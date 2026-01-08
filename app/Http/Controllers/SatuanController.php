<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SatuanController extends Controller
{
    public function index()
    {
        $filterNama = request()->filterNama;
        $filterId = request()->filterId;

        $satuans = Satuan::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $variables = [
            'satuans' => $satuans,
            'filterNama' => $filterNama,
            'filterId' => $filterId
        ];

        return view('satuan.index', $variables);
    }

    public function show(Satuan $satuan)
    {

        return view('satuan.show', ['satuan' => $satuan]);
    }

    public function create()
    {
        return view('satuan.create');
    }

    public function store()
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required']
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        Satuan::firstOrCreate($validatedReq);

        return redirect('/satuan')->with('message', 'Data satuan berhasil dibuat');
    }

    public function update(Satuan $satuan)
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required']
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        if(Auth::user()->level == 'admin') {
            $satuan->update($validatedReq);
        } else {
            return abort(403, 'Hanya super admin yang bisa update!');
        }

        return back()->with('message', 'Data satuan berhasil diupdate');;
    }

    public function destroy(Satuan $satuan)
    {
        return abort(403, 'Delete feature disabled!');
        // if (Auth::user()->level == 'admin') {
        //     $satuan->delete();
        // } else {
        //     abort(403, 'Unauthorized!');
        // }

        // return redirect('/satuan');
    }
}
