<br>
@if(auth()->user()->user_type_id==1)
    <div id="accordion" class="panel-group">
        <!-- USER -->
        <div class="card card-default">
            <div class="card-header">
                <h4 class="card-title" data-toggle="collapse" data-parent="#accordion">
                    {{ t('Email Settings') }}
                </h4>
            </div>
            <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'userPanel') ? 'show' : '' }}"
                 id="userPanel">
                <div class="card-body">
                    <form name="details" method="POST"
                          action="{{ url('/account/update_email_settings') }}"
                          enctype="multipart/form-data"
                          class="dashboard-form">
                        {!! csrf_field() !!}
                        <input name="user_id" type="hidden" value="{{ $user->id }}">
                        <input name="_method" type="hidden" value="POST">
                        <input name="panel" type="hidden" value="userPanel">

                        <div class="form-group row required">
                            <label class="col-md-3 col-form-label">{{ t('Add more emails to receive email notifications')
                            }}</label>
                            <div class="input-group col-md-9">
                                <input name="optional_emails" type="text"
                                       class="form-control"
                                       placeholder="abc@gmail.com,xyz@hmail.com"
                                       id="tag-input1"
                                       value="{{$user->optional_emails}}">
                                <div class="col-md-12">
                                        <sup>{{t('Press Enter after every email address to add more')}}</sup>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row required">
                            <label class="col-md-3 col-form-label">{{ t('Select which emails you would like to send to the above email addresses')
                            }}</label>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="checkbox" class="checkbox_email" id="select_all">
                                        <label for="select_all" class="checkbox_label">Select All</label>
                                    </div>
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                        <?php
                                        $selectd_email = [];
                                        foreach ($optional_selected_emails as $optional_selected_email) {
                                            $selectd_email[] = $optional_selected_email->email_id;
                                        }
                                        ?>
                                    @foreach($system_emails as $system_email)
                                        <div class="col-md-6">
                                            <input type="checkbox" class="checkbox_email" name="selected_emails[]"
                                                   id="{{$system_email->id}}" value="{{$system_email->id}}"
                                                    <?php if (in_array($system_email->id, $selectd_email)) {
                                                echo 'checked';
                                            } ?>>
                                            <label for="{{$system_email->id}}"
                                                   class="checkbox_label">{{$system_email->name}}</label>
                                        </div>
                                    @endforeach
                                    <div class="col-md-9">
                                        <button type="submit" onchange="this.form.submit()"
                                                class="btn btn-primary">{{ t('Update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var selectAllCheckbox = document.getElementById('select_all');
        var checkboxes = document.querySelectorAll('.checkbox_email');
        var allChecked = true;
        checkboxes.forEach(function (checkbox) {
            if (checkbox !== selectAllCheckbox && !checkbox.checked) {
                allChecked = false;
            }
        });
        if (allChecked) {
            selectAllCheckbox.checked = true;
        }

        selectAllCheckbox.addEventListener('change', function () {
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        checkboxes.forEach(function (checkbox) {
            if (checkbox !== selectAllCheckbox) {
                checkbox.addEventListener('change', function () {
                    if (!checkbox.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        var allChecked = true;
                        checkboxes.forEach(function (cb) {
                            if (cb !== selectAllCheckbox && !cb.checked) {
                                allChecked = false;
                            }
                        });
                        selectAllCheckbox.checked = allChecked;
                    }
                });
            }
        });
    });


    (function(){

        "use strict"

        // Plugin Constructor
        var TagsInput = function(opts){
            this.options = Object.assign(TagsInput.defaults , opts);
            this.init();
        }

        // Initialize the plugin
        TagsInput.prototype.init = function(opts){
            this.options = opts ? Object.assign(this.options, opts) : this.options;

            if(this.initialized)
                this.destroy();

            if(!(this.orignal_input = document.getElementById(this.options.selector)) ){
                console.error("tags-input couldn't find an element with the specified ID");
                return this;
            }

            this.arr = [];
            this.wrapper = document.createElement('div');
            this.input = document.createElement('input');
            init(this);
            initEvents(this);

            if (this.orignal_input.value) {
                this.addData(this.orignal_input.value.split(','));
            }

            this.initialized =  true;
            return this;
        }

        // Add Tags
        TagsInput.prototype.addTag = function(string){

            if(this.anyErrors(string))
                return ;

            this.arr.push(string);
            var tagInput = this;

            var tag = document.createElement('span');
            tag.className = this.options.tagClass;
            tag.innerText = string;

            var closeIcon = document.createElement('a');
            closeIcon.innerHTML = '&times;';

            // delete the tag when icon is clicked
            closeIcon.addEventListener('click' , function(e){
                e.preventDefault();
                var tag = this.parentNode;

                for(var i =0 ;i < tagInput.wrapper.childNodes.length ; i++){
                    if(tagInput.wrapper.childNodes[i] == tag)
                        tagInput.deleteTag(tag , i);
                }
            })


            tag.appendChild(closeIcon);
            this.wrapper.insertBefore(tag , this.input);
            this.orignal_input.value = this.arr.join(',');

            return this;
        }

        // Delete Tags
        TagsInput.prototype.deleteTag = function(tag , i){
            tag.remove();
            this.arr.splice( i , 1);
            this.orignal_input.value =  this.arr.join(',');
            return this;
        }

        // Make sure input string have no error with the plugin
        TagsInput.prototype.anyErrors = function(string){
            if( this.options.max != null && this.arr.length >= this.options.max ){
                console.log('max tags limit reached');
                return true;
            }

            if(!this.options.duplicate && this.arr.indexOf(string) != -1 ){
                console.log('duplicate found " '+string+' " ')
                return true;
            }

            return false;
        }

        // Add tags programmatically
        TagsInput.prototype.addData = function(array){
            var plugin = this;

            array.forEach(function(string){
                plugin.addTag(string);
            })
            return this;
        }

        // Get the Input String
        TagsInput.prototype.getInputString = function(){
            return this.arr.join(',');
        }


        // destroy the plugin
        TagsInput.prototype.destroy = function(){
            this.orignal_input.removeAttribute('hidden');

            delete this.orignal_input;
            var self = this;

            Object.keys(this).forEach(function(key){
                if(self[key] instanceof HTMLElement)
                    self[key].remove();

                if(key != 'options')
                    delete self[key];
            });

            this.initialized = false;
        }

        // Private function to initialize the tag input plugin
        function init(tags){
            tags.wrapper.append(tags.input);
            tags.wrapper.classList.add(tags.options.wrapperClass);
            tags.orignal_input.setAttribute('hidden' , 'true');
            tags.orignal_input.parentNode.insertBefore(tags.wrapper , tags.orignal_input);
        }

        // initialize the Events
        function initEvents(tags){
            tags.wrapper.addEventListener('click' ,function(){
                tags.input.focus();
            });


            tags.input.addEventListener('keydown' , function(e){
                var str = tags.input.value.trim();

                if( !!(~[9 , 13 , 188].indexOf( e.keyCode ))  )
                {
                    e.preventDefault();
                    tags.input.value = "";
                    if(str != "")
                        tags.addTag(str);
                }

            });
        }


        // Set All the Default Values
        TagsInput.defaults = {
            selector : '',
            wrapperClass : 'tags-input-wrapper',
            tagClass : 'tag',
            max : null,
            duplicate: false
        }

        window.TagsInput = TagsInput;

    })();

    var tagInput1 = new TagsInput({
        selector: 'tag-input1',
        duplicate : false,
        max : 10
    });
</script>
@endif