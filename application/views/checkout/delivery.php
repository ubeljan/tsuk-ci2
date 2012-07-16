
<script type="text/javascript" charset="utf-8">
function save_details(){
	var title 		= URLEncode(document.getElementById('title').value);
	if (title==''){document.getElementById('title').style.background='#F99';}else{document.getElementById('title').style.background='#FFF';}
	var firstname 	= URLEncode(document.getElementById('firstname').value);
	if (firstname==''){document.getElementById('firstname').style.background='#F99';}else{document.getElementById('firstname').style.background='#FFF';}
	var lastname 	= URLEncode(document.getElementById('lastname').value);
	if (lastname==''){document.getElementById('lastname').style.background='#F99';}else{document.getElementById('lastname').style.background='#FFF';}
	var email 	= URLEncode(document.getElementById('email').value);
	if (email==''){document.getElementById('email').style.background='#F99';}else{document.getElementById('email').style.background='#FFF';}
	var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	var email_chk= document.getElementById('email').value;
	if (re.exec(email_chk)==null){document.getElementById('email').style.background='#F99';}else{document.getElementById('email').style.background='#FFF';}
	var password 	= URLEncode(document.getElementById('password').value);
	if (password==''){document.getElementById('password').style.background='#F99';}else{document.getElementById('password').style.background='#FFF';}
	var address1 	= URLEncode(document.getElementById('address1').value);
	if (address1==''){document.getElementById('address1').style.background='#F99';}else{document.getElementById('address1').style.background='#FFF';}
	var address2 	= URLEncode(document.getElementById('address2').value);
	var city 		= URLEncode(document.getElementById('city').value);
	if (city==''){document.getElementById('city').style.background='#F99';}else{document.getElementById('city').style.background='#FFF';}
	var county 		= URLEncode(document.getElementById('county').value);
	var postcode 	= URLEncode(document.getElementById('postcode').value);
	if (postcode==''){document.getElementById('postcode').style.background='#F99';}else{document.getElementById('postcode').style.background='#FFF';}
	var country 	= URLEncode(document.getElementById('country').value);
	var shipping 	= URLEncode(document.getElementById('shipping').value);
	var poststr1 = 'title='+title+'&firstname='+firstname+'&lastname='+lastname+'&email='+email+'&password='+password;
	var poststr2 = '&address1='+address1+'&address2='+address2+'&city='+city+'&county='+county+'&postcode='+postcode+'&country='+country+'&shipping='+shipping;
	ajax('<?php echo $this->config->item('app_pad') ; ?>_ajax/save_details', 'save_status', poststr1+poststr2, 'POST', '', check);
}
function check(){
	var check = document.getElementById('save_status').innerHTML;
	if (check==1){
		document.getElementById('save_box').style.display="none";
		parent.location="<?php echo $this->config->item('app_pad') ; ?>checkout/summary";
	}else if (check==2){
		document.getElementById('save_check').className="error_small";
		document.getElementById('save_check').innerHTML="E-mail In Use!";
	}else{
		document.getElementById('save_check').className="error_small";
		document.getElementById('save_check').innerHTML="Not Completed!";
	}
}
function check_shipping(key){
	var ship_options = new Array();
	var disp_ship = 0;
	var create_new_select = '<select id="shipping" style="width:135px">';
	<?php
	$shipping2 = $shipping;
	foreach ($shipping as $key => $value) {
		echo 'var shipping'.$key.' = new Array();'."\n";
		$list = explode(',',$value['countries']);
		$i=0;
		foreach ($list as $cc){
			echo 'shipping'.$key.'['.$i.'] = \''.$cc.'\';'."\n";
			$i++;
		}
		echo 'var key'.$key.';'."\n";
		echo 'for (key'.$key.' in shipping'.$key.'){'."\n";
		echo 'if (shipping'.$key.'[key'.$key.']==key){'."\n";
		echo 'disp_ship = 1;'."\n";
		echo 'create_new_select += \'<option value="'.$key.'">'.$value['title'].'&nbsp;&pound;'.number_format($value['price'],2).'</option>\';'."\n";
		echo '}'."\n";
		echo '}'."\n";
	}
	?>
	create_new_select += '</select>';
	if (disp_ship==1){
		document.getElementById('shipping_option_select').innerHTML=create_new_select;
	}else{
		document.getElementById('shipping_option_select').innerHTML='<span style="font-weight:bold">Please contact us!<input id="shipping" type="hidden" value=""></span>';
	}
}
</script>
<div style="text-align:center;font-size:13px">Checkout&nbsp;&gt;&nbsp;Customer&nbsp;&gt;&nbsp;<span style="font-weight:bold;color:#990000">Delivery</span>&nbsp;&gt;&nbsp;Summary</div><br>
<div id="save_box">
	<table align="center"><tr>
	<td>
		<table class="content" style="border: 1px solid #7A7A9A; background:#F1F1F1; margin-top:2px; width:249px; height:300px">
			<tr><td colspan="3" align="center"><strong>Account Details</strong></td></tr>
			<tr>
				<td align="right">Title:</td><td colspan="2" align="left"><input id="title" type="text" class="textstyle" style="width:40px" value="<?php echo $user['title']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr>
				<td align="right">First Name:</td><td colspan="2" align="left"><input id="firstname" type="text" class="textstyle" style="width:135px" value="<?php echo $user['firstname']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr>
				<td align="right">Last Name:</td><td colspan="2" align="left"><input id="lastname" type="text" class="textstyle" style="width:135px" value="<?php echo $user['lastname']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr>
				<td align="right">E-Mail:</td><td colspan="2" align="left"><input id="email" type="text" class="textstyle" style="width:135px" value="<?php echo $user['email']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr>
				<td align="right">Password:</td><td colspan="2" align="left"><input id="password" type="password" class="textstyle" style="width:135px" value="<?php echo $user['password']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr><td align="right">&nbsp;</td><td colspan="2" align="left">&nbsp;</td></tr>
			<tr><td align="right">&nbsp;</td><td colspan="2" align="left">&nbsp;</td></tr>
			<tr><td align="right">&nbsp;</td><td colspan="2" align="left">&nbsp;</td></tr>
			<tr><td align="right">&nbsp;</td><td colspan="2" align="left"><span class="error" style="font-size:11px"><i>*Required field</i></span></td></tr>
		</table>
	</td>
	<td>
		<table class="content" style="border: 1px solid #7A7A9A; background:#F1F1F1; margin-top:2px; width:249px; height:300px">
			<tr><td colspan="2" align="center"><strong>Address Details</strong></td></tr>
			<tr>
				<td align="right">Address Line 1:</td><td colspan="2" align="left"><input id="address1" type="text" class="textstyle" style="width:135px" value="<?php echo $user['address1']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr>
				<td align="right">Address Line 2:</td><td colspan="2" align="left"><input id="address2" type="text" class="textstyle" style="width:135px" value="<?php echo $user['address2']; ?>"></td>
			</tr>
			<tr>
				<td align="right">Town/City:</td><td colspan="2" align="left"><input id="city" type="text" class="textstyle" style="width:135px" value="<?php echo $user['city']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr>
				<td align="right">County:</td><td colspan="2" align="left"><input id="county" type="text" class="textstyle" style="width:135px" value="<?php echo $user['county']; ?>"></td>
			</tr>
			<tr>
				<td align="right">Post Code:</td><td colspan="2" align="left"><input id="postcode" type="text" class="textstyle" style="width:75px" value="<?php echo $user['postcode']; ?>">&nbsp;<span class="error">*</span></td>
			</tr>
			<tr>
				<td align="right">Country:</td><td colspan="2" align="left">
					<select id="country" style="width:135px" onchange="check_shipping(this.value);">
					<?php
					foreach ($countries as $key => $value) {
						if ($user['country']==''){
							if ($value=='United Kingdom') $selected=' SELECTED'; else $selected='';
						}else{
							if ($value==$user['country']) $selected=' SELECTED'; else $selected='';
						}
						echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">Shipping:</td><td colspan="2" align="left" id="shipping_option_select">
					<select id="shipping" style="width:135px">
					<?php
					foreach ($shipping2 as $key => $value) {
						if ($user['shipping']==''){
							if ($value['title']==$ship_default) $selected=' SELECTED'; else $selected='';
						}else{
							if ($value['title']==$user['shipping']) $selected=' SELECTED'; else $selected='';
						}
						echo '<option value="'.$key.'"'.$selected.'>'.$value['title'].'&nbsp;&pound;'.number_format($value['price'],2).'</option>';
					}
					?>
					</select>
					<script type="text/javascript" charset="utf-8">
						var country = document.getElementById('country').value;
						check_shipping(country);
					</script>
				</td>
			</tr>
			<tr align="left">
				<td><span id="save_check"></span><span id="save_status" style="display:none;"></span></td>
				<td align="center"><input id="login" type="button" class="buttonstyle" value="Continue >>" onclick="save_details();">&nbsp;</td>
			</tr>
		</table>
	</td>
	</tr></table>
</div>

