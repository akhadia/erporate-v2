<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Role;
use App\Permission;
use App\PermissionRole;

use Yajra\Datatables\Datatables;

class RoleController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:read-acl|create-acl|update-acl|delete-acl');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role;
        $permission = Permission::all();
        return view('Role.form', compact('role','permission'));    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $all_data = $request->all(); 

        $data_role = array(
                        'name'          =>   $all_data['nama_role'],
                        'display_name'  =>   $all_data['display_name'],
                        'description'   =>   $all_data['description'],
                    );
        
        //1) Create Admin Role
        $role = Role::create($data_role);

        //2) Set Role Permissions
        if(isset($all_data['permission'])){
            $permission = Permission::whereIn('id', $all_data['permission'])->get();
            foreach ($permission as $key => $value) {
                $role->attachPermission($value);
            }
        }
       

        if($role){
            $message = "Data role berhasil ditambahkan";
            $status = 'success';
        }else{
            $message = "Data role gagal ditambahkan";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('role')->with($notification);
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::where('id',$id)->first();
        $permission_role = PermissionRole::where('role_id',$id)->get();
        $permission = Permission::all();
        
        return view('Role.form', compact('role','permission','permission_role'));    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = DB::transaction(function () use ($request, $id) {

            $all_data = $request->all(); 
    
            $data_role = array(
                            'name'          =>   $all_data['nama_role'],
                            'display_name'  =>   $all_data['display_name'],
                            'description'   =>   $all_data['description'],
                        );
            try{
                $update_role = Role::findOrFail($id);
                $act = $update_role->update($data_role);

                $permission = PermissionRole::where('role_id', $id)->get();
                foreach ($permission as $key => $value) {
                    $update_role->detachPermission($value);
                }
    
                $permission2 = Permission::whereIn('id', $all_data['permission'])->get();
                foreach ($permission2 as $key2 => $value2) {
                    $update_role->attachPermission($value2);
                }
    
                DB::commit();

                $notification = array(
                    'message' => 'Data role berhasil diubah', 
                    'alert-type' => 'success' 
                );
            } catch (\Exception $e) {
                DB::rollback();

                $notification = array(
                    'message' => 'Data role gagal diubah', 
                    'alert-type' => 'error' 
                );
            }

            return Redirect::to('role')->with($notification);
        
        });
          
        // redirect the page
        return $result;
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function loadData(){
        $nama_role = \Request::input('nama_role');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Role::where(function ($q) use ($nama_role){
                if(!empty($nama_role)){
                    $nama_role = strtoupper($nama_role );
                    $q->where(DB::raw('upper(name)'), 'LIKE', "%$nama_role%");
                } 
            })
            ->orderby('id','desc')
            ->get();

        return Datatables::of($dataList)
            ->addColumn('nomor',function($dataList){
                return $GLOBALS['nomor']++;
            })  
          
            ->addColumn('action', function ($dataList) {
                $content = '<div class="btn-toolbar">';
                if (\Laratrust::can('update-acl')) {
                    $content .= '<a href="'.url("role/".$dataList->id."/edit").'" class="btn btn-xs btn-info" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                }
                if (\Laratrust::can('delete-acl')) {
                    $content .= '<button id="btn_delete" class="btn btn-xs btn-danger hapus-role" val="'.$dataList->id.'"><i class="glyphicon glyphicon-trash"></i> Delete</button>';
                }
                $content .= '</div>';
                return $content;
            })

            // ->rawColumns(['status','action'])
            ->make(true);
            
    }

    public function delete(Request $request)
    {
        $result = DB::transaction(function () use ($request) {
            $id_role = $request->input('id_role');

            // $role=Role::findOrFail($id_role);

            $act=false;
            try {
                // $role->users()->sync([]); // Delete relationship data
                // $role->perms()->sync([]); // Delete relationship data
    
                // $act= $role->forceDelete();
            
                $role=Role::findOrFail($id_role);
                $act=$role->delete();

                DB::commit();
            } catch (\Exception $e) {
                // $role=Role::find($role->id);
                // $act=$role->delete();
                DB::rollback();
            }

            return response()->json($act);

        });

        return $result;
     
    }

}
