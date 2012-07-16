<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller 
{

	function index()
	{
		if ($this->session->userdata('user_id')!=''){
			$data = array();
			$this->session->unset_userdata('user_id');
			$this->session->unset_userdata('user_level');
			header('Location: '.base_url());
		}else{
			header('Location: '.base_url().'login');
		}
	}
	
}

/* End of file logout.php */
/* Location: ./public_html/application/controllers/logout.php */