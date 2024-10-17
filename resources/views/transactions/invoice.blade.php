<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <?php
    $style = '
    <style>
        * {
            font-family: "consolas", sans-serif;
        }
        p {
            display: block;
            margin: 3px;
            font-size: 10pt;
        }
        table td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                margin: 0;
                size: 75mm 
    ';
    ?>
    <?php 
    $style .= 
        ! empty($_COOKIE['innerHeight'])
            ? $_COOKIE['innerHeight'] .'mm; }'
            : '}';
    ?>
    <?php
    $style .= '
            html, body {
                width: 70mm;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
    ';
    ?>

    {!! $style !!}
</head>
<body onload="window.print()">
    <button class="btn-print" style="position: absolute; right: 1rem; top: rem;" onclick="window.print()">Print</button>
    <div class="text-center">
        <h3 style="margin-bottom: 5px;">Kasir Kita</h3>
        <p>Jl. Terusan Kopo No. 385/299</p>
    </div>
    <br>
    <div>
        <p style="float: left;">{{ date('d-m-Y') }}</p>
        <p style="float: right">Kasir 1</p>
    </div>
    <div class="clear-both" style="clear: both;"></div>
    <p>Transaction ID: {{ $transaction->id }}</p>

    <p class="text-center">===================================</p>
    
    <br>
    <table width="100%" style="border: 0;">
        @foreach ($transaction->details as $detail)
            <tr>
                <td colspan="3">{{ $detail->product->name }}</td>
            </tr>
            <tr>
                <td>{{ $detail->quantity }} x {{ number_format($detail->price) }}</td>
                <td></td>
                <td class="text-right">{{ number_format($detail->product->price * $detail->quantity, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>
    <p class="text-center">-----------------------------------</p>

    <table width="100%" style="border: 0;">
        <tr>
            <td>Total Harga:</td>
            <td class="text-right">{{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            @php
                $totalItems = $transaction->details->sum('quantity');
            @endphp
            <td>Total Item:</td>
            <td class="text-right"> {{ $totalItems }} </td>
        </tr>
        <tr>
            <td>Diskon:</td>
            <td class="text-right"> - </td>
        </tr>
        <tr>
            <td>Total Bayar:</td>
            <td class="text-right">{{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali:</td>
            <td class="text-right">{{ number_format($transaction->paid_amount - $transaction->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <p class="text-center">===================================</p>
    <p class="text-center">-- TERIMA KASIH --</p>

    <script>
        let body = document.body;
        let html = document.documentElement;
        let height = Math.max(
                body.scrollHeight, body.offsetHeight,
                html.clientHeight, html.scrollHeight, html.offsetHeight
            );

        document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        document.cookie = "innerHeight="+ ((height + 50) * 0.264583);
    </script>
</body>
</html>
