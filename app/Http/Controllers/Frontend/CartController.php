<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

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
}
