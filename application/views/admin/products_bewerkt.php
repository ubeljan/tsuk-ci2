
<script src="<?php echo base_url(); ?>javascript/prototype.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>javascript/scriptaculous.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
document.observe('dom:loaded', function(){
	Sortable.create('divContainer',{
		onUpdate: function(item) {
		var list=Sortable.options(item).element;
		var poststr = 'order='+URLEncode(Sortable.serialize(list));
		ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/sort_products', 'order_list', poststr, 'POST');
		},tag: 'div'
	});
});
function save_product(key,type){
	var title = URLEncode(document.getElementById('title_'+key).value);
	var description = URLEncode(document.getElementById('description_'+key).value);
	var price = URLEncode(document.getElementById('price_'+key).value);
	var buy = document.getElementById('buy_'+key).checked;
	var catkey = URLEncode(document.getElementById('catkey_'+key).value);
	var poststr = 'type='+type+'&key='+key+'&title='+title+'&description='+description+'&price='+price+'&buy='+buy+'&catkey='+catkey;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/edit_products', 'status_'+key, poststr, 'POST', '', refresh);
	window.setTimeout('document.getElementById("status_'+key+'").innerHTML="";', 2000);
}
function sort_alpha(){
	var poststr = 'sort=alpha'
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/sort_alpha_products', 'order_list', poststr, 'POST', '', refresh);
}
function reset_image(key){
	var poststr = 'key='+key;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/reset_image', 'status_'+key, poststr, 'POST', '', refresh);
	window.setTimeout('document.getElementById("status_'+key+'").innerHTML="";', 2000);
}
function refresh(){
	parent.location="<?php echo $this->config->item('app_pad') ; ?>admin/products/<?php echo $current; ?>";
}
function add_new(key){
	var toggle = document.getElementById('new_toggle').value;
	if (toggle=='off'){
		document.getElementById('new_toggle').value='on';
		document.getElementById(key).style.display='';
	}else{
		document.getElementById('new_toggle').value='off';
		document.getElementById(key).style.display='none';
	}
}
function show_product(key){
	var disp_toggle = document.getElementById('prod_show_'+key).value;
	if (disp_toggle=='off'){
		document.getElementById('prod_show_'+key).value='on';
		document.getElementById('prod1_'+key).style.display='block';
		document.getElementById('div_'+key).style.display='none';
	}else{
		document.getElementById('prod_show_'+key).value='off';
		document.getElementById('prod1_'+key).style.display='none';
		document.getElementById('div_'+key).style.display='block';
	}
}
function completeCallback(response) {
    document.getElementById('ajax_response').innerHTML = response;
	refresh();
}
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>javascript/ajax_sendfile.js"></script>
<?php
echo '<div style="text-align:left; margin-bottom:5px">Admin&nbsp;&gt;&nbsp;<strong>Products</strong></div>';
?>
<select id="select_category" onChange="javascript:window.location='<?php echo base_url(); ?>admin/products/'+this.value">
<?php
foreach ($categories as $key => $value) 
{
	if ($key==$current) $selected=' SELECTED'; else $selected='';
	echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
}
?>
</select>
<input type="button" class="buttonstyle" value="Add New" onclick="add_new('new_product');">
<input type="button" class="buttonstyle" value="Sort A-Z" onclick="sort_alpha();">
<div id="new_product" style="margin-top:5px; border:1px #7A7A9A solid;display:none">
<input type="hidden" id="new_toggle" value="off">
<table class="normal" style="background:#F1F1F1" width="506px">
<tr><td>Title:<br><input type="text" id="title_new" class="textstyle" style="width:250px" value=""></td>
<td align="right">
<select id="catkey_new">
<?php
foreach ($categories as $key => $value) 
{
	if ($key==$current) $selected=' SELECTED'; else $selected='';
	echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
}
?>
</select>
</td>
</tr>
<tr><td>Description:<br><textarea id="description_new" class="textstyle" style="width:300px;height:150px" rows="10" cols="10"></textarea></td></tr>
<tr><td>&pound;&nbsp;<input type="text" id="price_new" class="textstyle" style="width:70px;" value="">&nbsp;<input type="checkbox" id="buy_new" checked>&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_product('new','new');">&nbsp;<span id="status_new"></span></td></tr>
</table>
</div>
<?php
if ( $prods== 1 )
{
	echo '<div id="divContainer">';
	foreach ($product as $product_info)
	{
		if ($product_info['buy'] == 1 )
		{
			$checked = ' checked';
		}
		else
		{
			$checked = '';
		}
		echo '<input type="hidden" id="prod_show_'.$product_info['key'].'" value="off">';
		echo '<div id="prod1_'.$product_info['key'].'" style="display:none">';
		echo '<div style="margin-top:5px; border:1px #7A7A9A solid;">';
		echo '<table id="table_'.$product_info['key'].'" class="normal" style="background:#F1F1F1" width="506px">';
		echo '<tr style="font-weight:bold"><td><span style="cursor:pointer" onclick="show_product(\''.$product_info['key'].'\');">[-]&nbsp;Title:</span><br><input type="text" id="title_'.$product_info['key'].'" class="textstyle" style="width:250px" value="'.$product_info['title'].'"></td>';
		echo '<td align="right">';
		echo '<select id="catkey_'.$product_info['key'].'" onchange="save_product(\''.$product_info['key'].'\',\'edit\');">';
		foreach ($categories as $key => $value)
		{
			if ($key==$product_info['catkey']) $selected=' SELECTED'; else $selected = '';
			echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
		}
		echo '</select>';
		echo '</td>';
		echo '</tr>';
		echo '<tr><td>Description:<br><textarea id="description_'.$product_info['key'].'" class="textstyle" style="width:300px;height:150px" rows="10" cols="10">'.$product_info['description'].'</textarea></td><td align="center" rowspan="2">';
		
		if ($product_info['product_image']!='')
		{
			$image_file = 'images/products/'.$product_info['product_image'];
			if (file_exists($image_file))
			{
				echo image_thumb('images/products/'.$product_info['product_image'], 100, 100);
			}
			else
			{
				echo '<img src="'.url().'images/noimage_100x100.gif" alt="No Image!">';
			}
		}
		else
		{
				echo '<img src="'.url().'images/noimage_100x100.gif" alt="No Image!">';
		}
		echo '</td></tr>';
		echo '<tr><td>&pound;&nbsp;<input type="text" id="price_'.$product_info['key'].'" class="textstyle" style="width:70px;" value="'.number_format($product_info['price'],2).'">&nbsp;<input type="checkbox" id="buy_'.$product_info['key'].'"'.$checked.'>&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_product(\''.$product_info['key'].'\',\'edit\');">&nbsp;<input type="button" class="buttonstyle" value="Delete" onclick="save_product(\''.$product_info['key'].'\',\'delete\');">&nbsp;<input type="button" class="buttonstyle" value="Reset Img" onclick="reset_image(\''.$product_info['key'].'\');">&nbsp;<span id="status_'.$product_info['key'].'"></span></td></tr>';
		echo '<tr><td colspan="2"><form action="'.url().'_ajax/upload_image" method="post" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {\'onComplete\' : completeCallback});">';
		echo 'Upload Image:<br>File: <input type="file" name="file_'.$product_info['key'].'">&nbsp;<input type="submit" class="buttonstyle" value="Upload"></form></td></tr>';
		echo '</table>';
		echo '</div>';
		echo '</div>';
		echo '<div id="div_'.$product_info['key'].'" class="move" style="display:block;">';
		echo '<div style="margin-top:5px; border:1px #7A7A9A solid;">';
		echo '<table id="table2_'.$product_info['key'].'" class="normal" style="background:#F1F1F1" width="506px">';
		echo '<tr><td><span style="cursor:pointer" onclick="show_product(\''.$product_info['key'].'\');">[+]&nbsp;'.$product_info['title'].'</span></td><td align="right" width="15px"><img src="'.url().'images/icon_drag.png" alt="Move"></td></tr>';
		echo '</table>';
		echo '</div>';
		echo '</div>';
	}
	
	echo '</div>';
	echo '<div id="ajax_response" style="display:none"></div>';
	echo '<div id="order_list" style="display:none"></div>';
	
	if ($expand_this != '')
	{
		echo '<script type="text/javascript" charset="utf-8">';
		echo 'show_product(\''.$expand_this.'\');';
		echo '</script>';
	}
}
else
{
	echo '<br /><br />No Products!';
}
?>