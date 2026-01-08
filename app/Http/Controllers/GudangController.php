<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class GudangController extends Controller
{
    public function index()
    {
        $filterNama = request()->filterNama;
        $filterCabang = request()->filterCabang;
        $filteredCabang = Cabang::where('id', $filterCabang)->first();
        $filterTelepon = request()->filterTelepon;
        $filterId = request()->filterId;

        $gudangs = Gudang::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->when($filterCabang, function ($query) use ($filterCabang) {
                return $query->where('cabang_id', $filterCabang);
            })
            ->where('telepon', 'LIKE', '%' . $filterTelepon . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $cabangs = Cabang::latest()->get();

        $variables = [
            'gudangs' => $gudangs,
            'cabangs' => $cabangs,
            'filterNama' => $filterNama,
            'filterTelepon' => $filterTelepon,
            'filterCabang' => $filterCabang,
            'filteredCabang' => $filteredCabang,
            'filterId' => $filterId
        ];

        return view('gudang.index', $variables);
    }

    public function orderIndex()
    {
        if (request()->gudang_id) {
            $gudang = Gudang::find(request()->gudang_id);
            if ($gudang) {
                $orders = Order::where('gudang_id', '=', $gudang->id)
                    ->where('status', '=', 'onprocess')
                    ->latest()
                    ->paginate(10);

                $variables = [
                    'orders' => $orders,
                    'gudang' => $gudang,
                ];

                return view('gudang.list-order', $variables);
            } else {
                return abort(403, 'Gudang tidak terdaftar');
            }
        } else {
            return abort(403, 'Akses ditolak');
        }
    }

    public function show(gudang $gudang)
    {
        $cabangs = Cabang::latest()->get();

        $variables = [
            'gudang' => $gudang,
            'cabangs' => $cabangs
        ];

        return view('gudang.show', $variables);
    }

    public function create()
    {
        $cabangs = Cabang::latest()->get();

        $variables = [
            'cabangs' => $cabangs,
        ];

        return view('gudang.create',  $variables);
    }

    public function store()
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
            'cabang_id' => ['required'],
            'telepon' => ['required', 'regex:/^62[0-9]{8,12}$/'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        Gudang::create($validatedReq);

        return redirect('/gudang')->with('message', 'Data gudang berhasil dibuat');
    }

    public function update(Gudang $gudang)
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
            'cabang_id' => ['required'],
            'telepon' => ['required', 'regex:/^62[0-9]{8,12}$/'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        if (Auth::user()->level == 'admin') {
            $gudang->update($validatedReq);
        } else {
            return abort(403, 'Hanya super admin yang bisa update!');
        }

        return redirect('/gudang/' . $gudang->id)->with('message', 'Data gudang berhasil diupdate');
    }

    public function destroy(Gudang $gudang)
    {
        return abort(403, 'Delete data disabled!');
        // if (Auth::user()->level == 'admin') {
        //     $gudang->delete();
        // } else {
        //     abort(403, 'Unauthorized!');
        // }

        // return redirect('/gudang');
    }
}
