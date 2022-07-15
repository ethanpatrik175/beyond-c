<?php

namespace App\Http\Controllers;

use App\Models\SectionContent;
use App\Models\Section;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SectionContentController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("sectioncontents.index") . '" class="btn btn-sm btn-success">ALL SECTION CONTENT</a> &nbsp;';
        $this->buttons .= '<a href="' . route("sectioncontents.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="' . route('sectioncontent.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Section Content";
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('section_contents')
                ->join('users', 'section_contents.added_by', '=', 'users.id')
                ->join('sections', 'section_contents.section_id', '=', 'sections.id')
                ->leftJoin('users as users_updated', 'section_contents.updated_by', '=', 'users_updated.id')
                ->select('section_contents.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy', 'sections.name as section_name')
                ->whereNull('section_contents.deleted_at')
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('sectioncontents.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->created_at)->diffForHumans() . '</label>';
                })
                ->addColumn('section_name', function ($row) {
                    return Str::of($row->section_name)->limit(100);
                })
                ->addColumn('content', function ($row) {
                    return Str::of($row->content)->limit(100);
                })
                ->addColumn('added_by', function ($row) {
                    return '' . date('d-M-Y', strtotime($row->created_at)) . '<br/>' . "By:" . $row->first_name . ' ' . $row->last_name;
                })
                ->addColumn('updated_by', function ($row) {
                    if (isset($row->updatedBy)) {
                        return '' . date('d-M-Y', strtotime($row->updated_at)) . '<br/>' . "By:" . $row->updated_by_first_name . ' ' . $row->updated_by_last_name;
                    } else {
                        return  '-';
                    }
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="sectioncontent_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="sectioncontent_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->rawColumns(['action', 'created_at', 'is_active', 'content', 'added_by', 'updated_by', 'section_name'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Section Content';

        return view('backend.sectioncontent.all', $data);
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
        $data['parents'] = Section::get();
        $data['page_title'] = 'Add New Section Content';

        return view('backend.sectioncontent.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $SectionContent = new SectionContent();
        $SectionContent->added_by = Auth::user()->id;
        $SectionContent->name = $request->name;
        $SectionContent->section_id = $request->section_id;
        if (isset($request->text)) {
            $SectionContent->content = $request->text;
        }
        if (isset($request->link)) {
            $SectionContent->content = $request->link;
        }
        if (isset($request->meta_description)) {
            $SectionContent->content = $request->meta_description;
        }
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'max:1024'
            ]);
            $image = $request->file('image');
            if ($image->move('assets/frontend/sectioncontent/', $image->getClientOriginalName())) {
                $SectionContent->content = $image->getClientOriginalName();

                if ($SectionContent->save()) {
                    $data['type'] = "success";
                    $data['message'] = "Section Content Added Successfuly!.";
                    $data['icon'] = 'mdi-check-all';
                    return redirect()->route('sectioncontents.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Add Section Content, please try again.";
                    $data['icon'] = 'mdi-block-helper';
                    return redirect()->route('sectioncontents.create')->withInput()->with($data);
                }
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to upload image, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('sectioncontents.create')->withInput()->with($data);
            }
        }
        if ($SectionContent->save()) {
            $data['type'] = "success";
            $data['message'] = "Section Content Added Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('sectioncontents.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Section Content, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('sectioncontents.index')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SectionContent  $sectionContent
     * @return \Illuminate\Http\Response
     */
    public function show(SectionContent $sectionContent)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SectionContent  $sectionContent
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Section Content';
        $data['parents'] = Section::get();
        $data['page'] = SectionContent::findOrFail($id);
        $data['sections'] = Section::where('id', $data['page']->section_id)->get()->toArray();
        // dd($data['page']);
        return view('backend.sectioncontent.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SectionContent  $sectionContent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $SectionContent = SectionContent::findOrFail($id);
        //     $this->validate($request, [
        //         'name' => 'required',
        //         'section_id' => 'required',
        //         'content' => 'required',

        //  ]);
        $SectionContent->updated_by = Auth::user()->id;
        $SectionContent->name = $request->name;
        $SectionContent->section_id = $request->section_id;
        if (isset($request->text)) {
            $SectionContent->content = $request->text;
        }
        if (isset($request->link)) {
            $SectionContent->content = $request->link;
        }
        if (isset($request->meta_description)) {
            $SectionContent->content = $request->meta_description;
        }
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'max:1024'
            ]);
            $image = $request->file('image');
            if ($image->move('assets/frontend/sectioncontent/', $image->getClientOriginalName())) {
                $SectionContent->content = $image->getClientOriginalName();
                if ($SectionContent->save()) {
                    Cache::flush();
                    $data['type'] = "success";
                    $data['message'] = "Section Content Added Successfuly!.";
                    $data['icon'] = 'mdi-check-all';

                    return redirect()->route('sectioncontents.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Add Section Content, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('sectioncontents.create')->withInput()->with($data);
                }
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to upload image, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('sectioncontents.create')->withInput()->with($data);
            }
        }
        if ($SectionContent->save()) {
            $data['type'] = "success";
            $data['message'] = "Section Content Added Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('sectioncontents.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Add Section Content, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('sectioncontents.index')->withInput()->with($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SectionContent  $sectionContent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Section = SectionContent::find($id);
        if ($Section) {
            $Section->delete();
            $notification['type'] = "success";
            $notification['message'] = "Section Content Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Section Content, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('section_contents')
                ->join('users', 'section_contents.added_by', '=', 'users.id')
                ->leftJoin('users as users_updated', 'section_contents.updated_by', '=', 'users_updated.id')
                ->select('section_contents.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy')
                ->whereNotNull('section_contents.deleted_at')
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
                ->addColumn('content', function ($row) {
                    return Str::of($row->content)->limit(100);
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
                ->rawColumns(['action', 'deleted_at', 'status', 'content', 'added_by', 'updated_by'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trash Section Content';
        return view('backend.sectioncontent.trash', $data);
    }
    public function restoreService(Request $request)
    {
        $Section = SectionContent::withTrashed()->find($request->id);
        if ($Section) {
            $Section->restore();
            $notification['type'] = "success";
            $notification['message'] = "Section Content Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Section Content, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        $update = SectionContent::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Section Content Activated Successfuly!' : $message = 'Section Content Deactivated Successfuly!';

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
