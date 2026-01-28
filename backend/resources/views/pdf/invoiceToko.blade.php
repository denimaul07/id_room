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
                left:250px;
                top:5px;
            }
            #text
            {
                z-index:100;
           
                text-align: center;
                color:black;
                font-size:18px;
                font-weight:bold;
    
            }

            #text2
            {
                z-index:100;
                text-align: center;    
                color:black;
                font-size:18px;
                font-weight:bold;
          
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
                bottom: 100px;
            }

            #footerttd
            {
                width: 100%;
                position: absolute;
                bottom: 110px;
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
        <center><div style="font-size: 20px">Invoice {{ $bulan }}</div></center>
        <center><div style="font-size: 20px">{{ $toko }}</div></center>

        <br>


        <table id="table" style="font-size: 12px">
            <thead>
                <tr>
                    <th width="10px">No.</th>
                    <th width="100px">Customers</th>
                    <th width="55px">Sales Date</th>
                    <th width="70px">Item Name</th>
                    <th width="70px">Varian Size</th>
                    <th width="10px">Qty</th>
                    <th width="60px">Price/Pcs</th>
                    <th width="10px">Disc</th>
                    <th width="30px">Pembayaran</th>
                    <th width="70px">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0; // Inisialisasi total
                
                @endphp
                @foreach ($data as $no => $data)
                <tr>
                    <td scope="row"  style="text-align: center;">{{ $no+1 }}</td>
                    <td>{{ $data->penjualan->pecustomers_name }}</td>
                    <td>{{ $data->penjualan->tanggal_bayar }}</td>
                    <td>{{ $data->product_name }}</td>
                    <td>{{ $data->product_varian }} - {{ $data->product_size }}</td>
                    <td style="text-align: center;">{{ abs($data->qty) }}</td>
                    <td style="text-align: center;">Rp. {{  number_format($data->harga_jual, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $data->discount }} %</td>
                    <td style="text-align: center;">{{ $data->penjualan->cara_bayar }}</td>
                    <td style="text-align: left;">Rp. {{ number_format(($data->harga_jual * (1 - $data->discount / 100) * abs($data->qty)), 0, ',', '.')  }}</td>
                </tr>
                @php
                    // Tambahkan nilai subtotal ke total pada setiap iterasi
                    $subtotal = ($data->harga_jual * (1 - $data->discount / 100) * abs($data->qty));
                    $total += $subtotal;
                @endphp
                @endforeach

        
                <tr>
                    <td colspan="9" style="text-align: center;"><strong>TOTAL AMOUNT DUE FOR {{ $bulan}} </strong></td>
                    <td style="text-align: left;"><strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
                
            </tbody>
        </table>
        <div id="footerttd" style="font-size: 12px;text-align: right;">
            Jakarta, {{ now()->format('d F Y') }}
        </div>
        <table id="footer" style="font-size: 12px">

            <tbody>
                <tr>
                    <td width="20%">
                    
                    </td>

                    <td width="45%">

                    </td>

                    <td width="35%"  style="border: 1px solid black; padding: 10px;">
                        
                        Please Transfer To : <br>
                        <b>Bank BCA 178-777-5888 a/n Nicole Sumali </b>
                        <br>
                        

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