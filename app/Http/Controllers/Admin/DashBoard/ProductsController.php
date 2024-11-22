<?php

namespace App\Http\Controllers\Admin\DashBoard;

use App\Models\Brand;
use App\Models\product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

use App\Models\Category_child;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\DataTables\Admin\ProductsDataTable;
use App\Http\Controllers\Admin\BaseController;
use App\Models\product_meta;

class ProductsController extends BaseController
{

    public $model;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sach sản phẩm';
        $this->model = new product();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDataTable $dataTable, Request $request)
    {
        return $dataTable->render('admin.products.index');
    }
    public function getAll($model)
    {
        return $model::all();  // Lấy tất cả các bản ghi từ bảng liên quan
    }
    public function create(Request $request)
{
    // Lấy danh sách danh mục và thương hiệu
    $list_categories = $this->getAll(new Category_child);
    

    // Chuẩn bị dữ liệu để gửi vào view
    $data = [
        'list_categories' => $list_categories
       
    ];

    // Trả về view với dữ liệu đã chuẩn bị
    return view('admin.products.create', ['data' => $data]);
}


    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255', // Tên sản phẩm là bắt buộc và không quá 255 ký tự
            'path_1.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validate hình ảnh
            'id_category_child' => 'required', // Danh mục là bắt buộc và phải tồn tại trong bảng category
            
        ]);

        // Lấy id_category_parent từ bảng Category_child
        $categoryChild = Category_child::find($request->id_category_child);
        $id_category_parent = $categoryChild->id_parent;

        

        // Tạo sản phẩm và gán id_category_parent
        $productData = $request->all();
        $productData['id_category_parent'] = $id_category_parent;


         $product = Product::create($productData);

        if ($request->hasFile('path_1')) {
            $images = [];
            if ($request->hasFile('path_1')) {
                foreach ($request->file('path_1') as $image) {
                    // Tạo tên tệp duy nhất
                    $filename = hash('sha256', time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
        
                    // Lưu hình ảnh vào thư mục 'upload/products'
                    $path = $image->storeAs('upload/products', $filename, 'public');
                    $dbPath = '/storage/' . $path; // Đường dẫn lưu trong cơ sở dữ liệu
        
                    // Lưu đường dẫn vào mảng hình ảnh
                    $images[] = $dbPath;
                }
        
                // Cập nhật hình ảnh vào trường 'primary_image' hoặc 'second_image' trong bảng 'Product'
                if (count($images) > 0) {
                    $product->primary_image = $images[0]; // Lưu hình ảnh đầu tiên làm hình ảnh chính
        
                    // Nếu có hình ảnh thứ hai, lưu vào trường second_image
                    if (count($images) > 1) {
                        $product->second_image = $images[1];
                    }}

        // Lưu cập nhật sản phẩm
        $product->save();
    }
}

        return redirect()->route('admin.products.index')->with(['status'=>'success', 'html' => 'Thành công']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function importView()
    {
        return view('products.import_view');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ProductImage(), $request->file('file'));

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Thêm sản phẩm thành công!'
        ]);
    }

    public function edit($id)
    {
        $list_categories = $this->getAll(new Category_child);
        $product = Product::with('product_meta')->find($id);

        $data = [
            'list_categories' => $list_categories,
        
        ];
        
        return view('admin.products.edit')->with(['products'=>$product,'data'=>$data]);
    }

    

    public function update(Request $request, $id)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'name' => 'required|string|max:255',
        'id_category_child' => 'required',
        'path_1.*' => 'nullable|image|mimes:jpeg,webp,png,jpg,gif|max:2048', // Định dạng ảnh
    ]);

    // Tìm sản phẩm cần cập nhật
    $product = Product::findOrFail($id);

    // Cập nhật thông tin sản phẩm
    $product->name = $request->input('name');
    $product->id_category_child = $request->input('id_category_child');
    $product->status = $request->has('status') ? 1 : 0; // Cập nhật trạng thái
    $productMeta = $product->product_meta->first();
    $productMeta->price = $request->price;
    $productMeta->price_sale = $request->price_sale;
    $product->save();

    // Xử lý hình ảnh mới
    $images = [];
    if ($request->hasFile('path_1')) {
        foreach ($request->file('path_1') as $image) {
            // Tạo tên tệp duy nhất
            $filename = hash('sha256', time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();

            // Lưu hình ảnh vào thư mục 'upload/products'
            $path = $image->storeAs('upload/products', $filename, 'public');
            $dbPath = '/storage/' . $path; // Đường dẫn lưu trong cơ sở dữ liệu

            // Lưu đường dẫn vào mảng hình ảnh
            $images[] = $dbPath;
        }

        // Cập nhật hình ảnh vào trường 'primary_image' hoặc 'second_image' trong bảng 'Product'
        if (count($images) > 0) {
            $product->primary_image = $images[0]; // Lưu hình ảnh đầu tiên làm hình ảnh chính

            // Nếu có hình ảnh thứ hai, lưu vào trường second_image
            if (count($images) > 1) {
                $product->second_image = $images[1];
            }

            // Lưu vào cơ sở dữ liệu
            $product->save();
        }
    }

    // Xử lý xóa hình ảnh
    if ($request->input('deleted_images')) {
        $deletedImageIds = explode(',', $request->input('deleted_images'));

        // Xóa hình ảnh khỏi thư mục lưu trữ
        foreach ($deletedImageIds as $deletedImageId) {
            // Kiểm tra xem có hình ảnh nào không còn sử dụng nữa
            $imageToDelete = ProductImage::find($deletedImageId);
            if ($imageToDelete) {
                // Xóa hình ảnh khỏi thư mục lưu trữ
                Storage::disk('public')->delete(str_replace('/storage/', '', $imageToDelete->image_path));
                // Xóa bản ghi trong cơ sở dữ liệu
                $imageToDelete->delete();
            }
        }
    }

    // Xử lý cập nhật hình ảnh chính
    if ($request->input('primary_image_id')) {
        $primaryImageId = $request->input('primary_image_id');

        // Cập nhật trạng thái hình ảnh chính
        ProductImage::where('product_id', $product->id)->update(['is_primakey' => false]); // Đặt tất cả hình ảnh là không phải chính
        ProductImage::where('id', $primaryImageId)->update(['is_primakey' => true]); // Đánh dấu hình ảnh được chọn là hình ảnh chính
    }

    // Trả về thông báo thành công
    

    
        return redirect()->route('admin.products.index')->with(['success'=>'Cập nhật sản phẩm thành công!']);
    }
    


    public function login(Request $request)
    {
        auth()->loginUsingId($request->id);
        return redirect()->intended('/');
    }

   public function destroy(Request $request)
   {
       $this->model->destroy($request->id);
       $this->addToLog(request());
       return response()->json(['message' => 'Xóa thành công!']);
   }
}
