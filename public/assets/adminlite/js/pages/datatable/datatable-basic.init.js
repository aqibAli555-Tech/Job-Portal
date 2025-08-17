/*************************************************************************************/
// -->Template Name: Bootstrap Press Admin
// -->Author: Themedesigner
// -->Email: niravjoshi87@gmail.com
// -->File: datatable_basic_init
/*************************************************************************************/

/****************************************
 *       Basic Table                   *
 ****************************************/

/****************************************
 *       Default Order Table           *
 ****************************************/


/****************************************
 *       Multi-column Order Table      *
 ****************************************/
$('#multi_col_order').DataTable({
    columnDefs: [{
        targets: [0],
        orderData: [0, 1]
    }, {
        targets: [1],
        orderData: [1, 0]
    }, {
        targets: [4],
        orderData: [4, 0]
    }]
});

function getQueryParam(param) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

if ($('.datatables-job-seekers').length) {

    var updatedCvParam = getQueryParam('updated_cv');
    var updatedCv = (updatedCvParam && updatedCvParam.toLowerCase() === 'true');

    var updated_skill = getQueryParam('updated_skill');
    var updated_skill = (updated_skill && updated_skill.toLowerCase() === 'true');

    var table_job_seekers = $('.datatables-job-seekers').DataTable({
            searching: false,
            processing: true,
            serverSide: true,
            pageLength: 50,
            aaSorting: [],
            lengthMenu: [50, 100, 250, 500],
            columnDefs: [{orderable: false, targets: 0}],
            order: [[1, 'asc']],
            dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

            ajax: {
                url: $('.datatables-job-seekers').data('url'),
                "data": function (d) {
                    return $.extend({}, d, {
                        "search": $("#search").val().toLowerCase(),
                        "employyeskill": $("#employyeskill").val(),
                        "country_code": $("#country_code").val(),
                        "no_contact_cv": $("#no_contact_cv").val(),
                        "last_login": $("#last_login").val(),
                        "updated_cv": updatedCv,
                        "updated_skill": updated_skill,
                        "nationality": $("#nationality").val(),
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                        window.location.href = xhr.responseJSON.redirect;
                    } else {
                        console.error('An error occurred:', xhr.responseText);
                    }
                }
            }
        }
    );

    $(document).ready(function () {
        $('#search').bind("keyup", function () {
            $('.datatables-job-seekers tbody tr').remove();
            table_job_seekers.draw();
        });
        $('#employyeskill,#country_code,#no_contact_cv,#last_login,#nationality').bind("change", function () {            $('.datatables-job-seekers tbody tr').remove();
            table_job_seekers.draw();
        });
    });
}


if ($('.datatables-employer').length) {
    var current_subscription_users = getQueryParam('current_subscription_users');

    var table_employer = $('.datatables-employer').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        aaSorting: [],
        lengthMenu: [50, 100, 250, 500],
        columnDefs: [{orderable: false, targets: 0}],
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-employer').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                    "last_login": $("#last_login").val(),
                    "current_subscription_users": $("#subscription_user").val(),
                    "affiliate_id": $("#affiliate_id").val(),
                    "filter": $("#filter").val(),
                    "daterange": $("#daterange").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search').bind("keyup", function () {
            $('.datatables-employer tbody tr').remove();
            table_employer.draw();
        });
        $('#last_login,#subscription_user, #affiliate_id').bind("change", function () {            $('.datatables-employer tbody tr').remove();
            table_employer.draw();
        });
        $(document).on('click', '.applyBtn,.cancelBtn ', function () {
            $('.datatables-employer tbody tr').remove();
            table_employer.draw();
        });    
    });
}

if ($('.datatables-transaction').length) {
    var table_transaction = $('.datatables-transaction').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-transaction').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search_new": $("#search_new").val().toLowerCase(),
                    "package": $("#package").val(),
                    "search_date": $("#search_date").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
    $(document).ready(function () {
        $('#search_new,#package,#search_date').bind("keyup change", function () {
            $('.datatables-transaction tbody tr').remove();
            table_transaction.draw();
        });
    });
}


if ($('.datatables-staff').length) {
    var table_staff = $('.datatables-staff').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-staff').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search').bind("keyup change", function () {
            $('.datatables-staff tbody tr').remove();
            table_staff.draw();
        });
    });
}

