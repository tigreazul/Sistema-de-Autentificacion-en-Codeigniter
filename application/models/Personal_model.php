<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personal_model extends CI_Model {

	public $tables = array();
	protected $messages;
	protected $response = NULL;
	public $_auth_limit = NULL;
	public $_auth_order_by = NULL;
	public $_auth_select = array();
	public $_auth_where = array();	
	public $_auth_order = NULL;
	public $_auth_offset = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->config('conf_autentificacion', TRUE);
		
		$this->tables  = array( 'temporal' => 'temp_table');
		// initialize messages and error
		
		$this->messages    = array();
		$this->errors      = array();


		$this->message_start_delimiter = $this->config->item('error_start_delimiter', 'conf_autentificacion');;
		$this->message_end_delimiter   = $this->config->item('error_end_delimiter', 'conf_autentificacion');
	}


	/////////////////////////
	// Archivos Compatidos //
	/////////////////////////	
		public function archivos()
		{
			if (isset($this->_auth_select) && !empty($this->_auth_select))
			{
				foreach ($this->_auth_select as $select)
				{
					$this->db->select($select);
				}

				$this->_auth_select = array();
			}
			else
			{	
				//default selects
				$this->db->select(array(
				    $this->tables['temporal'].'.*'
				));
			}

			if (isset($this->_auth_where) && !empty($this->_auth_where))
			{
				foreach ($this->_auth_where as $where)
				{
					$this->db->where($where);
				}

				$this->_auth_where = array();
			}

			if (isset($this->_auth_limit) && isset($this->_auth_offset))
			{
				$this->db->limit($this->_auth_limit, $this->_auth_offset);

				$this->_auth_limit  = NULL;
				$this->_auth_offset = NULL;
			}
			else if (isset($this->_auth_limit))
			{
				$this->db->limit($this->_auth_limit);

				$this->_auth_limit  = NULL;
			}

			// set the order
			if (isset($this->_auth_order_by) && isset($this->_auth_order))
			{
				$this->db->order_by($this->_auth_order_by, $this->_auth_order);

				$this->_auth_order    = NULL;
				$this->_auth_order_by = NULL;
			}


			$this->response = $this->db->get($this->tables['temporal']);
			return $this;
		}



	///////////////////
	// Transacciones //
	///////////////////
		public function _insert($table,$data)
		{
			if(!empty($data))
			{
				$this->db->insert($table, $data);
				$insertId = $this->db->insert_id();
				$this->set_message('Inserto');
				return TRUE;
			}
				$this->set_error('error No inserto');
				return FALSE;
		}

		public function _update($table,$data = FALSE,$arr_id_user = FALSE)
		{
			$this->db->trans_begin();
			if(!$data || $arr_id_user)
			{
				$this->db->where($arr_id_user);
				$this->db->update($table, $data);
				if ($this->db->trans_status() === FALSE)
				{
					$this->db->trans_rollback();
					$this->set_error('update_unsuccessful');
					return FALSE;
				}
				$this->db->trans_commit();
				$this->set_message('update_successful');
				return TRUE;
			}else{
				return FALSE;
			}

			// return ($return) ? TRUE:FALSE);
		}

		public function _delete($table,$where)
		{
			if(!empty($where))
			{
				$this->db->where($where);
				$this->db->delete($table);
				$this->set_message('Se elimino correctamente');
				return TRUE;
			}
				$this->set_error('error intentente nuevamente');
				return FALSE;
		}


	/////////////////////////
	// Caprutando mensajes //
	/////////////////////////
		public function set_message($message)
		{
			$this->messages[] = $message;

			return $message;
		}

		public function set_error($error)
		{
			$this->errors[] = $error;

			return $error;
		}

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



	//////////////////////////////////
	// Funciones para obtener datos //
	//////////////////////////////////
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

		public function order_by($by, $order='asc')
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

		public function select($select)
		{
			$this->_auth_select[] = $select;

			return $this;
		}

}

/* End of file Personal_model.php */
/* Location: ./application/models/Personal_model.php */