<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Role;
use App\RoleUser;
use App\Permission;
use App\PermissionRole;

use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('User.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        $role = Role::all();
        return view('User.form', compact('user', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = DB::transaction(function () use ($request) {

            $all_data = $request->all(); 

            $data_user = array(
                'name'      => $all_data['name'],
                'username'  => $all_data['username'],
                'email'     => $all_data['email'],
                'password'  => bcrypt($all_data['password']),
            );

            try{
                $user = User::create($data_user);

                if(isset($all_data['role'])){
                    $role = Role::whereIn('id', $all_data['role'])->get();
                    foreach ($role as $key => $value) {
                        $user->attachRole($value);
                    }
                }

                DB::commit();

                $notification = array(
                    'message' => 'Data user berhasil ditambahkan', 
                    'alert-type' => 'success' 
                );
            } catch (\Exception $e) {
                DB::rollback();

                $notification = array(
                    'message' => 'Data user gagal ditambahkan', 
                    'alert-type' => 'error' 
                );
            }

            return Redirect::to('user')->with($notification);

        });

        return $result;
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
        $user = User::where('id',$id)->first();
        $role_user = RoleUser::where('user_id',$id)->get();
        $role = Role::all();
        
        return view('User.form', compact('role','role_user','user'));
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
        $result = DB::transaction(function () use ($request, $id) {
            
            $all_data = $request->all(); 
        
            $data_user = array(
                'name'      => $all_data['name'],
                'username'  => $all_data['username'],
                'email'     => $all_data['email'],
            );
    
            if(!empty($all_data['password']) || $all_data['password'] != null){
                $data_user['password'] = bcrypt($all_data['password']);
            }

            try{
                $update_user= User::findOrFail($id);
                $update_user->update($data_user);

                $role_user = RoleUser::where('user_id', $id)->get();
                foreach ($role_user as $key => $value) {
                    $update_user->detachRole($value);
                }

                $role_user2 = Role::whereIn('id', $all_data['role'])->get();
                foreach ($role_user2 as $key2 => $value2) {
                    $update_user->attachRole($value2);
                }

                DB::commit();

                $notification = array(
                    'message' => 'Data user berhasil diubah', 
                    'alert-type' => 'success' 
                );
            } catch (\Exception $e) {
                DB::rollback();

                $notification = array(
                    'message' => 'Data user gagal diubah', 
                    'alert-type' => 'error' 
                );
            }

            return Redirect::to('user')->with($notification);

        });

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
        $nama_user = \Request::input('nama_user');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = User::where(function ($q) use ($nama_user){
                if(!empty($nama_user)){
                    $nama_user = strtoupper($nama_user );
                    $q->where(DB::raw('upper(name)'), 'LIKE', "%$nama_user%");
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
                $content .= '<a href="'.url("user/".$dataList->id."/edit").'" class="btn btn-xs btn-info" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
            
                $content .= '<button id="btn_delete" class="btn btn-xs btn-danger hapus-user" val="'.$dataList->id.'"><i class="glyphicon glyphicon-trash"></i> Delete</button>';

               
                return $content;
            })

            // ->rawColumns(['status','action'])
            ->make(true);
            
    }

    public function delete(Request $request)
    {
        $result = DB::transaction(function () use ($request) {

            $id_user = $request->input('id_user');
            $act=false;

            try {
                $user=User::findOrFail($id_user);
                $act=$user->delete();

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }

            return response()->json($act);

        });

        return $result;
    }

    public function cekUsername(Request $request)
    {
        // $data='';

        $username = $request->input('username');
        $username2 = strtoupper($username);

        $dataList = User::where(DB::raw('upper(username)'), '=', "$username2")->get();

        $status = (count($dataList) > 0)?'taken':'not_taken';

        // if (count($dataList) > 0) {
        //     $status='';
        //     // $data = array(
        //     //             'status'=>'taken',
        //     //             'type'  =>'username'
        //     //             );
        // }else{
        //     // $data = array(
        //     //             'status'=>'not_taken',
        //     //             'type'  =>'username'
        //     //             );
        // }

        return response()->json($status);

    }

    public function cekEmail(Request $request)
    {
        $email = $request->input('email');
        $email2 = strtoupper($email);

        $dataList = User::where(DB::raw('upper(email)'), '=', "$email2")->get();

        $status = (count($dataList) > 0)?'taken':'not_taken';

        return response()->json($status);
    }
}
