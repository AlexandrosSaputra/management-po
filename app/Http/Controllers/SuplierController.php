<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\Suplier;
use Illuminate\Support\Facades\Auth;

class SuplierController extends Controller
{
    public function index()
    {
        $filterNama = request()->filterNama;
        $filterTelepon = request()->filterTelepon;
        $filterCabang = request()->filterCabang;
        $filteredCabang = Cabang::where('id', $filterCabang)->first();
        $filterId = request()->filterId;

        $supliers = Suplier::where('nama', 'LIKE', '%' . $filterNama . '%')
            ->when($filterCabang, function ($q) use ($filterCabang) {
                return $q->where('cabang_id', 'LIKE', '%' . $filterCabang . '%');
            })
            ->where('telepon', 'LIKE', '%' . $filterTelepon . '%')
            ->where('id', 'LIKE', '%' . $filterId . '%')
            ->latest()
            ->paginate(10);

        $cabangs = Cabang::all();

        $variables = [
            'supliers' => $supliers,
            'cabangs' => $cabangs,
            'filterNama' => $filterNama,
            'filterTelepon' => $filterTelepon,
            'filterCabang' => $filterCabang,
            'filteredCabang' => $filteredCabang,
            'filterId' => $filterId
        ];

        return view('suplier.index', $variables);
    }

    public function orderIndex()
    {
        if (request()->telepon) {
            $suplier = Suplier::where('telepon', '=', request()->telepon)->first();
            if ($suplier) {

                $orders = Order::where('suplier_id', '=', $suplier->id)
                    ->where(function ($query) {
                        $query->where('status', '=', 'terkirim')
                            ->orWhere('status', '=', 'revisiterkirim')
                            ->orWhere('status', '=', 'onprocess');
                    })->latest()->paginate(10);

                $variables = [
                    'orders' => $orders,
                    'suplier' => $suplier,
                ];

                return view('suplier.list-order', $variables);
            } else {
                return abort(403, 'Suplier tidak terdaftar');
            }
        } else {
            return abort(403, 'Akses ditolak');
        }
    }

    public function preorderIndex()
    {
        if (request()->telepon) {
            $suplier = Suplier::where('telepon', '=', request()->telepon)->first();
            if ($suplier) {

                $preorders = PreOrder::where('suplier_id', '=', $suplier->id)
                    ->where('status', '=', 'dikirim')
                    ->latest()
                    ->paginate(10);

                $variables = [
                    'preorders' => $preorders,
                    'suplier' => $suplier,
                ];

                return view('suplier.list-preorder', $variables);
            } else {
                return abort(403, 'Suplier tidak terdaftar');
            }
        } else {
            return abort(403, 'Akses ditolak');
        }
    }

    public function show(Suplier $suplier)
    {
        $cabangs = Cabang::all();
        return view('suplier.show', ['suplier' => $suplier, 'cabangs' => $cabangs]);
    }

    public function create()
    {
        $cabangs = Cabang::all();
        return view('suplier.create', ['cabangs' => $cabangs]);
    }

    public function store()
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
            'telepon' => ['required', 'regex:/^62[0-9]{8,12}$/'],
            'cabang_id' => ['required'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        $suplier = Suplier::where('telepon', $validatedReq['telepon'])->get();
        if ($suplier->isEmpty()) {
            Suplier::create($validatedReq);
        } else {
            return redirect('/suplier')->with('message', 'Data suplier dengan nomor ' . $suplier[0]->telepon . ' sudah ada');
        }

        return redirect('/suplier')->with('message', 'Data suplier berhasil dibuat');
    }

    public function update(Suplier $suplier)
    {
        if (Auth::user()->level != 'admin') {
            # code...
            return abort(403, "Hanya super admin yang dapat mengakses!");
        }

        $validatedReq = request()->validate([
            'nama' => ['required'],
            'telepon' => ['required', 'regex:/^62[0-9]{8,12}$/'],
            'cabang_id' => ['required'],
        ]);

        $validatedReq['nama'] = strtoupper($validatedReq['nama']);

        if (Auth::user()->level == 'admin') {
            $suplier->update($validatedReq);
        } else {
            return abort(403, 'Hanya super admin yang bisa update!');
        }

        return back()->with('message', 'Data suplier berhasil diupdate');
    }

    public function destroy(Suplier $suplier)
    {
        return abort(403, 'Delete feature disabled');

        // $suplier->delete();

        // return redirect('/suplier');
    }
}
