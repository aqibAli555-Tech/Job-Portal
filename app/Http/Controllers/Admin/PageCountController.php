<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Models\PageCount;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class PageCountController extends AdminBaseController
{
    use VerificationTrait;

    public function get_all_page_count(Request $request)
    {
        // $data = Cache::remember('page_analytics', 60, function () {
        //     $allCounts = PageCount::get_all_page_counts();
        //     return $allCounts->groupBy('page')->map(function ($records) {
        //         return $records->take(5);
        //     })->toArray();
        // });

        $title = 'Page Analytics';
        $breadcumbs = [
            [
                'title' => 'Dashboard',
                'link' => admin_url('dashboard')
            ],
            [
                'title' => 'Page Analytics',
                'link' => 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.pages.analytics', compact('title', 'breadcumbs'));
    }

    public function getData()
    {
        $data = Cache::remember('page_analytics', 60, function () {
            $allCounts = PageCount::get_all_page_counts();
            return $allCounts->groupBy('page')->map(function ($records) {
                return $records->take(5);
            })->toArray();
        });

        $priorityOrder = ['user_registered', 'register_page', 'login_users'];
        $data = collect($data)->sortBy(function ($records, $page) use ($priorityOrder) {
            $priority = array_search($page, $priorityOrder);
            return $priority !== false ? $priority : count($priorityOrder);
        })->toArray();

        $response = array(
            'status' => true,
            'data' => $data,
        );
        return response()->json($response);
    }


    public function get_analytics_details()
    {
        $page_title = request()->get('type');
        $title = 'Page Analytics';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Page Analytics',
                'link'=> 'javascript:void(0)'
            ]
        ];
        return view('admin.settings.pages.analytics_details', compact('page_title','title','breadcumbs'));
    }

    public function get_analytics_details_ajax(Request $request)
    {
        $type = $request->query('type');
        $pageData = PageCount::get_all_search_page_count(request()->get('type'));
        $pageData_count = PageCount::get_all_search_page_count_data(request()->get('type'));
        $data = [];

        if(!empty($pageData)){
            foreach ($pageData as $key => $item){
                $counter = $key+1;
                $data[$key][] = '<td>'.$counter.'</td>';
                $data[$key][] = '<td>'.date('d-M-Y', strtotime($item->date)).'</td>';
                $data[$key][] = '<td>'.$item->count.'</td>';
                $row = '';
                if(!empty($item->query_parameter)){
                    $query_parameter = $item->query_parameter;
                    $query_parameter = str_replace(',', ', ', $query_parameter);
                    $row .=$query_parameter;
                    $data[$key][] = '<td>'.$row.'</td>';
                }

            }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            ['draw' => $request->get('draw'),
                'recordsTotal' => $pageData_count,
                'recordsFiltered' =>  $pageData_count,
                'data' => $data,
            ]
        );
        die;
    }

    public function statistics()
    {
        $data['statistics'] = Statistic::get_home_page_statistic();
        $title = 'Statistics';
        $breadcumbs = [
            [
                'title'=> 'Dashboard',
                'link'=> admin_url('dashboard')
            ],
            [
                'title'=> 'Statistics',
                'link'=> 'javascript:void(0)'
            ]
        ];

        return view('admin.settings.pages.statistics',compact('title','breadcumbs'))->with($data);
    }

    public function update_statistics(Request $request)
    {
        if(Statistic::where('id',1)
            ->update(['employees'=>$request->employees,'companies'=>$request->companies,'jobs'=>$request->jobs])){
            flash('Statistics Updated Successfully')->success();
            return redirect()->back();
        }else{
            flash('Unable to update statistics')->success();
            return redirect()->back();
        }
    }
}
