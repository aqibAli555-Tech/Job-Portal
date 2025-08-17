<table class="table table-sm text-left applicant_list" id="Applied">
    <thead>
        <tr>
            <th style="width: 20%;">{{ t('User Details') }}</th>
            <th style="width: 20%;">{{ t('Job Post Title') }}</th>
            <th style="width: 20%;">{{ t('skill_set') }}</th>
            <th style="width: 20%;"><span data-toggle="tooltip" class="applicant-tooltip-class" data-placement="top"
                    data-trigger="manual" title="{{ t('skill_accuracy_note') }}">{{ t('skill_accuracy') }}</span></th>
            <th style="width: 20%;" class="action">{{ t('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        <?php $appliedCounter = 0;
        foreach ($unlocked_applicants_list as $key => $item) {
            if ($item->status == 'applied') {
                $appliedCounter++;
                if ($item->skill_accuracy == 'Accurate') {
                    $color = "btn-warning";
                } elseif ($item->skill_accuracy == 'Very Accurate') {
                    $color = "btn-success";
                } elseif ($item->skill_accuracy == 'Not Accurate') {
                    $color = "btn-danger";
                } else {
                    $color = "";
                }
                ?>
        <tr class="job-items">
            <td class="text-left">
                <div class="user-image-div-message"
                    style="background-image:url('{{ \App\Helpers\Helper::getImageOrThumbnailLink($item->user) }}')">
                </div>
                <span><strong class="font-weight-bolder">
                        {{ t('Name') }}:
                    </strong><small class="text-capitalize"> <a
                            href="{{ url('profile/' . $item->user_id) }}">{{ $item->name }} </a></small></span>
                <br>
                <span>Nationality:</span> <?= $item->user->nationalityData->name ?? '' ?>
                <br>
                <small class="text-info"><?= date('d-M-Y', strtotime($item->created_at)) ?></small>
            </td>
            <td class="title">
                @if (!empty($item->contact_unlock) && $item->contact_unlock == 1)
                    {{ t('Unlocked This Contact Through CV Search Page') }}
                @else
                    {{ $item->post['title']??'' }}
                @endif
            </td>

            <td>
                <?php
                $skill_sets = $item->user->skill_set;
                $skill_sets = str_replace(',', ', ', $skill_sets);
                echo $skill_sets;
                ?>
            </td>
            <td><span class="badge {{ $color }}">
                    {{ $item->skill_accuracy }}</span></td>
            <td class="title float-left">
                <select class="options" onchange="change_applicants_status(this.value)">
                    <option>Select option</option>
                    <option value="{{ lurl('account/Applicants/haired/' . $item->id) }}" style="background-color: #2ecc71;color: white;" id="hired">{{ t('Hired') }}</option>
                    <option value="{{ lurl('account/Applicants/interview/' . $item->id) }}" style="background-color: black;color: white;">
                        {{ t('Interview') }}
                    </option>
                    <option value="{{ lurl('account/Applicants/rejected/' . $item->id) }}" style="background-color: #d9534f;color: white;">
                        {{ t('Rejected') }}
                    </option>
                </select>
{{--                <select class="options change_applicants_status_ajax"  applicant_id="{{ $item->id }}">--}}
{{--                <option>Select option</option>--}}
{{--                <option value="haired" style="background-color: #2ecc71;color: white;"> {{ t('Hired') }} </option>--}}
{{--                <option value="interview" style="background-color: black;color: white;"> {{ t('Interview') }} </option>--}}
{{--                <option value="rejected" style="background-color: #d9534f;color: white;"> {{ t('Rejected') }} </option>--}}
{{--                </select>--}}
            </td>
        </tr>
        <?php }
    } ?>
    </tbody>
</table>


<script>
    function change_applicants_status(url) {
         $('#unlock_applicants_modal').modal('hide');
        if (url.indexOf("interview") != -1) {
            var icons = '';
            var message =
                'Are you sure you would like to Interview this applicant? An automatic email will be sent to them stating your potential interest and that you could be contacting them.';
        }
        if (url.indexOf("haired") != -1) {
            var icons = 'success';
            var message =
                'Are you sure you would like to Hire this applicant? An automatic email will be sent to congratulate them with their new position  with you - please only click Yes if you will actually be hiring them.';
        }
        if (url.indexOf("rejected") != -1) {
            var icons = '';
            var message =
                'Are you sure you want to reject this applicant? You can change their status anytime in the future.';
        }
        if (url.indexOf("applied") != -1) {
            var icons = '';
            var message = 'Are you sure you would like to Applied this applicant?.';
        }
        const config = {
            html: true,
            title: 'Attention',
            html: message,
            icon: icons,
            confirmButtonText: 'Yes',
            showCancelButton: true,
        };
        Swal.fire(config).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

                if (url.indexOf("rejected") != -1) {
                    var urlParts = url.split("/");
                    var id = urlParts[urlParts.length - 1];
                    $('#rejected_modal').modal('show');
                    $('#id').val(id);
                } else {
                    window.location.replace(url);
                }

            } else if (result.isDenied) {
                return false;
            }
        });

    }
</script>
