<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller
{

	//-----index
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
		
		$data['content'] 				= $this->content->get_content('home') ;

		$this->load->view('_top', 		$data ) ;	
		$this->load->view('home',		$data ) ;
		$this->load->view('_bottom',	$data ) ;		
		//------/
	}
	//-----index	
	
	
	//-----paden
	function paden()
	{
		//-----
		echo ENVIRONMENT ;
		echo '<br />' ;
		echo EXT ;
		echo '<br />' ;		
		echo FCPATH ;
		echo '<br />' ;		
		echo SELF ;
		echo '<br />' ;		
		echo BASEPATH ;
		echo '<br />' ;		
		echo APPPATH ;
		echo '<br />' ;		
		echo CI_VERSION ;
		echo '<br />' ;			
		echo $this->config->item('app_pad')  ;
		//-----/
	}
	//-----/paden	
	
	
}

/* End of file home.php */
/* Location: ./public_html/application/controllers/home.php */