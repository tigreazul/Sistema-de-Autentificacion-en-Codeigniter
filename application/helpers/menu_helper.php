<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
//si no existe la función invierte_date_time la creamos
if(!function_exists('menus'))
{
    //formateamos la fecha y la hora, función de cesarcancino.com
	function menus()
	{
		$ci =& get_instance();
		foreach (get_modulo() as $value) {
			$arr[] = array(
				'modulo' 	=> $value->mod_descripcion,
				'icono' 	=> $value->mod_icono,
				'interno' 	=> get_pagina($value->id_modulo)
			);
		}
		return !empty($arr)? $arr: array();
	}
}

if(!function_exists('get_pagina'))
{
	function get_pagina($id_modulo){
		$arr_page = array();
		$ci =& get_instance();
		$userid = $ci->autentificacion->get_user_id();
		$result_page = $ci->permiso_model->select('tbl_pagina.*')
		                                       ->where(array(
		                                       		'id_usuario'=>$userid
		                                       		,'pag_estado'=>1
		                                       		,'pag_visible'=>1
		                                       		,'pag_padre ='=>0
		                                       		,'tbl_modulo.id_modulo'=>$id_modulo
		                                       		)
		                                       	)
		                                       ->permiso()
		                                       ->order_by('pag_orden')
		                                       ->result();

		foreach ($result_page as $val) {
			$arr_page[] = array(
				'cabecera' 	=> $val->pag_descripcion,
				'ruta' 		=> $val->pag_ruta,
				'icono' 	=> $val->pag_icono,
				'id' 		=> $val->id_pagina,
				'submenu' 	=> get_padre_pag($id_modulo,$val->id_pagina),
					);
		}

		return (!empty($arr_page)? $arr_page : array());
	}
}

if(!function_exists('get_padre_pag'))
{	
	function get_padre_pag($id_modulo,$id_pagina)
	{
		$ci =& get_instance();
		$userid = $ci->autentificacion->get_user_id();
		return $ci->permiso_model->select('tbl_pagina.pag_descripcion,tbl_pagina.pag_ruta,tbl_pagina.id_pagina')
		                                       ->where(array(
		                                       		'id_usuario'=>$userid
		                                       		,'pag_estado'=>1
		                                       		,'pag_visible'=>1
		                                       		,'tbl_pagina.pag_padre '=>$id_pagina
		                                       		,'tbl_modulo.id_modulo'=>$id_modulo
		                                       		)
		                                       	)
		                                       ->permiso()
		                                       ->order_by('pag_orden')
		                                       ->result();
	} 
} 

if(!function_exists('get_modulo'))
{
	function get_modulo(){
		$ci =& get_instance();
		$userid = $ci->autentificacion->get_user_id();
		return $ci->permiso_model->select('tbl_modulo.*')
		                                       ->where(
		                                       		array(
		                                       		'id_usuario'=>$userid,
		                                       		'mod_estado'=>1
		                                       		)
		                                       	)
		                                       ->permiso()
		                                       ->result();
	}
}


// Niveles Permisos del sistema
if(!function_exists('get_permiso'))
{	
	function get_permiso()
	{
		$ci =& get_instance();
		$userid = $ci->autentificacion->get_user_id();
		return $ci->permiso_model->select('tbl_permisos.*,tbl_pagina.pag_descripcion')
		                                       ->where(
		                                       		array(
		                                       		'id_usuario'=>$userid,
		                                       		'usu_estado'=>1
		                                       		)
		                                       	)
		                                       ->permiso()
		                                       ->result();
	}
}


if(!function_exists('get_permiso_pag'))
{	
	function get_permiso_pag($id_pagina)
	{
		$ci =& get_instance();
		$userid = $ci->autentificacion->get_user_id();
		$permiso = $ci->permiso_model->select('tbl_permisos.*,tbl_pagina.pag_padre,tbl_rol.rol_descripcion,tbl_pagina.pag_descripcion,tbl_modulo.mod_descripcion')
		                                       ->where(
		                                       		array(
		                                       		'id_usuario'=>$userid,
		                                       		'tbl_pagina.id_pagina'=>$id_pagina,
		                                       		'usu_estado'=>1
		                                       		)
		                                       	)
		                                       ->permiso()
		                                       ->row();
		if(empty($permiso)){
			_render_page('layout/acceso_denegado');	
		}else{
			return $permiso;
		}
	}
}


# Vista de accesos denegad
if(!function_exists('denegado'))
{
	function denegado()
	{
		_render_page('layout/acceso_denegado',array());	
	}
}




if(!function_exists('session_loggin'))
{
	function session_loggin()
	{
		$ci =& get_instance();
		if (!$ci->autentificacion->logged_in()){
			redirect('login/entrar');
		}else{
			return $ci->autentificacion->get_user_id();
		}
	}
}


// Enviar a las vistas donde corresponde segun lo enviado
if(!function_exists('_render_page'))
{
	function _render_page($view, $data = array(), $returnhtml=false)
	{
		$ci =& get_instance();
		$viewdata = (empty($data)) ? $data: $data;
		$view_html = $ci->load->view($view, $viewdata, $returnhtml);
		if ($returnhtml) return $view_html;
	}
}