<?php

namespace App\Http\Controllers;

use App\Models\Dating;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use stdClass;

class DatingController extends Controller
{
    public function findYourDate()
    {
        $dating = Dating::where('user_id', Auth::user()->id)->first();

        dd($dating);
    }

    public function createAccount()
    {
        $dating = Dating::where('user_id', Auth::user()->id)->first();
        if($dating)
        {
            return redirect()->route('find.your.date');
        }
        return view('frontend.dating.create-account');
    }

    public function stepOneProcess(Request $request)
    {
        $request->validate([
            'gender' => 'required',
            'age_start' => 'required|integer',
            'age_end' => 'required|integer',
            'zipcode' => 'required'
        ], [
            'age_start.required' => 'Select valid start age',
            'age_end.required' => 'Select valid end age'
        ]);

        $stepOne = new stdClass;
        $stepOne->gender = $request->gender;
        $stepOne->age_start = $request->age_start;
        $stepOne->age_end = $request->age_end;
        $stepOne->zipcode = $request->zipcode;

        $request->session()->put('datingStepOne', $stepOne);
        $request->session()->put('stepStatus', 'frontend.dating.step-two');

        return view('frontend.dating.step-two');
    }

    public function stepTwoProcess(Request $request)
    {
        $request->validate([
            'date_of_birth' => 'required'
        ], [
            'date_of_birth.required' => 'Please select valid date of birth'
        ]);

        $dob = \Carbon\Carbon::parse($request->date_of_birth)->age;
        if($dob < 18)
        {
            $data['type'] = "error";
            $data['message'] = "You must be at least 18 to use our site.";
            return response($data);
        }

        $stepTwo = new stdClass;
        $stepTwo->date_of_birth = $request->date_of_birth;
        $stepTwo->body_type = $request->body_type;
        $stepTwo->relationship_status = $request->relationship_status;

        $request->session()->put('datingStepTwo', $stepTwo);
        $request->session()->put('stepStatus', 'frontend.dating.step-three');

        return view('frontend.dating.step-three');
    }

    public function stepThreeProcess(Request $request)
    {
        $stepthree = new stdClass;
        $stepthree->have_kids = $request->have_kids;
        $stepthree->want_kids = $request->want_kids;
        $stepthree->education = $request->education;
        $stepthree->do_smoke = $request->do_smoke;
        $stepthree->do_drink = $request->do_drink;

        $request->session()->put('datingStepThree', $stepthree);
        $request->session()->put('stepStatus', 'frontend.dating.step-four');

        return view('frontend.dating.step-four');
    }

    public function stepFourProcess(Request $request)
    {
        $request->validate([
            'passion' => 'nullable|max:250',
            'about' => 'nullable|max:250'
        ]);

        $stepFour = new stdClass;
        $stepFour->religion = $request->religion;
        $stepFour->passion = $request->passion;
        $stepFour->about = $request->about;

        $request->session()->put('datingStepFour', $stepFour);
        $request->session()->put('stepStatus', 'frontend.dating.final-step');

        return view('frontend.dating.final-step');
    }

    public function finalStepProcess(Request $request)
    {
        $step1 = $request->session()->get('datingStepOne');
        $step2 = $request->session()->get('datingStepTwo');
        $step3 = $request->session()->get('datingStepThree');
        $step4 = $request->session()->get('datingStepFour');
        $avatar = $request->session()->get('avatar');

        $dating = new Dating();
        $dating->user_id = Auth::user()->id;
        $dating->age_start = $step1->age_start;
        $dating->age_end = $step1->age_end;
        $dating->gender = $step1->gender;
        $dating->zipcode = $step1->zipcode;
        $dating->interested_gender = 'female';
        $dating->date_of_birth = $step2->date_of_birth;
        $dating->body_type = $step2->body_type;
        $dating->relationship_status = $step2->relationship_status;
        $dating->have_kids = $step3->have_kids;
        $dating->want_kids = $step3->want_kids;
        $dating->education = $step3->education;
        $dating->do_smoke = $step3->do_smoke;
        $dating->do_drink = $step3->do_drink;
        $dating->religion = $step4->religion;
        $dating->passion = $step4->passion;
        $dating->about = $step4->about;
        $dating->avatar = $avatar;

        if($dating->save()){
            Session::forget('datingStepOne');
            Session::forget('datingStepTwo');
            Session::forget('datingStepThree');
            Session::forget('datingStepFour');
            Session::forget('stepStatus');
            Session::forget('avatar');
            Session::save();

            $data['type'] = 'done';
            $data['message'] = 'Your dating account has been created successfully, you will redirected to the subscription page in few seconds.';
            $data['html'] = view('frontend.dating.message')->render();
            return $data;
        }
        else{
            $data['type'] = 'error';
            $data['message'] = 'Something went wrong, please contact to our support team or try again.';
            return response($data);
        }
    }

    public function restoreStep(Request $request)
    {
        if(Auth::check())
        {
            $view = $request->session()->get('stepStatus');
            return view($view);
        }
        else{
            $data['type'] = "error";
            $data['message'] = "";
            return response($data);
        }
    }

    public function stepBack(Request $request)
    {
        if(Auth::check())
        {   
            if($request->backStep == "step-four")
            {
                return view('frontend.dating.step-four');
            }
            elseif($request->backStep == "step-three")
            {
                return view('frontend.dating.step-three');
            }
            elseif($request->backStep == "step-two")
            {
                return view('frontend.dating.step-two');
            }
            else if($request->backStep == "step-one")
            {
                return view('frontend.dating.step-one');
            }
            
        }
        else{
            $data['type'] = "error";
            $data['message'] = "";
            return response($data);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'filepond' => 'required|image|max:1024'
        ]);

        if($request->hasFile('filepond'))
        {
            $image = $request->file('filepond');
            $imageName = $image->getClientOriginalName();
            if($image->move('assets/frontend/images/users/'.Auth::user()->id,$imageName))
            {
                $request->session()->put('avatar', $imageName);
                $data['type'] = 'success';
                $data['message'] = 'Profile Image uploaded successfully';

                return response()->json($data);
            }
        }

        return response()->json(['error'=>'Failed to upload image, please try again.']);
    }

    public function removeImage(Request $request)
    {
        $filename =  $request->session()->get('avatar');
        $path = asset('assets/frontend/images/users/'.Auth::user()->id.'/'.$filename);
        dd($filename, $path, file_exists($path));
        if (file_exists($path)) {
            unlink($path);
            return response()->json(['message'=>'Image has been removed successfully!']);
        }
        else{
            return response()->json(['message'=>'Error Occured!']);
        }
    }

    // Subscription Process
    public function subscribe()
    {
        $data['pageTitle'] = "Subscribe Package";
        $data['bannerTitle'] = "Subscribe Package";
        $data['packages'] = Subscription::get();
        return view('frontend.dating.subscribe', $data);
    }
}
