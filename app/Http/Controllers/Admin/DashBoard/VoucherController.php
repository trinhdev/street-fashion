<?php

namespace App\Http\Controllers\Admin\DashBoard;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\DataTables\Admin\VoucherDatatable;
use App\Http\Controllers\Admin\BaseController;

class VoucherController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách mã giảm giá';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VoucherDatatable $dataTable, Request $request)
    {
        return $dataTable->render('admin.vouchers.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:vouchers,code|max:255',
            'discount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_count' => 'nullable|integer|min:0',

        ]);
        Voucher::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm mã giảm giá thành công!']);
    }

    public function show(Request $request)
    {
        $data = Voucher::findOrFail($request->id);
        return response(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_count' => 'nullable|integer|min:0',

        ]);
        $data = Voucher::findOrFail($request->id);
        $data->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Cập nhập thành công!']);
    }

    public function destroy(Request $request)
    {
        $data = Voucher::findOrFail($request->id);
        $data->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $voucher = Voucher::findOrFail($request->id);
        $voucher->status == 0 ? $voucher->status =1 : $voucher->status = 0;
        $voucher->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }


}
