@extends('layouts.template')

@section('addressTitle','Data Transaksi')

@section('customStyle')
    <style>
        #tambahButton {
            margin: 0 0 3% 3%;
        }
    </style>
@endsection

@section('contentHere')
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-shadow mb-4">
                        <div class="card-header border-0">
                            <div class="custom-title-wrap bar-primary">
                                <div class="custom-title">Data Transaksi</div>
                                @if (Session::has('success'))
                                    <div class="alert alert-success successAlert">
                                        <p>{{ Session::get('success') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body- pt-3 pb-4">
                            <a class="btn btn-primary" id="tambahButton" href="{{ route('transaksi.create') }}"> <i class="fa fa-book"></i> Tambah Transaksi </a>
                            <table class="table table-stripped" id="tableTransaksi">
                                <thead>
                                    <th>No.</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @foreach ($transaksis as $trk => $transaksi)
                                        <tr>
                                            <td>{{ ++$trk }}</td>
                                            <td>{{ $transaksi->kode_transaksi }}</td>
                                            <td>{{ $transaksi->tanggal }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-info light-s" data-toggle="modal" data-id="{{ $transaksi->kode_transaksi }}" data-target="#detailTransaksiModal"><span class="fa fa-eye"></span></a>
                                                <a class="btn btn-sm btn-warning light-s" href="{{ route('transaksi.edit', $transaksi->kode_transaksi) }}"><span class="fa fa-pencil"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Init Modal -->

        <div class="modal fade bd-example-modal-lg" id="detailTransaksiModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myLargeModalLabel">Detail Transaksi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('scriptPlace')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableTransaksi').DataTable();
        });
    </script>

     <!-- Init Modal -->
     <script type="text/javascript">
        $(document).ready(function(){
            $("#detailTransaksiModal").on('show.bs.modal', function(e){

                var kodeTransaksi = $(e.relatedTarget).data('id');

                $.get('/admin/transaksi/'+kodeTransaksi, function(data){
                    $(".modal-body").html(data);
                });
            });
        });
    </script>
    <!-- End -->
@endsection
