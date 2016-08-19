<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->library(array('autentificacion','form_validation'));
		$this->load->helper(array('url','language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'conf_autentificacion'), $this->config->item('error_end_delimiter', 'conf_autentificacion'));
		$this->output->set_template('login');
	}


	public function index()
	{
		// echo site_url('login/entrar');
		if (!$this->autentificacion->logged_in())
		{
			// redirect them to the login page
			
			redirect('login/entrar');
		}
		else
		{
			redirect('dashboard','refresh');
		}
	}

	// log the user in
	public function entrar()
	{
		if ($this->autentificacion->logged_in()){redirect('dashboard','refresh');}
		$this->data['title'] = 'Titulo de login';
		//validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', 'Email/Usuario:'), 'required');
		$this->form_validation->set_rules('password', str_replace(':', '', 'Contraseña:'), 'required');

		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->autentificacion->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->autentificacion->messages());
				redirect('/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->autentificacion->errors());
				redirect('login/entrar', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'class' => 'form-control',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id'   => 'password',
				'class' => 'form-control',
				'type' => 'password',
			);

			$this->_render_page('login/login', $this->data);
		}
	}

	// olvido contraseña
	public function forgot_password()
	{
		if ($this->autentificacion->logged_in()){redirect('dashboard','refresh');}
		// setting validation rules by checking whether identity is username or email
		if($this->config->item('identity', 'conf_autentificacion') != 'email' )
		{
		   $this->form_validation->set_rules('identity', 'etiqueta de identidad Olvidó su contraseña', 'required');
		}
		else
		{
		   $this->form_validation->set_rules('identity', 'etiqueta de correo electrónico de validación Olvidó su contraseña', 'required|valid_email');
		}


		if ($this->form_validation->run() == false)
		{
			$this->data['type'] = $this->config->item('identity','conf_autentificacion');
			// setup the input
			$this->data['identity'] = array(
				'name' => 'identity',
				'class' => 'form-control',
				'id' => 'identity',
				'type' => 'email',
			);

			if ( $this->config->item('identity', 'conf_autentificacion') != 'usu_email' ){
				$this->data['identity_label'] = 'sssssss';
			}
			else
			{
				$this->data['identity_label'] = 'Correo Electrónico';
			}

			// set any errors and display the form
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->_render_page('login/forgot_password', $this->data);
		}
		else
		{
			$identity_column = $this->config->item('identity','conf_autentificacion');
			// echo $identity_column; die();
			$identity = $this->autentificacion->where('usu_email', $this->input->post('identity'))->users()->row();

			
			if(empty($identity)) {

        		if($this->config->item('identity', 'conf_autentificacion') != 'usu_email')
            	{
            		$this->autentificacion->set_error('forgot_password_identity_not_found');
            	}
            	else
            	{
            	   $this->autentificacion->set_error('forgot_password_email_not_found');
            	}

                $this->session->set_flashdata('message', $this->autentificacion->errors());
        		redirect("login/forgot_password", 'refresh');
    		}

			// run the forgotten password method to email an activation code to the user
			$forgotten = $this->autentificacion->forgotten_password($identity->{$this->config->item('identity', 'conf_autentificacion')});

			// echo '<pre>';print_r($forgotten);echo '</pre>'; die();
			if ($forgotten)
			{
				// if there were no errors
				$this->session->set_flashdata('message', $this->autentificacion->messages());
				redirect("login/entrar", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->autentificacion->errors());
				redirect("login/forgot_password", 'refresh');
			}
		}
	}

	// Reseteat contraseña
	public function reset_password($code = NULL)
	{
		if (!$code)
		{
			show_404();
		}

		$user = $this->autentificacion->forgotten_password_check($code);
		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new','Nueva Contraseña', 'required|min_length[' . $this->config->item('min_password_length', 'conf_autentificacion') . ']|max_length[' . $this->config->item('max_password_length', 'conf_autentificacion') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', 'Confirmar nueva contraseña', 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form

				// set the flash data error message if there is one
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

				$this->data['min_password_length'] = $this->config->item('min_password_length', 'conf_autentificacion');
				$this->data['new_password'] = array(
					'name' => 'new',
					'id'   => 'new',
					'class' => 'form-control',
					'type' => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['new_password_confirm'] = array(
					'name'    => 'new_confirm',
					'id'      => 'new_confirm',
					'class' => 'form-control',
					'type'    => 'password',
					'pattern' => '^.{'.$this->data['min_password_length'].'}.*$',
				);
				$this->data['user_id'] = array(
					'name'  => 'user_id',
					'id'    => 'user_id',
					'type'  => 'hidden',
					'value' => $user->id_usuario,
				);
				$this->data['csrf'] = $this->_get_csrf_nonce();
				$this->data['code'] = $code;

				// render
				$this->_render_page('login/reset_password', $this->data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id_usuario != $this->input->post('user_id'))
				{

					// something fishy might be up
					$this->autentificacion->clear_forgotten_password_code($code);

					show_error('error_csrf');

				}
				else
				{
					// finally change the password
					$identity = $user->{$this->config->item('identity', 'conf_autentificacion')};

					$change = $this->autentificacion->reset_password($identity, $this->input->post('new'));

					if ($change)
					{

						// if the password was successfully changed
						$this->session->set_flashdata('message', $this->autentificacion->messages());
						redirect("login/entrar", 'refresh');

					}
					else
					{
						$this->session->set_flashdata('message', $this->autentificacion->errors());
						redirect('login/reset_password/' . $code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->autentificacion->errors());
			redirect("login/forgot_password", 'refresh');
		}
	}

	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	public function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function _render_page($view, $data=null, $returnhtml=false)//I think this makes more sense
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

	// log the user out
	public function logout()
	{
		$this->data['title'] = "Logout";

		// log the user out
		$logout = $this->autentificacion->logout();

		// redirect them to the login page
		$this->session->set_flashdata('message', $this->autentificacion->messages());
		redirect('login/entrar');
	}



}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */