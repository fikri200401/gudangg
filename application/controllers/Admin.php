<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property CI_Input $input
 * @property CI_Upload $upload
 * @property CI_Image_lib $image_lib
 * @property CI_Loader $load
 * @property CI_URI $uri
 * @property M_admin $M_admin
 */
class Admin extends CI_Controller{

  public function __construct(){
		parent::__construct();
    $this->load->model('M_admin');
    $this->load->library('upload');
    
    // Cek session sekali di constructor untuk semua method
    if($this->session->userdata('status') != 'login' || $this->session->userdata('role') != 1){
      redirect('login');
    }
	}

  public function index(){
    // Session sudah dicek di constructor, langsung load data saja
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $data['stokBarangMasuk'] = $this->M_admin->sum('tb_barang_masuk','jumlah');
    $data['stokBarangKeluar'] = $this->M_admin->sum('tb_barang_keluar','jumlah');      
    $data['dataUser'] = $this->M_admin->numrows('user');
    $this->load->view('admin/index',$data);
  }

  public function sigout(){
    session_destroy();
    redirect('login');
  }

  ####################################
              // Profile
  ####################################

  public function profile()
  {
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/profile',$data);
  }

  public function token_generate()
  {
    return $tokens = md5(uniqid(rand(), true));
  }

  private function hash_password($password)
  {
    return password_hash($password,PASSWORD_DEFAULT);
  }

  public function proses_new_password()
  {
    $this->form_validation->set_rules('email','Email','required');
    $this->form_validation->set_rules('new_password','New Password','required');
    $this->form_validation->set_rules('confirm_new_password','Confirm New Password','required|matches[new_password]');

    if($this->form_validation->run() == TRUE)
    {
      if($this->session->userdata('token_generate') === $this->input->post('token'))
      {
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $new_password = $this->input->post('new_password');

        $data = array(
            'email'    => $email,
            'password' => $this->hash_password($new_password)
        );

        $where = array(
            'id' =>$this->session->userdata('id')
        );

        $this->M_admin->update_password('user',$where,$data);

        $this->session->set_flashdata('msg_berhasil','Password Telah Diganti');
        redirect(base_url('admin/profile'));
      }
    }else {
      $this->load->view('admin/profile');
    }
  }

