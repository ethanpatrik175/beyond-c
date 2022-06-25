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
        // $this->buttons .= '<a href="'.route("comments.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        // $this->buttons .= '<a href="'.route('comment.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Comments";
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('post_comments')
                ->join('users', 'post_comments.user_id', '=', 'users.id')

                ->select('post_comments.*', 'users.first_name', 'users.last_name')
                ->whereNull('post_comments.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('comments.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->created_at)->diffForHumans() . '</label>';
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="comments_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
               
                ->addColumn('comment', function ($row) {
                    return Str::of($row->comment)->limit(100);
                })
                ->addColumn('user_id', function ($row) {
                    return $row->first_name . ' ' . $row->last_name . ' <br />(' . Str::of($row->user_id)->upper() . ')';
                })
               
                ->rawColumns(['action', 'created_at', 'is_active', 'user_id', 'updated_by'])
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
        abort(404);
        // $this->validate($request, [
        //     'name' => 'required',
        //     'page_id' => 'required',
        //     'order' => 'required',
        // ]);

        $Comment = new Comment();
        $Comment->user_id = Auth::user()->id;
        $Comment->parent_id = $request->parent_id;
        $Comment->post_id = $request->post_id;
        $Comment->comment = $request->comment;

        if ($Comment->save()) {
            $data['type'] = "success";
            $data['message'] = "Comments Added Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('comments.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Page, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('comments.index')->withInput()->with($data);
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
        abort(404);
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
        abort(404);
        $Comment = Comment::findOrFail($id);
        //     $this->validate($request, [
        //         'name' => 'required',
        //         'page_id' => 'required',
        //         'order' => 'required',

        //  ]);


        $Comment->parent_id = $request->parent_id;
        $Comment->post_id = $request->post_id;
        $Comment->comment = $request->comment;

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
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" class="restore" data-id="' . $row->id . '"><i title="Restore" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
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
        abort(404);
        // dd($request->all());
        $update = Comment::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Comment Activated Successfuly!' : $message = 'Comment Deactivated Successfuly!';

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
}
