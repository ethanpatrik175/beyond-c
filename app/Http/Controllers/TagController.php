<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("tags.index").'" class="btn btn-sm btn-success">ALL Tags</a> &nbsp;';
        $this->buttons .= '<a href="'.route("tags.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('tag.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Tags";
    }
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = DB::table('tags')
                ->join('users', 'tags.added_by', '=', 'users.id')
                ->leftJoin('users as users_updated', 'tags.updated_by', '=', 'users_updated.id')
                ->select('tags.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
                ->whereNull('tags.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('tags.edit', $row->id).'" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
               
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="tags_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="tags_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if($row->icon){
                        $image = '<img src=' . asset('assets/frontend/images/tags/' . $imageName) . ' class="avatar-sm" />';
                    }else{
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                // ->addColumn('description', function($row){
                //     return Str::of($row->description)->limit(100);
                // })
                ->addColumn('added_by', function($row){
                    return date('d-M-Y', strtotime($row->created_at)).'<br/>'.$row->first_name.' '.$row->last_name.' <br />('.Str::of($row->addedBy)->upper().')';
                })
                ->addColumn('updated_by', function($row){
                    if(isset($row->updatedBy)){
                        return $row->updated_by_first_name.' '.$row->updated_by_last_name.' <br />('.Str::of($row->updatedBy)->upper().')';
                    }else{
                        return  '-';
                    }
                })
                ->rawColumns(['action', 'is_active', 'icon', 'added_by', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Tags';
            
        return view('backend.tags.all', $data);
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
        $data['page_title'] = 'Add New Tags';

        return view('backend.tags.add', $data);
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
        ]);

        $post = new Tag();
        $post->added_by = Auth::user()->id;
        $post->slug = Str::slug($request->Slug);
        $post->title = $request->Title;
        // $post->metadata = $request->metadata;
            if($request->file('image') == null){
                if($post->save())
                {
                    $data['type'] = "success";
                    $data['message'] = "Category Added Successfuly!.";
                    $data['icon'] = 'mdi-check-all';
        
                    return redirect()->route('tags.index')->with($data);
                }
                else
                {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Add Category, please try again.";
                    $data['icon'] = 'mdi-block-helper';
        
                    return redirect()->route('tags.index')->withInput()->with($data);
                }
            }else{
                $image = $request->file('image');
                if ($image->move('assets/frontend/images/tags/', $image->getClientOriginalName())) {
        
                    $post->icon = $image->getClientOriginalName();
                    if($post->save())
                    {
                        $data['type'] = "success";
                        $data['message'] = "Tags Added Successfuly!.";
                        $data['icon'] = 'mdi-check-all';
        
                        return redirect()->route('tags.index')->with($data);
                    }
                    else
                    {
                        $data['type'] = "danger";
                        $data['message'] = "Failed to Add post, please try again.";
                        $data['icon'] = 'mdi-block-helper';
        
                        return redirect()->route('tags.create')->withInput()->with($data);
                    }
        
                }
                else{
                    $data['type'] = "danger";
                    $data['message'] = "Failed to upload image, please try again.";
                    $data['icon'] = 'mdi-block-helper';
        
                    return redirect()->route('tags.create')->withInput()->with($data);
                }
            }
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Tags';
        $data['service'] =Tag::findOrFail($id);

        return view('backend.tags.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Title' => 'required|max:100',
            'slug' => 'required|max:100',
        ]);
        $Tag = Tag::findOrFail($id);
       
        $Tag->updated_by = Auth::user()->id;
        $Tag->slug = Str::slug($request->slug);
        $Tag->title = $request->Title;
        // $Tag->metadata = $request->metadata;

        if($request->file('image') == null){
            if($Tag->save())
            {
                $data['type'] = "success";
                $data['message'] = "Tags Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';
       
                return redirect()->route('tags.index')->with($data);
            }
            else
            {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Tags, please try again.";
                $data['icon'] = 'mdi-block-helper';
       
                return redirect()->route('tags.index')->withInput()->with($data);
            }
        
        }else{
            if($request->file('image')){
                $request->validate([
                    'image' => 'required|image|max:1024'
                ],[
                    'image.required'=>'Please upload valid image'
                ]);
    
                $image = $request->file('image');
                if ($image->move('assets/frontend/images/tags/', $image->getClientOriginalName())) {
    
                    $Tag->icon = $image->getClientOriginalName();
                    if($Tag->save())
                    {
                        $data['type'] = "success";
                        $data['message'] = "Tags Updated Successfuly!.";
                        $data['icon'] = 'mdi-check-all';
    
                        return redirect()->route('tags.index')->with($data);
                    }
                    else
                    {
                        $data['type'] = "danger";
                        $data['message'] = "Failed to Update tags, please try again.";
                        $data['icon'] = 'mdi-block-helper';
    
                        return redirect()->route('tags.edit', $request->id)->withInput()->with($data);
                    }
                }
                else{
                    $data['type'] = "danger";
                    $data['message'] = "Failed to upload image, please try again.";
                    $data['icon'] = 'mdi-block-helper';
    
                    return redirect()->route('tags.edit', $request->id)->withInput()->with($data);
                }
            }else{
                if($Tag->save())
                {
                    $data['type'] = "success";
                    $data['message'] = "Tags Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';
    
                    return redirect()->route('tags.index')->with($data);
                }
                else
                {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update tags, please try again.";
                    $data['icon'] = 'mdi-block-helper';
    
                    return redirect()->route('tags.edit', $request->id)->withInput()->with($data);
                }
            }
        }
      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Tags = Tag::find($id);
        $Tags->deleted_by = Auth::user()->id;
        if ($Tags) {
            $Tags->delete();
            $notification['type'] = "success";
            $notification['message'] = "Tags Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Tags, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        
        $update = Tag::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Tag Activated Successfuly!' : $message = 'Tag Deactivated Successfuly!';

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
        if($request->ajax())
        {
            $data = DB::table('tags')
                ->join('users', 'tags.added_by', '=', 'users.id')
                ->leftJoin('users as users_updated', 'tags.updated_by', '=', 'users_updated.id')
                ->select('tags.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
                ->whereNotNull('tags.deleted_at')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" class="restore" data-id="' . $row->id . '"><i title="Restore" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->deleted_at)->diffForHumans().'</label>';
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if($row->icon){
                        $image = '<img src=' . asset('assets/frontend/images/posts/' . $imageName) . ' class="avatar-sm" />';
                    }else{
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="tags_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="tags_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('added_by', function($row){
                    return $row->first_name.' '.$row->last_name.' <br />('.Str::of($row->addedBy)->upper().')';
                })
                ->addColumn('updated_by', function($row){
                    if(isset($row->updatedBy)){
                        return $row->updated_by_first_name.' '.$row->updated_by_last_name.' <br />('.Str::of($row->updatedBy)->upper().')';
                    }else{
                        return  '-';
                    }
                })
                ->rawColumns(['action', 'deleted_at', 'is_active', 'icon','added_by', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trash Tags';
       
        return view('backend.tags.trash', $data);
    }

    public function restoretags (Request $request)
    {
        $Tag = Tag::withTrashed()->find($request->id);
        if ($Tag) {
            $Tag->restore();
            $notification['type'] = "success";
            $notification['message'] = "Tags Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Tags, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
}
