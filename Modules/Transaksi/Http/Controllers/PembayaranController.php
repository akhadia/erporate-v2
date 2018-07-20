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
        // $this->middleware('permission:read-pembayaran|create-pembayaran|update-pembayaran|delete-pembayaran');
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
    public function create($id)
    {
        $pembayaran = new Pembayaran;
        $pesanan = Pesanan::find($id);
        $detailPesanan = DetailPesanan::where('id_pesanan',$id)->get();
        
        return view('transaksi::Pembayaran.form-add-pembayaran',compact('pembayaran','pesanan','detailPesanan'));    
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $all_data = $request->all();

        dd(    $all_data );
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('transaksi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('transaksi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
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
            // if(!empty($status) && $status!='All'){
            //     $status = strtoupper($status );
            //     $q->where(DB::raw('upper(status)'), 'LIKE', "%$status%");
            // } 
            // if(auth()->user()->hasrole('pelayan')){
            //     $q->where('user_input', $id_user);
            // }
            
            })
        ->where('pesanan.status','N')
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
                $content = '';
                if(isset($dataList->id_pembayaran)){
                // $content .= '<a href="'.url("pemesanan/viewdetailpemesanan/".$dataList->id).'" class="btn btn-xs btn-primary" target=""><i class="glyphicon glyphicon-edit"></i> Detail</a>';
                }else{
                    $content .= '<a href="'.url("transaksi/pembayaran/create/".$dataList->id).'" class="btn btn-xs btn-success" target=""><i class="glyphicon glyphicon-edit"></i> Bayar</a>';
                }
                // $pembayaran = Pembayaran::where('id_pemesanan', $dataList->id)->first();
                // $content .= '<a href="'.url("pembayaran/viewdetailpembayaran/".$dataList->id).'" class="btn btn-xs btn-primary" target=""><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                // if(!empty($pembayaran)){
                //     $content .= '<a href="'.url("pemesanan/pemesananselesai/".$dataList->id).'" class="btn btn-xs btn-danger pemesanan-selesai" target=""><i class="glyphicon glyphicon-ok"></i> Tutup</a>';
                // }
                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }
}
