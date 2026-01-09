<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Gudang;
use App\Models\Pembayaran;
use App\Models\Order;
use App\Models\Suplier;
use App\Models\TipePembayaran;
use App\Models\User;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    public function index()
    {
        $filterKode = request()->filterKode;
        $filterStatus = request()->filterStatus;
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
            $filterStatus = null;
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
        })
            ->get();
        $filteredUser = User::when($filterUser, function ($q) use ($filterUser) {
            return $q->where('id', $filterUser);
        })->when($filterCabang, function ($q) use ($filterCabang) {
            return $q->where('cabang_id', $filterCabang);
        })->get();

        $kasirs = User::where('level', 'pembayaran')->when($filterCabang, function ($q) use ($filterCabang) {
            return $q->where('cabang_id', $filterCabang);
        })->get();
        $filteredKasir = User::where('id', $filterKasir)->get();

        $pembayarans = Pembayaran::with(['gudang.cabang', 'user.cabang', 'suplier.cabang', 'orders.itemPenawarans'])->whereBetween('created_at', [$periode_awal, $periode_akhir])
            ->where('arsip_pembayaran_id', '=', null)
            ->when($filterKasir, function ($q) use ($filterKasir) {
                return $q->where('kasir_id', $filterKasir);
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
            'kasirs' => $kasirs,
            'filterKasir' => $filterKasir,
            'filteredKasir' => $filteredKasir,
            'countItemPenawaran' => $countItemPenawaran,
        ];

        return view('pembayaran.index', $variables);
    }

    public function show(Pembayaran $pembayaran)
    {
        $token = request()->query('token');

        if ($token) {
            if ($token != $pembayaran->token) {
                return abort(403, 'Invalid token or access denied.');
            }

            return view('pembayaran.with-token', ['pembayaran' => $pembayaran]);
        }

        if (Auth::user()) {
            if ($pembayaran->status == 'dibayar') {
                return redirect('/arsip/' . $pembayaran->arsip_pembayaran_id);
            }

            $tipePembayarans = TipePembayaran::all();

            $countItemPenawaran = [];
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

            $variables = [
                'pembayaran' => $pembayaran,
                'tipePembayarans' => $tipePembayarans,
                'countItemPenawaran' => $countItemPenawaran
            ];

            if (Auth::user()->level == 'admin') {
                return view('pembayaran.admin-show', $variables);
            } else {
                return view('pembayaran.show', $variables);
            }
        } else {
            return redirect('/login');
        }
    }

    public function create()
    {
        $user = Auth::user();
        $filterGudang = request()->gudang_id;
        $filterSuplier = request()->suplier_id;
        $filterUser = request()->user_id ?? Auth::user()->id;

        $periode_awal = request()->periode_awal ? Carbon::parse(request()->periode_awal) : Carbon::today()->subDay(7);
        $periode_akhir = request()->periode_akhir ? Carbon::parse(request()->periode_akhir)->endOfDay() : Carbon::today()->endOfDay();

        $orders = Order::with(['itemPenawarans.item'])
            ->when($filterGudang, function ($q) use ($filterGudang) {
                return $q->where('gudang_id', $filterGudang);
            })
            ->where('suplier_id', $filterSuplier)
            ->where('pembayaran_id', null)
            ->where('user_id', $filterUser)
            ->whereIn('status', ['diterima', 'revisiditerima'])
            ->whereBetween('created_at', [$periode_awal, $periode_akhir])
            ->latest()
            ->get();

        $variables = [
            'pembayarans' => Pembayaran::all(),
            'orders' => $orders,
            'supliers' => Suplier::all(),
            'gudangs' => Gudang::all(),
            'kasirs' => User::where('level', '=', 'pembayaran')->get() ?? collect(),
            'selectedSuplierId' => $filterSuplier,
            'selectedGudangId' => $filterGudang,
            'user' => $user,
            'periode_awal' => $periode_awal->toDateString(),
            'periode_akhir' => $periode_akhir->toDateString(),
        ];

        return view('pembayaran.create', $variables);
    }

    public function store()
    {
        request()->validate([
            'check_po' => 'required|array|min:1',
            'selected_suplier_id' => 'required',
            'selected_gudang_id' => '',
            'periode_awal' => 'required',
            'periode_akhir' => 'required',
            'kasir_id' => 'required',
            'cabang' => 'required',
            'divisi' => 'required',
            'judul' => 'required',
        ]);

        // update data pembayaran jika terdapat foto
        if (request()->foto) {
            // validate
            request()->validate([
                'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]);
            $foto = request()['foto'];

            // Store the image
            $imageName = time() . '.' . $foto->extension();

            // server
            $destinationPath = '../public_html/folder-image-truenas';
            // lokal
            // $destinationPath = public_path('folder-image-truenas');

            // store the image
            $foto->move($destinationPath, $imageName);
            $path = $imageName;

            if (!$path) {
                return abort(403, 'Upload gambar gagal, harap coba lagi');
            }
        }

        // buat data invoice
        $pembayaran = Pembayaran::firstOrCreate([
            'user_id' => Auth::user()->id,
            'suplier_id' => request()->selected_suplier_id,
            'gudang_id' => request()->selected_gudang_id,
            'periode_tgl' => Carbon::parse(request()->periode_awal)->toDateString(),
            'sampai_tgl' => Carbon::parse(request()->periode_akhir)->toDateString(),
            'token' => Str::random(40),
            'foto' => $path ?? null,
            'kasir_id' => request()->kasir_id,
            'cabang_dana_id' => request()->cabang,
            'divisi_project_dana_id' => request()->divisi,
            'judul_dana_id' => request()->judul,
        ]);

        $pembayaran->update([
            'kode' => 'INV' . $pembayaran->id,
        ]);

        // hitung total biaya pembayaran
        $total_tagihan = 0.00;
        foreach (request()->check_po as $_ => $check_po) {
            $order = Order::where('id', $check_po)->first();

            $order->update([
                'pembayaran_id' => $pembayaran->id
            ]);

            $total_tagihan += (floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0));
        }

        $pembayaran->update([
            'total_tagihan' => number_format($total_tagihan, 2, ',', '.'),
            'link_token' => url('/pembayaran/' . $pembayaran->id . '?token=' . $pembayaran->token),
        ]);

        $this->kirimWA($pembayaran); // disabled

        return redirect('/pembayaran');
    }

    public function update(Pembayaran $pembayaran)
    {
        $token = request()->query('token');

        if ($token) {
            if ($token != $pembayaran->token) {
                return abort(403, 'Invalid token or access denied.');
            }

            $pembayaran->update([
                'status' => 'diterima',
            ]);

            return redirect('/konfirmasi')->with('message', 'Data terkonfirmasi');
        } else {
            if (request()->foto) {
                // validate
                request()->validate([
                    'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                ]);
                $foto = request()['foto'];

                // Store the image
                $imageName = time() . '.' . $foto->extension();

                // server
                $destinationPath = '../public_html/folder-image-truenas';
                // lokal
                // $destinationPath = public_path('folder-image-truenas');

                // store the image
                $foto->move($destinationPath, $imageName);
                $path = $imageName;

                if (!$path) {
                    return abort(403, 'Upload gambar gagal, harap coba lagi');
                }

                $this->hapusGambar($pembayaran);

                $pembayaran->update([
                    'foto' => $path,
                ]);
            }

            if (Auth::user()->level == 'admin') {
                request()->validate([
                    'status' => 'required',
                ]);


                $pembayaran->update([
                    'status' => request()->status,
                ]);
            }

            return back()->with('message', 'Data pembayaran berhasil diupdate');
        }
    }

    function hapusGambar(Pembayaran $pembayaran)
    {
        if ($pembayaran->foto) {
            $filename = $pembayaran->foto;

            // server
            $filePath = '../public_html/folder-image-truenas/' . $filename;

            // lokal
            // $filePath = public_path('folder-image-truenas/' . $filename);

            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
            }
        }
    }
	
	protected function pesanWA(string $telepon, string $pesan)
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

    public function pesanWAAdmin(Pembayaran $pembayaran)
    {
        $concatPreOrders = '';

        foreach ($pembayaran->orders as $index => $order) {
            $concatPreOrders .= $index + 1 . '. ' . $order->id . ' | ' . explode(' ', $order->created_at)[0] . ' | ' . $order->target_kirim . ' | ' . ($order->isKontrak ? 'Kontrak' : 'Penawaran') . ' | ' . $order->suplier->nama . ' | Rp ' . number_format($order->total_biaya, 2, ',', '.') . "\n";
        }

        $pesanAdmin =
            "Berikut Data yang pembayaran Pre Order Nurul Hayat kepada suplier: " .
            "\n\nKode : " . $pembayaran->kode .
            "\n\nSuplier : " . $pembayaran->suplier->nama .
            "\n\nPemesan : " . $pembayaran->user->nama .
            "\n\nGudang : " . ($pembayaran->gudang ? $pembayaran->gudang->nama : '--') .
            "\n\nPeriode : " . $pembayaran->periode_tgl . ' s/d ' . $pembayaran->sampai_tgl .
            // "\n\nCetak PDF : " . url('/') . '/pembayaran-pdf/' . $pembayaran->id .
            "\n\nTotal Biaya : Rp " . $pembayaran->total_tagihan .
            "\n\nRincian Pesanan: " .
            "\nNo. Kode PO | Tanggal PO | Target Kirim | Gudang PO | Suplier | Nominal" . "\n\n" . $concatPreOrders;

        $this->pesanWA($pembayaran->user->telepon, $pesanAdmin);
    }

    public function pesanWASuplier(Pembayaran $pembayaran)
    {
        $concatPreOrders = '';

        foreach ($pembayaran->orders as $index => $order) {
            $concatPreOrders .= $index + 1 . '. ' . $order->id . ' | ' . explode(' ', $order->created_at)[0] . ' | ' . $order->target_kirim . ' | ' . ($order->isKontrak ? 'Kontrak' : 'Penawaran') . ' | ' . $order->suplier->nama . ' | Rp ' . number_format($order->total_biaya, 2, ',', '.') . "\n";
        }

        $pesanSuplier =
            "Berikut Data yang pembayaran Pre Order Nurul Hayat kepada suplier: " .
            "\n\nKode : " . $pembayaran->kode .
            "\n\nSuplier : " . $pembayaran->suplier->nama .
            "\n\nPemesan : " . $pembayaran->user->nama .
            "\n\nGudang : " . ($pembayaran->gudang ? $pembayaran->gudang->nama : '--') .
            "\n\nPeriode : " . $pembayaran->periode_tgl . ' s/d ' . $pembayaran->sampai_tgl .
            "\n\nTotal Biaya : Rp " . $pembayaran->total_tagihan .
            "\n\nRincian Pesanan: " .
            "\nNo. Kode PO | Tanggal PO | Target Kirim | Gudang PO | Suplier | Nominal" . "\n\n" . $concatPreOrders;

        $this->pesanWA($pembayaran->suplier->telepon, $pesanSuplier);
    }


    public function kirimWA(Pembayaran $pembayaran)
    {
        try {
            $this->pesanWAAdmin($pembayaran); // error / tidak tetap terus
        } catch (\Exception $e) {
            // foreach ($pembayaran->orders as $index => $order) {
            //     $order->update([
            //         'pembayaran_id' => null,
            //     ]);
            // }

            // $pembayaran->delete();
            // return abort(403, 'API WA error ke admin, harap refresh');

            try {
                $this->pesanWASuplier($pembayaran);
            } catch (\Exception $e) {
                foreach ($pembayaran->orders as $index => $order) {
                    $order->update([
                        'pembayaran_id' => null,
                    ]);
                }

                $pembayaran->delete();
                return abort(403, 'API WA error ke suplier, harap refresh');
            }

            return;
        }

        try {
            $this->pesanWASuplier($pembayaran);
        } catch (\Exception $e) {
            foreach ($pembayaran->orders as $index => $order) {
                $order->update([
                    'pembayaran_id' => null,
                ]);
            }

            $pembayaran->delete();
            return abort(403, 'API WA error ke suplier, harap refresh');
        }
    }

    public function destroy(Pembayaran $pembayaran)
    {
        foreach ($pembayaran->orders as $index => $order) {
            $order->update([
                'pembayaran_id' => null,
            ]);
        }

        $pembayaran->delete();

        return redirect('/pembayaran')->with('message', 'Penawaran dicancel, semua data order dikembalikan');
    }
}
