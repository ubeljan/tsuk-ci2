<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends CI_Model
{
	
	function get_content($name)
	{
		if ($name=='about')
		{
			$content['body'] = '<p align="center">About Page</p>';
		}
		elseif ($name=='faq')
		{
			$content['body'] = '<p align="center">FAQ Page</p>';
		}
		elseif ($name=='contact')
		{
			$content['body'] = '<p align="center">Contact Page</p>';
		}
		elseif ($name=='testimonials')
		{
			$content['body'] = '<p align="center">Testimonials Page</p>';
		}
		elseif ($name=='privacy')
		{
			$content['body'] = '<p align="center">Privacy Page</p>';
		}
		elseif ($name=='terms')
		{
			$content['body'] = '<p align="center">Terms and Conditions Page</p>';
		}
		else
		{
			$content['body'] = '<p align="center">TSUK Demo</p>';
		}
		
		return $content;
	}
	
}

/* End of file content.php */
/* Location: ./public_html/application/models/content.php */