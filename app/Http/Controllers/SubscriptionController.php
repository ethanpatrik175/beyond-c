<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SubscriptionController extends Controller
{
    public $buttons;
    public $section;

    public function __construct()
    {
        $this->buttons = '';
        $this->buttons .= '<a href="' . route("subscriptions.index") . '" class="btn btn-sm btn-success">ALL SUBSCRIPTIONS</a> &nbsp;';
        $this->buttons .= '<a href="' . route("subscriptions.create") . '" class="btn btn-sm btn-primary">ADD NEW</a> &nbsp;';
        $this->buttons .= '<a href="' . route('subscriptions.trash') . '" class="btn btn-sm btn-danger">TRASH</a>';
        $this->section = "Subscriptions";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subscription::with(['addedBy', 'updatedBy'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('subscriptions.edit', $row->id) . '" target="_blank"><i title="Edit" class="fas fa-edit font-size-18"></i></a>';
                    $btn .= ' <a href="javascript:void(0);" class="text-danger" onClick="deleteRecord(' . $row->id . ')"><i title="Delete" class="fas fa-trash-alt font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-M-Y h:i A', strtotime($row->created_at)) . '<br /> <label class="text-primary">By: ' . $row->addedBy->first_name . ' ' . $row->addedBy->last_name . '</label>';
                })
                ->addColumn('updated_at', function ($row) {
                    return date('d-M-Y h:i A', strtotime($row->updated_at)) . '<br /> <label class="text-primary">By: ' . @$row->updatedBy->first_name . ' ' . @$row->updatedBy->last_name . '</label>';
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active == 0) {

                        $btn0 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="1"/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn0;
                    } elseif ($row->is_active == 1) {

                        $btn1 = '<div class="square-switch"><input type="checkbox" id="switch' . $row->id . '" class="status" switch="bool" data-id="' . $row->id . '" value="0" checked/><label for="switch' . $row->id . '" data-on-label="Yes" data-off-label="No"></label></div>';
                        return $btn1;
                    }
                })
                /*->addColumn('image', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if (isset($row->icon)) {
                        $image = '<img src=' . asset('assets/frontend/images/subscriptions/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })*/
                ->addColumn('description', function ($row) {
                    return json_decode($row->description);
                })
                ->addColumn('charges', function ($row) {
                    $charges = '';
                    $charges .= 'Price/Month' . number_format($row->price_per_month, 2);
                    $charges .= '<br />Discounted Price' . number_format($row->discount_per_year, 2);
                    $charges .= '<hr />Price/Year' . number_format($row->price_per_year, 2);
                    $charges .= '<br />Discouned Price' . number_format($row->discount_per_year, 2);
                    return $charges;
                })
                ->rawColumns(['action', 'created_at', 'updated_at', 'status', 'description', 'charges'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'All Subscriptions';

        return view('backend.subscriptions.index', $data);
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
        $data['page_title'] = 'Create New Subscription';

        return view('backend.subscriptions.create', $data);
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
            "name" => "required|max:20",
            "price_per_month" => "required",
            "price_per_year" => "required",
            'charge_type' => 'required|in:monthly,yearly'
        ]);

        $obj = new Subscription();
        $obj->name = $request->name;
        $obj->slug = Str::slug($request->name);
        $obj->added_by = Auth::user()->id;
        $obj->price_per_month = $request->price_per_month;
        $obj->discount_per_month = $request->discount_per_month;
        $obj->price_per_year = $request->price_per_year;
        $obj->discount_per_year = $request->discount_per_year;
        $obj->charge_type = $request->charge_type;
        $obj->description = json_encode($request->description);

        /*if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:1024'
            ]);

            $image = $request->file('image');
            if ($image->move('assets/frontend/images/subscriptions/', $image->getClientOriginalName())) {
                $obj->icon = $image->getClientOriginalName();
            }
        }*/

        if ($obj->save()) {
            $data['type'] = "success";
            $data['message'] = "Subscription Created Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('subscriptions.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Create Subscription, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('subscriptions.create')->withInput()->with($data);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(Subscription $subscription)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        $data['subs'] = Subscription::findOrFail($subscription->id);
        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Edit & Update Subscription';

        return view('backend.subscriptions.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $obj = Subscription::findOrFail($subscription->id);

        $request->validate([
            "name" => "required|max:20",
            "price_per_month" => "required",
            "price_per_year" => "required",
        ]);

        $obj->name = $request->name;
        $obj->slug = Str::slug($request->name);
        $obj->updated_by = Auth::user()->id;
        $obj->price_per_month = $request->price_per_month;
        $obj->discount_per_month = $request->discount_per_month;
        $obj->price_per_year = $request->price_per_year;
        $obj->discount_per_year = $request->discount_per_year;
        $obj->description = json_encode($request->description);

        /*if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:1024'
            ]);

            $image = $request->file('image');
            if ($image->move('assets/frontend/images/subscriptions/', $image->getClientOriginalName())) {
                $obj->icon = $image->getClientOriginalName();
            }
        }*/

        if ($obj->save()) {
            $data['type'] = "success";
            $data['message'] = "Subscription Updated Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return redirect()->route('subscriptions.index')->with($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Update Subscription, please try again.";
            $data['icon'] = 'mdi-block-helper';

            return redirect()->route('subscriptions.edit', $subscription->id)->withInput()->with($data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $destroy = Subscription::findOrFail($subscription->id);
        $model = "Subscription";
        if ($destroy->delete()) {
            $destroy->deleted_by = Auth::user()->id;
            $destroy->save();

            $data['type'] = "success";
            $data['message'] = $model . " Deleted Successfuly!.";
            $data['icon'] = 'mdi-check-all';

            return json_encode($data);
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Delete " . $model . ", please try again.";
            $data['icon'] = 'mdi-block-helper';

            return json_encode($data);
        }
    }

    public function updateStatus(Request $request)
    {
        $update = Subscription::where('id', $request->id)->update(['is_active' => $request->status]);
        $modelName = "Subscription";
        if ($update) {
            $request->status == 1 ? $alertType = 'success' : $alertType = 'info';
            $request->status == 1 ? $message = $modelName . ' Activated Successfuly!' : $message = $modelName . ' Deactivated Successfuly!';

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
            $data = Subscription::onlyTrashed()->with('deletedBy')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0);" data-toggle="tooltip" onClick="restoreRecord(' . $row->id . ')" data-original-title="Restore"><i title="Restore" class="fas fa-trash-restore font-size-18"></i></a>';
                    return $btn;
                })
                ->addColumn('deleted_at', function ($row) {
                    return date('d-M-Y h:i A', strtotime($row->deleted_at)) . '<br /> <label class="text-primary">By: ' . $row->deletedBy->first_name . ' ' . $row->deletedBy->last_name . '</label>';
                })
                /*->addColumn('image', function ($row) {
                    $imageName = Str::of($row->icon)->replace(' ', '%20');
                    if (isset($row->icon)) {
                        $image = '<img src=' . asset('assets/frontend/images/subscriptions/' . $imageName) . ' class="avatar-sm" />';
                    } else {
                        $image = '<img src=' . asset('assets/backend/images/no-image.jpg') . ' class="avatar-sm" />';
                    }
                    return $image;
                })*/
                ->addColumn('charges', function ($row) {
                    $charges = '';
                    $charges .= 'Price/Month' . number_format($row->price_per_month, 2);
                    $charges .= '<br />Discounted Price' . number_format($row->discount_per_year, 2);
                    $charges .= '<hr />Price/Year' . number_format($row->price_per_year, 2);
                    $charges .= '<br />Discouned Price' . number_format($row->discount_per_year, 2);
                    return $charges;
                })
                ->rawColumns(['action', 'deleted_at', 'charges'])
                ->make(true);
        }

        $data['shortcut_buttons'] = $this->buttons;
        $data['section'] = $this->section;
        $data['page_title'] = 'Trashed Subscriptions';

        return view('backend.subscriptions.trash', $data);
    }

    public function restore(Request $request)
    {
        $restore = Subscription::withTrashed()->where('id', $request->id)->restore();
        $model = "Subscription";
        if ($restore) {
            $data['type'] = "success";
            $data['message'] = $model . " Restored Successfuly!.";
            $data['icon'] = 'mdi-check-all';
        } else {
            $data['type'] = "danger";
            $data['message'] = "Failed to Restore " . $model . ", please try again.";
            $data['icon'] = 'mdi-block-helper';
        }
        
        return json_encode($data);
    }
}
