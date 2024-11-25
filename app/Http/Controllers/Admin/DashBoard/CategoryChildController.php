<?php

namespace App\Http\Controllers\Admin\DashBoard;

use App\Models\category;
use Illuminate\Http\Request;
use App\Models\Category_child;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\BaseController;
use App\DataTables\Admin\CategoryChildDabaTable;

class CategoryChildController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách danh mục';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryChildDabaTable $dataTable, Request $request)
    {   
        $list_category_parent = category::all();
        $data = ['list_category_parent' => $list_category_parent];


        return $dataTable->render('admin.category-child.index', ['data'=> $data]);
        
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
             
        ]);
        Category_child::create([
            'name'=> $request->name,
            'id_parent'=> $request->id_parent,
            'slug'=> $request->slug,
            
        ]);
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm danh mục thành công!']);
    }

    public function show(Request $request)
    {
        $module = Category_child::findOrFail($request->id);
        return response(['data' => $module]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);
        $module = Category_child::findOrFail($request->id);
        $module->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Sửa thành công!']);
    }

    public function destroy(Request $request)
    {
        $data = Category_child::findOrFail($request->id);

        if ($data->image && Storage::exists($data->image)) {
            Storage::delete($data->image);
        }

        $data->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
}
