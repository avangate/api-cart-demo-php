<?php
function smarty_function_make_sha ($params, &$template) 
{
	if (isset($params['key'])) {
		$skey = $params['key'];
		unset ($params['key']);
	} else {
		$skey = null;
	}
	$string = implode ($params);
	
	$hash	= mhash(MHASH_SHA1, $string, $skey);
	$hash	= strtoupper(bin2hex($hash));
	return $hash;
}
