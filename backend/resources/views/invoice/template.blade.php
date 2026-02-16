@php
    /*
    |--------------------------------------------------------------------------
    | AUTO SHRINK ENGINE (DomPDF Safe)
    |--------------------------------------------------------------------------
    | Hitung jumlah item untuk menentukan density layout
    */

    $itemCount = 1; // default 1 kamar

    if ($itemCount <= 3) {
        $fontSize = 12;
        $cellPad  = 8;
        $lineH    = 1.5;
        $headerSpace = 20;
    } elseif ($itemCount <= 6) {
        $fontSize = 11;
        $cellPad  = 6;
        $lineH    = 1.4;
        $headerSpace = 14;
    } elseif ($itemCount <= 10) {
        $fontSize = 10;
        $cellPad  = 5;
        $lineH    = 1.3;
        $headerSpace = 10;
    } else {
        // SUPER COMPACT MODE
        $fontSize = 9;
        $cellPad  = 4;
        $lineH    = 1.2;
        $headerSpace = 6;
    }
@endphp


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Receipt</title>

        <style>
            @page {
                margin:18px 22px;
            }

            body{
                font-family: Arial, Helvetica, sans-serif;
                color:#111;
                font-size: {{ $fontSize }}px;
                line-height: {{ $lineH }};
                margin:0;
            }

            table{
                border-collapse:collapse;
                width:100%;
                page-break-inside: avoid;
            }

            tr{ page-break-inside: avoid; }

            td{
                padding: {{ $cellPad }}px;
                vertical-align:top;
            }

            .section-title{
                background:#EDEDED;
                padding: {{ $cellPad }}px {{ $cellPad + 4 }}px;
                font-weight:700;
                font-size: {{ $fontSize + 1 }}px;
            }

            .tight td{
                padding: {{ max($cellPad - 2,2) }}px;
            }

            .no-pad td{ padding:2px 0; }

        </style>
    </head>

    <body>

        {{-- ================= HEADER ================= --}}
        <table>
            <tr>
                <td width="72%" style="padding:{{ $headerSpace }}px 0 {{ $cellPad }}px 0;">
                    <table>
                        <tr>
                            <td width="6" style="background:#000;"></td>
                            <td style="padding-left:12px;">
                                <div style="font-weight:700; letter-spacing:.5px;">
                                    BUKTI PEMBELIAN (RECEIPT)
                                </div>

                                <div style="margin-top:4px;">
                                    <strong>Nomor :</strong> {{ $detail->invoice_code }}
                                </div>

                                <div>
                                    <strong>Tanggal :</strong> {{ $detail->paid_at }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>

                <td width="28%" align="right">
                    <img src="{{ storage_path('app/public/logo/invoice.png') }}" style="width:100%; max-width:240px;">
                </td>
            </tr>
        </table>


        {{-- ================= DETAIL PEMBAYARAN ================= --}}
        <table>
            <tr>
                <td colspan="2" class="section-title">DETAIL PEMBAYARAN</td>
            </tr>
            <tr>
                <td width="50%">
                    <strong>PEMBELIAN MELALUI:</strong> {{ $detail->payment_method }}
                </td>

                <td width="50%" align="right">
                    <strong>DETAIL TRANSAKSI:</strong> {{ $detail->status }}
                </td>
            </tr>
        </table>


        {{-- ================= DATA PEMESAN ================= --}}
        <table>
            <tr>
                <td width="50%" class="section-title">DATA PEMESAN</td>
                <td width="50%" class="section-title">DETAIL PERUSAHAAN</td>
            </tr>

            <tr>
                <td>
                    <table class="no-pad">
                        <tr>
                            <td width="90">Nama</td><td width="10">:</td>
                            <td>{{ $transaction->user->name }}</td>
                        </tr>

                        <tr>
                            <td>Email</td><td>:</td>
                            <td>{{ $transaction->user->email }}</td>
                        </tr>

                        <tr>
                            <td>No. Kontak</td><td>:</td>
                            <td>{{ $transaction->user->phone }}</td>
                        </tr>
                    </table>
                </td>
                
                <td>
                    <table class="no-pad">
                        <tr>
                            <td width="90">Nama</td><td width="10">:</td>
                            <td>PT KAISAR LANGIT GROUP</td>
                        </tr>

                        <tr>
                            <td>NPWP</td><td>:</td>
                            <td>1000000007756439</td>
                        </tr>

                        <tr>
                            <td style="vertical-align:top;">Alamat</td><td>:</td>
                            <td>
                                Jalan A. Yani Patra 2 Jalan Jendral Ahmad Yani No 51<br>
                                Cempaka Putih Jakarta Pusat 10510
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if($transaction->type == 'BOOKING')
        {{-- ================= TAMU ================= --}}
        <table>
            <tr><td class="section-title">TAMU</td></tr>
            <tr>
                <td>
                    @foreach($detail->booking->passengers as $passenger)
                    <div>{{ $passenger->guest_name }}</div>
                    @endforeach
                </td>
            </tr>
        </table>
    
        {{-- ================= DETAIL HOTEL ================= --}}
        <table>
            <tr><td class="section-title">DETAIL HOTEL</td></tr>
            <tr>
            <td>
            <strong>{{ $detail->booking->property->properties }}</strong><br>
            Alamat: {{ $detail->booking->property->address }}<br>

            @if($itemCount <= 6)
            Check-in: {{ $detail->booking->checkin_date }}<br>
            Durasi: {{ $detail->booking->total_nights }} malam
            @endif
            </td>
            </tr>
        </table>

        {{-- ================= DETAIL PEMBELIAN ================= --}}
        <table class="tight">
            <tr>
                <td colspan="6" class="section-title">DETAIL PEMBELIAN</td>
            </tr>

            <tr style="background:#F7F7F7;">
                <td width="5%"><strong>No.</strong></td>
                <td width="18%"><strong>Jenis Barang</strong></td>
                <td width="32%"><strong>Deskripsi</strong></td>
                <td width="7%" align="center"><strong>Jml.</strong></td>
                <td width="18%" align="right"><strong>Harga</strong></td>
                <td width="20%" align="right"><strong>Total</strong></td>
            </tr>

            <tr>
                <td>1</td>
                <td>{{ $transaction->type }}</td>
                <td>
                    {{ $detail->booking->property->properties }} -
                    {{ $detail->booking->room->room_name }}
                </td>
                <td align="center">1</td>
                <td align="right">Rp {{ number_format($detail->booking->room->price,0,',','.') }}</td>
                <td align="right">Rp {{ number_format($detail->booking->room->price,0,',','.') }}</td>
            </tr>
        </table>

        {{-- ================= SUMMARY ================= --}}
        <table style="margin-top:6px;">
            <tr>
                <td width="60%"></td>
                <td width="40%">
                    <table class="tight">
                        <tr>
                            <td>TOTAL</td>
                            <td align="right">Rp {{ number_format($transaction->amount,0,',','.') }}</td>
                        </tr>

                        <tr>
                            <td>Biaya Layanan</td>
                            <td align="right">Rp {{ number_format($transaction->fee,0,',','.') }}</td>
                        </tr>

                        <tr>
                            <td>Tax</td>
                            <td align="right">Rp {{ number_format($detail->booking->tax_amount,0,',','.') }}</td>
                        </tr>

                        <tr>
                            <td>Diskon</td>
                            <td align="right">Rp {{ number_format($detail->booking->discount_amount,0,',','.') }}</td>
                        </tr>

                        <tr>
                            <td style="font-weight:700;">JUMLAH PEMBAYARAN</td>
                            <td align="right" style="font-weight:700;">
                            Rp {{ number_format($transaction->amount + $transaction->fee + $detail->booking->tax_amount - $detail->booking->discount_amount,0,',','.') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @endif

        @if($transaction->type == 'MEMBERSHIP' || $transaction->type == 'TOPUP') 

            @if($transaction->type == 'MEMBERSHIP')
            {{-- ================= DETAIL MEMBERSHIP ================= --}}
            <table>
                <tr><td class="section-title">DETAIL MEMBERSHIP</td></tr>
                <tr>
                    <td>
                        <strong>{{ $detail->membership->title }}</strong><br>
                        {{ $detail->membership->desc }}
                    </td>
                </tr>
            </table>

            {{-- ================= DETAIL PEMBELIAN ================= --}}
            <table class="tight">
                <tr>
                    <td colspan="6" class="section-title">DETAIL PEMBELIAN</td>
                </tr>

                <tr style="background:#F7F7F7;">
                    <td width="5%"><strong>No.</strong></td>
                    <td width="18%"><strong>Jenis Barang</strong></td>
                    <td width="32%"><strong>Deskripsi</strong></td>
                    <td width="7%" align="center"><strong>Jml.</strong></td>
                    <td width="18%" align="right"><strong>Harga</strong></td>
                    <td width="20%" align="right"><strong>Total</strong></td>
                </tr>

                <tr>
                    <td>1</td>
                    <td>{{ $transaction->type }}</td>
                    <td>
                        {{ $detail->membership->title }}<br>
                        @php
                            $membership = optional($transaction->user->userMemberships)->first();
                        @endphp


                        Start Date :
                        {{ $membership ? \Carbon\Carbon::parse($membership->start_date)->format('d M Y') : '-' }}

                        Expire Date :
                        {{ $membership ? \Carbon\Carbon::parse($membership->end_date)->format('d M Y') : '-' }}


                    </td>
                    <td align="center">1</td>
                    <td align="right">Rp {{ number_format($detail->membership->price,0,',','.') }}</td>
                    <td align="right">Rp {{ number_format($detail->membership->price,0,',','.') }}</td>
                </tr>
            </table>

            @endif

            @if($transaction->type == 'TOPUP')
            {{-- ================= DETAIL TOPUP ================= --}}
            <table>
                <tr><td class="section-title">DETAIL TOPUP</td></tr>
                <tr>
                    <td>
                        Topup Saldo Wallet
                    </td>
                </tr>
            </table>

            {{-- ================= DETAIL PEMBELIAN ================= --}}
            <table class="tight">
                <tr>
                    <td colspan="6" class="section-title">DETAIL PEMBELIAN</td>
                </tr>

                <tr style="background:#F7F7F7;">
                    <td width="5%"><strong>No.</strong></td>
                    <td width="18%"><strong>Jenis Barang</strong></td>
                    <td width="32%"><strong>Deskripsi</strong></td>
                    <td width="7%" align="center"><strong>Jml.</strong></td>
                    <td width="18%" align="right"><strong>Harga</strong></td>
                    <td width="20%" align="right"><strong>Total</strong></td>
                </tr>

                <tr>
                    <td>1</td>
                    <td>{{ $transaction->type }}</td>
                    <td>
                        {{ $detail->description }}


                    </td>
                    <td align="center">1</td>
                    <td align="right">Rp {{ number_format($detail->amount,0,',','.') }}</td>
                    <td align="right">Rp {{ number_format($detail->amount,0,',','.') }}</td>
                </tr>
            </table>


            @endif

        
            {{-- ================= SUMMARY ================= --}}
            <table style="margin-top:6px;">
                <tr>
                    <td width="60%"></td>
                    <td width="40%">
                        <table class="tight">
                            <tr>
                                <td>TOTAL</td>
                                <td align="right">Rp {{ number_format($transaction->amount,0,',','.') }}</td>
                            </tr>

                            <tr>
                                <td>Biaya Layanan</td>
                                <td align="right">Rp {{ number_format($transaction->fee,0,',','.') }}</td>
                            </tr>

                            <tr>
                                <td style="font-weight:700;">JUMLAH PEMBAYARAN</td>
                                <td align="right" style="font-weight:700;">
                                Rp {{ number_format($transaction->amount + $transaction->fee ,0,',','.') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        @endif


        {{-- ================= STATUS ================= --}}
        <table style="margin-top:8px;">
            <tr>
                <td>
                    @if($transaction->status == 'PAID')
                    <img src="{{ storage_path('app/public/logo/paid.png') }}" width="110">
                    @else
                    <img src="{{ storage_path('app/public/logo/unpaid.png') }}" width="110">
                    @endif
                </td>
            </tr>
        </table>

    </body>
</html>
