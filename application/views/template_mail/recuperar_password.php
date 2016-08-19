<html>
<body>
	<h1><?php echo sprintf('Reestablecer contraseña para %s', $identity);?></h1>
	<p><?php echo sprintf('Por favor ingresa en este link para %s.', anchor('login/reset_password/'. $forgotten_password_code, 'Restablecer Tu Contraseña'));?></p>
</body>
</html>