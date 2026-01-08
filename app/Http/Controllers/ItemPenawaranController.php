<?php

namespace App\Http\Controllers;

use App\Models\BuktiGudang;
use App\Models\Harga;
use App\Models\Item;
use App\Models\ItemPenawaran;
use App\Models\Suplier;
use App\Models\TemplateOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ItemPenawaranController extends Controller
{
    public function index()
    {
        $filterItem = request()->filterItem ?? 19;
        $filteredItem = Item::where('id', '=', $filterItem)->first();

        $itemPenawaran = ItemPenawaran::with(['suplier', 'item'])
            ->whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::now()])
            ->where('order_id', '=', null)
            ->where('item_id', '=', $filterItem)
            ->where('harga', '!=', null)
            ->orderBy('harga', 'asc')
            ->get()
            ->groupBy('suplier_id');

        if (count($itemPenawaran) <= 0) {
            $itemKosong = Item::where('id', '=', $filteredItem->id)->first();
            return back()->with('message', $itemKosong->nama . ' tidak memiliki data PO');
        }

        $suplier = Suplier::all();
        $item = Item::all();

        $variables = [
            'itemPenawarans' => $itemPenawaran,
            'supliers' => $suplier,
            'items' => $item,
            'filterItem' => $filterItem,
            'filteredItem' => $filteredItem
        ];

        return view('item_penawaran.index', $variables);
    }

    public function storeFromTemplate(TemplateOrder $templateorder)
    {
        request()->validate([
            'item' => 'required|array',
            'item.*' => 'required|array',
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

        return back()->with('message', 'Item berhasil ditambahkan');
    }

    public function storeFromHarga(Harga $harga)
    {
        request()->validate([
            'item' => 'required|array|min:1',
            'item.*' => 'required|array',
            'item.*.harga' => 'required',
            'item.*.item_id' => 'required',
            'item.*.satuan_id' => 'required',
        ]);

        $items = request()->item;
        foreach ($items as $item) {
            ItemPenawaran::create([
                'harga_id' => $harga->id,
                'item_id' => $item['item_id'],
                'satuan_id' => $item['satuan_id'],
                'suplier_id' => $harga->suplier_id,
                'harga' => $item['harga']
            ]);
        }

        return back()->with('message', 'Item berhasil ditambahkan');
    }

    public function destroy(ItemPenawaran $itemPenawaran)
    {
        // admin
        if (Auth::user()) {
            if ($itemPenawaran->gambar) {
                $this->hapusGambar($itemPenawaran);
            }

            if ($itemPenawaran->kontrak_id) {
                $kontrak = $itemPenawaran->kontrak;

                $itemPenawaran->delete();

                if (count($kontrak->itemPenawarans) <= 0) {
                    $kontrak->update([
                        "status" => 'ditolak',
                    ]);
                }
            } elseif ($itemPenawaran->template_order_id) {
                $templateorder = $itemPenawaran->template_order;

                $itemPenawaran->delete();

                if (count($templateorder->itemPenawarans) <= 0) {
                    $templateorder->update([
                        "status" => 'tidakaktif',
                    ]);
                }
            } elseif ($itemPenawaran->harga_id) {
                $harga = Harga::find($itemPenawaran->harga_id);

                $itemPenawaran->delete();

                if (count($harga->itemPenawarans) <= 0) {
                    $harga->update([
                        "status" => 'tidakaktif',
                    ]);
                }
            } else {
                $preorder = $itemPenawaran->pre_order;

                $itemPenawaran->delete();

                if (count($preorder->itemPenawarans) <= 0) {
                    $preorder->update([
                        "status" => 'ditolak',
                    ]);
                }
            }

            return back()->with('message', 'Item berhasil dihapus');

            // user dengan token
        } else {
            if (request()->token) {
                if ($itemPenawaran->gambar) {
                    $this->hapusGambar($itemPenawaran);
                }

                if ($itemPenawaran->kontrak_id) {
                    $kontrak = $itemPenawaran->kontrak;

                    $itemPenawaran->delete();
                } else {
                    $preorder = $itemPenawaran->pre_order;

                    $itemPenawaran->delete();


                    if (count($preorder->itemPenawarans) <= 0) {
                        $preorder->update([
                            "status" => 'ditolak',
                            "token" => Str::random(40),
                        ]);

                        return redirect('/konfirmasi')->with('message', 'Item habis, penawaran otonatis ditolak');
                    }
                }

                return back()->with('message', 'Item berhasil dihapus');
            } else {
                return abort(403, 'No Access Token');
            }
        }
    }

    function hapusGambar(ItemPenawaran $itemPenawaran)
    {
        if ($itemPenawaran->gambar) {
            $filename = $itemPenawaran->gambar;

            // server
            $filePath = '../public_html/folder-image-truenas/' . $filename;

            // lokal
            // $filePath = public_path('folder-image-truenas/' . $filename);

            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }
    }

    public function update(ItemPenawaran $itemPenawaran)
    {
        if (request()->token != null) {
            $itemPenawaran->update([
                'satuan_id' => request()->satuan_id,
                'harga' => request()->harga
            ]);

            return redirect('/penawaran/' . $itemPenawaran->penawaran->id . '?token=' . request()->token);
        }

        $itemPenawaran->update([
            'satuan_id' => request()->satuan_id,
            'item_id' => request()->item_id,
            'harga' => request()->harga,
            'jumlah' => request()->jumlah,
            'total_harga' => request()->total_harga,
        ]);

        if ($itemPenawaran->kontrak_id != null) {
            return redirect('/nonpo/' . $itemPenawaran->kontrak->id);
        } else if ($itemPenawaran->pre_order_id != null) {
            return redirect('/preorder/' . $itemPenawaran->preorder->id);
        }
    }

    public function updateBuktiGudang(ItemPenawaran $itemPenawaran)
    {
        request()->validate([
            'gambar.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'index_item' => 'required'
        ]);

        if ($itemPenawaran->bukti_gudangs) {
            foreach ($itemPenawaran->bukti_gudangs as $index => $bukti_gudang) {
                // server
                $filePath = '../public_html/folder-image-truenas/' . $bukti_gudang->foto;

                // lokal
                // $filePath = public_path('folder-image-truenas/' . $bukti_gudang->foto);

                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }

                $bukti_gudang->delete();
            }
        }

        if (request()->hasfile('gambar')) {
            foreach (request()->file('gambar') as $indexGambar => $gambar) {
                $index = request()->index_item;

                // Store the image
                $imageName = time() . $index . '-' . $indexGambar . '.' . $gambar->extension();
                // Get the full URL or path of and store the image
                // server
                $destinationPath = '../public_html/folder-image-truenas';
                // lokal
                // $destinationPath = public_path('folder-image-truenas');

                try {
                    //code...
                    $gambar->move($destinationPath, $imageName);
                } catch (\Throwable $th) {
                    //throw $th;
                    return back()->with('erroruploadfile', 'Upload gambar gagal item ke-' . $index + 1 . ', harap coba lagi')->withFragment('itempenawaran-' . $index + 1);
                }
                $path = $imageName;

                if (!$path) {
                    return back()->with('erroruploadfile', 'Upload gambar gagal item ke-' . $index + 1 . ', harap coba lagi')->withFragment('itempenawaran-' . $index + 1);
                }

                // $itemPenawaran->update([
                //     'gambar_bukti_gudang' => $path,
                // ]);

                BuktiGudang::create([
                    'foto' => $path,
                    'item_penawaran_id' => $itemPenawaran->id
                ]);
            }
        }

        return back()->with('message', 'Gambar bukti item ke-' . $index + 1 . ' berhasil terupload')->withFragment('itempenawaran-' . $index + 1);
    }
}
