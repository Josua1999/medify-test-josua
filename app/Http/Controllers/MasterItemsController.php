<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        if (!empty($hargamax)) $data_search = $data_search->where('harga_beli', '<=', $hargamax);


        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0) {
    if ($method == 'new') {
        $item = new MasterItem;
        $item_kategoris = [];
    } else {
        $item = MasterItem::with('kategoris')->find($id);
        $item_kategoris = $item->kategoris->pluck('id')->toArray();
    }
    $data['item'] = $item;
    $data['method'] = $method;
    $data['kategoris'] = \App\Models\Kategori::all();
    $data['item_kategoris'] = $item_kategoris;
    return view('master_items.form.index', $data);
}


    public function singleView($kode) {
    $data['data'] = MasterItem::with('kategoris')->where('kode', $kode)->first();
    return view('master_items.single.index', $data);
}


    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $maxId = MasterItem::max('id') ?? 0;
            $kode = $maxId + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            // sleep(3); // Di-comment agar penyimpanan data menjadi instan dan responsif
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
                // Upload Foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data_item->foto = 'uploads/' . $filename;
        }

        $data_item->save();

// Simpan Relasi Kategori (Many-to-Many)
if ($request->has('kategori_ids')) {
    $data_item->kategoris()->sync($request->kategori_ids);
} else {
    $data_item->kategoris()->detach();
}

        return redirect('master-items');
    }

    public function delete($id) {
        $item = MasterItem::find($id);
        if ($item) {
            $item->kategoris()->detach();
            $item->delete();
        }
        return redirect('master-items');
    }

    public function downloadExcel() {
        $items = MasterItem::with('kategoris')->get();
        
        $html = '
        <table border="1">
            <tr>
                <th>No</th>
                <th>Nama kategori</th>
                <th>Nama items</th>
                <th>Nama supplier</th>
                <th>Harga</th>
                <th>Laba</th>
                <th>Harga jual</th>
            </tr>';
            
        foreach ($items as $key => $item) {
            $kategoris = $item->kategoris->pluck('nama')->implode(', ');
            $hargaJual = round($item->harga_beli + ($item->harga_beli * $item->laba / 100));
            $html .= '
            <tr>
                <td>' . ($key + 1) . '</td>
                <td>' . htmlspecialchars($kategoris ?: '-') . '</td>
                <td>' . htmlspecialchars($item->nama) . '</td>
                <td>' . htmlspecialchars($item->supplier) . '</td>
                <td>' . $item->harga_beli . '</td>
                <td>' . $item->laba . '%' . '</td>
                <td>' . $hargaJual . '</td>
            </tr>';
        }
        $html .= '</table>';
        
        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="master_items.xls"');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}
