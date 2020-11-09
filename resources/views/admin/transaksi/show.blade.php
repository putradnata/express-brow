@foreach ($dataTransaksi as $transaksi)
    <table class="table" name="detailTransaksi">
        <tr>
            <th>Kode Transaksi</th>
            <td>:</td>
            <td>{{ $transaksi->kode_transaksi }}</td>
        </tr>
        <tr>
            <th>Nama Pegawai</th>
            <td>:</td>
            <td>{{ $transaksi->nama_user}}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>:</td>
            <td>{{ $transaksi->tanggal}}</td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td>:</td>
            <td>{{ $transaksi->keterangan_transaksi}}</td>
        </tr>
    </table>
    @foreach ($datadetailTransaksi as $detailTransaksi)
    <table class="table">
        <tr>
            <th>Qty</th>
            <th>Satuan</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
        </tr>
        <tr>
            <td>{{ $detailTransaksi->qty}}</td>
            <td>{{ $detailTransaksi->satuan_produk}}</td>
            <td>{{ $detailTransaksi->nama_produk}}</td>
            <td>{{ $detailTransaksi->harga_produk}}</td>
            <td>{{ $detailTransaksi->harga_produk*$detailTransaksi->qty}}</td>
        </tr>
        <tr>
            <td colspan="4" align="Center"><b>Total Harga</b></td>
            <td>{{ $transaksi->total}}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
        </tr>
    </table>
    @endforeach
@endforeach
