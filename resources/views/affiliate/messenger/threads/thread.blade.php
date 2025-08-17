<?php

$ThreadParticipant = \App\Models\ThreadParticipant::where('thread_id', $thread->id)->where('user_id', '!=', auth()->user()->id)->first();
$thread_user_data = \App\Models\User::withoutGlobalScopes([\App\Models\Scopes\VerifiedScope::class, \App\Models\Scopes\ReviewedScope::class])->where('id', $ThreadParticipant->user_id)->first();

$class = '';

if (auth()->user()->user_type_id == 1) {
    $url = url('profile') . '/' . $thread_user_data->id;
} else {
    $url = url('companyprofile') . '/' . $thread_user_data->id;
}
?>

<div class="list-group-item{{ $thread->isUnread() ? '' : ' seen' }}">
    <div class="form-check">
        <div class="custom-control pl-0">
            <label class="control-label" for="entries"></label>
        </div>
    </div>
    <a href="{{$url}}" class="list-box-user "
       data-id="{{ $thread->id }}">
        @if(!empty($thread_user_data->thumbnail))
            <img src="{{  \App\Helpers\Helper::getImageOrThumbnailLink($thread_user_data) }}"
                 alt="{{ $thread_user_data->name }}">
        @endif
        @if(!empty($thread_user_data->name))
            <span class="name" style="overflow: unset !important; padding:0px;margin:5px">
			<?php $userIsOnline = isUserOnline($thread->creator()) ? 'online' : 'offline'; ?>
                {{ \Illuminate\Support\Str::limit($thread_user_data->name, 14) }}
		</span>
        @endif
    </a>
    <a href="{{ url('affiliate/messages/'.$thread->id) }}" class="list-box-content <?= $class ?>"
       data-id="{{ $thread->id }}">

        <span class="title">{{ $thread_user_data->name }}</span>
        <div class="message-text">
            {{ \Illuminate\Support\Str::limit($thread->latest_message->body, 125) }}
        </div>
        <div class="time text-muted">{{ $thread->created_at_formatted }}</div>
    </a>
    <div class="list-box-action">
        <a href="{{ url('affiliate/messages/'.$thread->id.'/actions?type=delete') }}"
           data-toggle="tooltip"
           data-placement="top"
           title="{{ t('Delete') }}"
        >
            <i class="fas fa-trash"></i>
        </a>
    </div>
</div>


