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
        
        .editable-field {
            border: none;
            border-bottom: 1px solid #ccc;
            padding: 2px 5px;
            min-width: 300px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        
        .editable-field:focus {
            outline: none;
            border-bottom: 2px solid #0040ff;
            background-color: #f0f8ff;
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
            padding: 80px 8px;
            text-align: center;
            border: 1px solid #333;
            font-size: 13px;
        }
        
        .editable-cell {
            min-height: 80px;
            cursor: text;
            padding: 10px;
        }
        
        .editable-cell:focus {
            outline: 2px solid #0040ff;
            background-color: #f0f8ff;
        }
        
        .editable-cell:empty:before {
            content: attr(data-placeholder);
            color: #999;
        }
        
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .total-row td {
            padding: 12px 8px;
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
        }
        
        .print-button button:hover {
            background-color: #0030cc;
        }
        
        .btn-edit {
            background-color: #28a745;
        }
        
        .btn-edit:hover {
            background-color: #218838;
        }
        
        .btn-back {
            background-color: #666;
        }
        
        .btn-back:hover {
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
        <button class="btn-edit" onclick="addRow()">➕ Tambah Baris</button>
        <button class="btn-back" onclick="window.history.back()">⬅️ Kembali</button>
    </div>

    <div class="invoice-container">
        <div class="header">
            <h1>INVOICE BUKTI PENGELUARAN BARANG</h1>
            <p><?php echo $company; ?></p>
        </div>
        
        <div class="invoice-info">
            <p><strong>No ID Transaksi</strong> : <input type="text" class="editable-field" placeholder="Masukkan ID Transaksi"></p>
            <p><strong>Ditunjukan Untuk</strong> : <input type="text" class="editable-field" placeholder="Masukkan nama penerima"></p>
            <p><strong>Tanggal</strong> : <input type="text" class="editable-field" placeholder="DD/MM/YYYY"></p>
            <p><strong>PO Customer</strong> : <input type="text" class="editable-field" placeholder="Masukkan PO Customer"></p>
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
                <tr>
                    <td contenteditable="true" class="editable-cell" data-placeholder="1"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="ID"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Tgl Masuk"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Tgl Keluar"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Lokasi"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Kode"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Nama Barang"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Satuan"></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Jumlah"></td>
                </tr>
                <tr class="total-row">
                    <td colspan="8" style="text-align: center;"><strong>JUMLAH</strong></td>
                    <td contenteditable="true" class="editable-cell" data-placeholder="Total"></td>
                </tr>
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

    <script>
        function enableEdit() {
            alert('Mode Edit Aktif!\n\nAnda bisa:\n- Klik pada field untuk mengedit\n- Klik pada sel tabel untuk mengisi data\n- Tekan tombol + untuk tambah baris');
        }

        // Tambah baris baru di tabel
        function addRow() {
            const tbody = document.querySelector('table tbody');
            const totalRow = tbody.querySelector('.total-row');
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
                <td contenteditable="true" class="editable-cell" data-placeholder=""></td>
            `;
            
            tbody.insertBefore(newRow, totalRow);
        }

        // Keyboard shortcut: Ctrl+Enter untuk tambah baris
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                addRow();
            }
        });
    </script>
</body>
</html>
