<?php  if ( !defined('BASEPATH')) { exit('No direct script access allowed') ; } ?>

<script type="text/javascript" charset="utf-8">
function add_to_basket(key){
	var poststr = 'product='+key;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/basket_add', 'status_'+key, poststr, 'POST', '', update_basket, key);
}
function sort_view(sort){
	var poststr = 'sort='+sort;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/sort_view_categories', 'sort_status', poststr, 'POST', '', refresh_page);
}
function update_basket(key){
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
$views = array(
	'default' 		=> 'Best Match',
	'price_asc' 	=> 'Price: lowest first',
	'price_desc' 	=> 'Price: highest first',
	'item_az' 		=> 'Condition: sort a-z',
	'item_za' 		=> 'Condition: sort z-a'
	);

echo '<table class="normal" cellpadding="0" cellspacing="0" width="506"><tr>';
echo '<td align="left" valign="top">'.$category['parent_title'].'&nbsp;>&nbsp;<strong>'.$category['cat_title'].'</strong></td>';
echo '<td align="right"><span style="color:#444;font-weight:bold">Sort by:</span>&nbsp;&nbsp;';
echo '<select id="sort_by" onchange="sort_view(this.value)">';
foreach ($views as $key => $value) 
{
	if ($sort_view==$key)
	{
		$selected = ' SELECTED';
	}
	else
	{
		$selected = '';
	}
	echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
}
echo '</select><span id="sort_status" style="display:none"></span><br /><br />';
echo '</td>';
echo '</tr></table>';

$i=0;

foreach ($products as $product_info) 
{
	if ($i!=0)
	{	
		echo '<hr style="height:1px; background:#ACA899; border:none;">';	
	}
	
	echo '<div style="width:506px">'."\n" ;
	echo '<table class="normal">'."\n" ;
	echo '<tr>'."\n" ;
	echo '<td rowspan="4" style="padding-right:10px: width:100px;">'."\n" ;
	
	if ($product_info['image']!='')
	{
		$image_file = 'images/products/'.$product_info['image'];
		
		if (file_exists($image_file))
		{
			echo '<a href="'.base_url().'products/p/'.$product_info['key'].'">'.image_thumb('images/products/'.$product_info['image'], 100, 100).'</a>';
		}
		else
		{
			echo '<a href="'.base_url().'products/p/'.$product_info['key'].'"><img src="'.base_url().'images/noimage_100x100.gif" alt="No Image!"></a>';
		}
	}
	else
	{
		echo '<a href="'.base_url().'products/p/'.$product_info['key'].'"><img src="'.base_url().'images/noimage_100x100.gif" alt="No Image!"></a>';
	}
	
	echo '</td>'."\n" ;
	echo '<td colspan="2"><span style="font-size:13px"><a href="'.base_url().'products/p/'.$product_info['key'].'"><strong>'.$product_info['title'].'</strong></a></span></td>'."\n" ;
	echo '</tr>'."\n" ;
	echo '<tr>'."\n" ;
	echo '<td width="400" colspan="2">'.$product_info['description'].'</td>'."\n" ;
	echo '</tr>'."\n" ;
	echo '<tr>'."\n" ;
	echo '<td rowspan="2"><input type="button" class="buttonstyle" value="More Details" onclick="parent.location=\''.base_url().'products/p/'.$product_info['key'].'\';"></td>';
	echo '<td align="right">';
	echo '<span style="color:#444">Price:</span>&nbsp;<span style="color:#990000"><strong>&pound;'.number_format($product_info['price'],2).'</strong></span>';
	echo '</td>'."\n" ;
	echo '</tr>'."\n" ;
	echo '<tr>'."\n" ;
	echo '<td align="right"><span id="status_'.$product_info['key'].'" style="display:none"></span>&nbsp;';
	if ($product_info['buy'] == 1 )
	{
		echo '<input type="button" class="buttonstyle" value="Add To Basket" onclick="add_to_basket(\''.$product_info['key'].'\');">';
	}
	else
	{
		echo '<strong>Please contact us to buy this item!</strong>';
	}
	echo '</td>'."\n" ;
	echo '</tr>'."\n" ;
	echo '</table>'."\n" ;
	echo '</div>'."\n" ;
	
	$i++;
}

echo '<br /><div style="text-align:center">'.$this->pagination->create_links().'</div><br />';

?>