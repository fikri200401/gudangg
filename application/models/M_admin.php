<?php

class M_admin extends CI_Model
{

  public function insert($tabel,$data)
  {
    $this->db->insert($tabel,$data);
  }

  public function select($tabel)
  {
    // Hanya ambil yang tidak dihapus (soft delete)
    if($tabel == 'tb_barang_masuk' || $tabel == 'tb_barang_keluar') {
      $this->db->where('deleted_at IS NULL');
    }
    $query = $this->db->get($tabel);
    return $query->result();
  }

  public function select_deleted($tabel)
  {
    // Ambil yang sudah dihapus
    $this->db->where('deleted_at IS NOT NULL');
    $query = $this->db->get($tabel);
    return $query->result();
  }

  public function cek_jumlah($tabel,$id_transaksi)
  {
    return  $this->db->select('*')
               ->from($tabel)
               ->where('id_transaksi',$id_transaksi)
               ->get();

  }

  public function get_data_array($tabel,$id_transaksi)
  {
    $query = $this->db->select()
                      ->from($tabel)
                      ->where($id_transaksi)
                      ->get();
    return $query->result_array();
  }

  public function get_data($tabel,$id_transaksi)
  {
    $query = $this->db->select()
                      ->from($tabel)
                      ->where($id_transaksi)
                      ->get();
    return $query->result();
  }

  public function update($tabel,$data,$where)
  {
    $this->db->where($where);
    $this->db->update($tabel,$data);
  }

  public function delete($tabel,$where)
  {
    // Soft delete untuk barang masuk dan keluar
    if($tabel == 'tb_barang_masuk' || $tabel == 'tb_barang_keluar') {
      $data = array('deleted_at' => date('Y-m-d H:i:s'));
      $this->db->where($where);
      $this->db->update($tabel, $data);
    } else {
      $this->db->where($where);
      $this->db->delete($tabel);
    }
  }

  public function restore($tabel,$where)
  {
    // Restore barang yang dihapus
    $data = array('deleted_at' => NULL);
    $this->db->where($where);
    $this->db->update($tabel, $data);
  }

  public function mengurangi($tabel,$id_transaksi,$jumlah)
  {
    // Cek dulu apakah data ada
    $this->db->where('id_transaksi', $id_transaksi);
    $query = $this->db->get($tabel);
    
    if($query->num_rows() == 0) {
      log_message('error', 'Kurangi stok GAGAL - Data dengan ID '.$id_transaksi.' tidak ditemukan di tabel '.$tabel);
      return false;
    }
    
    $current_data = $query->row();
    $stok_lama = (int)$current_data->jumlah;
    $stok_baru = $stok_lama - (int)$jumlah;
    
    // Pastikan tidak minus
    if($stok_baru < 0) {
      log_message('error', 'Kurangi stok GAGAL - Stok akan minus. Stok lama: '.$stok_lama.', Kurangi: '.$jumlah);
      return false;
    }
    
    // Update dengan nilai langsung
    $this->db->where('id_transaksi', $id_transaksi);
    $this->db->update($tabel, array('jumlah' => $stok_baru));
    
    $affected = $this->db->affected_rows();
    log_message('info', 'Kurangi stok - Tabel: '.$tabel.', ID: '.$id_transaksi.', Stok lama: '.$stok_lama.', Kurangi: '.$jumlah.', Stok baru: '.$stok_baru.', Affected: '.$affected);
    
    return ($affected > 0);
  }

  public function update_password($tabel,$where,$data)
  {
    $this->db->where($where);
    $this->db->update($tabel,$data);
  }

  public function get_data_gambar($tabel,$username)
  {
    $query = $this->db->select()
                      ->from($tabel)
                      ->where('username_user',$username)
                      ->get();
    return $query->result();
  }

  public function sum($tabel,$field)
  {
    $query = $this->db->select_sum($field)
                      ->from($tabel)
                      ->get();
    return $query->result();
  }

  public function numrows($tabel)
  {
    $query = $this->db->select()
                      ->from($tabel)
                      ->get();
    return $query->num_rows();
  }

  public function kecuali($tabel,$username)
  {
    $query = $this->db->select()
                      ->from($tabel)
                      ->where_not_in('username',$username)
                      ->get();

    return $query->result();
  }

  public function get_barang_keluar_by_kode($kode_barang)
  {
    // Ambil total barang keluar berdasarkan kode barang
    $this->db->select_sum('jumlah');
    $this->db->where('kode_barang', $kode_barang);
    $this->db->where('deleted_at IS NULL');
    $query = $this->db->get('tb_barang_keluar');
    $result = $query->row();
    return $result->jumlah ? $result->jumlah : 0;
  }

  public function tambah_stok($id_transaksi, $jumlah)
  {
    // Cek dulu apakah data barang masuk ada
    $this->db->where('id_transaksi', $id_transaksi);
    $query = $this->db->get('tb_barang_masuk');
    
    if($query->num_rows() > 0) {
      // Jika ada, UPDATE stok yang sudah ada
      $current_data = $query->row();
      $stok_lama = (int)$current_data->jumlah;
      $stok_baru = $stok_lama + (int)$jumlah;
      
      $this->db->where('id_transaksi', $id_transaksi);
      $this->db->update('tb_barang_masuk', array('jumlah' => $stok_baru));
      
      $affected = $this->db->affected_rows();
      log_message('info', 'Tambah stok (UPDATE) - ID: '.$id_transaksi.', Stok lama: '.$stok_lama.', Tambah: '.$jumlah.', Stok baru: '.$stok_baru.', Affected: '.$affected);
      
      return ($affected > 0);
    } else {
      // Jika tidak ada, berarti barang sudah habis dipindahkan ke barang keluar
      // Kita harus INSERT kembali data barang masuk dari barang keluar
      log_message('info', 'Barang masuk dengan ID '.$id_transaksi.' tidak ditemukan, akan di-restore dari barang keluar');
      return false; // Return false agar controller bisa handle restore
    }
  }

  public function restore_barang_masuk_from_keluar($data_barang_keluar)
  {
    // Insert kembali data ke barang masuk dari barang keluar
    $data = array(
      'id_transaksi' => $data_barang_keluar->id_transaksi,
      'tanggal' => $data_barang_keluar->tanggal_masuk,
      'lokasi' => $data_barang_keluar->lokasi,
      'kode_barang' => $data_barang_keluar->kode_barang,
      'nama_barang' => $data_barang_keluar->nama_barang,
      'satuan' => $data_barang_keluar->satuan,
      'jumlah' => $data_barang_keluar->jumlah
    );
    
    $result = $this->db->insert('tb_barang_masuk', $data);
    log_message('info', 'Restore barang masuk - ID: '.$data_barang_keluar->id_transaksi.', Jumlah: '.$data_barang_keluar->jumlah.', Result: '.($result ? 'Success' : 'Failed'));
    
    return $result;
  }
  


}



 ?>
