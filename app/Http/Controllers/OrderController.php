<?php

namespace App\Http\Controllers;

use App\Models\AnggaranDana;
use App\Models\AnggaranDetilDana;
use App\Models\Cabang;
use App\Models\CabangDana;
use App\Models\DivisiProjectDana;
use App\Models\Gudang;
use App\Models\Harga;
use App\Models\Item;
use App\Models\ItemPenawaran;
use App\Models\Jenis;
use App\Models\JudulDana;
use App\Models\Kontrak;
use App\Models\Order;
use App\Models\PreOrder;
use App\Models\Satuan;
use App\Models\Suplier;
use App\Models\TemplateOrder;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index()
    {
        $filterJenis = request()->filterJenis;
        $filterStatus = request()->filterStatus;
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
        $filterKode = request()->filterKode;
        $periode_awal = request()->periode_awal ? Carbon::parse(request()->periode_awal) : Carbon::today()->subDay(7);
        $periode_akhir = request()->periode_akhir ? Carbon::parse(request()->periode_akhir)->endOfDay() : Carbon::today()->endOfDay();

        if ($filterKode) {
            $filterJenis = null;
            $filterStatus = null;
            $filterSuplier = null;
            $filterGudang = null;
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

        $orders = Order::whereBetween('created_at', [$periode_awal, $periode_akhir])
            ->where('pembayaran_id', null)
            ->when($filterJenis == 'kontrak', function ($q) {
                return $q->where('isKontrak', 1);
            })
            ->when($filterJenis == 'preorder', function ($q) {
                return $q->where('isKontrak', 0);
            })
            ->when($filterStatus, function ($q) use ($filterStatus) {
                return $q->where('status', 'LIKE', '%' . $filterStatus . '%');
            })
            ->when(Auth::user() != 'super' && Auth::user() != 'qc', function ($q) {
                return $q->where('status', '!=', 'invalid');
            })
            ->when($filterKode, function ($q) use ($filterKode) {
                return $q->where('kode', 'LIKE', '%' . $filterKode . '%');
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

        $orderTerkirimTerlambat = 0;
        $orderOnprocessTerlambat = 0;
        $orderDiterimaTerlambat = 0;
        $orderPreorderTerlambat = 0;
        $orderBelumSelesai = 0;
        $orderSelesai = 0;
        $orderTerlambat = 0;
        $orderTepat = 0;
        $jumlahOrder = $orders->count();
        foreach ($orders as $index => $order) {
            if ($order->status == 'diterima') {
                $selisihWaktu = Carbon::parse($order->tgl_selesai)->diff(Carbon::parse($order->target_kirim));

                if ($selisihWaktu->invert > 0) {
                    $orderDiterimaTerlambat++;
                    $orderTerlambat++;
                } else {
                    $orderTepat++;
                }

                $orderSelesai++;
            } else {
                $orderBelumSelesai++;
            }

            if ($order->status == 'terkirim') {
                $selisihWaktu = today()->diff(Carbon::parse($order->target_kirim));

                if ($selisihWaktu->invert > 0) {
                    $orderTerkirimTerlambat++;
                    $orderTerlambat++;
                }
            }

            if ($order->status == 'onprocess') {
                $selisihWaktu = today()->diff(Carbon::parse($order->target_kirim));

                if ($selisihWaktu->invert > 0) {
                    $orderOnprocessTerlambat++;
                    $orderTerlambat++;
                }
            }

            if ($order->status == 'preorder') {
                $selisihWaktu = today()->diff(Carbon::parse($order->target_kirim));

                if ($selisihWaktu->invert > 0) {
                    $orderPreorderTerlambat++;
                    $orderTerlambat++;
                }
            }
        }

        $variables = [
            'orders' => $orders,
            'filterJenis' => $filterJenis,
            'filterStatus' => $filterStatus,
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
            'orderTerkirimTerlambat' => $orderTerkirimTerlambat,
            'orderPreorderTerlambat' => $orderPreorderTerlambat,
            'orderOnprocessTerlambat' => $orderOnprocessTerlambat,
            'orderDiterimaTerlambat' => $orderDiterimaTerlambat,
            'orderBelumSelesai' => $orderBelumSelesai,
            'orderSelesai' => $orderSelesai,
            'orderTerlambat' => $orderTerlambat,
            'orderTepat' => $orderTepat,
            'jumlahOrder' => $jumlahOrder,
        ];

        return view('order.index', $variables);
    }

    public function create()
    {
        $filterSuplier = intval(request()->suplier) == 0 ? 1 : intval(request()->suplier);
        $filteredSuplier = Suplier::where('id', '=', $filterSuplier)->first();

        $harga = Harga::with(['suplier', 'jenis', 'user'])->where('user_id', '=', Auth::user()->id)->where('suplier_id', '=', $filterSuplier)->get();

        if (!$harga->isEmpty()) {
            $pemesan = $harga[0]->user;
            $jenis = $harga[0]->jenis;
            $suplier = $harga[0]->suplier;
            $itemPenawarans = $harga[0]->itemPenawarans;
        }

        $supliers = Suplier::all();

        $jenises = Jenis::all();

        $gudangs = Gudang::all();

        $variables = [
            'harga' => $harga,
            'pemesan' => $pemesan ?? null,
            'jenis' => $jenis ?? null,
            'suplier' => $suplier ?? null,
            'itemPenawarans' => $itemPenawarans ?? collect(),
            'supliers' => $supliers,
            'jenises' => $jenises,
            'gudangs' => $gudangs,
            'filterSuplier' => $filterSuplier,
            'filteredSuplier' => $filteredSuplier,
        ];

        return view('order.create', $variables);
    }

    public function show(Order $order)
    {
        if (Auth::user()) {
            // menampilkan data kontrak
            if ($order->kontrak_id) {
                if ($order->status != 'diterima' && $order->status != 'revisiditerima') {
                    $itemPenawarans = $order->kontrak->itemPenawarans;
                } else {
                    $itemPenawarans = ItemPenawaran::where('order_id', $order->id)->get();
                }
                // menampilkan data penawaran
            } else {
                $itemPenawarans = ItemPenawaran::where('order_id', $order->id)->get();
            }

            $cabangsDana = CabangDana::all();
            $divisisDana = DivisiProjectDana::all();
            $judulsDana = JudulDana::where('status', 1)->get();

            $variables = [
                'order' => $order,
                'gudangs' => Gudang::all(),
                'jenises' => Jenis::all(),
                'itemPenawarans' => $itemPenawarans,
                'cabangsDana' => $cabangsDana,
                'divisisDana' => $divisisDana,
                'judulsDana' => $judulsDana,
            ];

            if (Auth::user()->level == 'admin') {
                $variables['supliers'] = Suplier::all();
                $variables['users'] = User::all();
                $variables['items'] = Item::all();
                $variables['satuans'] = Satuan::all();

                return view('order.admin-show', $variables);
            } else {
                return view('order.show', $variables);
            }
        } else {
            return redirect('/login');
        }
    }

    public function showOrderSuplier(Order $order)
    {
        $token = request()->query('token');

        if ($token) {
            if ($token != $order->token) {
                return abort(403, 'Invalid token or access denied.');
            }

            $itemPenawarans = ItemPenawaran::where('order_id', $order->id)->get();

            return view('order.suplier-with-token', ['order' => $order, 'itemPenawarans' => $itemPenawarans]);
        }
    }

    public function showOrderGudang(Order $order)
    {
        $token = request()->query('token');

        if ($token) {
            if ($token != $order->token) {
                return abort(403, 'Invalid token or access denied.');
            }

            $itemPenawarans = ItemPenawaran::where('order_id', $order->id)->get();

            return view('order.gudang-with-token', ['order' => $order, 'itemPenawarans' => $itemPenawarans]);
        }
    }

    public function store()
    {
        // order untuk pre order
        if (request()->preorder_id != null) {
            request()->validate([
                'check_items' => 'required|array|min:1',
            ]);

            $preorder = PreOrder::where('id', request()->preorder_id)->first();
            $order = Order::firstOrCreate([
                'pre_order_id' => $preorder->id,
                'user_id' => $preorder->user_id,
                'suplier_id' => $preorder->suplier_id,
                'token' => Str::random(40),
                'isKontrak' => false,
                'jenis_id' => $preorder->jenis_id,

            ]);

            $order->update([
                'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
            ]);

            // $date = now()->format('dmY');
            $order->update([
                'kode' => $order->jenis->kode . $order->id,
            ]);

            $preorder->update([
                'isOrdered' => true,
                'order_id' => $order->id,
            ]);

            foreach (request()->check_items as $check_item) {
                $itemPenawaran = ItemPenawaran::where('id', '=', $check_item)->first();

                $itemPenawaran->update([
                    'order_id' => $order->id
                ]);
            }

            return redirect('/order/' . $order->id)->with('message', 'Data order berhasil dibuat');

            // order untuk kontrak
        } else if (request()->kontrak_id != null) {

            $kontrak = Kontrak::where('id', request()->kontrak_id)->first();
            $order = Order::create([
                'kontrak_id' => $kontrak->id,
                'user_id' => $kontrak->user_id,
                'suplier_id' => $kontrak->suplier_id,
                'gudang_id' => $kontrak->gudang_id,
                'jenis_id' => $kontrak->jenis_id,
                'token' => Str::random(40),
                'isKontrak' => true,
                'total_biaya' => $kontrak->total_biaya,
                'target_kirim' => $kontrak->tanggal_akhir,
            ]);

            $order->update([
                'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
            ]);

            $order->update([
                'kode' => $order->jenis->kode . $order->id,
            ]);

            $kontrak->update([
                'status' => 'order',
                'order_id' => $order->id,
            ]);

            foreach ($kontrak->itemPenawarans as $itemPenawaran) {
                $itemPenawaran->update([
                    'order_id' => $order->id
                ]);
            }

            return redirect('/order/' . $order->id)->with('message', 'Data order berhasil dibuat');
        }
    }

    public function fromTemplateStore(TemplateOrder $templateorder)
    {
        request()->validate([
            'check_items' => 'required|array|min:1'
        ]);

        $preorder = PreOrder::firstOrCreate([
            'status' => 'diterima',
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

        $order = Order::firstOrCreate([
            'pre_order_id' => $preorder->id,
            'user_id' => $preorder->user_id,
            'suplier_id' => $preorder->suplier_id,
            'token' => Str::random(40),
            'isKontrak' => false,
            'isNonpo' => true,
            'jenis_id' => $preorder->jenis_id,
        ]);

        $order->update([
            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
        ]);

        $order->update([
            'kode' => $order->jenis->kode . $order->id,
        ]);

        $preorder->update([
            'isOrdered' => true,
            'order_id' => $order->id,
        ]);

        foreach ($preorder->itemPenawarans as $itemPenawaran) {
            $itemPenawaran->update([
                'order_id' => $order->id
            ]);
        }

        return redirect('/order/' . $order->id)->with('message', 'Data order berhasil dibuat');
    }

    public function kontrakStore(Harga $harga)
    {
        request()->validate([
            'check_items' => 'required|array|min:1',
            'suplier_id' => 'required',
            'gudang_id' => 'required',
            'jenis_id' => 'required',
            'tanggal_po' => 'required',
            'target_kirim' => 'required',
            'items' => 'required|array|min:' . count($harga->itemPenawarans),
        ]);

        $kontrak = Kontrak::firstOrCreate([
            'status' => 'order',
            'suplier_id' => request()->suplier_id,
            'gudang_id' => request()->gudang_id,
            'jenis_id' => request()->jenis_id,
            'user_id' => Auth::user()->id,
            'tanggal_mulai' => request()->tanggal_po,
            'tanggal_akhir' => request()->target_kirim,
            'token' => Str::random(40),
            'harga_id' => $harga->id
        ]);

        $kontrak->update([
            'link_token' => url('/nonpo/' . $kontrak->id . '?token=' . $kontrak->token),
        ]);

        $total_biaya = 0.00;
        foreach ($harga->itemPenawarans as $index => $itemPenawaran) {
            $total_harga = 0.00;
            foreach (request()->check_items as $check_item) {
                if ($check_item == $itemPenawaran->id) {
                    $total_harga = str_replace(',', '.', request()->items[$index]['jumlah']) * $itemPenawaran->harga;
                    $total_biaya += $total_harga;


                    $itemPenawaran = ItemPenawaran::where('id', '=', $check_item)->first();

                    ItemPenawaran::create([
                        'kontrak_id' => $kontrak->id,
                        'item_id' => $itemPenawaran->item_id,
                        'satuan_id' => $itemPenawaran->satuan_id,
                        'suplier_id' => $kontrak->suplier_id,
                        'total_harga' => $total_harga,
                        'harga' => $itemPenawaran->harga,
                        'jumlah' => floatval(str_replace(',', '.', request()->items[$index]['jumlah'])),
                    ]);
                }
            }
        }

        $order = Order::firstOrCreate([
            'kontrak_id' => $kontrak->id,
            'gudang_id' => $kontrak->gudang_id,
            'user_id' => $kontrak->user_id,
            'suplier_id' => $kontrak->suplier_id,
            'token' => Str::random(40),
            'isKontrak' => true,
            'jenis_id' => $kontrak->jenis_id,
            'total_biaya' => $total_biaya,
            'target_kirim' => $kontrak->tanggal_akhir,
        ]);

        $order->update([
            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
            'created_at' => Carbon::parse(request()->tanggal_po)->toDateTimeString(),
            'kode' => $order->jenis->kode . $order->id,
        ]);

        $kontrak->update([
            'order_id' => $order->id,
            'total_biaya' => $total_biaya,
        ]);

        foreach ($kontrak->itemPenawarans as $itemPenawaran) {
            $itemPenawaran->update([
                'order_id' => $order->id
            ]);
        }

        return redirect('/order/' . $order->id)->with('message', 'Data order berhasil dibuat');
    }

    public function uploadInvoice(Request $request)
    {
        if ($request->uploadInvoice) {
            $invoice = $request->uploadInvoice;
            // Store the pdf
            $inoviceName = time() . '.' . $invoice->extension();
            // Get the full URL or path of and store the pdf
            // server
            $destinationPath = '../public_html/folder-image-truenas';
            // lokal
            // $destinationPath = public_path('folder-image-truenas');

            $invoice->move($destinationPath, $inoviceName);
            $path = $inoviceName;

            if (!$path) {
                return back()->with('erroruploadfile', 'Upload gambar gagal, harap coba lagi');
            }
        }

        return $path ?? null;
    }

    function uploadFoto(Request $request)
    {
        if ($request->foto) {
            $foto = $request->foto;
            // Store the image
            $imageName = time() . '.' . $foto->extension();
            // Get the full URL or path of and store the image
            // server
            $destinationPath = '../public_html/folder-image-truenas';
            // lokal
            // $destinationPath = public_path('folder-image-truenas');

            $foto->move($destinationPath, $imageName);
            $path = $imageName;

            if (!$path) {
                return back()->with('erroruploadfile', 'Upload gambar gagal, harap coba lagi');
            }
        }

        return $path ?? null;
    }

    function kontrakDiterima(Order $order)
    {
        // update data kontrak
        $order->kontrak->update([
            'status' => 'selesai',
            'order_id' => null,
        ]);

        // update itempenawaran dengan memisahkan dengan table kontrak
        foreach ($order->kontrak->itemPenawarans as $_ => $itemPenawaran) {
            $newItemPenawaran = ItemPenawaran::create([
                "pre_order_id" => $itemPenawaran->pre_order_id,
                "kontrak_id" => null,
                "item_id" => $itemPenawaran->item_id,
                "satuan_id" => $itemPenawaran->satuan_id,
                "order_id" => $itemPenawaran->order_id,
                "isRevisi" => $itemPenawaran->isRevisi,
                "gambar" => $itemPenawaran->gambar,
                "gambar_bukti_gudang" => $itemPenawaran->gambar_bukti_gudang,
                "jumlah_revisi" => $itemPenawaran->jumlah_revisi,
                "harga" => $itemPenawaran->harga,
                "jumlah" => $itemPenawaran->jumlah,
                "total_harga" => $itemPenawaran->total_harga,
                "suplier_id" => $itemPenawaran->suplier_id,
            ]);

            foreach ($itemPenawaran->bukti_gudangs as $_ => $bukti_gudang) {
                $bukti_gudang->update([
                    'item_penawaran_id' => $newItemPenawaran->id,
                ]);
            }

            $itemPenawaran->update([
                'isRevisi' => false,
                'gambar_bukti_gudang' => null,
                'jumlah_revisi' => null,
                'order_id' => null,
            ]);
        }
    }

    public function update(Order $order)
    {
        $token = request()->query('token');

        // update dengan token
        if ($token) {
            if ($token != $order->token) {
                return abort(403, 'Invalid token or access denied.');
            }

            // update buat kontrak
            if ($order->isKontrak) {
                // ketika terkirim ke suplier
                if ($order->status == 'terkirim') {
                    // ketika diterima suplier
                    if (request()->status != 'ditolak') {
                        // request()->validate([
                        //     'uploadInvoice' => 'required|mimes:pdf|max:2048', // 2048 KB = 2 MB
                        // ]);

                        request()->validate([
                            'uploadInvoice' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096', // 4096 KB = 4 MB
                            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                        ]);

                        $oldToken = $order->token;
                        try {
                            $this->kirimGudang($order);
                        } catch (\Throwable $th) {
                            $order->update([
                                'status' => 'terkirim',
                                'token' => $oldToken,
                                'foto' => null,
                                'invoice_suplier' => null,
                                'catatan_suplier' => null,
                                'link_token' => url('/order/' . $order->id . '?token=' . $oldToken),
                            ]);

                            return abort(403, 'Kirim WA error, coba kirim lagi!');
                        }

                        $pathFoto = $this->uploadFoto(request());
                        $pathInvoice = $this->uploadInvoice(request());

                        $order->update([
                            'status' => 'onprocess',
                            'token' => Str::random(40),
                            'foto' => $pathFoto,
                            'invoice_suplier' => $pathInvoice,
                            'catatan_suplier' => null,
                        ]);

                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);


                        // ketika ditolak suplier
                    } else {
                        request()->validate([
                            'catatan_suplier' => 'required'
                        ]);

                        $order->update([
                            'status' => 'ditolak',
                            'token' => Str::random(40),
                            'catatan_suplier' => request()->catatan_suplier
                        ]);

                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);
                    }

                    // ketika di gudang
                } elseif ($order->status == 'onprocess') {

                    $minItems = count($order->itemPenawarans);
                    request()->validate([
                        'kesesuaian_item' => 'required|array|min:' . $minItems,
                    ]);

                    foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                        if ($itemPenawaran->bukti_gudangs->isEmpty()) {
                            return back()->with('erroruploadfile', 'Gambar bukti item ke-' . $index + 1 . ' belum terupload');
                        }
                    }

                    $kesesuaian_items = request()->input('kesesuaian_item');
                    $isSalah = false;
                    foreach ($kesesuaian_items as $index => $kesesuaian_item) {
                        if ($kesesuaian_item == 'salah') {
                            $order->itemPenawarans[$index]->update([
                                'isRevisi' => true,
                            ]);

                            $isSalah = true;
                        }
                    }

                    if ($isSalah) {

                        request()->validate([
                            'catatan_gudang' => 'required',
                        ]);

                        $order->update([
                            'status' => 'revisi',
                            'token' => Str::random(40),
                            'catatan_gudang' => request()->catatan_gudang
                        ]);

                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);
                    } else {
                        foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                            if ($itemPenawaran->bukti_gudangs->isEmpty()) {
                                return back()->with('errorMessage', 'Item ke-' . $index . ' belum upload gambar');
                            }
                        }

                        if ($order->jenis->isStokable) {
                            if ($order->jenis->nama == 'INVENTARIS') {
                                try {
                                    $this->postInventaris($order);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                    return back()->with('errorMessage', 'Tambah Inventaris Gagal, Harap Ulangi Lagi!');
                                }
                            } else {
                                try {
                                    $this->postStok($order);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                    return back()->with('errorMessage', 'Tambah Stok Gagal, Harap Ulangi Lagi!');
                                }
                            }
                        }

                        // try {
                        //     //code...
                        //     $this->kirimSpreadsheetKPI($order);
                        // } catch (\Throwable $th) {
                        //     //throw $th;
                        //     return back()->with('errorMessage', 'Kirim Spreadsheet Gagal, Harap Ulangi Lagi!');
                        // }

                        $order->update([
                            'status' => 'diterima',
                            'token' => Str::random(40),
                            'tgl_selesai' => Carbon::today(),
                        ]);

                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);


                        $this->kontrakDiterima($order);
                    }
                }
                // update untuk pre order
            } else {
                if ($order->status == 'terkirim') {

                    if (request()->status != 'ditolak') {
                        // request()->validate([
                        //     'uploadInvoice' => 'required|mimes:pdf|max:2048', // 2048 KB = 2 MB
                        // ]);

                        request()->validate([
                            'uploadInvoice' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096', // 4096 KB = 4 MB
                            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                        ]);

                        $oldToken = $order->token;
                        try {
                            $this->kirimGudang($order);
                        } catch (\Throwable $th) {
                            $order->update([
                                'status' => 'terkirim',
                                'token' => $oldToken,
                                'foto' => null,
                                'invoice_suplier' => null,
                                'catatan_suplier' => null,
                                'link_token' => url('/order/' . $order->id . '?token=' . $oldToken),
                            ]);

                            return abort(403, 'Kirim WA error, coba kirim lagi!');
                        }

                        $pathFoto = $this->uploadFoto(request());
                        $pathInvoice = $this->uploadInvoice(request());

                        $order->update([
                            'status' => 'onprocess',
                            'token' => Str::random(40),
                            'foto' => $pathFoto,
                            'invoice_suplier' => $pathInvoice,
                            'catatan_suplier' => null
                        ]);

                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);
                    } else {
                        request()->validate([
                            'catatan_suplier' => 'required'
                        ]);

                        $order->update([
                            'status' => 'ditolak',
                            'token' => Str::random(40),
                            'catatan_suplier' => request()->catatan_suplier
                        ]);

                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);
                    }
                } elseif ($order->status == 'onprocess') {
                    $minItems = count($order->itemPenawarans);
                    request()->validate([
                        'kesesuaian_item' => 'required|array|min:' . $minItems,
                    ]);

                    foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                        if ($itemPenawaran->bukti_gudangs->isEmpty()) {

                            return back()->with('erroruploadfile', 'Gambar bukti item ke-' . $index + 1 . ' belum terupload');
                        }
                    }

                    $kesesuaian_items = request()->input('kesesuaian_item');
                    $isSalah = false;
                    foreach ($kesesuaian_items as $index => $kesesuaian_item) {
                        if ($kesesuaian_item == 'salah') {
                            $order->itemPenawarans[$index]->update([
                                'isRevisi' => true,
                            ]);

                            $isSalah = true;
                        }
                    }

                    if ($isSalah) {
                        request()->validate([
                            'catatan_gudang' => 'required',
                        ]);

                        $order->update([
                            'status' => 'revisi',
                            'token' => Str::random(40),
                            'catatan_gudang' => request()->catatan_gudang
                        ]);

                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);
                    } else {
                        foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                            if ($itemPenawaran->bukti_gudangs->isEmpty()) {
                                return back()->with('errorMessage', 'Item ke-' . $index . ' belum upload gambar');
                            }
                        }

                        if ($order->jenis->isStokable) {
                            if ($order->jenis->nama == 'INVENTARIS') {
                                try {
                                    $this->postInventaris($order);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                    return back()->with('errorMessage', 'Tambah Inventaris Gagal, Harap Ulangi Lagi!');
                                }
                            } else {
                                try {
                                    $this->postStok($order);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                    return back()->with('errorMessage', 'Tambah Stok Gagal, Harap Ulangi Lagi!');
                                }
                            }
                        }

                        // try {
                        //     //code...
                        //     $this->kirimSpreadsheetKPI($order);
                        // } catch (\Throwable $th) {
                        //     //throw $th;
                        //     return back()->with('errorMessage', 'Kirim Spreadsheet Gagal, Harap Ulangi Lagi!');
                        // }

                        $order->update([
                            'status' => 'diterima',
                            'token' => Str::random(40),
                            'tgl_selesai' => Carbon::today(),
                        ]);


                        $order->update([
                            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                        ]);
                    }
                }
            }

            return redirect('/konfirmasi')->with('message', 'Data terkonfirmasi');
        }

        // update tanpa token (admin)
        if (Auth::user()) {
            // validasi
            $jumlahItemPenawaran = count($order->itemPenawarans);

            if (Auth::user()->level == 'admin') {
                request()->validate([
                    'target_kirim' => 'required',
                    'tanggal_po' => 'required',
                    'gudang_id' => 'required',
                    'jenis_id' => 'required',
                    'suplier_id' => 'required',
                    'user_id' => 'required',
                    'status' => 'required',
                    'item_id' => 'required|array|min:' . $jumlahItemPenawaran,
                    'item_id.*' => 'required',
                    'satuan_id' => 'required|array|min:' . $jumlahItemPenawaran,
                    'satuan_id.*' => 'required',
                    'uploadInvoice' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096', // 4096 KB = 4 MB
                ]);

                if (request()->uploadInvoice) {
                    # code...
                    $pathInvoice = $this->uploadInvoice(request());
                } else {
                    $pathInvoice = $order->invoice_suplier;
                }

                // convert harga to decimal format
                if (request()->harga) {
                    $hargas = request()->input('harga');
                    foreach ($hargas as $index => $harga) {
                        $hargas[$index] = str_replace(',', '.', $harga);
                    }

                    request()->merge(['harga' => $hargas]);
                }

                // convert harga_revisi to decimal format
                if (request()->harga_revisi) {
                    $harga_revisis = request()->input('harga_revisi');
                    foreach ($harga_revisis as $index => $harga_revisi) {
                        $harga_revisis[$index] = str_replace(',', '.', $harga_revisi);
                    }

                    request()->merge(['harga_revisi' => $harga_revisis]);
                }

                // convert harga to decimal format
                if (request()->jumlah) {
                    $jumlahs = request()->input('jumlah');
                    foreach ($jumlahs as $index => $jumlah) {
                        $jumlahs[$index] = str_replace(',', '.', $jumlah);
                    }

                    request()->merge(['jumlah' => $jumlahs]);
                }

                // mengecek keterangan item dengan potongan harga dan convert to decimal format
                // $potongan_hargas = request()->input('potongan_harga');
                // foreach ($potongan_hargas as $index => $potongan_harga) {
                //     $potongan_hargas[$index] = $potongan_harga ? str_replace(',', '.', $potongan_harga) : null;

                //     if ($potongan_harga) {
                //         if (!request()->keterangan[$index]) {
                //             return back()->with('errorMessage', 'Item ke-' . $index + 1 . ' dengan potongan harga, belum ada keterangan');
                //         }
                //     }
                // }
                // request()->merge(['potongan_harga' => $potongan_hargas]);
                // $keterangans = request()->input('keterangan');

                // convert jumlah_revisi to decimal format
                if (request()->jumlah_revisi) {
                    $jumlah_revisis = request()->input('jumlah_revisi');
                    foreach ($jumlah_revisis as $index => $jumlah_revisi) {
                        $jumlah_revisis[$index] = str_replace(',', '.', $jumlah_revisi);
                    }

                    request()->merge(['jumlah_revisi' => $jumlah_revisis]);
                }

                // proses gambar
                request()->validate([
                    'gambar' => 'array',
                    'gambar.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                ]);


                if (request()->gambar) {
                    foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                        $gambar = request()->gambar[$index] ?? null;

                        if ($gambar) {
                            // Store the image
                            $imageName = time() . $index . '.' . $gambar->extension();
                            // Get the full URL or path of and store the image
                            // server
                            $destinationPath = '../public_html/folder-image-truenas';
                            // lokal
                            // $destinationPath = public_path('folder-image-truenas');

                            $gambar->move($destinationPath, $imageName);
                            $path = $imageName;

                            if (!$path) {
                                return back()->with('erroruploadfile', 'Upload gambar gagal, harap coba lagi');
                            }

                            $itemPenawaran->update([
                                'gambar_bukti_gudang' => $path,
                            ]);
                        } else {
                            continue;
                        }
                    }
                }

                if (request()->jumlah_revisi) {
                    $jumlah_revisis = request()->input('jumlah_revisi');
                    foreach ($jumlah_revisis as $index => $jumlah_revisi) {
                        $jumlah_revisis[$index] = str_replace(',', '.', $jumlah_revisi);
                    }

                    request()->merge(['jumlah_revisi' => $jumlah_revisis]);
                }

                // update data items
                foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                    if ($itemPenawaran->isRevisi) {
                        $itemPenawaran->update([
                            'suplier_id' => request()->suplier_id,
                            'item_id' => request()->item_id[$index],
                            'satuan_id' => request()->satuan_id[$index],
                            'jumlah' => request()->jumlah ? floatval(request()->jumlah[$index]) : floatval($itemPenawaran->jumlah),
                            'jumlah_revisi' =>  request()->jumlah_revisi ? floatval(request()->jumlah_revisi[$index]) : floatval($itemPenawaran->jumlah_revisi),
                            'harga' => request()->harga ? floatval(request()->harga[$index]) : floatval($itemPenawaran->harga),
                            'harga_revisi' =>  request()->harga_revisi ? floatval(request()->harga_revisi[$index]) : floatval($itemPenawaran->harga_revisi),
                            // 'potongan_harga' => $potongan_hargas[$index],
                            // 'keterangan' => $keterangans[$index],
                        ]);
                    } else {
                        $itemPenawaran->update([
                            'suplier_id' => request()->suplier_id,
                            'item_id' => request()->item_id[$index],
                            'satuan_id' => request()->satuan_id[$index],
                            'jumlah' =>  request()->jumlah ? floatval(request()->jumlah[$index]) : floatval($itemPenawaran->jumlah),
                            'harga' =>  request()->harga ? floatval(request()->harga[$index]) : floatval($itemPenawaran->harga),
                            // 'potongan_harga' => $potongan_hargas[$index],
                            // 'keterangan' => $keterangans[$index],
                        ]);
                    }
                }

                $total_biaya_admin = 0.0;
                // $total_biaya_potongan_admin = 0.0;
                foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                    $itemPenawaran->update([
                        'total_harga' => number_format(($itemPenawaran->jumlah_revisi ?? $itemPenawaran->jumlah) * ($itemPenawaran->harga_revisi ?? $itemPenawaran->harga), 2, '.', ''),
                        // 'total_harga_potongan' => $potongan_hargas[$index] ? number_format($jumlahs[$index] * $potongan_hargas[$index], 2, '.', '') : null,
                    ]);


                    $total_biaya_admin += $itemPenawaran->total_harga;
                    // $total_biaya_potongan_admin += $itemPenawaran->total_harga - $itemPenawaran->total_harga_potongan ?? 0;
                }

                // update data order
                $newJenis = Jenis::where('id', request()->jenis_id)->first();
                $order->update([
                    'user_id' => request()->user_id,
                    'target_kirim' => request()->target_kirim,
                    'status' => request()->status,
                    'tgl_selesai' => request()->tgl_selesai ?? $order->tgl_selesai,
                    'created_at' => Carbon::parse(request()->tanggal_po),
                    'gudang_id' => request()->gudang_id,
                    'jenis_id' => request()->jenis_id,
                    'kode' => $newJenis->kode . $order->id,
                    'total_biaya' => number_format($total_biaya_admin, 2, '.', ''),
                    // 'total_biaya_potongan' => number_format($total_biaya_potongan_admin, 2, '.', ''),
                    'invoice_suplier' => $pathInvoice,
                ]);
            } else {
                request()->validate([
                    'target_kirim' => 'required',
                    'tanggal_po' => 'required',
                    'gudang_id' => 'required',
                    'jenis_id' => 'required',
                    'jumlah' => 'required|array|min:' . $jumlahItemPenawaran,
                    'jumlah.*' => 'required',
                ]);

                if (count(request()->jumlah) > 0) {
                    $jumlahs = request()->input('jumlah');
                    foreach ($jumlahs as $index => $jumlah) {
                        $jumlahs[$index] = str_replace(',', '.', $jumlah);
                    }

                    request()->merge(['jumlah' => $jumlahs]);

                    // Define validation rules
                    request()->validate([
                        'jumlah' => 'required|array',
                        'jumlah.*' => ['required', 'regex:/^\d+(\.\d{1,3})?$/'], // Regex allows decimals with up to 3 places
                        'jumlah.*' => 'required', // Adjust as needed for your use case
                    ]);

                    // mengecek keterangan item dengan potongan harga
                    // $potongan_hargas = request()->input('potongan_harga');
                    // foreach ($potongan_hargas as $index => $potongan_harga) {
                    //     $potongan_hargas[$index] = $potongan_harga ? str_replace(',', '.', $potongan_harga) : null;

                    //     if ($potongan_harga) {
                    //         if (!request()->keterangan[$index]) {
                    //             return back()->with('errorMessage', 'Item ke-' . $index + 1 . ' dengan potongan harga, belum ada keterangan');
                    //         }
                    //     }
                    // }
                    // request()->merge(['potongan_harga' => $potongan_hargas]);
                    // $keterangans = request()->input('keterangan');

                    // update data kontrak
                    if ($order->isKontrak || $order->isNonpo) {
                        request()->validate([
                            'harga' => 'required|array|min:' . $jumlahItemPenawaran,
                            'harga.*' => 'required'
                        ]);

                        $hargas = request()->input('harga');

                        foreach ($hargas as $index => $harga) {
                            $hargas[$index] = str_replace(',', '.', $harga);
                        }

                        request()->merge(['harga' => $hargas]);

                        request()->validate([
                            'harga' => 'required|array',
                            'harga.*' => ['required', 'regex:/^\d+(\.\d{1,3})?$/'], // Regex allows decimals with up to 3 places
                            'harga.*' => 'required', // Adjust as needed for your use case
                        ]);

                        foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                            $itemPenawaran->update([
                                'harga' => number_format($hargas[$index], 2, '.', ''),
                            ]);

                            $index++;
                        }

                        if ($order->kontrak_id) {
                            $order->kontrak->update([
                                'gudang_id' => request()->gudang_id,
                                'jenis_id' => request()->jenis_id,
                            ]);
                        }
                    }

                    $total_biaya = 0.0;
                    // $total_biaya_potongan = 0.0;
                    foreach ($order->itemPenawarans as $index => $itemPenawaran) {
                        $itemPenawaran->update([
                            'jumlah' => $jumlahs[$index],
                            // 'potongan_harga' => $potongan_hargas[$index],
                            // 'keterangan' => $keterangans[$index],
                            'total_harga' => number_format($jumlahs[$index] * $itemPenawaran->harga, 2, '.', ''),
                            // 'total_harga_potongan' => $potongan_hargas[$index] ? number_format($jumlahs[$index] * $potongan_hargas[$index], 2, '.', '') : null,
                        ]);

                        $total_biaya += $itemPenawaran->total_harga;
                        // $total_biaya_potongan += $itemPenawaran->total_harga - $itemPenawaran->total_harga_potongan ?? 0;
                    }

                    // update data order
                    $newJenis = Jenis::where('id', request()->jenis_id)->first();
                    $order->update([
                        'target_kirim' => request()->target_kirim,
                        'created_at' => Carbon::parse(request()->tanggal_po),
                        'gudang_id' => request()->gudang_id,
                        'jenis_id' => request()->jenis_id,
                        'kode' => $newJenis->kode . $order->id,
                        'total_biaya' => number_format($total_biaya, 2, '.', ''),
                        // 'total_biaya_potongan' => number_format($total_biaya_potongan, 2, '.', ''),
                    ]);
                }
            }

            return back()->with('message', 'Data order berhasil diupdate');
        }
    }

    public function postStok(Order $order)
    {
        $order = Order::with(['itemPenawarans'])->where('id', $order->id)->first();

        $data = $order->toArray();

        // $response = Http::post('http://nh-manajemen-stok.test/api/stok', $data); // lokal
        $response = Http::post('https://inventory.itnh.systems/index.php/api/stok', $data); // server

        // Handling the response
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'API Request failed', 'details' => $response->body(),], $response->status());
        }
    }

    public function postInventaris(Order $order)
    {
        $order = Order::with(['itemPenawarans'])->where('id', $order->id)->first();

        $data = $order->toArray();

        // $response = Http::post('http://nh-manajemen-inventory.test/api/inventaris', $data); // lokal
        $response = Http::post('https://assets.itnh.systems/api/inventaris', $data); // server

        // Handling the response
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        } else {
            return response()->json(['error' => 'API Request failed', 'details' => $response->body(),], $response->status());
        }
    }

    public function revisi(Order $order)
    {
        $token = request()->query('token');

        // update dengan token
        if ($token) {
            if ($token != $order->token) {
                return abort(403, 'Invalid token or access denied.');
            } else {
                if (request()->tolak) {

                    request()->validate([
                        'catatan_suplier' => 'required|min:1'
                    ]);

                    $order->update([
                        'catatan_suplier' => request()->catatan_suplier,
                        'status' => 'revisiditolak'
                    ]);

                    foreach ($order->itemPenawarans as $itemPenawaran) {
                        if ($itemPenawaran->isRevisi) {
                            $itemPenawaran->update([
                                'jumlah_revisi' => null,
                                'harga_revisi' => null,
                            ]);
                        }
                    }

                    return back()->with('message', 'Penolakan revisi terkirim');
                } else {
                    if ($order->isKontrak) {
                        $this->kontrakDiterima($order);
                    }

                    $total_biaya = 0;
                    foreach ($order->itemPenawarans as $_ => $itemPenawaran) {
                        if ($itemPenawaran->isRevisi) {
                            if ($itemPenawaran->harga_revisi != null) {
                                $itemPenawaran->update([
                                    'total_harga' => number_format($itemPenawaran->harga_revisi * $itemPenawaran->jumlah_revisi, 2, '.', ''),
                                ]);
                            } else {
                                $itemPenawaran->update([
                                    'total_harga' => number_format($itemPenawaran->harga * $itemPenawaran->jumlah_revisi, 2, '.', ''),
                                ]);
                            }
                        }

                        $total_biaya += $itemPenawaran->total_harga;
                    }

                    if ($order->jenis->isStokable) {
                        # code...
                        try {
                            //code...
                            $this->postStok($order);
                        } catch (\Throwable $th) {
                            //throw $th;
                            return back()->with('errorMessage', 'Tambah Stok Gagal, Harap Ulangi Lagi!');
                        }
                    }

                    $order->update([
                        'token' => Str::random(40),
                        'status' => 'revisiditerima',
                        'tgl_selesai' => Carbon::today(),
                        'total_biaya' => number_format($total_biaya, 2, '.', ''),
                    ]);

                    $order->update([
                        'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
                    ]);

                    return redirect('/konfirmasi')->with('message', 'Data terkonfirmasi');
                }
            }

            // update oleh admin
        } else {
            if (Auth::user()) {
                if (Auth::user()->level == 'user') {
                    $pesanWARevisi = '*REVISI KETIDAKSESUAIAN PEMESANAN*' .
                        "\n\nKode Order: " . $order->kode .
                        "\n\nPemesan: " . $order->user->nama .
                        "\n\nTanggal rilis: " . explode(' ', $order->created_at)[0] .
                        "\n\nCek daftar data penawaran: " .
                        "\n" . url('/') . '/suplier/list-order?telepon=' . $order->suplier->telepon;

                    try {
                        $this->kirimWA($order->suplier->telepon, $pesanWARevisi);
                    } catch (\Throwable $th) {

                        return abort(403, 'Kirim WA error, coba kirim lagi!');
                    }

                    $jumlah_revisis = request()->input('jumlah_revisi');
                    foreach ($jumlah_revisis as $indexJumlahRevisi => $jumlah_revisi) {
                        $jumlah_revisis[$indexJumlahRevisi] = str_replace(',', '.', $jumlah_revisi);
                    }

                    request()->merge(['jumlah_revisi' => $jumlah_revisis]);

                    request()->validate([
                        'jumlah_revisi' => 'required|array',
                        'jumlah_revisi.*' => ['required', 'regex:/^\d+(\.\d{1,3})?$/'], // Regex allows decimals with up to 3 places
                        'jumlah_revisi.*' => 'required',
                    ]);
                } elseif (Auth::user()->level == 'admin') {
                    $harga_revisis = request()->input('harga_revisi');
                    foreach ($harga_revisis as $indexHargaRevisi => $harga_revisi) {
                        $harga_revisis[$indexHargaRevisi] = str_replace(',', '.', $harga_revisi);
                    }

                    // Update request with modified data
                    request()->merge(['harga_revisi' => $harga_revisis]);

                    // Define validation rules
                    request()->validate([
                        'harga_revisi' => 'required|array',
                        'harga_revisi.*' => ['required', 'regex:/^\d+(\.\d{1,3})?$/'], // Regex allows decimals with up to 3 places
                        'harga_revisi.*' => 'required',
                    ]);
                }

                foreach ($order->itemPenawarans as $_ => $itemPenawaran) {
                    foreach (request()->itempenawaran_id as $indexItemRevisi => $itempenawaran_id) {
                        if (intval($itempenawaran_id) == $itemPenawaran->id) {
                            if (Auth::user()->level == 'admin') {
                                $itemPenawaran->update([
                                    'harga_revisi' => request()->harga_revisi[$indexItemRevisi],
                                ]);
                            } elseif (Auth::user()->level == 'user') {
                                $itemPenawaran->update([
                                    'jumlah_revisi' => request()->jumlah_revisi[$indexItemRevisi],
                                ]);
                            }
                        }
                    }
                }


                if (Auth::user()->level == 'admin') {
                    return back()->with('message', 'Data berhasil diupdate');
                } else {
                    $order->update([
                        'status' => 'revisiterkirim'
                    ]);

                    return back()->with('message', 'Revisi terkirim ke suplier');
                }
            } else {
                return abort(403, 'Invalid token or access denied.');
            }
        }
    }
