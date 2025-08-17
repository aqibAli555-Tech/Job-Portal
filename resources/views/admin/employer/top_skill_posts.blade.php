@extends('admin.layouts.master')
@section('content')
<div class="row">
    @if (Session::has('flash_notification'))
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Top Skill Posts Count</h4>
                <div class="table-responsive mt-3 no-wrap">
                <table id="default_order" class="table table-striped  table-sm table-bordered no-wrap">
                    <thead>
                        <tr>
                            <th class="border-0">{{ trans('admin.Name') }}</th>
                            <th class="border-0">{{ trans('admin.Total Count in Posts') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($top_skill_posts))
                            @foreach ($top_skill_posts as $posts)
                            
                            <?php $url = admin_url() . '/get_posts?employyeskill='. $posts->category_id; ?>
                                <tr>
                                
                                    <td class="td-nowrap"><a href="{{$url}}">{{ $posts->skill }}</a></td>
                                    <td class="td-nowrap"><a href="{{$url}}">{{ $posts->totalskills }}</a></td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    {{ trans('admin.No users found') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection