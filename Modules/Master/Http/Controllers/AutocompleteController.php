<?php

namespace Modules\Master\Http\Controllers;

use Modules\Master\Models\Kategori;
use Modules\Master\Models\Produk;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AutocompleteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function search($method,Request $request)
    {
        return $this->$method($request);
    }


    public function produk($request)
    {
        $cari = strtoupper($request->get('q'));
        $data = Produk::select('id','nama', 'harga', 'image')
                ->where(DB::raw('upper(nama)'), 'LIKE', "%$cari%")
                ->where('status','Y')
                ->get();
                
        return response()->json($data);
    }
}
