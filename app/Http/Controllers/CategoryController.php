<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("categories.index") . '" class="btn btn-sm btn-success">ALL Category</a> &nbsp;';
        $this->buttons .= '<a href="' . route("categories.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="' . route('category.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Tags";
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('categories as b')
                ->join('users', 'b.added_by', '=', 'users.id')
                ->leftJoin('categories as a', 'b.parent_id', '=', 'a.id')
                ->leftJoin('users as users_updated', 'b.updated_by', '=', 'users_updated.id')
                ->select('b.*', 'users.first_name', 'users.last_name', 'users_updated.first_name as updated_by_first_name', 'users_updated.last_name as updated_by_last_name', 'users.role as addedBy', 'users_updated.role as updatedBy', 'a.title as parent_title')
                ->whereNull('b.deleted_at')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('categories.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger remove" data-id="' . $row->id . '"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    // $btn .= ' <a href="'.route('service.images',['services', $row->id]).'" target="_blank" class="text-warning" data-id="'.$row->id.'"><i title="More Images" class="fas fa-images font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)) . '<br /> <label class="text-primary">' . Carbon::parse($row->created_at)->diffForHumans() . '</label>';
                })
                ->addColumn('is_active', function ($row) {
                    if ($row->is_active == '0') {
                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="categories_status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == '1') {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="categories_status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                ->addColumn('icon', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if ($row->icon) {
                        $image = '<img src=' . asset('assets/frontend/images/category/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(100);
                })
                ->addColumn('parent_title', function ($row) {
                    return Str::of($row->parent_title)->limit(100);
                })

                ->addColumn('added_by', function ($row) {
                    return date('d-M-Y', strtotime($row->created_at)).'<br/>'.$row->first_name . ' ' . $row->last_name ;
                })
                ->addColumn('updated_by', function ($row) {
                    if (isset($row->updatedBy)) {
                        return $row->updated_by_first_name . ' ' . $row->updated_by_last_name . ' <br />(' . Str::of($row->updatedBy)->upper() . ')';
                    } else {
                        return  '-';
                    }
                })
                ->rawColumns(['action', 'is_active', 'icon', 'title', 'added_by', 'updated_by', 'parent_title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Categories';


        return view('backend.category.all', $data);
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
        $data['page_title'] = 'Add New Category';
        $data['parents'] = Category::get();

        return view('backend.category.add', $data);
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
        $Category = new Category();
        $Category->added_by = Auth::user()->id;
        $Category->slug = Str::slug($request->Slug);
        $Category->parent_id = $request->parent_id;
        $Category->title = $request->Title;
        $Category->metadata = $request->metadata;
        if ($request->file('image') == null) {
            if ($Category->save()) {
                $data['type'] = "success";
                $data['message'] = "Category Added Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('categories.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Category, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('categories.index')->withInput()->with($data);
            }
        } else {
            $image = $request->file('image');
            if ($image->move('assets/frontend/images/category/', $image->getClientOriginalName())) {

                $Category->icon = $image->getClientOriginalName();
                if ($Category->save()) {
                    $data['type'] = "success";
                    $data['message'] = "Category Added Successfuly!.";
                    $data['icon'] = 'mdi-check-all';

                    return redirect()->route('categories.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Add Category, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('categories.create')->withInput()->with($data);
                }
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to upload image, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('categories.create')->withInput()->with($data);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        abort(404);
    }

    public function edit($id)
    {
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update CATEGORY';
        $data['Category'] = Category::findOrFail($id);
        // $data['categories'] = Category::where('id', $data['Category']->parent_id)->get()->toArray();
        $data['parents'] = Category::where('id', '!=', $id)->get();
        
        return view('backend.category.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Title' => 'required|max:100',
            'slug' => 'required|max:100',
        ]);

        $Category = Category::findOrFail($id);

        $Category->updated_by = Auth::user()->id;
        $Category->slug = Str::slug($request->slug);
        $Category->parent_id = $request->parent_id;
        $Category->title = $request->Title;
        // $Category->metadata = $request->metadata;
        if ($request->file('image') == null) {
            if ($Category->save()) {
                $data['type'] = "success";
                $data['message'] = "Category Updated Successfuly!.";
                $data['icon'] = 'mdi-check-all';

                return redirect()->route('categories.index')->with($data);
            } else {
                $data['type'] = "danger";
                $data['message'] = "Failed to Add Category, please try again.";
                $data['icon'] = 'mdi-block-helper';

                return redirect()->route('categories.index')->withInput()->with($data);
            }
        } else {
            if ($request->file('image')) {
                $request->validate([
                    'image' => 'required|image|max:1024'
                ], [
                    'image.required' => 'Please upload valid image'
                ]);

                $image = $request->file('image');
                if ($image->move('assets/frontend/images/category/', $image->getClientOriginalName())) {

                    $Category->icon = $image->getClientOriginalName();
                    if ($Category->save()) {
                        $data['type'] = "success";
                        $data['message'] = "Category Updated Successfuly!.";
                        $data['icon'] = 'mdi-check-all';

                        return redirect()->route('categories.index')->with($data);
                    } else {
                        $data['type'] = "danger";
                        $data['message'] = "Failed to Update Category, please try again.";
                        $data['icon'] = 'mdi-block-helper';

                        return redirect()->route('categories.edit', $request->id)->withInput()->with($data);
                    }
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to upload image, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('categories.edit', $request->id)->withInput()->with($data);
                }
            } else {
                if ($Category->save()) {
                    $data['type'] = "success";
                    $data['message'] = "Category Updated Successfuly!.";
                    $data['icon'] = 'mdi-check-all';

                    return redirect()->route('categories.index')->with($data);
                } else {
                    $data['type'] = "danger";
                    $data['message'] = "Failed to Update Category, please try again.";
                    $data['icon'] = 'mdi-block-helper';

                    return redirect()->route('categories.edit', $request->id)->withInput()->with($data);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Category = Category::find($id);
        $Category->deleted_by = Auth::user()->id;
        if ($Category) {
            $Category->delete();
            $notification['type'] = "success";
            $notification['message'] = "Category Moved to Trash Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Remove Category, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        $update = Category::where('id', $request->id)->update(['is_active' => $request->is_active]);

        if ($update) {
            $request->is_active == '1' ? $alertType = 'success' : $alertType = 'info';
            $request->is_active == '1' ? $message = 'Category Activated Successfuly!' : $message = 'Category Deactivated Successfuly!';

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

        // dd($request->all(), $data);
        if ($request->ajax()) {
            $data = Category::onlyTrashed()->get();
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
                        $image = '<img src=' . asset('assets/frontend/images/category/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })
                ->addColumn('title', function ($row) {
                    return Str::of($row->title)->limit(100);
                })

                ->rawColumns(['action', 'deleted_at', 'icon', 'title'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Category Trash';
        return view('backend.category.trash', $data);
    }

    public function restoreCategory(Request $request)
    {
        $Category = Category::withTrashed()->find($request->id);
        if ($Category) {
            $Category->restore();
            $notification['type'] = "success";
            $notification['message'] = "Category Restored Successfuly!.";
            $notification['icon'] = 'mdi-check-all';

            echo json_encode($notification);
        } else {
            $notification['type'] = "danger";
            $notification['message'] = "Failed to Restore Category, please try again.";
            $notification['icon'] = 'mdi-block-helper';

            echo json_encode($notification);
        }
    }
}
