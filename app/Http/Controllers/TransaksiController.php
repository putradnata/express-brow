<?php

namespace App\Http\Controllers;

use App\Transaksi;
use App\Barang;
use App\DetailTransaksi;
use Illuminate\Http\Request;

use Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $transkasi = new Transaksi();

        $selectTransaksi = Transaksi::all();


        return view('admin/transaksi.index',[
            'transaksis' => $selectTransaksi
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $transaksis = new Transaksi();

        $transaksi =  (object) $transaksis->getDefaultValues();

        $produk = new Barang();

        $selectProduk = Barang::all();

        $selectPelanggan = DB::table('pelanggans')->get();

        return view('admin/transaksi.form',[
            'transaksi' => $transaksi,
            'produk' => $selectProduk,
            'produk1' => $selectProduk,
            'pelanggans' => $selectPelanggan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $r = count($request->qtyProduk);

        $transaksi = new Transaksi();
        $detailtransaksi = new DetailTransaksi();

        // dd($request->all());

        $validate = $request->validate([
            'kodeTransaksi'=> 'required',
            'tanggalTransaksi' => 'required',
            'totalTransaksi' => 'required',
            'keteranganTransaksi' => 'required',
            'pelanggan' => 'required'
        ]);

        $data = [
            'kode_transaksi' => $request->kodeTransaksi,
            'kode_user' => Auth::user()->kode_user,
            'kode_pelanggan' => $request->pelanggan,
            'tanggal' => $request->tanggalTransaksi,
            'total' => $request->totalTransaksi,
            'keterangan_transaksi' => $request->keteranganTransaksi
        ];

        $insertData = $transaksi::create($data);

        for($i=0; $i<$r; $i++){
            $dataDT = [
                'kode_transaksi' => $request->kodeTransaksi,
                'kode_produk' => $request->kodeProduk[$i],
                'qty' => $request->qtyProduk[$i],
                'harga' => $request->hargaProduk[$i]
            ];

            $insertDataDT = $detailtransaksi::create($dataDT);
        }

        if($insertDataDT){
            return redirect('admin/transaksi')->with('success','Data Berhasil Disimpan');
        }else{
            return redirect('admin/transaksi/create')->with('error','Data Gagal Disimpan');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataTransaksi = DB::table('transaksis')
                                ->join('users', 'transaksis.kode_user', '=', 'users.kode_user')
                                ->join('pelanggans','transaksis.kode_pelanggan','=','pelanggans.kode_pelanggan')
                                ->select('transaksis.*','users.nama_user as nama_user','pelanggans.name as naPelanggan')
                                ->where('transaksis.kode_transaksi',$id)
                                ->get();

        $datadetailTransaksi = DB::table('detail_transaksis')
                                ->join('barangs', 'detail_transaksis.kode_produk', '=', 'barangs.kode_produk')
                                ->select('detail_transaksis.*','barangs.nama_produk as nama_produk','barangs.harga as harga_produk','barangs.satuan as satuan_produk')
                                ->where('kode_transaksi',$id)
                                ->get();

        return view('admin/transaksi.show',[
            'dataTransaksi'=>$dataTransaksi,
            'datadetailTransaksi'=>$datadetailTransaksi])->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }

    public function reportTransaction(){
        $getData = DB::table('transaksis')
                        ->join('detail_transaksis','transaksis.kode_transaksi','=','detail_transaksis.kode_transaksi')
                        ->orderByDesc('transaksis.created_at')
                        ->paginate(10);

        return view('admin/laporan.index',[
            'getData' => $getData
        ]);
    }

    public function getReportTransaction(Request $request){

        switch($request->input('action')){
            case 'cariData':
                $dariTanggal = $request->dariTanggal;
                $sampaiTanggal = $request->sampaiTanggal;

                $getData = DB::table('transaksis')
                                ->join('detail_transaksis','transaksis.kode_transaksi','=','detail_transaksis.kode_transaksi')
                                ->whereBetween('transaksis.tanggal',[$dariTanggal, $sampaiTanggal])
                                ->paginate(10);

                return view('admin/laporan.index',[
                    'getData' => $getData
                ]);
            break;

            case 'printData':
                $dariTanggal = $request->dariTanggal;
                $sampaiTanggal = $request->sampaiTanggal;

                $getData = DB::table('transaksis')
                                ->join('detail_transaksis','transaksis.kode_transaksi','=','detail_transaksis.kode_transaksi')
                                ->whereBetween('transaksis.tanggal',[$dariTanggal, $sampaiTanggal])
                                ->get();

                $pdf = PDF::loadview('admin/laporan.cetak',[
                    'getData'=>$getData
                ]);
                return $pdf->stream();
            break;
        }
    }
}
