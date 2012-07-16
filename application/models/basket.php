<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Basket extends CI_Model
{
	
	//-----set_session
	function set_session()
	{
		//-----
		$sessid = '';
		
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}
		
		$sessid .= $this->session->userdata('ip_address');
		
		$session_key = md5(uniqid($sessid, TRUE));
		
		$this->session->set_userdata('public_session', $session_key);
		
		return $session_key;
		//-----/
	}
	//-----/set_session
	
	
	//-----add_to_basket	
	function add_to_basket()
	{
		//-----
		if ($this->session->userdata('basket_id') == '')
		{
			//-----
			$basket_id = '';
			
			while (strlen($basket_id) < 32)
			{
				$basket_id .= mt_rand(0, mt_getrandmax());
			}
			
			$basket_id .= $this->session->userdata('ip_address');
			
			$baskey_key = md5(uniqid($basket_id, TRUE));
			
			$this->session->set_userdata('basket_id', $baskey_key);
			//-----/
		}
		
		$data = array();
		
		if ($this->input->post('product')!='')
		{
			//-----
			$product = $this->input->post('product');
			
			$query = $this->db->query("SELECT product_key FROM products WHERE product_key='$product' LIMIT 1");
			
			if ($query->num_rows() > 0 )
			{
				foreach ($query->result() as $key => $row)
				{
					$product_key = $row->product_key;
				}
				
				$basket_data = $this->session->userdata('basket');
				
				if (isset($basket_data[$product_key]['qty']))
				{
					$basket_data[$product_key]['qty']++;
				}
				else
				{
					$basket_data[$product_key]['key']=$product_key;
					$basket_data[$product_key]['qty']=1;
				}
				
				
				$this->session->set_userdata('basket',$basket_data);
				
				return '<strong>Item added!</strong>';
				//-----/
			}
			else
			{
				return 'Unable to add item to basket!';
			}
		}
		else
		{
			return 'Unable to add item to basket!';
		}
		//-----/
	}
	//-----/add_to_basket
	
	
	//-----update_basket_qty	
	function update_basket_qty()
	{
		$basket_qty = 0; 
		
		if (is_array($this->session->userdata('basket')))
		{
			$basket_data = $this->session->userdata('basket');
			foreach ($basket_data as $value)
			{
				$basket_qty += $value['qty'] ;
			}
		}
		
		return $basket_qty;
		//-----/
	}
	//-----/update_basket_qty
	
	
	//-----update_basket_total
	function update_basket_total()
	{
		//-----
		$basket_total = 0.00;
		
		if (is_array($this->session->userdata('basket')))
		{
			$basket_data = $this->session->userdata('basket');
			
			$key_test  = '';
			
			foreach ($basket_data as $value)
			{
				$query = $this->db->query("SELECT product_price FROM products WHERE product_key='$value[key]'");
			
				foreach ($query->result() as $key => $row)
				{
				    $basket_total += ( $row->product_price*$value['qty'] );
				}
			}
		}
		
		return $basket_total;
		//-----/
	}
	//-----/update_basket_total
	
	
}
/* End of file basket.php */
/* Location: ./public_html/application/models/basket.php */