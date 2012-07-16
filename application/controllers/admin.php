<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
	
	//-----index	
	function index()
	{
		header('Location: '.base_url() ) ;
		
		$this->load->view('_ajax/_blank') ;
	}
	//-----/index
	
	
	//-----config	
	function config()
	{
		//-----
		$data = array();	
		
		if ($this->session->userdata('user_level')!=1)
		{
			header('Location: '.base_url());
		}
		else
		{			
			$data['content'] = $this->content->get_content('home');
			
			$this->load->view('/admin/config',$data);
		}
		//-----/
	}
	//-----/config
	
	
	//-----shipping
	function shipping()
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
		
		if ($this->session->userdata('user_level') != '1' )
		{
			header('Location: '.base_url());
		}
		else
		{
			//-----
			$country_codes = array();
			
			$query = $this->db->query("SELECT country_code, country_title FROM countries ORDER BY country_title");
			if ($query->num_rows()>0)
			{
				foreach ($query->result() as $key => $row)
				{	
					$country_codes[$row->country_code] = $row->country_title;
				}
			}
			
			$query = $this->db->query("SELECT * FROM shipping ORDER BY shipping_title");			
			if ($query->num_rows() > '0')
			{
				foreach ($query->result() as $key => $row)
				{
					//-----
					$data['shipping'][$row->shipping_id]['title'] 		= $row->shipping_title;
					$data['shipping'][$row->shipping_id]['price'] 		= $row->shipping_price;
					$shipping_country[$row->shipping_id] = explode(",", $row->shipping_countries);
					foreach ($country_codes as $country_code => $country_title) 
					{
						if (in_array($country_code,$shipping_country[$row->shipping_id]))
						{
							$data['shipping'][$row->shipping_id]['countries_added'][$country_code] = $country_title;
						}
						else
						{
							$data['shipping'][$row->shipping_id]['countries_available'][$country_code] = $country_title;
						}
					}
					
					$data['shipping'][$row->shipping_id]['default'] 	= $row->shipping_default;
					$data['shipping'][$row->shipping_id]['active'] 		= $row->shipping_active;
					//-----/
				}
			}
			
			$data['content'] = $this->content->get_content('home');

			$this->load->view('_top', 				$data ) ;			
			$this->load->view('/admin/shipping',	$data ) ;
			$this->load->view('_bottom',			$data ) ;			
			//-----/
		}
	}
	//-----/shipping
	
	
	//-----categories
	function categories()
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
		
		if ($this->session->userdata('user_level') != 1)
		{
			header('Location: '.base_url());
		}
		else
		{
			if ($this->uri->segment(3)!='')
			{
				$parent_key = $this->uri->segment(3);
				 
				$query = $this->db->query("SELECT category_key, category_parentkey, category_title FROM categories WHERE category_parentkey='$parent_key' ORDER BY category_order, category_title");
				if ($query->num_rows()>0)
				{
					$data['cats']=1;
					foreach ($query->result() as $key => $row)
					{
					    $data['category'][$row->category_key]['key']		= $row->category_key;
					 	$data['category'][$row->category_key]['parentkey']	= $row->category_parentkey;
					 	$data['category'][$row->category_key]['title']		= $row->category_title;
					}
				}
				else
				{
					$data['cats']=0;
				}
				
				$query = $this->db->query("SELECT category_title FROM categories WHERE category_key='$parent_key'");
				
				if ($query->num_rows() > 0 )
				{
					foreach ($query->result() as $key => $row)
					{
					 	$data['category_title'] = $row->category_title;
					}
				}
				
				$data['parent_key']					=		$parent_key;
				$data['content'] 					= 		$this->content->get_content('home');
				
				$this->load->view('_top', 					$data ) ;			
				$this->load->view('admin/sub_categories',	$data);
				$this->load->view('_bottom',				$data ) ;							
				//-----/				
			}
			else
			{
				//-----				
				$parent_key = '_top';
				
				$query = $this->db->query("SELECT category_key, category_parentkey, category_title FROM categories WHERE category_parentkey='_top' ORDER BY category_order, category_title");
				if ($query->num_rows() > 0 )
				{
					$data['cats'] = 1 ;
					
					foreach ($query->result() as $key => $row)
					{
					    $data['category'][$row->category_key]['key']		= $row->category_key;
					 	$data['category'][$row->category_key]['parentkey']	= $row->category_parentkey;
					 	$data['category'][$row->category_key]['title']		= $row->category_title;
					}
				}
				else
				{
					$data['cats'] = 0 ;
				}
				
				$data['parent_key']		= $parent_key;				
				$data['content'] 		= $this->content->get_content('home');				
				
				$this->load->view('_top', 					$data ) ;			
				$this->load->view('admin/categories',	$data);
				$this->load->view('_bottom',				$data ) ;							
				//-----/					
			}
		}
	}
	//-----/categories
	
	
	//-----products	
	function products()
	{
		//-----	
		if ($this->session->userdata('user_level')!=1)
		{
			header('Location: '.base_url());
		}
		else
		{
			//-----			
			$data2 		= array();
							
			$query 		= $this->db->query('SELECT category_key, category_parentkey, category_title FROM categories ORDER BY category_order, category_title');
			
			foreach ($query->result() as $key => $row)
			{
				$data2['categories'][$row->category_key]['key'] 			= $row->category_key;
				$data2['categories'][$row->category_key]['parentkey'] 	= $row->category_parentkey;
				$data2['categories'][$row->category_key]['title'] 		= $row->category_title;
			}				
			
			$data 							= array();
			$data['basket_qty'] 			= $this->basket->update_basket_qty() ;
			$data['basket_total'] 			= $this->basket->update_basket_total() ;		
			$data['level'] 					= $this->session->userdata('user_level') ;
			$data2['level'] 					= $this->session->userdata('user_level') ;	
			
			$catkey 	= 	'';
			$i			=	0;
			
			$query = $this->db->query("SELECT category_key, category_title FROM categories WHERE category_parentkey='_top' ORDER BY category_order, category_title");
			
			if ($query->num_rows()>0)
			{
				foreach ($query->result() as $key => $row)
				{	
					$data['categories'][$row->category_key] = '---'.$row->category_title.'---';
					$query = $this->db->query("SELECT category_key, category_title FROM categories WHERE category_parentkey='$row->category_key' ORDER BY category_order, category_title");
					if ($query->num_rows()>0)
					{
						foreach ($query->result() as $key => $row)
						{	
							//-----
							if ($i==0)
							{
								$catkey = $this->uri->segment(3,$row->category_key);
								$i++;
							}
							
							$data['categories'][$row->category_key] = $row->category_title;
							//-----/
						}
					}
				}
			}
			$data['current'] = $catkey;
			
			$query = $this->db->query("SELECT product_key, product_catkey, product_title, product_description, product_price, product_buy, product_image FROM products WHERE product_catkey='$catkey' ORDER BY product_order, product_title");
			
			if ($query->num_rows() > 0)
			{
				//-----
				$data['prods']	=	1;
				
				foreach ($query->result() as $key => $row)
				{
				    $data['product'][$row->product_key]['key']				= $row->product_key;
				 	$data['product'][$row->product_key]['catkey']			= $row->product_catkey;
				 	$data['product'][$row->product_key]['title']			= $row->product_title;
					$data['product'][$row->product_key]['description']		= $row->product_description;
					$data['product'][$row->product_key]['price']			= $row->product_price;
					$data['product'][$row->product_key]['buy']				= $row->product_buy;
					$data['product'][$row->product_key]['product_image']	= $row->product_image;
				}
				//-----/
			}
			else
			{
				$data['prods']=0;
			}
			if ($this->session->flashdata('expand_this')!='')
			{
				$data['expand_this']				= $this->session->flashdata('expand_this');
			}
			else
			{
				$data['expand_this'] 				= '';
			}

				
			$data['content'] 						= $this->content->get_content('home');
			
			$this->load->view('_top', 				$data2 ) ;			
			$this->load->view('admin/products',		$data ) ;
			$this->load->view('_bottom',			$data ) ;			
		}
	}
	//-----/products
	

}
/* End of file admin.php */
/* Location: ./public_html/application/controllers/admin.php */