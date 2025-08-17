<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-header modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{t('Post Preview')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 page-content col-thin-right">
                    <div class="inner inner-box items-details-wrapper pb-0">
                        <h4 class="job-title">
                            <a href="#"><span id="title_post"></span> </a>
                            <br><span class="posttime" id="posted_on"></span>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="posttime">
                                <i class="icon-eye-3"></i> 0 Views
                            </span>
                        </h4>

                        <div class="spacer"></div>
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{t('Company')}}: </strong>
                                    <a href="{{url('/companyprofile')}}/{{auth()->user()->id}}" target="_blank"><span
                                            id="title_company"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Base Salary')}}: </strong>
                                    <span class="salary" id="base_salary">

                                    </span>
                                    <div class="spacer"></div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{t('Start Date')}}: </strong>
                                    <span id="start_dates"></span>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Negotiable')}}: </strong>
                                    <span id="negotiable_1"></span>
                                    <div class="spacer"></div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>{{t('Location')}}:</strong>
                                    <span class="item-location">
                                        <a href="#" id="post_city" target="_blank">
                                            <span id="company_city"></span>
                                        </a>,
                                        <a href="#" id="post_country" target="_blank">
                                            <span id="company_country"></span>
                                        </a>

                                    </span>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Job Type')}}: </strong>
                                    <a href="#" id="post_type" target="_blank">
                                        <span id="job_types"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Skill set')}}: </strong>
                                    <a href="#" id="post_skill_set" target="_blank">
                                        <span id="skill_set"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Transportation Available')}}: </strong>
                                    <span id="transportation_available_div"> </span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong> {{t('Overtime Pay available')}}: </strong>
                                    <span id="Overtime_Pay_available"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>

                                <div class="col-md-6">
                                    <strong>{{t('Housing Available')}}: </strong>
                                    <span id="Housing_Available"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Experience')}}: </strong>
                                    <span id="experience_data"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Gender Preference')}}: </strong>
                                    <span id="gender_data"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>

                                <div class="col-md-6">
                                    <strong>{{t('nationality')}}: </strong>
                                    <span id="nationality_data"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                <div class="col-md-6">
                                    <strong>{{t('Who Can Apply?')}}: </strong>
                                    <span id="who_can_apply_data"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>

                                {{--  --}}
                                <div class="col-md-6">
                                    <strong>{{t('Where Can Employees (Job Seekers) Apply From?')}}: </strong>
                                    <span id="where_can_apply_data"></span>
                                    </a>
                                    <div class="spacer"></div>
                                </div>
                                {{--  --}}
                            </div>
                        </div>
                        <br>
                        <h4 class="">{{t('Job Details')}} : </h4>

                        <!-- Description -->
                        <div>
                            <p id="Job_Details"></p>
                        </div>

                        <!-- Company Description -->
                        <h4>{{t('Company Description')}} : </h4>
                        <div>
                            <span id="Company_Description"></span>

                        </div>
                        <div class="items-details">
                            <div class="row pb-4">
                                <div
                                    class="items-details-info jobs-details-info col-md-8 col-sm-12 col-xs-12 enable-long-words from-wysiwyg">

                                    <!-- Tags -->

                                </div>
                            </div>
                        </div>

                    </div>

                    <!--/.items-details-wrapper-->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_new_skill_set" tabindex="-1" role="dialog" aria-labelledby="add_new_skill_set_label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_new_skill_set_label">{{t('Add New Skill Set')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{url('posts/add_new_skill')}}" method="POST">
                @csrf
                    <div class="form-group">
                        <label for="skill_set" class="col-form-label">Add Skill:</label>
                        <input type="text" class="form-control" id="skill_set" required name="skill_set">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


<script>
function previewpost() {
    var form_data = $("#postForm").serializeArray();
    $.ajax({
        url: "<?php echo url('posts/preview_post')?>",
        type: "post",
        data: form_data,
        beforeSend: function() {
            $("#overlay").show();
        },
        success: function(response) {
            $("#overlay").hide();
            var result = JSON.parse(response)
            $('#title_post').html(result.post_data.title);
            if (result.post_data.hide_company_logo != 1) {
                $('#title_company').html(result.company.name);
            } else {
                $('#title_company').html('This company decided to hide it’s logo & name for this job post');
            }
            if (result.post_data.who_can_apply == 1) {
                var title_who_can_apply = "Only Employees (Jobs Seekers) Living in " + result.country.name +
                    ' ' + result.image;
            } else {
                var title_who_can_apply = "Anyone Living Anywhere In The World  🌎";
            }
            if (result.post_data.post_type == 2) {
                var who_can_apply_data = 'Only These Skills Sets: ' + result.skill_set;
            } else {
                var who_can_apply_data = 'All Skills Sets';
            }
            $('#who_can_apply_data').html(who_can_apply_data);
            $('#where_can_apply_data').html(title_who_can_apply);
            var postedOn = "{{t('Posted On')}} " + result.post_data.created_at;

            $('#posted_on').html(postedOn);
            var baseSalary = " Hidden by Employer";
            if (result.post_data.hide_salary == 1) {
                $('#base_salary').html(baseSalary);
            } else {
                var salary_type = '';
                if (result.post_data.salary_type_id == 1) {
                    salary_type = 'Per Hour';
                } else if (result.post_data.salary_type_id == 2) {
                    salary_type = 'Per Day';
                } else if (result.post_data.salary_type_id == 3) {
                    salary_type = 'Per Month';
                } else if (result.post_data.salary_type_id == 4) {
                    salary_type = 'Per year';
                } else {
                    salary_type = '';
                }
                var baseSalary = result.post_data.salary_min + "-" + result.post_data.salary_max + '  ' +
                    salary_type;
                $('#base_salary').html(baseSalary);
            }
            $('#start_dates').html(result.post_data.start_date);
            $('#job_types').html(result.post_type.name);
            $('#skill_set').html(result.category.skill);

            var transportation_availabledata = '';
            if (result.post_data.transportation_available == 1) {
                transportation_availabledata = 'Yes';
            } else {
                transportation_availabledata = 'No';
            }

            $('#transportation_available_div').html(transportation_availabledata);

            var overtime_pay = '';
            if (result.post_data.overtime_pay == 1) {
                overtime_pay = 'Yes';
            } else {
                overtime_pay = 'No';
            }
            $('#Overtime_Pay_available').html(overtime_pay);

            var housing_available = '';
            if (result.post_data.housing_available == 1) {
                housing_available = 'Yes';
            } else {
                housing_available = 'No';
            }
            $('#Housing_Available').html(housing_available);
            $('#Job_Details').html(result.post_data.description);
            $('#Company_Description').html(result.company.description);
            $('#company_city').html(result.city.name);
            $('#company_country').html(result.country.name);


            var negotiable1 = '';
            if (result.post_data.negotiable == 1) {
                negotiable1 = 'Yes';
            } else {
                negotiable1 = 'No';
            }
            $('#negotiable_1').html(negotiable1);
            $('#experience_data').html(result.post_data.experience);
            $('#gender_data').html(result.post_data.gender);
            $('#nationality_data').html(result.nationality);

            $('#post_city').attr('href',result.post_city_url);
            $('#post_country').attr('href', result.post_country_url);
            $('#post_skill_set').attr('href',result.post_skill_set_url);
            $('#post_type').attr('href',result.post_type_url);



            $('#exampleModal').modal('show');

        },
    });
}
</script>