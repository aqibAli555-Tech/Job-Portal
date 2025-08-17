<html lang="en">
<head>
    <title>Bulk Download CV</title>
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://bootswatch.com/3/flatly/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        default:active:hover, .btn-default.active:hover, .open > .dropdown-toggle.btn-default:hover, .btn-default:active:focus, .btn-default.active:focus, .open > .dropdown-toggle.btn-default:focus, .btn-default:active.focus, .btn-default.active.focus, .open > .dropdown-toggle.btn-default.focus {
            outline: none;
            box-shadow: none;
        }

        .filter-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-btn-group {
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <br>
    <br>
    <div class="container-fluid">
        <div class="filter-row">
            <div class="btn-group filter-btn-group" role="group" id="filter-buttons">
                <button type="button" class="btn btn-default filter-btn active" data-filter="all">All (<span class="count"
                   data-class="all">0</span>)
               </button>
               <button type="button" class="btn btn-default filter-btn" data-filter="pending">Pending (<span class="count"
                  data-class="pending">0</span>)
              </button>
              <button type="button" class="btn btn-default filter-btn" data-filter="rejected">Rejected (<span
                class="count" data-class="rejected">0</span>)
            </button>
            <button type="button" class="btn btn-default filter-btn" data-filter="inprocess">In Process (<span
                class="count" data-class="inprocess">0</span>)
            </button>
        </div>
        <div style="width: 250px;">
            <input type="text" id="searchBox" class="form-control" placeholder="Search employees...">
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col">Sr #</th>
                <th scope="col">Employee</th>
                <th scope="col">Rejected Reason</th>
                <th scope="col">Status</th>
                <th scope="col">CV</th>
            </tr>
            <tr class="nothing-found" style="display: none;">
                <td colspan="5" class="text-center">Nothing found</td>
            </tr>

        </thead>
        <tbody>
            <?php $counter = 1;
            foreach ($user_cvs_data as $key => $status){
                if ($key == "pending") {
                    $class = "info";
                } elseif ($key == 'rejected') {
                    $class = "danger";
                } else {
                    $class = "primary";
                }

                ?>
                @foreach($status as $item)
                <tr class="<?=$key;?>">
                    <td>{{ $counter++ }}</td>
                    <td><span class="label label-primary">{{$item->id}}</span> {{$item->name}}<br>
                        <small>{{$item->email}}</small></td>
                        <td> {{ ($key ==  'rejected') ? $item->cv_no_contact_rejected_reason : '' }}</td>
                        <td><span class="label label-<?=$class;?>"><?= ucfirst($key) ?></span></td>
                        <td>
                            <?php if ($key == 'inprocess'){ ?>
                                <a class="btn btn-info btn-xs" download="{{$item->id}}.pdf"
                                 href="{{ url('account/resumes/show_cv/' . $item->id.'?set_not_contact_cv_status_to_process=0') }}"
                                 target="_blank">Download CV</a>
                             <?php } else { ?>

                                <a class="btn btn-info btn-xs" download="{{$item->id}}.pdf"
                                 href="{{ url('account/resumes/show_cv/' . $item->id.'?set_not_contact_cv_status_to_process=1') }}"
                                 target="_blank">Download CV</a>
                             <?php } ?>
                         </td>

                     </tr>
                     @endforeach
                 <?php } ?>
             </tbody>
         </table>
     </div>
     <script>
        function updateCounters() {
            var total = $('table tbody tr').not('.nothing-found').length;
            $('.count[data-class="all"]').text(total);

            var classes = ['pending', 'rejected', 'inprocess'];
            classes.forEach(function(cls) {
                var count = $('table tbody tr.' + cls).length;
                $('.count[data-class="' + cls + '"]').text(count);
            });
        }

        function updateSrNo() {
            $('table tbody tr:visible').not('.nothing-found').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        $('.filter-btn').on('click', function () {
            var filterClass = $(this).data('filter');

            $('.filter-btn').removeClass('active');
            $(this).addClass('active');

            if (filterClass === 'all') {
                $('table tr').show();
            } else {
                $('table tr').hide();
                $('table tr.' + filterClass).show();
            }
            updateSrNo();
        });
        updateCounters();
        $('#searchBox').on('keyup', function () {
            applyFilters();
        });
    </script>
</body>
</html>



