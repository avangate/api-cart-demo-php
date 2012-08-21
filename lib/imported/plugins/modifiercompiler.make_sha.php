<?php
function smarty_modifiercompiler_make_sha ($params, &$compiler)
{
	// modifiercompiler plugins output directly to the compiled template the string returned here - which gets executed 
	//return "strtoupper(bin2hex(mhash(MHASH_SHA1,". $params[0] ." ," . $params[1].")))";
	return "VaultMakeSha(". $params[0] .", " . $params[1].")";
}
