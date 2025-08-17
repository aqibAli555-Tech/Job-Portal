
<div class="col-md-3 col-lg-2">
	<ul class="nav nav-pills inbox-nav">
		<li class="nav-item{{ (!request()->has('filter') || request()->get('filter')=='') ? ' active' : '' }}">
			<a class="nav-link" href="{{ url('affiliate/messages') }}">
				{{ t('inbox') }}
				<?php
				$badgeColor = (!request()->has('filter') || request()->get('filter')=='') ? 'badge-light' : 'badge-primary text-white';
				$visibility = (!isset($countThreadsWithNewMessage) || $countThreadsWithNewMessage <= 0) ? ' hide' : '';
				?>
                &nbsp;
				<span class="count-threads-with-new-messages count badge {{ $badgeColor }}{{ $visibility }}">
					{{ \App\Helpers\Number::short($countThreadsWithNewMessage) }}
				</span>
			</a>
		</li>
	</ul>
</div>