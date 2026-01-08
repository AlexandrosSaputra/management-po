<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Jenis;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $filterNama = request()->filterNama;
        $filterId = request()->filterId;

        $items = Item::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $variables = [
            'items' => $items,
            'filterNama' => $filterNama,
            'filterId' => $filterId
        ];

        return view('item.index', $variables);
    }

    public function show(Item $item)
    {
        $jenis = Jenis::latest()->get();

        return view('item.show', ['item' => $item, 'jenises' => $jenis]);
    }

    public function create()
    {
        $jenis = Jenis::latest()->get();

        return view('item.create', ['jenises' => $jenis]);
    }

    public function store()
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => 'required',
            'jenis_id' => 'required'
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        Item::firstOrCreate($validatedReq);

        return redirect('/item')->with('message', 'Data item berhasil dibuat');
    }

    public function update(Item $item)
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => 'required',
            'jenis_id' => 'required'
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        if(Auth::user()->level == 'admin') {
            $item->update($validatedReq);
        } else {
            return abort(403, 'Hanya super admin yang bisa update');
        }

        return back()->with('message', 'Data item berhasil diupdate');
    }

    public function destroy(Item $item)
    {
        return abort(403, 'Delete feature disabled!');
        // if (Auth::user()->level == 'admin') {
        //     $item->delete();
        // } else {
        //     abort(403, 'Unauthorized!');
        // }

        // return redirect('/item');
    }
}
