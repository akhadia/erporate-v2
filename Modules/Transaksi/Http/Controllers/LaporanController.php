<?php

namespace Modules\Transaksi\Http\Controllers;

use Modules\Master\Models\Meja;
use Modules\Transaksi\Models\Pesanan;
use Modules\Transaksi\Models\DetailPesanan;
use Modules\Transaksi\Models\Pembayaran;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Carbon\Carbon;

class LaporanController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:read-laporan|create-laporan|update-laporan|delete-laporan');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('transaksi::Laporan.index');
    }

    public function createLaporanPesanan(Request $request)
    {
        // print_r('oke');die;
        $all_data = $request->all();
        $status = $all_data['status'];
        $date_from = $all_data['date_from'];
        $date_to = $all_data['date_to'];
        $user_id = auth()->user()->id;
        $user_name = auth()->user()->name;

        $selected_field='pesanan.*, m.no_meja';

        $pesanan = Pesanan::select(\DB::raw($selected_field))
                    ->leftjoin('meja as m','m.id','=','pesanan.id_meja')
                    ->where(function ($q) use ($date_from, $date_to, $user_id ){
                        if(!empty($date_from)){
                            $date_from2 = Carbon::createFromFormat('d-m-Y', $date_from)->format('Y-m-d');
                            $q->where('pesanan.tgl_pesanan', '>=', $date_from2);
                        }
                        if(!empty($date_to)){
                            $date_to2 = Carbon::createFromFormat('d-m-Y', $date_to)->format('Y-m-d');
                            $q->where('pesanan.tgl_pesanan', '<=', $date_to2);
                        }
                        if(!empty($user_id)){
                            $q->where('pesanan.user_input', $user_id);
                        }
                    })
                    // ->orderby('pemesanan.id','asc')
                    ->get();
                    // print_r($date_from);die;
   
        if($status == 'display'){
            return response()->json($pesanan);
        }else{
            // print_r($pesanan);die;
            return view('transaksi::Laporan.cetak-laporan-pesanan',compact('pesanan','date_from','date_to','user_name'));
        }
    }

}
