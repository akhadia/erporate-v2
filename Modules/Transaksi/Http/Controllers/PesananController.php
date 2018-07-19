<?php

namespace Modules\Transaksi\Http\Controllers;

use Modules\Master\Models\Meja;
use Modules\Transaksi\Models\Pesanan;
use Modules\Transaksi\Models\DetailPesanan;

use Carbon\Carbon;
use Laratrust;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;


class PesananController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:read-pesanan|create-pesanan|update-pesanan|delete-pesanan');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('transaksi::Pesanan.index');
    }

    public function create(){
        $pesanan = new Pesanan;
        $meja = Meja::pluck('no_meja', 'id');
        $meja->prepend('Pilih Meja','');
        return view('transaksi::Pesanan.form-add-pesanan',compact('pesanan','meja'));
    }

    public function store(Request $request)
    {
        $all_data = $request->all();

        // dd(    $all_data );

        $tgl_pesanan= date("Y-m-d H:i:s");

        $data_pesanan = array(
                                "no_pesanan" => $this->noUrutPesanan(),
                                "id_meja" => $all_data['meja'],
                                "tgl_pesanan" =>   $tgl_pesanan,
                                "keterangan" => $all_data['description'],
                                "total" => $all_data['all_total_produk'],
                            );
                            
        DB::beginTransaction();
        try {
            $pesanan=Pesanan::create($data_pesanan);
            $id_pesanan = $pesanan->id;

            if($pesanan){
                $this->addDetailPesanan($all_data,$id_pesanan);
            }

        }catch (Exception $e) {
            DB::rollBack();

            $notification = array(
                'message' => 'Data pesanan gagal ditambahkan', 
                'alert-type' => 'error', 
            );
            
            return \Redirect::to('/transaksi/pesanan')->with($notification);
        }
        DB::commit();

        $notification = array(
            'message' => 'Data pesanan berhasil ditambahkan', 
            'alert-type' => 'success', 
        );
        
        return \Redirect::to('/transaksi/pesanan')->with($notification);
    }

    function addDetailPesanan($all_data,$id_pesanan)
    {
        //cek delete
        if(isset($all_data["details_deleted"]) && !empty($all_data["details_deleted"]) )  {
            $this->deleteDetailPesanan($all_data);
        }

        //cek edit dan kalau ada data akan di edit
        if(isset($all_data["old_id_ps_produk_edit"]) && !empty($all_data["old_id_ps_produk_edit"])){
            $jumlah_edit    = count($all_data["old_id_ps_produk_edit"]);
            if($jumlah_edit > 0){
                $this->editDetailPesanan($all_data);
            }
        }

        //add data detail pesanan
        if(isset($all_data["id_produk"]) && !empty($all_data["id_produk"]) )  {
            $this->insertDetailPesanan($all_data,$id_pesanan);
        }
    }

    public function loadData(){

        $id_user = auth()->user()->id;
        $no_pesanan = \Request::input('no_pesanan');
        $date_from = \Request::input('date_from');
        $date_to = \Request::input('date_to');
        $status = \Request::input('status');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Pesanan::where(function ($q) use ($no_pesanan, $date_from, $date_to ,$status , $id_user){
                if(!empty($no_pesanan)){
                    $no_pesanan = strtoupper($no_pesanan );
                    $q->where(DB::raw('upper(no_pesanan)'), 'LIKE', "%$no_pesanan%");
                } 
                if(!empty($date_from)){
                    $date_from = Carbon::createFromFormat('d-m-Y', $date_from )->format('Y-m-d');
                    $q->where('tgl_pesanan', '>=', $date_from." 00:00:00");
                }
                if(!empty($date_to)){
                    $date_to = Carbon::createFromFormat('d-m-Y', $date_to )->format('Y-m-d');
                    $q->where('tgl_pesanan', '<=', $date_to." 23:59:59");
                }
                if(!empty($status) && $status!='All'){
                    $status = strtoupper($status );
                    $q->where(DB::raw('upper(status)'), 'LIKE', "%$status%");
                } 
                if(auth()->user()->hasrole('pelayan')){
                    $q->where('user_input', $id_user);
                }
                
            })
            // ->where('status','N')
            ->orderby('pesanan.id','desc')
            ->get();

        return Datatables::of($dataList)
            ->addColumn('nomor',function($kategori){
                return $GLOBALS['nomor']++;
            })
            ->addColumn('tgl_pesanan',function($dataList){
                  if(isset($dataList->tgl_pesanan)){
                    $tgl = Carbon::createFromFormat('Y-m-d', $dataList->tgl_pesanan);
                    return $tgl->format('d-m-Y');
                  }else{
                    return null;
                  }
            })

            ->addColumn('meja',function($dataList){
                  if(isset($dataList->meja->no_meja)){
                    return $dataList->meja->no_meja;
                  }else{
                    return null;
                  }
            })

            ->addColumn('status', function ($dataList) {
                $content = '';
                if($dataList->status == 'N'){
                     $content .= '<span class="label label-warning">Selesai</span>';
                }else{
                    $content .= '<span class="label label-success">Aktif</span>';
                }
                return $content;
            })    
          
            ->addColumn('action', function ($dataList) {
                $content = '';
                if (Laratrust::can('update-pesanan') && $dataList->status == 'Y') {
                    $content .= '<a href="'.url("transaksi/pesanan/edit/".$dataList->id).'" class="btn btn-xs btn-primary" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                }
                if (Laratrust::can('read-pesanan') && $dataList->status == 'N') {
                    $content .= '<a href="'.url("transaksi/pesanan/edit/".$dataList->id).'" class="btn btn-xs btn-info" target=""><i class="glyphicon glyphicon-list-alt"></i> Detail</a>';
                }
                // $content .= '<a href="'.url("pesanan/edit/".$dataList->id).'" class="btn btn-xs btn-primary" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                if(auth()->user()->hasrole('kasir') && $dataList->status == 'Y'){
                    //  $content .= '<a href="'.url("pesanan/selesaipesanan/".$dataList->id).'" class="btn btn-xs btn-warning pesanan-selesai" target=""><i class="glyphicon glyphicon-ok"></i> Selesai</a>';
                    // if (Laratrust::can('update-produk')) {
                    //     $content .= '<button id="btn-edit" class="btn btn-xs btn-info edit-produk" val="'.$dataList->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</button>';
                    // }
                    $content .= '<button id="btn-selesai" class="btn btn-xs btn-success pesanan-selesai" val="'.$dataList->id.'"><i class="glyphicon glyphicon-ok"></i> Selesai</button>';

                }

                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
    }

    //Fungsi insert data ke tabel detail_pesanan
    function insertDetailPesanan($all_data,$id_pesanan){

        $jumlah = count($all_data["id_produk"]);
        for($i=0;$i<$jumlah;$i++)  {
            if($all_data["id_produk"][$i] !== '' && !empty($all_data["id_produk"][$i])){
                $data_detailPesanan = array(
                    "id_pesanan" => $id_pesanan,
                    "id_produk" => $all_data["id_produk"][$i],
                    "qty_pesanan" => $all_data["produk_qty"][$i],
                    "harga" => $all_data["produk_harga"][$i],
                    "subtotal" => $all_data["nilai_subtotal"][$i],
                );

                $detailPesanan=DetailPesanan::create($data_detailPesanan);
            }
        }
    }

    //Fungsi edit pemesanan detail 
    function editDetailPesanan($all_data){
        $jumlah = count($all_data["old_id_ps_produk_edit"]);

        for($i=0;$i<$jumlah;$i++)  {

            if($all_data["old_id_ps_produk_edit"][$i] !== '' && !empty($all_data["old_id_ps_produk_edit"][$i])){
              
                $data_detailPesanan = array(
                    // "id_pesanan" => $id_pesanan,
                    "id_produk" => $all_data["id_produk_edit"][$i],
                    "qty_pesanan" => $all_data["produk_qty_edit"][$i],
                    "harga" => $all_data["produk_harga_edit"][$i],
                    "subtotal" => $all_data["nilai_subtotal_edit"][$i],
                );

                //update detil permintaan sesuai id nya
                $update_detailPesanan = DetailPesanan::findOrFail($all_data["old_id_ps_produk_edit"][$i]);
                $update_detailPesanan->update($data_detailPesanan);
            }
        }
    }

    //Fungsi delete pesanan detail 
    public function deleteDetailPesanan($all_data)
    {
        $jumlah = count($all_data["details_deleted"]);

        for($i=0;$i<$jumlah;$i++)  {
            //delete sesuai id
            $detailPesanan=DetailPesanan::find($all_data["details_deleted"][$i]);
    
            try {
                $act=$detailPesanan->forceDelete();
            } catch (\Exception $e) {
                $detailPesanan = DetailPesanan::find($detailPesanan->id);
                $detailPesanan->delete();
            }
        }
    }

    public function edit(Request $request, $id_pesanan)
    {
        $pesanan = Pesanan::find($id_pesanan);
        $detailPesanan = DetailPesanan::where('id_pesanan',$id_pesanan)->get();
        $meja = Meja::pluck('no_meja', 'id');
        $meja->prepend('Pilih Meja','');

        return view('transaksi::Pesanan.form-add-pesanan',compact('pesanan','detailPesanan','meja'));
    }

    //Fungsi tambah & edit data pesanan detail 
    public function update(Request $request, $id_pesanan)
    {
        $act = false;
        $all_data = $request->all();

        $data_pesanan = array(
            // "no_pesanan" => $this->noUrutPesanan(),
            "id_meja" => $all_data['meja'],
            // "tgl_pesanan" =>   $tgl_pesanan,
            "keterangan" => $all_data['description'],
            "total" => $all_data['all_total_produk'],
        );

      
        DB::beginTransaction();
        try {
            if(!empty($id_pesanan) && $id_pesanan != null){
                $update_pesanan = Pesanan::findOrFail($id_pesanan);
                $update_pesanan->update($data_pesanan);
            }

            $this->addDetailPesanan($all_data,$id_pesanan);
            
        }catch (Exception $e) {
            DB::rollBack();

            $notification = array(
                'message' => 'Data pesanan gagal diubah', 
                'alert-type' => 'error',
            );
            
            return \Redirect::to('/transaksi/pesanan')->with($notification);
        }
        DB::commit();

        $notification = array(
            'message' => 'Data pesanan berhasil diubah', 
            'alert-type' => 'success',
        );
        
        return \Redirect::to('/transaksi/pesanan')->with($notification);
    }

    public function pesananSelesai(){
        $act=false;
        $id_pesanan = \Request::input('id_pesanan');

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::findOrFail($id_pesanan);

            if (isset($pesanan) && !empty($pesanan)){
                $pesanan->status = 'N';
                $act = $pesanan->save();
            }
        }catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    
        return response()->json($act);
    }

    //Fungsi untuk auto generate nomor pesanan
    private function noUrutPesanan(){
        $temp = Carbon::createFromFormat('Y-m-d', date("Y-m-d"));
        $tgl_no_urut = $temp->format('dmY');

        $initial_no_seri = 'ERP';

        $base_no_seri = strtoupper($initial_no_seri);
        $base_no_seri = $base_no_seri.$tgl_no_urut."-";

        $last_no_data = Pesanan::where(DB::raw('upper(no_pesanan)'), 'LIKE', "%$base_no_seri%")->orderBy('id', 'desc')->first();

        if(!empty($last_no_data->no_pesanan)){
            $last_no = $last_no_data->no_pesanan;
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




//====## END ##====//
}
