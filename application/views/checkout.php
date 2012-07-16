<?php  if ( !defined('BASEPATH')) { exit('No direct script access allowed') ; } ?>

<script type="text/javascript" charset="utf-8">
function refresh(){
	parent.location="<?php echo base_url(); ?>checkout";	
}
function update_items(){
	itemsForm = document.getElementById('items');
	poststr = 'items=';
	poststr += URLEncode('first=value');
	for (i=0; i < itemsForm.elements.length; i++){
		ele = itemsForm.elements[i];
		poststr += URLEncode('&'+ele.name+'='+ele.value);
	}
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/basket_update_items', 'update_status', poststr, 'POST', '', refresh);
}
function remove_item(key){
	var poststr = 'key='+key;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/basket_remove_item', 'remove_status', poststr, 'POST', '', refresh);
}
</script>
<div style="text-align:center;font-size:13px"><span style="font-weight:bold;color:#990000">Checkout</span>&nbsp;&gt;&nbsp;Customer&nbsp;&gt;&nbsp;Delivery&nbsp;&gt;&nbsp;Summary</div><br />
<form action="" id="items" onsubmit="return false;">
<table class="normal" align="center" width="506px" style="border:1px solid #7A7A9A;background:#F1F1F1;padding:2px">
	<tr>
		<td><strong>Item</strong></td>
		<td><strong>QTY</strong></td>
		<td><strong>Price</strong></td>
		<td><strong>Total</strong></td>
		<td>&nbsp;</td>
	</tr>
	<?php
	foreach ($basket as $product_info) {
		$total = $product_info['price']*$product_info['qty'];
		echo '<tr>';
		echo '<td><a href="'.url().'products/p/'.$product_info['key'].'"><strong>'.$product_info['title'].'</strong></a></td>';
		echo '<td><input type="text" name="qty_'.$product_info['key'].'" class="textstyle" style="width:25px" value="'.$product_info['qty'].'"></td>';
		echo '<td>&pound;'.number_format($product_info['price'],2).'</td>';
		echo '<td>&pound;'.number_format($total,2).'</td>';
		echo '<td><a href="javascript:remove_item(\''.$product_info['key'].'\');"><img src="'.url().'images/del.gif" alt="Remove"></a></td>';
		echo '</tr>';
	}
	?>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td align="center" colspan="2"><input type="button" class="buttonstyle" value="Update" onclick="update_items();"></td>
		<td rowspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
		<?php
			echo '<tr><td colspan="3" align="right"><strong>Total:</strong></td><td><strong>&pound;'.number_format($basket_total,2).'</strong></td></tr>';
		?>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<span id="update_status" style="display:none"></span>
			<span id="remove_status" style="display:none"></span>
			<span id="empty_status" style="display:none"></span>
			<?php
				echo '<input type="button" class="buttonstyle" value="Continue >>" onclick="javascript:parent.location=\''.url().'checkout/customer\';"><br /><br />';
			?>
		</td>
	</tr>
</table>
</form>
