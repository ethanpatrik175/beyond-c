<?php

namespace App\Http\Controllers;

use App\Models\post;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\Tag;
use App\Models\Category;
use App\Models\PostTag;
use Session;

class PostController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("blogs.index") . '" class="btn btn-sm btn-success">ALL Blogs</a> &nbsp;';
        $this->buttons .= '<a href="' . route("blogs.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="' . route('blog.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Blogs";
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = DB::table('posts')
                ->leftJoin('users', 'posts.user_id', '=', 'users.id')
                ->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
                ->leftJoin('users as users_updated', 'posts.updated_by', '=', 'users_updated.id')
                ->select('posts.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy', 'categories.title as category_title')
                ->whereNull('posts.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('blogs.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->created_at)->diffForHumans() . '</label>';
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="blogs_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="blogs_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if ($row->icon) {
                        $image = '<img src=' . asset('assets/frontend/images/posts/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(50);
                })
                ->addColumn('description', function ($row) {
                    return Str::of($row->description)->limit(50);
                })
                ->addColumn('category_title', function ($row) {
                    return Str::of($row->category_title)->limit(50);
                })
                ->addColumn('user_id', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)).'<br/>'.$row->first_name . ' ' . $row->last_name ;
                })
                ->addColumn('updated_by', function ($row) {
                    if (isset($row->updatedBy)) {
                        return $row->updated_by_first_name . ' ' . $row->updated_by_last_name . ' <br />(' . Str::of($row->updatedBy)->upper() . ')';
                    } else {
                        return  '-';
                    }
                })
                ->rawColumns(['action','is_active', 'icon', 'description', 'user_id', 'updated_by', 'category_title','title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Blogs';
        return view('backend.blogs.all', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['tags'] = Tag::get();
        $data['Category'] = Category::where('is_active', 1)->whereNull('deleted_at')->get();
        $data['page_title'] = 'Add New Blogs';
        return view('backend.blogs.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        

        $request->validate([
            'Title' => 'required|max:100',
            'Slug' => 'required|max:100',
            'description' => 'required',
            'content' => 'required',
            'image' => 'required|image|max:1024'
        ], [
            'image.required' => 'Please upload valid image'
        ]);

        $post = new post();
        $post->user_id = Auth::user()->id;
        $post->slug = Str::slug($request->Slug);
        $post->title = $request->Title;
        $post->description = $request->description;
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        $meta_data['meta_title'] = $request->meta_title;
        $meta_data['meta_description'] = $request->meta_description;
        $meta_data['meta_keywords'] = $request->meta_keywords;
        $post->meta_data = json_encode($meta_data);
        $image = $request->file('image');
        if ($image->move('assets/frontend/images/posts/', $image->getClientOriginalName())) {

            $post->icon = $image->getClientOriginalName();
            if ($post->save()) {
                foreach ($request->tag_id as $PostCategory) {
                    $PostCategories = new PostTag();
                    $PostCategories->post_id = $post->id;
                    $PostCategories->tag_id = $PostCategory;
                    $PostCategories->save();
                }
                $data['type'] = "success";
                $data['message'] = "Post Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('blogs.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add post, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('blogs.create')->withInput()->with($data);
            }
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('blogs.create')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(post $post)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Blogs';
        $data['service'] = post::findOrFail($id);
        $data['tags'] = Tag::where('is_active', 1)->whereNull('deleted_at')->get();
        $data['Categories'] = Category::where('is_active', 1)->whereNull('deleted_at')->get();
        $data['PostTag'] = PostTag::where('post_id', $id)->get()->pluck('tag_id')->toArray();

        return view('backend.blogs.edit', $data);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'Title' => 'required|max:100',
            'slug' => 'required|max:100',
            'description' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|max:1024'
        ]);

        $post = post::findOrFail($id);

        $post->updated_by = Auth::user()->id;
        $post->user_id = Auth::user()->id;
        $post->slug = Str::slug($request->slug);
        $post->title = $request->Title; 
        $post->description = $request->description;
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        $meta_data['meta_title'] = $request->meta_title;
        $meta_data['meta_description'] = $request->meta_description;
        $meta_data['meta_keywords'] = $request->meta_keywords;
        $post->meta_data = json_encode($meta_data);
        $delete_post_tag = PostTag::where('post_id', $id)->get();
        $delete_post_tag->each->delete();
        
        if($request->tag_id){
            foreach ($request->tag_id as $PostCategory) {
                $PostCategories = new PostTag();
                $PostCategories->post_id = $id;
                $PostCategories->tag_id = $PostCategory;
                $PostCategories->save();
            }
        }

        if ($request->file('image')) {
            $request->validate([
                'image' => 'required|image|max:1024'
            ], [
                'image.required' => 'Please upload valid image'
            ]);

            $image = $request->file('image');
            if ($image->move('assets/frontend/images/posts/', $image->getClientOriginalName())) {

                $post->icon = $image->getClientOriginalName();
                if ($post->save()) {



                    $data['type'] = "success";
                    $data['message'] = "Blogs Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';

                    return redirect()->route('blogs.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Blogs, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('blogs.edit', $request->id)->withInput()->with($data);
                }
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to upload image, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('blogs.edit', $request->id)->withInput()->with($data);
            }
        } else {
            if ($post->save()) {
                $data['type'] = "success";
                $data['message'] = "Blogs Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('blogs.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Update Blogs, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('blogs.edit', $request->id)->withInput()->with($data);
            }
        }
    }


    public function destroy($id)
    {
        $post = post::find($id);
        $post->deleted_by = Auth::user()->id;
        if ($post) {
            $post->delete();
            $notification['type'] = "success";
            $notification['message'] = "Blogs Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Blogs, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        $update = post::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Blogs Activated Successfuly!' : $message = 'Blogs Deactivated Successfuly!';

            $notification = array(
                'message' => $message,
                'type' => $alertType,
                'icon' => 'mdi-check-all'
            );
        } else {
            $notification = array(
                'message' => 'Some Error Occured, Try Again!',
                'type' => 'error',
                'icon'  => 'mdi-block-helper'
            );
        }

        echo json_encode($notification);
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('posts')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->leftJoin('users as users_updated', 'posts.updated_by', '=', 'users_updated.id')
                ->select('posts.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
                ->whereNotNull('posts.deleted_at')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" class="restore" data-id="' . $row->id . '"><i title="Restore" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->deleted_at)->diffForHumans() . '</label>';
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if ($row->icon) {
                        $image = '<img src=' . asset('assets/frontend/images/posts/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('description', function ($row) {
                    return Str::of($row->description)->limit(100);
                })
                ->addColumn('added_by', function ($row) {
                    return $row->first_name . ' ' . $row->last_name . ' <br />(' . Str::of($row->addedBy)->upper() . ')';
                })
                ->addColumn('updated_by', function ($row) {
                    if (isset($row->updatedBy)) {
                        return $row->updated_by_first_name . ' ' . $row->updated_by_last_name . ' <br />(' . Str::of($row->updatedBy)->upper() . ')';
                    } else {
                        return  '-';
                    }
                })
                ->rawColumns(['action', 'deleted_at', 'status', 'icon', 'description', 'added_by', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trash Blogs';
        return view('backend.blogs.trash', $data);
    }

    public function restorePost(Request $request)
    {
        $posts = post::withTrashed()->find($request->id);
        if ($posts) {
            $posts->restore();
            $notification['type'] = "success";
            $notification['message'] = "Blogs Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Blogs, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
