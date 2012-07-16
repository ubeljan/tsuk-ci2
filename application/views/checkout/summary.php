	
<script type="text/javascript" charset="utf-8">
function pay_data(){
	document.getElementById('pay_button').innerHTML='<img src="<?php echo base_url(); ?>images/paypal_grey.gif" alt=""><br>Please Wait...';
	save_clear();
}
function postdata(){
	document.paypal.submit();
}
function save_clear(){
	var poststr = 'reset=true';
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/save_reset_basket', 'empty_status', poststr, 'POST', '', postdata);
}
</script>
<div style="text-align:center;font-size:13px">Checkout&nbsp;&gt;&nbsp;Customer&nbsp;&gt;&nbsp;Delivery&nbsp;&gt;&nbsp;<span style="font-weight:bold;color:#990000">Summary</span></div><br>
<table class="normal" align="center" width="506px" style="border:1px solid #7A7A9A;background:#F1F1F1;padding:2px">
	<tr>
		<td><strong>Item</strong></td>
		<td><strong>QTY</strong></td>
		<td><strong>Price</strong></td>
		<td><strong>Total</strong></td>
	</tr>
	<?php
	foreach ($basket as $product_info) {
		$total = $product_info['price']*$product_info['qty'];
		echo '<tr>';
		echo '<td><a href="'.url().'products/p/'.$product_info['key'].'"><strong>'.$product_info['title'].'</strong></a></td>';
		echo '<td>'.$product_info['qty'].'</td>';
		echo '<td>&pound;'.number_format($product_info['price'],2).'</td>';
		echo '<td>&pound;'.number_format($total,2).'</td>';
		echo '</tr>';
	}
	?>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<?php
		echo '<tr><td colspan="3" align="right"><strong>Sub Total:</strong></td><td><strong>&pound;'.number_format($basket_total,2).'</strong></td></tr>';

		$grand_total = $basket_total+$shipping['price'];
		echo '<tr><td colspan="3" align="right"><strong>'.$shipping['title'].' Shipping:</strong></td><td><strong>&pound;'.number_format($shipping['price'],2).'</strong></td></tr>';
		echo '<tr><td colspan="3" align="right"><strong>Grand Total:</strong></td><td><strong>&pound;'.number_format($grand_total,2).'</strong></td></tr>';
	?>
	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" name="paypal">  
			<input type="hidden" name="cmd" value="_cart">  
			<input type="hidden" name="upload" value="1">
			<input type="hidden" name="business" value="some_account@paypal.com"> 
			<?php
			$i=1;
			foreach ($basket as $pay_info) {
				echo '<input type="hidden" name="item_name_'.$i.'" value="'.$pay_info['title'].'">';
				echo '<input type="hidden" name="item_number_'.$i.'" value="'.$pay_info['key'].'">';
				echo '<input type="hidden" name="amount_'.$i.'" value="'.number_format($pay_info['price'],2).'">';
				echo '<input type="hidden" name="quantity_'.$i.'" value="'.$pay_info['qty'].'">';
				$i++;
			}
			?>
			<input type="hidden" name="shipping_1" value="<?php echo number_format($shipping['price'],2); ?>">
			<input type="hidden" name="no_shipping" value="2">
			<input type="hidden" name="shipping" value="<?php echo number_format($shipping['price'],2); ?>">
			<input type="hidden" name="first_name" value="<?php echo $user['firstname']; ?>">
			<input type="hidden" name="last_name" value="<?php echo $user['lastname']; ?>">
			<input type="hidden" name="address1" value="<?php echo $user['address1']; ?>">
			<input type="hidden" name="address2" value="<?php echo $user['address2']; ?>">
			<input type="hidden" name="city" value="<?php echo $user['city']; ?>">
			<input type="hidden" name="state" value="<?php echo $user['county']; ?>">
			<input type="hidden" name="zip" value="<?php echo $user['postcode']; ?>">
			<!-- <input type="hidden" name="phone" value="0123456789"> -->
			<input type="hidden" name="email" value="<?php echo $user['email']; ?>">
			<input type="hidden" name="return" value="<?php echo base_url(); ?>checkout/success">
			<input type="hidden" name="cancel_return" value="<?php echo base_url(); ?>checkout/order_cancelled">
			<input type="hidden" name="currency_code" value="GBP">
			<input type="hidden" name="lc" value="GB">
			<input type="hidden" name="bn" value="PP-BuyNowBF">
			<span id="pay_button"><a href="javascript:pay_data();"><img src="<?php echo base_url(); ?>images/paypal.gif" alt="PayPal - The safer, easier way to pay online"></a></span><br><br>or<br><br>
			<input id="continue_shopping" type="button" class="buttonstyle" value="Continue Shopping" onclick="parent.location='<?php echo base_url(); ?>'">
			<span id="update_status" style="display:none"></span>
			<span id="remove_status" style="display:none"></span>
			<span id="empty_status" style="display:none"></span>
			</form>
		</td>
	</tr>
</table>

