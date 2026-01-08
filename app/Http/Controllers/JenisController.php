<?php

namespace App\Http\Controllers;

use App\Models\Jenis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisController extends Controller
{
    public function index()
    {
        $filterNama = request()->filterNama;
        $filterId = request()->filterId;

        $jenises = Jenis::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $variables = [
            'jenises' => $jenises,
            'filterNama' => $filterNama,
            'filterId' => $filterId
        ];

        return view('jenis.index', $variables);
    }

    public function show(Jenis $jenis)
    {

        return view('jenis.show', ['jenis' => $jenis]);
    }

    public function create()
    {
        return view('jenis.create');
    }

    function ambilHurufPertamaKapital($string)
    {
        // Pisahkan string berdasarkan spasi
        $words = explode(' ', $string);

        // Ambil huruf pertama dari setiap kata dan ubah menjadi kapital
        $capitalizedWords = array_map(function ($word) {
            return strtoupper(substr($word, 0, 1)); // Ambil huruf pertama dan kapital
        }, $words);

        // Gabungkan kembali huruf pertama menjadi string
        return implode('', $capitalizedWords);
    }

    public function store()
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => 'required',
            'kode' => 'required',
            'isStokable' => 'required',
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);
        $validatedReq['kode'] = strtoupper($validatedReq['kode']);

        $storedJenis = Jenis::where('kode', request()->kode)->get();

        if (!$storedJenis->isEmpty()) {
            return back()->with('errorInput', 'Kode Sudah Terdaftar!');
        }

        Jenis::firstOrCreate($validatedReq);

        return redirect('/jenis')->with('message', 'Data jenis berhasil dibuat');
    }

    public function update(Jenis $jenis)
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        if (Auth::user()->level = 'admin') {
            // $kode = $this->ambilHurufPertamaKapital(request()->nama);

            $validatedReq = request()->validate([
                'nama' => 'required',
                'kode' => 'required',
                'isStokable' => 'required',
            ]);

            $validatedReq['nama'] = strtoupper($validatedReq['nama']);
            $validatedReq['kode'] = strtoupper($validatedReq['kode']);

            $storedJenis = Jenis::where('id', '!=', $jenis->id)->where('kode', request()->kode)->get();

            if (!$storedJenis->isEmpty()) {
                return back()->with('errorInput', 'Kode Sudah Terdaftar!');
            }

            $jenis->update($validatedReq);
        } else {
            return abort(403, 'Hanya super admin yang bisa update');
        }

        return back()->with('message', 'Data jenis berhasil diupdate');
    }

    public function destroy(Jenis $jenis)
    {
        return abort(403, 'Delete feature disabled!');
        // if (Auth::user()->level == 'admin') {
        //     $jenis->delete();
        // } else {
        //     abort(403, 'Unauthorized!');
        // }

        // return redirect('/jenis');
    }
}
