<?php  if ( !defined('BASEPATH')) { exit('No direct script access allowed') ; } ?>
	
<script type="text/javascript" src="<?php echo base_url(); ?>javascript/prototype.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>javascript/lightbox.js"></script>
<script type="text/javascript" charset="utf-8">
function add_to_basket(key)
{
	var poststr = 'product='+key;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/basket_add', 'status_'+key, poststr, 'POST', '', update_basket, key);
}
function update_basket(key)
{
	var getdata = document.getElementById('status_'+key).innerHTML;
	var getdata_arr = getdata.split('|');
	document.getElementById('status_'+key).innerHTML=getdata_arr[0];
	document.getElementById('status_'+key).style.display='';
	document.getElementById('basket_qty').innerHTML=getdata_arr[1];
	document.getElementById('basket_total').innerHTML=getdata_arr[2];
	window.setTimeout('document.getElementById("status_'+key+'").innerHTML="";', 2000);
}
</script>
<?php

echo $products['parent_title'].'&nbsp;>&nbsp;<a href="'.base_url().'products/c/'.$products['catkey'].'">'.$products['cat_title'].'</a>&nbsp;>&nbsp;<strong>'.$products['title'].'</strong>'."\n" ;

echo '<div style="margin-top:5px; width:506px">'."\n" ;

echo '<table class="normal">'."\n" ;
echo '<tr>'."\n" ;
echo '<td align="center" width="340"><span style="font-size:14px"><strong>'.$products['title'].'</strong></span>'."\n" ;
echo '<br />'."\n" ;
echo '</td>'."\n" ;
echo '<td>&nbsp;</td>'."\n" ;
echo '</tr>'."\n" ;
echo '<tr>'."\n" ;
echo '<td>'."\n" ;
echo '<br />'."\n" ;
echo nl2br2($products['description'])."\n" ;
echo '</td>'."\n" ;
echo '<td rowspan="3" valign="top" align="center" width="150">'."\n" ;

if ($products['image']!='')
{
	$image_file = 'images/products/'.$products['image'];
	
	if (file_exists($image_file))
	{
		echo '<a href="'.base_url().'lightbox/i/'.$products['image'].'" class="lbOn">'.image_thumb('images/products/'.$products['image'], 150, 150);
		echo '<br /><img src="'.base_url().'images/zoom.gif" alt="Zoom">Zoom</a>'."\n" ;
	}
	else
	{
		echo '<img src="'.base_url().'images/noimage_150x150.gif" alt="No Image!">'."\n" ;
	}
}
else
{
	echo '<img src="'.base_url().'images/noimage_150x150.gif" alt="No Image!">'."\n" ;
}
echo '</td>'."\n" ;
echo '</tr>'."\n" ;
echo '<tr>'."\n" ;
echo '<td>'."\n" ;
echo '<br />'."\n" ;
echo '<span style="color:#444">Price:</span>&nbsp;<span style="color:#990000"><strong>&pound;'.number_format($products['price'],2).'</strong></span>'."\n" ;
echo '<br />'."\n" ;
echo '<br />'."\n" ;
echo '</td>'."\n" ;
echo '</tr>'."\n" ;

if ($products['buy']==1)
{
	echo '<tr>'."\n" ;
	echo '<td><input type="button" class="buttonstyle" value="Add To Basket" onclick="add_to_basket(\''.$products['key'].'\');">&nbsp;<span id="status_'.$products['key'].'" style="display:none"></span></td>'."\n" ;
	echo '</tr>'."\n" ;
}
else
{
	echo '<tr>'."\n" ;
	echo '<td><strong>Please contact us to buy this item!</strong>&nbsp;<span id="status_'.$products['key'].'" style="display:none"></span></td>'."\n" ;
	echo '</tr>'."\n" ;
}

echo '</table>'."\n" ;

echo '</div>'."\n" ;

echo '<p align="center"><input type="button" class="buttonstyle" value="<< Back" onClick="parent.history.back();"></p>'."\n" ;
//-----/