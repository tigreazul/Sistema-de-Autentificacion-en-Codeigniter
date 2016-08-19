<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

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
		
		$permiso = get_permiso_pag(13);
		if(!empty($permiso)):
			$this->data['permisos'] = $permiso;
			$this->data['lstData'] = $this->personal_model->archivos()->result();
			$this->data['message'] = ($this->validation_errors) ? $this->validation_errors : $this->session->flashdata('message');
			_render_page('dashboard/crud/_index', $this->data);
		endif;

	}



	## Crear archivos compartidos
	function create()
	{
		$this->data['message'] = ($this->validation_errors) ? $this->validation_errors : $this->session->flashdata('message');
		$permiso = get_permiso_pag(13);
		if(!empty($permiso)):
			if($permiso->per_insert != 0){
				$this->data['permisos'] = $permiso;
				$this->data['form'] = $this->_formularios();
				## Redirecciona a la vista
				_render_page('dashboard/crud/_create', $this->data);
			}else{
				denegado();
			}
		endif;
	}

	## Editar archivos compartidos
	function edit($id)
	{
		$this->data['message'] = ($this->validation_errors) ? $this->validation_errors : $this->session->flashdata('message');
		$permiso = get_permiso_pag(13);
		if(!empty($permiso)):
			if($permiso->per_insert != 0){
				if(!dataNumber($id)) show_404();
				
				
				$_rTemporal = $this->personal_model->where('idtable',$id)->archivos()->row();
				if(count($_rTemporal) != 0)
				{
					$this->data['form'] = $this->_formularios($_rTemporal);
				}else
				{
					show_404();
				}

				## Redirecciona a la vista
				_render_page('dashboard/crud/_create', $this->data);
			}else{
				denegado();
			}
		endif;
	}


	## Eliminar archivo
	function delete($id)
	{
		$this->data['message'] = ($this->validation_errors) ? $this->validation_errors : $this->session->flashdata('message');
		$permiso = get_permiso_pag(13);
		if(!empty($permiso)):
			if($permiso->per_delete != 0){
				if(!dataNumber($id)) show_404();
				
				
				$_rTemporal = $this->personal_model->_delete($this->tables['temporal'],array('idtable'=>$id));
				if(count($_rTemporal) != 0)
				{
					redirect('dashboard','refresh');
				}else
				{
					show_404();
				}

				## Redirecciona a la vista
				
			}else{
				denegado();
			}
		endif;
	}

	private function _formularios($row_data = null){
        $data['id'] = (!empty($row_data)) ? $row_data->idtable : $this->form_validation->set_value('id');

        $data['nombre'] = array(
            'name'  => 'nombre',
            'id'    => 'nombre',
            'type'  => 'text',
            'class' => 'form-control',
            'required' => 'required',
            'value' => (!empty($row_data)) ? $row_data->nombre : $this->form_validation->set_value('nombre') 
        );

        $data['fecha'] = array(
            'name'  => 'fecha',
            'id'    => 'fecha',
            'type'  => 'date',
            'class' => 'form-control',
            'required' => 'required',
            'value' => (!empty($row_data)) ? $row_data->fecha : $this->form_validation->set_value('fecha')
        );

        return $data;
    }

	function cu_data()
	{
		
		$this->form_validation->set_rules('nombre', 'Nombre', 'required');
		$this->form_validation->set_rules('fecha', 'Fecha', 'required');
        $this->form_validation->set_rules('id', 'Código', 'numeric');
        if ($this->form_validation->run() == true)
        {
        	$permiso = get_permiso_pag(13);
        	if(empty($permiso)) redirect('dashboard/permission','refresh');


            $id          = $this->input->post('id');
            $nombre      = $this->input->post('nombre');
            $fecha       = $this->input->post('fecha');

            $arr_datos = array(
                'nombre'  => $nombre,
                'estado'  => 1,
                'fecha'   => $fecha
            );

            if(!empty($id)){
	            if($permiso->per_update == 1){
		            $whereId = array(
		                'idtable'  => (int)$id
		            );
	                $this->personal_model->_update($this->tables['temporal'],$arr_datos,$whereId);
	                $this->session->set_flashdata('message', $this->personal_model->messages());
	            }else{
					$this->session->set_flashdata('message', 'No tiene permiso para editar');
	            }
            }else{
            	if($permiso->per_insert == 1){
	                $result = $this->personal_model->_insert($this->tables['temporal'],$arr_datos);
	                if(!$result){
	                    $this->session->set_flashdata('message', $this->personal_model->errors());
	                }
                	$this->session->set_flashdata('message', $this->personal_model->messages());
                }else{
                	$this->session->set_flashdata('message', 'No tiene permiso para guardar');
                }
            }
            redirect('dashboard','refresh');
        }else{
            $this->session->set_flashdata('message', "=>".$this->personal_model->errors());
            $this->validation_errors = validation_errors();
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            redirect('dashboard','refresh');
        }
	}





	public function permission()
	{
		denegado();
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */