<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class BannerController extends Controller
{

    public $buttons;
    public $section;
    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="'.route("banners.index").'" class="btn btn-sm btn-success">ALL BANNERS</a> &nbsp;';
        $this->buttons .= '<a href="'.route("banners.create").'" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="'.route('banners.trash').'" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Banners";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('banners')->whereNull('deleted_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = "";
                    $btn .= '<a href="'.route('banners.edit', $row->id).'" target="_blank" ><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->created_at)->diffForHumans().'</label>';
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="banner_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="banner_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('image', function ($row) {
                    $imageName = Str::of($row->image)->replace(' ', '%20');
                    if($row->image){
                        $image = '<img src=' . asset('assets/frontend/images/banners/' . $imageName) . ' class="avatar-sm" />';
                    }else{
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('page', function ($row) {
                    return Str::of(Str::replace('-', ' ', $row->page))->upper();
                })
                ->addColumn('description', function ($row) {
                    return Str::of($row->description)->limit(200);
                })
                ->addColumn('headings', function ($row) {
                    $getHeadings = '';
                    $headings = json_decode($row->headings);
                    if(count((array)$headings)){
                        foreach($headings as $index => $value)
                        {
                            if(isset($value)){
                                $getHeadings .= 'Heading '.$index.': '.$value.'<br />';
                            }
                        }
                    }

                    return $getHeadings;
                })
                ->rawColumns(['action', 'created_at', 'is_active', 'image', 'page', 'headings'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Banners';
        return view('backend.banners.all', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['pages'] = Page::where('is_active' , 1)->whereNull('deleted_at')->get();
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Add New Banner';

        return view('backend.banners.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'heading.oneBrown' => 'required|max:45',
        //     'heading.oneWhite' => 'required|max:45',
        //     'heading.two' => 'max:45',
        //     'heading.three' => 'max:45',
        //     'image'       => 'required|image|max:1024',
        //     'page'        => 'required',
        // ]);

        $banner = new Banner();
        $banner->headings = json_encode($request->heading);
        $banner->description = $request->description;
        $banner->page = $request->page;
        $banner->buttons = json_encode($request->buttons);

        $image = $request->file('image');
        if ($image->move('assets/frontend/images/banners/', $image->getClientOriginalName())) {

            $banner->image = $image->getClientOriginalName();
            if($banner->save())
            {
                $data['type'] = "success";
                $data['message'] = "Banner Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('banners.index')->with($data);
            }
            else
            {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Banner, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('banners.create')->withInput()->with($data);
            }

        }
        else{
            $data['type'] = "danger";
            $data['message'] = "Failed to upload image, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('banners.index')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['pages'] = Page::where('is_active',1)->whereNull('deleted_at')->get();
        $data['banner'] = Banner::findOrFail($id);
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Banner';

        return view('backend.banners.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all(), $id);
        // $request->validate([
        //     'heading.oneBrown' => 'required|max:45',
        //     'heading.oneWhite' => 'required|max:45',
        //     'heading.two' => 'max:45',
        //     'heading.three' => 'max:45',
        //     'page'        => 'required',
        // ]);

        $banner = Banner::findOrFail($id);
        $banner->headings = json_encode($request->heading);
        $banner->description = $request->description;
        $banner->page = $request->page;
        $banner->buttons = json_encode($request->buttons);

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            if ($image->move('assets/frontend/images/banners/', $image->getClientOriginalName())) {

                $banner->image = $image->getClientOriginalName();
                if($banner->save())
                {
                    $data['type'] = "success";
                    $data['message'] = "Banner Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';

                    return redirect()->route('banners.index')->with($data);
                }
                else
                {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Banner, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('banners.index')->withInput()->with($data);
                }

            }
            else{
                $data['type'] = "danger";
                $data['message'] = "Failed to upload image, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('banners.edit', $id)->withInput()->with($data);
            }

        }
        else
        {
            if($banner->save())
            {
                $data['type'] = "success";
                $data['message'] = "Banner Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('banners.index')->with($data);
            }
            else
            {
                $data['type'] = "danger";
                $data['message'] = "Failed to Update Banner, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('banners.index')->withInput()->with($data);
            }
        }
    }

    /**
     * Update status of banners here
     */
    public function updateStatus(Request $request)
    {
        $update = Banner::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Banner Activated Successfuly!' : $message = 'Banner Deactivated Successfuly!';

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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::find($id);
        if ($banner->status == 'inactive') {
            $banner->delete();
            $notification['type'] = "success";
            $notification['message'] = "Banner Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Banner, may be banner status is active. Please try again.";
            $notification['icon'] = 'mdi-block-helper';
        }

        return json_encode($notification);
    }

    /**
     * Banners Trash here
     *
     */
    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('banners')->whereNotNull('deleted_at')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="javascript:void(0);" class="text-primary restore" data-id="'.$row->id.'"><i title="Delete" class="fas fa-trash-restore-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y', strtotime($row->deleted_at)).'<br /> <label class="text-primary">'.Carbon::parse($row->deleted_at)->diffForHumans().'</label>';
                })
                ->addColumn('image', function ($row) {
                    $imageName = Str::of($row->image)->replace(' ', '%20');
                    if($row->image){
                        $image = '<img src=' . asset('assets/frontend/images/banners/' . $imageName) . ' class="avatar-sm" />';
                    }else{
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('page', function ($row) {
                    return Str::of(Str::replace('-', ' ', $row->page))->upper();
                })
                ->addColumn('description', function ($row) {
                    return Str::of($row->description)->limit(200);
                })
                ->addColumn('headings', function ($row) {
                    $getHeadings = '';
                    $headings = json_decode($row->headings);
                    if(count((array)$headings)){
                        foreach($headings as $index => $value)
                        {
                            if(isset($value)){
                                $getHeadings .= 'Heading '.$index.': '.$value.'<br />';
                            }
                        }
                    }

                    return $getHeadings;
                })
                ->rawColumns(['action', 'deleted_at', 'status', 'image', 'page', 'headings'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trash Banners';
        return view('backend.banners.trash', $data);
    }

    public function restoreBanner(Request $request)
    {
        $banner = Banner::withTrashed()->find($request->id);
        if ($banner) {
            $banner->restore();
            $notification['type'] = "success";
            $notification['message'] = "Banner Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Banner, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
}
