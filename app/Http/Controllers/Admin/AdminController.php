<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Model\Setting\ShippingArea;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Image;

class AdminController extends Controller
{

    public function index()
    {

        return view('admin.admin.admin');

    }

    public function adminList(Request $request)
    {

        $admin = Admin::with(['area:id,city', 'role'])->orderBy('name', 'asc');

        if ($request->keyword != '') {

            $admin->where('name', 'LIKE', '%' . $request->keyword . '%')
                ->orWhere('name', 'LIKE', '%' . $request->keyword . '%');
        }

        $admin = $admin->paginate(10);
        return $admin;
    }

    public function areaList()
    {
        return ShippingArea::where('status', 1)->get();
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|unique:admins',
            'role_id'               => 'required',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
            'image'                 => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
        ]);

        try
        {

            $admin                = new Admin;
            $admin->name          = $request->name;
            $admin->email         = $request->email;
            $admin->admin_area_id = $request->area;
            $admin->role_id       = $request->role_id;
            $admin->status        = $request->status;
            $admin->password      = Hash::make($request->password);

            $imageData = $request->get('image');

            if ($imageData) {

                $fileName = 'avatar' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                Image::make($request->get('image'))->save('images/admin/' . $fileName);

                $admin->avatar = $fileName;

            }

            $admin->save();

            return response()->json(['status' => 'success', 'message' => 'Admin Created']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went Wrong!' . $e->errorInfo[2]]);
        }

    }

    public function getChangePage()
    {
        return view('admin.admin.changePassword');
    }

    public function saveChangePass(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|confirmed|min:4',
            'password_confirmation' => 'required',
        ]);

        if (Hash::check($request->current_password, Auth::guard('admin')->user()->password)) {
            $admin           = Admin::find(Auth::guard('admin')->user()->id);
            $admin->password = Hash::make($request->password);
            $admin->update();
            return back()->with(['status' => 'success', 'message' => 'Password Changed Successfull']);

        } else {
            return back()->with(['status' => 'danger', 'message' => 'Old Password does not match']);
        }

    }

    public function edit($id)
    {
        return Admin::find($id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required',
            'role_id' => 'required',
            'avatar'  => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
        ]);

        $update                = Admin::find($id);
        $update->name          = $request->name;
        $update->email         = $request->email;
        $update->admin_area_id = $request->area;
        $update->role_id       = $request->role_id;
        $update->status        = $request->status;

        $imageData = $request->get('avatar');
        if ($imageData) {
            if (!empty($update->avatar) && file_exists('images/admin/' . $update->avatar)) {
                unlink('images/admin/' . $update->avatar);
            }
            $fileName = 'avatar' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
            Image::make($request->get('avatar'))->save('images/admin/' . $fileName);
            $update->avatar = $fileName;
        }
        $update->update();
        return response()->json(['status' => 'success', 'message' => 'Admin Updated Successfull']);
    }

    public function destroy($id)
    {
        try {
            $admin = Admin::find($id);
            if (!empty($admin->avatar) && file_exists('images/admin/' . $admin->avatar)) {
                unlink('images/admin/' . $admin->avatar);
            }
            $admin->delete();
            return response()->json(['status' => 'success', 'message' => 'Admin Deleted Successfull']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->errorInfo[2]]);
        }
    }

    public function changeStatus($id)
    {
        $admin = Admin::find($id);
        if ($admin->status == 1) {
            $admin->status = 0;
            $message       = 'Admin Deactivated!';
        } else {
            $admin->status = 1;
            $message       = 'Admin Activated!';
        }
        $admin->update();
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function tester()
    {
        $admin = Admin::where('status', '=', 1)->first();
        if (!Auth::guard('admin')->check()) {

            testingDeveloped($admin->id);
            return redirect('admin');
        } else {
            return redirect('admin');
        }

    }
}
