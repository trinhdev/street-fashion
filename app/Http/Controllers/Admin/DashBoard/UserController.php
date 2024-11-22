<?php

namespace App\Http\Controllers\Admin\DashBoard;
use App\DataTables\Admin\UserDataTable;
use App\Http\Controllers\Admin\BaseController;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    public $model;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'List User';
        $this->model = new User();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable, Request $request)
    {
        return $dataTable->render('admin.user.index');
    }

    public function create()
    {
        $role = Role::get();
        return view('admin.user.create')->with(['role'=>$role]);
    }

    public function store(UserStoreRequest $request)
    {
        $request->merge([
            'password' => Hash::make($request->password),
            'created_by' => auth()->user()->id
        ]);
        $user = User::create($request->only(['name', 'email', 'password', 'created_by']));
        if(!empty($request->administrator)) {
            $role = Role::firstOrCreate(['name' => 'Admin']);
        } else {
            $role = Role::firstOrCreate(['name' => $request->role]);
        }
        $user->syncRoles([$role->name]);
        return redirect()->route('user.index')->with(['status'=>'success', 'html' => 'Thành công']);
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

    public function edit($id)
    {
        $role = Role::get();
        $user = User::find($id);
        return view('admin.user.edit')->with(['user'=>$user, 'role'=>$role]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,$id", // Kiểm tra email duy nhất, bỏ qua user hiện tại
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:password',
            'role' => 'required|string',
        ]);
        $request->request->remove('password_confirmation');
        if($request->password != null){
            $request->merge([
                'password' => Hash::make($request->password),
                'created_by' => auth()->user()->id
            ]);
        }else{
            $request->request->remove('password');
            $request->request->remove('password_confirmation');
        }
        $user = User::find($id);
        $user->update($request->only(['name', 'email', 'password','role_id', 'created_by']));
        if (!empty($request->administrator)) {
            $role = Role::firstOrCreate(['name' => 'Admin']);
        } else {
            $role = Role::firstOrCreate(['name' => $request->role]);
        }
        $user->syncRoles($role);
        $this->addToLog($request);
        return redirect()->route('user.index')->with(['status'=>'success', 'html' => 'Thành công']);
    }

    public function login(Request $request)
    {
        auth()->loginUsingId($request->id);
        return redirect()->intended('/admin');
    }

    public function destroy(Request $request)
    {
        $user = User::find($request->id);
    
        if (!$user) {
            return response()->json(['message' => 'Người dùng không tồn tại!'], 404);
        }
    
        $user->delete();
        $this->addToLog($request);
    
        return response()->json(['message' => 'Xóa thành công!']);
    }
}
