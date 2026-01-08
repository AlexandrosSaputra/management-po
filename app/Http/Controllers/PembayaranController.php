<?php

namespace App\Http\Controllers;

use App\Models\AnggaranDana;
use App\Models\AnggaranDetilDana;
use App\Models\Cabang;
use App\Models\CabangDana;
use App\Models\DivisiProjectDana;
use App\Models\Gudang;
use App\Models\JudulDana;
use App\Models\Pembayaran;
use App\Models\Order;
use App\Models\Suplier;
use App\Models\TipePembayaran;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
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

        $cabangsDana = CabangDana::all();
        $divisisDana = DivisiProjectDana::all();
        $judulsDana = JudulDana::where('status', 1)->get();

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
            'cabangsDana' => $cabangsDana,
            'divisisDana' => $divisisDana,
            'judulsDana' => $judulsDana,
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

        $cabangsDana = CabangDana::all();
        $divisisDana = DivisiProjectDana::all();
        $judulsDana = JudulDana::where('status', 1)->get();

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
            'cabangsDana' => $cabangsDana,
            'divisisDana' => $divisisDana,
            'judulsDana' => $judulsDana,
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

        $pendanaanResult = $this->inputPendanaan($pembayaran, $pembayaran->judul_dana_id, $pembayaran->divisi_project_dana_id, $pembayaran->cabang_dana_id);

        return redirect('/pembayaran')->with($pendanaanResult ? 'message' : 'errorMessage', $pendanaanResult ? 'Data pembayaran sudah dibuat!' : 'Data pembayaran sudah dibuat namun tidak masuk ke pendanaan');
    }

    public function inputPendanaan(Pembayaran $pembayaran, $judul, $project, $cabang)
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

        $orders = $pembayaran->orders;
        $grand = 0;
        foreach ($orders as $order) {
            $grand += floatval($order->total_biaya);
        }

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

        $validatedReq['id_pro'] = $project;
        $validatedReq['jenis'] = "Rutin";
        $validatedReq['id_bid'] = $kegiatanWithBidang[0]['id_bid'];
        $validatedReq['id_keg'] = $judul;
        $validatedReq['status'] = 0;
        $validatedReq['create_nik'] = $userDana->nik;
        $validatedReq['create_date'] = now()->format('Y-m-d h:i:s');
        $validatedReq['oto1_status'] = 0;
        $validatedReq['oto1_nik'] = $nik_ats;
        $validatedReq['oto2_status'] = 0;
        $validatedReq['oto2_nik'] = $nik_bid;
        $validatedReq['oto3_status'] = 0;
        $validatedReq['oto3_nik'] = $nik_keu;
        $validatedReq['id_jurnal'] = 0;
        $validatedReq['ang_cab'] = $cabang;
        $validatedReq['cek_pro'] = $ck_ats;
        $validatedReq['cek_bid'] = $ck_bid;

        try {
            //code...
            // laravel eloquent query failed
            // $anggaran = AnggaranDana::create($validatedReq);

            // direct postgresql syntax
            $id_ang = AnggaranDana::selectRaw('COALESCE(MAX(id_ang), 0) as max_id_ang')->value('max_id_ang');
            $id_a_next = $id_ang + 1;
            $insert_anggaran = "INSERT INTO dana.anggaran (id_ang,jenis, id_pro, id_bid, id_keg, status, create_nik, create_date, oto1_status, oto1_nik,oto2_status, oto2_nik, oto3_status, oto3_nik,id_jurnal,ang_cab,cek_pro,cek_bid) VALUES ('$id_a_next','Rutin','$project','$id_bid','$judul','0','$userDana->nik','$timestamp','0','$nik_ats','0','$nik_bid','0','$nik_keu','0','$cabang','$ck_ats','$ck_bid')";
            $res_angg = pg_query($conn, $insert_anggaran);

            if (!$res_angg) {
                # code...
                return back()->with('errorMessage', 'Gagal Membuat Data Anggaran Dana ');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('errorMessage', 'Gagal Membuat Data Anggaran Dana ' . $th);
        }

        try {
            //code...
            foreach ($orders as $index => $order) {
                $uraian = $order->kode . ' | Suplier: ' . $order->suplier->nama . ' | Gudang: ' . $order->gudang->nama . ' | Admin: ' . $order->user->nama . ' | Tanggal PO: ' . Carbon::parse($order->periode_tgl)->format('d/m/Y');
                $jumlah = 1;
                $nominal = floatval((floatval($order->total_biaya) - floatval($order->dp_1 ?? 0)  - floatval($order->dp_2 ?? 0)));
                $total = floatval((floatval($order->total_biaya) - floatval($order->dp_1 ?? 0)  - floatval($order->dp_2 ?? 0))) * $jumlah;

                // laravel eloquent query failed
                // AnggaranDetilDana::create([
                //     'id_ang' => $id_a_next,
                //     'uraian' => $pembayaran->kode . ' | Suplier: ' . $pembayaran->suplier->nama . ' | Gudang: ' . $pembayaran->gudang->nama . ' | Admin: ' . $pembayaran->user->nama . ' | Periode: ' . Carbon::parse($pembayaran->periode_tgl)->format('d/m/Y') . ' s/d ' . Carbon::parse($pembayaran->sampai_tgl)->format('d/m/Y'),
                //     'jumlah' => 1,
                //     'nominal' => floatval(str_replace(',', '.', str_replace('.', '', $pembayaran->total_tagihan))),
                //     'total' => floatval(str_replace(',', '.', str_replace('.', '', $pembayaran->total_tagihan))) * 1,
                //     'status' => '1',
                // ]);

                // direct postgresql syntax
                $insert_rincian = "INSERT INTO dana.anggaran_detil (id_ang, uraian, jumlah, nominal, total, status) VALUES ('$id_a_next', '$uraian', '$jumlah', '$nominal', '$total', '1')";
                $res_angg_rinc = pg_query($conn, $insert_rincian);

                if (!$res_angg_rinc) {
                    # code...
                    return back()->with('errorMessage', 'Gagal Membuat Detail Data Anggaran Dana Index ke-' . $index);
                }
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

        // memastikan data pembayaran sudah masuk ke aplikasi dana dan jika tidak maka statusnya belum masuk ke dana
        $anggaranDanaData = AnggaranDetilDana::where('id_ang', $id_a_next)->first();

        if ($anggaranDanaData) {
            # code...
            $uraianAnggaranDanaData = $anggaranDanaData->uraian;
            $kodeOrder = explode(' | ', $uraianAnggaranDanaData)[0];

            $dataOrder = Order::where('kode', $kodeOrder)->first();

            if ($dataOrder) {
                # code...
                $dataPembayaran = Pembayaran::find($dataOrder->pembayaran_id);

                if ($dataPembayaran->id == $pembayaran->id) {
                    # code...
                    $pembayaran->update([
                        'is_pendanaan' => 1,
                        'anggaran_dana_id' => $id_a_next,
                    ]);

                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function postPendanaan()
    {
        if (Auth::user()->level != 'pembayaran' && Auth::user()->level != 'admin') {
            # code...
            return abort(403, 'Anda tidak memiliki akses');
        }

        request()->validate([
            'check_pembayaran' => 'required|array|min:1',
            'judul' => 'required',
            'project' => 'required',
            'cabang' => 'required',
        ]);

        if (Auth::user()->level == 'pembayaran') {
            $pembayaranKasirs = Pembayaran::where('kasir_id', Auth::user()->id)->pluck('id');

            if (collect(request()->check_pembayaran)->contains(fn($check) => !$pembayaranKasirs->contains($check))) {
                return back()->with('errorMessage', 'Terdapat Pembayaran yang Bukan Untuk Anda');
            }
        }

        $pembayarans = Pembayaran::whereIn('id', request()->check_pembayaran)->get();

        foreach ($pembayarans as $pembayaran) {
            # code...
            try {
                //code...
                $pendanaanResult = $this->inputPendanaan($pembayaran, request()->judul, request()->project, request()->cabang);

                if (!$pendanaanResult) {
                    # code...
                    return back()->with('errorMessage', 'Data Pembayaran ID: ' . $pembayaran->id . ' Gagal Dikirim Ke Aplikasi Dana');
                }
            } catch (\Throwable $th) {
                //throw $th;
                return back()->with('errorMessage', 'Data Pembayaran ID: ' . $pembayaran->id . ' Gagal Dikirim Ke Aplikasi Dana');
            }
        }

        return back()->with('message', 'Data Berhasil Dikirim Ke Aplikasi Dana');
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
	/*
    protected function pesanWA(string $telepon, string $pesan)
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
