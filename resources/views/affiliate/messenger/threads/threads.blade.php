<div class="list-group">

	@if (isset($threads) && $threads->count() > 0)
		@foreach($threads as $thread)
			@include('affiliate.messenger.threads.thread', ['thread' => $thread])
		@endforeach
	@else
		@include('affiliate.messenger.threads.no-threads')
	@endif

</div>