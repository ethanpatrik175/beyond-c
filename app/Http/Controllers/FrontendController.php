<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Donation;
use App\Models\Event;
use App\Models\post;
use App\Models\Product;
use App\Models\TravelPackage;
use App\Models\Comment;
use App\Models\TravelTags;
use App\Models\Tag;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\RelatedProduct;
use App\Models\Section;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
    public function welcome()
    {       
        $data['content'] = Cache::rememberForever('content', function () {
            return Section::with('sectioncontent')->where('name','section_1')->where('page_id',1)->first();
        });
        $data['section_2'] = Cache::rememberForever('section_2', function () {
            return Section::with('sectioncontent')->where('name','section_2')->where('page_id',1)->first();
        });
        $data['section_3'] = Cache::rememberForever('section_3', function () {
            return Section::with('sectioncontent')->where('name','section_3')->where('page_id',1)->first();
        });
        $data['section_4'] = Cache::rememberForever('section_4', function () {
            return Section::with('sectioncontent')->where('name','section_4')->where('page_id',1)->first();
        });
        // $data['testimonal_1'] = "test";
        $data['testimonal_1'] = Cache::rememberForever('testimonal_1', function () {
            return Section::with('sectioncontent')->where('page_id',3)->where('name','section_1')->first();
        });
        $data['heading_2'] = Cache::rememberForever('heading_2', function () {
            return Section::with('sectioncontent')->where('page_id',1)->where('name','section_5')->first();
        });

        $data['footerContent'] = Cache::rememberForever('footerContent', function () {
            $footer['logo'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_1')->first();
            $footer['desc'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_2')->first();
            $footer['number'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_3')->first();
            $footer['email'] =  Section::with('sectioncontent')->where('page_id',17)->where('name','section_4')->first();
            $footer['Adress'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_5')->first();
            $footer['link_1'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_6')->first();
            $footer['link_2'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_7')->first();
            $footer['link_3'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_8')->first();
            $footer['link_4'] = Section::with('sectioncontent')->where('page_id',17)->where('name','section_9')->first();
            return $footer;
        });
       
        $data['bannerTitle'] = Banner::where('page',"home")->first();
        return view('welcome',$data);
    }


    public function aboutUs()
    {
        $data['testimonal_1'] = Cache::rememberForever('testimonal_1', function () {
            return Section::with('sectioncontent')->where('page_id',3)->where('name','section_1')->first();
        });
        $data['aboutus'] = Cache::rememberForever('aboutus', function () {
            $aboutus['heading_1'] = Section::with('sectioncontent')->where('page_id',12)->where('name','section_1')->first();
            $aboutus['heading_2'] = Section::with('sectioncontent')->where('page_id',12)->where('name','section_2')->first();
            $aboutus['para_1'] = Section::with('sectioncontent')->where('page_id',12)->where('name','section_3')->first();
            $aboutus['para_2'] =  Section::with('sectioncontent')->where('page_id',12)->where('name','section_4')->first();
            $aboutus['heading_3'] =  Section::with('sectioncontent')->where('page_id',12)->where('name','section_5')->first();
            $aboutus['para_3'] =  Section::with('sectioncontent')->where('page_id',12)->where('name','section_6')->first();
            $aboutus['image'] =  Section::with('sectioncontent')->where('page_id',12)->where('name','section_7')->first();
            return $aboutus;
        });
        $data['pageTitle'] = "About Us";
        $data['bannerTitle'] = Banner::where('page',"about-us")->first();
        return view('frontend.about-us', $data);
    }

    public function blogs()
    {
        $data['testimonal_1'] = Cache::rememberForever('testimonal_1', function () {
            return Section::with('sectioncontent')->where('page_id',3)->where('name','section_1')->first();
        });
        $data['pageTitle'] = "Latest Blogs";
        $data['bannerTitle'] = Banner::where('page',"blogs")->first();
        $data['blogs'] = post::with(['user', 'category'])->whereIsActive(1)->whereNull('deleted_at')->paginate(12);
        return view('frontend.blogs', $data);
    }

    public function blogDetail($slug)
    {
        $data['post'] = post::with(['user', 'category'])->whereIsActive(1)->whereNull('deleted_at')->whereSlug($slug)->first();
        $data['comment'] = Comment::where('post_id', $data['post']->id)->where('status', "Approved")->get();
        // dd($data['comment']);
        $data['pageTitle'] = "Blog Detail";
        $data['bannerTitle'] = Banner::where('page',"blog-detail")->first();
        return view('frontend.blog-detail', $data);
    }

    public function viewEvents()
    {
        $data['pageTitle'] = "Latest Events";
        $data['bannerTitle'] = Banner::where('page',"events")->first();
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
        $data['bannerTitle'] = Banner::where('page',"charity-donation")->first();
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
        $donationNumber = 'DONS' . date('dmYhis', time()) . '' . ($request->donation_amount == "custom") ? $request->custom_amount : $request->donation_amount;
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

        if ($donation->save()) {
            $data['type'] = "success";
            $data['message'] = "Donation transaction has been completed successfully!";

            try {
                /*Send Notification to the user*/
                $userFirst = User::where('email', 'jostalin376@gmail.com')->where('role', 'admin')->first();
                $user = User::find($userFirst->id);

                $body = 'New Donation has been recieved with Donation Number#' . $donationNumber;
                $details = [
                    'subject' => 'New Donation has been recieved with Donation Number#' . $donationNumber,
                    'greeting' => 'Hi ' . $user->first_name . ' ' . $user->last_name . ',',
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

        } else {
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
        $data['bannerTitle'] = Banner::where('page',"checkout")->first();
        return view('frontend.checkout', $data);
    }

    public function contact()
    {
       
        $data['section_1'] = Cache::rememberForever('section_1', function () {
            return Section::with('sectioncontent')->where('name','section_1')->where('page_id',4)->first();
        });
        $data['pageTitle'] = "Contact Us";
        $data['bannerTitle'] = Banner::where('page',"contact-us")->first();
        return view('frontend.contact', $data);
    }

    public function productPromotion(Request $request)
    {
        $data['pageTitle'] = "Product Promotion";
        $data['bannerTitle'] = Banner::where('page',"product-promotion")->first();

        $data['product_category'] = ProductCategory::whereIsActive(1)->whereNull('deleted_at')->get();

        if ($request->category) {
            $data['products'] = Product::where('category_id', $request->category)->whereIsActive(1)->whereNull('deleted_at')->paginate(12);
        } else {
            $data['products'] = Product::whereIsActive(1)->whereNull('deleted_at')->paginate(12);
        }

        return view('frontend.product-promotion', $data);
    }

    public function productDetail($id)
    {
        $data['pageTitle'] = "Product Detail";
        $data['bannerTitle'] = Banner::where('page',"product-detail")->first();
        $data['singleproduct'] = Product::where('id', $id)->get();

        $product_ids = RelatedProduct::where('product_id', $id)->get()->pluck('related_product_id');
        $data['related_product'] = Product::whereIn('id', $product_ids)->get();
        return view('frontend.product-detail', $data);
    }

    public function travelPackages()
    {
        $data['pageTitle'] = "Travel Packages";
        $data['bannerTitle'] = Banner::where('page',"travel-packages")->first();
        $data['travel_package'] = TravelPackage::with('travel_type')->latest()->paginate(4);
        // dd($data['travel_package']);
        return view('frontend.travel-packages', $data);
    }

    public function travelPackageDetail($slug)
    {
        // dd($slug);
        $data['pageTitle'] = "Travel Package Detail";
        $data['bannerTitle'] = Banner::where('page',"travel-package-detail")->first();
        $data['travel_package'] = TravelPackage::with('travel_type', 'tags')->where('slug', $slug)->first();
        $data['traveltag'] = TravelTags::where('travel_package_id', $data['travel_package']->id)->pluck('tag_id');
        $data['tags'] = Tag::whereIn('id', $data['traveltag'])->get();
        return view('frontend.travel-package-detail', $data);
    }
    public function addtocart()
    {
        dd("test");
        $data['pageTitle'] = "Cart";
        $data['bannerTitle'] = "cart";
       
        return view('frontend.add-to-cart', $data);
    }

    public function thankYou()
    {
        $data['pageTitle'] = "Thank You";
        $data['bannerTitle'] = "Thank You";

        if (!Session::has('type')) {
            return redirect()->route('front.home');
        }
        return view('frontend.thank-you', $data);
    }
    // public function testimomal()
    // {
    //   
    //    dd($data['content']);
    //     return view('frontend.thank-you', $data);
    // }
}
