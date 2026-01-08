<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Item;
use App\Models\ItemPenawaran;
use App\Models\Jenis;
use App\Models\Satuan;
use App\Models\Suplier;
use App\Models\TemplateOrder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TemplateOrderController extends Controller
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
            $templateorders = TemplateOrder::where('status', 'LIKE',  $filterStatus == 'all' ? '%%' : $filterStatus)
                ->where('id', 'LIKE', '%' . $filterId . '%')
                ->where('suplier_id', 'LIKE',  $filterSuplier == 'all' ? '%%' : $filterSuplier)
                ->where('jenis_id', 'LIKE',  $filterJenis == 'all' ? '%%' : $filterJenis)
                ->latest()
                ->paginate(10);
        } else {
            $templateorders = TemplateOrder::where('user_id', Auth::user()->id)
                ->where('status', 'LIKE',  $filterStatus == 'all' ? '%%' : $filterStatus)
                ->where('id', 'LIKE', '%' . $filterId . '%')
                ->where('suplier_id', 'LIKE',  $filterSuplier == 'all' ? '%%' : $filterSuplier )
                ->where('jenis_id', 'LIKE',  $filterJenis == 'all' ? '%%' : $filterJenis )
                ->latest()
                ->paginate(10);
        }

        $variables = [
            'templateorders' => $templateorders,
            'filterStatus' => $filterStatus,
            'filterId' => $filterId,
            'filterSuplier' => $filterSuplier,
            'filteredSuplier' => $filteredSuplier,
            'filterJenis' => $filterJenis,
            'filteredJenis' => $filteredJenis,
            'supliers' => $supliers,
            'jenises' => $jenises
        ];


        return view('template-po.index', $variables);
    }

    public function show(TemplateOrder $templateorder)
    {
        $items = Item::all();
        $supliers = Suplier::all();
        $satuans = Satuan::all();
        $jenis = Jenis::all();
        $gudangs = Gudang::all();

        $variables = [
            'templateorder' => $templateorder,
            'items' => $items,
            'supliers' => $supliers,
            'satuans' => $satuans,
            'gudangs' => $gudangs,
            'jenises' => $jenis
        ];

        return view('template-po.show', $variables);
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

        return view('template-po.create', $variables);
    }

    public function store()
    {
        request()->validate([
            'user_id' => 'required',
            'suplier_id' => 'required',
            'jenis_id' => 'required',
            'gudang_id' => 'required',
            'item' => 'required|array',
        ]);

        $templateorder = TemplateOrder::firstOrCreate([
            'suplier_id' => request()->suplier_id,
            'gudang_id' => request()->gudang_id,
            'jenis_id' => request()->jenis_id,
            'user_id' => request()->user_id,
            'token' => Str::random(40),
        ]);

        $items = request()->item;
        foreach ($items as $item) {
            ItemPenawaran::create([
                'template_order_id' => $templateorder->id,
                'item_id' => $item['item_id'],
                'satuan_id' => $item['satuan_id'],
                'suplier_id' => $templateorder->suplier_id
            ]);
        }

        return redirect('/templateorder')->with('message', 'Data template berhasil dibuat');
    }

    public function duplicate(TemplateOrder $templateorder)
    {
        $newTemplateorder = TemplateOrder::create([
            'gudang_id' => $templateorder->gudang_id,
            'user_id' => $templateorder->user_id,
            'suplier_id' => $templateorder->suplier_id,
            'jenis_id' => $templateorder->jenis_id,
            'token' => Str::random(40)
        ]);

        foreach ($templateorder->itemPenawarans as $_ => $itemPenawaran) {
            ItemPenawaran::create([
                'satuan_id' => $itemPenawaran->satuan_id,
                'item_id' => $itemPenawaran->item_id,
                'template_order_id' => $newTemplateorder->id,
                'suplier_id' => $newTemplateorder->suplier_id,
            ]);
        }

        return back()->with('message', 'Data template berhasil diduplikat');
    }

    public function update(TemplateOrder $templateorder)
    {
        $itemPenawaranCount = count($templateorder->itemPenawarans);
        request()->validate([
            'item' => 'required|array|min:' . $itemPenawaranCount,
        ]);

        foreach ($templateorder->itemPenawarans as $index => $itemPenawaran) {
            $itemPenawaran->update([
                'item_id' => request()->item[$index]['item_id'],
                'satuan_id' => request()->item[$index]['satuan_id'],
            ]);
        }

        return back()->with('message', 'Data template berhasil diupdate');
    }

    public function metaUpdate(TemplateOrder $templateorder)
    {
        request()->validate([
            'suplier_id' => 'required',
            'jenis_id' => 'required',
            'gudang_id' => 'required',
            'status' => 'required',
        ]);

        $templateorder->update([
            'suplier_id' => request()->suplier_id,
            'jenis_id' => request()->jenis_id,
            'gudang_id' => request()->gudang_id,
            'status' => request()->status,
        ]);

        foreach ($templateorder->itemPenawarans as $_ => $itemPenawaran) {
            $itemPenawaran->update([
                'suplier_id' => request()->suplier_id,
            ]);
        }

        return back()->with('message', 'Data template berhasil diupdate');
    }

    public function destroy(TemplateOrder $templateorder)
    {
        $templateorder->itemPenawarans()->delete();

        $templateorder->delete();

        return redirect('/templateorder')->with('message', 'Data template berhasil dihapus');
    }
}
