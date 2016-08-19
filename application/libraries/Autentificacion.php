<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Autentificacion
*
* Version: 0.1
*
* Author: Junior Tello
*		  zkated@gmail.com
*         @zkpzk8
*/

class Autentificacion
{

	public function __construct()
	{
		$this->load->model('autentificacion_model');
		$this->load->helper(array('cookie', 'language','url'));
		$this->load->library('session');
		#get_cookie = Cookie propio de CI
		if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code'))
		{
			// $this->autentificacion_model->login_remembered_user();
		}
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 * @param $method
	 * @param $arguments
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->autentificacion_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}
		if($method == 'create_user')
		{
			return call_user_func_array(array($this, 'register'), $arguments);
		}
		if($method=='update_user')
		{
			return call_user_func_array(array($this, 'update'), $arguments);
		}
		return call_user_func_array( array($this->autentificacion_model, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	**/
	public function logged_in()
	{
		return (bool) $this->session->userdata('identity');
	}

	/**
	 * logged_in
	 *
	 * @return integer
	 * @author jrmadsen67
	 **/
	public function get_user_id()
	{
		$user_id = $this->session->userdata('user_id');
		if (!empty($user_id))
		{
			return $user_id;
		}
		return null;
	}

	public function logout()
	{

		$identity = 'email';

                if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->unset_userdata( array($identity => '', 'id' => '', 'user_id' => '') );
        }
        else
        {
        	$this->session->unset_userdata( array($identity, 'id', 'user_id') );
        }

		// delete the remember me cookies if they exist
		if (get_cookie('identity'))
		{
			delete_cookie('identity');
		}
		if (get_cookie('remember_code'))
		{
			delete_cookie('remember_code');
		}

		// Destroy the session
		$this->session->sess_destroy();

		//Recreate the session
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->sess_create();
		}
		else
		{
			if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
				session_start();
			}
			$this->session->sess_regenerate(TRUE);
		}

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * forgotten_password_check
	 *
	 * @param $code
	 * @author Michael
	 * @return bool
	 */
	public function forgotten_password_check($code)
	{
		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile
		// echo '<pre>'; print_r($profile); echo '</pre>'; die();
		if (!is_object($profile))
		{
			$this->set_error('password___change_unsuccessful');
			return FALSE;
		}
		else
		{
			if ($this->config->item('forgot_password_expiration', 'conf_autentificacion') > 0) {
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'conf_autentificacion');
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->clear_forgotten_password_code($code);
					$this->set_error('password_change____unsuccessful');
					return FALSE;
				}
			}
			return $profile;
		}
	}





}