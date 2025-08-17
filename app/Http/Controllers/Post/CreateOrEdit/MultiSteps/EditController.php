<?php


namespace App\Http\Controllers\Post\CreateOrEdit\MultiSteps;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Post\CreateOrEdit\MultiSteps\Traits\EditTrait;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Company;
use App\Models\Package;
use App\Models\Post;
use App\Models\PostType;
use App\Models\SalaryType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class EditController extends FrontController
{
    use EditTrait, VerificationTrait;

    public $data;
    public $msg = [];
    public $uri = [];

    /**
     * EditController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->commonQueries();
            return $next($request);
        });
    }

    /**
     * Common Queries
     */
    public function commonQueries()
    {
        // References
        $data = [];
        $data['categories'] = Category::where(function ($query) {
            $query->where('parent_id', 0)->orWhereNull('parent_id');
        })->with(['children'])->orderBy('lft')->get();
        view()->share('categories', $data['categories']);

        // Get Post Types
        $data['postTypes'] = PostType::get();
        view()->share('postTypes', $data['postTypes']);

        // Get Salary Types
        $data['salaryTypes'] = SalaryType::query()->get();
        view()->share('salaryTypes', $data['salaryTypes']);

        // Get the User's Company
        if (auth()->check()) {
            $data['companies'] = Company::where('c_id', auth()->user()->id)->whereNull('deleted_at')->get();
            view()->share('companies', $data['companies']);
        }

        // Count Packages
        $data['countPackages'] = Package::applyCurrency()->count();
        view()->share('countPackages', $data['countPackages']);

        // Count Payment Methods
        $data['countPaymentMethods'] = $this->countPaymentMethods;

        // Save common's data
        $this->data = $data;
    }

    /**
     * Show the form the create a new ad post.
     *
     * @param $postId
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function getForm($postId)
    {

        $post_data = Post::where('id', $postId)->first();
        if ($post_data->is_deleted == 1) {
            return redirect(url('/'));
        }
        // Check if the form type is 'Single Step Form', and make redirection to it (permanently).
        if (config('settings.single.publication_form_type') == '2') {
            return redirect(url('edit/' . $postId), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        return $this->getUpdateForm($postId);
    }

    /**
     * Store a new ad post.
     *
     * @param $postId
     * @param PostRequest $request
     * @return RedirectResponse|Redirector
     */
    public function postForm($postId, PostRequest $request)
    {
        if (auth()->user()->user_type_id == 2) {
            flash("Permission error..!")->error();
            return redirect('/');
        }
        return $this->postUpdateForm($postId, $request);
    }
}
