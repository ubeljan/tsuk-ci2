<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller 
{

	function index()
	{
		//-----
		$data = array();
		
		$query = $this->db->query('SELECT category_key, category_parentkey, category_title FROM categories ORDER BY category_order, category_title');
		
		foreach ($query->result() as $key => $row)
		{
			$data['categories'][$row->category_key]['key'] 			= $row->category_key;
			$data['categories'][$row->category_key]['parentkey'] 	= $row->category_parentkey;
			$data['categories'][$row->category_key]['title'] 		= $row->category_title;
		}		
		
		$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
		$data['basket_total'] 			= $this->basket->update_basket_total() ;		
		$data['level'] 					= $this->session->userdata('user_level') ;	
		
		$data['content'] = $this->content->get_content('faq');

		$this->load->view('_top', 		$data ) ;	
		$this->load->view('home',		$data ) ;
		$this->load->view('_bottom',	$data ) ;		
		//------/	
	}
	
}
/* End of file faq.php */
/* Location: ./public_html/application/controllers/faq.php */