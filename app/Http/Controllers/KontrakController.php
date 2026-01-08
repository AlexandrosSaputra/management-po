<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Item;
use App\Models\ItemPenawaran;
use App\Models\Jenis;
use App\Models\Kontrak;
use App\Models\Satuan;
use App\Models\Suplier;
use App\Models\TemplateOrder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class KontrakController extends Controller
{
    public function index()
    {
        $filterStatus = request()->filterStatus;
        $filterId = request()->filterId;
        $filterSuplier = request()->filterSuplier;
        $filterGudang = request()->filterGudang;
        if (Auth::user()->level == 'user' || Auth::user()->level == 'pembayaran' || Auth::user()->level == 'manager') {
            # code...
            $filterCabang = Auth::user()->cabang_id;
            $filterUser = request()->filterUser ??  Auth::user()->id;
        } else {
            $filterCabang = request()->filterCabang;
            $filterUser = request()->filterUser;
        }
        $periode_awal = request()->periode_awal ? Carbon::parse(request()->periode_awal) : Carbon::today()->subDay(7);
        $periode_akhir = request()->periode_akhir ? Carbon::parse(request()->periode_akhir)->endOfDay() : Carbon::today()->endOfDay();

        if ($filterId) {
            $filterStatus = null;
            $filterSuplier = null;
            $filterGudang = null;
            $filterCabang = null;
            $periode_awal = Carbon::today()->subDay(365);
            $periode_akhir = Carbon::today()->endOfDay();
        }

        $cabangs = Cabang::all();
        $filteredCabang = Cabang::where('id', $filterCabang)->first();

        $supliers = Suplier::all();
        $filteredSuplier = Suplier::where('id', $filterSuplier)->first();

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

        $kontraks = Kontrak::whereBetween('created_at', [$periode_awal, $periode_akhir])
            ->when($filterStatus, function ($q) use ($filterStatus) {
                return $q->where('status', 'LIKE', '%' . $filterStatus . '%');
            })
            ->where('status', '!=', 'selesai')
            ->when($filterId, function ($q) use ($filterId) {
                return $q->where('id', 'LIKE', '%' . $filterId . '%');
            })
            ->when($filterSuplier, function ($q) use ($filterSuplier) {
                return $q->where('suplier_id', $filterSuplier);
            })
            ->when($filterGudang, function ($q) use ($filterGudang) {
                return $q->where('gudang_id', $filterGudang);
            })
            ->whereIn('user_id', $filteredUser->pluck('id'))
            ->latest()
            ->paginate(10);

        $variables = [
            'kontraks' => $kontraks,
            'filterStatus' => $filterStatus,
            'filterId' => $filterId,
            'periode_awal' => $periode_awal->toDateString(),
            'periode_akhir' => $periode_akhir->toDateString(),
            'supliers' => $supliers,
            'filterSuplier' => $filterSuplier,
            'filteredSuplier' => $filteredSuplier,
            'cabangs' => $cabangs,
            'filterCabang' => $filterCabang,
            'filteredCabang' => $filteredCabang,
            'supliers' => $supliers,
            'filterSuplier' => $filterSuplier,
            'filteredSuplier' => $filteredSuplier,
            'gudangs' => $gudangs,
            'filterGudang' => $filterGudang,
            'filteredGudang' => $filteredGudang,
            'users' => $users,
            'filterUser' => $filterUser,
            'filteredUser' => $filteredUser,
        ];

        return view('kontrak.index', $variables);
    }

    public function show(Kontrak $kontrak)
    {
        $item = Item::all();
        $satuan = Satuan::all();

        $variables = [
            'kontrak' => $kontrak,
            'items' => $item,
            'satuans' => $satuan,
        ];

        return view('kontrak.show', $variables);
    }

    public function create()
    {
        $items = Item::all();
        $supliers = Suplier::all();
        $satuans = Satuan::all();
        $jenis = Jenis::all();
        $gudangs = Gudang::all();

        $variables = [
            'items' => $items,
            'supliers' => $supliers,
            'satuans' => $satuans,
            'gudangs' => $gudangs,
            'jenises' => $jenis
        ];

        return view('kontrak.create', $variables);
    }

    public function store(TemplateOrder $templateorder)
    {
        request()->validate([
            'check_items' => 'required|array|min:1'
        ]);

        $kontrak = Kontrak::firstOrCreate([
            'template_order_id' => $templateorder->id,
            'suplier_id' => $templateorder->suplier_id,
            'gudang_id' => $templateorder->gudang_id,
            'jenis_id' => $templateorder->jenis_id,
            'user_id' => $templateorder->user_id,
            'token' => Str::random(40),
        ]);

        foreach (request()->check_items as $check_item) {
            $itemPenawaran = ItemPenawaran::where('id', '=', $check_item)->first();

            ItemPenawaran::create([
                'kontrak_id' => $kontrak->id,
                'item_id' => $itemPenawaran->item_id,
                'satuan_id' => $itemPenawaran->satuan_id,
                'suplier_id' => $kontrak->suplier_id
            ]);
        }

        return redirect('/nonpo/' . $kontrak->id)->with('message', 'Data kontrak berhasil dibuat');
    }

    public function update(Kontrak $kontrak)
    {
        request()->validate([
            'item' => 'required|array',
            'item.*.item_id' => 'required',
            'item.*.satuan_id' => 'required'
        ]);

        foreach ($kontrak->itemPenawarans as $index => $itemPenawaran) {
            $itemPenawaran->update([
                'item_id' => request()->item[$index]['item_id'],
                'satuan_id' => request()->item[$index]['satuan_id'],
            ]);
        }

        return back()->with('message', 'Data kontrak berhasil diupdate');
    }

    public function updateDate(Kontrak $kontrak)
    {
        $kontrak->update([
            'tanggal_mulai' => request()->tanggal_mulai,
            'tanggal_akhir' => request()->tanggal_akhir,
        ]);

        return back()->with('message', 'Data kontrak berhasil diupdate');
    }

    public function destroy(Kontrak $kontrak)
    {
        $kontrak->itemPenawarans()->delete();

        $kontrak->delete();

        return redirect('/nonpo')->with('message', 'Data kontrak berhasil dihapus');
    }
}
