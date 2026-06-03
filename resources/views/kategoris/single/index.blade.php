@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2 d-flex justify-content-between">
                <a href="{{url('kategoris')}}" class="btn btn-secondary">Kembali</a>
                <a href="{{url('kategoris/download-pdf')}}/{{$data->kode}}" class="btn btn-danger">Download PDF</a>
            </div>
            <div class="card">
                <div class="card-header">Detail Kategori</div>
                <div class="card-body">
                    <table class="table table-bordered mb-4">
                        <tr><th width="30%">Kode Kategori</th><td>: {{$data->kode}}</td></tr>
                        <tr><th>Nama Kategori</th><td>: {{$data->nama}}</td></tr>
                    </table>
                    <h5 class="mt-4">Daftar Item dalam Kategori Ini</h5>
                    <table class="table table-striped table-bordered mt-2">
                        <thead>
                            <tr><th>Kode</th><th>Nama Barang</th><th>Jenis</th><th>Harga Beli</th><th>Harga Jual</th></tr>
                        </thead>
                        <tbody>
                            @forelse($data->masterItems as $item)
                                @php $harga_jual = round($item->harga_beli + ($item->harga_beli * $item->laba / 100)); @endphp
                                <tr>
                                    <td>{{$item->kode}}</td>
                                    <td>{{$item->nama}}</td>
                                    <td>{{$item->jenis}}</td>
                                    <td>Rp {{number_format($item->harga_beli, 0, ',', '.')}}</td>
                                    <td>Rp {{number_format($harga_jual, 0, ',', '.')}}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">Belum ada item.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
