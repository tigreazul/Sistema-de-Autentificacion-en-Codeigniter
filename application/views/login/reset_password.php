<p class="login-box-msg"><?php echo $message;?></p>

<h4><strong>Cambiar Contraseña</strong></h4>
<?php echo form_open('login/reset_password/' . $code);?>	
    <div class="form-group has-feedback">
		<label for="new_password"><?php echo sprintf('Nueva contraseña (minimo %s caracteres):', $min_password_length);?></label>
        <?php echo form_input($new_password);?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>   
    <div class="form-group has-feedback">
		<label for="new_password">Confirmar nueva Contraseña</label>
        <?php echo form_input($new_password_confirm);?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <?php echo form_input($user_id);?>
		<?php echo form_hidden($csrf); ?>
    </div>    

    <div class="row">
        <div class="col-xs-12">
            <?php echo form_submit('submit', 'Cambiar','class="btn btn-primary btn-block btn-flat"');?>
        </div>
        <!-- /.col -->
    </div>
<?php echo form_close();?>