/*
    public function kirimWA(string $telepon, string $pesan)
    {
        $hp = $telepon;
		$baseUrl = "https://app.wapakrt.my.id/send-message";
		$params = array(
            'api_key' => 'AQFhKB2s01TxBsHoT5v3pvBS9X78VeOu',
            'sender' => '6289503314976',
            'number' => $hp,
            'message' => $pesan
        );
        $url = $baseUrl . '?' . http_build_query($params);

        $response = file_get_contents($url);
    }
*/	
	public function kirimWA(string $telepon, string $pesan)
	{
		$hp = $telepon;
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

    public function kirimSuplier(Order $order)
    {

        $header = $order->status == 'ditolak' ? "*-- REVISI PEMESANAN --*" : '*PEMESANAN*';
        $pesanWA = $header .
            "\n\nKode Order: " . $order->kode .
            "\n\nPemesan: " . $order->user->nama .
            "\n\nTanggal rilis: " . explode(' ', $order->created_at)[0] .
            "\n\nCek daftar data penawaran: " .
            "\n" . url('/') . '/suplier/list-order?telepon=' . $order->suplier->telepon;

        try {
            $this->kirimWA($order->suplier->telepon, $pesanWA);
        } catch (\Throwable $th) {

            return abort(403, 'Kirim WA error, coba kirim lagi!');
        }

        $order->update(['status' => 'terkirim']);

        if ($order->isKontrak) {
            $order->kontrak->update([
                'status' => 'order'
            ]);
        }

        return redirect('/order/' . $order->id)->with('message', 'Pesan WA ke suplier terkirim');;
    }

    public function kirimGudang(Order $order)
    {
        // Kirim WA ke gudang
        $pesanWAGudang = '*PEMESANAN*' .
            "\n\nKode Order: " . $order->kode .
            "\n\nPemesan: " . $order->user->nama .
            "\n\nTanggal rilis: " . explode(' ', $order->created_at)[0] .
            "\n\nCek daftar data order: " .
            "\n" . url('/') . '/gudang/list-order?gudang_id=' . $order->gudang_id;


        try {
            $this->kirimWA($order->gudang->telepon, $pesanWAGudang);
        } catch (\Throwable $th) {

            return abort(403, 'Kirim WA error, coba kirim lagi!');
        }

        // Kirim WA ke Admin
        $concatItemPenawaran = '';

        $index = 0;
        foreach ($order->itemPenawarans as $itemPenawaran) {
            $index++;
            $concatItemPenawaran .= $index . ". " . $itemPenawaran->item->nama . " | Rp " . number_format($itemPenawaran->harga, 2, ',', '.') . "/" . $itemPenawaran->satuan->nama . " | " . str_replace('.', ',', $itemPenawaran->jumlah) . ' ' . $itemPenawaran->satuan->nama . " | " . 'Rp ' . number_format($itemPenawaran->total_harga, 2, ',', '.') . "\n";
        }

        $pesanWAAdmin =
            "Pemesan : " . $order->user->nama .
            "\n\nTgl. PO : " . explode(' ', $order->created_at)[0] .
            "\n\nKode Order : " . $order->kode  .
            "\n\nTarget kirim : " . $order->target_kirim .
            "\n\nSuplier : " . $order->suplier->nama . ' ( ' . $order->suplier->telepon . ' ) ' .
            "\n\nGudang : " . $order->gudang->nama . ' ( ' . $order->gudang->telepon . ' ) ' .
            "\n\nTotal Biaya : Rp " . number_format(floatval($order->total_biaya), 2, ',', '.') .
            "\n\nCetak PDF (Saat sudah final): \n" . url('/') . '/order-pdf/' . $order->id .
            "\n\nList Order (Nama Barang | Harga/Satuan | Jumlah | Total Harga):\n" .
            $concatItemPenawaran;

        try {
            $this->kirimWA($order->user->telepon, $pesanWAAdmin);
        } catch (\Throwable $th) {

            return abort(403, 'Kirim WA error, coba kirim lagi!');
        }

        return redirect('/konfirmasi')->with('message', 'Data terkonfirmasi');
    }

    public function kirimWAGudang(Order $order)
    {
        // Kirim WA ke gudang
        $pesanWAGudang = '*PEMESANAN*' .
            "\n\nKode Order: " . $order->kode .
            "\n\nPemesan: " . $order->user->nama .
            "\n\nTanggal rilis: " . explode(' ', $order->created_at)[0] .
            "\n\nCek daftar data order: " .
            "\n" . url('/') . '/gudang/list-order?gudang_id=' . $order->gudang_id;

        try {
            $this->kirimWA($order->gudang->telepon, $pesanWAGudang);
        } catch (\Throwable $th) {

            return abort(403, 'Kirim WA error, coba kirim lagi!');
        }

        return back()->with('message', 'Pesan WA ke gudang terkirim');
    }

    public function kirimWASuplier(Order $order)
    {
        // Kirim WA ke gudang
        $pesanWASuplier = '*PEMESANAN*' .
            "\n\nKode Order: " . $order->kode .
            "\n\nPemesan: " . $order->user->nama .
            "\n\nTanggal rilis: " . explode(' ', $order->created_at)[0] .
            "\n\nCek daftar data order: " .
            "\n" . url('/') . '/suplier/list-order?telepon=' . $order->suplier->telepon;

        try {
            $this->kirimWA($order->suplier->telepon, $pesanWASuplier);
        } catch (\Throwable $th) {

            return abort(403, 'Kirim WA error, coba kirim lagi!');
        }

        return back()->with('message', 'Pesan WA ke gudang terkirim');
    }

    public function notifSuplier(int $ongoingIndex = 0)
    {
        $notiforder = Order::with('suplier')->where('status', 'terkirim')->where('created_at', '<', Carbon::today())->get()->groupBy('suplier_id');
        foreach ($notiforder as $index => $item) {
            if ($index < $ongoingIndex) {
                continue;
            }

            try {
                $pesanWASuplier = "*SEBANYAK " . count($item) . " DATA ORDER BELUM TERKONFIRMASI*" .
                    "\n\nCek daftar data order: " .
                    "\n" . url('/') . '/suplier/list-order?telepon=' . $item[0]->suplier->telepon;

                $this->kirimWA($item[0]->suplier->telepon, $pesanWASuplier);
            } catch (\Throwable $th) {
                return $this->notifSuplier($index);
            }
        }

        return response()->json([
            'success' => 'Semua notifikasi terkirim',
        ], 200);
    }

    public function notifGudang(int $ongoingIndex = 0)
    {
        $notiforder = Order::with('gudang')->where('status', 'onprocess')->where('created_at', '<', Carbon::today())->get()->groupBy('gudang_id');

        foreach ($notiforder as $index => $item) {
            if ($index < $ongoingIndex) {
                continue;
            }

            try {
                $pesanWAGudang = "*SEBANYAK " . count($item) . " DATA BELUM TERKONFIRMASI*" .
                    "\n\nCek daftar data order: " .
                    "\n" . url('/') . '/suplier/list-order?telepon=' . $item[0]->gudang->telepon;

                $this->kirimWA($item[0]->gudang->telepon, $pesanWAGudang);
            } catch (\Throwable $th) {
                return $this->notifSuplier($index);
            }
        }

        return response()->json([
            'success' => 'Semua notifikasi terkirim',
        ], 200);
    }

    public function destroy(Order $order)
    {
        if ($order->isKontrak) {
            $order->kontrak->update([
                'status' => 'kontrak',
            ]);
        } else {
            $order->pre_order->update([
                'status' => 'diterima',
                'isOrdered' => false,
            ]);

            $itemPenawarans = $order->pre_order->itemPenawarans;

            foreach ($itemPenawarans as $itemPenawaran) {
                $itemPenawaran->update([
                    'jumlah' => null,
                    'total_harga' => null,
                ]);
            }
        }

        $order->delete();

        return redirect('/order')->with('message', 'Data order dicancel');
    }

    public function cancelOrderTerkirim(Order $order)
    {
        $pesanWACancel = '*PEMESANAN DICANCEL*' .
            "\n\nKode Order: " . $order->kode .
            "\n\nPemesan: " . $order->user->nama .
            "\n\nTanggal rilis: " . explode(' ', $order->created_at)[0] .
            "\n\nCek daftar data order: " .
            "\n" . url('/') . '/suplier/list-order?telepon=' . $order->suplier->telepon;

        try {
            $this->kirimWA($order->suplier->telepon, $pesanWACancel);
        } catch (\Throwable $th) {

            return abort(403, 'Kirim WA error, coba kirim lagi!');
        }

        foreach ($order->itemPenawarans as $itemPenawaran) {
            $itemPenawaran->update([
                'jumlah' => null,
                'total_harga' => null,
            ]);
        }

        if ($order->isKontrak) {
            foreach ($order->itemPenawarans as $itemPenawaran) {
                $itemPenawaran->update([
                    'harga' => null,
                ]);
            }
        }

        $order->update([
            'status' => 'preorder',
            'total_biaya' => null,
            'target_kirim' => null,
            'token' => Str::random(40),
        ]);

        $order->update([
            'link_token' => url('/order/' . $order->id . '?token=' . $order->token),
        ]);

        return back()->with('message', 'Data order suplier dicancel');
    }

    public function postDP(Order $order)
    {
        if (Auth::user()->level != 'admin' && ($order->user_id != Auth::user()->id)) {
            # code...
            return abort(403, "Anda Tidak Memiliki Hak Akses!");
        }

        $isDanaCheck = request()->dana_check;

        request()->validate([
            'nominaldp' => 'required',
        ]);

        if (request()->nominaldp > $order->total_biaya) {
            # code...
            return back()->with('errorMessage', 'Nominal DP lebih besar dari total biaya!');
        }

        if ($isDanaCheck) {
            request()->validate([
                'cabang' => 'required',
                'project' => 'required',
                'judul' => 'required',
            ]);

            $success = $this->inputPendanaan($order, request()->judul, request()->project, request()->cabang, request()->nominaldp);
        } else {
            $success = true;
        }

        if ($success) {
            # code...
            if (!$order->dp_1) {
                # code...
                try {
                    //code...
                    $order->update([
                        'dp_1' => floatval(request()->nominaldp),
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;

                    return back()->with('errorMessage', 'Update DP Gagal!');
                }
            } elseif (!$order->dp_2) {
                try {
                    //code...
                    $order->update([
                        'dp_2' => floatval(request()->nominaldp),
                    ]);
                } catch (\Throwable $th) {
                    //throw $th;

                    return back()->with('errorMessage', 'Update DP Gagal!');
                }
            } else {
                return back()->with('errorMessage', 'Maksimal DP 2 Kali!');
            }
        } else {

            return back()->with('errorMessage', 'Update DP Gagal!');
        }

        return back()->with('message', 'Data order DP terkirim ke aplikasi dana');
    }

    public function inputPendanaan(Order $order, $judul, $project, $cabang, $nominaldp)
    {
        date_default_timezone_set('Asia/Jakarta');
        $host    =  "192.168.1.7";
        $dbuser  =  "postgres";
        $dbpass  =  "almukmin";
        $port    =  "5432";
        $dbname  =  "nurul_hayat_new";

        $conn = pg_connect("host='$host' port='$port' dbname='$dbname' user='$dbuser' password='$dbpass'");

        if (!$conn) {
            die("Koneksi gagal: " . pg_last_error());
        }

        // Fungsi untuk membersihkan input dari serangan injeksi SQL
        function anti_injection($input)
        {
            global $conn;
            $clean_input = pg_escape_string($conn, $input);
            return $clean_input;
        }

        $kegiatanWithBidang = JudulDana::where('id_keg', $judul)
            ->with('bidang')
            ->get()
            ->map(function ($kegiatan) {
                return [
                    ...$kegiatan->toArray(),
                    'nik_pj' => $kegiatan->bidang->nik_pj ?? null,
                ];
            });

        $userDana = UserToken::where('id_tkn', Auth::user()->id_token)->first();

        $grand = $nominaldp;

        if ($userDana->id_cabang != 1) {
            $id_ats = JudulDana::where('id_keg', $judul)->first()->create_nik;
        } else {
            $id_ats = UserToken::where('nik', $userDana->nik)->first()->nik_pimp;
        }
        $nik_ats = $id_ats;
        $nik_keu = '201212015';
        $nik_bid = $kegiatanWithBidang[0]['bidang']['nik_pj'];
        if ($grand >= 3000000) {
            $ck_ats = '1';
            $ck_bid = '1';
        } else {
            $ck_ats = '0';
            $ck_bid = '0';
        }

        $timestamp = now()->format('Y-m-d h:i:s');
        $id_bid = $kegiatanWithBidang[0]['id_bid'];
        $project = $project;
        $judul = $judul;
        $cabang = $cabang;

        $id_ang = AnggaranDana::selectRaw('COALESCE(MAX(id_ang), 0) as max_id_ang')->value('max_id_ang');
        $id_a_next = $id_ang + 1;

        try {
            //code...

            // direct postgresql syntax
            $insert_anggaran = "INSERT INTO dana.anggaran (id_ang,jenis, id_pro, id_bid, id_keg, status, create_nik, create_date, oto1_status, oto1_nik,oto2_status, oto2_nik, oto3_status, oto3_nik,id_jurnal,ang_cab,cek_pro,cek_bid) VALUES ('$id_a_next','Rutin','$project','$id_bid','$judul','0','$userDana->nik','$timestamp','0','$nik_ats','0','$nik_bid','0','$nik_keu','0','$cabang','$ck_ats','$ck_bid')";
            $res_angg = pg_query($conn, $insert_anggaran);

            if (!$res_angg) {
                # code...
                return false;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('errorMessage', 'Gagal Membuat Data Anggaran Dana ' . $th);
        }

        try {
            //code...
            $uraian = $order->kode . ' | ' . (!$order->dp_1 ? 'DP 1' : (!$order->dp_2 ? 'DP 2' : '')) . ' | Suplier: ' . $order->suplier->nama . ' | Gudang: ' . $order->gudang->nama . ' | Admin: ' . $order->user->nama . ' | Tanggal PO: ' . Carbon::parse($order->periode_tgl)->format('d/m/Y');
            $jumlah = 1;
            $nominal = floatval($nominaldp);
            $total = floatval($nominaldp) * $jumlah;

            // direct postgresql syntax
            $insert_rincian = "INSERT INTO dana.anggaran_detil (id_ang, uraian, jumlah, nominal, total, status) VALUES ('$id_a_next', '$uraian', '$jumlah', '$nominal', '$total', '1')";
            $res_angg_rinc = pg_query($conn, $insert_rincian);

            if (!$res_angg_rinc) {
                # code...
                return false;
            }
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('errorMessage', 'Gagal Membuat Data Detail Anggaran Dana ' . $th);
        }

        // Membebaskan memori hasil query
        pg_free_result($res_angg);
        pg_free_result($res_angg_rinc);

        // Menutup koneksi ke database
        pg_close($conn);

        $anggaranDanaData = AnggaranDetilDana::where('id_ang', $id_a_next)->first();

        if ($anggaranDanaData) {
            # code...
            $uraianAnggaranDanaData = $anggaranDanaData->uraian;
            $kodeOrder = explode(' | ', $uraianAnggaranDanaData)[0];

            $dataOrder = Order::where('kode', $kodeOrder)->first();

            if ($dataOrder) {
                if (!$order->dp_1) {
                    # code...
                    $order->update([
                        'jurnal_id_dp_1' => $id_a_next,
                    ]);
                } elseif ($order->dp_1 && !$order->dp_2) {
                    $order->update([
                        'jurnal_id_dp_2' => $id_a_next,
                    ]);
                }

                return true;
            } else {
                if (!$order->dp_1) {
                    # code...
                    $order->update([
                        'dp_1' => null,
                    ]);
                } elseif ($order->dp_1 && !$order->dp_2) {
                    $order->update([
                        'dp_2' => null,
                    ]);
                }

                return false;
            }
        } else {
            return false;
        }
    }

    public function grabSpreadsheetKPI()
    {
        $cabang_id = request()->cbg;
        $order_id = request()->ordr;

        $gudangs = Gudang::where('cabang_id', $cabang_id)->get();

        if ($gudangs->isEmpty()) {
            # code...
            return response()->json([
                'message' => 'Data gudang tidak tersedia!',
                'request' => request()->all()
            ], 400);
        }

        $orders = Order::with(['gudang.cabang', 'suplier', 'user'])->whereIn('gudang_id', $gudangs->pluck('id'))
            ->whereIn('status', ['diterima',  'revisiditerima'])
            ->when($order_id, function ($q) use ($order_id) {
                return $q->where('id', '>', $order_id);
            })
            ->get();

        $data = [];
        foreach ($orders as $order) {
            # code...
            $data[] = [
                'order_id' => $order->id,
                'admin' => $order->user->nama,
                'cabang' => $order->gudang->cabang->nama,
                'gudang' => $order->gudang->nama,
                'suplier' => $order->suplier->nama,
                'created_at' => Carbon::parse($order->created_at)->format('d-m-Y'),
                'target_kirim' => Carbon::parse($order->target_kirim)->format('d-m-Y'),
                'tgl_selesai' => $order->tgl_selesai ? Carbon::parse($order->tgl_selesai)->format('d-m-Y') : null,
                'status' =>  $order->tgl_selesai ? (Carbon::parse($order->tgl_selesai)->gt(Carbon::parse($order->target_kirim)) ? 'TERLAMBAT' : 'TEPAT WAKTU') : null,
            ];
        }

        // return response()->json([
        //     'message' => 'Sukses',
        //     'requset' => request()->all(),
        //     'date' => today()->format('d-m-Y'),
        //     'data_count' => count($data),
        //     'data' => $data,
        // ]);

        return response()->json($data);
    }
}
