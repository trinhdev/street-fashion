<?php

namespace App\DataTables\Admin;

use App\Models\product;
use App\DataTables\BuilderDatatables;

class ProductsDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('id_category_parent', function ($row) { 
                return $row->category->name;
            })
            ->editColumn('name', function ($row) {
                return '
                    <a href="' . route('products.edit', $row->id) . '">' . $row->name . '</a>
                    <div class="row-options">
                        <a href="' . route('products.edit', $row->id) . '">Sửa</a> |
                        <a href="#" data-id="' . $row->id . '" onclick="dialogConfirmWithAjax(deleteProducts, this)" class="text-danger">Xóa</a>
                    </div>
                ';
            })
            ->editColumn('id_category_child', function ($row) { 
                return $row->categorychild->name;
            })
            ->editColumn('primary_image', function ($row) {
               
            
                $url = env('APP_URL');
                if ($row->primary_image) {
                    return '<img src="' . asset($row->primary_image) . '" alt="" width="50" height="50">';

                } else {
                    return 'No image';
                }
            })
            ->editColumn('second_image', function ($row) {
               
            
                $url = env('APP_URL');
                if ($row->second_image) {
                    return '<img src="' . asset($row->second_image) . '" alt="" width="50" height="50">';

                } else {
                    return 'No image';
                }
            })
            ->rawColumns(['primary_image', 'second_image', 'name']);
            ;
        }

    public function query(product $model)
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
            'name' => [
                'title' => 'Tên',
            ],

            'id_category_parent' => [
                'title' => 'Danh mục mẹ',
            ],
            'id_category_child' => [
                'title' => 'Danh mục con',
            ],
            'slug' => [
                'title' => 'Link rút gọn',
            ],
            'status' => [
                'title' => 'Trạng thái',
            ],
            'primary_image' => [
                'title' => 'Hình ảnh chính',
            ],
            'second_image' => [
                'title' => 'Hình ảnh hai',
            ],
            'quantity_favorite' => [
                'title' => 'Số lượt thích',
            ],
            'rate' => [
                'title' => 'Đánh giá',
            ]
        ];
    }
}
