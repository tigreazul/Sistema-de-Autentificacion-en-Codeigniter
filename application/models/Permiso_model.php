<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permiso_model extends CI_Model {

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
		
		$this->tables  = $this->config->item('tables', 'conf_autentificacion');
		// initialize messages and error
		$this->messages    = array();
		$this->errors      = array();


		$this->message_start_delimiter = $this->config->item('error_start_delimiter', 'conf_autentificacion');;
		$this->message_end_delimiter   = $this->config->item('error_end_delimiter', 'conf_autentificacion');
	}


	public function permiso($id_usuario = NULL)
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
			    $this->tables['usuario'].'.*',
			    $this->tables['rol'].'.*',
			    $this->tables['pagina'].'.*',
			    $this->tables['permiso'].'.*',
			    $this->tables['modulo'].'.*'
			));
		}

		$this->db->distinct();
		$this->db->join(
		    $this->tables['rol'],
		    $this->tables['rol'].'.id_rol ='.$this->tables['usuario'].'.id_rol','inner'
		);

		$this->db->join(
		    $this->tables['permiso'],
		    $this->tables['rol'].'.id_rol ='.$this->tables['permiso'].'.id_rol','inner'
		);

		$this->db->join(
		    $this->tables['pagina'],
		    $this->tables['permiso'].'.id_pagina ='.$this->tables['pagina'].'.id_pagina','inner'
		);

		$this->db->join(
		    $this->tables['modulo'],
		    $this->tables['pagina'].'.id_mod ='.$this->tables['modulo'].'.id_modulo','inner'
		);

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


		$this->response = $this->db->get($this->tables['usuario']);
		return $this;
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
			$_output .= $this->message_start_delimiter . $errorLang . $this->message_end_delimiter;
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
				$_output[] = $this->message_start_delimiter . $errorLang . $this->message_end_delimiter;
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

/* End of file Permiso_model.php */
/* Location: ./application/models/Permiso_model.php */