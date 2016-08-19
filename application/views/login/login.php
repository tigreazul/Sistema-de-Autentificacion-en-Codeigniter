<p class="login-box-msg"><?php echo $message;?></p>
<?php echo form_open("login/entrar");?>
    <div class="form-group has-feedback">
        <label>Email</label>
        <?php echo form_input($identity);?>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <label>Password</label>
        <?php echo form_input($password);?>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>

    <div class="row">
        <div class="col-xs-8">
            <div class="checkbox icheck">
                <label>
                    <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>Recordarme
                </label>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
            <?php echo form_submit('submit', 'Entrar','class="btn btn-primary btn-block btn-flat"');?>
        </div>
        <!-- /.col -->
    </div>


<?php echo form_close();?>
<br>

<a href="<?php echo site_url('login/forgot_password') ?>">¿Has olvidado tu contraseña?</a><br>