if ($('.datatables-job-post').length) {
    var pending_post = getQueryParam('pending_post');

    var table_job_post = $('.datatables-job-post').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-job-post').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                    "employyeskill": $("#employee_skill").val().toLowerCase(),
                    "status": $("#status").val().toLowerCase(),
                    "type": $("#post_type").val().toLowerCase(),
                    "pending_post": pending_post,
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search,#employee_skill,#status,#post_type').bind("keyup change", function () {
            $('.datatables-job-post tbody tr').remove();
            table_job_post.draw();
        });
    });
}


if ($('.datatables-applicant').length) {

    var table_applicants = $('.datatables-applicant').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        columnDefs: [{orderable: false, targets: 0}],
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-applicant').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                    "status": $("#status").val(),
                    "company": $("#company").val().toLowerCase(),
                    "post": $("#post").val(),
                    "is_deleted": $("#is_deleted").val(),
                    "unlock_applicant": $("#unlock_applicant").val(),
                    "daterange": $("#daterange").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search,#post,#status,#company,#is_deleted,#unlock_applicant').bind("keyup change", function () {
            $('.datatables-applicant tbody tr').remove();
            table_applicants.draw();
        });
        $(document).on('click', '.applyBtn,.cancelBtn ', function () {
            $('.datatables-applicant tbody tr').remove();
            table_applicants.draw();
        });
    });
}

if ($('.datatables-company').length) {
    var table_company = $('.datatables-company').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-company').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                    "country": $("#country").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search,#country').bind("keyup change", function () {
            $('.datatables-company tbody tr').remove();
            table_company.draw();
        });
    });
}

if ($('.datatables-employeeskill').length) {
    var table_employeeskill = $('.datatables-employeeskill').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-employeeskill').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search').bind("keyup change", function () {
            $('.datatables-employeeskill tbody tr').remove();
            table_employeeskill.draw();
        });
    });
}


if ($('.datatables-rejected-reason').length) {
    var table_rejected_reason = $('.datatables-rejected-reason').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-rejected-reason').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search').bind("keyup change", function () {
            $('.datatables-rejected-reason tbody tr').remove();
            table_rejected_reason.draw();
        });
    });
}


if ($('.datatables-package-cancel-reasons').length) {
    var table_package_cancel_reasons = $('.datatables-package-cancel-reasons').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-package-cancel-reasons').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-package-cancel-reasons tbody tr').remove();
    //         table_package_cancel_reasons.draw();
    //     });
    // });
}
if ($('.datatables-post-archived-reason').length) {
    var table_post_reason = $('.datatables-post-archived-reason').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-post-archived-reason').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
}

if ($('.datatables-genders').length) {
    var table_genders = $('.datatables-genders').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-genders').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-genders tbody tr').remove();
    //         table_genders.draw();
    //     });
    // });
}


if ($('.datatables-post-type').length) {
    var table_post_type = $('.datatables-post-type').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-post-type').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-post-type tbody tr').remove();
    //         table_post_type.draw();
    //     });
    // });
}


if ($('.datatables-salary-type').length) {
    var table_salary_type = $('.datatables-salary-type').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-salary-type').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-salary-type tbody tr').remove();
    //         table_salary_type.draw();
    //     });
    // });
}


if ($('.datatables-contact-us').length) {
    var table_contact_us = $('.datatables-contact-us').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        columnDefs: [{orderable: false, targets: 0}],
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-contact-us').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-contact-us tbody tr').remove();
    //         table_contact_us.draw();
    //     });
    // });
}


if ($('.datatables-skill-experience').length) {
    var table_skill_experience = $('.datatables-skill-experience').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-skill-experience').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-skill-experience tbody tr').remove();
    //         table_skill_experience.draw();
    //     });
    // });
}


if ($('.datatables-availability').length) {
    var table_availability = $('.datatables-availability').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-availability').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-availability tbody tr').remove();
    //         table_availability.draw();
    //     });
    // });
}

if ($('.datatables-pages').length) {
    var table_pages = $('.datatables-pages').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-pages').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-pages tbody tr').remove();
    //         table_pages.draw();
    //     });
    // });
}


if ($('.datatables-payments').length) {
    var searchParam = getQueryParam('search');

    function getSearch() {
        var searchFieldValue = $("#search").val().toLowerCase();
        return searchFieldValue ? searchFieldValue : (searchParam ? searchParam.toLowerCase() : "");
    }

    var table_payments = $('.datatables-payments').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-payments').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": getSearch(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search').bind("keyup change", function () {
            $('.datatables-payments tbody tr').remove();
            table_payments.draw();
        });
    });
}


