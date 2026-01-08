<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    public function index()
    {
        $filterNama = request()->filterNama;
        $filterId = request()->filterId;

        $cabangs = Cabang::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $variables = [
            'cabangs' => $cabangs,
            'filterNama' => $filterNama,
            'filterId' => $filterId
        ];

        return view('cabang.index', $variables);
    }

    public function show(Cabang $cabang)
    {
        return view('cabang.show', ['cabang' => $cabang]);
    }

    public function create()
    {
        return view('cabang.create');
    }

    public function store()
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        $cabang = Cabang::where('nama', $validatedReq['nama'])->get();
        if ($cabang->isEmpty()) {
            Cabang::create($validatedReq);
        } else {
            return redirect('/cabang')->with('message', 'Data cabang dengan nomor ' . $cabang[0]->nama . ' sudah ada');
        }

        return redirect('/cabang')->with('message', 'Data cabang berhasil dibuat');
    }

    public function update(Cabang $cabang)
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        if (Auth::user()->level == 'admin') {
            $cabang->update($validatedReq);
        } else {
            return abort(403, 'Hanya super admin yang bisa update!');
        }

        return redirect('/cabang/' . $cabang->id)->with('message', 'Data cabang berhasil diupdate');
    }

    public function destroy(Cabang $cabang)
    {
        return abort(403, 'Delete data disabled!');
        // if (Auth::user()->level == 'admin') {
        //     $cabang->delete();
        // } else {
        //     abort(403, 'Unauthorized!');
        // }

        // return redirect('/cabang');
    }
}
