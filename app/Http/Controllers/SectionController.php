<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;


class SectionController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("sections.index").'" class="btn btn-sm btn-success">ALL SECTION</a> &nbsp;';
        $this->buttons .= '<a href="'.route("sections.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('section.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Section";
    }
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = DB::table('sections')
                ->join('users', 'sections.added_by', '=', 'users.id')
                ->join('pages' , 'sections.page_id', '=', 'pages.id')
                ->leftJoin('users as users_updated', 'sections.updated_by', '=', 'users_updated.id')
                ->select('sections.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy','pages.name as page_name')
                ->whereNull('sections.deleted_at')
                ->get();
               
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('sections.edit', $row->id).'" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->created_at)->diffForHumans().'</label>';
                })
                ->addColumn('page_name', function($row){
                    return Str::of($row->page_name)->limit(100);
                })
                ->addColumn('order', function($row){
                    return Str::of($row->order)->limit(100);
                })
                ->addColumn('added_by', function($row){
                    return ''.date('d-M-Y', strtotime($row->created_at)).'<br/>'."By:".$row->first_name.' '.$row->last_name;
                })
                ->addColumn('updated_by', function($row){
                    if(isset($row->updatedBy)){
                        return ''.date('d-M-Y', strtotime($row->updated_at)).'<br/>'."By:".$row->updated_by_first_name.' '.$row->updated_by_last_name;
                    }else{
                        return  '-';
                    }
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="section_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="section_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->rawColumns(['action', 'created_at', 'is_active', 'order', 'added_by', 'updated_by','page_name'])
                ->make(true);
        }
        
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Section';

        return view('backend.section.all', $data);
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
        $data['parents'] = Page::get();
        $data['page_title'] = 'Add New Section';

        return view('backend.section.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'page_id' => 'required',
            'order' => 'required',
        ]);

        $Section = new Section();
        $Section->added_by = Auth::user()->id;
        $Section->name = $request->name;
        $Section->order = $request->order;
        $Section->page_id = $request->page_id;

        if($Section->save())
        {
            $data['type'] = "success";
            $data['message'] = "Section Added Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('sections.index')->with($data);
        }
        else
        {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Page, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('sections.index')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Section';
        $data['parents'] = Page::get();
        $data['page'] = Section::findOrFail($id);
        // $data['pages'] = Page::where('id' , $data['page']->page_id)->pluck('id')->toArray();;
        // dd($data['pages']);

        return view('backend.section.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Section = Section::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'page_id' => 'required',
            'order' => 'required',

     ]);

     $Section->updated_by = Auth::user()->id;
     $Section->name = $request->name;
     $Section->order = $request->order;
     $Section->page_id = $request->page_id;

     if($Section->save())
     {
         $data['type'] = "success";
         $data['message'] = "Section Updated Successfuly!.";
         $data['icon'] = 'mdi-check-all';

         return redirect()->route('sections.index')->with($data);
     }
     else
     {
         $data['type'] = "danger";
         $data['message'] = "Failed to Add Section, please try again.";
         $data['icon'] = 'mdi-block-helper';

         return redirect()->route('sections.index')->withInput()->with($data);
     }
    }

    public function destroy($id)
    {
        $Section = Section::find($id);
        if ($Section) {
            $Section->delete();
            $notification['type'] = "success";
            $notification['message'] = "Section Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Section, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function trash(Request $request)
    {
        if($request->ajax())
        {
            $data = DB::table('sections')
                ->join('users', 'sections.added_by', '=', 'users.id')
                ->leftJoin('users as users_updated', 'sections.updated_by', '=', 'users_updated.id')
                ->select('sections.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
                ->whereNotNull('sections.deleted_at')
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
                ->addColumn('order', function($row){
                    return Str::of($row->order)->limit(100);
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
                ->rawColumns(['action', 'deleted_at', 'status', 'order', 'added_by', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trash Section';
        return view('backend.section.trash', $data);
    }
    public function restoreService(Request $request)
    {
        $Section = Section::withTrashed()->find($request->id);
        if ($Section) {
            $Section->restore();
            $notification['type'] = "success";
            $notification['message'] = "Section Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Section, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        $update = Section::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Section Activated Successfuly!' : $message = 'Section Deactivated Successfuly!';

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
