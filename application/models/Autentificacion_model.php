<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autentificacion_model extends CI_Model {

	public $tables = array();
	protected $response = NULL;
	protected $messages;
	private $hash_normal;
	protected $error_start_delimiter;
	protected $error_end_delimiter;

	public $_auth_limit = NULL;
	public $_auth_order_by = NULL;
	public $_auth_where = array();



	public $forgotten_password_code;

	public function __construct()
	{
		parent::__construct();
		$this->load->config('conf_autentificacion', TRUE);
		
		$this->tables  = $this->config->item('tables', 'conf_autentificacion');

		$this->identity_column = $this->config->item('identity', 'conf_autentificacion');
		$this->store_salt      = $this->config->item('store_salt', 'conf_autentificacion');
		$this->salt_length     = $this->config->item('salt_length', 'conf_autentificacion');
		// initialize messages and error
		$this->messages    = array();
		$this->errors      = array();
		$delimiters_source = $this->config->item('delimiters_source', 'conf_autentificacion');
		$this->hash_normal = $this->config->item('hash_normal', 'conf_autentificacion');
		
		if ($delimiters_source === 'form_validation')
		{
			$this->error_start_delimiter = $error_prefix->getValue($this->form_validation);
			$this->message_start_delimiter = $this->error_start_delimiter;

			$this->message_end_delimiter = $this->error_end_delimiter;
		}else{
			$this->message_start_delimiter = $this->config->item('error_start_delimiter', 'conf_autentificacion');;
			$this->message_end_delimiter   = $this->config->item('error_end_delimiter', 'conf_autentificacion');
		}
	}


	/**
	 * users
	 *
	 * @return object Users
	 * @author Ben Edmunds
	 **/
	public function users()
	{
		//default selects
		$this->db->select(array(
		    $this->tables['usuario'].'.*'
		));



		if (isset($this->_auth_where) && !empty($this->_auth_where))
		{
			foreach ($this->_auth_where as $where)
			{
				$this->db->where($where);
			}

			$this->_auth_where = array();
		}

		
		$this->response = $this->db->get($this->tables['usuario']);
		return $this;
	}

	/**
	 * user
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function user($id = NULL)
	{

		// if no id was passed use the current users id
		$id = isset($id) ? $id : $this->session->userdata('user_id');

		$this->limit(1);
		$this->order_by($this->tables['usuario'].'.id', 'desc');
		$this->where($this->tables['usuario'].'.id', $id);

		$this->users();

		return $this;
	}


	/**
	 * login
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function login($identity, $password, $remember=FALSE)
	{
		if (empty($identity) || empty($password))
		{
			$this->set_error('Inicio sesión sin exito');
			return FALSE;
		}

		$query = $this->db->select('usu_email, id_usuario as id, usu_clave,active')
		                  ->where($this->identity_column, $identity)
		                  ->limit(1)
		    			  ->order_by('id', 'desc')
		                  ->get($this->tables['usuario']);

		if($this->is_time_locked_out($identity))
		{
			// Hash something anyway, just to take up time
			$this->hash_password($password);
			$this->set_error('Se terminó el tiempo para la autenticación');

			return FALSE;
		}


		if ($query->num_rows() === 1)
		{
			$user = $query->row();
			$password = $this->hash_password_db($user->id, $password,FALSE,TRUE);
			// echo '<pre>';
			// var_dump($password);
			// echo '</pre>'; die();

			if ($password === TRUE)
			{
				if ($user->active == 0)
				{
					$this->set_error('Activar cuenta - inicio de sesión sin exito');
					return FALSE;
				}

				$this->set_session($user);

				$this->update_last_login($user->id);

				$this->clear_login_attempts($identity);

				if ($remember && TRUE)
				{
					$this->remember_user($user->id);
				}
				$this->set_message('Inicio sesión correctamente');

				return TRUE;
			}
		}

		// Hash something anyway, just to take up time
			$this->hash_password($password);
		// if($hash_normal == FALSE){
		// }

		$this->increase_login_attempts($identity);

		$this->set_error('Inicio sesión sin exito');

		return FALSE;
	}

	public static function code_Hash($algoritmo,$data,$key)
	{
		$hash = hash_init($algoritmo,HASH_HMAC,$key);
		hash_update($hash, $data);
		return hash_final($hash);
	}

	public function increase_login_attempts($identity) {
		if ($this->config->item('track_login_attempts', 'conf_autentificacion')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());
			return $this->db->insert($this->tables['login_attempts'], array('ip_address' => $ip_address, 'login' => $identity, 'time' => time()));
		}
		return FALSE;
	}


	public function remember_user($id)
	{
		if (!$id)
		{
			return FALSE;
		}

		$user = $this->user($id)->row();

		$salt = $this->salt();

		$this->db->update($this->tables['usuario'], array('remember_code' => $salt), array('id_usuario' => $id));

		if ($this->db->affected_rows() > -1)
		{
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if($this->config->item('user_expire', 'conf_autentificacion') === 0)
			{
				$expire = (60*60*24*365*2);
			}
			// otherwise use what is set
			else
			{
				$expire = $this->config->item('user_expire', 'conf_autentificacion');
			}

			set_cookie(array(
			    'name'   => $this->config->item('identity_cookie_name', 'conf_autentificacion'),
			    'value'  => $user->{$this->identity_column},
			    'expire' => $expire
			));

			set_cookie(array(
			    'name'   => $this->config->item('remember_cookie_name', 'conf_autentificacion'),
			    'value'  => $salt,
			    'expire' => $expire
			));

			return TRUE;
		}

		return FALSE;
	}


	public function update_last_login($id)
	{

		$this->load->helper('date');
		$this->db->update($this->tables['usuario'], array('last_login' => time()), array('id_usuario' => $id));

		return $this->db->affected_rows() == 1;
	}

	public function clear_login_attempts($identity, $expire_period = 86400) {
		if ($this->config->item('track_login_attempts', 'conf_autentificacion')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			$this->db->where(array('ip_address' => $ip_address, 'login' => $identity));
			// Purge obsolete login attempts
			$this->db->or_where('time <', time() - $expire_period, FALSE);

			return $this->db->delete($this->tables['login_attempts']);
		}
		return FALSE;
	}


	public function set_session($user)
	{
		
		$session_data = array(
		    'identity'             => $user->{$this->identity_column},
		    $this->identity_column => $user->{$this->identity_column},
		    'email'                => $user->usu_email,
		    'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
		    // 'old_last_login'       => $user->last_login
		);

		$this->session->set_userdata($session_data);
		return TRUE;
	}

	public function hash_password_db($id, $password, $use_sha1_override=FALSE,$hasNormal = FALSE)
	{
		if (empty($id) || empty($password))
		{
			return FALSE;
		}

		$query = $this->db->select('usu_clave, salt')
		                  ->where('id_usuario', $id)
		                  ->limit(1)
		                  ->order_by('id_usuario', 'desc')
		                  ->get($this->tables['usuario']);

		$hash_password_db = $query->row();

		if ($query->num_rows() !== 1)
		{
			return FALSE;
		}

		// sha1
		if($hasNormal){
			$db_password = $this->code_Hash('sha1',$password,$this->config->item('key_code_pass', 'conf_autentificacion'));
			// echo $db_password; die();
		}else{
			if ($this->store_salt)
			{
				$db_password = sha1($password . $hash_password_db->salt);
			}
			else
			{
				$salt = substr($hash_password_db->usu_clave, 0, $this->salt_length);

				$db_password =  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
			}

		}

		if($db_password == $hash_password_db->usu_clave)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function hash_password($password, $salt=false, $use_sha1_override=FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}



		if ($this->store_salt && $salt)
		{
			return  sha1($password . $salt);
		}
		else
		{
			return $this->code_Hash('sha1',$password,$this->config->item('key_code_pass','conf_autentificacion'));
			// $salt = $this->salt();
			// return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}

	public function is_time_locked_out($identity) {

		return $this->is_max_login_attempts_exceeded($identity) && $this->get_last_attempt_time($identity) > time() - $this->config->item('lockout_time', 'conf_autentificacion');
	}

	public function get_last_attempt_time($identity) {
		if ($this->config->item('track_login_attempts', 'conf_autentificacion')) {
			$ip_address = $this->_prepare_ip($this->input->ip_address());

			$this->db->select('time');
            if ($this->config->item('track_login_ip_address', 'conf_autentificacion')) $this->db->where('ip_address', $ip_address);
			else if (strlen($identity) > 0) $this->db->or_where('login', $identity);
			$this->db->order_by('id', 'desc');
			$qres = $this->db->get($this->tables['login_attempts'], 1);

			if($qres->num_rows() > 0) {
				return $qres->row()->time;
			}
		}

		return 0;
	}

	protected function _prepare_ip($ip_address) {
		// just return the string IP address now for better compatibility
		return $ip_address;
	}

	public function is_max_login_attempts_exceeded($identity) {
		if ($this->config->item('track_login_attempts', 'conf_autentificacion')) {
			$max_attempts = $this->config->item('maximum_login_attempts', 'conf_autentificacion');
			if ($max_attempts > 0) {
				$attempts = $this->get_attempts_num($identity);
				return $attempts >= $max_attempts;
			}
		}
		return FALSE;
	}

	public function get_attempts_num($identity)
	{
        if ($this->config->item('track_login_attempts', 'conf_autentificacion')) {
            $ip_address = $this->_prepare_ip($this->input->ip_address());
            $this->db->select('1', FALSE);
            if ($this->config->item('track_login_ip_address', 'conf_autentificacion')) {
            	$this->db->where('ip_address', $ip_address);
            	$this->db->where('login', $identity);
            } else if (strlen($identity) > 0) $this->db->or_where('login', $identity);
            $qres = $this->db->get($this->tables['login_attempts']);
            return $qres->num_rows();
        }
        return 0;
	}


	/**
	 * Generates a random salt value.
	 *
	 * Salt generation code taken from https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
	 *
	 * @return void
	 * @author Anthony Ferrera
	 **/
	public function salt()
	{

		$raw_salt_len = 16;

 		$buffer = '';
        $buffer_valid = false;

        if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
            $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
            if ($buffer) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
            $buffer = openssl_random_pseudo_bytes($raw_salt_len);
            if ($buffer) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && @is_readable('/dev/urandom')) {
            $f = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);
            while ($read < $raw_salt_len) {
                $buffer .= fread($f, $raw_salt_len - $read);
                $read = strlen($buffer);
            }
            fclose($f);
            if ($read >= $raw_salt_len) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid || strlen($buffer) < $raw_salt_len) {
            $bl = strlen($buffer);
            for ($i = 0; $i < $raw_salt_len; $i++) {
                if ($i < $bl) {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                } else {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }

        $salt = $buffer;

        // encode string with the Base64 variant used by crypt
        $base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $base64_string   = base64_encode($salt);
        $salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);

	    $salt = substr($salt, 0, $this->salt_length);


		return $salt;
	}


	/**
	 * set_message
	 *
	 * Set a message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_message($message)
	{
		$this->messages[] = $message;

		return $message;
	}



	/**
	 * messages
	 *
	 * Get the messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function messages()
	{
		$_output = '';
		foreach ($this->messages as $message)
		{

			$messageLang = $message ? $message : '##' . $message . '##';
			$_output .= $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
		}

		return $_output;
	}

	/**
	 * messages as array
	 *
	 * Get the messages as an array
	 *
	 * @return array
	 * @author Raul Baldner Junior
	 **/
	public function messages_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->messages as $message)
			{
				$messageLang = $message ? $message : '##' . $message . '##';
				$_output[] = $this->message_start_delimiter . $messageLang . $this->message_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->messages;
		}
	}


	/**
	 * clear_messages
	 *
	 * Clear messages
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function clear_messages()
	{
		$this->messages = array();

		return TRUE;
	}



	/**
	 * set_error
	 *
	 * Set an error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function set_error($error)
	{
		$this->errors[] = $error;

		return $error;
	}


	/**
	 * errors
	 *
	 * Get the error message
	 *
	 * @return void
	 * @author Ben Edmunds
	 **/
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$errorLang = $error ? $error : '##' . $error . '##';
			$_output .= $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
		}

		return $_output;
	}



	public function errors_array($langify = TRUE)
	{
		if ($langify)
		{
			$_output = array();
			foreach ($this->errors as $error)
			{
				$errorLang = $error ? $error : '##' . $error . '##';
				$_output[] = $this->error_start_delimiter . $errorLang . $this->error_end_delimiter;
			}
			return $_output;
		}
		else
		{
			return $this->errors;
		}
	}


	public function clear_errors()
	{
		$this->errors = array();

		return TRUE;
	}


	// Funciones para obtener datos
	public function result()
	{

		$result = $this->response->result();

		return $result;
	}

	public function limit($limit)
	{
		$this->_auth_limit = $limit;

		return $this;
	}

	public function order_by($by, $order='desc')
	{
		$this->_auth_order_by = $by;
		$this->_auth_order    = $order;

		return $this;
	}

	public function where($where, $value = NULL)
	{
		if (!is_array($where))
		{
			$where = array($where => $value);
		}

		array_push($this->_auth_where, $where);

		return $this;
	}

	public function row()
	{
		$row = $this->response->row();

		return $row;
	}


	// OLVIDE CONTRASEÑA
	// |
	// |
	// |
	// |
	// |
	// | ==================>
	/**
	 * Insert a forgotten password key.
	 *
	 * @return bool
	 * @author Mathew
	 * @updated Ryan
	 * @updated 52aa456eef8b60ad6754b31fbdcc77bb
	 **/
	public function forgotten_password($identity)
	{
		if (empty($identity))
		{
			return FALSE;
		}

		// All some more randomness
		$activation_code_part = "";
		if(function_exists("openssl_random_pseudo_bytes")) {
			$activation_code_part = openssl_random_pseudo_bytes(128);
		}

		for($i=0;$i<1024;$i++) {
			$activation_code_part = sha1($activation_code_part . mt_rand() . microtime());
		}

		$key = $this->hash_code($activation_code_part.$identity);

		// If enable query strings is set, then we need to replace any unsafe characters so that the code can still work
		if ($key != '' && $this->config->item('permitted_uri_chars') != '' && $this->config->item('enable_query_strings') == FALSE)
		{
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($this->config->item('permitted_uri_chars'), '-'))."]+$|i", $key))
			{
				$key = preg_replace("/[^".$this->config->item('permitted_uri_chars')."]+/i", "-", $key);
			}
		}

		$this->forgotten_password_code = $key;

		$update = array(
		    'forgotten_password_code' => $key,
		    'forgotten_password_time' => time()
		);

		$this->db->update($this->tables['usuario'], $update, array($this->identity_column => $identity));
		$return = $this->db->affected_rows() == 1;

		$this->load->library('email');
		$this->email->from($this->config->item('email_from', 'conf_autentificacion'), $this->config->item('name_from', 'conf_autentificacion'));
		$this->email->to($identity);
		$this->email->subject($this->config->item('email_asunto', 'conf_autentificacion'));
		
		$datax['forgotten_password_code']	= $key;
		$datax['identity'] 					= $identity;

		$body_menssage = $this->load->view('template_mail/recuperar_password',$datax,TRUE);
		$this->email->message($body_menssage);
		if ( $this->email->send())
		{
			// $this->email->print_debugger(array('headers')); die();
			$this->set_message('Mensaje enviado correctamente');
		}else{
			$this->set_error('Ocurrio un error no se pudo enviar el mensaje intentelo nuevamente');
		}

		return $return;
	}

	public function clear_forgotten_password_code($code) {

		if (empty($code))
		{
			return FALSE;
		}

		$this->db->where('forgotten_password_code', $code);

		if ($this->db->count_all_results($this->tables['usuario']) > 0)
		{
			$data = array(
			    'forgotten_password_code' => NULL,
			    'forgotten_password_time' => NULL
			);

			$this->db->update($this->tables['usuario'], $data, array('forgotten_password_code' => $code));

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * reset password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function reset_password($identity, $new) {
		if (!$this->identity_check($identity)) {
			return FALSE;
		}

		$query = $this->db->select('id_usuario, usu_clave, salt')
		                  ->where($this->identity_column, $identity)
		                  ->limit(1)
		    			  ->order_by('id_usuario', 'desc')
		                  ->get($this->tables['usuario']);
		if ($query->num_rows() !== 1)
		{
			$this->set_error('Cambio de contraseña sin exito');
			return FALSE;
		}

		$result = $query->row();

		$new = $this->hash_password($new, $result->salt);

		// store the new password and reset the remember code so all remembered instances have to re-login
		// also clear the forgotten password code
		$data = array(
		    'usu_clave' => $new,
		    'remember_code' => NULL,
		    'forgotten_password_code' => NULL,
		    'forgotten_password_time' => NULL,
		);

		$this->db->update($this->tables['usuario'], $data, array($this->identity_column => $identity));

		$return = $this->db->affected_rows() == 1;
		if ($return)
		{
			$this->set_message('Cambio de contraseña exitosa');
		}
		else
		{
			$this->set_error('Cambio de contraseña sin exito');
		}

		return $return;
	}


	/**
	 * Identity check
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function identity_check($identity = '')
	{
		if (empty($identity))
		{
			return FALSE;
		}

		return $this->db->where($this->identity_column, $identity)
		                ->count_all_results($this->tables['usuario']) > 0;
	}

	/**
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_code($password)
	{
		return $this->hash_password($password, FALSE, TRUE);
	}


}

/* End of file autentificacion.php */
/* Location: ./application/models/autentificacion.php */