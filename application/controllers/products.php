<?php  if ( !defined('BASEPATH') ) { exit('No direct script access allowed'); }

class Products extends CI_Controller 
{

	//-----index
	function index()
	{
		header('Location: '.base_url());
	}
	//-----/index
	
	
	//-----c
	function c()
	{
		//-----
		$data = array();
		
		$name = $this->uri->segment(3, 'Home');
		
		$query = $this->db->query("SELECT category_title, category_parentkey FROM categories WHERE category_key='$name'");
		
		if ($query->num_rows()>0)
		{
			foreach ($query->result() as $key => $row)
			{
				$data['category']['cat_title'] = $row->category_title;
				if ($row->category_parentkey!='_top')
				{
					$parent_key = $row->category_parentkey ;
					
					$query2 = $this->db->query("SELECT category_title FROM categories WHERE category_key='$parent_key'");
					
					if ($query2->num_rows() > 0)
					{
						foreach ($query2->result() as $key2 => $row2)
						{
							$data['category']['parent_title'] = $row2->category_title;
						}
					}
				}
				else
				{
					$data['category']['parent_title']='_top';
				}
			}
		}
		
		
		$query			=	$this->db->query("SELECT * FROM products WHERE product_catkey='$name'");
		$total_rows		=	$query->num_rows();
		$offset 		= 	$this->uri->segment(4, 0);
		$num 			= 	4 ;
		
		if ($this->session->userdata('sort_view')=='')
		{
			$this->session->set_userdata('sort_view','default');
		}
		
		if ($this->session->userdata('sort_view')=='price_asc')
		{
			$query = $this->db->query("SELECT * FROM products WHERE product_catkey='$name' ORDER BY product_price, product_order, product_title LIMIT $offset,$num");
		}
		elseif ($this->session->userdata('sort_view')=='price_desc')
		{
			$query = $this->db->query("SELECT * FROM products WHERE product_catkey='$name' ORDER BY product_price DESC, product_order, product_title LIMIT $offset,$num");
		}
		elseif ($this->session->userdata('sort_view')=='item_az')
		{
			$query = $this->db->query("SELECT * FROM products WHERE product_catkey='$name' ORDER BY product_title, product_order LIMIT $offset,$num");
		}
		elseif ($this->session->userdata('sort_view')=='item_za')
		{
			$query = $this->db->query("SELECT * FROM products WHERE product_catkey='$name' ORDER BY product_title DESC, product_order LIMIT $offset,$num");
		}
		else
		{
			$query = $this->db->query("SELECT * FROM products WHERE product_catkey='$name' ORDER BY product_order, product_title LIMIT $offset,$num");
		}
		
		foreach ($query->result() as $key => $row)
		{
			$data['products'][$row->product_key]['key'] 		= $row->product_key;
			$data['products'][$row->product_key]['title'] 		= $row->product_title;
			$data['products'][$row->product_key]['description'] = truncate($row->product_description,100);
			$data['products'][$row->product_key]['price']		= $row->product_price;
			$data['products'][$row->product_key]['buy']			= $row->product_buy;
			$data['products'][$row->product_key]['image'] 		= $row->product_image;
		}
		
		if ($query->num_rows() > 0 )
		{
			$this->load->library('pagination');
			
			$config['uri_segment'] 		= 4;
			$config['num_links'] 		= 10;
			$config['base_url'] 		= base_url().'products/c/'.$name;
			$config['total_rows'] 		= $total_rows;
			$config['per_page'] 		= 4;
			$config['full_tag_open'] 	= '<div style="display:inline">';
			$config['full_tag_close'] 	= '</div>';
			$config['next_link'] 		= 'Next';
			$config['next_tag_open'] 	= '<div class="pagination" style="display:inline">';
			$config['next_tag_close'] 	= '</div>';
			$config['prev_link'] 		= 'Prev';
			$config['prev_tag_open'] 	= '<div class="pagination" style="display:inline">';
			$config['prev_tag_close']	= '</div>';
			$config['cur_tag_open'] 	= '<div class="pagination_cur" style="display:inline">';
			$config['cur_tag_close'] 	= '</div>';
			$config['num_tag_open'] 	= '<div class="pagination" style="display:inline">';
			$config['num_tag_close'] 	= '</div>';
			$this->pagination->initialize($config);
			
			$data['sort_view'] = $this->session->userdata('sort_view');
			
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
			
			$this->load->view('_top', 		$data ) ;				
			$this->load->view('categories',	$data ) ;
			$this->load->view('_bottom',	$data ) ;			
		}
		else
		{
			//-----			
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
			
			$data['content']['body'] 		= '<table class="normal" align="center" width="400">';
			$data['content']['body']		.= '<tr><td align="center"><br>Sorry there are no products in this category!</td></tr>';
			$data['content']['body']		.= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
			$data['content']['body']		.= '</table>';

			$this->load->view('_top', 		$data ) ;	
			$this->load->view('home',		$data ) ;
			$this->load->view('_bottom',	$data ) ;		
			//------/
		}
	}
	//-----/c
	
	
	//-----p
	function p()
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
			
