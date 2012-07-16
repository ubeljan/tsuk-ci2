<?php  if ( !defined('BASEPATH')) { exit('No direct script access allowed') ; }

function url()
{
	$url_array = explode('/',base_url());
	$x = count($url_array);
	$url = '';
	for ($i=3; $i<$x; $i++) {
		$url.= '/'.$url_array[$i];
	}
	return $url;
}

function remove_nl($str)
{
	$new_line 	= array("\r\n", "\n", "\r");
	$replace	= ' ';
	return str_replace($new_line, $replace, $str);
}

function truncate($data,$maxlen,$type=1)
{
	if ($type==1){
		if ((strlen($data)>$maxlen) && (strlen($data)>3)){
			$data=substr($data,0,$maxlen-3).'...';
		}
		return $data;
	}
	if ($type==2){
		if ((strlen($data)>$maxlen) && (strlen($data)>3)){
			$data='...'.substr($data,strlen($data)-$maxlen,$maxlen);
		}
		return $data;
	}		
}

function pr($array)
{  
	return '<pre>'.print_r($array).'</pre>';
}

function nl2br2($text)
{
    return preg_replace("/\r\n|\n|\r/", "<br>", $text);
}

function p2nl($text)
{
	$text = preg_replace(array("/<p[^>]*>/iU","/<\/p[^>]*>/iU"), array("","\n"), $text);
    $text = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $text);
	$text = strip_tags($text);
	return $text;
}

function build_options($values,$options,$selected)
{		
	if (count($options)<>count($values)){
		$options=$values;
	}
	$build = "\n";	
	foreach ($options as $i => $value){
		$option='<option value="'.$values[$i].'" ';
		if ($selected==$values[$i]){
			$option.="SELECTED";
		}	
		$option.='>'.$options[$i].'</option>'; 
		$build.= $option."\n";
	}
	return $build;
}

function passGen($len)
{
	$chars = 'abcdefghijklmnopqrstuvwxyz123456789';
	$pass = '';
	for ($i = 0; $i < $len; $i++) {
		$pass .= $chars[(rand() % strlen($chars))];
    }
    return $pass;
}

/* End of file function_helper.php */
/* Location: ./application/helpers/function_helper.php */