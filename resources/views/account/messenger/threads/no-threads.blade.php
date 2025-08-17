<div class="alert" role="alert">
	@if (request()->get('filter') == 'unread')
		{{ t('No new thread or with new messages') }}
	@elseif (request()->get('filter') == 'started')
		{{ t('No thread started by you') }}
	@elseif (request()->get('filter') == 'important')
		{{ t('No message marked as important') }}
   @elseif(auth()->user()->user_type_id==1)
		{{ t('Once you view a Contact Card of an employee (job seeker), you can message them here at anytime to chat with them') }}
		@else{{ t('Once an employer (company) views your CV, they can choose to also message you here') }}
	@endif
</div>