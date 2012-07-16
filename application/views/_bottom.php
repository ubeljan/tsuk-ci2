<?php  if ( !defined('BASEPATH')) { exit('No direct script access allowed') ; } ?>

					</td>
				</tr>
			</table>
		</td>
		<td class="right">
			<table class="subright" cellpadding="0" cellspacing="0">
				<tr class="subrighthdr"><td>Basket</td></tr>
				<tr class="subrightcntmargin2"><td></td></tr>
				<tr class="subrightcnt"><td>Basket Items: <strong><span id="basket_qty"><?php echo $basket_qty; ?></span></strong></td></tr>
				<tr class="subrightcntmargin2"><td></td></tr>
				<tr class="subrightcnt"><td><strong>Total:</strong>&nbsp;<span style="font-weight:bold;color:#990000">&pound;</span><span id="basket_total" style="font-weight:bold;color:#990000"><?php echo number_format($basket_total,2); ?></span></td></tr>
				<tr class="subrightcntmargin2"><td></td></tr>
				<tr class="subrightcnt"><td><input type="button" class="buttonstyle" value="Checkout" onclick="parent.location='<?php echo base_url(); ?>checkout';"></td></tr>
				<tr class="subrightcntmargin2"><td></td></tr>
				<tr class="subrightcnt"><td><input type="button" class="buttonstyle" value="Cancel" onclick="javascript:cancel_basket();"><span id="cancel_status" style="display:none"></span></td></tr>
				<tr class="subrightcntmargin2"><td></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" class="nav" valign="middle">
			<a href="<?php echo base_url(); ?>testimonials">Testimonials</a>
			<a href="<?php echo base_url(); ?>privacy">Privacy</a>
			<a href="<?php echo base_url(); ?>terms">Terms and Conditions</a>
		</td>
	</tr>
	<tr><td colspan="3" class="footer">Copyright (c) <?php echo date('Y'); ?> TSUK</td></tr>
	<tr><td colspan="3" class="bottom"></td></tr>
</table>

<?php
/*
if( !isset($_SESSION) )
{
	session_start();
}
echo '<pre>' ;
print_r( $_SESSION ) ; 
echo '</pre>' ;
*/
?>

</body>
</html>