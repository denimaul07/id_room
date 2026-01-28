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
                left: 180px;
                top:85px;
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

            #footerttd
            {
                width: 100%;
                position: absolute;
                bottom: 190px;
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
            <div id="text">Form Serah Terima Barang Askara Aktiv</div>
        </div>

        <br>
        <br>
        <br>
    

        Kepada : {{ $tujuan}}
        <br>
        <table id="table" style="font-size: 12px">
            <thead>
                <tr>
                    <th colspan="5" style="text-align: center;">Product List</th>
                </tr>
                <tr>
                    <th width="10px" style="text-align: center;">No.</th>
                    <th width="100px">SKU</th>
                    <th width="70px">Varian Size</th>
                    <th width="70px">Item Name</th>
                    <th width="10px" style="text-align: center;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rowCount = 0;
                @endphp

    
        
                @foreach ($items as $index => $product)
                    {{ $product}}
                    @if ($rowCount % 35 === 0)
                        @if ($rowCount !== 0)
                            </tbody>
                        </table>
                        <div style="page-break-before: always;"></div>
                        <table id="table" style="font-size: 12px">
                            <thead>
                                <tr>
                                    <th colspan="5" style="text-align: center;">Product List</th>
                                </tr>
                                <tr>
                                    <th width="10px" style="text-align: center;">No.</th>
                                    <th width="100px">SKU</th>
                                    <th width="70px">Varian Size</th>
                                    <th width="70px">Item Name</th>
                                    <th width="10px" style="text-align: center;">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                        @endif
                    @endif
                    <tr>
                        <td style="text-align: center;">{{ $rowCount + 1 }}</td>
                        <td>{{ optional($product->productVarian)->product_sku ?? '-' }}</td>
                        <td>{{ optional($product->productVarian)->product_size ?? '-' }}</td>
                        <td>{{ optional(optional($product->productVarian)->product)->product_name ?? '-' }}</td>
                        <td style="text-align: center;">{{ $product->stock ?? 0 }}</td>
                    </tr>
                    @php
                        $rowCount++;
                    @endphp
                @endforeach
            </tbody>
        </table>
        
        <div id="footerttd" style="font-size: 12px;text-align: right;">
            Jakarta, {{ now()->format('d F Y') }}
        </div>
    
        <table id="footer" style="font-size: 12px">
            <tbody>
                <tr>
                    <td width="20%">
                        <table style="font-size: 12px" class="no-border">
                            <thead>
                                <tr>
                                    <th>Yang Menyerahkan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td style="text-align: center;"><img src="{{ storage_path('app/public/logo/ttd.png') }}" alt="" width="100px"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <td width="65%">

                    </td>

                    <td width="15%">
                    
                        <table style="font-size: 12px" class="no-border">
                            <thead>
                                <tr>
                                    <th>Yang Menerima</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td style="text-align: center;"><img src="{{ storage_path('app/public/logo/ttd.png') }}" alt="" width="100px"></td>
                                </tr>
                                <tr>
                                    <td style="text-align: center;">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                )</td>
                                </tr>
                            </tbody>
                        </table>
                        

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