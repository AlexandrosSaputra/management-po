<?php

namespace App\Http\Controllers;

use App\Models\Harga;
use App\Models\Item;
use App\Models\ItemPenawaran;
use App\Models\Jenis;
use App\Models\Satuan;
use App\Models\Suplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HargaController extends Controller
{
    public function index()
    {
        $filterStatus = request()->filterStatus ?? 'all';
        $filterId = request()->filterId;
        $filterSuplier = request()->filterSuplier ?? 'all';
        $filterJenis = request()->filterJenis ?? 'all';

        $jenises = Jenis::all();
        $filteredJenis = Jenis::where('id', 'LIKE',  $filterJenis == 'all' ? '%%' : $filterJenis)->first();
        $supliers = Suplier::all();
        $filteredSuplier = Suplier::where('id', 'LIKE',  $filterSuplier == 'all' ? '%%' : $filterSuplier)->first();

        if (Auth::user()->level == 'admin') {
            $hargas = Harga::where('status', 'LIKE',  $filterStatus == 'all' ? '%%' : $filterStatus)
                ->where('id', 'LIKE', '%' . $filterId . '%')
                ->where('suplier_id', 'LIKE',  $filterSuplier == 'all' ? '%%' : $filterSuplier)
                ->where('jenis_id', 'LIKE',  $filterJenis == 'all' ? '%%' : $filterJenis)
                ->latest()
                ->paginate(10);
        } else {
            $hargas = Harga::where('user_id', Auth::user()->id)
                ->where('status', 'LIKE',  $filterStatus == 'all' ? '%%' : $filterStatus)
                ->where('id', 'LIKE', '%' . $filterId . '%')
                ->where('suplier_id', 'LIKE',  $filterSuplier == 'all' ? '%%' : $filterSuplier)
                ->where('jenis_id', 'LIKE',  $filterJenis == 'all' ? '%%' : $filterJenis)
                ->latest()
                ->paginate(10);

        }

        $variables = [
            'hargas' => $hargas,
            'filterStatus' => $filterStatus,
            'filterId' => $filterId,
            'filterSuplier' => $filterSuplier,
            'filteredSuplier' => $filteredSuplier,
            'filterJenis' => $filterJenis,
            'filteredJenis' => $filteredJenis,
            'supliers' => $supliers,
            'jenises' => $jenises
        ];

        return view('harga.index', $variables);
    }

    public function show(Harga $harga)
    {
        $items = Item::all();
        $supliers = Suplier::all();
        $satuans = Satuan::all();
        $jenis = Jenis::all();

        $variables = [
            'harga' => $harga,
            'items' => $items,
            'supliers' => $supliers,
            'satuans' => $satuans,
            'jenises' => $jenis
        ];

        return view('harga.show', $variables);
    }

    public function create()
    {
        $items = Item::all();
        $supliers = Suplier::all();
        $satuans = Satuan::all();
        $jenis = Jenis::all();

        $variables = [
            'items' => $items,
            'supliers' => $supliers,
            'satuans' => $satuans,
            'jenises' => $jenis
        ];

        return view('harga.create', $variables);
    }

    public function store()
    {
        request()->validate([
            'suplier_id' => 'required',
            'jenis_id' => 'required',
            'item' => 'required|array|min:1',
            'item.*.harga' => 'required',
            'item.*.satuan_id' => 'required',
            'item.*.item_id' => 'required',
        ]);

        $availableHarga = Harga::with(['suplier'])->where('user_id', Auth::user()->id)->where('suplier_id', request()->suplier_id)->first();

        if ($availableHarga) {
            return back()->with('errorMessage', 'Data dengan suplier ' . $availableHarga->suplier->nama . ' sudah ada, harap update saja');
        }

        $harga = Harga::firstOrCreate([
            'suplier_id' => request()->suplier_id,
            'jenis_id' => request()->jenis_id,
            'user_id' => Auth::user()->id,
            'token' => Str::random(40),
        ]);

        $items = request()->item;
        foreach ($items as $item) {
            ItemPenawaran::create([
                'harga_id' => $harga->id,
                'item_id' => $item['item_id'],
                'satuan_id' => $item['satuan_id'],
                'suplier_id' => $harga->suplier_id,
                'harga' => $item['harga']
            ]);
        }

        return redirect('/harga')->with('message', 'Data harga berhasil dibuat');
    }

    public function duplicate(Harga $harga)
    {
        $newHarga = Harga::create([
            'user_id' => $harga->user_id,
            'suplier_id' => $harga->suplier_id,
            'jenis_id' => $harga->jenis_id,
            'token' => Str::random(40)
        ]);

        foreach ($harga->itemPenawarans as $_ => $itemPenawaran) {
            ItemPenawaran::create([
                'satuan_id' => $itemPenawaran->satuan_id,
                'item_id' => $itemPenawaran->item_id,
                'harga_id' => $newHarga->id,
                'suplier_id' => $newHarga->suplier_id,
                'harga' => $itemPenawaran->harga,
            ]);
        }

        return back()->with('message', 'Data berhasil diduplikat');
    }

    public function update(Harga $harga)
    {
        $itemPenawaranCount = count($harga->itemPenawarans);
        request()->validate([
            'item' => 'required|array|min:' . $itemPenawaranCount,
            'item.*.harga' => 'required',
            'item.*.item_id' => 'required',
            'item.*.satuan_id' => 'required',
        ]);

        foreach ($harga->itemPenawarans as $index => $itemPenawaran) {
            $itemPenawaran->update([
                'item_id' => request()->item[$index]['item_id'],
                'satuan_id' => request()->item[$index]['satuan_id'],
                'harga' => request()->item[$index]['harga'],
            ]);
        }

        return back()->with('message', 'Data berhasil diupdate');
    }

    public function metaUpdate(Harga $harga)
    {
        request()->validate([
            'suplier_id' => 'required',
            'jenis_id' => 'required',
            'status' => 'required',
        ]);

        $harga->update([
            'suplier_id' => request()->suplier_id,
            'jenis_id' => request()->jenis_id,
            'status' => request()->status,
        ]);

        foreach ($harga->itemPenawarans as $_ => $itemPenawaran) {
            $itemPenawaran->update([
                'suplier_id' => request()->suplier_id,
            ]);
        }

        return back()->with('message', 'Data berhasil diupdate');
    }

    public function destroy(Harga $harga)
    {
        $harga->itemPenawarans()->delete();

        $harga->delete();

        return redirect('/harga')->with('message', 'Data berhasil dihapus');
    }
}
