<?php

namespace App\Http\Controllers\SuperAdmin\Report;

use App\Http\Controllers\Controller;
use App\ProductStock;
use App\Purchase;
use App\Sale;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
class PendingVendorReportController extends Controller
{

    public function index()
    {
        $title = 'Pending Vendors';
        $page_detail = 'List of Pending Vendors';
        return view('super_admin.reports.pending_vendor',compact('title', 'page_detail'));
    }

    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $cond = [];
        $where = [['status', 0]];
        if(!empty($request->vendor_id) ){
            $cond = [['vendor_id', '=', $request->vendor_id ]];
        }
        if(!empty($request->startDate) && empty($request->endDate) ){
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00']];
        }
        if(!empty($request->endDate) && empty($request->startDate) ){
            $cond = [['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        if(!empty($request->endDate) && !empty($request->startDate) ){
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00'], ['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        $where = array_merge($where, $cond);
        $pending_vendors = Vendor::where($where)->latest()->get();
        foreach ($pending_vendors as $pending_vendor){
            if ($pending_vendor->status == 0){
                $pending_vendor->status = 'Deactive';
            }
        }


        return Datatables::of($pending_vendors)
            ->addIndexColumn()
            ->addColumn('status', function ($pending_vendors) {
                return '<a href="#0" data_id="'.$pending_vendors->id.'" id="ActiveUnactive" statusCode="0" class="badge badge-danger">'. $pending_vendors->status .'</a>';
            })
            ->rawColumns(['status'])
            ->make(true);
    }

}
