<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Order PDF</title>
</head>

<body>
    <main class="po-container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/nhlogo.png') }}" alt="NH Logo"><span>Manajemen PO</span>
            </div>
        </div>

        <div class="po-title">
            <p>Order - {{ $order->id }}</p>
        </div>

        <div class="supplier-name">
            <p>Tanggal Order: <span>{{ explode(' ', $order->created_at)[0] }}</span></p>
            <p>Kepada Suplier, <br><span>{{ $order->suplier->nama }}</span></p>
        </div>

        <table class="po-table">
            <thead>
                <tr>
                    <th>No. </th>
                    <th>Item</th>
                    <th>Harga/Satuan</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($itemPenawarans as $index => $itemPenawaran)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $itemPenawaran->item->nama }}</td>
                        <td>Rp.
                            {{ $itemPenawaran->harga_revisi ? number_format($itemPenawaran->harga_revisi, 2, ',', '.') : number_format($itemPenawaran->harga, 2, ',', '.') }},-/{{ $itemPenawaran->satuan->nama }}
                        </td>
                        <td>{{ $itemPenawaran->isRevisi ? str_replace('.', ',', $itemPenawaran->jumlah_revisi) : str_replace('.', ',', $itemPenawaran->jumlah) }}
                            {{ $itemPenawaran->satuan->nama }}</td>
                        <td>Rp. {{ number_format($itemPenawaran->total_harga, 2, ',', '.') }},-</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total"><strong>Total: Rp.
                {{ number_format(floatval($order->total_biaya), 2, ',', '.') }},-</strong>
        </p>

        <p class="total"><strong>DP: Rp.
                {{ number_format(floatval($order->dp_1 ?? 0) + floatval($order->dp_2 ?? 0), 2, ',', '.') }},-</strong>
        </p>

        <p class="total"><strong>Grand Total: Rp.
                {{ number_format(floatval($order->total_biaya) - floatval($order->dp_1 ?? 0) - floatval($order->dp_2 ?? 0), 2, ',', '.') }},-</strong>
        </p>

        <p class="sign-off">Admin,</p>
        <p class="sign-off-name">{{ $order->user->nama }}</p>
    </main>
</body>

</html>
