<?php

namespace App\Http\Controllers\Auth\Traits;


use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Prologue\Alerts\Facades\Alert;
use Twilio\Rest\Client;

trait PhoneVerificationTrait
{
    /**
     * Show the ReSend Verification SMS Link
     *
     * @param $entity
     * @param $entityRefId
     * @return bool
     */
    public function showReSendVerificationSmsLink($entity, $entityRefId)
    {
        // Get Entity
        $entityRef = $this->getEntityRef($entityRefId);
        if (empty($entity) || empty($entityRef)) {
            return false;
        }

        // Show ReSend Verification SMS Link
        if (session()->has('verificationSmsSent')) {
            $message = t("Resend the verification message to verify your phone number");
            $message .= ' <a href="' . url('verify/' . $entityRef['slug'] . '/' . $entity->id . '/resend/sms') . '" class="btn btn-warning">' . t("Re-send") . '</a>';
            flash($message)->warning();
        }

        return true;
    }

    /**
     * URL: Re-Send the verification SMS
     *
     * @param $entityId
     * @return RedirectResponse
     */
    public function reSendVerificationSms(Request $request, $entityId)
    {

        // Non-admin data resources
        $entityRefId = request()->segment(2);

        // Admin data resources
        if (isFromAdminPanel()) {
            $entityRefId = Request::segment(3);
        }

        // Keep Success Message If exists
        if (session()->has('message')) {
            session()->keep(['message']);
        }

        // Get Entity
        $entityRef = $this->getEntityRef($entityRefId);
        if (empty($entityRef)) {

            $message = t("Entity ID not found");

            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }

            return back();
        }

        // Get Entity by Id
        $model = $entityRef['namespace'];
        $entity = $model::withoutGlobalScopes($entityRef['scopes'])->where('id', $entityId)->first();
        if (empty($entity)) {
            $message = t("Entity ID not found");

            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }

            return back();
        }


        // Check if the Phone is already verified
        if ($entity->verified_phone == 1 || isDemo()) {

            if (isDemo()) {
                $message = t('demo_mode_message');
                if (isFromAdminPanel()) {
                    Alert::info($message)->flash();
                } else {
                    flash($message)->info();
                }
            } else {
                $message = t("Your field is already verified", ['field' => t('Phone Number')]);
                if (isFromAdminPanel()) {
                    Alert::error($message)->flash();
                } else {
                    flash($message)->error();
                }
            }
            // Remove Notification Trigger
            $this->clearSmsSession();

            return back();
        }

        // Re-Send the confirmation
        if ($this->sendVerificationSms($entity, false)) {
            if (isFromAdminPanel()) {
                $message = t("The activation code has been sent to the user to verify his phone number");
                Alert::success($message)->flash();
            } else {
//                $message = t("The activation code has been sent to you to verify your phone number");
//                flash($message)->success();
            }
            //Remove Notification Trigger
            $this->clearSmsSession();
        }
        session()->forget('flash_notification');
        if (!empty($request->get('form_login')) && $request->get('form_login') == 1) {
            $values = [
                'sms_count' => $entity->sms_count + 1
            ];
            User::where('id', $entity->id)->update($values);
            $message = t("The activation code has been sent to you to verify your phone number");
            flash($message)->success();
            return redirect('verify/user/phone?user_id=' . $entity->id);
        }
        return back();
    }

    /**
     * Remove Notification Trigger (by clearing the sessions)
     */
    private function clearSmsSession()
    {
        if (session()->has('verificationSmsSent')) {
            session()->forget('verificationSmsSent');
        }
    }

    /**
     * Send verification SMS
     *
     * @param $entity
     * @param bool $displayFlashMessage
     * @return bool
     */
    public function sendVerificationSms($entity, $displayFlashMessage = true)
    {

        // Get Entity
        $entityRef = $this->getEntityRef();

        if (empty($entity) || empty($entityRef)) {
            $message = t("Entity ID not found");

            if (isFromAdminPanel()) {
                Alert::error($message)->flash();
            } else {
                flash($message)->error();
            }

            return false;
        }


        // Send Confirmation Email
        try {

            $sid = '';
            $token = '';

            $twilioCreate = new Client($sid, $token);


            $service = $twilioCreate->verify->v2->services->create("Hungry For Jobs");

            $twilioSend = new Client($sid, $token);
            $verification = $twilioSend->verify->v2->services($service->sid)
                ->verifications
                ->create($entity->whatsapp_number, // to
                    "whatsapp", // channel
                    [
                        "TemplateSid" => "",
                        "locale" => 'en',
                    ]
                );
            $user = User::withoutGlobalScopes()->where('phone', $entity->phone)->first();
            $user->sid = $verification->serviceSid;
            $user->save();


//            if (request()->filled('locale')) {
//                $locale = (array_key_exists(request()->get('locale'), getSupportedLanguages()))
//                    ? request()->get('locale')
//                    : null;
//
//                if (!empty($locale)) {
//                    $entity->notify((new PhoneVerification($entity, $entityRef))->locale($locale));
//                } else {
//                    $entity->notify(new PhoneVerification($entity, $entityRef));
//                }
//            } else {
//                $entity->notify(new PhoneVerification($entity, $entityRef));
//            }

//			if ($displayFlashMessage) {
//				$message = t("An activation code has been sent to you to verify your phone number");
//				flash($message)->success();
//			}
//
//			session()->put('verificationSmsSent', true);

            return true;
        } catch (Exception $e) {

            if (isFromAdminPanel()) {
                Alert::error($e->getMessage())->flash();
            } else {
                flash($e->getMessage())->error();
            }
        }

        return false;
    }
}
