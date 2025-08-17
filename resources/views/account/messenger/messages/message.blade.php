
@if (auth()->id() == $message->user->id)
	<div class="chat-item object-me" id="message-chat-{{ $message->id }}">
		<div class="object-user-img">

			<a href="{{ url('companyprofile/'.$message->user->id) }}">
				 <div class="user-image-div-message" style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($message->user);}}')">   
                                                                    </div>
				<label style="">{{$message->user->name}}</label>
			</a>
		</div>
		<div class="chat-item-content">
			<div class="msg">
				{!! createAutoLink(nlToBr($message->body), ['class' => 'text-light']) !!}
				@if (!empty($message->filename))
					<?php $mt2Class = !empty(trim($message->body)) ? 'mt-2' : ''; ?>
					<div class="{{ $mt2Class }}">
						<i class="fas fa-paperclip" aria-hidden="true"></i>
						<a class=""
						   href="{{ fileUrl($message->filename) }}"
						   target="_blank"
						   data-toggle="tooltip"
						   data-placement="left"
						   title="{{ basename($message->filename) }}">
							{{ \Illuminate\Support\Str::limit(basename($message->filename), 20) }}
						</a>
					</div>
				@endif
			</div>
			<div>
				<a href="javascript:void(0);"
				data-toggle="tooltip"
				data-placement="top"
				title="{{ t('Delete') }}"
				onclick="event.stopPropagation(); confirmMessageChatDeletion('{{ $message->id }}')"
				style="color: red; font-size: 12px; margin-right: 30px;">
					<i class="fas fa-trash" style="margin-right: 5px;"></i> Delete
				</a>
				<a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{ t('Edit') }}"
					onclick="event.stopPropagation(); editChatMessage('{{ $message->id }}')"
					style="font-size: 12px; margin-right: 5px;">
					<i class="fas fa-edit"></i> Edit
				</a>
			</div> 
			<span class="time-and-date">
				{{ $message->created_at_formatted }}
				<?php $recipient = $message->recipients()->first(); ?>
				@if (!empty($recipient) && !$message->thread->isUnread($recipient->user_id))
					&nbsp;<i class="fas fa-check-double"></i>
				@endif
			</span>
		</div>
	</div>
@else
	<div class="chat-item object-user">
		<div class="object-user-img">
			@if($message->user->user_type_id==1)
			<a href="{{ url('companyprofile/'.$message->user->id) }}">
				
				<div class="user-image-div-message" style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($message->user); }}')">   
                                                                    </div>
				<label style="margin-left: 15px;">{{$message->user->name}}</label>
			</a>
			@else
				<a href="{{ url('profile/'.$message->user->id) }}">
						<div class="user-image-div-message" style="background-image:url('{{  \App\Helpers\Helper::getImageOrThumbnailLink($message->user);}}')">   
                                                                    </div>
					<label style="">{{$message->user->name}}</label>
				</a>
			@endif
		</div>
		<div class="chat-item-content">
			<div class="chat-item-content-inner">

				<div class="msg bg-white">
					{!! createAutoLink(nlToBr($message->body), ['class' => 'text-light']) !!}
					@if (!empty($message->filename))
						<?php $mt2Class = !empty(trim($message->body)) ? 'mt-2' : ''; ?>
						<div class="{{ $mt2Class }}">
							<i class="fas fa-paperclip" aria-hidden="true"></i>
							<a class="text-black"
							   href="{{ fileUrl($message->filename) }}"
							   target="_blank"
							   data-toggle="tooltip"
							   data-placement="left"
							   title="{{ basename($message->filename) }}">
								{{ \Illuminate\Support\Str::limit(basename($message->filename), 20) }}
							</a>
						</div>
					@endif
				</div>
				</div>
				<?php $userIsOnline = isUserOnline($message->user); ?>
				<span class="time-and-date ml-0">
					@if ($userIsOnline)
						<!--<i class="fa fa-circle color-success"></i>&nbsp;-->
					@endif
					{{ $message->created_at_formatted }}
				</span>
			</div>
		</div>
@endif

<div id="edit-chat-message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editMessageModalLabel">Edit Message</h4>
            </div>
            <div class="modal-body">
                <textarea id="edit-chat-message-body" class="form-control" rows="4"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="update-chat-message-btn" onclick="updateChatMessage()">Update</button>
            </div>
        </div>
    </div>
</div>