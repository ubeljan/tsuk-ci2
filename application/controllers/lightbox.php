<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lightbox extends CI_Controller 
{

	function index()
	{
		header('Location: '.base_url());
		$this->load->view('_ajax/_blank');
	}
	
	function i()
	{
		$data = array();
		$data['img'] = $this->uri->segment(3);
		$this->load->view('lightbox',$data);
	}
	
}

/* End of file lightbox.php */
/* Location: ./public_html/application/controllers/lightbox.php */