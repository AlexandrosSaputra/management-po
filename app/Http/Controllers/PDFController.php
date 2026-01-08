<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public function orderPDF(Order $order)
    {
        if ($order->status != 'diterima' && $order->status != 'revisiditerima') {
            return abort(403, 'Order masih belum final, tunggu konfirmasi admin');
        }

        if ($order->isKontrak) {
            if (count($order->itemPenawarans) <= 0) {
                return abort(403, 'Tidak terdapat orderan');
            }

            $itemPenawarans = $order->itemPenawarans;
        } else {

            $itemPenawarans = $order->itemPenawarans;
        }

        // Load view dengan data
        $pdf = PDF::loadView('pdf.order-pdf', ['order' => $order, 'itemPenawarans' => $itemPenawarans]);

        // Download file PDF
        return $pdf->stream('order-' . $order->id . '.pdf');
    }

    public function pembayaranPDF(Pembayaran $pembayaran)
    {
        if ($pembayaran->status == 'pembayaran' || $pembayaran->status == 'ditolak') {
            return abort(403, 'Terima pembayaran dahulu untuk akses');
        }

        // Load view dengan data
        $pdf = PDF::loadView('pdf.pembayaran-pdf', ['pembayaran' => $pembayaran]);

        // Download file PDF
        return $pdf->stream('pembayaran-' . $pembayaran->id . 'pdf');
    }
}
