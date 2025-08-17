<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{t('Contact Card Problems')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= url('/contact_card_problem') ?>" method="post">
                    @csrf
                    <div class="mb-3">

                        <label for="name" class="form-label">{{t('Enter name of the employee (job seeker)')}}</label>
                        <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp">
                        <input type="hidden" class="form-control" id="company" name="company" value="<?= auth()->user()->name ?>" aria-describedby="emailHelp">
                    </div>
                    <button type="submit" class="btn btn-primary">{{t('Submit')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="track_applicant_modal" data-backdrop="static" role="dialog" data-dismiss="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{trans('admin.track_applicant')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="userTable">
                        <thead>
                            <tr role="row">
                                <th>#</th>
                                <th>{{ trans('admin.post_title') }} </th>
                                <th>{{ trans('admin.company_name') }}</th>
                                <th>{{ trans('admin.status') }}</th>
                                <th>{{ trans('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


