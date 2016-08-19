<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrador extends CI_Controller {

	public $user_id = NULL;
	public $tables = array();
	public $validation_errors = array();
	public function __construct(){
		parent::__construct();
		$this->load->model('personal_model');
		$this->tables  = array( 'temporal' => 'temp_table');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'conf_autentificacion'), $this->config->item('error_end_delimiter', 'conf_autentificacion'));
		$this->userid = session_loggin();
		$this->output->set_template('default');
	}
	
	public function index()
	{
		$permiso = get_permiso_pag(1);
		if(!empty($permiso)):
			$this->data['permisos'] = $permiso;
			$this->data['lstData'] = $this->personal_model->archivos()->result();
			$this->data['message'] = ($this->validation_errors) ? $this->validation_errors : $this->session->flashdata('message');
			_render_page('personal/_administrador', $this->data);
		endif;
	}

}

/* End of file Personal.php */
/* Location: ./application/controllers/Personal.php */