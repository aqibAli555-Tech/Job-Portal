<?php

namespace App\Http\Controllers\Auth\Traits;

use App\Helpers\UrlGen;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Session;
use Twilio\Rest\Client;

trait VerificationTrait
{
    use EmailVerificationTrait, PhoneVerificationTrait, RecognizedUserActionsTrait;

    public $entitiesRefs = [
        'user' => [
            'slug' => 'user',
            'namespace' => '\\App\Models\User',
            'name' => 'name',
            'scopes' => [
                VerifiedScope::class,
            ],
        ],
        'post' => [
            'slug' => 'post',
            'namespace' => '\\App\Models\Post',
            'name' => 'contact_name',
            'scopes' => [
                VerifiedScope::class,
                ReviewedScope::class,
            ],
        ],
    ];

    /**
     * URL: Verify User's Email Address or Phone Number
     *
     * @param $field
     * @param null $token
     * @return Factory|RedirectResponse|Redirector|View
     */
    public function verification(Request $request, $field, $token = null)
    {


        // Keep Success Message If exists
        if (session()->has('message')) {
            session()->keep(['message']);
        }

        // Get Entity
        $entityRef = $this->getEntityRef(request()->segment(2));
        if (!empty($request->get('user_id'))) {
            Session::put('userIdForVerify', $request->get('user_id'));
        }
        if (empty($entityRef)) {
            abort(404, t("Entity ID not found"));
        }


        // Get Field Label
        $fieldLabel = t('Email Address');
        if ($field == 'phone') {
            $fieldLabel = t('Phone Number');
        }

        // Show Token Form
        if (empty($token) && !request()->filled('_token')) {
            if (!empty($request->get('user_id'))) {
                Session::put('last_url', url()->full());
                $user_data = User::withoutGlobalScopes()->where('id', $request->get('user_id'))->first();
                if (empty($user_data)) {
                    $message = 'User not found!';
                    flash($message)->error();
                    return redirect('/');
                }

                return view('token');
            } else {
                $message = 'User not found';
                flash($message)->error();
                return redirect('/');
            }
        }

        // Token Form Submission
        if (request()->filled('_token')) {

            // Form validation
            $validator = Validator::make(request()->all(), ['code' => 'required']);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if (request()->filled('code')) {
                return redirect('verify/' . $entityRef['slug'] . '/' . $field . '/' . request()->get('code'));
            }
        }


        // Get Entity by Token
        $model = $entityRef['namespace'];

        if (session()->has('userIdForVerify')) {
            $entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('id', Session::get('userIdForVerify'))->first();

            $fail = 0;
            if (!empty($entity->sid)) {

                $sid = '';
                $tokenAuth = '';

                try {
                    $twilioVerify = new Client($sid, $tokenAuth);
                    $verification_check = $twilioVerify->verify->v2->services($entity->sid)
                        ->verificationChecks
                        ->create($token, // code
                            ["to" => $entity->whatsapp_number]
                        );

                    if (!empty($verification_check->status)) {
                        if ($verification_check->status == 'approved') {
                            session()->forget('userIdForVerify');
                        } else {

                            $fail = 1;
                        }
                    } else {

                        $fail = 1;
                    }
                } catch (Exception $e) {

                    $fail = 1;
                    // return $next($request);
                }


            }
            if (!empty($fail)) {
                $message = t("Your field verification has failed", ['field' => $fieldLabel]);
                flash($message)->error();
                if (!empty($fieldLabel == 'phone')) {
                    $url = session()->get('last_url');
                    session()->forget('last_url');
                    return redirect($url);
                } else {
                    return redirect('/');
                }
            }
        } else {
            $entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('email_token', $token)->first();
        }

        if (!empty($entity)) {

            if ($entity->{'verified_' . $field} != 1) {

                // Verified
                $entity->{'verified_' . $field} = 1;
                $entity->save();

                $message = t("Your field has been verified", ['name' => $entity->{$entityRef['name']}, 'field' => $fieldLabel]);
                // $message = 'Thank you for the signup on Hungry For Jobs.Your account is under verification.Admin will get back to you once verified.';
                flash($message)->success();
                session()->forget('last_url');
                // Remove Notification Trigger
                if (session()->has('emailOrPhoneChanged')) {
                    session()->forget('emailOrPhoneChanged');
                }
                if (session()->has('verificationEmailSent')) {
                    session()->forget('verificationEmailSent');
                }
                if (session()->has('verificationSmsSent')) {
                    session()->forget('verificationSmsSent');
                }
            } else {
                session()->forget('last_url');
                $message = t("Your field is already verified", ['field' => $fieldLabel]);
                flash($message)->error();
            }

            // Get Next URL
            // Get Default next URL
            $nextUrl = '/?from=verification';

            // Is User Entity
            if ($entityRef['slug'] == 'user') {

                // Match User's ads (posted as Guest)
                $this->findAndMatchPostsToUser($entity);

                // Get User creation next URL
                // Login the User
                if (Auth::loginUsingId($entity->id)) {

                    $nextUrl = '/';
                } else {

                    if (session()->has('userNextUrl')) {
                        $nextUrl = session('userNextUrl');
                    } else {
                        $nextUrl = '/';
                    }
                }
            }


            // Is Post Entity
            if ($entityRef['slug'] == 'post') {
                // Match User's Posts (posted as Guest) & User's data (if missed)
                $this->findAndMatchUserToPost($entity);

                // Get Post creation next URL
                if (session()->has('itemNextUrl')) {
                    $nextUrl = session('itemNextUrl');
                    if (Str::contains($nextUrl, 'create') && !session()->has('tmpPostId')) {
                        $nextUrl = UrlGen::postUri($entity);
                    }
                } else {
                    $nextUrl = UrlGen::postUri($entity);
                }
            }

            // Remove Next URL session
            if (session()->has('userNextUrl')) {
                session()->forget('userNextUrl');
            }
            if (session()->has('itemNextUrl')) {
                session()->forget('itemNextUrl');
            }
//            dd($nextUrl);

            // Redirection

            return redirect($nextUrl);
        } else {

            $message = t("Your field verification has failed", ['field' => $fieldLabel]);
            flash($message)->error();
            if (!empty($fieldLabel == 'phone')) {
                $url = session()->get('last_url');
                session()->forget('last_url');
                return redirect($url);
            } else {
                return redirect('/');
            }
        }
    }

