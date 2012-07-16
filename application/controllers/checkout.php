<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout extends CI_Controller
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
		
			
		if (is_array($this->session->userdata('basket')))
		{
			//-----
			$data['basket_total'] 	= $this->basket->update_basket_total();
			$data['basket'] 		= $this->session->userdata('basket');
			
			$query = $this->db->query("SELECT product_key, product_title, product_price FROM products");
			
			if ($query->num_rows() > 0 )
			{
				foreach ($query->result() as $key => $row)
				{
					if (isset($data['basket'][$row->product_key]))
					{
						$data['basket'][$row->product_key]['title'] = $row->product_title;
						$data['basket'][$row->product_key]['price'] = $row->product_price;
					}
				}
			}
			
			$i=0;
			foreach ($data['basket'] as $key => $value)
			{
				if (!isset($value['price']))
				{
					$i++;
				}
			}
			
			if ($i==0)
			{
				$data['content'] = $this->content->get_content('home');
	
				$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
				$data['basket_total'] 			= $this->basket->update_basket_total() ;		
				$data['level'] 					= $this->session->userdata('user_level') ;
				
				$this->load->view('_top', 		$data ) ;		
				$this->load->view('checkout',	$data ) ;
				$this->load->view('_bottom',	$data ) ;					
			}
			else
			{
				$data = array();
				
				$this->session->unset_userdata('basket');
				
				$data['content']['body'] = '<table class="normal" align="center" width="400px">';
				$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
				$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
				$data['content']['body'].= '</table>';				
				
				$data['level'] 					= $this->session->userdata('user_level') ;
				
				$this->load->view('_top', 		$data ) ;	
				$this->load->view('home',		$data ) ;
				$this->load->view('_bottom',	$data ) ;	
			}
		}
		else
		{
			$data = array();
			
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			
			$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
			$data['basket_total'] 			= $this->basket->update_basket_total() ;		
			$data['level'] 					= $this->session->userdata('user_level') ;
			
			$this->load->view('_top', 		$data ) ;	
			$this->load->view('home',		$data ) ;
			$this->load->view('_bottom',	$data ) ;	
		}
	}
	//-----/index


	//-----customer	
	function customer()
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
		
		$query = $this->db->query('SELECT category_key, category_parentkey, category_title FROM categories ORDER BY category_order, category_title');
		
		foreach ($query->result() as $key => $row)
		{
			$data['categories'][$row->category_key]['key'] 			= $row->category_key;
			$data['categories'][$row->category_key]['parentkey'] 	= $row->category_parentkey;
			$data['categories'][$row->category_key]['title'] 		= $row->category_title;
		}
		
		if (is_array($this->session->userdata('basket')))
		{
			if ($this->session->userdata('user_id')!='')
			{
				header('Location: '.base_url().'checkout/delivery');	
			}
			
			$data['content'] = $this->content->get_content('home');
			
			$this->load->view('_top', 				$data ) ;	
			$this->load->view('checkout/customer',	$data ) ;
			$this->load->view('_bottom',			$data ) ;				
			
		}
		else
		{
			$data = array();
			
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			
			$this->load->view('home',$data);
		}
	}
	//-----/customer


	//-----delivery	
	function delivery()
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
		
		$query = $this->db->query('SELECT category_key, category_parentkey, category_title FROM categories ORDER BY category_order, category_title');
		
		foreach ($query->result() as $key => $row)
		{
			$data['categories'][$row->category_key]['key'] 			= $row->category_key;
			$data['categories'][$row->category_key]['parentkey'] 	= $row->category_parentkey;
			$data['categories'][$row->category_key]['title'] 		= $row->category_title;
		}	
	
		if (is_array($this->session->userdata('basket')))
		{			
			if ($this->session->userdata('user_id')!='')
			{
				$user_id = $this->session->userdata('user_id');
				$query = $this->db->query("SELECT * FROM users WHERE user_id='$user_id'");
				if ($query->num_rows() > 0 )
				{
					foreach ($query->result() as $key => $row)
					{
						$data['user']['title'] 		= $row->user_title;
					    $data['user']['firstname'] 	= $row->user_firstname;
						$data['user']['lastname'] 	= $row->user_lastname;
						$data['user']['email'] 		= $row->user_email;
						$data['user']['password'] 	= $row->user_password;
						$data['user']['address1'] 	= $row->user_address1;
						$data['user']['address2'] 	= $row->user_address2;
						$data['user']['city'] 		= $row->user_city;
						$data['user']['county'] 	= $row->user_county;
						$data['user']['postcode'] 	= $row->user_postcode;
						$data['user']['country'] 	= $row->user_country;
					}
				}
				else
				{
					header('Location: '.base_url().'logout');
				}
			}
			else
			{
				$data['user']['title'] 		= '';
			    $data['user']['firstname'] 	= '';
				$data['user']['lastname'] 	= '';
				$data['user']['email'] 	= '';
				$data['user']['password'] 	= '';
				$data['user']['address1'] 	= '';
				$data['user']['address2'] 	= '';
				$data['user']['city'] 		= '';
				$data['user']['county'] 	= '';
				$data['user']['postcode'] 	= '';
				$data['user']['country'] 	= '';
			}
			
			$query = $this->db->query("SELECT country_code, country_title FROM countries ORDER BY country_title");
			if ($query->num_rows()>0)
			{
				foreach ($query->result() as $key => $row)
				{	
					$data['countries'][$row->country_code] = $row->country_title;
				}
			}
			
			$query = $this->db->query("SELECT * FROM shipping WHERE shipping_active=1 ORDER BY shipping_default DESC, shipping_price");
			if ($query->num_rows()>0)
			{
				foreach ($query->result() as $key => $row)
				{	
					if ($row->shipping_default==1)
					{
						$data['ship_default']=$row->shipping_title;
					}
					$data['shipping'][$row->shipping_id]['title']		= $row->shipping_title;
					$data['shipping'][$row->shipping_id]['price']		= $row->shipping_price;
					$data['shipping'][$row->shipping_id]['countries'] 	= $row->shipping_countries;
				}
			}
			else
			{
				$data['shipping'][0]['title']		= 'N/A';
				$data['shipping'][0]['price']		= '0.00';
				$data['shipping'][0]['countries'] 	= '0';
			}
			
			if ($this->session->userdata('user_id')!='')
			{
				$data['user']['shipping'] = $this->session->userdata('user_shipping');
			}
			
			$data['content'] = $this->content->get_content('home');
			$data['level'] 					= $this->session->userdata('user_level') ;
		
			$this->load->view('_top', 				$data ) ;			
			$this->load->view('checkout/delivery',	$data ) ;
			$this->load->view('_bottom',			$data ) ;			
		}
		else
		{
			$data = array();
			
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			
			$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
			$data['basket_total'] 			= $this->basket->update_basket_total() ;		
			$data['level'] 					= $this->session->userdata('user_level') ;
			
			$this->load->view('_top', 		$data ) ;	
			$this->load->view('home',		$data ) ;
			$this->load->view('_bottom',	$data ) ;
		}
	}
	//-----/delivery


	//-----summary	
	function summary()
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
		
		if ($this->session->userdata('user_id')=='')
		{
			header('Location: '.base_url().'checkout/delivery');	
		}
		
		if (is_array($this->session->userdata('basket')))
		{
			//-----			
			$data['basket_total'] 	= $this->basket->update_basket_total();
			$data['basket'] 		= $this->session->userdata('basket');
			
			$query = $this->db->query("SELECT product_key, product_title, product_price FROM products");
			if ($query->num_rows( )> 0)
			{
				foreach ($query->result() as $key => $row)
				{
					if (isset($data['basket'][$row->product_key]))
					{
						$data['basket'][$row->product_key]['title'] = $row->product_title;
						$data['basket'][$row->product_key]['price'] = $row->product_price;
					}
				}
			}
			
			$i = 0 ;
			foreach ($data['basket'] as $key => $value)
			{
				if (!isset($value['price']))
				{
					$i++;
				}
			}
			
			if ($i==0)
			{
				$ship_title = $this->session->userdata('user_shipping');
				$data['shipping']['title'] = $ship_title;
				$query = $this->db->query("SELECT shipping_price FROM shipping WHERE shipping_title='$ship_title'");
				if ($query->num_rows()>0)
				{
					foreach ($query->result() as $key => $row)
					{	
						$data['shipping']['price'] = $row->shipping_price;
					}
				}
				
				if ($this->session->userdata('user_id')!='')
				{
					$user_id = $this->session->userdata('user_id');
					$query = $this->db->query("SELECT * FROM users WHERE user_id='$user_id'");
					if ($query->num_rows()>0)
					{
						foreach ($query->result() as $key => $row)
						{
							$data['user']['title'] 		= $row->user_title;
						    $data['user']['firstname'] 	= $row->user_firstname;
							$data['user']['lastname'] 	= $row->user_lastname;
							$data['user']['email'] 	= $row->user_email;
							$data['user']['password'] 	= $row->user_password;
							$data['user']['address1'] 	= $row->user_address1;
							$data['user']['address2'] 	= $row->user_address2;
							$data['user']['city'] 		= $row->user_city;
							$data['user']['county'] 	= $row->user_county;
							$data['user']['postcode'] 	= $row->user_postcode;
							$data['user']['country'] 	= $row->user_country;
						}
					}
				}
				
				$basket_id = $this->session->userdata('basket_id');
				
				$data['content'] 				= $this->content->get_content('home') ;				
				$data['level'] 					= $this->session->userdata('user_level') ;
			
				$this->load->view('_top', 				$data ) ;			
				$this->load->view('checkout/summary',	$data ) ;
				$this->load->view('_bottom',			$data ) ;				
				//-----/				
			}
			else
			{
				$data = array();
				
				$this->session->unset_userdata('basket');
				
				$data['content']['body'] = '<table class="normal" align="center" width="400px">';
				$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
				$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
				$data['content']['body'].= '</table>';

				$data['level'] 					= $this->session->userdata('user_level') ;
				
				$this->load->view('_top', 		$data ) ;				
				$this->load->view('home',		$data ) ;
				$this->load->view('_bottom',	$data ) ;					
			}
		}
		else
		{
			$data = array();
			
			$data['content']['body'] = '<table class="normal" align="center" width="400px">';
			$data['content']['body'].= '<tr><td align="center"><br>There are no products in your basket!</td></tr>';
			$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
			$data['content']['body'].= '</table>';
			
			$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
			$data['basket_total'] 			= $this->basket->update_basket_total() ;		
			$data['level'] 					= $this->session->userdata('user_level') ;
			
			$this->load->view('_top', 		$data ) ;	
			$this->load->view('home',		$data ) ;
			$this->load->view('_bottom',	$data ) ;	
		}
	}
	//------/summary


	//-----success
	function success()
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
		
		
		if ($this->session->flashdata('order_ref')!='')
		{
			$order_ref = $this->session->flashdata('order_ref');
			$status = 2;
		}
		
		$data = array();
		
		$data['content'] = $this->content->get_content('home');
		$data['content']['body'] = '<table class="normal" align="center" width="400px">';
		$data['content']['body'].= '<tr><td align="center"><br>Thank you for your order!</td></tr>';
		$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
		$data['content']['body'].= '</table>';
		
		$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
		$data['basket_total'] 			= $this->basket->update_basket_total() ;		
		$data['level'] 					= $this->session->userdata('user_level') ;
		
		$this->load->view('_top', 		$data ) ;	
		$this->load->view('home',		$data ) ;
		$this->load->view('_bottom',	$data ) ;	
	}
	//------/success


	//-----error	
	function error()
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
		
		
		$data = array();
		
		$data['content'] = $this->content->get_content('home');
		$data['content']['body'] = '<table class="normal" align="center" width="400px">';
		$data['content']['body'].= '<tr><td align="center"><br>There has been an error processing your order!</td></tr>';
		$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
		$data['content']['body'].= '</table>';
		
		$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
		$data['basket_total'] 			= $this->basket->update_basket_total() ;		
		$data['level'] 					= $this->session->userdata('user_level') ;
		
		$this->load->view('_top', 		$data ) ;	
		$this->load->view('home',		$data ) ;
		$this->load->view('_bottom',	$data ) ;	
	}
	//-----/error


	//-----order_cancelled	
	function order_cancelled()
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
		
		if ($this->session->flashdata('order_ref')!='')
		{
			$order_ref = $this->session->flashdata('order_ref');
			$status = 4;
		}
		
		$data = array();
		
		$data['content'] = $this->content->get_content('home');
		$data['content']['body'] = '<table class="normal" align="center" width="400px">';
		$data['content']['body'].= '<tr><td align="center"><br>Your order has not been processed!</td></tr>';
		$data['content']['body'].= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
		$data['content']['body'].= '</table>';
		
		$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
		$data['basket_total'] 			= $this->basket->update_basket_total() ;		
		$data['level'] 					= $this->session->userdata('user_level') ;
		
		$this->load->view('_top', 		$data ) ;	
		$this->load->view('home',		$data ) ;
		$this->load->view('_bottom',	$data ) ;	
	}
	//-----/order_cancelled


}
/* End of file checkout.php */
/* Location: ./public_html/application/controllers/checkout.php */