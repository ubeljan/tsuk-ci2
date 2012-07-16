<?php  if ( !defined('BASEPATH')) { exit('No direct script access allowed') ; } ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title>LightBox</title>
</head>
<body>
	<br /><br />
<?php
if (isset($img))
{
	echo '<div>';
	echo image_thumb('images/products/'.$img, 400, 400);
	echo '<br /><br /><a href="#" id="lbAction" class="lbAction" rel="deactivate">Close [x]</a>';
	echo '</div>';
}
?>
</body>
</html>