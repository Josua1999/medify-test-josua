<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <option @if($selected == 'Umum') selected @endif>Umum</option>
            <option @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>
    <div class="form-group mb-3">
    <label>Kategori Barang</label>
    <div class="p-3 card">
        @foreach($kategoris as $kategori)
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="kategori_ids[]" value="{{ $kategori->id }}" id="kategori_{{ $kategori->id }}"
                    @if(in_array($kategori->id, $item_kategoris)) checked @endif>
                <label class="form-check-label" for="kategori_{{ $kategori->id }}">
                    {{ $kategori->nama }} ({{ $kategori->kode }})
                </label>
            </div>
        @endforeach
        @if($kategoris->isEmpty())
            <span class="text-muted small">Belum ada kategori. Silakan <a href="{{ url('kategoris/form/new') }}" target="_blank">buat kategori baru</a> terlebih dahulu.</span>
        @endif
    </div>
</div>

 <div class="form-group mb-3">
     <label>Foto Barang</label>
     <input type="file" class="form-control" name="foto" accept="image/*">
     @if(!empty($item->foto))
         <div class="mt-2">
             <img src="{{ asset($item->foto) }}" alt="Foto Barang" style="max-height: 100px;" class="img-thumbnail">
         </div>
     @endif
 </div>

    <button class="mt-3 btn btn-primary">Submit</button>

</form>
