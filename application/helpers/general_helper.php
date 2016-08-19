<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('paginas')){
	// function paginas($pageId, $mineId = null)
	// {
	// 	$ci =& get_instance();
	// 	$resulr = $ci->permiso_model->select('tbl_pagina.*')
 //                                    ->where(array(
 //                                   		'id_usuario'=>$userid
 //                                   		,'pag_estado'=>1
 //                                   		,'pag_visible'=>1
 //                                   		,'pag_padre ='=>0
 //                                   		,'tbl_modulo.id_modulo'=>$id_modulo
 //                                   		)
 //                                   	)
 //                                    ->permiso()
 //                                    ->order_by('pag_orden')
 //                                    ->result();
		
	// 	switch ($pageId)
	// 	{
	// 		case 'buena': $mine_id = array(1);break;
	// 		case 'hudbay': $mine_id = array(2);break;
	// 		case 'impala': $mine_id = array(3);break;
	// 		case 'voto': $mine_id = array(4);break;
	// 		case 'anta': $mine_id = array(5);break;
	// 		case 'milpo': $mine_id = array(6);break;
	// 		case 'gold': $mine_id = array(7);break;
	// 		case 'volc': $mine_id = array(8);break;
	// 		case 'isem': $mine_id = array(9);break;
	// 		case 'ferr': $mine_id = array(10);break;
	// 		case 'minsur': $mine_id = array(11);break;
	// 		case 'sanmartin': $mine_id = array(12);break;
	// 		case 'shougang': $mine_id = array(13);break;
	// 		case 'all': $mine_id = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13);break;
	// 		default: $mine_id = null;break;
	// 	}

	// 	$mineToValidate = ($mineId ? $mineId: (defined('PROFILE_SESS_ID') ? PROFILE_SESS_ID: null));

	// 	return ( in_array($mineToValidate, $mine_id) ? true: false);	
	// }
}


if(!function_exists('dataString')){
	function dataString($string){
		if(empty($string)){
			show_404();
			return FALSE;
		}

		if(!is_string($string) || is_array($string) || is_object($string)){
			return FALSE;
		}
		return TRUE;
	}
}


if(!function_exists('dataNumber')){
	function dataNumber($number){
		if(empty($number)){
			return FALSE;
		}

		if(!is_numeric($number) || is_array($number) || is_object($number)){
			return FALSE;
		}
		return TRUE;

	}
}



if(!function_exists('ruta')){
	function ruta(){
		return base_url().'recursos/';
	}
}