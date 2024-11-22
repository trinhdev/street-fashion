<?php

namespace App\DataTables\Admin;

use App\Models\Order;
use App\Models\Status;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class OrdersDataTable extends BuilderDatatables
{
    // protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    // protected $hasCheckbox = false;
    // protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            
            ->editColumn('order_code', function ($row) {
                return '
                     <a href="#" data-id="'.$row->id.'" onclick="detailOrder(this)">'.$row->order_code.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailOrder(this)">Sửa</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteBrand, this)" class="text-danger">Xóa</a>
                    </div>
                ';
            })
            
            ->editColumn('user_id', function ($row) {
                return $row->user->name ?? 'N/A';
            })
            ->editColumn('order_status_id', function ($row) {
                // Lấy danh sách các trạng thái với `type = 'order'`
                $statuses = Status::where('type', 'order')->get();
            
                // Tạo các tùy chọn với màu sắc tùy thuộc vào status_id
                $statusHtml = '';
                foreach ($statuses as $status) {
                    // Kiểm tra trạng thái để áp dụng màu sắc
                    if ($row->order_status_id == $status->id) {
                        // Gán màu sắc cho từng trạng thái
                        $color = ''; // Màu mặc định
                        switch ($status->id) {
                            case 9:
                                $color = 'background-color: #FFF8E5; color: black; border: 2px solid #FFD700;'; // Màu vàng nhạt, viền vàng
                                break;
                            case 11:
                                $color = 'background-color: #F0F9FE; color: black; border: 2px solid #00BFFF;'; // Màu xanh nhạt, viền xanh
                                break;
                            case 12:
                                $color = 'background-color: #E7FFF2; color: black; border: 2px solid #32CD32;'; // Màu xanh lá nhạt, viền xanh lá
                                break;
                            case 13:
                                $color = 'background-color: #FF99A5; color: black; border: 2px solid #FF69B4;'; // Màu hồng nhạt, viền hồng
                                break;
                            default:
                                $color = 'background-color: black; color: white; border: 2px solid #FFFFFF;'; // Màu mặc định, viền trắng
                                break;
                        }
                        
                        // Tạo HTML để hiển thị tên trạng thái với màu sắc và border
                        $statusHtml = "<span style='$color padding: 5px; border-radius: 4px;'>{$status->status_name}</span>";
                    } 
                }
            
                // Trả về HTML hiển thị trạng thái với màu sắc và border
                return $statusHtml;
            })

            // ->editColumn('payment_id', function ($row) {
            //     // Kiểm tra mối quan hệ `payment` đã tồn tại
            //     if ($row->payment) {
            //         // Lấy `payment_status` từ mối quan hệ
            //         $paymentStatusId = $row->payment->payment_status;
            
            //         // Truy vấn bảng `status` để lấy `status_name`
            //         $status = Status::find($paymentStatusId);
            
            //         // Trả về `status_name` nếu tìm thấy, nếu không thì hiển thị "Không có trạng thái"
            //         return $status ? $status->status_name : 'Không có trạng thái';
            //     }
            
            //     // Trường hợp không có `payment`, trả về thông báo mặc định
            //     return 'Không có thông tin thanh toán';
            // })
            
            ->editColumn('checkbox', function ($row) {
                return '<div class="checkbox"><input type="checkbox" value="' . $row->event_id . '"><label></label></div>';
            })
            ->editColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 0, ',', '.') . 'đ';
            })
           
        
            
            ->rawColumns(['order_code','checkbox','user_id','order_status_id','total_amount']);
    }

    public function query(Order $model)
    {
        return $model->newQuery();
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => 'ID',
                'width' => '20px',
            ],
            'order_code' => [
                'title' => 'Mã đơn hàng',
            ],
            'user_id' => [
                'title' => 'Người mua',
            ],
            'order_status_id' => [
                'title' => 'Trạng thái',
            ],
            
            'total_amount' => [
                'title' => 'Tổng đơn',
            ]
        ];
    }
}
