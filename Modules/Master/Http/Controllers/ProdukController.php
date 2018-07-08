<?php

namespace Modules\Master\Http\Controllers;

use Modules\Master\Models\Kategori;
use Modules\Master\Models\Produk;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Laratrust;
use Yajra\Datatables\Datatables;

class ProdukController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        // $this->middleware('permission:read-produk|create-produk|update-produk|delete-produk');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('master::Produk.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $kategori = Kategori::pluck('nama', 'id');
        $kategori->prepend('Pilih Kategori','');

        $produk = new Produk;

        return view('master::Produk.form', compact('produk','kategori'));    
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $all_data = $request->all(); 

        $data_produk = array(
            'id_kategori'   => $all_data['kategori'],
            'nama'          => $all_data['nama_prod'],
            'harga'         => $all_data['harga_prod'],
            'deskripsi'     => $all_data['deskripsi_prod'],
        );

        if(isset($all_data['image_prod'])){
            $file       = $request->file('image_prod');
            $file_name   = $file->getClientOriginalName();
            $request->file('image_prod')->move("image/", $file_name);

            $data_produk['image'] =  $file_name;
        }

        $act=Produk::create($data_produk);

        if($act){
            $message = "Data produk berhasil ditambahkan";
            $status = 'success';
        }else{
            $message = "Data produk gagal ditambahkan";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('/master/produk')->with($notification);
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $id_produk = \Request::input('id_produk');
        $kategori = Kategori::pluck('nama', 'id');
        $kategori->prepend('Pilih Kategori','');

        $produk = Produk::where('id',$id_produk)->first();
        return view('master::Produk.form', compact('produk','kategori'));    
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $all_data = $request->all(); 

        $data_produk = array(
            'id_kategori'   => $all_data['kategori'],
            'nama'          => $all_data['nama_prod'],
            'harga'         => $all_data['harga_prod'],
            'deskripsi'     => $all_data['deskripsi_prod'],
        );

        if(isset($all_data['image_prod'])){
            $file       = $request->file('image_prod');
            $file_name   = $file->getClientOriginalName();
            $request->file('image_prod')->move("image/", $file_name);

            $data_produk['image'] =  $file_name;
        }

        // dd($data_produk);

        $update_produk = Produk::findOrFail($id);
        $act = $update_produk->update($data_produk);

        if($act){
            $message = "Data produk berhasil diubah";
            $status = 'success';
        }else{
            $message = "Data produk gagal dibah";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('/master/produk')->with($notification);
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
        $id_produk = \Request::input('id_produk');

        $produk = Produk::findOrFail($id_produk);

        DB::beginTransaction();
        try {
            $act= $produk->forceDelete();
        }catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    
        return response()->json($act);
    }

    public function loadData(){
        $nama_produk = \Request::input('nama_produk');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Produk::where(function ($q) use ($nama_produk){
                if(!empty($nama_produk)){
                    $nama_produk = strtoupper($nama_produk );
                    $q->where(DB::raw('upper(nama)'), 'LIKE', "%$nama_produk%");
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
                // $content .= '<a href="'.url("produk/".$dataList->id."/edit").'" class="btn btn-xs btn-info" target="modal"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                if (Laratrust::can('update-produk')) {
                    $content .= '<button id="btn-edit" class="btn btn-xs btn-info edit-produk" val="'.$dataList->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</button>';
                }
                if (Laratrust::can('delete-produk')) {
                    $content .= '<button id="btn_delete" class="btn btn-xs btn-danger hapus-produk" val="'.$dataList->id.'"><i class="glyphicon glyphicon-trash"></i> Delete</button>';
                }
                
                

                // $content .= '<a href="'.url("role/destroy/".$dataList->id).'" class="btn btn-xs btn-danger hapus-bahan" target=""><i class="glyphicon glyphicon-trash"></i> Delete</a>';

               
                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }
}
