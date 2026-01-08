<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Pembayaran PDF</title>
</head>

<body>
    <main class="inv-container">
        <div class="header">
            <div class="logo">
                <p><span>Pembayaran : <span>{{ $pembayaran->kode }}</span></p>
            </div>
        </div>

        <div class="supplier-name">
            <p><span>Periode : <span>{{ $pembayaran->periode_tgl }} s/d {{ $pembayaran->sampai_tgl }}</span></p>

            <p><span>Gudang : <span>{{ $pembayaran->gudang->nama }} - {{ $pembayaran->gudang->telepon }}</span></p>


            <p>Kepada, <br><span>{{ $pembayaran->user->nama }}</span></p>
        </div>

        <table class="po-table">
            <thead>
                <tr>
                    <th>Kode PO</th>
                    <th>Tanggal PO</th>
                    <th>Tanggal Selesai</th>
                    <th>Target Kirim</th>
                    <th>Jenis PO</th>
                    <th>Suplier</th>
                    <th>Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembayaran->orders as $index => $order)
                    <tr>
                        <td>{{ $order->kode }}</td>
                        <td>{{ explode(' ', $order->created_at)[0] }}</td>
                        <td>{{ explode(' ', $order->updated_at)[0] }}</td>
                        <td>{{ $order->target_kirim }}</td>
                        <td>{{ $order->isKontrak ? 'Kontrak' : 'Penawaran' }}</td>
                        <td>{{ $order->Suplier->nama }}</td>
                        <td>Rp.
                            {{ number_format(floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0), 2, ',', '.') }},-
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total"><strong>Total: Rp. {{ $pembayaran->total_tagihan }},-</strong></p>

        <p class="sign-off">Suplier,</p>
        <p class="sign-off-name">{{ $pembayaran->suplier->nama }}</p>
    </main>
</body>

</html>
