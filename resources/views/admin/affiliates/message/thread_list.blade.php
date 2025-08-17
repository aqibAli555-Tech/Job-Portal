@foreach($threads as $thread)

    <div class="discussion" data-thread-id="{{ $thread->id }}" onclick="load_affiliate_messages('{{ $thread->id }}')">
        <div class="photo"
             style="background-image: url('{{ \App\Helpers\Helper::getImageOrThumbnailLink($thread->userDataExcludingAuthUser) }}');">
            @if(\App\Helpers\Helper::check_user_is_online($thread)=='online')
                <div class="online"></div>
            @endif
        </div>
        @php
            $style='';
            if($thread->isUnread()){
                $style='font-weight: bold;';
            }
        @endphp
        <div class="desc-contact" style="{{$style}}">
            <p class="name">{{ $thread->userDataExcludingAuthUser->name }}</p>
            <p class="message"> {{ \Illuminate\Support\Str::limit(@$thread->latest_message->body, 125) }}</p>
            <p class="message">{{ $thread->created_at_formatted }}</p>
        </div>
        <div class="list-box-action">
            <a href="javascript:void(0);"
               data-toggle="tooltip"
               data-placement="top"
               title="{{ t('Delete') }}"
               onclick="event.stopPropagation(); confirmAffiliateDeletion('{{ $thread->id }}')">
                <i class="fas fa-trash"></i>
            </a>
        </div>
    </div>
@endforeach


