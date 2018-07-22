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
use Carbon\Carbon;
use File;

class ProdukController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:read-produk|create-produk|update-produk|delete-produk');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $kategori = Kategori::pluck('nama', 'id');
        $kategori->prepend('All Kategori','All');
        return view('master::Produk.index', compact('kategori'));    
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
            // $file_name   = $file->getClientOriginalName();
            $new_file_name = $this->noUrutGambar();

            $request->file('image_prod')->move("images/", $new_file_name);

            $data_produk['image'] =  $new_file_name;
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
            'status'        => $all_data['status'],
        );

        if(isset($all_data['image_prod'])){
            $file       = $request->file('image_prod');
            // $file_name   = $file->getClientOriginalName();
            $new_file_name = $this->noUrutGambar();
            $request->file('image_prod')->move("images/", $new_file_name);

            $data_produk['image'] =  $new_file_name;
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
        $img_produk = $produk->image;
        $destinationPath = 'images/'.$img_produk;

        DB::beginTransaction();
        try {
            if(isset($img_produk) && !empty($img_produk)){
                File::delete($destinationPath);
            }
            $act= $produk->forceDelete();
        }catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    
        return response()->json($act);
    }

    public function loadData(){
        $nama_produk = \Request::input('nama_produk');
        $status = \Request::input('status');
        $id_kategori = \Request::input('kategori');
        
        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Produk::where(function ($q) use ($nama_produk, $status, $id_kategori){
                if(!empty($nama_produk)){
                    $nama_produk = strtoupper($nama_produk );
                    $q->where(DB::raw('upper(nama)'), 'LIKE', "%$nama_produk%");
                } 
                if(!empty($status) && $status!='All'){
                    $status = strtoupper($status );
                    $q->where(DB::raw('upper(status)'), '=', $status);
                } 
                if(!empty($id_kategori) && $id_kategori!='All'){
                    $q->where('id_kategori','=',$id_kategori);                
                } 
            })
            ->orderby('id','desc')
            ->get();

        return Datatables::of($dataList)
            ->addColumn('nomor',function($dataList){
                return $GLOBALS['nomor']++;
            })  

            ->addColumn('gambar',function($dataList){
                if(!empty($dataList->image) && $dataList->image != null){
                    return url("/images/{$dataList->image}");
                }else{
                  return null;
                }
            })

            ->addColumn('kategori',function($dataList){
                if(isset($dataList->kategori->nama)){
                  return $dataList->kategori->nama;
                }else{
                  return null;
                }
            })

            ->addColumn('status', function ($dataList) {
                $content = '';
                if($dataList->status == 'Y'){
                     $content .= '<span class="label label-success">Ready</span>';
                }else{
                    $content .= '<span class="label label-warning">Unready</span>';
                }
                return $content;
            })   
          
            ->addColumn('action', function ($dataList) {
                $content = '<div class="btn-toolbar">';
                // $content .= '<a href="'.url("produk/".$dataList->id."/edit").'" class="btn btn-xs btn-info" target="modal"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                if (Laratrust::can('update-produk')) {
                    $content .= '<button id="btn-edit" class="btn btn-xs btn-info edit-produk" val="'.$dataList->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</button>';
                }
                if (Laratrust::can('delete-produk')) {
                    $content .= '<button id="btn_delete" class="btn btn-xs btn-danger hapus-produk" val="'.$dataList->id.'"><i class="glyphicon glyphicon-trash"></i> Delete</button>';
                }
                // $content .= '<a href="'.url("role/destroy/".$dataList->id).'" class="btn btn-xs btn-danger hapus-bahan" target=""><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                $content .= '</div">';

                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }

    public function popupProduk(){
        $nomer = \Request::input('nomer');
        return view('master::Produk.popup-produk', compact('nomer'));  
    }

    public function loadDataPopup(){
        $nama_produk = \Request::input('nama_produk');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Produk::where(function ($q) use ($nama_produk){
                if(!empty($nama_produk)){
                    $nama_produk = strtoupper($nama_produk );
                    $q->where(DB::raw('upper(nama)'), 'LIKE', "%$nama_produk%");
                }
            })
            ->where('status','Y')
            ->get();

        return Datatables::of($dataList)
            ->addColumn('nomor',function($dataList){
                return $GLOBALS['nomor']++;
            })

            ->addColumn('gambar',function($dataList){
                if(!empty($dataList->image) && $dataList->image != null){
                    return url("/images/{$dataList->image}");
                }else{
                  return null;
                }
            })

            ->addColumn('status', function ($dataList) {
                $content = '';
                if($dataList->status == 'Y'){
                     $content .= '<span class="label label-success">Ready</span>';
                }else{
                    $content .= '<span class="label label-warning">Unready</span>';
                }
                return $content;
            })    
          
            ->addColumn('action', function ($dataList) {
                $content = '';
                $content = '<a href="#" class="btn btn-xs btn-primary select-produk-from-popup" data-id="'.$dataList->id.'"><i class="glyphicon glyphicon-ok"></i> Add</a>';

                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }

    function getProduk(Request $request, $id){
        $produk=Produk::where('id',$id)->first();
        return response()->json($produk);
    }

     //Fungsi untuk auto generate nomor gambar
     private function noUrutGambar(){
        $temp = Carbon::createFromFormat('Y-m-d', date("Y-m-d"));
        $tgl_no_urut = $temp->format('dmY');

        $initial_no_seri = 'IMG';

        $base_no_seri = strtoupper($initial_no_seri);
        $base_no_seri = $base_no_seri.$tgl_no_urut."-";

        $last_no_data = Produk::where(DB::raw('upper(image)'), 'LIKE', "%$base_no_seri%")->orderBy('id', 'desc')->first();
    
        if(!empty($last_no_data->image)){
            $last_no = $last_no_data->image;
            $int_last_no = substr($last_no, -3);
            $max_lengnth_no = strlen($int_last_no);
            //dd($int_last_no);
            $int_last_no++;
            $next_no = $int_last_no;
            $diff_lengnth_no = $max_lengnth_no - strlen($next_no);
            $char_tambahan = '';
            for ($x = 1; $x <= $diff_lengnth_no; $x++) {
                $char_tambahan .='0';
            }
            $next_no =  $char_tambahan.$next_no;
            $next_no_seri = $base_no_seri.$next_no;
            
        }else{
            $next_no_seri = $base_no_seri.'001';
        }
  
        return $next_no_seri;
    }

     

}