if ($('.datatables-logs').length) {
    var type = getQueryParam('type');
    var table_logs = $('.datatables-logs').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-logs').data('url'),
            data: function (d) {
                return $.extend({}, d, {
                    search: $("#search").val().toLowerCase(),
                    "company": $("#company").val(),
                    "type": type,
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        },

        columns: [
            { data: 'id', title: 'ID' },
            { data: 'created_at', title: 'Created At' },
            { data: 'description', title: 'Description' },
        ],

        createdRow: function(row, data) {
            if (data.user_id != 0) {
                $(row).css('background-color', '#D2FFF7'); // Apply row color
            }
        }
    });

    $(document).ready(function() {
        $('#search,#company').bind("keyup change input", function() {
            table_logs.draw();
        });
    });
}

if ($('.datatables-mailgun-ajax').length) {
    var table_mailgun_ajax = $('.datatables-mailgun-ajax').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-mailgun-ajax').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-mailgun-ajax tbody tr').remove();
    //         table_mailgun_ajax.draw();
    //     });
    // });
}

if ($('.datatables-analytics-detail-ajax').length) {
    var table_datatables_analytics_detail = $('.datatables-analytics-detail-ajax').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-analytics-detail-ajax').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-analytics-detail-ajax tbody tr').remove();
    //         table_datatables_analytics_detail.draw();
    //     });
    // });
}


if ($('.datatables-contact_card_problem').length) {
    var table_contact_card_problem = $('.datatables-contact_card_problem').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        columnDefs: [{orderable: false, targets: 0}],
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-contact_card_problem').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    // "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    // Handle other errors
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    // $(document).ready(function(){
    //     $('#search').bind("keyup change", function(){
    //         $('.datatables-contact_card_problem tbody tr').remove();
    //         table_contact_card_problem.draw();
    //     });
    // });
}

if ($('.datatables-affiliates').length) {
    var current_subscription_users = getQueryParam('current_subscription_users');

    var table_affiliates = $('.datatables-affiliates').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        aaSorting: [],
        lengthMenu: [50, 100, 250, 500],
        columnDefs: [{orderable: false, targets: 0}],
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-affiliates').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                    "affiliate_id": $("#affiliate_id").val(),
                    "current_subscription_users": current_subscription_users,
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search, #affiliate_id').bind("change", function () {
            $('.datatables-affiliates tbody tr').remove();
            table_affiliates.draw();
        });
    });
}


$('#default_order').DataTable({
    searching: true,
    paging: true,
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
    order: [[1, 'desc']],  // Default sorting by the second column (Number of users)
    columnDefs: [{orderable: false, targets: 0}],  // Disable sorting on the first column (Country)
});

$('#zero_config').DataTable({
    "order": [[0, "desc"]],
    dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

});

if ($('.datatables-activitylogs').length) {
    var table_transaction = $('.datatables-activitylogs').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-activitylogs').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search_new": $("#search_new").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
    $(document).ready(function () {
        $('#search_new').bind("keyup change", function () {
            $('.datatables-activitylogs tbody tr').remove();
            table_transaction.draw();
        });
    });
}
if ($('.datatables-referral-commission').length) {

    var table_referral_commission = $('.datatables-referral-commission').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        aaSorting: [],
        lengthMenu: [50, 100, 250, 500],
        columnDefs: [{orderable: false, targets: 0}],
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-referral-commission').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "referrer": $("#referrer").val(),
                    "year": $("#year").val(),
                    "month": $("#month").val(),
                    "status": $("#status").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#referrer').bind("change", function () {
            $('.datatables-referral-commission tbody tr').remove();
            table_referral_commission.draw();
        });
        $('#year').bind("change", function () {
            $('.datatables-referral-commission tbody tr').remove();
            table_referral_commission.draw();
        });
        $('#month').bind("change", function () {
            $('.datatables-referral-commission tbody tr').remove();
            table_referral_commission.draw();
        });
        $('#status').bind("change", function () {
            $('.datatables-referral-commission tbody tr').remove();
            table_referral_commission.draw();
        });
        $(document).on('click', '.applyBtn,.cancelBtn ', function () {
            $('.datatables-applicant tbody tr').remove();
            table_referral_commission.draw();
        });
    });
}

