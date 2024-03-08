<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = BlogCategory::latest()->get();
        return view('admin.backend.blogcategory.blog_category',compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $categories = BlogCategory::find($id);
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        BlogCategory::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace([' ', '/'],'-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'BlogCategory Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogCategory $blogCategory)
    {
        $cat_id = $request->cat_id;

        $blogCategory::find($cat_id)->update([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace([' ', '/'],'-',$request->category_name)),
        ]);

        $notification = array(
            'message' => 'BlogCategory Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory, $id)
    {
        $blogCategory::find($id)->delete();

        $notification = array(
            'message' => 'BlogCategory Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function BlogPost(){
        $post = BlogPost::latest()->get();
        return view('admin.backend.post.all_post',compact('post'));
    }

    public function AddBlogPost(){
        $blogcat = BlogCategory::latest()->get();
        return view('admin.backend.post.add_post',compact('blogcat'));
    }

    public function StoreBlogPost(Request $request){

        $image = $request->file('post_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(370,247)->save('upload/post/'.$name_gen);
        $save_url = 'upload/post/'.$name_gen;

        BlogPost::insert([
            'blogcat_id' => $request->blogcat_id,
            'post_title' => $request->post_title,
            'post_slug' => strtolower(str_replace([' ', '/'],'-',$request->post_title)),
            'long_desc' => $request->long_desc,
            'post_tags' => $request->post_tags,
            'post_image' => $save_url,
            'created_at' => Carbon::now(),

        ]);

        $notification = array(
            'message' => 'Blog Post Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('blog.post')->with($notification);

    }

    public function EditBlogPost($id){

        $blogcat = BlogCategory::latest()->get();
        $post = BlogPost::find($id);
        return view('admin.backend.post.edit_post',compact('post','blogcat'));

    }


    public function UpdateBlogPost(Request $request){

        $post_id = $request->id;

        if ($request->file('post_image')) {

            $image = $request->file('post_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(370,247)->save('upload/post/'.$name_gen);
            $save_url = 'upload/post/'.$name_gen;

            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace([' ', '/'],'-',$request->post_title)),
                'long_desc' => $request->long_desc,
                'post_tags' => $request->post_tags,
                'post_image' => $save_url,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('blog.post')->with($notification);

        } else {

            BlogPost::find($post_id)->update([
                'blogcat_id' => $request->blogcat_id,
                'post_title' => $request->post_title,
                'post_slug' => strtolower(str_replace([' ', '/'],'-',$request->post_title)),
                'long_desc' => $request->long_desc,
                'post_tags' => $request->post_tags,
                'created_at' => Carbon::now(),

            ]);

            $notification = array(
                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('blog.post')->with($notification);

        } // end else

    }

    public function DeleteBlogPost($id){

        $item = BlogPost::find($id);
        $img = $item->post_image;
        unlink($img);

        BlogPost::find($id)->delete();

        $notification = array(
            'message' => 'Blog Post Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function BlogDetails($slug){
        $blog = BlogPost::where('post_slug',$slug)->first();
        $tags = $blog->post_tags;
        $tags_all = explode(',',$tags);
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();
        return view('frontend.blog.blog_details',compact('blog','tags_all','bcategory','post'));
    }

    public function BlogCatList($id){
        $blog = BlogPost::where('blogcat_id',$id)->get();
        $breadcat = BlogCategory::where('id',$id)->first();
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();
        return view('frontend.blog.blog_cat_list',compact('blog','breadcat','bcategory','post'));
    }

    public function BlogList(){
        $blog = BlogPost::latest()->get();
        $bcategory = BlogCategory::latest()->get();
        $post = BlogPost::latest()->limit(3)->get();
        return view('frontend.blog.blog_list',compact('blog','bcategory','post'));
    }
}
