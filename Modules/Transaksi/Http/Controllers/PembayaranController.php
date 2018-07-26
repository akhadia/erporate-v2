<?php

namespace Modules\Transaksi\Http\Controllers;

use Modules\Master\Models\Meja;
use Modules\Transaksi\Models\Pesanan;
use Modules\Transaksi\Models\DetailPesanan;
use Modules\Transaksi\Models\Pembayaran;

use Carbon\Carbon;
use Laratrust;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class PembayaranController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:read-pembayaran|create-pembayaran|update-pembayaran|delete-pembayaran');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('transaksi::Pembayaran.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    public function addEditPembayaran($id_pesanan)
    {
        $status = 'update';
        $pesanan = Pesanan::find($id_pesanan);
        $detailPesanan = DetailPesanan::where('id_pesanan',$id_pesanan)->get();
        $pembayaran = Pembayaran::where('id_pesanan',$id_pesanan)->first();

        if(!$pembayaran){
            $pembayaran = new Pembayaran;
            $status = 'create';
        }
    
        return view('transaksi::Pembayaran.form-add-pembayaran',compact('pembayaran','pesanan','detailPesanan','status'));    
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */

    public function store(Request $request)
    {
        $all_data = $request->all();

        $tgl_pembayaran = date("Y-m-d H:i:s");

        $data_pembayaran= array(
                                "id_pesanan" => $all_data['id_pesanan'],
                                "tgl_bayar" => $tgl_pembayaran,
                                "total_tagihan" =>  $all_data['total_tagihan'],
                                "jumlah_bayar" => $all_data['jumlah_bayar'],
                                "kembalian" => $all_data['kembalian'],
                                "keterangan" => '',
                            );

        DB::beginTransaction();
        try {
            $pembayaran=Pembayaran::create($data_pembayaran);

        }catch (Exception $e) {
            DB::rollBack();

            $response = array(
                'status'    => 'Error',
                'message'   => 'Pembayaran no pesanan '. $all_data['no_pesanan'].' gagal ditambahkan', 
                'from'      => 'store', 
            );
            
            return response()->json($response);
        }
        DB::commit();

        $response = array(
            'status'    => 'Success',
            'message'   => 'Pembayaran no pesanan '. $all_data['no_pesanan'].' berhasil ditambahkan', 
            'from'      => 'store',
        );
        
        return response()->json($response);

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $all_data = $request->all();

        $data_pembayaran= array(
                                "jumlah_bayar" => $all_data['jumlah_bayar'],
                                "kembalian" => $all_data['kembalian'],
                                "keterangan" => '',
                            );
        
        DB::beginTransaction();
        try {
            if(!empty($all_data['id_pembayaran']) && $all_data['id_pembayaran'] != null){
                $update_pembayaran= Pembayaran::findOrFail($all_data['id_pembayaran']);
                $update_pembayaran->update($data_pembayaran);
            }
        }catch (Exception $e) {
            DB::rollBack();

            $response = array(
                'status'    => 'Error',
                'message'   => 'Pembayaran no pesanan '. $all_data['no_pesanan'].' gagal diubah', 
                'from'      => 'store', 
            );
            
            return response()->json($response);
        }
        DB::commit();

        $response = array(
            'status'    => 'Success',
            'message'   => 'Pembayaran no pesanan '. $all_data['no_pesanan'].' berhasil diubah', 
            'from'      => 'store', 
        );
        
        return response()->json($response);
    }

    public function loadData(){
        $id_user = auth()->user()->id;
        $no_pesanan = \Request::input('no_pesanan');
        $date_from = \Request::input('date_from');
        $date_to = \Request::input('date_to');
        $status = \Request::input('status');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $selected_field="pesanan.*, pb.id as id_pembayaran";
        $dataList = Pesanan::select(\DB::raw($selected_field))
            ->leftjoin('pembayaran as pb', 'pesanan.id','=','pb.id_pesanan')

            ->where(function ($q) use ($no_pesanan, $date_from, $date_to ,$status , $id_user){
                if(!empty($no_pesanan)){
                    $no_pesanan = strtoupper($no_pesanan );
                    $q->where(DB::raw('upper(pesanan.no_pesanan)'), 'LIKE', "%$no_pesanan%");
                } 
                if(!empty($date_from)){
                    $date_from = Carbon::createFromFormat('d-m-Y', $date_from )->format('Y-m-d');
                    $q->where('pesanan.tgl_pesanan', '>=', $date_from." 00:00:00");
                }
                if(!empty($date_to)){
                    $date_to = Carbon::createFromFormat('d-m-Y', $date_to )->format('Y-m-d');
                    $q->where('pesanan.tgl_pesanan', '<=', $date_to." 23:59:59");
                }
                if(!empty($status) && $status!='All'){
                    $status = strtoupper($status );
                    if($status=='Y'){
                        $q->whereNotNull('pb.id');
                    }else{
                        $q->whereNull('pb.id');
                    }
                } 
                // if(auth()->user()->hasrole('pelayan')){
                //     $q->where('pb.user_input', $id_user);
                // }
            
            })
        // ->where('pesanan.status','N')
        ->orderby('pesanan.id','desc')
        ->get();

        // print_r($dataList);die;

        return Datatables::of($dataList)
            ->addColumn('nomor',function($dataList){
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
                if(isset($dataList->id_pembayaran)){
                    $content .= '<span class="label label-success">Sudah Bayar</span>';
                }else{
                    $content .= '<span class="label label-warning">Belum Bayar</span>';
                }
                return $content;
            })      
          
            ->addColumn('action', function ($dataList) {
                $content = '<div class="btn-toolbar">';

                if(isset($dataList->id_pembayaran)){
                    if (Laratrust::can('update-pembayaran')) {
                        $content .= '<a href="'.url("transaksi/pembayaran/addeditpembayaran/".$dataList->id).'" class="btn btn-xs btn-primary" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                    }
                    $content .= '<button id="btn-cetak" val="'.$dataList->id.'" class="btn btn-xs btn-info btn-cetak" target=""><i class="glyphicon glyphicon-print"></i> Cetak</button>';
                }else{
                    if (Laratrust::can('create-pembayaran')) {
                        $content .= '<a href="'.url("transaksi/pembayaran/addeditpembayaran/".$dataList->id).'" class="btn btn-xs btn-success" target=""><i class="glyphicon glyphicon-ok"></i> Bayar</a>';
                    }
                }
                // $pembayaran = Pembayaran::where('id_pemesanan', $dataList->id)->first();
                // $content .= '<a href="'.url("pembayaran/viewdetailpembayaran/".$dataList->id).'" class="btn btn-xs btn-primary" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                // if(!empty($pembayaran)){
                //     $content .= '<a href="'.url("pemesanan/pemesananselesai/".$dataList->id).'" class="btn btn-xs btn-danger pemesanan-selesai" target=""><i class="glyphicon glyphicon-ok"></i> Tutup</a>';
                // }
                $content .= '</div>';
                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }


    public function cetakNota(Request $request)
    {
        $id_pesanan = $request->id_pesanan;

        $pesanan = Pesanan::find($id_pesanan);
        $pembayaran = Pembayaran::where('id_pesanan',$id_pesanan)->first();
        $detailPesanan = DetailPesanan::where('id_pesanan',$id_pesanan)->get();
            
        return view('transaksi::Pembayaran.cetak-nota-pembayaran',compact('pembayaran','pesanan','detailPesanan'));
        
    }
}
