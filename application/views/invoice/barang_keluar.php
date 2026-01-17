<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            padding: 30px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0040ff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #0040ff;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #004080;
            font-size: 14px;
        }
        
        .invoice-info {
            margin-bottom: 30px;
        }
        
        .invoice-info p {
            font-size: 14px;
            margin: 8px 0;
            line-height: 1.6;
        }
        
        .invoice-info strong {
            display: inline-block;
            width: 180px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        table th {
            background-color: #0040ff;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-size: 13px;
            border: 1px solid #0040ff;
        }
        
        table td {
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #333;
            font-size: 13px;
        }
        
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .signature {
            margin-top: 50px;
        }
        
        .signature p {
            margin-bottom: 80px;
            font-size: 14px;
        }
        
        .print-button {
            text-align: center;
            margin: 20px 0;
        }
        
        .print-button button {
            background-color: #0040ff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin: 0 5px;
        }
        
        .print-button button:hover {
            background-color: #0030cc;
        }
        
        .print-button .btn-back {
            background-color: #666;
        }
        
        .print-button .btn-back:hover {
            background-color: #555;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                padding: 0;
            }
            
            .invoice-container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">🖨️ Print Invoice</button>
        <button class="btn-back" onclick="window.history.back()">⬅️ Kembali</button>
    </div>

    <div class="invoice-container">
        <div class="header">
            <h1>INVOICE BUKTI PENGELUARAN BARANG</h1>
            <p><?php echo $company; ?></p>
        </div>
        
        <div class="invoice-info">
            <p><strong>No ID Transaksi</strong> : <?php echo isset($id_transaksi) ? $id_transaksi : '-'; ?></p>
            <p><strong>Ditunjukan Untuk</strong> : ___________________________</p>
            <p><strong>Tanggal</strong> : <?php echo isset($tanggal_keluar) ? $tanggal_keluar : '-'; ?></p>
            <p><strong>PO Customer</strong> : ___________________________</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 110px;">ID Transaksi</th>
                    <th style="width: 110px;">Tanggal Masuk</th>
                    <th style="width: 110px;">Tanggal Keluar</th>
                    <th style="width: 130px;">Lokasi</th>
                    <th style="width: 140px;">Kode Barang</th>
                    <th style="width: 140px;">Nama Barang</th>
                    <th style="width: 80px;">Satuan</th>
                    <th style="width: 80px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_jumlah = 0;
                if (!empty($invoice_data)): 
                    $no = 1;
                    foreach($invoice_data as $item): 
                        $total_jumlah += $item->jumlah;
                ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $item->id_transaksi; ?></td>
                    <td><?php echo $item->tanggal_masuk; ?></td>
                    <td><?php echo $item->tanggal_keluar; ?></td>
                    <td><?php echo $item->lokasi; ?></td>
                    <td><?php echo $item->kode_barang; ?></td>
                    <td><?php echo $item->nama_barang; ?></td>
                    <td><?php echo $item->satuan; ?></td>
                    <td><?php echo $item->jumlah; ?></td>
                </tr>
                <?php 
                    $no++;
                    endforeach; 
                else: 
                ?>
                <tr>
                    <td colspan="9" style="padding: 50px; text-align: center; color: #999;">
                        Tidak ada data
                    </td>
                </tr>
                <?php endif; ?>
                
                <?php if (!empty($invoice_data)): ?>
                <tr class="total-row">
                    <td colspan="8" style="text-align: center;"><strong>JUMLAH</strong></td>
                    <td><strong><?php echo $total_jumlah; ?></strong></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <div class="signature">
            <p><strong>Mengetahui</strong></p>
            <p><strong>Admin</strong></p>
        </div>
        
        <div style="text-align: center; color: #666; font-size: 12px; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px;">
            Dicetak pada: <?php echo $print_date; ?>
        </div>
    </div>
</body>
</html>
