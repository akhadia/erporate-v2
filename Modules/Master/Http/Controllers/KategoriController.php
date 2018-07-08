<?php

namespace Modules\Master\Http\Controllers;

use Modules\Master\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Laratrust;
use Yajra\Datatables\Datatables;

class KategoriController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:read-kategori|create-kategori|update-kategori|delete-kategori');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('master::Kategori.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $kategori = new Kategori;
        return view('master::Kategori.form', compact('kategori'));    
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $all_data = $request->all(); 

        $data_kategori = array('nama' => $all_data['nama_kategori']);

        $act=Kategori::create($data_kategori);

        if($act){
            $message = "Data kategori berhasil ditambahkan";
            $status = 'success';
        }else{
            $message = "Data kategori gagal ditambahkan";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('/master/kategori')->with($notification);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        // return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $id_kategori = \Request::input('id_kategori');

        $kategori = Kategori::where('id',$id_kategori)->first();
        return view('master::Kategori.form', compact('kategori'));    
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $all_data = $request->all(); 

        $data_kategori = array(
                'nama' =>  $all_data['nama_kategori'],
                // 'aktif' =>  $all_data['status'],
            );

        $update_kategori = Kategori::findOrFail($id);
        $act = $update_kategori->update($data_kategori);

        if($act){
            $message = "Data kategori berhasil diubah";
            $status = 'success';
        }else{
            $message = "Data kategori gagal dibah";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('/master/kategori')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }


    public function delete(){
        $act=false;
        $id_kategori = \Request::input('id_kategori');

        $kategori = Kategori::findOrFail($id_kategori);

        DB::beginTransaction();
        try {
            $act= $kategori->forceDelete();
        }catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    
        return response()->json($act);
    }

    public function loadData(){
        $nama_kategori = \Request::input('nama_kategori');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Kategori::where(function ($q) use ($nama_kategori){
                if(!empty($nama_kategori)){
                    $nama_kategori = strtoupper($nama_kategori );
                    $q->where(DB::raw('upper(nama)'), 'LIKE', "%$nama_kategori%");
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
                // $content .= '<a href="'.url("kategori/".$dataList->id."/edit").'" class="btn btn-xs btn-info" target="modal"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                if (Laratrust::can('update-kategori')) {
                    $content .= '<button id="btn-edit" class="btn btn-xs btn-info edit-kategori" val="'.$dataList->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</button>';
                }
                if (Laratrust::can('delete-kategori')) {
                    $content .= '<button id="btn_delete" class="btn btn-xs btn-danger hapus-kategori" val="'.$dataList->id.'"><i class="glyphicon glyphicon-trash"></i> Delete</button>';
                }
                
                

                // $content .= '<a href="'.url("role/destroy/".$dataList->id).'" class="btn btn-xs btn-danger hapus-bahan" target=""><i class="glyphicon glyphicon-trash"></i> Delete</a>';

               
                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }
}
