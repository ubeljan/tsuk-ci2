
<script src="<?php echo base_url(); ?>javascript/prototype.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>javascript/scriptaculous.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
document.observe('dom:loaded', function(){
	Sortable.create('divContainer',{
		onUpdate: function(item) {
		var list=Sortable.options(item).element;
		var poststr = 'order='+URLEncode(Sortable.serialize(list));
		ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/sort_categories', 'order_list', poststr, 'POST');
		},tag: 'div'
	});
});
function save_category(key,type){
	if (type=='delete'){
		if (confirm("This will delete all child products, do you wish to continue?")){
			var confirmed='Y';
		}else{
			var confirmed='N';
		}
	}else{
		var confirmed='Y';
	}
	if (confirmed=='Y'){
		var title = URLEncode(document.getElementById('title_'+key).value);
		var poststr = 'type='+type+'&key='+key+'&title='+title+'&parentkey=<?php echo $parent_key; ?>';
		ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/edit_categories', 'status_'+key, poststr, 'POST', '', refresh);
		window.setTimeout('document.getElementById("status_'+key+'").innerHTML="";', 2000);
	}
}
function sort_alpha(parent){
	var poststr = 'sort=alpha'+'&parent='+parent;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/sort_alpha_categories', 'order_list', poststr, 'POST', '', refresh);
}
function refresh(){
	parent.location="<?php echo $this->config->item('app_pad') ; ?>admin/categories/<?php echo $parent_key; ?>";	
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
</script>
<?php
echo '<div style="text-align:left; margin-bottom:5px">Admin&nbsp;&gt;&nbsp;<a href="'.url().'admin/categories">Categories</a>&nbsp;>&nbsp;<strong>'.$category_title.'</strong></div>';
?>
<input type="button" class="buttonstyle" value="Add New" onclick="add_new('new_category');">
<input type="button" class="buttonstyle" value="Refresh" onclick="refresh();">
<input type="button" class="buttonstyle" value="Sort A-Z" onclick="sort_alpha('<?php echo $parent_key; ?>');">
<div id="new_category" style="margin-top:5px; border:1px #7A7A9A solid;display:none">
<input type="hidden" id="new_toggle" value="off">
<table class="normal" style="background:#F1F1F1" width="100%;">
<tr><td>Title:&nbsp;<input type="text" id="title_new" class="textstyle" style="width:250px" value="">&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_category('new','new');">&nbsp;<span id="status_new"></span></td></tr>
</table>
</div>
<div id="divContainer">
<?php
$i=1;
if ($cats==1){	
	foreach ($category as $category_info) {
		echo '<div id="div_'.$category_info['key'].'" class="move" style="margin-top:5px; border:1px #7A7A9A solid;">';
		echo '<table class="normal" style="background:#F1F1F1" width="100%;">';
		echo '<tr><td>Title:&nbsp;<input type="text" id="title_'.$category_info['key'].'" class="textstyle" style="width:250px" value="'.$category_info['title'].'">&nbsp;<input type="button" class="buttonstyle" value="Save" onclick="save_category(\''.$category_info['key'].'\',\'edit\');">&nbsp;<input type="button" class="buttonstyle" value="Delete" onclick="save_category(\''.$category_info['key'].'\',\'delete\');"></td><td><img src="'.url().'images/icon_drag.png" alt="Move"><span id="status_'.$category_info['key'].'" style="display:none"></span></td></tr>';
		echo '</table>';
		echo '</div>';
		$i++;
	}
?>
</div>
<div id="order_list" style="display:none"></div>
<?php
}else{
	echo '<br><br>No Categories!';
}
?>