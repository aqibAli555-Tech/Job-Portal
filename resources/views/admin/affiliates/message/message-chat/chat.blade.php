<div class="messages-chat">
    @if(!empty($messages))
        @foreach($messages as $message)

            @if (auth()->id() == $message->user->id)
                <div class="message" id="message-{{ $message->id }}">
                    <div class="photo"
                         style="background-image: url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($message->user)}}')">

                    </div>
                        <div class="send">
                            <div class="text">
 
                            @if (!empty($message->body))
                                    <p style="white-space: pre-wrap;">{!! createAutoLink($message->body) !!}</p>
                            @endif
                            @if (!empty($message->filename))
                                    <?php $mt2Class = !empty($message->body) ? 'mt-1' : ''; ?>
                                    <i class="fas fa-paperclip" aria-hidden="true"></i>
                                    <a class=""
                                       href="{{ fileUrl($message->filename) }}"
                                       target="_blank"
                                       data-toggle="tooltip"
                                       data-placement="left"
                                       title="{{ basename($message->filename) }}">
                                        {{ \Illuminate\Support\Str::limit(basename($message->filename), 20) }}
                                    </a>

                                    @endif
                                </div>
                                <div style="display: flex; align-items: center; gap: 25px;">
                                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{ t('Edit') }}"
                                        onclick="event.stopPropagation(); editMessage('{{ $message->id }}')"
                                        style="font-size: 12px; margin-right: 20px; margin-left: 40px;">
                                        <i class="fas fa-edit" style="margin-right: 5px;"></i> Edit
                                    </a>
                                    <a href="javascript:void(0);"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="{{ t('Delete') }}"
                                    onclick="event.stopPropagation(); confirmAffiliateMessageDeletion('{{ $message->id }}')"
                                    style="color: red; font-size: 12px;">
                                        <i class="fas fa-trash" style="margin-right: 5px;"></i> Delete
                                    </a>
                                    <a href="javascript:void(0);"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="{{ t('Copy Message') }}"
                                        onclick="event.stopPropagation(); copyMessageText('{{ $message->id }}')"
                                        style="color: gray; font-size: 12px;">
                                            <i class="fas fa-copy" style="margin-right: 5px;"></i> Copy
                                    </a>
                                </div>

                        <div class="timer"> {{ $message->created_at_formatted }}</div>

                    </div>

                </div>
                    @else
                        <div class="message">
                            <div class="response">
                                <div class="text">
                                    @if(!empty($message->body))
                                        <p style="white-space: pre-wrap;">{!! createAutoLink($message->body) !!}</p>
                                    @endif
                                    @if (!empty($message->filename))
                                            <?php $mt2Class = !empty($message->body) ? 'mt-1' : ''; ?>

                                        <i class="fas fa-paperclip" aria-hidden="true"></i>
                                        <a class=""
                                           href="{{ fileUrl($message->filename) }}"
                                           target="_blank"
                                           data-toggle="tooltip"
                                           data-placement="left"
                                           title="{{ basename($message->filename) }}">
                                            {{ \Illuminate\Support\Str::limit(basename($message->filename), 20) }}
                                        </a>

                                    @endif
                                </div>
                                <div class="timer"> {{ $message->created_at_formatted }}</div>

                            </div>
                            <div class="photo"
                                 style="background-image: url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($message->user)}}')">
                            </div>
                        </div>
                    @endif
                    @endforeach
                    @endif
                </div>
                <br>
<div id="edit-message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editMessageModalLabel">Edit Message</h4>
            </div>
            <div class="modal-body">
                <textarea id="edit-message-body" class="form-control" rows="4"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-message-btn" onclick="updateMessage()">Update</button>
            </div>
        </div>
    </div>
</div>


