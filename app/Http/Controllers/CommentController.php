<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\post;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;


class CommentController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("comments.index") . '" class="btn btn-sm btn-success">ALL Comments</a> &nbsp;';
        $this->buttons .= '<a href="'.route("status.approved").'" class="btn btn-sm btn-primary">Approved Comment </a> &nbsp;';
        $this->buttons .= '<a href="'.route('status.non.approved').'" class="btn btn-sm btn-danger">Un-Approved Comment</a>';
        $this->section = "Comments";
    }

    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $data = DB::table('post_comments')
                ->join('users', 'post_comments.user_id', '=', 'users.id')
                ->leftJoin('posts as a', 'post_comments.post_id', '=', 'a.id')
                ->select('post_comments.*', 'users.first_name', 'users.last_name','a.title')
                ->whereNull('post_comments.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('comments.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    // $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                // ->addColumn('created_at', function ($row) {
                //     return date('d-M-Y', strtotime($row->created_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->created_at)->diffForHumans() . '</label>';
                // })
                ->addColumn('status_order', function ($row) {
                    if ($row->status == 'Un-Approved') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="Approved"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->status == 'Approved') {
                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="Un-Approved" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
               
                ->addColumn('comment', function ($row) {
                    return Str::of($row->comment)->limit(50);
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(50);
                })
                ->addColumn('status', function ($row) {
                    return Str::of($row->status);
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)).'<br/>'.$row->first_name . ' ' . $row->last_name ;
                })
               
                ->rawColumns(['action', 'created_at','updated_by' ,'status','status_order','title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Comments';

        return view('backend.comment.all', $data);
    }
    






    public function create()
    {
        abort(404);
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Comments';
        $data['post'] = post::get();
        $data['parents'] = Comment::get();

        return view('backend.comment.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required|max:200',
        ]);

        $Comment = new Comment();
        // $Comment->user_id = Auth::user()->id;
        $Comment->parent_id = $request->parent_id;
        $Comment->post_id = $request->post_id;
        $Comment->comment = $request->message;
        $Comment->name = $request->name;
        $Comment->email = $request->email;


        if ($Comment->save()) {
            $data['type'] = "success";
            $data['message'] = "Comments Added Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return response()->json(['success'=>'Your comment has been saved successfully, it will posted as it is approved by admin.']);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Comments, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return response()->json(['success'=>'Failed to Add Comments']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update COMMENTS';
        $data['Comments'] = Comment::findOrFail($id);
        $data['parents'] = Comment::get();
        $data['parents_id'] = Comment::where('id', $data['Comments']->parent_id)->get()->toArray();
        $data['post'] = post::get();
        $data['post_id'] = post::where('id', $data['Comments']->post_id)->get()->toArray();
        return view('backend.comment.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    //    dd($request->all());
        $Comment = Comment::findOrFail($id);
        //     $this->validate($request, [
        //         'name' => 'required',
        //         'page_id' => 'required',
        //         'order' => 'required',

        //  ]);


        $Comment->status = $request->status;
        if ($Comment->save()) {
            $data['type'] = "success";
            $data['message'] = "Comment Updated Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('comments.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Section, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('comments.index')->withInput()->with($data);
        }
    }

    public function destroy($id)
    {
        abort(404);
        $Comment = Comment::find($id);
        if ($Comment) {
            $Comment->delete();
            $notification['type'] = "success";
            $notification['message'] = "Comment Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Comment, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function trash(Request $request)
    {
        abort(404);
        if ($request->ajax()) {
            $data = DB::table('post_comments')
                ->join('users', 'post_comments.user_id', '=', 'users.id')
                // ->leftJoin('users as users_updated', 'posts.updated_by', '=', 'users_updated.id')
                ->select('post_comments.*', 'users.first_name', 'users.last_name')
                ->whereNotNull('post_comments.deleted_at')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
             
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->deleted_at)->diffForHumans() . '</label>';
                })
                ->addColumn('user_id', function ($row) {
                    return $row->first_name . ' ' . $row->last_name . ' <br />(' . Str::of($row->user_id)->upper() . ')';
                })
                ->addColumn('updated_by', function ($row) {
                    if (isset($row->updatedBy)) {
                        return $row->updated_by_first_name . ' ' . $row->updated_by_last_name . ' <br />(' . Str::of($row->updatedBy)->upper() . ')';
                    } else {
                        return  '-';
                    }
                })
                ->rawColumns(['action', 'deleted_at', 'user_id', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trash Comment';
        return view('backend.comment.trash', $data);
    }
    public function restorecomments(Request $request)
    {
        abort(404);
        $Comment = Comment::withTrashed()->find($request->id);
        if ($Comment) {
            $Comment->restore();
            $notification['type'] = "success";
            $notification['message'] = "Comment Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Comment, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
       
       
        $update = Comment::where('id', $request->id)->update(['status' => $request->status]);

        if ($update) {
            $request->status == 'Approved' ? $alertType = 'success' : $alertType = 'info';
            $request->status == 'Approved' ? $message = 'Comment Approved Successfuly!' : $message = 'Comment Un-Approved Successfuly!';

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
    public function approved(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('post_comments')
                ->join('users', 'post_comments.user_id', '=', 'users.id')
                ->leftJoin('posts as a', 'post_comments.post_id', '=', 'a.id')
                ->select('post_comments.*', 'users.first_name', 'users.last_name' ,'a.title')
                ->where('post_comments.status',"Approved")
                ->whereNull('post_comments.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('comments.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    // $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                // ->addColumn('created_at', function ($row) {
                //     return date('d-M-Y', strtotime($row->created_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->created_at)->diffForHumans() . '</label>';
                // })
                ->addColumn('status_order', function ($row) {
                    if ($row->status == 'Un-Approved') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="Approved"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->status == 'Approved') {
                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="Un-Approved" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(50);
                })
                ->addColumn('comment', function ($row) {
                    return Str::of($row->comment)->limit(50);
                })
                ->addColumn('status', function ($row) {
                    return Str::of($row->status);
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)).'<br/>'.$row->first_name . ' ' . $row->last_name ;
                })
               
                ->rawColumns(['action', 'created_at','updated_by' ,'status','status_order','title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Comments';

        return view('backend.comment.approved', $data);
    }
    public function non_approved(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('post_comments')
                ->join('users', 'post_comments.user_id', '=', 'users.id')
                ->leftJoin('posts as a', 'post_comments.post_id', '=', 'a.id')
                ->select('post_comments.*', 'users.first_name', 'users.last_name','a.title')
                ->where('post_comments.status',"Un-Approved")
                ->whereNull('post_comments.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('comments.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    // $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                // ->addColumn('created_at', function ($row) {
                //     return date('d-M-Y', strtotime($row->created_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->created_at)->diffForHumans() . '</label>';
                // })
                ->addColumn('status_order', function ($row) {
                    if ($row->status == 'Un-Approved') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="Approved"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->status == 'Approved') {
                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="Un-Approved" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
               
                ->addColumn('comment', function ($row) {
                    return Str::of($row->comment)->limit(50);
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(50);
                })
                ->addColumn('status', function ($row) {
                    return Str::of($row->status);
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)).'<br/>'.$row->first_name . ' ' . $row->last_name ;
                })
               
                ->rawColumns(['action', 'created_at','updated_by' ,'status','status_order','title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Comments';

        return view('backend.comment.nonapproved', $data);
    }
}
