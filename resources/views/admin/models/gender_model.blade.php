<div id="post_title" class="modal fade parent" tabindex="-1" role="dialog"aria-labelledby="post_titleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4>{{ trans('admin.update_skill') }} :</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="{{ admin_url('post_title') }}" method="POST">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="id" name="id" value="">
                    <div class="form-group">
                        <label for="inputTitle">Title</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter title" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('admin.Close') }}</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>