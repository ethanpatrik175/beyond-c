<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;


class PageController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("page.index") . '" class="btn btn-sm btn-success">ALL Pages</a> &nbsp;';
        $this->buttons .= '<a href="' . route("page.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="' . route('pages.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Pages";
    }

    public function index(Request $request)
    {
        // $data = DB::table('pages')
        // ->join('users', 'pages.added_by', '=', 'users.id')
        // ->leftJoin('users as users_updated', 'pages.updated_by', '=', 'users_updated.id')
        // ->select('pages.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
        // ->whereNull('pages.deleted_at')
        // ->get();
        if ($request->ajax()) {
            $data = DB::table('pages')
                ->join('users', 'pages.added_by', '=', 'users.id')
                ->leftJoin('users as users_updated', 'pages.updated_by', '=', 'users_updated.id')
                ->select('pages.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
                ->whereNull('pages.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('page.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                // ->addColumn('is_active', function ($row) {
                //     if ($row->is_active == 0) {

                //         $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="anncoument_status" switch="bool" data-id="' . $row->id . '" value="active"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                //         return $btn0;
                //     } elseif ($row->is_active == 1) {

                //         $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="anncoument_status" switch="bool" data-id="' . $row->id . '" value="inactive" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                //         return $btn1;
                //     }
                // })
                // ->addColumn('image', function ($row) {
                //     $imageName = Str::of($row->icon)->replace(' ', '%20');
                //     if($row->icon){
                //         $image = '<img src=' . asset('assets/frontend/images/announcement/' . $imageName) . ' class="avatar-sm" />';
                //     }else{
                //         $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                //     }
                //     return $image;
                // })
                ->addColumn('order', function ($row) {
                    return Str::of($row->order)->limit(100);
                })
                ->addColumn('added_by', function ($row) {
                    return $row->first_name . ' ' . $row->last_name . '<br/>(' . date('d-M-Y', strtotime($row->created_at)) . ')';
                })
                ->addColumn('updated_by', function ($row) {
                    if (isset($row->updatedBy)) {
                        return $row->updated_by_first_name . ' ' . $row->updated_by_last_name . '<br/>(' . date('d-M-Y', strtotime($row->updated_at)) . ')';
                    } else {
                        return  '-';
                    }
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="page_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="page_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->rawColumns(['action', 'created_at', 'is_active', 'order', 'added_by', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Pages';

        return view('backend.pages.all', $data);
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
        $data['page_title'] = 'Add New Pages';

        return view('backend.pages.add', $data);
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
            'slug' => 'required',
            'order' => 'required',
            // 'link' => 'required',
        ]);

        $Page = new Page();
        $Page->added_by = Auth::user()->id;
        $Page->name = $request->name;
        $Page->slug = $request->slug;
        $Page->order = $request->order;
        $Page->link = $request->link;
        $Page->parent_id = $request->parent_id;

        if ($Page->save()) {
            $data['type'] = "success";
            $data['message'] = "Page Added Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('page.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Page, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('page.index')->withInput()->with($data);
        }
    }


    public function show(Page $page)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Page';
        $data['parents'] = Page::get();
        $data['page'] = Page::findOrFail($id);
        if (isset($data['page']->parent_id)) {
            $data['parent'] = Page::where('id', $data['page']->parent_id)->get()->toArray();
        } else {
            $data['parent'] = array();
        }
        return view('backend.pages.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Page = Page::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
            'order' => 'required',
        ]);

        $Page->updated_by = Auth::user()->id;
        $Page->name = $request->name;
        $Page->slug = $request->slug;
        $Page->order = $request->order;
        $Page->link = $request->link;
        $Page->parent_id = $request->parent_id;

        if ($Page->save()) {
            $data['type'] = "success";
            $data['message'] = "Page Updated Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('page.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Page, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('page.index')->withInput()->with($data);
        }
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        if ($page) {
            $page->delete();
            $notification['type'] = "success";
            $notification['message'] = "Page Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Page, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('pages')
                ->join('users', 'pages.added_by', '=', 'users.id')
                ->leftJoin('users as users_updated', 'pages.updated_by', '=', 'users_updated.id')
                ->select('pages.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
                ->whereNotNull('pages.deleted_at')
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
                ->addColumn('order', function ($row) {
                    return Str::of($row->order)->limit(100);
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
                ->rawColumns(['action', 'deleted_at', 'status', 'order', 'added_by', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trash Page';
        return view('backend.pages.trash', $data);
    }

    public function restoreService(Request $request)
    {
        $Page = Page::withTrashed()->find($request->id);
        if ($Page) {
            $Page->restore();
            $notification['type'] = "success";
            $notification['message'] = "Page Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Page, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }

    public function updateStatus(Request $request)
    {
        $update = Page::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Pages Activated Successfuly!' : $message = 'Pages Deactivated Successfuly!';

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