		$name = $this->uri->segment(3, 'Home');
		
		$query = $this->db->query("SELECT * FROM products WHERE product_key='$name' ORDER BY product_order, product_title");
		
		if ($query->num_rows()>0)
		{
			foreach ($query->result() as $key => $row)
			{
				$cat_key = $row->product_catkey;
				$query2 = $this->db->query("SELECT category_title, category_parentkey FROM categories WHERE category_key='$cat_key'");
				if ($query2->num_rows()>0)
				{
					foreach ($query2->result() as $key2 => $row2)
					{
						$cat_title = $row2->category_title;
						if ($row2->category_parentkey!= '_top' )
						{
							$parent_key 	= $row2->category_parentkey;
							$query3 		= $this->db->query("SELECT category_title FROM categories WHERE category_key='$parent_key'");
							if ($query3->num_rows() > 0 )
							{
								foreach ($query3->result() as $key3 => $row3)
								{
									$parent_title = $row3->category_title;
								}
							}
						}
						else
						{
							$parent_title = '_top' ;
						}
					}
				}
				$data['products']['key'] 			= $row->product_key;
				$data['products']['catkey'] 		= $row->product_catkey;
				$data['products']['cat_title'] 		= $cat_title;
				$data['products']['parent_title'] 	= $parent_title;
				$data['products']['title'] 			= $row->product_title;
				$data['products']['description'] 	= auto_link($row->product_description);
				$data['products']['price'] 			= $row->product_price;
				$data['products']['buy'] 			= $row->product_buy;
				$data['products']['image'] 			= $row->product_image;
			}		

			
			$this->load->view('_top', 				$data ) ;			
			$this->load->view('products',			$data ) ;
			$this->load->view('_bottom',			$data ) ;				
			//-----/
		}
		else
		{
			//-----			
			$query = $this->db->query('SELECT category_key, category_parentkey, category_title FROM categories ORDER BY category_order, category_title');
			
			foreach ($query->result() as $key => $row)
			{
				$data['categories'][$row->category_key]['key'] 			= $row->category_key;
				$data['categories'][$row->category_key]['parentkey'] 	= $row->category_parentkey;
				$data['categories'][$row->category_key]['title'] 		= $row->category_title;
			}		
			
			$data['basket_qty'] 					= $this->basket->update_basket_qty() ;
			$data['basket_total'] 					= $this->basket->update_basket_total() ;		
			$data['level'] 							= $this->session->userdata('user_level') ;	
			
			$data['content']['body'] 				= '<table class="normal" align="center" width="400px">';
			$data['content']['body']				.= '<tr><td align="center"><br>Sorry product not available!</td></tr>';
			$data['content']['body']				.= '<tr><td align="center"><br><input type="button" class="buttonstyle" value="Continue Shopping" onclick="javascript:parent.location=\''.base_url().'\';"></td></tr>';
			$data['content']['body']				.= '</table>';
			
			$this->load->view('_top', 				$data ) ;	
			$this->load->view('home',				$data ) ;
			$this->load->view('_bottom',			$data ) ;	
			//-----/
		}
	}
	//-----/p
	
	
}
/* End of file products.php */
/* Location: ./public_html/application/controllers/products.php */