<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\post;
use App\Models\Product;
use App\Models\Comment;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\RelatedProduct;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }

    public function aboutUs()
    {
        $data['pageTitle'] = "About Us";
        $data['bannerTitle'] = "About Us";
        return view('frontend.about-us', $data);
    }

    public function blogs()
    {
        $data['pageTitle'] = "Latest Blogs";
        $data['bannerTitle'] = "Latest Blogs";
        $data['blogs'] = post::with(['user', 'category'])->whereIsActive(1)->whereNull('deleted_at')->paginate(12);
        return view('frontend.blogs', $data);
    }

    public function blogDetail($slug)
    {
        $data['post'] = post::with(['user', 'category'])->whereIsActive(1)->whereNull('deleted_at')->whereSlug($slug)->first();
        $data['comment'] = Comment::where('post_id', $data['post']->id)->where('status', "Approved")->get();
        // dd($data['comment']);
        $data['pageTitle'] = "Blog Detail";
        $data['bannerTitle'] = "Blog Detail";
        return view('frontend.blog-detail', $data);
    }

    public function viewEvents()
    {
        $data['pageTitle'] = "Latest Events";
        $data['bannerTitle'] = "Latest Events";
        $data['events'] = Event::where('is_active', 1)->whereNull('deleted_at')->with('addedBy')->orderBy('id', 'desc')->paginate(6);
        return view('frontend.events', $data);
    }

    public function eventDetail($slug)
    {
        $data['pageTitle'] = "Event Detail";
        $data['bannerTitle'] = "Event Detail";
        $data['event'] = Event::whereSlug($slug)->whereIsActive(1)->whereNull('deleted_at')->first();
        return view('frontend.event-detail', $data);
    }

    public function charityDonation()
    {
        $data['pageTitle'] = "Charity Donation";
        $data['bannerTitle'] = "Charity Donation";
        return view('frontend.charity-donation', $data);
    }
    
    public function processCharityDonation(Request $request)
    {
        $request->validate([
            "donation_amount" => "required",
            "recurrence" => "required",
            "processing_fees" => "required",
            "behalf_of" => "required",
            "message" => "nullable|max:255",
            "first_name" => "required|string|min:3|max:20",
            "last_name" => "required|string|min:3|max:20",
            "email" => "required|email"
        ]);

        $donation = new Donation();
        $donationNumber = 'DONS'.date('dmYhis', time()).''.($request->donation_amount == "custom") ? $request->custom_amount : $request->donation_amount;
        $donation->donation_number = $donationNumber;
        $donation->donation_amount =  ($request->donation_amount == "custom") ? $request->custom_amount : $request->donation_amount;
        $donation->recurrence = $request->recurrence ?? '';
        $donation->processing_fees = $request->processing_fees ?? '';
        $donation->behalf_of = $request->behalf_of ?? '';
        $donation->message = $request->message ?? '';
        $donation->first_name = $request->first_name ?? '';
        $donation->last_name = $request->last_name ?? '';
        $donation->email = $request->email ?? '';
        $donation->address = $request->address ?? '';
        $donation->postal_code = $request->postal_code ?? '';
        $donation->payment_method = $request->payment_method ?? '';
        $donation->check_details = $request->check_details;
        $donation->payment_details = ($request->payment_details) ? json_encode($request->payment_details) : '';
        
        if($donation->save())
        {
            $data['type'] = "success";
            $data['message'] = "Donation transaction has been completed successfully!";

            try {
                /*Send Notification to the user*/
                $userFirst = User::where('email', 'jostalin376@gmail.com')->where('role', 'admin')->first();
                $user = User::find($userFirst->id);

                $body = 'New Donation has been recieved with Donation Number#' . $donationNumber;
                $details = [
                    'subject' => 'New Donation has been recieved with Donation Number#' . $donationNumber,
                    'greeting' => 'Hi ' . $user->first_name .' '. $user->last_name . ',',
                    'donation' => $donation,
                    'body' => $body,
                    'action_title' => '',
                    'action_url' => '#',
                    'thanks' => 'Thank you for your donations!',
                ];
                $user->notify(new \App\Notifications\SendNotification($details));

                $data['thankyou'] = true;
                $data['type'] = "success";
                $data['message'] = "Donation transaction has been completed successfully!.";

                return redirect()->route('thank.you')->with($data);

            } catch (Exception $e) {
                Log::info($e->getMessage());
                
                $data['thankyou'] = true;
                $data['type'] = "info";
                $data['message'] = "Donation transaction has been completed successfully, Failed to Send emails.";

                return redirect()->route('thank.you')->with($data);
            }

            // return json_encode($data);

        }
        else
        {
            $data['type'] = "danger";
            $data['message'] = "Failed to make donation transaction.";

            return redirect()->route('thank.you')->with($data);
            // return json_encode($data);
        }

    }

    public function checkout()
    {
           $data['cart'] = \Cart::GetContent();
        $data['product_total'] = \Cart::GetContent()->count();
        $data['total'] = \Cart::getTotal();
        $data['pageTitle'] = "Checkout";
        $data['bannerTitle'] = "Checkout";
        return view('frontend.checkout', $data);
    }

    public function contact()
    {
        $data['pageTitle'] = "Contact Us";
        $data['bannerTitle'] = "Contact Us";
        return view('frontend.contact', $data);
    }

    public function productPromotion(Request $request)
    {
        $data['pageTitle'] = "Product Promotion";
        $data['bannerTitle'] = "Product Promotion";
    
        $data['product_category'] = ProductCategory::whereIsActive(1)->whereNull('deleted_at')->get();
        
        if($request->category){
            $data['products'] = Product::where('category_id' , $request->category)->whereIsActive(1)->whereNull('deleted_at')->paginate(12);    
        }
        else{
            $data['products'] = Product::whereIsActive(1)->whereNull('deleted_at')->paginate(12);
        }
        
        return view('frontend.product-promotion', $data);
    }

    public function productDetail($id)
    {
        $data['pageTitle'] = "Product Detail";
        $data['bannerTitle'] = "Product Detail";
        $data['singleproduct'] = Product::where('id',$id)->get();

        $product_ids = RelatedProduct::where('product_id' , $id)->get()->pluck('related_product_id');
       $data['related_product'] = Product::whereIn('id' , $product_ids)->get();
        return view('frontend.product-detail', $data);
    }

    public function travelPackages()
    {
        $data['pageTitle'] = "Travel Packages";
        $data['bannerTitle'] = "Travel Packages";
        return view('frontend.travel-packages', $data);
    }

    public function travelPackageDetail($slug)
    {
        $data['pageTitle'] = "Travel Package Detail";
        $data['bannerTitle'] = "Travel Package Detail";
        return view('frontend.travel-package-detail', $data);
    }
   public function addtocart()
    {
        $data['pageTitle'] = "Cart";
        $data['bannerTitle'] = "Cart";
        return view('frontend.add-to-cart', $data);
    }
   
    public function thankYou()
    {
        $data['pageTitle'] = "Thank You";
        $data['bannerTitle'] = "Thank You";

        if(!Session::has('type'))
        {
            return redirect()->route('front.home');
        }
        return view('frontend.thank-you', $data);
    }
}
