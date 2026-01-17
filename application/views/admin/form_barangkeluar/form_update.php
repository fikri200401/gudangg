<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Edit Barang Keluar</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/web_admin/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/datetimepicker/css/bootstrap-datetimepicker.css">
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
        <li class="treeview active">
          <a href="#"><i class="fa fa-table"></i> <span>Tables</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
          <ul class="treeview-menu">
            <li><a href="<?= base_url('admin/tabel_barangmasuk')?>"><i class="fa fa-circle-o"></i> Tabel Barang Masuk</a></li>
            <li class="active"><a href="<?= base_url('admin/tabel_barangkeluar')?>"><i class="fa fa-circle-o"></i> Tabel Barang Keluar</a></li>
          </ul>
        </li>
      </ul>
    </section>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Edit Barang Keluar</h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url('admin')?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=base_url('admin/tabel_barangkeluar')?>">Tabel Barang Keluar</a></li>
        <li class="active">Edit</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-edit"></i> Form Edit Barang Keluar</h3>
            </div>

            <?php if($this->session->flashdata('msg_error')): ?>
              <div class="alert alert-danger alert-dismissible" style="margin: 15px;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-exclamation-triangle"></i> <?=$this->session->flashdata('msg_error')?>
              </div>
            <?php endif; ?>

            <?php foreach($data_barang_keluar as $d): ?>
            <form role="form" action="<?=base_url('admin/proses_update_barangkeluar')?>" method="post">
              <div class="box-body">
                <input type="hidden" name="id" value="<?=$d->id?>">
                <input type="hidden" name="id_transaksi" value="<?=$d->id_transaksi?>">
                <input type="hidden" name="jumlah_lama" value="<?=$d->jumlah?>">

                <div class="form-group">
                  <label>ID Transaksi</label>
                  <input type="text" class="form-control" value="<?=$d->id_transaksi?>" readonly>
                </div>

                <div class="form-group">
                  <label>Kode Barang</label>
                  <input type="text" class="form-control" value="<?=$d->kode_barang?>" readonly>
                </div>

                <div class="form-group">
                  <label>Nama Barang</label>
                  <input type="text" class="form-control" value="<?=$d->nama_barang?>" readonly>
                </div>

                <div class="form-group">
                  <label>Tanggal Masuk</label>
                  <input type="text" class="form-control" value="<?=$d->tanggal_masuk?>" readonly>
                </div>

                <div class="form-group">
                  <label>Tanggal Keluar <span class="text-danger">*</span></label>
                  <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" name="tanggal_keluar" value="<?=$d->tanggal_keluar?>" required />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                  <small class="text-muted">Tanggal keluar harus setelah tanggal masuk</small>
                </div>

                <div class="form-group">
                  <label>Jumlah Barang Keluar <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" name="jumlah" value="<?=$d->jumlah?>" min="1" required>
                  <small class="text-muted">Ubah jumlah untuk mengembalikan sebagian stok ke barang masuk</small>
                </div>

                <div class="alert alert-info">
                  <i class="fa fa-info-circle"></i> 
                  <strong>Catatan:</strong> Jika Anda mengurangi jumlah barang keluar, selisihnya akan dikembalikan ke stok barang masuk.
                </div>
              </div>

              <div class="box-footer">
                <a href="<?=base_url('admin/tabel_barangkeluar')?>" class="btn btn-default">
                  <i class="fa fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fa fa-save"></i> Update
                </button>
              </div>
            </form>
            <?php endforeach; ?>
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
<script src="<?php echo base_url()?>assets/web_admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>assets/web_admin/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url()?>assets/datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url()?>assets/web_admin/dist/js/adminlte.min.js"></script>

<script type="text/javascript">
$(function () {
  $('#datetimepicker1').datetimepicker({
    format: 'DD/MM/YYYY'
  });
});
</script>
</body>
</html>
