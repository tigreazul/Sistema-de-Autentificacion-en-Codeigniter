<?php $this->load->view('themes/layout/head_view') ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <?php $this->load->view('themes/layout/menu_view') ?>
    <!-- Left side column. contains the logo and sidebar -->
  
	<?php $this->load->view('themes/layout/menuprincipal_view') ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?php echo $output;?>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <?php $this->load->view('themes/layout/footer_view') ?>

    <!-- Control Sidebar -->
    <?php //require_once('themes/layout/control.php') ?>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 2.2.3 -->
<script src="<?php echo ruta() ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo ruta() ?>bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo ruta() ?>assets/js/dash.js"></script>
<script src="<?php echo ruta() ?>dist/js/app.min.js"></script>
<script type="text/javascript">
    
    $('.delete').on('click',function(e){

        var delet = $(this).attr('href');
        var r = confirm("Desea eliminar el archivo?");
        if (r == true) {
            location.href = delet;
        }
        
        return false;
    });

</script>

</body>
</html>
