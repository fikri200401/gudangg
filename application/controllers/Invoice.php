<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property M_admin $M_admin
 */
class Invoice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_admin');
    }

    public function barangKeluarManual()
    {
        $data['title'] = 'Invoice Bukti Pengeluaran Barang';
        $data['company'] = 'Web Aplikasi Gudang';
        $data['print_date'] = date('d/m/Y H:i:s');
        
        // Data kosong untuk invoice manual
        $data['invoice_data'] = array();
        
        $this->load->view('invoice/barang_keluar_manual', $data);
    }

    public function barangKeluar($id = null, $tgl1 = null, $tgl2 = null, $tgl3 = null)
    {
        if (!$id) {
            // Ambil dari URI segment
            $id = $this->uri->segment(3);
            $tgl1 = $this->uri->segment(4);
            $tgl2 = $this->uri->segment(5);
            $tgl3 = $this->uri->segment(6);
        }

        $tanggal_keluar = $tgl1.'/'.$tgl2.'/'.$tgl3;
        $where = array(
            'id_transaksi' => $id,
            'tanggal_keluar' => $tanggal_keluar
        );
        
        $data['title'] = 'Invoice Bukti Pengeluaran Barang';
        $data['company'] = 'Web Aplikasi Gudang';
        $data['print_date'] = date('d/m/Y H:i:s');
        $data['invoice_data'] = $this->M_admin->get_data('tb_barang_keluar', $where);
        $data['id_transaksi'] = $id;
        $data['tanggal_keluar'] = $tanggal_keluar;
        
        $this->load->view('invoice/barang_keluar', $data);
    }
}
