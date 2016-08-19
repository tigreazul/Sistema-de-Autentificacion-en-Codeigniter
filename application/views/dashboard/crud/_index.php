<section class="content-header">
    <h1>
        Lista 
        <small>General</small>
    </h1>
</section>

<div class="col-md-12" style="margin-top: 20px;margin-bottom: 10px;">
    <div class="col-md-2 noleftpadding">
        <!-- Perfil Administrador Sistemas -->
        <a href="<?php echo site_url('dashboard/create') ?>" class="btn btn-block btn-success"><i class="fa fa-file-text"></i> Crear </a>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <!-- Lista de Articulos Perfiles: Todos -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered table-hover">
                        <thead> 
                            <tr>
								<th>id</th>	
								<th>Nombre</th>	
								<th>Fecha</th>	
								<th>Estado</th>	
								<th>Acciones</th>	
                            </tr>
                        </thead>
                        <tbody id="listPagination">
                            <?php if(!empty($lstData)): foreach ($lstData as $value): ?>
                                <tr class="">
                                    <td>
                                        <?php echo $value->idtable ?>
                                    </td>
                                    <td><?php echo $value->nombre ?></td>
                                    <td><?php echo $value->fecha ?></td>
                                    <td><?php echo $value->estado ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo site_url('dashboard/edit/'.$value->idtable) ?>" class="btn btn-warning" title="Editar"><i class="fa fa-edit"></i></a>
                                            <a href="<?php echo site_url('dashboard/delete/'.$value->idtable) ?>" class="btn btn-danger delete" title="Eliminar"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <p>No existen datos </p>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

