<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Barang Dihapus</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/sweetalert/dist/sweetalert.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="<?php echo base_url('admin')?>" class="logo">
      <span class="logo-mini"><b>A</b>LT</span>
      <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php foreach($avatar as $a){ ?>
              <img src="<?php echo base_url('assets/upload/user/img/'.$a->nama_file)?>" class="user-image" alt="User Image">
              <?php } ?>
              <span class="hidden-xs"><?=$this->session->userdata('name')?></span>
            </a>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <?php foreach($avatar as $a){ ?>
          <img src="<?php echo base_url('assets/upload/user/img/'.$a->nama_file)?>" class="img-circle" alt="User Image">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?=$this->session->userdata('name')?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li><a href="<?= base_url('admin')?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-edit"></i> <span>Forms</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('admin/form_barangmasuk')?>"><i class="fa fa-circle-o"></i> Tambah Data Barang Masuk</a></li>
            <li><a href="<?php echo base_url('admin/form_satuan')?>"><i class="fa fa-circle-o"></i> Tambah Satuan Barang</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i> <span>Tables</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url('admin/tabel_barangmasuk') ?>"><i class="fa fa-circle-o"></i> Tabel Barang Masuk</a></li>
            <li><a href="<?= base_url('admin/tabel_barangkeluar')?>"><i class="fa fa-circle-o"></i> Tabel Barang Keluar</a></li>
            <li><a href="<?= base_url('admin/tabel_satuan')?>"><i class="fa fa-circle-o"></i> Tabel Satuan</a></li>
            <li class="active"><a href="<?= base_url('admin/tabel_barang_deleted')?>"><i class="fa fa-trash-o"></i> Tabel Barang Dihapus</a></li>
          </ul>
        </li>
        <li class="header">LABELS</li>
        <li>
          <a href="<?php echo base_url('admin/profile')?>">
         <i class="fa fa-cogs" aria-hidden="true"></i> <span>Profile</span></a>
        </li>
        <li>
          <a href="<?php echo base_url('admin/users')?>">
         <i class="fa fa-fw fa-users" aria-hidden="true"></i> <span>Users</span></a>
        </li>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Barang Yang Dihapus</h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url('admin')?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Barang Dihapus</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-trash" aria-hidden="true"></i> Data Barang Yang Telah Dihapus</h3>
            </div>
            <div class="box-body">
              <?php if($this->session->flashdata('msg_berhasil')){ ?>
                <div class="alert alert-success alert-dismissible">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <strong>Success!</strong> <?php echo $this->session->flashdata('msg_berhasil');?>
               </div>
              <?php } ?>

              <a href="<?=base_url('admin/tabel_barangmasuk')?>" class="btn btn-primary" style="margin-bottom:10px;">
                <i class="fa fa-arrow-left"></i> Kembali
              </a>

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Satuan</th>
                  <th>Jumlah</th>
                  <th>Tanggal Dihapus</th>
                  <th>Restore</th>
                </tr>
                </thead>
                <tbody>
                <?php if(is_array($list_data) && count($list_data) > 0){ ?>
                  <?php $no = 1; foreach($list_data as $dd): ?>
                <tr>
                    <td><?=$no?></td>
                    <td><?=$dd->kode_barang?></td>
                    <td><?=$dd->nama_barang?></td>
                    <td><?=$dd->satuan?></td>
                    <td><?=$dd->jumlah?></td>
                    <td><?=date('d-m-Y H:i', strtotime($dd->deleted_at))?></td>
                    <td>
                      <a type="button" class="btn btn-success btn-restore" href="<?=base_url('admin/restore_barang/'.$dd->id_transaksi)?>">
                        <i class="fa fa-undo"></i> Restore
                      </a>
                    </td>
                </tr>
                  <?php $no++; endforeach; ?>
                <?php }else { ?>
                <tr>
                    <td colspan="7" align="center"><strong>Tidak Ada Data Barang Yang Dihapus</strong></td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <strong>Copyright &copy; <?=date('Y')?></strong>
  </footer>
</div>

<script src="<?php echo base_url()?>assets/web_admin/bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo base_url()?>assets/web_admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/web_admin/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/web_admin/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/web_admin/dist/js/adminlte.min.js"></script>

<script>
jQuery(document).ready(function($){
  $('.btn-restore').on('click',function(){
      var getLink = $(this).attr('href');
      swal({
        title: 'Restore Data',
        text: 'Yakin Ingin Mengembalikan Data Ini?',
        html: true,
        confirmButtonColor: '#5cb85c',
        showCancelButton: true,
      },function(){
        window.location.href = getLink
      });
      return false;
  });
});

$(function () {
  $('#example1').DataTable({
    'ordering': true,
    'info': true
  })
});

  // Auto hide alert setelah 30 detik
  $(document).ready(function() {
    setTimeout(function() {
      $('.alert-success, .alert-info, .alert-warning, .alert-danger').fadeOut('slow');
    }, 30000);
  });
</script>
</body>
</html>
