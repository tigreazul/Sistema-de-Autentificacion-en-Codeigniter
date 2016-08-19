


<section class="content-header">
    <h1>
        <?php echo $form['id'] == '' ? 'CREAR' : 'EDITAR' ?> 
    </h1>
</section>

<div class="col-md-12" style="margin-top: 20px;margin-bottom: 10px;">
    <div class="col-md-2 noleftpadding">
        <!-- Perfil Administrador Sistemas -->
        <a class="btn btn-default" href="javascript:window.history.back();"><i class="fa fa-reply"></i> Volver</a>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <!-- Lista de Articulos Perfiles: Todos -->
    <div class="row">
        <div class="col-md-12">
          	<!-- general form elements -->
          	<div class="box box-primary">
	            <div class="box-header with-border">
	            </div>
	            <!-- /.box-header -->
	           	<?php echo form_open('dashboard/cu_data');?>
	              	<div class="box-body">
		                <div class="form-group">
		                  	<label for="exampleInputEmail1">Nombre</label>
		                  	<?php echo form_input($form['nombre']); ?>
					        <?php echo form_hidden('id', $form['id']);?>
		                </div>
		                <div class="form-group">
		                  	<label for="exampleInputPassword1">Fecha</label>
		                  	<?php echo form_input($form['fecha']); ?>
		                </div>
	              	</div>
	              	<!-- /.box-body -->
	              	<div class="box-footer">
	                	<button type="submit" class="btn btn-primary"><?php echo $form['id'] == '' ? 'GUARDAR' : 'ACTUALIZAR' ?></button>
	              	</div>
	            <?php echo form_close();?>
          	</div>
          	<!-- /.box -->
        </div>
    </div>
</section>