  public function proses_gambar_upload()
  {
    $config =  array(
                   'upload_path'     => "./assets/upload/user/img/",
                   'allowed_types'   => "gif|jpg|png|jpeg",
                   'encrypt_name'    => False, //
                   'max_size'        => "50000",  // ukuran file gambar
                   'max_height'      => "9680",
                   'max_width'       => "9024"
                 );
      $this->load->library('upload',$config);
      $this->upload->initialize($config);

      if( ! $this->upload->do_upload('userpicture'))
      {
        $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
        $this->session->set_flashdata('msg_error_gambar', $this->upload->display_errors());
        $this->load->view('admin/profile',$data);
      }else{
        $upload_data = $this->upload->data();
        $nama_file = $upload_data['file_name'];
        $ukuran_file = $upload_data['file_size'];

        //resize img + thumb Img -- Optional
        $config['image_library']     = 'gd2';
				$config['source_image']      = $upload_data['full_path'];
				$config['create_thumb']      = FALSE;
				$config['maintain_ratio']    = TRUE;
				$config['width']             = 150;
				$config['height']            = 150;

        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config);
				if (!$this->image_lib->resize())
        {
          $data['pesan_error'] = $this->image_lib->display_errors();
          $this->load->view('admin/profile',$data);
        }

        $where = array(
                'username_user' => $this->session->userdata('name')
        );

        $data = array(
                'nama_file' => $nama_file,
                'ukuran_file' => $ukuran_file
        );

        $this->M_admin->update('tb_upload_gambar_user',$data,$where);
        $this->session->set_flashdata('msg_berhasil_gambar','Gambar Berhasil Di Upload');
        redirect(base_url('admin/profile'));
      }
  }

  ####################################
           // End Profile
  ####################################



  ####################################
              // Users
  ####################################
  public function users()
  {
    $data['list_users'] = $this->M_admin->kecuali('user',$this->session->userdata('name'));
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/users',$data);
  }

  public function form_user()
  {
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_users/form_insert',$data);
  }

  public function update_user()
  {
    $id = $this->uri->segment(3);
    $where = array('id' => $id);
    $data['token_generate'] = $this->token_generate();
    $data['list_data'] = $this->M_admin->get_data('user',$where);
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_users/form_update',$data);
  }

  public function proses_delete_user()
  {
    $id = $this->uri->segment(3);
    $where = array('id' => $id);
    $this->M_admin->delete('user',$where);
    $this->session->set_flashdata('msg_berhasil','User Behasil Di Delete');
    redirect(base_url('admin/users'));

  }

  public function proses_tambah_user()
  {
    $this->form_validation->set_rules('username','Username','required');
    $this->form_validation->set_rules('email','Email','required|valid_email');
    $this->form_validation->set_rules('password','Password','required');
    $this->form_validation->set_rules('confirm_password','Confirm password','required|matches[password]');

    if($this->form_validation->run() == TRUE)
    {
      if($this->session->userdata('token_generate') === $this->input->post('token'))
      {

        $username     = $this->input->post('username',TRUE);
        $email        = $this->input->post('email',TRUE);
        $password     = $this->input->post('password',TRUE);
        $role         = $this->input->post('role',TRUE);

        $data = array(
              'username'     => $username,
              'email'        => $email,
              'password'     => $this->hash_password($password),
              'role'         => $role,
        );
        $this->M_admin->insert('user',$data);

        $this->session->set_flashdata('msg_berhasil','User Berhasil Ditambahkan');
        redirect(base_url('admin/form_user'));
        }
      }else {
        $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
        $this->load->view('admin/form_users/form_insert',$data);
    }
  }

  public function proses_update_user()
  {
    $this->form_validation->set_rules('username','Username','required');
    $this->form_validation->set_rules('email','Email','required|valid_email');

    
    if($this->form_validation->run() == TRUE)
    {
      if($this->session->userdata('token_generate') === $this->input->post('token'))
      {
        $id           = $this->input->post('id',TRUE);        
        $username     = $this->input->post('username',TRUE);
        $email        = $this->input->post('email',TRUE);
        $role         = $this->input->post('role',TRUE);

        $where = array('id' => $id);
        $data = array(
              'username'     => $username,
              'email'        => $email,
              'role'         => $role,
        );
        $this->M_admin->update('user',$data,$where);
        $this->session->set_flashdata('msg_berhasil','Data User Berhasil Diupdate');
        redirect(base_url('admin/users'));
       }
    }else{
        $this->load->view('admin/form_users/form_update');
    }
  }


  ####################################
           // End Users
  ####################################



  ####################################
        // DATA BARANG MASUK
  ####################################

  public function form_barangmasuk()
  {
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_barangmasuk/form_insert',$data);
  }

  public function tabel_barangmasuk()
  {
    $data = array(
              'list_data' => $this->M_admin->select('tb_barang_masuk'),
              'avatar'    => $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'))
            );
    $this->load->view('admin/tabel/tabel_barangmasuk',$data);
  }

  public function update_barang($id_transaksi)
  {
    $where = array('id_transaksi' => $id_transaksi);
    $data['data_barang_update'] = $this->M_admin->get_data('tb_barang_masuk',$where);
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_barangmasuk/form_update',$data);
  }

  public function delete_barang($id_transaksi)
  {
    $where = array('id_transaksi' => $id_transaksi);
    $this->M_admin->delete('tb_barang_masuk',$where);
    $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Dihapus');
    redirect(base_url('admin/tabel_barangmasuk'));
  }

  public function tabel_barang_deleted()
  {
    // Tabel barang yang sudah dihapus
    $data = array(
              'list_data' => $this->M_admin->select_deleted('tb_barang_masuk'),
              'avatar'    => $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'))
            );
    $this->load->view('admin/tabel/tabel_barang_deleted',$data);
  }

  public function restore_barang($id_transaksi)
  {
    // Restore barang yang dihapus
    $where = array('id_transaksi' => $id_transaksi);
    $this->M_admin->restore('tb_barang_masuk',$where);
    $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Dikembalikan');
    redirect(base_url('admin/tabel_barang_deleted'));
  }



  public function proses_databarang_masuk_insert()
  {
    $this->form_validation->set_rules('lokasi','Lokasi','required');
    $this->form_validation->set_rules('kode_barang','Kode Barang','required');
    $this->form_validation->set_rules('nama_barang','Nama Barang','required');
    $this->form_validation->set_rules('jumlah','Jumlah','required');

    if($this->form_validation->run() == TRUE)
    {
      $id_transaksi = $this->input->post('id_transaksi',TRUE);
      $tanggal      = $this->input->post('tanggal',TRUE);
      $lokasi       = $this->input->post('lokasi',TRUE);
      $kode_barang  = $this->input->post('kode_barang',TRUE);
      $nama_barang  = $this->input->post('nama_barang',TRUE);
      $satuan       = $this->input->post('satuan',TRUE);
      $jumlah       = $this->input->post('jumlah',TRUE);

      $data = array(
            'id_transaksi' => $id_transaksi,
            'tanggal'      => $tanggal,
            'lokasi'       => $lokasi,
            'kode_barang'  => $kode_barang,
            'nama_barang'  => $nama_barang,
            'satuan'       => $satuan,
            'jumlah'       => $jumlah
      );
      $this->M_admin->insert('tb_barang_masuk',$data);

      $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Ditambahkan');
      redirect(base_url('admin/form_barangmasuk'));
    }else {
      $data['list_satuan'] = $this->M_admin->select('tb_satuan');
      $this->load->view('admin/form_barangmasuk/form_insert',$data);
    }
  }

  public function proses_databarang_masuk_update()
  {
    $this->form_validation->set_rules('lokasi','Lokasi','required');
    $this->form_validation->set_rules('kode_barang','Kode Barang','required');
    $this->form_validation->set_rules('nama_barang','Nama Barang','required');
    $this->form_validation->set_rules('jumlah','Jumlah','required');

    if($this->form_validation->run() == TRUE)
    {
      $id_transaksi = $this->input->post('id_transaksi',TRUE);
      $tanggal      = $this->input->post('tanggal',TRUE);
      $lokasi       = $this->input->post('lokasi',TRUE);
      $kode_barang  = $this->input->post('kode_barang',TRUE);
      $nama_barang  = $this->input->post('nama_barang',TRUE);
      $satuan       = $this->input->post('satuan',TRUE);
      $jumlah       = $this->input->post('jumlah',TRUE);

      $where = array('id_transaksi' => $id_transaksi);
      $data = array(
            'id_transaksi' => $id_transaksi,
            'tanggal'      => $tanggal,
            'lokasi'       => $lokasi,
            'kode_barang'  => $kode_barang,
            'nama_barang'  => $nama_barang,
            'satuan'       => $satuan,
            'jumlah'       => $jumlah
      );
      $this->M_admin->update('tb_barang_masuk',$data,$where);
      $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Diupdate');
      redirect(base_url('admin/tabel_barangmasuk'));
    }else{
      $this->load->view('admin/form_barangmasuk/form_update');
    }
  }
  ####################################
      // END DATA BARANG MASUK
  ####################################


  ####################################
              // SATUAN
  ####################################

  public function form_satuan()
  {
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_satuan/form_insert',$data);
  }

  public function tabel_satuan()
  {
    $data['list_data'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/tabel/tabel_satuan',$data);
  }

  public function update_satuan()
  {
    $uri = $this->uri->segment(3);
    $where = array('id_satuan' => $uri);
    $data['data_satuan'] = $this->M_admin->get_data('tb_satuan',$where);
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_satuan/form_update',$data);
  }

  public function delete_satuan()
  {
    $uri = $this->uri->segment(3);
    $where = array('id_satuan' => $uri);
    $this->M_admin->delete('tb_satuan',$where);
    redirect(base_url('admin/tabel_satuan'));
  }

  public function proses_satuan_insert()
  {
    $this->form_validation->set_rules('kode_satuan','Kode Satuan','trim|required|max_length[100]');
    $this->form_validation->set_rules('nama_satuan','Nama Satuan','trim|required|max_length[100]');

    if($this->form_validation->run() ==  TRUE)
    {
      $kode_satuan = $this->input->post('kode_satuan' ,TRUE);
      $nama_satuan = $this->input->post('nama_satuan' ,TRUE);

      $data = array(
            'kode_satuan' => $kode_satuan,
            'nama_satuan' => $nama_satuan
      );
      $this->M_admin->insert('tb_satuan',$data);

      $this->session->set_flashdata('msg_berhasil','Data satuan Berhasil Ditambahkan');
      redirect(base_url('admin/form_satuan'));
    }else {
      $this->load->view('admin/form_satuan/form_insert');
    }
  }

  public function proses_satuan_update()
  {
    $this->form_validation->set_rules('kode_satuan','Kode Satuan','trim|required|max_length[100]');
    $this->form_validation->set_rules('nama_satuan','Nama Satuan','trim|required|max_length[100]');

    if($this->form_validation->run() ==  TRUE)
    {
      $id_satuan   = $this->input->post('id_satuan' ,TRUE);
      $kode_satuan = $this->input->post('kode_satuan' ,TRUE);
      $nama_satuan = $this->input->post('nama_satuan' ,TRUE);

      $where = array(
            'id_satuan' => $id_satuan
      );

      $data = array(
            'kode_satuan' => $kode_satuan,
            'nama_satuan' => $nama_satuan
      );
      $this->M_admin->update('tb_satuan',$data,$where);

      $this->session->set_flashdata('msg_berhasil','Data satuan Berhasil Di Update');
      redirect(base_url('admin/tabel_satuan'));
    }else {
      $this->load->view('admin/form_satuan/form_update');
    }
  }

  ####################################
            // END SATUAN
  ####################################


  ####################################
     // DATA MASUK KE DATA KELUAR
  ####################################

  public function barang_keluar()
  {
    $uri = $this->uri->segment(3);
    $where = array( 'id_transaksi' => $uri);
    $data['list_data'] = $this->M_admin->get_data('tb_barang_masuk',$where);
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/perpindahan_barang/form_update',$data);
  }

  public function proses_data_keluar()
  {
    $this->form_validation->set_rules('tanggal_keluar','Tanggal Keluar','trim|required');
    if($this->form_validation->run() === TRUE)
    {
      $id_transaksi   = $this->input->post('id_transaksi',TRUE);
      $tanggal_masuk  = $this->input->post('tanggal',TRUE);
      $tanggal_keluar = $this->input->post('tanggal_keluar',TRUE);
      $lokasi         = $this->input->post('lokasi',TRUE);
      $kode_barang    = $this->input->post('kode_barang',TRUE);
      $nama_barang    = $this->input->post('nama_barang',TRUE);
      $satuan         = $this->input->post('satuan',TRUE);
      $jumlah         = $this->input->post('jumlah',TRUE);

      // Validasi: Tanggal keluar harus setelah tanggal masuk
      $tgl_masuk_timestamp = strtotime(str_replace('/', '-', $tanggal_masuk));
      $tgl_keluar_timestamp = strtotime(str_replace('/', '-', $tanggal_keluar));
      
      if($tgl_keluar_timestamp < $tgl_masuk_timestamp) {
        $this->session->set_flashdata('msg_error','Tanggal keluar tidak boleh sebelum tanggal masuk!');
        redirect(base_url('admin/barang_keluar/'.$id_transaksi));
        return;
      }

      $where = array( 'id_transaksi' => $id_transaksi);
      $data = array(
              'id_transaksi' => $id_transaksi,
              'tanggal_masuk' => $tanggal_masuk,
              'tanggal_keluar' => $tanggal_keluar,
              'lokasi' => $lokasi,
              'kode_barang' => $kode_barang,
              'nama_barang' => $nama_barang,
              'satuan' => $satuan,
              'jumlah' => $jumlah
      );
        $this->M_admin->insert('tb_barang_keluar',$data);
        $this->session->set_flashdata('msg_berhasil_keluar','Data Berhasil Keluar');
        redirect(base_url('admin/tabel_barangmasuk'));
    }else {
      $id_transaksi = $this->input->post('id_transaksi',TRUE);
      $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
      $this->load->view('admin/perpindahan_barang/form_update',$data);
    }

  }
  ####################################
    // END DATA MASUK KE DATA KELUAR
  ####################################


  ####################################
        // DATA BARANG KELUAR
  ####################################

  public function tabel_barangkeluar()
  {
    $data['list_data'] = $this->M_admin->select('tb_barang_keluar');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/tabel/tabel_barangkeluar',$data);
  }

  public function update_barangkeluar($id)
  {
    // Form edit barang keluar
    $where = array('id' => $id);
    $data['data_barang_keluar'] = $this->M_admin->get_data('tb_barang_keluar',$where);
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_barangkeluar/form_update',$data);
  }

  public function proses_update_barangkeluar()
  {
    // Proses update barang keluar dan update stok barang masuk
    $id = $this->input->post('id',TRUE);
    $id_transaksi = $this->input->post('id_transaksi',TRUE);
    $jumlah_lama = $this->input->post('jumlah_lama',TRUE);
    $jumlah_baru = $this->input->post('jumlah',TRUE);
    $tanggal_keluar = $this->input->post('tanggal_keluar',TRUE);

    // Validasi jumlah baru harus lebih dari 0
    if($jumlah_baru < 1) {
      $this->session->set_flashdata('msg_error','Jumlah barang keluar harus minimal 1');
      redirect(base_url('admin/update_barangkeluar/'.$id));
      return;
    }

    // Jika jumlah baru lebih besar dari jumlah lama, cek stok barang masuk
    if($jumlah_baru > $jumlah_lama) {
      $selisih_tambah = $jumlah_baru - $jumlah_lama;
      $where_stok = array('id_transaksi' => $id_transaksi);
      $barang_masuk = $this->M_admin->get_data('tb_barang_masuk', $where_stok);
      
      if(!empty($barang_masuk)) {
        $stok_tersedia = $barang_masuk[0]->jumlah;
        if($stok_tersedia < $selisih_tambah) {
          $this->session->set_flashdata('msg_error','Stok barang masuk tidak cukup! Tersedia: '.$stok_tersedia);
          redirect(base_url('admin/update_barangkeluar/'.$id));
          return;
        }
      }
    }

    // Hitung selisih
    $selisih = $jumlah_lama - $jumlah_baru;

    // Update jumlah barang keluar
    $where = array('id' => $id);
    $data = array(
        'jumlah' => $jumlah_baru,
        'tanggal_keluar' => $tanggal_keluar
    );
    $this->M_admin->update('tb_barang_keluar', $data, $where);

    // Update stok barang masuk (kembalikan selisih)
    if($selisih > 0) {
      // Jumlah baru lebih kecil - kembalikan ke barang masuk
      $result = $this->M_admin->tambah_stok($id_transaksi, $selisih);
      
      if($result) {
        $this->session->set_flashdata('msg_berhasil','✓ Data berhasil diupdate. '.$selisih.' unit dikembalikan ke barang masuk (ID: '.$id_transaksi.')');
      } else {
        // Barang masuk tidak ada, ambil data barang keluar untuk restore
        $where_keluar = array('id' => $id);
        $barang_keluar = $this->M_admin->get_data('tb_barang_keluar', $where_keluar);
        
        if(!empty($barang_keluar)) {
          $data_keluar = $barang_keluar[0];
          // Set jumlah ke selisih yang akan dikembalikan
          $data_keluar->jumlah = $selisih;
          $restore = $this->M_admin->restore_barang_masuk_from_keluar($data_keluar);
          
          if($restore) {
            $this->session->set_flashdata('msg_berhasil','✓ Data berhasil diupdate. Data barang masuk (ID: '.$id_transaksi.') telah di-restore dengan '.$selisih.' unit');
          } else {
            $this->session->set_flashdata('msg_error','⚠ Data barang keluar diupdate, tapi GAGAL mengembalikan stok ke barang masuk.');
          }
        }
      }
    } else if($selisih < 0) {
      // Jumlah baru lebih besar - kurangi dari barang masuk
      $result = $this->M_admin->mengurangi('tb_barang_masuk', $id_transaksi, abs($selisih));
      
      if($result) {
        $this->session->set_flashdata('msg_berhasil','✓ Data berhasil diupdate. '.abs($selisih).' unit dikurangi dari barang masuk (ID: '.$id_transaksi.')');
      } else {
        $this->session->set_flashdata('msg_error','⚠ Data barang keluar diupdate, tapi GAGAL mengurangi stok dari barang masuk. ID Transaksi '.$id_transaksi.' tidak ditemukan atau stok tidak cukup!');
      }
    } else {
      // Tidak ada perubahan jumlah
      $this->session->set_flashdata('msg_berhasil','Data Barang Keluar Berhasil Diupdate (tanpa perubahan stok)');
    }

    redirect(base_url('admin/tabel_barangkeluar'));
  }

  public function undo_barangkeluar($id)
  {
    // Undo barang keluar - kembalikan ke barang masuk
    $where = array('id' => $id);
    $barang = $this->M_admin->get_data('tb_barang_keluar', $where);
    
    if($barang) {
      $data_barang = $barang[0];
      
      // Coba tambah stok dulu
      $result = $this->M_admin->tambah_stok($data_barang->id_transaksi, $data_barang->jumlah);
      
      if($result) {
        // Berhasil update stok yang sudah ada
        $this->M_admin->delete('tb_barang_keluar', $where);
        $this->session->set_flashdata('msg_berhasil','✓ Barang Keluar Berhasil Di-undo. '.$data_barang->jumlah.' unit dikembalikan ke barang masuk (ID: '.$data_barang->id_transaksi.')');
      } else {
        // Barang masuk tidak ada, harus di-restore (insert kembali)
        $restore = $this->M_admin->restore_barang_masuk_from_keluar($data_barang);
        
        if($restore) {
          $this->M_admin->delete('tb_barang_keluar', $where);
          $this->session->set_flashdata('msg_berhasil','✓ Barang Keluar Berhasil Di-undo. Data barang masuk (ID: '.$data_barang->id_transaksi.') telah di-restore dengan '.$data_barang->jumlah.' unit');
        } else {
          $this->session->set_flashdata('msg_error','⚠ GAGAL undo barang keluar! Tidak bisa mengembalikan data ke barang masuk.');
        }
      }
    } else {
      $this->session->set_flashdata('msg_error','Data barang keluar tidak ditemukan');
    }
    
    redirect(base_url('admin/tabel_barangkeluar'));
  }


}
?>
