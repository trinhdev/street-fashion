<?php

namespace App\Http\Controllers\Admin\DashBoard;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Status;
use App\Models\category;
use App\Imports\BrandImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\DataTables\Admin\BrandDatatable;
use Spatie\Permission\Models\Permission;
use App\DataTables\Admin\OrdersDataTable;
use App\Http\Controllers\Admin\BaseController;


class OrdersController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách đơn hàng';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrdersDataTable $dataTable, Request $request)
    {
       
        $list_status = Status::where('type','order')->get();

        $data = ['list_status' => $list_status];


        return $dataTable->render('admin.orders.index', ['data'=> $data]);
    }

    
    
    public function show(Request $request)
    {
        $data = Order::findOrFail($request->id);
        return response(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        
        $data = Order::findOrFail($request->id);
        
        $data->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Cập nhập thành công!']);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'order_status_id' => 'required|exists:order_status,id',
        ]);
    
        $order = Order::find($request->order_id);
        $order->order_status_id = $request->order_status_id;
        $order->save();
    
        return response()->json(['message' => 'Cập nhật trạng thái thành công.']);
    }
    

    
    public function destroy(Request $request)
    {
        $data = Brand::findOrFail($request->id);

        if ($data->image && Storage::exists($data->image)) {
            Storage::delete($data->image);
        }

        $data->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }


}
