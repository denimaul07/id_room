@php
    use Illuminate\Support\Facades\Storage;
    $image = Storage::get('logo/invoice.png');
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Purchase Application</title>
        <style>
            #table {
                /* font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; */
                border-collapse: collapse;
                width: 100%;
            }

            #table td, #table th {
                border: 1px solid black;
                padding: 1px;
            }

            /* #table tr:nth-child(even){background-color: #f2f2f2;}

            #table tr:hover {background-color: #ddd;} */

            #table th {
                padding-top: 10px;
                padding-bottom: 10px;
                text-align: center;
                /* background-color: #4CAF50; */
                /* color: white; */
            }

            #container
            {
                height:80px;
                width:100%;
                position:relative;
            }

            #image
            {    
                position:absolute;
                left:260px;
                top:5px;
            }
            #text
            {
                z-index:100;
                position:absolute;    
                color:black;
                font-size:18px;
                font-weight:bold;
                left: 290px;
                top:80px;
            }

            #text2
            {
                z-index:100;
                position:absolute;    
                color:black;
                font-size:12px;
                font-weight:bold;
                right: 117px;
                top:5px;
            }

            #text3
            {
                z-index:100;
                position:absolute;    
                color:black;
                font-size:10px;
                font-weight:bold;
                right: 158px;
                top:18px;
            }

            #text4
            {
                z-index:100;
                position:absolute;    
                color:black;
                font-size:10px;
                right: 84px;
                top:29px;
            }

            #text5
            {
                z-index:100;
                position:absolute;    
                color:black;
                font-size:10px;
                right: 0;
                top:40px;
            }

            #text6
            {
                z-index:100;
                position:absolute;    
                color:black;
                font-size:10px;
                right: 49px;
                top:50px;
            }

            #text7
            {
                z-index:100;
                position:absolute;    
                color:black;
                font-size:10px;
                right: 59px;
                top:60px;
            }

            #footer
            {
                width: 100%;
                position: absolute;
                bottom: 180px;
            }

            #box1{
				width:100%;
                border: 2px black solid;
                border-radius: 5px;
                background: white;
                box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        
			}

        </style>
    </head>
    <body>
        <div id="container">
            <img id="image" src="{{ storage_path('app/public/logo/invoice.png') }}" width="200px"/>

        </div>

        <br>

        <table border="0"  style="font-size: 12px" width="100%" cellpadding="2">
            <tbody>
                <tr>
                    <td width="35%">No . Invoice</td>
                    <td width="85%">: {{ $header->noinvoice }}</td>
                    <td width="35%" style="text-align: left;">Date</td>
                    <td width="55%"  style="text-align: left;">: {{ $header->sales_date }}</td>
                </tr>

                <tr>
                    <td width="15%">Bill To</td>
                    <td>: {{ $header->customers_name }}</td>
                    <td width="15%">Instagram ID</td>
                    <td>: {{ $header->customer->instagram_id ?? '' }} </td>
                </tr>

                <tr>
                    <td width="15%">Ref</td>
                    <td>: {{ $header->reference }}</td>
                    <td width="15%">Address</td>
                    <td>: {{ $header->customer->address ?? '' }}</td>
                </tr>

                <tr>
                    <td width="15%">No. HP</td>
                    <td>: {{ $header->customer->telp ?? '' }}</td>
                    <td width="15%">City</td>
                    <td>: {{ $header->customer->city ?? '' }}</td>
                </tr>
                <tr>
                    <td width="15%">Email Address</td>
                    <td>: {{ $header->customer->email ?? '' }}</td>
                    <td width="15%">Sales Name</td>
                    <td>: {{ $header->customer->type ?? '' }}</td>
                </tr>
            </tbody>
        </table>
        
        
        <br>
        <table id="table" style="font-size: 12px">
            <thead>
                <tr>
                    <th width="10px">No.</th>
                    <th width="70px">Item Name</th>
                    <th width="70px">Varian Size</th>
                    <th width="100px">SKU</th>
                    <th width="10px">Qty</th>
                    <th width="50px">Price/Pcs</th>
                    <th width="10px">Discount</th>
                    <th width="60px">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0; // Inisialisasi total
                
                @endphp
                @foreach ($data as $no => $data)
                <tr>
                    <td scope="row"  style="text-align: center;">{{ $no+1 }}</td>
                    <td>{{ $data->product_name }}</td>
                    <td>{{ $data->product_varian }} - {{ $data->product_size }}</td>
                    <td>{{ $data->product_sku }}</td>
                    <td style="text-align: center;">{{ abs($data->qty) }}</td>
                    <td style="text-align: center;">Rp. {{  number_format($data->harga_jual, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $data->discount }} %</td>
                    <td style="text-align: left;">Rp. {{ number_format(($data->harga_jual * (1 - $data->discount / 100) * abs($data->qty)), 0, ',', '.')  }}</td>
                </tr>
                @php
                    // Tambahkan nilai subtotal ke total pada setiap iterasi
                    $subtotal = ($data->harga_jual * (1 - $data->discount / 100) * abs($data->qty));
                    $total += $subtotal;
                @endphp
                @endforeach

                <tr>
                    <td colspan="7" style="text-align: right;"><strong>Total : </strong></td>
                    <td style="text-align: left;"><strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>

                <tr>
                    <td colspan="7" style="text-align: right;"><strong>Ongkir : </strong></td>
                    <td style="text-align: left;"><strong>Rp. {{ number_format($header->ongkir ?? 0, 0, ',', '.') }}</strong></td>
                </tr>

                <tr>
                    <td colspan="7" style="text-align: right;"><strong>Biaya Layanan : </strong></td>
                    <td style="text-align: left;"><strong>Rp. {{ number_format($header->biaya_layanan ?? 0, 0, ',', '.') }}</strong></td>
                </tr>

                <tr>
                    <td colspan="7" style="text-align: right;"><strong>Biaya Apps : </strong></td>
                    <td style="text-align: left;"><strong>Rp. {{ number_format($header->biaya_apps ?? 0, 0, ',', '.') }}</strong></td>
                </tr>

                <tr>
                    <td colspan="7" style="text-align: right;"><strong>Grand Total : </strong></td>
                    <td style="text-align: left;"><strong>Rp. {{ number_format($total+($header->ongkir ?? 0) + ($header->biaya_layanan ?? 0) + ($header->biaya_apps ?? 0), 0, ',', '.') }}</strong></td>
                </tr>
            
                
                
                
                
                
                
            </tbody>
        </table>

        <table id="footer" style="font-size: 12px">

            <tbody>
                <tr>
                    <td width="20%">
                        <table style="font-size: 12px" class="no-border">
                            <thead>
                                <tr>
                                    <th>Hormat Kami</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td style="text-align: center;"><img src="{{ storage_path('app/public/logo/ttd.png') }}" alt="" width="100px"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">Askara Aktif</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <td width="35%">

                    </td>

                    <td width="45%"  style="border: 1px solid black; padding: 10px;">
                        
                        Pembayaran dengan cara transfer, dapat dilakukan melalui : <br>
                        <b>Bank BCA 178-777-5888 a/n Nicole Sumali </b>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br> 
                        <b>Check out our latest styles on IG : @askara.aktiv</b>
                        <br> 
                        Tag us using your new Askara Aktiv wear!<br>
                        

                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>

<style>
    /* Style to remove border from the table */
    table.no-border {
        border-collapse: collapse;
        border: none;
    }
</style>