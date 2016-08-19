<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo ruta() ?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Alertas</li>
            <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Actualizaciones</span></a></li>

            <li class="header">Dashboard</li>
            <?php foreach (menus() as $menu): 
                if(!is_array($menu['interno']) || empty($menu['interno']) ){ 
                    echo '<li class="">';
                    echo '<a href="">'.$menu['icono'].' <span>'.$menu['modulo'].'</span></a>';
                    echo '</li>';
                }else{ 
                    ?>
                    <li class="treeview">
                        <a href="javascript:void(0)"><?php echo $menu['icono'] ?> <span><?php echo $menu['modulo'] ?></span>
                            <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <?php foreach ($menu['interno'] as $interno) : ?>
                                <?php if(!is_array($interno['submenu']) || empty($interno['submenu'])):  ?>
                                    <li><a href="<?php echo site_url($interno['ruta']) ?>"><i class="fa fa-circle-o"></i><?php echo $interno['cabecera'] ?></a></li>
                                <?php else: ?>
                                    <li class="treeview">
                                        <a href="javascript:void(0)"><?php echo $interno['icono'] ?> <span><?php echo $interno['cabecera'] ?></span>
                                            <span class="pull-right-container">
                                            <i class="fa fa-angle-left pull-right"></i>
                                            </span>
                                        </a>
                                        <ul class="treeview-menu">
                                        <?php foreach ($interno['submenu'] as $submenu) : ?>
                                            <li><a href="<?php echo site_url($submenu->pag_ruta) ?>"><i class="fa fa-circle-o"></i><?php echo $submenu->pag_descripcion ?></a></li>
                                        <?php endforeach; ?>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </li>
            <?php
            }
            endforeach; ?>
            
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>