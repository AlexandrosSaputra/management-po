<?php

namespace App\Http\Controllers;

use App\Models\TipePembayaran;
use Illuminate\Support\Facades\Auth;

class TipePembayaranController extends Controller
{
    public function index()
    {
        $filterNama = request()->filterNama;
        $filterId = request()->filterId;

        $tipePembayarans = TipePembayaran::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $variables = [
            'tipePembayarans' => $tipePembayarans,
            'filterNama' => $filterNama,
            'filterId' => $filterId
        ];

        return view('tipe-pembayaran.index', $variables);
    }

    public function show(TipePembayaran $tipePembayaran)
    {

        return view('tipe-pembayaran.show', ['tipePembayaran' => $tipePembayaran]);
    }

    public function create()
    {

        return view('tipe-pembayaran.create');
    }

    public function store()
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
            'norek' => ['required', 'regex:/^\d{10,15}$/'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        TipePembayaran::firstOrCreate($validatedReq);

        return redirect('/tipe-pembayaran')->with('message', 'Data tipe pembayaran berhasil dibuat');
    }

    public function update(TipePembayaran $tipePembayaran)
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
            'norek' => ['required', 'regex:/^\d{10,15}$/'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        if (Auth::user()->level == 'admin') {
            $isAktif = filter_var(request()->input('isAktif'), FILTER_VALIDATE_BOOLEAN);
            request()->validate([
                'nama' => 'required|string',
                'norek' => 'required|regex:/^\d{10,15}$/',
            ]);

            $tipePembayaran->update($validatedReq);
        } else {
            return abort(403, 'Hanya super admin yang bisa update!');
        }

        return back()->with('message', 'Data tipe pembayaran berhasil dibuat');
    }

    public function destroy(TipePembayaran $tipePembayaran)
    {
        return abort(403, 'Delete feature disabled');

        // $tipePembayaran->delete();

        // return redirect('/tipe-pembayaran');
    }
}
