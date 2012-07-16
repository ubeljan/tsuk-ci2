
<script type="text/javascript" charset="utf-8">
function login(){
	var email = URLEncode(document.getElementById('email').value);
	var password = URLEncode(document.getElementById('password').value);
	var poststr = 'email='+email+'&password='+password;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/login', 'login_status', poststr, 'POST', '', check);
}
function check(){
	var check = document.getElementById('login_status').innerHTML;
	if (check==1){
		document.getElementById('login_box').style.display="none";
		parent.location="<?php echo $this->config->item('app_pad') ; ?>checkout/delivery";
	}else{
		document.getElementById('login_check').className="error";
		document.getElementById('login_check').innerHTML="Invalid Login!";
	}
}
</script>
<div style="text-align:center;font-size:13px">Checkout&nbsp;&gt;&nbsp;<span style="font-weight:bold;color:#990000">Customer</span>&nbsp;&gt;&nbsp;Delivery&nbsp;&gt;&nbsp;Summary</div><br>
<div id="login_box">
	<table align="center"><tr>
	<td>
		<table class="content" style="padding:10px; margin-top:30px; width:249px; height:140px">
			<tr><td align="center"><strong>New Customer</strong></td></tr>
			<tr>
				<td align="center">An account is needed to continue!</td>
			</tr>
			<tr align="left">
				<td align="center"><input id="create" type="button" class="buttonstyle" value="Create Account" onclick="javascript:parent.location='<?php echo base_url(); ?>checkout/delivery';">&nbsp;</td>
			</tr>
		</table>
	</td>
	<td>
		<table class="content" style="border: 1px solid #7A7A9A; background:#F1F1F1; padding:10px; margin-top:30px; width:249px; height:140px">
			<tr><td colspan="2" align="center"><strong>Existing Customer</strong></td></tr>
			<tr>
				<td align="right">E-Mail:</td><td colspan="2" align="left"><input id="email" type="text" class="textstyle" style="width:150px"></td>
			</tr>
			<tr>
				<td align="right">Password:</td><td colspan="2" align="left"><input id="password" type="password" class="textstyle" onkeypress="if (event.keyCode==13 || event.which==13) login();" style="width:150px"></td>
			</tr>
			<tr align="left">
				<td>&nbsp;</td>
				<td align="center"><input id="login" type="button" class="buttonstyle" value="Login" onclick="login();">&nbsp;
				<span id="login_check"></span><span id="login_status" style="display:none;"></span>
				</td>
			</tr>
		</table>
	</td>
	</tr></table>
</div>

