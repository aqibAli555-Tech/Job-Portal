<?php
/**
 * JobClass - Job Board Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace Larapen\Impersonate\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Lab404\Impersonate\Services\ImpersonateManager;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Session;

class ImpersonateController extends \Lab404\Impersonate\Controllers\ImpersonateController
{
	/** @var ImpersonateManager */
	protected $manager;
	
	/**
	 * ImpersonateController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware('auth');
		$this->middleware('demo.restriction');
		
		$this->manager = app()->make(ImpersonateManager::class);
	}
	
	/**
	 * @param Request $request
	 * @param int $id
	 * @param null $guardName
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function take(Request $request, $id, $guardName = null)
	{

		$guardName = $guardName ?? $this->manager->getDefaultSessionGuard();
		
		// If the Domain Mapping plugin is installed,
		// Then, the impersonate feature need to be disabled
		if (config('plugins.domainmapping.installed')) {
			Alert::error(t('Cannot impersonate when the Domain Mapping plugin is installed'))->flash();
			
			return redirect()->back();
		}

		
		// Cannot impersonate yourself
		if ($id == $request->user()->getKey() && ($this->manager->getCurrentAuthGuardName() == $guardName)) {
			Alert::error('Cannot impersonate yourself')->flash();
			
			return redirect()->back();
		}

		// Cannot impersonate again if you're already impersonate a user
		if ($this->manager->isImpersonating()) {
			abort(403);
		}
		
		if (!$request->user()->canImpersonate()) {
			Alert::error('The current user can not impersonate')->flash();
			return redirect()->back();
		}

		$userToImpersonate = $this->manager->findUserById($id, $guardName);
		
		if ($userToImpersonate->canBeImpersonated()) {

			if ($this->manager->take($request->user(), $userToImpersonate, $guardName)) {

                //if child company login from parent company
                if(!empty($request->get('from_parent_company')) && $request->get('from_parent_company')==1){
                    $values = array(
                        'last_login_at' => date('Y-m-d h:i:s'),
                    );
                    User::where('id', $id)->update($values);
                }
				$takeRedirect = $this->manager->getTakeRedirectTo();
				if ($takeRedirect !== 'back') {
					return redirect()->to($takeRedirect);
				}
			}
		} else {
			Alert::error(t('The destination user can not be impersonated'))->flash();
		}
		
		return redirect()->back();
	}
	
	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function leave()
	{
		// if ($this->manager->isImpersonating()) {
		// 	abort(403);
		// }
		//dd(session::get('impersonate_back_url'));
		$this->manager->leave();
		if(!empty($_GET['user_id'])){
            if(!empty($_GET['from_parent_company'])){
                $from_parent_company='?from_parent_company=1';
            }else{
                $from_parent_company='';
            }
			return redirect(url('impersonate/take/'.$_GET['user_id'].$from_parent_company));
		}else{
			session()->forget(['impersonate']);
			
			return redirect()->to(session::get('impersonate_back_url'));
			session()->forget(['impersonate_back_url']);
		}

		//$leaveRedirect = session::get('impersonate_back_url');
		// return redirect()->to(session::get('impersonate_back_url'));
		// if ($leaveRedirect !== 'back') {
		// 	return redirect()->to($leaveRedirect);
		// }
		
		// return redirect()->back();
	}
}
