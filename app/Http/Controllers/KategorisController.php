<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class KategorisController extends Controller
{
    public function index()
    {
        return view('kategoris.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Kategori::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        $item = ($method == 'new') ? new Kategori : Kategori::find($id);
        return view('kategoris.form.index', ['item' => $item, 'method' => $method]);
    }

    public function singleView($kode)
    {
        $data['data'] = Kategori::with('masterItems')->where('kode', $kode)->first();
        if (!$data['data']) {
            abort(404);
        }
        return view('kategoris.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $data_item = ($method == 'new') ? new Kategori : Kategori::find($id);
        if ($method == 'new') {
            $maxId = Kategori::max('id') ?? 0;
            $kode = 'CAT' . str_pad($maxId + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->kode = $kode;
        $data_item->save();

        return redirect('kategoris');
    }

    public function delete($id)
    {
        $kategori = Kategori::find($id);
        if ($kategori) {
            $kategori->masterItems()->detach(); // Clean many-to-many relationship
            $kategori->delete();
        }
        return redirect('kategoris');
    }

    public function downloadPdf($kode)
    {
        $kategori = Kategori::with('masterItems')->where('kode', $kode)->first();
        if (!$kategori) {
            abort(404);
        }

        $data = [
            'kategori' => $kategori,
            'tanggal_cetak' => Carbon::now()->format('d-m-Y H:i:s')
        ];

        $pdf = Pdf::loadView('kategoris.pdf', $data);
        return $pdf->download('kategori-' . $kategori->kode . '.pdf');
    }
}