    /**
     * @param null $entityRefId
     * @return null
     */
    public function getEntityRef($entityRefId = null)
    {
//        dd(Str::contains(Route::currentRouteAction(), 'generatePDF'));
        if (empty($entityRefId)) {

            if (
                Str::contains(Route::currentRouteAction(), 'Auth\RegisterController') ||
                Str::contains(Route::currentRouteAction(), 'Account\EditController') ||
                Str::contains(Route::currentRouteAction(), 'Admin\UserController') ||
                Str::contains(Route::currentRouteAction(), 'generatePDF')
            ) {

                $entityRefId = 'user';
            }

            if (
                Str::contains(Route::currentRouteAction(), 'Post\CreateOrEdit\MultiSteps\CreateController') ||
                Str::contains(Route::currentRouteAction(), 'Post\CreateOrEdit\MultiSteps\EditController') ||
                Str::contains(Route::currentRouteAction(), 'Post\CreateOrEdit\SingleStep\CreateController') ||
                Str::contains(Route::currentRouteAction(), 'Post\CreateOrEdit\SingleStep\EditController') ||
                Str::contains(Route::currentRouteAction(), 'Admin\PostController')
            ) {
                $entityRefId = 'post';
            }
        }
//        dd($entityRefId);
        // Check if Entity exists
        if (!isset($this->entitiesRefs[$entityRefId])) {
            return null;
        }

        // Get Entity
        $entityRef = $this->entitiesRefs[$entityRefId];

        return $entityRef;
    }
}
