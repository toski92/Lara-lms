<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\Orderconfirm;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\InvoicePaid;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::content();
        $cartTotal = Cart::total(2,'.',',');
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $course = Course::find($id);
        if ($request->session()->has('coupon')) {
            $request->session()->forget('coupon');
        }

        // Check if the course is already in the cart
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }
        $options = [
            'image' => $course->feature_image,
            'slug' => $request->slug,
            'instructor' => $request->instructor,
        ];

        if ($course->discount_price == NULL) {

            Cart::add($id,$request->course_name,1,$course->selling_price,1,$options);

        }else{

            Cart::add($id,$request->course_name,1,$course->discount_price,1,$options);
        }

        return response()->json(['success' => 'Successfully Added on Your Cart']);
    }

    public function MyCart(){

        return view('frontend.mycart.view_mycart');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $rowId)
    {
        Cart::remove($rowId);
        return response()->json(['success' => 'Course Remove From Cart']);
    }
    public function CouponApply(Request $request){
        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();

        if ($coupon) {
            $request->session()->put('coupon',[
                'coupon_name' => $coupon->coupon_name,
                'coupon_discount' => $coupon->coupon_discount,
                'discount_amount' => round(Cart::total(2,'.',',') * $coupon->coupon_discount/100),
                'total_amount' => round(Cart::total(2,'.',',') - Cart::total(2,'.',',') * $coupon->coupon_discount/100 )
            ]);

            return response()->json(array(
                'validity' => true,
                'success' => 'Coupon Applied Successfully'
            ));

        }else {
            return response()->json(['error' => 'Invaild Coupon']);
        }
    }

    public function CouponCalculation(Request $request){

        if ($request->session()->has('coupon')) {
           return response()->json(array(
            'subtotal' => Cart::total(2,'.',','),
            'coupon_name' => session()->get('coupon')['coupon_name'],
            'coupon_discount' => session()->get('coupon')['coupon_discount'],
            'discount_amount' => session()->get('coupon')['discount_amount'],
            'total_amount' => session()->get('coupon')['total_amount'],
           ));
        } else{
            return response()->json(array(
                'total' => Cart::total(2,'.',','),
            ));
        }

    }

    public function CouponRemove(Request $request){

        $request->session()->forget('coupon');
        return response()->json(['success' => 'Coupon Remove Successfully']);

    }

    public function CheckoutCreate(){

        if (Auth::check()) {

            if (Cart::total(2,'.',',') > 0) {
                $carts = Cart::content();
                $cartTotal = Cart::total(2,'.',',');
                $cartQty = Cart::count();

                return view('frontend.checkout.checkout_view',compact('carts','cartTotal','cartQty'));
            } else{

                $notification = array(
                    'message' => 'Add At list One Course',
                    'alert-type' => 'error'
                );
                return redirect()->to('/')->with($notification);

            }

        }else{

            $notification = array(
                'message' => 'You Need to Login First',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification);

        }

    }
    public function Payment(Request $request){
        $user = User::where('role','instructor')->get();

        if ($request->session()->has('coupon')) {
           $total_amount = session()->get('coupon')['total_amount'];
        }else {
            $total_amount = round(Cart::total());
        }

        $payment = array();
        $payment['name'] = $request->name;
        $payment['email'] = $request->email;
        $payment['phone'] = $request->phone;
        $payment['address'] = $request->address;
        $payment['course_title'] = $request->course_title;
        $cartTotal = Cart::total();
        $carts = Cart::content();

        if ($request->cash_delivery == 'stripe') {
            return view('frontend.payment.stripe',compact('payment','cartTotal','carts'));
        }elseif($request->cash_delivery == 'handcash'){

            // Cerate a new Payment Record

            $payment = new Payment();
            $payment->name = $request->name;
            $payment->email = $request->email;
            $payment->phone = $request->phone;
            $payment->address = $request->address;
            $payment->cash_delivery = $request->cash_delivery;
            $payment->total_amount = $total_amount;
            $payment->payment_type = 'Direct Payment';

            $payment->invoice_no = 'EOS' . mt_rand(10000000, 99999999);
            $payment->order_date = Carbon::now()->format('d F Y');
            $payment->order_month = Carbon::now()->format('F');
            $payment->order_year = Carbon::now()->format('Y');
            $payment->status = 'pending';
            $payment->created_at = Carbon::now();
            $payment->save();

            foreach ($request->course_title as $key => $course_title) {

                $existingOrder = Order::where('user_id',Auth::user()->id)->where('course_id',$request->course_id[$key])->first();

                if ($existingOrder) {

                    $notification = array(
                        'message' => 'You Have already enrolled in this course',
                        'alert-type' => 'error'
                    );
                    return redirect()->back()->with($notification);
                } // end if

                $order = new Order();
                $order->payment_id = $payment->id;
                $order->user_id = Auth::user()->id;
                $order->course_id = $request->course_id[$key];
                $order->instructor_id = $request->instructor_id[$key];
                $order->course_title = $course_title;
                $order->price = $request->price[$key];
                $order->save();

            } // end foreach

            $request->session()->forget('cart');

            $paymentId = $payment->id;

            /// Start Send email to student ///
            $sendmail = Payment::find($paymentId);
            $data = [
                'invoice_no' => $sendmail->invoice_no,
                'amount' => $total_amount,
                'name' => $sendmail->name,
                'email' => $sendmail->email,
            ];

            Mail::to($request->email)->send(new Orderconfirm($data));
            /// End Send email to student ///
            /// Send Notification
            Notification::send($user, new InvoicePaid($request->name));
            $notification = array(
                'message' => 'Cash Payment Submit Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('index')->with($notification);
        }
    }

    public function StripeOrder(Request $request){
        if ($request->session()->has('coupon')) {
            $total_amount = $request->session()->get('coupon')['total_amount'];
         }else {
             $total_amount = round(Cart::total());
         }

         \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

         $token = $_POST['stripeToken'];

         $charge = \Stripe\Charge::create([
            'amount' => $total_amount*100,
            'currency' => 'usd',
            'description' => 'Lms',
            'source' => $token,
            'metadata' => ['order_id' => '3434'],
         ]);

         $order_id = Payment::insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_amount' => $total_amount,
            'payment_type' => 'Stripe',
            'invoice_no' => 'EOS' . mt_rand(10000000, 99999999),
            'order_date' => Carbon::now()->format('d F Y'),
            'order_month' => Carbon::now()->format('F'),
            'order_year' => Carbon::now()->format('Y'),
            'status' => 'pending',
            'created_at' => Carbon::now(),

        ]);
        $carts = Cart::content();
         foreach ($carts as $cart) {
            Order::insert([
                'payment_id' => $order_id,
                'user_id' => Auth::user()->id,
                'course_id' => $cart->id,
                'instructor_id' => $cart->options->instructor,
                'course_title' => $cart->options->name,
                'price' => $cart->price,
            ]);
         }// end foreach

         if ($request->session()->has('coupon')) {
            $request->session()->forget('coupon');
         }
         Cart::destroy();

         $notification = array(
            'message' => 'Stripe Payment Submit Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('index')->with($notification);

    }

    public function BuyToCart(Request $request, $id){

        $course = Course::find($id);

        // Check if the course is already in the cart
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }

        if ($course->discount_price == NULL) {

            Cart::add($id,$request->course_name,1,$course->selling_price,1,[
                    'image' => $course->feature_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            );

        }else{

            Cart::add($id,$request->course_name,1,$course->discount_price,1,[
                    'image' => $course->feature_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            );
        }

        return response()->json(['success' => 'Successfully Added on Your Cart']);

    }

    public function InsCouponApply(Request $request){
        $coupon = Coupon::where('coupon_name',$request->coupon_name)->where('coupon_validity','>=',Carbon::now()->format('Y-m-d'))->first();

        if ($coupon) {
            if ($coupon->course_id == $request->course_id && $coupon->instructor_id == $request->instructor_id) {

                $request->session()->put('coupon',[
                    'coupon_name' => $coupon->coupon_name,
                    'coupon_discount' => $coupon->coupon_discount,
                    'discount_amount' => round(Cart::total() * $coupon->coupon_discount/100),
                    'total_amount' => round(Cart::total() - Cart::total() * $coupon->coupon_discount/100 )
                ]);

                return response()->json(array(
                    'validity' => true,
                    'success' => 'Coupon Applied Successfully'
                ));

            } else {
                return response()->json(['error' => 'Coupon Criteria Not Met for this course and instructor']);
            }
        } else {
            return response()->json(['error' => 'Invalid Coupon']);
        }
    }
    public function MarkAsRead(Request $request, $notificationId){

        $user = Auth::user();
        $notification = $user->notifications()->where('id',$notificationId)->first();

        if ($notification) {
            $notification->markAsRead();

        }
        return response()->json(['count' => $user->unreadNotifications()->count()]);

    }
}
