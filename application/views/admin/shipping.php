
<script type="text/javascript" charset="utf-8">
function save_shipping(key,type){
	var title = URLEncode(document.getElementById('title_'+key).value);
	var price = URLEncode(document.getElementById('price_'+key).value);
	var shipping_default = document.getElementById('default_'+key).checked;
	var poststr = 'type='+type+'&key='+key+'&title='+title+'&price='+price+'&shipping_default='+shipping_default;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/edit_shipping', 'status_'+key, poststr, 'POST', '', refresh);
	window.setTimeout('document.getElementById("status_'+key+'").innerHTML="";', 2000);
}
function refresh(){
	parent.location="<?php echo $this->config->item('app_pad') ; ?>admin/shipping";	
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
function update_ship_country(key,code){
	var poststr = 'key='+key+'&code='+code;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/edit_shipping_country', 'status_'+key, poststr, 'POST', '', refresh);
}
</script>
<?php
echo '<div style="text-align:left; margin-bottom:5px">Admin&nbsp;&gt;&nbsp;<strong>Shipping</strong></div>';
?>
<input type="button" class="buttonstyle" value="Add New" onclick="add_new('new_shipping');">
<div id="new_shipping" style="margin-top:5px; border:1px #7A7A9A solid;display:none">
<input type="hidden" id="new_toggle" value="off">
<table class="normal" style="background:#F1F1F1" width="100%;">
<tr><td>Title:&nbsp;<input type="text" id="title_new" class="textstyle" style="width:235px" value="">&nbsp;&pound;&nbsp;<input type="text" id="price_new" class="textstyle" style="width:70px" value="">&nbsp;<input type="radio" id="default_new" name="shipping_default">&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_shipping('new','new');">&nbsp;<span id="status_new"></span></td></tr>
</table>
</div>
<?php
if (!empty($shipping))
{	
	foreach ($shipping as $key => $value)
	{
		if ($value['default']==1)
		{
			$checked = ' checked';
		}
		else
		{
			$checked = '';
		}
		echo '<div id="div_'.$key.'" style="margin-top:5px; border:1px #7A7A9A solid;">'."\n" ;
		echo '<table class="normal" style="background:#F1F1F1; width:100%;">'."\n" ;
		echo '<tr>'."\n" ;
		echo '<td>Title:&nbsp;<input type="text" id="title_'.$key.'" class="textstyle" style="width:100px" value="'.$value['title'].'">&nbsp;&pound;&nbsp;<input type="text" id="price_'.$key.'" class="textstyle" style="width:70px" value="'.number_format($value['price'],2).'">&nbsp;';
		echo '<select id="country_'.$key.'" style="width:135px" onchange="update_ship_country(\''.$key.'\',this.value)">'."\n" ;
		echo '<option value="-1">--ADDED--</option>'."\n" ;
		foreach ($value['countries_added'] as $key_added => $value_added) 
		{
			echo '<option value="'.$key_added.'">'.$value_added.'</option>';
		}
		echo '<option value="-1" SELECTED>--AVAILABLE--</option>';
		foreach ($value['countries_available'] as $key_avail => $value_avail)
		{
			echo '<option value="'.$key_avail.'">'.$value_avail.'</option>'."\n" ;
		}
		echo '</select>'."\n" ;
		echo '<input type="radio" id="default_'.$key.'" name="shipping_default"'.$checked.'>&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_shipping(\''.$key.'\',\'edit\');">&nbsp;<input type="button" class="buttonstyle" value="Delete" onclick="save_shipping(\''.$key.'\',\'delete\');"><span id="status_'.$key.'" style="display:none"></span>';
		echo '</td>'."\n" ;
		echo '</tr>'."\n" ;
		echo '</table>'."\n" ;
		echo '</div>'."\n" ;
	}
}
else
{
	echo '<br><br>No Shipping!'."\n" ;
}
//-----/