<?php

namespace Modules\Master\Http\Controllers;

use Modules\Master\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use Laratrust;
use Yajra\Datatables\Datatables;

class MejaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('permission:read-meja|create-meja|update-meja|delete-meja');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('master::Meja.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $meja = new Meja;
        return view('master::Meja.form', compact('meja'));    
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $all_data = $request->all(); 

        $data_meja = array('no_meja' => $all_data['no_meja']);

        $act=Meja::create($data_meja);

        if($act){
            $message = "Data meja berhasil ditambahkan";
            $status = 'success';
        }else{
            $message = "Data meja gagal ditambahkan";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('/master/meja')->with($notification);
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
        $id_meja = \Request::input('id_meja');

        $meja = Meja::where('id',$id_meja)->first();
        return view('master::Meja.form', compact('meja'));    
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $all_data = $request->all(); 

        $data_meja = array(
                'no_meja' =>  $all_data['no_meja'],
                // 'aktif' =>  $all_data['status'],
            );

        $update_meja = Meja::findOrFail($id);
        $act = $update_meja->update($data_meja);

        if($act){
            $message = "Data meja berhasil diubah";
            $status = 'success';
        }else{
            $message = "Data meja gagal dibah";
            $status = 'error';
        }

        $notification = array(
            'message' => $message, 
            'alert-type' => $status 
        );
        
        return Redirect::to('/master/meja')->with($notification);
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
        $id_meja = \Request::input('id_meja');

        $meja = Meja::findOrFail($id_meja);

        DB::beginTransaction();
        try {
            $act= $meja->forceDelete();
        }catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
    
        return response()->json($act);
    }

    public function loadData(){
        $no_meja = \Request::input('no_meja');

        $GLOBALS['nomor']=\Request::input('start',1)+1;

        $dataList = Meja::where(function ($q) use ($no_meja){
                if(!empty($no_meja)){
                    $no_meja = strtoupper($no_meja );
                    $q->where(DB::raw('upper(no_meja)'), 'LIKE', "%$no_meja%");
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
                if (Laratrust::can('update-meja')) {
                    $content .= '<button id="btn-edit" class="btn btn-xs btn-info edit-meja" val="'.$dataList->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</button>';
                }
                if (Laratrust::can('delete-meja')) {
                    $content .= '<button id="btn_delete" class="btn btn-xs btn-danger hapus-meja" val="'.$dataList->id.'"><i class="glyphicon glyphicon-trash"></i> Delete</button>';
                }
                
                // $content .= '<a href="'.url("role/destroy/".$dataList->id).'" class="btn btn-xs btn-danger hapus-bahan" target=""><i class="glyphicon glyphicon-trash"></i> Delete</a>';

                return $content;
            })

            ->rawColumns(['status','action'])
            ->make(true);
            
    }
}
