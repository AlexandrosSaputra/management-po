<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Item;
use App\Models\ItemPenawaran;
use App\Models\Jenis;
use App\Models\PreOrder;
use App\Models\Satuan;
use App\Models\Suplier;
use App\Models\TemplateOrder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PreOrderController extends Controller
{
    public function index()
    {
        $filterStatus = request()->filterStatus;
        $filterId = request()->filterId;
        $filterSuplier = request()->filterSuplier;
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
            $filterCabang = null;
            $periode_awal = Carbon::today()->subDay(365);
            $periode_akhir = Carbon::today()->endOfDay();
        }

        $cabangs = Cabang::all();
        $filteredCabang = Cabang::where('id', $filterCabang)->first();

        $supliers = Suplier::all();
        $filteredSuplier = Suplier::where('id', $filterSuplier)->get();

        $users = User::when($filterCabang, function ($q) use ($filterCabang) {
            return $q->where('cabang_id', $filterCabang);
        })->get();
        $filteredUser = User::when($filterUser, function ($q) use ($filterUser) {
            return $q->where('id', $filterUser);
        })->when($filterCabang, function ($q) use ($filterCabang) {
            return $q->where('cabang_id', $filterCabang);
        })->get();

        $preorders = PreOrder::whereBetween('created_at', [$periode_awal, $periode_akhir])
            ->where('isOrdered', false)
            ->when($filterId, function ($q) use ($filterId) {
                return $q->where('id', 'LIKE', '%' . $filterId . '%');
            })
            ->when($filterStatus, function ($q) use ($filterStatus) {
                return $q->where('status', $filterStatus);
            })
            ->when($filterSuplier, function ($q) use ($filterSuplier) {
                return $q->where('suplier_id', $filterSuplier);
            })
            ->when(Auth::user() != 'super' && Auth::user() != 'qc', function ($q) {
                return $q->where('status', '!=', 'invalid');
            })
            ->whereIn('user_id', $filteredUser->pluck('id'))
            ->latest()
            ->paginate(10);

        $variables = [
            'preorders' => $preorders,
            'cabangs' => $cabangs,
            'filterCabang' => $filterCabang,
            'filteredCabang' => $filteredCabang,
            'supliers' => $supliers,
            'filterSuplier' => $filterSuplier,
            'filteredSuplier' => $filteredSuplier,
            'filterStatus' => $filterStatus,
            'filterId' => $filterId,
            'users' => $users,
            'filterUser' => $filterUser,
            'filteredUser' => $filteredUser,
            'periode_awal' => $periode_awal->toDateString(),
            'periode_akhir' => $periode_akhir->toDateString(),
        ];

        return view('pre_order.index', $variables);
    }

    public function create()
    {
        $items = Item::all();
        $supliers = Suplier::all();
        $satuans = Satuan::all();
        $jenis = Jenis::all();

        $variables = [
            'items' => $items,
            'supliers' => $supliers,
            'satuans' => $satuans,
            'jenises' => $jenis
        ];

        return view('pre_order.create', $variables);
    }

    public function store()
    {
        $satuan_id = request()->input('satuan_id');
        $item_id = request()->input('item_id');

        $preorder = PreOrder::create([
            'user_id' => request('user_id'),
            'suplier_id' => request('suplier_id'),
            'jenis_id' => request('jenis_id'),
            'token' => Str::random(40)
        ]);

        $preorder->update([
            'link_token' => url('/preorder/' . $preorder->id . '?token=' . $preorder->token),
        ]);

        for ($i = 0; $i < count($item_id); $i++) {
            // validate
            request()->validate([
                'gambar-' . $i + 1 => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);

            if (request()['gambar-' . $i + 1]) {
                $gambar = request()['gambar-' . $i + 1];

                // Store the image
                $imageName = time() . $i . '.' . $gambar->extension();
                // server
                $destinationPath = '../public_html/folder-image-truenas';
                // lokal
                // $destinationPath = public_path('folder-image-truenas');
                $gambar->move($destinationPath, $imageName);
                $path = $imageName;

                if (!$path) {
                    $preorder->delete();

                    return abort(403, 'Upload gambar gagal, harap coba lagi');
                }
            }

            ItemPenawaran::create([
                'satuan_id' => $satuan_id[$i],
                'item_id' => $item_id[$i],
                'pre_order_id' => $preorder->id,
                'suplier_id' => $preorder->suplier_id,
                'gambar' => $path ?? null,
            ]);
        }

        return redirect('/preorder/' . $preorder->id)->with('message', 'Data pre order berhasil dibuat');
    }

    public function duplicate(PreOrder $preorder)
    {
        $newPreorder = PreOrder::create([
            'status' => 'diterima',
            'user_id' => $preorder->user_id,
            'suplier_id' => $preorder->suplier_id,
            'jenis_id' => $preorder->jenis_id,
            'token' => Str::random(40)
        ]);

        $newPreorder->update([
            'link_token' => url('/preorder/' . $preorder->id . '?token=' . $preorder->token),
        ]);

        foreach ($preorder->itemPenawarans as $_ => $itemPenawaran) {
            ItemPenawaran::create([
                'satuan_id' => $itemPenawaran->satuan_id,
                'item_id' => $itemPenawaran->item_id,
                'pre_order_id' => $newPreorder->id,
                'suplier_id' => $newPreorder->suplier_id,
                'harga' => $itemPenawaran->harga
            ]);
        }

        return back()->with('message', 'Data pre order berhasil diduplikat');
    }

    public function templateStore(TemplateOrder $templateorder)
    {
        request()->validate([
            'check_items' => 'required|array|min:1'
        ]);

        $preorder = PreOrder::firstOrCreate([
            'template_order_id' => $templateorder->id,
            'suplier_id' => $templateorder->suplier_id,
            'jenis_id' => $templateorder->jenis_id,
            'user_id' => $templateorder->user_id,
            'token' => Str::random(40),
        ]);

        $preorder->update([
            'link_token' => url('/preorder/' . $preorder->id . '?token=' . $preorder->token),
        ]);

        foreach (request()->check_items as $check_item) {
            $itemPenawaran = ItemPenawaran::where('id', '=', $check_item)->first();

            ItemPenawaran::create([
                'pre_order_id' => $preorder->id,
                'item_id' => $itemPenawaran->item_id,
                'satuan_id' => $itemPenawaran->satuan_id,
                'suplier_id' => $preorder->suplier_id
            ]);
        }

        return redirect('/preorder/' . $preorder->id)->with('message', 'Data pre order berhasil dibuat');
    }

    public function show(PreOrder $preorder)
    {
        $token = request()->query('token');
        $satuan = Satuan::latest()->get();

        abort_if($preorder->isOrdered, 403, "Data sudah diorder");

        if ($token) {
            if ($token != $preorder->token) {
                return abort(403, 'Invalid token or access denied.');
            }

            return view('pre_order.with-token', ['preorder' => $preorder, 'satuans' => $satuan, 'token' => $token]);
        }

        if (Auth::user()) {
            $item = Item::all();

            $variables = [
                'preorder' => $preorder,
                'items' => $item,
                'satuans' => $satuan
            ];


            $compareItemPenawaran = [];

            foreach ($preorder->itemPenawarans as $_ => $itemPenawaran) {
                $compareItemPenawaran[] = ItemPenawaran::with(['suplier', 'item'])
                    ->whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::now()])
                    ->where('order_id', '=', null)
                    ->where('item_id', '=', $itemPenawaran->item_id)
                    ->where('harga', '!=', null)
                    ->orderBy('harga', 'asc')
                    ->get()
                    ->groupBy('suplier_id');
            }

            $variables['compareItemPenawarans'] = $compareItemPenawaran;

            // dd($compareItemPenawaran);
            if (Auth::user()->level == 'admin') {
                $variables['supliers'] = Suplier::all();
                $variables['users'] = User::all();
                $variables['items'] = Item::all();
                $variables['jenises'] = Jenis::all();
                $variables['satuans'] = Satuan::all();

                return view('pre_order.admin-show', $variables);
            } else {
                return view('pre_order.show', $variables);
            }
        } else {
            return redirect('/login');
        }
    }

    public function edit(PreOrder $preorder)
    {
        $hargaItem = ItemPenawaran::all()->where('pre_order_id', $preorder->id);
        $jenis = Jenis::all();
        $gudang = Gudang::all();

        $data = [
            'preorder' => $preorder,
            'hargaItems' => $hargaItem,
            'jenises' => $jenis,
            'gudangs' => $gudang
        ];


        return view('pre_order.edit', $data);
    }

    public function update(PreOrder $preorder)
    {
        $token = request()->query('token');

        if ($token) {
            if (request()->tolak) {
                request()->validate([
                    'catatan_suplier' => 'required'
                ]);

                $preorder->update([
                    'status' => 'ditolak',
                    'catatan_suplier' => request()->catatan_suplier,
                    'token' => Str::random(40),
                ]);

                $preorder->update([
                    'link_token' => url('/preorder/' . $preorder->id . '?token=' . $preorder->token),
                ]);

                return redirect('/konfirmasi')->with('message', 'Data dikonfirmasi tolak');
            } else {
                // Replace commas with dots for each 'harga' field in the 'item' array
                $items = request()->input('item');
                foreach ($items as $index => $item) {
                    if (isset($item['harga'])) {
                        $items[$index]['harga'] = str_replace(',', '.', $item['harga']);
                    }
                }
                // Update request with modified data
                request()->merge(['item' => $items]);

                // Define validation rules
                request()->validate([
                    'item' => 'required|array',
                    'item.*.harga' => ['required', 'regex:/^\d+(\.\d{1,3})?$/'], // Regex allows decimals with up to 3 places
                    'item.*.satuan_id' => 'required', // Adjust as needed for your use case
                ]);

                if ($token != $preorder->token) {
                    return abort(403, 'Invalid token or access denied.');
                }

                $satuan = request()->input('satuan');
                $harga = request()->input('harga');

                foreach (request()->item as $index => $item) {

                    $preorder->itemPenawarans[$index]->update([
                        'satuan_id' => $item['satuan_id'],
                        'harga' => $item["harga"]
                    ]);
                }

                return $this->updateTerima($preorder);
            }
        } else {
            if (Auth::user()) {
                if (Auth::user()->level == 'admin') {

                    $itemCount = count($preorder->itemPenawarans);

                    request()->validate([
                        "suplier_id" => 'required',
                        "user_id" => 'required',
                        "jenis_id" => 'required',
                        'status' => 'required',
                        'item' => 'required|array|min:' . $itemCount,
                    ]);

                    $preorder->update([
                        "suplier_id" => request()->suplier_id,
                        "user_id" => request()->user_id,
                        "jenis_id" => request()->jenis_id,
                        'status' => request()->status,
                    ]);

                    foreach (request()->item as $index => $item) {
                        $harga = $item['harga'] ?? $preorder->itemPenawarans[$index]->harga;

                        $preorder->itemPenawarans[$index]->update([
                            'satuan_id' => $item['satuan_id'],
                            'item_id' => $item["item_id"],
                            'harga' => floatval(str_replace(',', '.', str_replace('.', '', $harga))),
                        ]);
                    }
                } else {
                    foreach (request()->item as $index => $item) {

                        $preorder->itemPenawarans[$index]->update([
                            'satuan_id' => $item['satuan_id'],
                            'item_id' => $item["item_id"]
                        ]);
                    }
                }
            } else {
                redirect('/login');
            }
        }

        return back()->with('message', 'Data berhasil diupdate');
    }

    public function updateTerima(PreOrder $preorder)
    {
        $preorder->update([
            'status' => 'diterima',
            'token' => Str::random(40),
        ]);

        $preorder->update([
            'link_token' => url('/preorder/' . $preorder->id . '?token=' . $preorder->token),
        ]);

        return redirect('/konfirmasi')->with('message', 'Data dikonfirmasi terima');
    }

    public function destroy(PreOrder $preorder)
    {
        $itemPenawarans = $preorder->itemPenawarans;

        // menghapus file gambar dan item preorder
        foreach ($itemPenawarans as $itemPenawaran) {
            $filename = $itemPenawaran->gambar;

            if ($filename) {
                // server
                $filePath = '../public_html/folder-image-truenas/' . $filename;

                // lokal
                // $filePath = public_path('folder-image-truenas/' . $filename);

                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }
            }

            $itemPenawaran->delete();
        }

        $preorder->delete();

        return redirect('/preorder')->with('message', 'Data berhasil dihapus');
    }

    public function cancel(PreOrder $preorder)
    {
        $pensanWACancel = '*PERMINTAAN PENAWARAN INI DICANCEL*' .
            "\n\nID Penawaran: " . $preorder->id .
            "\n\nPemesan: " . $preorder->user->nama .
            "\n\nTanggal rilis: " . explode(' ', $preorder->created_at)[0] .
            "\n\nCek daftar data penawaran: " .
            "\n" . url('/') . '/suplier/list-preorder?telepon=' . $preorder->suplier->telepon;

        $this->kirimWA($preorder, $pensanWACancel);

        $preorder->update([
            'status' => 'penawaran',
            'token' => Str::random(40),
        ]);

        $preorder->update([
            'link_token' => url('/preorder/' . $preorder->id . '?token=' . $preorder->token),
        ]);

        return back()->with('message', 'Data pre order terkirim ke suplier');
    }

    public function kirimSuplier(PreOrder $preorder)
    {
        $pesanWASuplier = '*PERMINTAAN PENAWARAN*' .
            "\n\nID Penawaran: " . $preorder->id .
            "\n\nPemesan: " . $preorder->user->nama .
            "\n\nTanggal rilis: " . explode(' ', $preorder->created_at)[0] .
            "\n\nCek daftar data penawaran: " .
            "\n" . url('/') . '/suplier/list-preorder?telepon=' . $preorder->suplier->telepon;

        $this->kirimWA($preorder, $pesanWASuplier);

        $preorder->update([
            'status' => 'dikirim',
        ]);

        return back()->with('message', 'Data pre order terkirim ke suplier');
    }

    public function notifSuplier(int $ongoingIndex = 0)
    {
        $notifpreorder = PreOrder::with('suplier')->where('status', 'dikirim')->where('created_at', '<', Carbon::today())->get()->groupBy('suplier_id');
        foreach ($notifpreorder as $index => $item) {
            if ($index < $ongoingIndex) {
                continue;
            }

            try {
                $pesanWASuplier = "*SEBANYAK " . count($item) . " DATA PENAWARAN PO BELUM TERKONFIRMASI*" .
                    "\n\nCek daftar data penawaran: " .
                    "\n" . url('/') . '/suplier/list-preorder?telepon=' . $item[0]->suplier->telepon;

                $this->kirimWA($item[0], $pesanWASuplier);
            } catch (\Throwable $th) {
                return $this->notifSuplier($index);
            }
        }

        return response()->json([
            'success' => 'Semua notifikasi terkirim',
        ], 200);
    }
	/*
    public function kirimWA(PreOrder $preorder, string $pesan)
    {
        $hp = $preorder->suplier->telepon;
        $baseUrl = "https://app.wapakrt.my.id/send-message";
        $params = array(
            'api_key' => 'AQFhKB2s01TxBsHoT5v3pvBS9X78VeOu',
            'sender' => '6289503314976',
            'number' => $hp,
            'message' => $pesan
        );

        $url = $baseUrl . '?' . http_build_query($params);

        try {
            $response = file_get_contents($url);
        } catch (\Throwable $th) {
            return abort(403, 'API WA Error, Coba Lagi');
        }
    }
	*/
	public function kirimWA(PreOrder $preorder, string $pesan)
    {
        $hp = $preorder->suplier->telepon;

        $baseUrl = "https://itnh.systems/wa_spl.php";
        $data = [
			'penerima' => $hp,
			'pesan'    => $pesan
		];

        $ch = curl_init($baseUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
    }
}
