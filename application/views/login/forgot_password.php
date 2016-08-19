<p class="login-box-msg"><?php echo $message;?></p>

<h4><strong>He olvidado mi Contraseña</strong></h4>
<p><?php echo sprintf('Por favor, introduce tu %s para que podamos enviarte un email y restablecer tu contraseña.', $identity_label);?></p>
<?php echo form_open("login/forgot_password");?>	
    <div class="form-group has-feedback">
		<label for="identity"><?php echo (($type=='usu_email') ? sprintf('%s:', $identity_label) : sprintf('Identity', $identity_label));?></label>
        <?php echo form_input($identity);?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>    

    <div class="row">
        <div class="col-xs-12">
            <?php echo form_submit('submit', 'Enviar','class="btn btn-primary btn-block btn-flat"');?>
        </div>
        <!-- /.col -->
    </div>
<?php echo form_close();?>
<br>
<a href="<?php echo site_url('login') ?>">Iniciar Sesión</a><br>
