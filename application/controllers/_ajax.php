<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _ajax extends CI_Controller
{
	
	function index()
	{
		header('Location: '.base_url());
		$data['msg'] = 'Redirect';
		$this->load->view('_ajax/_blank',$data);
	}
	
	function basket_update_items()
	{
		if ($this->input->post('items')!=''){
			$data = array();
			$items = array();
			parse_str(str_replace('qty_','',$this->input->post('items')), $items);
			unset($items['first']);
			$basket = $this->session->userdata('basket');
			foreach ($basket as $key => $value) {
				if (is_numeric($items[$key]) && $items[$key]>0){
					$basket[$key]['qty'] = $items[$key];
				}else if ($items[$key]==0){
					unset($basket[$key]);
				}
			}
			$this->session->set_userdata('basket',$basket);
			if (count($basket)==0){
			 	$this->session->unset_userdata('basket');
			}
			$data['msg'] = 'Items Updated';
			$this->load->view('_ajax/_blank',$data);
		}else{
			$data = array();
			$data['msg'] = 'Items Not Updated';
			$this->load->view('_ajax/_blank',$data);
		}
	}
	
	function basket_remove_item()
	{
		if ($this->input->post('key')!=''){
			$data = array();
			$basket = $this->session->userdata('basket');
			unset($basket[$this->input->post('key')]);
			if (count($basket)==0){
				$this->session->unset_userdata('basket');
			}else{
				$this->session->set_userdata('basket',$basket);
			}
			$data['msg'] = 'Item Removed';
			$this->load->view('_ajax/_blank',$data);
		}else{
			$data = array();
			$data['msg'] = 'Item Not Removed';
			$this->load->view('_ajax/_blank',$data);
		}
	}
	
	function save_reset_basket()
	{
		$basket_id = $this->session->userdata('basket_id');
		if ($this->input->post('reset')=='true'){
			$data = array();
			$this->session->unset_userdata('basket');
			$this->session->unset_userdata('basket_id');
			$data['msg'] = 'Basket Cleared';
			$this->load->view('_ajax/_blank',$data);
		}else{
			$data = array();
			$data['msg'] = 'Basket Not Cleared!';
			$this->load->view('_ajax/_blank',$data);
		}
	}
	
	function reset_basket()
	{
		if ($this->input->post('reset')=='true'){
			$data = array();
			$this->session->unset_userdata('basket');
			$this->session->unset_userdata('basket_id');
			$data['msg'] = 'Basket Cleared';
			$this->load->view('_ajax/_blank',$data);
		}else{
			$data = array();
			$data['msg'] = 'Basket Not Cleared!';
			$this->load->view('_ajax/_blank',$data);
		}
	}
	
	function basket_add()
	{
		$data = array();
		$this->load->model('basket');
		$data['msg'] = $this->basket->add_to_basket();
		$data['msg'].= '|'.$this->basket->update_basket_qty();
		$data['msg'].= '|'.number_format($this->basket->update_basket_total(),2);
		$this->load->view('_ajax/_blank',$data);
	}

	function login()
	{
		$data = array();
		if ($this->input->post('email')!='' && $this->input->post('password')!=''){
			$email = $this->db->escape($this->input->post('email'));
			$pass = $this->db->escape($this->input->post('password'));
			$query = $this->db->query("SELECT user_id, user_level FROM users WHERE user_email=$email AND user_password=$pass AND user_active=1");
			if ($query->num_rows()>0){
				foreach ($query->result() as $key => $row){
					$id 	= $row->user_id;
					$level	= $row->user_level;
				}
				$this->session->set_userdata('user_id', $id);
				$this->session->set_userdata('user_level', $level);
				$data['msg'] = 1;
			}else{
				$data['msg'] = 0;
			}
		}else{
			$data['msg'] = 0;
		}
		$this->load->view('_ajax/_blank',$data);
	}

	
	function save_details()
	{
		$data['msg'] = 0;
		$email = $this->db->escape($this->input->post('email'));
		$query = $this->db->query("SELECT user_id, user_password FROM users WHERE user_email=$email");
		if ($query->num_rows()>0){
			foreach ($query->result() as $key => $row){
				if ($row->user_password==$this->input->post('password')){
					$allow=1;
				}else if ($this->session->userdata('user_id')==$row->user_id){
					$allow=1;
				}else{
					$allow=0;
					$data['msg'] = 2;
				}
			}
		}else{
			$allow=1;
		}
		if ($allow==1){
			$this->load->helper('email');
			if ($this->input->post('title')!='' && $this->input->post('firstname')!='' && $this->input->post('lastname')!='' && $this->input->post('email')!='' && $this->input->post('password')!='' && $this->input->post('address1')!='' && $this->input->post('city')!='' && $this->input->post('postcode')!='' && $this->input->post('country')!='' && $this->input->post('shipping')!='' && valid_email($this->input->post('email'))){
				$title 	   		= $this->input->post('title');
				$firstname 		= $this->input->post('firstname');
				$lastname   		= $this->input->post('lastname');
				$email  		= $this->input->post('email');
				$password  		= $this->input->post('password');
				$address1  		= $this->input->post('address1');
				if ($this->input->post('address2')!=''){$address2 = $this->input->post('address2');}else{$address2 = '';}
				$city 			= $this->input->post('city');
				if ($this->input->post('county')!=''){$county = $this->input->post('county');}else{$county = '';}
				$postcode  		= $this->input->post('postcode');
				$country_code 	= $this->input->post('country');
				$shipping_id	= $this->input->post('shipping');
				$query = $this->db->query("SELECT shipping_title, shipping_countries FROM shipping WHERE shipping_id='$shipping_id'");
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$shipping 			= $row->shipping_title;
						$shipping_countries = $row->shipping_countries;
					}
				}
				$ship_countries_array = explode(",",$shipping_countries);
				if (in_array($country_code,$ship_countries_array)){
					$this->session->set_userdata('user_shipping', $shipping);
					$query = $this->db->query("SELECT country_title FROM countries WHERE country_code='$country_code'");
					if ($query->num_rows()>0){
						foreach ($query->result() as $key => $row){
							$country = $row->country_title;
						}
					}
					if ($this->session->userdata('user_id')!=''){
						$user_id = $this->session->userdata('user_id');
						$data = array(
							'user_date' 		=> date('Y-m-d H:i:s'),
							'user_ip' 			=> $this->session->userdata('ip_address'),
							'user_title' 		=> $title,
							'user_firstname' 	=> $firstname,
							'user_lastname' 	=> $lastname,
							'user_email' 		=> $email,
							'user_password' 	=> $password,
							'user_address1' 	=> $address1,
							'user_address2' 	=> $address2,
							'user_city' 		=> $city,
							'user_county' 		=> $county,
							'user_postcode' 	=> $postcode,
							'user_country' 		=> $country
							);
						$this->db->where('user_id', $user_id);
						$this->db->update('users', $data);
					}else{
						$query = $this->db->query("SELECT user_id FROM users WHERE user_email='$email'");
						if ($query->num_rows()>0){
							foreach ($query->result() as $key => $row){
								$user_id = $row->user_id;
							}
							$data = array(
								'user_date' 		=> date('Y-m-d H:i:s'),
								'user_ip' 			=> $this->session->userdata('ip_address'),
								'user_title' 		=> $title,
								'user_firstname' 	=> $firstname,
								'user_lastname' 	=> $lastname,
								'user_email' 		=> $email,
								'user_password' 	=> $password,
								'user_address1' 	=> $address1,
								'user_address2' 	=> $address2,
								'user_city' 		=> $city,
								'user_county' 		=> $county,
								'user_postcode' 	=> $postcode,
								'user_country' 		=> $country
								);
							$this->db->where('user_id', $user_id);
							$this->db->update('users', $data);
						}else{
							$data = array(
								'user_date' 		=> date('Y-m-d H:i:s'),
								'user_ip' 			=> $this->session->userdata('ip_address'),
								'user_title' 		=> $title,
								'user_firstname' 	=> $firstname,
								'user_lastname' 	=> $lastname,
								'user_email' 		=> $email,
								'user_password' 	=> $password,
								'user_address1' 	=> $address1,
								'user_address2' 	=> $address2,
								'user_city' 		=> $city,
								'user_county' 		=> $county,
								'user_postcode' 	=> $postcode,
								'user_country' 		=> $country
								);
							$this->db->insert('users', $data);
						}
						$query = $this->db->query("SELECT user_id FROM users WHERE user_email='$email'");
						if ($query->num_rows()>0){
							foreach ($query->result() as $key => $row){
								$this->session->set_userdata('user_id', $row->user_id);
							}
						}
					}
					$data['msg'] = 1;
				}
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_view_categories()
	{
		if ($this->input->post('sort')!=''){
			$view = $this->input->post('sort');
			$this->session->set_userdata('sort_view',$view);
			$data['msg'] = 'View Changed!';
		}else{
			$data['msg'] = 'View Not Changed!';
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function edit_categories()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('type')=='new' && $this->input->post('title')!=''){
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$query = $this->db->query("SELECT category_key FROM categories WHERE category_key='$created_key'");
				if ($query->num_rows()>0){
					$data = array();
					$data['msg'] = 'Duplicate Key!';
				}else{
					$data = array(
						'category_key' 			=> $created_key,
						'category_title' 		=> $this->input->post('title'),
						'category_parentkey' => $this->input->post('parentkey')
						);
					$this->db->insert('categories', $data);
					$data = array();
					$data['msg'] = 'Added!';
				}
			}
		
			if ($this->input->post('type')=='edit' && $this->input->post('title')!=''){
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$data = array(
					'category_key' 			=> $created_key,
					'category_title' => $this->input->post('title'),
					'category_parentkey' => $this->input->post('parentkey')
					);
				$this->db->where('category_key', $this->input->post('key'));
				$this->db->update('categories', $data);
				$data = array('product_catkey' => $created_key);
				$this->db->where('product_catkey', $this->input->post('key'));
				$this->db->update('products', $data);
				$data = array('category_parentkey' => $created_key);
				$this->db->where('category_parentkey', $this->input->post('key'));
				$this->db->update('categories', $data);
				$data = array();
				$data['msg'] = 'Saved!';
			}
		
			if ($this->input->post('type')=='delete'){
				$catkey = $this->input->post('key');
				$query = $this->db->query("SELECT product_key,product_image FROM products WHERE product_catkey='$catkey'");
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$file_key 	= $row->product_key;
						$ext 		= strrchr($row->product_image, '.');
						if (file_exists('images/products/'.$file_key.$ext)){
							unlink('images/products/'.$file_key.$ext);
					    }
						if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
						}
					}
				}
				$this->db->where('product_catkey', $catkey);
				$this->db->delete('products');
				$this->db->where('category_key', $catkey);
				$this->db->delete('categories');
				$query = $this->db->query("SELECT category_key FROM categories WHERE category_parentkey='$catkey'");
				$category_keys = array();
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$category_keys[] = $row->category_key;
					}
				}
				$this->db->where('category_parentkey', $catkey);
				$this->db->delete('categories');
				if (count($category_keys)>0){
					foreach ($category_keys as $value){
						$query2 = $this->db->query("SELECT product_key,product_image FROM products WHERE product_catkey='$value'");
						if ($query2->num_rows()>0){
							foreach ($query2->result() as $key2 => $row2){
								$file_key 	= $row2->product_key;
								$ext = strrchr($row2->product_image, '.');
								if (file_exists('images/products/'.$file_key.$ext)){
									unlink('images/products/'.$file_key.$ext);
							    }
								if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
									unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
								}
								if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
									unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
								}
								if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
									unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
								}
							}
						}
						$this->db->where('product_catkey', $value);
						$this->db->delete('products');
					}
				}
				$data = array();
				$data['msg'] = 'Deleted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_categories()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('order')!=''){
				$order_array = explode("divContainer[]=", urldecode($this->input->post('order')));
				$i=0;
				foreach($order_array as $order){
					$order=str_replace('&','',$order);
					if ($order!=''){
						$data = array('category_order' => $i);
						$this->db->where('category_key', $order);
						$this->db->update('categories', $data);
					}
					$i++;
				}
			}
		}
		$this->load->view('_ajax/_blank');
	}
	
	function edit_shipping()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('title')!='' && $this->input->post('price')!=''){
				$shipping_title 	= preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));
				$shipping_price		= $this->input->post('price');
				if ($this->input->post('shipping_default')=='true'){
					$shipping_default 	= 1;
					$data = array('shipping_default' => 0);
					$this->db->where('shipping_default', 1);
					$this->db->update('shipping', $data);
				}else{
					$shipping_default = 0;
				}
				if ($this->input->post('type')=='new'){
					$query = $this->db->query("SELECT shipping_title FROM shipping WHERE shipping_title='$shipping_title'");
					if ($query->num_rows()>0){
						$data = array();
						$data['msg'] = 'Duplicate Title!';
					}else{
						$data = array(
							'shipping_title' 	=> $shipping_title,
							'shipping_price' 	=> $shipping_price,
							'shipping_default'	=> $shipping_default,
							'shipping_active'	=> 1
							);
						$this->db->insert('shipping', $data);
						$data = array();
						$data['msg'] = 'Added!';
					}
				}
				if ($this->input->post('type')=='edit'){
					$data = array(
						'shipping_title' 	=> $shipping_title,
						'shipping_price' 	=> $shipping_price,
						'shipping_default' 	=> $shipping_default
						);
					$this->db->where('shipping_id', $this->input->post('key'));
					$this->db->update('shipping', $data);
					$data = array();
					$data['msg'] = 'Saved!';
				}
				if ($this->input->post('type')=='delete'){
					$this->db->where('shipping_id', $this->input->post('key'));
					$this->db->delete('shipping');
					$data = array();
					$data['msg'] = 'Deleted!';
				}
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function edit_shipping_country()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('key')!='' && $this->input->post('code')!='' && $this->input->post('code')!='-1'){
				$shipping_id = $this->input->post('key');
				$query = $this->db->query("SELECT shipping_countries FROM shipping WHERE shipping_id='$shipping_id'");
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$cc_array = explode(",", $row->shipping_countries);
						$country_codes = $row->shipping_countries;
					}
				}
				foreach ($cc_array as $cc) {
					$country_codes_array[$cc] = $cc;
				}
				if (in_array($this->input->post('code'),$country_codes_array)){
					unset($country_codes_array[$this->input->post('code')]);
					$updated_countries = implode(",",$country_codes_array);
					$data = array('shipping_countries' => $updated_countries);
					$this->db->where('shipping_id', $shipping_id);
					$this->db->update('shipping', $data);
				}else{
					if ($country_codes!=''){
						$country_codes.=','.$this->input->post('code');
					}else{
						$country_codes.=$this->input->post('code');
					}
			 		$data = array('shipping_countries' => $country_codes);
					$this->db->where('shipping_id', $shipping_id);
					$this->db->update('shipping', $data);
				}
				$data['msg'] = 'Updated!';
			}else{
				$data['msg'] = 'Not Updated!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_products()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('order')!=''){
				$order_array = explode("divContainer[]=", urldecode($this->input->post('order')));
				$i=0;
				foreach($order_array as $order){
					$order=str_replace('&','',$order);
					if ($order!=''){
						$data = array('product_order' => $i);
						$this->db->where('product_key', $order);
						$this->db->update('products', $data);
					}
					$i++;
				}
			}
		}
		$this->load->view('_ajax/_blank');
	}
	
	function edit_products()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('type')=='new' && $this->input->post('title')!=''){
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$product_buy = $this->input->post('buy');
				if ($product_buy=='true'){
					$can_buy=1;
				}else{
					$can_buy=0;
				}
				$query = $this->db->query("SELECT product_key FROM products WHERE product_key='$created_key'");
				if ($query->num_rows()>0){
					$data = array();
					$data['msg'] = 'Duplicate Key!';
				}else{
					$data = array(
						'product_key' 			=> $created_key,
						'product_title' 		=> $this->input->post('title'),
						'product_description' 	=> utf8_encode(htmlentities($this->input->post('description'))),
						'product_price' 		=> str_replace(',', '', $this->input->post('price')),
						'product_buy' 			=> $can_buy,
						'product_catkey' 		=> $this->input->post('catkey')
						);
					$this->db->insert('products', $data);
					$data = array();
					$data['msg'] = 'Added!';
				}
				$this->session->set_flashdata('expand_this', $created_key);
			}
		
			if ($this->input->post('type')=='edit' && $this->input->post('title')!=''){
				$product_key = $this->input->post('key');
				$stripped_string = preg_replace('/[^A-Za-z0-9 ]/', '', $this->input->post('title'));  
				$created_key = strtolower(str_replace(' ', '-', $stripped_string));
				$query = $this->db->query("SELECT product_image FROM products WHERE product_key='$product_key'");
				if ($query->result()>0){
					foreach ($query->result() as $key => $row){
						$name = $row->product_image;
					}
					$ext = strrchr($name, '.');
					if (file_exists('images/products/_thumbs/'.$product_key.'_100x100'.$ext)){
						unlink('images/products/_thumbs/'.$product_key.'_100x100'.$ext);
					}
					if (file_exists('images/products/_thumbs/'.$product_key.'_150x150'.$ext)){
						unlink('images/products/_thumbs/'.$product_key.'_150x150'.$ext);
					}
					if (file_exists('images/products/_thumbs/'.$product_key.'_400x400'.$ext)){
						unlink('images/products/_thumbs/'.$product_key.'_400x400'.$ext);
					}
					if (file_exists('images/products/'.$product_key.$ext)){
						rename('images/products/'.$product_key.$ext,'images/products/'.$created_key.$ext);
					}
					$data = array(
						'product_image' => $created_key.$ext
						);
					$this->db->where('product_key', $product_key);
					$this->db->update('products', $data);
				}
				$product_buy = $this->input->post('buy');
				if ($product_buy=='true'){
					$can_buy=1;
				}else{
					$can_buy=0;
				}
				$data = array(
					'product_key' 			=> $created_key,
					'product_title' 		=> $this->input->post('title'),
					'product_description' 	=> utf8_encode(htmlentities($this->input->post('description'))),
					'product_price' 		=> str_replace(',', '', $this->input->post('price')),
					'product_buy' 			=> $can_buy,
					'product_catkey' 		=> $this->input->post('catkey')
					);
				$this->db->where('product_key', $product_key);
				$this->db->update('products', $data);
				$data = array();
				$data['msg'] = 'Saved!';
				$this->session->set_flashdata('expand_this', $created_key);
			}
			if ($this->input->post('type')=='delete'){
				$file_key = $this->input->post('key');
				$query = $this->db->query("SELECT product_image FROM products WHERE product_key='$file_key'");
				if ($query->num_rows()>0){
					foreach ($query->result() as $key => $row){
						$ext = strrchr($row->product_image, '.');
						if (file_exists('images/products/'.$file_key.$ext)){
							unlink('images/products/'.$file_key.$ext);
					    }
						if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
						}
						if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
							unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
						}
					}
				}
				$this->db->where('product_key', $this->input->post('key'));
				$this->db->delete('products');
				$data = array();
				$data['msg'] = 'Deleted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function upload_image()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($_FILES){
				$image_checked = 0;
				foreach ($_FILES as $key => $value){
					$image_mime_types = array(
				       					'png' 		=> 'image/png',
				            			'jpe' 		=> 'image/jpeg',
				            			'jpeg' 		=> 'image/jpeg',
				            			'jpg' 		=> 'image/jpeg',
										'ie_jpg'	=> 'image/pjpeg',
				            			'gif' 		=> 'image/gif',
				            			'bmp' 		=> 'image/bmp',
				            			'tiff' 		=> 'image/tiff',
				            			'tif' 		=> 'image/tiff',
				            			'svg' 		=> 'image/svg+xml',
				            			'svgz' 		=> 'image/svg+xml'
										);
					foreach ($image_mime_types as $mime){
						if ($value['type']==$mime){
							$image_checked = 1;
						}
					}
					if ($image_checked==1){
						$file_key = str_replace('file_','',$key);
						$query = $this->db->query("SELECT product_image FROM products WHERE product_key='$file_key'");
						foreach ($query->result() as $key => $row){
							$name = $row->product_image;
						}
						if ($value['error']==0){
							$ext1 = strtolower(strrchr($value['name'], '.'));
							$ext2 = strrchr($name, '.');
						    if (file_exists('images/products/'.$file_key.$ext1)){
								unlink('images/products/'.$file_key.$ext1);
						    }
						    if (file_exists('images/products/'.$file_key.$ext2)){
								unlink('images/products/'.$file_key.$ext2);
						    }
							if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext1)){
								unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext1);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext2)){
								unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext2);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext1)){
								unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext1);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext2)){
								unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext2);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext1)){
								unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext1);
							}
							if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext2)){
								unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext2);
							}
							move_uploaded_file($value['tmp_name'],'images/products/'.$file_key.$ext1);
							$data = array(
								'product_image' => $file_key.$ext1
								);
							$this->db->where('product_key', $file_key);
							$this->db->update('products', $data);
						}
					}
				}
			}
			$data = array();
			$data['msg'] = 'Uploaded!';
			$this->session->set_flashdata('expand_this', $file_key);
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function reset_image()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			if ($this->input->post('key')!=''){
				$file_key = $this->input->post('key');
				$query = $this->db->query("SELECT product_image FROM products WHERE product_key='$file_key'");
				foreach ($query->result() as $key => $row){
					$name = $row->product_image;
				}
				$ext = strrchr($name, '.');
			    if (file_exists('images/products/'.$file_key.$ext)){
					unlink('images/products/'.$file_key.$ext);
			    }
				if (file_exists('images/products/_thumbs/'.$file_key.'_100x100'.$ext)){
					unlink('images/products/_thumbs/'.$file_key.'_100x100'.$ext);
				}
				if (file_exists('images/products/_thumbs/'.$file_key.'_150x150'.$ext)){
					unlink('images/products/_thumbs/'.$file_key.'_150x150'.$ext);
				}
				if (file_exists('images/products/_thumbs/'.$file_key.'_400x400'.$ext)){
					unlink('images/products/_thumbs/'.$file_key.'_400x400'.$ext);
				}
				$data = array(
					'product_image' => ''
					);
				$this->db->where('product_key', $file_key);
				$this->db->update('products', $data);
				$data = array();
				$data['msg'] = 'Reset!';
			}else{
				$data = array();
				$data['msg'] = 'Not Reset!';
			}
			$this->session->set_flashdata('expand_this', $file_key);
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_alpha_products()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			$data = array();
			if ($this->input->post('sort')=='alpha'){
				$i=0;
				$query = $this->db->query("SELECT product_key, product_title FROM products ORDER BY product_title");
				foreach ($query->result() as $key => $row){
					$data = array('product_order' => $i);
					$this->db->where('product_key', $row->product_key);
					$this->db->update('products', $data);
					$i++;
				}
				$data['msg'] = 'Sorted A-Z!';
			}else{
				$data['msg'] = 'Not Sorted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
	function sort_alpha_categories()
	{
		if ($this->session->userdata('user_level')!=1){
			$data['msg'] = 'Admin Only!';
		}else{
			$data = array();
			if ($this->input->post('sort')=='alpha' && $this->input->post('parent')!=''){
				$parent = $this->input->post('parent');
				$i=0;
				$query = $this->db->query("SELECT category_key, category_title FROM categories WHERE category_parentkey='$parent' ORDER BY category_title");
				foreach ($query->result() as $key => $row){
					$data = array('category_order' => $i);
					$this->db->where('category_key', $row->category_key);
					$this->db->update('categories', $data);
					$i++;
				}
				$data['msg'] = 'Sorted A-Z!';
			}else{
				$data['msg'] = 'Not Sorted!';
			}
		}
		$this->load->view('_ajax/_blank',$data);
	}
	
}

/* End of file ajax.php */
/* Location: ./public_html/application/controllers/ajax.php */