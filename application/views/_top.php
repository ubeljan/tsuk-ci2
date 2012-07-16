<?php  if ( !defined('BASEPATH')) { exit('No direct script access allowed') ; } ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="A lightweight demo of TSUK eCommerce, many features have been removed. Please purchase to receive full functionality" />
	<meta name="keywords" content="tsuk demo, demo ecommerce, free ecommerce, lightweight ecommerce, codeigniter ecommerce" />
	
	<title>TSUK Demo :: eCommerce</title>
	
	<base href="<?php echo base_url(); ?>" />
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/lightbox.css" type="text/css" />
	
	<!--[if IE]>
	<style type="text/css">
	#overlay {
		filter:alpha(opacity=80);
	}
	</style>
	<![endif]-->
	
	<!--[if lte IE 6]>
	<style type="text/css">
		img,div,input { 
		behavior:url('<?php echo base_url(); ?>images/iepngfix.htc');
	}
	</style>
	<![endif]-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css" charset="utf-8" />
	
	<script type="text/javascript" src="<?php echo base_url(); ?>javascript/ajax.js"></script>
	
	<script type="text/javascript" charset="utf-8">
	function cancel_basket(){
		var poststr = 'reset=true';
		ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/reset_basket', 'cancel_status', poststr, 'POST', '', refresh_page);
	}
	function refresh_page(){
		parent.location="<?php echo $_SERVER['REQUEST_URI']; ?>";	
	}
	</script>

	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->	
	
</head>
<body>
<table class="main" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="3" class="banner"><h1>TSUK Lightweight Demo</h1></td>
	</tr>
	<tr>
		<td colspan="3" class="top">			
<?php 
			
	if ($this->session->userdata('user_id')=='')
	{ 
		echo '<a href="'.base_url().'login">[ Login ]</a>';
	}
	else
	{ 
		echo '<a href="'.base_url().'logout">[ Logout ]</a>'; 
	}  
?>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="divider"> </td>
	</tr>
	<tr>
		<td colspan="3" class="nav">
			<a href="<?php echo base_url(); ?>">Home</a>
			<a href="<?php echo base_url(); ?>about">About Us</a>
			<a href="<?php echo base_url(); ?>faq">FAQ</a>
			<a href="<?php echo base_url(); ?>contact">Contact Us</a>
		</td>
	</tr>
	<tr>
		<td class="left">
			<?php
			
			if ( $level == 1 )
			{
			
			?>
			<table class="subleft" cellpadding="0" cellspacing="0">
				<tr class="sublefthdr">
					<td>Admin</td>
				</tr>
				<tr class="subleftcntmargin">
					<td> </td>
				</tr>
				<tr class="subleftcnt">
					<td><a href="<?php echo base_url(); ?>admin/categories">Categories</a></td>
				</tr>
				<tr class="subleftcnt">
					<td><a href="<?php echo base_url(); ?>admin/products">Products</a></td>
				</tr>
				<tr class="subleftcnt">
					<td><a href="<?php echo base_url(); ?>admin/shipping">Shipping</a></td>
				</tr>
				<tr class="subleftcntmargin">
					<td> </td>
				</tr>
			</table>
			<?php
			
			}			
					
			if ( isset($categories) )
			{
				if ( count($categories) > 0 )
				{
					foreach ( $categories as $value )
					{
						if ( $value['parentkey'] == '_top' )
						{
							
							echo '<table class="subleft" cellpadding="0" cellspacing="0">'."\n" ;
							echo '<tr class="sublefthdr">'."\n" ;
							echo '<td>'.$value['title'].'</td>'."\n" ;
							echo '</tr>'."\n" ;
							echo '<tr class="subleftcntmargin">'."\n" ;
							echo '<td> </td>'."\n" ;
							echo '</tr>'."\n" ;
						}
				
						foreach ( $categories as $value2 )
						{				
							if ( $value2['parentkey'] == $value['key'] )
							{
								echo '<tr class="subleftcnt">'."\n" ;
								echo '<td><a href="'.url().'products/c/'.$value2['key'].'">'.$value2['title'].'</a></td>'."\n" ;
								echo '</tr>'."\n" ;		
							}
						}
				
						if ($value['parentkey']=='_top')
						{
							echo '<tr class="subleftcntmargin">'."\n" ;
							echo '<td> </td>'."\n" ;
							echo '</tr>'."\n" ;
							echo '</table>'."\n" ;
						}

					}
				}
			}
			else
			{
				echo '<table class="subleft" cellpadding="0" cellspacing="0">'."\n" ;
				echo '<tr class="sublefthdr"><td>No Categories</td></tr>'."\n" ;
				echo '<tr class="subleftcntmargin"><td></td></tr>'."\n" ;
				echo '<tr class="subleftcnt"><td><a href="javascript:;">No Subcategories</a></td></tr>'."\n" ;
				echo '<tr class="subleftcntmargin"><td></td></tr>'."\n" ;
				echo '</table>'."\n" ;
			}
			
			?>
		</td>
		<td class="center">
			<table class="subcenter">
				<tr>
					<td class="subcentercnt">
						<noscript class="error"><p><img width="506" height="41" alt="" src="<?php echo base_url(); ?>images/noscript.gif"></p></noscript>