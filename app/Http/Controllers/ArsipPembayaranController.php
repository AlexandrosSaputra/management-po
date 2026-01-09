<?php

namespace App\Http\Controllers;

use App\Models\ArsipPembayaran;
use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Pembayaran;
use App\Models\Suplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ArsipPembayaranController extends Controller
{
    public function index()
    {
        $filterKode = request()->filterKode;
        $filterSuplier = request()->filterSuplier;
        $filterGudang = request()->filterGudang;
        if (Auth::user()->level == 'user') {
            # code...
            $filterCabang = Auth::user()->cabang_id;
            $filterUser = request()->filterUser ??  Auth::user()->id;
            $filterKasir = request()->filterKasir;
        } elseif (Auth::user()->level == 'pembayaran') {
            $filterCabang = request()->filterCabang;
            $filterUser = request()->filterUser;
            $filterKasir = request()->filterKasir ?? Auth::user()->id;
        } else {
            $filterCabang = request()->filterCabang ?? Auth::user()->cabang_id;
            $filterUser = request()->filterUser;
            $filterKasir = request()->filterKasir;
        }
        $periode_awal = request()->periode_awal ? Carbon::parse(request()->periode_awal) : Carbon::today()->subDay(7);
        $periode_akhir = request()->periode_akhir ? Carbon::parse(request()->periode_akhir)->endOfDay() : Carbon::today()->endOfDay();

        if ($filterKode) {
            $filterGudang = null;
            $filterSuplier = null;
            $periode_awal = Carbon::today()->subDay(365);
            $periode_akhir = Carbon::today()->endOfDay();
        }

        $supliers = Suplier::all();
        $filteredSuplier = Suplier::where('id', $filterSuplier)->first();

        $cabangs = Cabang::all();
        $filteredCabang = Cabang::where('id', $filterCabang)->first();

        $gudangs = Gudang::all();
        $filteredGudang = Gudang::where('id', $filterGudang)->get();

        $users = User::when($filterCabang, function ($q) use ($filterCabang) {
            return $q->where('cabang_id', $filterCabang);
        })->get();
        $filteredUser = User::when($filterUser, function ($q) use ($filterUser) {
            return $q->where('id', $filterUser);
        })->when($filterCabang, function ($q) use ($filterCabang) {
            return $q->where('cabang_id', $filterCabang);
        })->get();

        $kasirs = User::where('level', 'pembayaran')->when($filterCabang, function ($q) use ($filterCabang) {
            return $q->where('cabang_id', $filterCabang);
        })->get();
        $filteredKasir = User::where('id', $filterKasir)->get();

        $arsipPembayaranIds = ArsipPembayaran::whereBetween('tgl_bayar', [$periode_awal, $periode_akhir])->pluck('id');

        $pembayarans = Pembayaran::with(['gudang.cabang', 'user.cabang', 'suplier.cabang', 'orders.itemPenawarans'])->whereBetween('created_at', [$periode_awal, $periode_akhir])
            ->whereIn('arsip_pembayaran_id', $arsipPembayaranIds)
            ->when($filterKasir, function ($q) use ($filterKasir) {
                return $q->where('kasir_id', $filterKasir);
            })
            ->when($filterGudang, function ($q) use ($filterGudang) {
                return $q->where('gudang_id', $filterGudang);
            })
            ->when($filterKode, function ($q) use ($filterKode) {
                return $q->where('kode', 'LIKE', '%' . $filterKode . '%');
            })
            ->when($filterSuplier, function ($q) use ($filterSuplier) {
                return $q->where('suplier_id', $filterSuplier);
            })
            ->whereIn('user_id', $filteredUser->pluck('id'))
            ->latest()
            ->get();

        $countItemPenawaran = [];
        foreach ($pembayarans as $pembayaran) {
            foreach ($pembayaran->orders as $order) {
                foreach ($order->itemPenawarans as $itemPenawaran) {
                    $itemName = $itemPenawaran->item->nama;
                    $itemJumlah = $itemPenawaran->jumlah;
                    $itemSatuan = $itemPenawaran->satuan->nama;

                    // Check if the item already exists in the count array
                    $found = false;
                    foreach ($countItemPenawaran as &$item) {
                        if ($item['nama'] == $itemName) {
                            $item['jumlah'] += $itemJumlah; // Update the jumlah
                            $found = true;
                            break; // Exit the loop once found
                        }
                    }

                    // If not found, add a new entry
                    if (!$found) {
                        $countItemPenawaran[] = [
                            'nama' => $itemName,
                            'jumlah' => $itemJumlah,
                            'satuan' => $itemSatuan
                        ];
                    }
                }
            }
        }

        $variables = [
            'pembayarans' => $pembayarans,
            'filterKode' => $filterKode,
            'periode_awal' => $periode_awal->toDateString(),
            'periode_akhir' => $periode_akhir->toDateString(),
            'supliers' => $supliers,
            'filterSuplier' => $filterSuplier,
            'filteredSuplier' => $filteredSuplier,
            'gudangs' => $gudangs,
            'filterGudang' => $filterGudang,
            'filteredGudang' => $filteredGudang,
            'cabangs' => $cabangs,
            'filterCabang' => $filterCabang,
            'filteredCabang' => $filteredCabang,
            'users' => $users,
            'filterUser' => $filterUser,
            'filteredUser' => $filteredUser,
            'kasirs' => $kasirs,
            'filterKasir' => $filterKasir,
            'filteredKasir' => $filteredKasir,
            'countItemPenawaran' => $countItemPenawaran,
        ];

        return view('arsip-pembayaran.index', $variables);
    }

    public function show(ArsipPembayaran $arsip)
    {
        if (Auth::user()) {
            $countItemPenawaran = [];
            foreach ($arsip->pembayaran->orders as $order) {
                foreach ($order->itemPenawarans as $itemPenawaran) {
                    $itemName = $itemPenawaran->item->nama;
                    $itemJumlah = $itemPenawaran->jumlah;
                    $itemSatuan = $itemPenawaran->satuan->nama;

                    // Check if the item already exists in the count array
                    $found = false;
                    foreach ($countItemPenawaran as &$item) {
                        if ($item['nama'] == $itemName) {
                            $item['jumlah'] += $itemJumlah; // Update the jumlah
                            $found = true;
                            break; // Exit the loop once found
                        }
                    }

                    // If not found, add a new entry
                    if (!$found) {
                        $countItemPenawaran[] = [
                            'nama' => $itemName,
                            'jumlah' => $itemJumlah,
                            'satuan' => $itemSatuan
                        ];
                    }
                }
            }

            $variables = [
                'pembayaran' => $arsip->pembayaran,
                'countItemPenawaran' => $countItemPenawaran
            ];

            return view('arsip-pembayaran.show', $variables);
        } else {
            return redirect('/login');
        }
    }

    public function store(Pembayaran $pembayaran)
    {
        if (($pembayaran->kasir_id != Auth::user()->id) && (Auth::user()->level != 'admin')) {
            # code...
            return back()->with('errorMessage', 'Anda bukan kasir yang dituju dan bukan Super Admin!');
        }

        request()->validate([
            'tipe_pembayaran_id' => 'required',
            'tgl_bayar' => 'required'
        ]);

        $arsip = ArsipPembayaran::firstOrCreate([
            'pembayaran_id' => $pembayaran->id,
            'tipe_pembayaran_id' => request()->tipe_pembayaran_id,
            'tgl_bayar' => request()->tgl_bayar
        ]);

        $pembayaran->update([
            'status' => 'dibayar',
            'arsip_pembayaran_id' => $arsip->id,
        ]);

        return redirect('/arsip')->with('message', 'Data pembayaran sudah dibayar dan diarsip');
    }
}
