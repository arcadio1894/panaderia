<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 8pt; }
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 6px 0; }
        .line2 { text-align: center; margin: 6px 0; font-family: monospace; font-weight: bold; letter-spacing: 2px; }
        .line2::after { content: "***********************"; }
        p { margin: 0; padding: 2px 0; }
        img { max-width: 100px; height: auto; display: block; margin: 0 auto; }
        .row { display: flex; justify-content: space-between; align-items: baseline; }
        .h5 { font-size: 14px; }
        .muted { color: #555; font-size: 11px; }
        * { page-break-inside: avoid; page-break-before: auto; page-break-after: auto; }
        /* ancho típico 80mm: 226.8pt. Si tu motor lo respeta, puedes fijarlo: */
        .ticket { width: 210.8pt; margin: 0 auto; }
        /* filas de items */
        .item-row { display: flex; justify-content: space-between; align-items: baseline; gap: 6px; }
        .item-left { flex: 1; }
        .item-right { flex: 0 0 auto; min-width: 70px; text-align: right; }
        .muted { color: #555; font-size: 11px; }
        .sec-title { font-weight: bold; margin-top: 4px; }

        .total {
            font-weight: bold;
            text-align: right;
        }
        .right {
            text-align: right;
        }
        .full-width {
            width: 100%;
        }

        .table-operations {
            width: 100%;
            border-collapse: collapse;
        }

        .table-operations td {
            border: none;
            padding: 2px 0;
        }
    </style>
</head>
<body>
<div class="ticket">
    <p><b>Fecha:</b> {{ now()->format('d/m/Y H:i') }}</p>

    <div class="line"></div>

    @forelse ($sale->details as $detail)
        @php
            $qty   = (float)$detail->quantity;
            $total = round($detail->total, 2);
            $nombre = $detail->material->full_name;
        @endphp
        <p class="bold" style="font-size: 11px;">
            {{ $nombre }} x {{ rtrim(rtrim(number_format($qty,2,'.',''), '0'),'.') }}
            <span style="float:right;">S/ {{ number_format($total, 2, '.', '') }}</span>
        </p>
    @empty
        <p class="muted">— Sin productos registrados —</p>
    @endforelse

    <div class="line"></div>

    <table class="table-operations full-width">
        <tr>
            <td class="total"><b>TOTAL A PAGAR</b></td>
            <td class="total right">S/. {{ number_format($sale->importe_total, 2) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <p class="text-center" style="font-size: 18px; text-align: center"><b>{{ strtoupper($sale->tipoPago->description) }} </b></p>

    <div class="line"></div>

    <p class="bold right">Pago con: S/. {{ number_format($sale->importe_total+$sale->vuelto, 2) }}</p>
    <p class="bold right">Vuelto: S/. {{ number_format($sale->vuelto, 2) }}</p>

    <div class="line2"></div>

    <div class="text-center">
        {{--<p><b>Documento no válido como comprobante</b></p>--}}
        <p class="muted"><b>Sistema Punto de Venta desarrollado por www.venti360.com</b></p>
    </div>

    <div class="line2"></div>
</div>
</body>
</html>