if ($('.datatables-referral-users').length) {
    var table_referral_users = $('.datatables-referral-users').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-referral-users').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search_new": $("#search_new").val().toLowerCase(),
                    "referral_affiliate_id": $("#referral_affiliate_id").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
    $(document).ready(function () {
        $('#search_new').bind("keyup", function () {
            $('.datatables-referral-users tbody tr').remove();
            table_referral_users.draw();
        });
        $('#referral_affiliate_id').bind("change", function () {
            $('.datatables-referral-users tbody tr').remove();
            table_referral_users.draw();
        });
    });
}

if ($('.datatables-package-commissions').length) {
    var table_package_commissions = $('.datatables-package-commissions').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-package-commissions').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "id": $("#id").val(),
                    "package": $("#package").val(),
                    "date": $("#date").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
    $(document).ready(function () {
        $('#package,#date').bind("change", function () {
            $('.datatables-package-commissions tbody tr').remove();
            table_package_commissions.draw();
        });
    });
}

if ($('.datatables-withdraw-requests').length) {
    var table_withdraw_requests = $('.datatables-withdraw-requests').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-withdraw-requests').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "month": $("#month").val(),
                    "year": $("#year").val(),
                    "filter_status": $("#filter_status").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
    $('#year').bind("change", function () {
        $('.datatables-withdraw-requests tbody tr').remove();
        table_withdraw_requests.draw();
    });
    $('#month').bind("change", function () {
        $('.datatables-withdraw-requests tbody tr').remove();
        table_withdraw_requests.draw();
    });
    $('#filter_status').bind("change", function () {
        $('.datatables-withdraw-requests tbody tr').remove();
        table_withdraw_requests.draw();
    });
}

if ($('.datatables-commissions').length) {
    var table_commissions = $('.datatables-commissions').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-commissions').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "month": $("#month").val(),
                    "year": $("#year").val(),
                    "status": $("#status").val(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
    $('#year').bind("change", function () {
        $('.datatables-commissions tbody tr').remove();
        table_commissions.draw();
    });
    $('#month').bind("change", function () {
        $('.datatables-commissions tbody tr').remove();
        table_commissions.draw();
    });
    $('#status').bind("change", function () {
        $('.datatables-commissions tbody tr').remove();
        table_commissions.draw();
    });
}

if ($('.datatables-email-logs').length) {
    var table_email_logs = $('.datatables-email-logs').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-email-logs').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        },

    });
    $(document).ready(function() {
        $('#search').bind("keyup input", function() {
            $('.datatables-email-logs tbody tr').remove();
            table_email_logs.draw();
        });
    });
}

if ($('.datatables-twilio-logs').length) {
    var table_twilio_logs = $('.datatables-twilio-logs').DataTable({
        searching: true,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-twilio-logs').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        },

    });
    $(document).ready(function() {
        $('#search').bind("keyup input", function() {
            $('.datatables-twilio-logs tbody tr').remove();
            table_email_logs.draw();
        });
    });
}

if ($('.datatables-whatsApp').length) {
    var table_whatsApp = $('.datatables-whatsApp').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        aaSorting: [],
        lengthMenu: [50, 100, 250, 500],
        columnDefs: [{orderable: false, targets: 0}],
        order: [[1, 'asc']],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",

        ajax: {
            url: $('.datatables-whatsApp').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search": $("#search").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });

    $(document).ready(function () {
        $('#search').bind("keyup", function () {
            $('.datatables-whatsApp tbody tr').remove();
            table_whatsApp.draw();
        });
    });
}

if ($('.datatables-referral-affiliates').length) {
    var table_referral_affiliates = $('.datatables-referral-affiliates').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        pageLength: 50,
        lengthMenu: [50, 100, 250, 500],
        dom: "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>" + "t" + "<'row'<'col-sm-4'l><'col-sm-4'i><'col-sm-4 d-flex justify-content-end'p>>",
        ajax: {
            url: $('.datatables-referral-affiliates').data('url'),
            "data": function (d) {
                return $.extend({}, d, {
                    "search_new": $("#search_new").val().toLowerCase(),
                });
            },
            error: function (xhr) {
                if (xhr.status === 401 && xhr.responseJSON && xhr.responseJSON.redirect) {
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            }
        }
    });
    $(document).ready(function () {
        $('#search_new').bind("keyup", function () {
            $('.datatables-referral-affiliates tbody tr').remove();
            table_referral_affiliates.draw();
        });
    });
}