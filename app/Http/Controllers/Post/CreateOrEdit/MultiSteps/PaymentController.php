<?php

namespace App\Http\Controllers\Post\CreateOrEdit\MultiSteps;

use App\Helpers\UrlGen;
use App\Http\Controllers\Post\CreateOrEdit\Traits\PricingTrait;
use App\Http\Controllers\Post\CreateOrEdit\Traits\RetrievePaymentTrait;
use App\Http\Requests\PackageRequest;
use App\Models\Post;
use App\Models\Country;
use App\Models\Package;
use App\Models\User;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Scopes\StrictActiveScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use App\Http\Controllers\FrontController;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Payment as PaymentHelper;
use Psy\Util\Json;
use App\Http\Controllers\Post\CreateOrEdit\Traits\MakePaymentTrait;
use \App\Helpers\Myfatoorahv2;
use DB;
use Illuminate\Http\Request;

class PaymentController extends FrontController
{
	use PricingTrait, MakePaymentTrait, RetrievePaymentTrait;

	public $request;
	public $data;
	public $msg = [];
	public $uri = [];
	public $packages;
	public $paymentMethods;

	/**
	 * PackageController constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->request = $request;
			$this->commonQueries();

			return $next($request);
		});
	}

	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		// Messages
		if (request()->segment(2) == 'create') {
			$this->msg['post']['success'] = t("Your ad has been created");
		} else {
			$this->msg['post']['success'] = t("Your ad has been updated");
		}
		$this->msg['checkout']['success'] = t("We have received your payment");
		$this->msg['checkout']['cancel'] = t("payment_cancelled_text");
		$this->msg['checkout']['error'] = t("payment_error_text");

		// Set URLs
		if (request()->segment(2) == 'create') {
			$this->uri['previousUrl'] = 'posts/create/#entryToken/payment';
			$this->uri['nextUrl'] = 'posts/create/#entryToken/finish';
			$this->uri['paymentCancelUrl'] = url('posts/create/#entryToken/payment/cancel');
			$this->uri['paymentReturnUrl'] = url('posts/create/#entryToken/payment/success');
		} else {
			$this->uri['previousUrl'] = 'posts/#entryId/payment';
			$this->uri['nextUrl'] = str_replace(['{slug}', '{id}'], ['#entrySlug', '#entryId'], (config('routes.post') ?? '#entrySlug/#entryId'));
			$this->uri['paymentCancelUrl'] = url('posts/#entryId/payment/cancel');
			$this->uri['paymentReturnUrl'] = url('posts/#entryId/payment/success');
		}

		// Payment Helper init.
		PaymentHelper::$country = collect(config('country'));
		PaymentHelper::$lang = collect(config('lang'));
		PaymentHelper::$msg = $this->msg;
		PaymentHelper::$uri = $this->uri;

		// Selected Package
		$package = $this->getSelectedPackage();
		view()->share('selectedPackage', $package);

		// Get Packages
		$this->packages = Package::applyCurrency()->with('currency')->orderBy('lft')->get();
		view()->share('packages', $this->packages);
		view()->share('countPackages', $this->packages->count());

		// Keep the Post's creation message
		// session()->keep(['message']);
		if (request()->segment(2) == 'create') {
			if (session()->has('tmpPostId')) {
				session()->flash('message', t('Your ad has been created'));
			}
		}
	}

	/**
	 * Show the form the create a new ad post.
	 *
	 * @param $postIdOrToken
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function getForm($postIdOrToken)
	{

		// Check if the form type is 'Single Step Form', and make redirection to it (permanently).
		if (config('settings.single.publication_form_type') == '2') {
			return redirect(url('edit/' . $postIdOrToken), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
		}

		$data = [];

		// Get Post
		if (request()->segment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::currentCountry()->with([
				'latestPayment' => function ($builder) {
					$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
				},
			])->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
		} else {
			$post = Post::currentCountry()->with([
				'latestPayment' => function ($builder) {
					$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
				},
			])->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();
		}

		if (empty($post)) {
			abort(404);
		}

		view()->share('post', $post);

		// Share the Post's Latest Payment Info (If exists)
		$this->sharePostLatestPaymentInfo($post);

		// Meta Tags
		if (request()->segment(2) == 'create') {
			MetaTag::set('title', getMetaTag('title', 'create'));
			MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
			MetaTag::set('keywords', getMetaTag('keywords', 'create'));
		} else {
			MetaTag::set('title', t('Update My Ad'));
			MetaTag::set('description', t('Update M y Ad'));
		}

		return appView('post.createOrEdit.multiSteps.packages', $data);
	}

	/**
	 * Store a new ad post.
	 *
	 * @param $postIdOrToken
	 * @param PackageRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function postForm($postIdOrToken, PackageRequest $request)
	{

		// Get Post
		if (request()->segment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
		} else {
			$post = Post::with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();
		}

		if (empty($post)) {
			abort(404);
		}

		// MAKE A PAYMENT (IF NEEDED)

		// Check if the selected Package has been already paid for this Post
		$alreadyPaidPackage = false;
		if (!empty($post->latestPayment)) {
			if ($post->latestPayment->package_id == $request->get('package_id')) {
				$alreadyPaidPackage = true;
			}
		}

		// Check if Payment is required
		$package = Package::find($request->get('package_id'));

		if (!empty($package)) {
			if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {

				// Send the Payment
				return $this->sendPayment($request, $post);
			}
		}

		// IF NO PAYMENT IS MADE (CONTINUE)

		// Get the next URL
		if (request()->segment(2) == 'create') {
			$request->session()->flash('message', t('Your ad has been created'));
			$nextStepUrl = 'posts/create/' . $postIdOrToken . '/finish';
		} else {
			flash(t("Your ad has been updated"))->success();
			$nextStepUrl = UrlGen::postUri($post);
		}

		// Redirect
		return redirect($nextStepUrl);
	}

	public function myfatoora($request)
	{
		// dd($request);
		// Meta Tags
		MetaTag::set('title', 'Select Package');
		MetaTag::set('description', 'Select Package ' . config('settings.app.app_name'));
		$data['data'] = Package::orderBy('lft', 'asc')->get();
		
		$data['token'] = $request;
		// dd($data['post']);
		return view('post.createOrEdit.multiSteps.myfatoora')->with('data', $data);
	}

	public function myfatoorapayment($postIdOrToken, $id)
	{

		// Meta Tags
		MetaTag::set('title', 'Credit Card Credentials');
		MetaTag::set('description', 'Credit Card Credentials ' . config('settings.app.app_name'));
		$Package = Package::where('id', $id)->first();
		if (!empty($Package->price)) {
			$price = $Package->price;
		}else {
			$price = 0;
		}
		$post = Post::
				withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
		// dd($post);
		$cust_details = [];
		$cust_details['name'] = $post->contact_name;
		$cust_details['mobile'] = $post->phone;
		$cust_details['email'] = $post->email;
		$success_url = lurl('posts/create/success');
		$error_url =  lurl('posts/create/finish');
		$RedirectUrl =  Myfatoorahv2::getPaymentLink($cust_details,$price,$id,$success_url,$error_url ,3,'','');
		dd($RedirectUrl);
		if ($RedirectUrl) {

			return $this->redirect($RedirectUrl);
		} else {
			flash("Unable to proceed with payment")->error();
		}
	}

	public function paymentsuccess($request)
	{
		$paymentId = $request->get('paymentId');
		$myfat = new Myfatoorahv2();
        $pay_data = $myfat->paymentdetails($paymentId);
        if (!empty($pay_data)) {
        
			// $payment = new Payment();
            // $order_id = $pay_data['UserDefinedField'];
            // $payment->order_id = $order_id;
            // $payment->price = $pay_data['InvoiceValue'];
            // $payment->coustmer_name = $pay_data['CustomerName'];
            // $payment->payment_id  = $pay_data['InvoiceTransactions'][0]['PaymentId'];
            // $payment->created_on = $pay_data['CreatedDate'];
			$PaymentCreate = array(
				'user_id' => auth()->user()->id,
				'payment_method_id' => 3,
				'active' => 1,
				'package_id' => $pay_data['UserDefinedField'],
				'important' => '',
				'amount' => $pay_data['InvoiceValue'],
				'created_at'=>$pay_data['CreatedDate'],
			);
			Payment::create($PaymentCreate);
			flash(t("Your ad has been created"))->success();
			return redirect('/account');
           
	}
}
	public function upgrade($request)
	{
		// dd($request);
		// Meta Tags
		MetaTag::set('title', 'Select Package');
		MetaTag::set('description', 'Select Package ' . config('settings.app.app_name'));
		$data['data'] = Package::orderBy('lft', 'asc')->get();
		$data['token'] = $request;
		// dd($data['token']);
		return view('post.createOrEdit.multiSteps.upgrade')->with('data', $data);
	}

	public function credentials($postIdOrToken, $id)
	{
		// dd($postIdOrToken);
		// Meta Tags



		MetaTag::set('title', 'Credit Card Credentials');
		MetaTag::set('description', 'Credit Card Credentials ' . config('settings.app.app_name'));

		$data['package'] = Package::where('id', $id)->first();
		$data['post'] = Post::
				withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $postIdOrToken)
				->first();
		$data['country'] = Country::where('code', $data['post']['country_code'])
				->first();
		$data['payment'] = Payment_method::where('id',3)->first();
		$data['token'] = $postIdOrToken;

		$data['error'] = lurl('posts/create/'.$postIdOrToken.'/tap-payment-error');
		$data['redirect'] = lurl('posts/create/'.$postIdOrToken.'/tap-redirect/?package_id='.$id);
		$data['success'] =lurl('posts/create/'.$postIdOrToken.'/tap-payment-success');
		// dd($data);
		return view('post.createOrEdit.multiSteps.card_info')->with('data', $data);
	}

	public function paymentmethod($request)
	{
		dd($request);
		// Meta Tags
		MetaTag::set('title', 'Select Package');
		MetaTag::set('description', 'Select Method');
		$data['data'] = Package::orderBy('lft', 'asc')->get();
		$data['token'] = $request;
		// dd($data['token']);
		return view('post.createOrEdit.multiSteps.upgrade')->with('data', $data);
	}

//	public function tappaymenterror($postIdOrToken){
//		dd('error');
//	}
//	public function tappaymentredirect($postIdOrToken){
//		dd('redirect');
//	}
//	public function tappaymentsuccess($postIdOrToken){
//		dd('success');
//	}

	public function paymentFree(Request $request,$postIdOrToken)
	{

		$today = date('Y-m-d');
        $id=$request->input('id');
		if (auth()->user()->cDate == null) {
			$data = Package::where('id', $id)->first();
			$credits = $data->short_name + auth()->user()->remaining_credits;
			$credit = $data->short_name + auth()->user()->credits;
			$UserCreate = array(
				'remaining_credits' => $credits,
				'credits' => $credit,
				'cDate' => $today,
			);
			User::where('id', auth()->user()->id)
				->update($UserCreate);
			$PaymentCreate = array(
				'user_id' => auth()->user()->id,
				'payment_method_id' => 2,
				'active' => 1,
				'package_id' => $data->id,
				'important' => '',
			);
			Payment::create($PaymentCreate);
			flash(t("Your ad has been created"))->success();
			return redirect('/account');
		} else {
			$t = auth()->user()->cDate;
			$date = new \DateTime("$t");
			$date->modify('+1 month');
			$lastDate = $date->format('Y-m-d');
			if ($today > $lastDate) {
				$data = Package::where('id', $request->get('id'))->first();
				$credits = $data->short_name + auth()->user()->remaining_credits;
				$credit = $data->short_name + auth()->user()->credits;
				$UserCreate = array(
					'remaining_credits' => $credits,
					'credits' => $credit,
					'cDate' => $today,
				);
				User::where('id', auth()->user()->id)
					->update($UserCreate);
				$PaymentCreate = array(
					'user_id' => auth()->user()->id,
					'payment_method_id' => 2,
					'active' => 1,
					'package_id' => $data->id,
					'important' => '',
				);
				Payment::create($PaymentCreate);
				flash(t("Your ad has been created"))->success();
				// return redirect('posts/create/' . $postIdOrToken . '/finish');
			} else {
				flash("You are not eligible till $lastDate ")->success();
				return redirect()->back();
			}
		}
	}

    public function freePackeg(Request $request,$postIdOrToken){

        $PACKEG_ID=$request->get('id');
        if (request()->segment(2) == 'create') {
            if (!session()->has('tmpPostId')) {
                return redirect('posts/create');
            }
            $post = Post::with(['latestPayment'])
                ->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('id', session('tmpPostId'))
                ->where('tmp_token', $postIdOrToken)
                ->first();
        } else {
             $post = Post::with(['latestPayment'])
                ->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
                ->where('user_id', auth()->user()->id)
                ->where('id', $postIdOrToken)
                ->first();
        }
        if (empty($post)) {
            abort(404);
        }
        $alreadyPaidPackage = false;
        if (!empty($post->latestPayment)) {
            if ($post->latestPayment->package_id ==$PACKEG_ID) {
                $alreadyPaidPackage = true;
            }
        }

        // Check if Payment is required
        $package = Package::find($PACKEG_ID);
        // Get the next URL
        if (request()->segment(2) == 'create') {
            flash(("Your add has been created"))->success();
            $nextStepUrl = 'posts/create/' . $postIdOrToken . '/finish';
        } else {
            flash(("Your add has been updated"))->success();
            $nextStepUrl = UrlGen::postUri($post);
        }
        // Redirect
        return redirect($nextStepUrl);
    }

}
