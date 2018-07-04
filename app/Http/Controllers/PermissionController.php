<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use App\Permission;

use Yajra\Datatables\Datatables;


class PermissionController extends Controller
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
        return view('Permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = new Permission;
        return view('Permission.form', compact('permission'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message='';$status='';

        $all_data = $request->all(); 

        $data_permission = array(
                        'name'          =>   $all_data['nama_permission'],
                        'display_name'  =>   $all_data['display_name'],
                        'description'   =>   $all_data['description'],
                    );
        
        //1) Create Admin Role
        $act = Permission::create($data_permission);

        if($act){
            $message = "Data permission berhasil ditambahkan";
            $status = 'success';
        }else{
            $message = "Data permission gagal ditambahkan";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('permission')->with($notification);
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
        $permission = Permission::where('id',$id)->first();
        
        return view('Permission.form', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $all_data = $request->all(); 

        $data_permission = array(
            'name'          =>   $all_data['nama_permission'],
            'display_name'  =>   $all_data['display_name'],
            'description'   =>   $all_data['description'],
        );

        $update_permission = Permission::findOrFail($id);
        $act = $update_permission->update($data_permission);

        if($act){
            $message = "Data permission berhasil diubah";
            $status = 'success';
        }else{
            $message = "Data permission gagal diubah";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('permission')->with($notification);
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

    public function delete(Request $request)
    {
        $id_permission = \Request::input('id_permission');

        $permission = Permission::findOrFail($id_permission);
        $act=false;
        try {
            $permission->roles()->sync([]); // Delete relationship data

            $act= $permission->forceDelete();
        } catch (\Exception $e) {
            $permission = Permission::find($permission->id);
            $act=$permission->delete();
        }

        // if($act){
        //     flash('Data satuan berhasil dihapus')->success();
        // }else{
        //     flash('Data satuan gagal dihapus')->error();
        // }
    
        return response()->json($act);
    }

    public function loadData(){
        $nama_permission = \Request::input('nama_permission');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Permission::where(function ($q) use ($nama_permission){
                if(!empty($nama_permission)){
                    $nama_permission = strtoupper($nama_permission );
                    $q->where(DB::raw('upper(name)'), 'LIKE', "%$nama_permission%");
                } 
            })
            ->orderby('id','desc')
            ->get();

        return Datatables::of($dataList)
            ->addColumn('nomor',function($dataList){
                return $GLOBALS['nomor']++;
            })  
          
            ->addColumn('action', function ($dataList) {
                $content = '';
                $content .= '<a href="'.url("permission/".$dataList->id."/edit").'" class="btn btn-xs btn-info" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            
                // $content .= '<a href="'.url("role/destroy/".$dataList->id).'" class="btn btn-xs btn-danger hapus-bahan" target=""><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                $content .= '<button id="btn_delete" class="btn btn-xs btn-danger hapus-permission" val="'.$dataList->id.'"><i class="glyphicon glyphicon-trash"></i> Delete</button>';

               
                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }
}
