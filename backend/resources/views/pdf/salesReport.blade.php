<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 10px;
        }
        h2 {
            text-align: center;
            margin: 10px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
        }
        th {
            background-color: #d3d3d3;
            font-weight: bold;
        }
        .store-name {
            text-align: left;
            padding-left: 8px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .section-title {
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 11px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h2>Sales Report {{ $year }}</h2>
    
    <!-- Offline Stores - Quantity Section -->
    <div class="section-title">Sales Quantity by Month - Offline Stores</div>
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">CABANG</th>
                <th style="width: 8%;">JAN</th>
                <th style="width: 8%;">FEB</th>
                <th style="width: 8%;">MARET</th>
                <th style="width: 8%;">APR</th>
                <th style="width: 8%;">MEI</th>
                <th style="width: 8%;">JUN</th>
                <th style="width: 8%;">JUL</th>
                <th style="width: 8%;">AGUST</th>
                <th style="width: 8%;">SEPT</th>
                <th style="width: 8%;">OKT</th>
                <th style="width: 8%;">NOV</th>
                <th style="width: 8%;">DES</th>
                <th style="width: 9%;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stores['offline']['monthlyData'] as $data)
            <tr>
                <td class="store-name">{{ $data['nama_toko'] }}</td>
                @for($m = 1; $m <= 12; $m++)
                    <td>{{ $data['months'][$m] }}</td>
                @endfor
                <td>{{ $data['total_qty'] }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td class="store-name">Total Per Bulan</td>
                @for($m = 1; $m <= 12; $m++)
                    <td>{{ $stores['offline']['monthlyTotalsQty'][$m] }}</td>
                @endfor
                <td>{{ $stores['offline']['grandTotalQty'] }}</td>
            </tr>
        </tbody>
    </table>

        <!-- Online Stores - Quantity Section -->
    <div class="section-title">Sales Quantity by Month - Online Stores</div>
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">CABANG</th>
                <th style="width: 8%;">JAN</th>
                <th style="width: 8%;">FEB</th>
                <th style="width: 8%;">MARET</th>
                <th style="width: 8%;">APR</th>
                <th style="width: 8%;">MEI</th>
                <th style="width: 8%;">JUN</th>
                <th style="width: 8%;">JUL</th>
                <th style="width: 8%;">AGUST</th>
                <th style="width: 8%;">SEPT</th>
                <th style="width: 8%;">OKT</th>
                <th style="width: 8%;">NOV</th>
                <th style="width: 8%;">DES</th>
                <th style="width: 9%;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stores['online']['monthlyData'] as $data)
            <tr>
                <td class="store-name">{{ $data['nama_toko'] }}</td>
                @for($m = 1; $m <= 12; $m++)
                    <td>{{ $data['months'][$m] }}</td>
                @endfor
                <td>{{ $data['total_qty'] }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td class="store-name">Total Per Bulan</td>
                @for($m = 1; $m <= 12; $m++)
                    <td>{{ $stores['online']['monthlyTotalsQty'][$m] }}</td>
                @endfor
                <td>{{ $stores['online']['grandTotalQty'] }}</td>
            </tr>
        </tbody>
    </table>
    
    
    <div class="page-break"></div>

    
    <!-- Offline Stores - Amount Section -->
    <div class="section-title">Sales Amount by Month - Offline Stores</div>
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">CABANG</th>
                <th style="width: 8%;">JAN</th>
                <th style="width: 8%;">FEB</th>
                <th style="width: 8%;">MARET</th>
                <th style="width: 8%;">APR</th>
                <th style="width: 8%;">MEI</th>
                <th style="width: 8%;">JUN</th>
                <th style="width: 8%;">JUL</th>
                <th style="width: 8%;">AGUST</th>
                <th style="width: 8%;">SEPT</th>
                <th style="width: 8%;">OKT</th>
                <th style="width: 8%;">NOV</th>
                <th style="width: 8%;">DES</th>
                <th style="width: 9%;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stores['offline']['monthlyData'] as $data)
            <tr>
                <td class="store-name">{{ $data['nama_toko'] }}</td>
                @for($m = 1; $m <= 12; $m++)
                    <td style="text-align: left;">
                        @if($data['amounts'][$m] > 0)
                            Rp {{ number_format($data['amounts'][$m], 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </td>
                @endfor
                <td>Rp {{ number_format($data['total_amount'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td class="store-name">Total Per Bulan</td>
                @for($m = 1; $m <= 12; $m++)
                    <td>
                        @if($stores['offline']['monthlyTotalsAmount'][$m] > 0)
                            Rp {{ number_format($stores['offline']['monthlyTotalsAmount'][$m], 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </td>
                @endfor
                <td>Rp {{ number_format($stores['offline']['grandTotalAmount'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    
    
    <!-- Online Stores - Amount Section -->
    <div class="section-title">Sales Amount by Month - Online Stores</div>
    <table>
        <thead>
            <tr>
                <th style="width: 12%;">CABANG</th>
                <th style="width: 8%;">JAN</th>
                <th style="width: 8%;">FEB</th>
                <th style="width: 8%;">MARET</th>
                <th style="width: 8%;">APR</th>
                <th style="width: 8%;">MEI</th>
                <th style="width: 8%;">JUN</th>
                <th style="width: 8%;">JUL</th>
                <th style="width: 8%;">AGUST</th>
                <th style="width: 8%;">SEPT</th>
                <th style="width: 8%;">OKT</th>
                <th style="width: 8%;">NOV</th>
                <th style="width: 8%;">DES</th>
                <th style="width: 9%;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stores['online']['monthlyData'] as $data)
            <tr>
                <td class="store-name">{{ $data['nama_toko'] }}</td>
                @for($m = 1; $m <= 12; $m++)
                    <td style="text-align: left;">
                        @if($data['amounts'][$m] > 0)
                            Rp {{ number_format($data['amounts'][$m], 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </td>
                @endfor
                <td>Rp {{ number_format($data['total_amount'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td class="store-name">Total Per Bulan</td>
                @for($m = 1; $m <= 12; $m++)
                    <td>
                        @if($stores['online']['monthlyTotalsAmount'][$m] > 0)
                            Rp {{ number_format($stores['online']['monthlyTotalsAmount'][$m], 0, ',', '.') }}
                        @else
                            Rp 0
                        @endif
                    </td>
                @endfor
                <td>Rp {{ number_format($stores['online']['grandTotalAmount'], 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
