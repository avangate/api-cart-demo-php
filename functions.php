<?php
function isDebug () {return true;}
/**
 * Function to turn the triggered errors into exceptions
 * @author troelskn at gmail dot com
 * @see http://php.net/manual/en/class.errorexception.php
 * @param $severity
 * @param $message
 * @param $filename
 * @param $lineno
 * @throws vscExceptionError
 * @return void
 */
function exceptions_error_handler ($iSeverity, $sMessage, $sFilename, $iLineNo) {
	if (error_reporting() == 0) {
		return;
	}

	if (error_reporting() & $iSeverity) {
		// the __autoload seems not to be working here
// 		include_once(realpath(VSC_LIB_PATH . 'exceptions/vscexceptionerror.class.php'));
		throw new ErrorException ($sMessage, 0, $iSeverity, $sFilename, $iLineNo);
	}
}

/**
 * @return bool
 */
function isCli () {
	return (php_sapi_name() == 'cli');
}

if (!function_exists('d') ) {
	function d () {
		$aRgs = func_get_args();
		$iExit = 1;

		for ($i = 0; $i < ob_get_level(); $i++) {
			// cleaning the buffers
			ob_end_clean();
		}

		if (!isCli()) {
			// not running in console
			echo '<pre>';
		}

		foreach ($aRgs as $object) {
			// maybe I should just output the whole array $aRgs
			try {
				var_dump($object);
				if (isCli()) {
					echo "\n________\n";
				} else {
					echo '<hr/>';
				}
			} catch (Exception $e) {
				//
			}
	}
	debug_print_backtrace();

	if (!isCli()) {
		// not running in console
		echo '</pre>';
	}
	exit ();
}
}

/**
 * the __autoload automagic function for class instantiation,
 * @param string $className
 */
function __autoload ($className) {
	if (class_exists ($className, false)) {
		return true;
	}
	$fileIncluded = false;

	$classNameLow = strtolower($className);

	$sFilePath	= $classNameLow . '.class.php';
	if (stristr ($classNameLow, 'exception')) {
		$sExceptionsFilePath = 'exceptions' . DIRECTORY_SEPARATOR . $sFilePath;
		$fileIncluded = include ($sExceptionsFilePath);
	}
	if (!$fileIncluded) {
		$fileIncluded = include ($sFilePath);
	}
	if ( !$fileIncluded || (!in_array($className,get_declared_classes()) && !in_array($className,get_declared_interfaces()))) {
		$sExport = var_export(getPaths(),true);
		trigger_error ('Could not load class ['.$className.'] in path: <pre style="font-weight:normal">' . $sExport . '</pre>', E_USER_ERROR);
	}
	return true;
}

function getPaths () {
	return explode (PATH_SEPARATOR, get_include_path());
}

function cleanBuffers ($iLevel = null) {
	$sErrors = '';
	if (is_null($iLevel))
	$iLevel = ob_get_level();

	for ($i = 0; $i < $iLevel; $i++) {
		$sErrors .= ob_get_clean();
	}
	return $sErrors;
}

function addPath ($pkgPath, $sIncludePath = null) {
	// removing the trailing / if it exists
	if (substr($pkgPath,-1) == DIRECTORY_SEPARATOR) {
		$pkgPath = substr ($pkgPath,0, -strlen (DIRECTORY_SEPARATOR));
	}

	if (is_null($sIncludePath)) {
		$sIncludePath 	= get_include_path();
	}

	// checking to see if the path exists already in the included path
	if (strpos ($sIncludePath, $pkgPath . PATH_SEPARATOR) === false) {
		set_include_path (
		$pkgPath . PATH_SEPARATOR .
		$sIncludePath
		);
	}
	return true;
}

/**
 * Adds the package name to the include path
 * Also we are checking if an existing import exists, which would define some application specific import rules
 * @param string $sIncPath
 * @return bool
 * @throws vscExceptionPackageImport
 */
function import ($sIncPath) {
	// fixing the paths to be fully compliant with the OS - indifferently how they are set
	$sIncPath	= str_replace(array('/','\\'), array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR),$sIncPath);
	$bStatus 	= false;
	$sPkgLower 	= strtolower ($sIncPath);
	$sIncludePath 	= get_include_path();

	if (is_dir ($sIncPath)) {
		return addPath ($sIncPath, $sIncludePath);
	}

	$aPaths 		= explode(PATH_SEPARATOR, $sIncludePath);
	krsort ($aPaths);

	$currentFolder = dirname(__FILE__);
	// this definitely needs improvement
	foreach ($aPaths as $sPath) {
		if (stristr($sPath, $currentFolder)) {
			$pkgPath 	= $sPath . DIRECTORY_SEPARATOR . $sPkgLower;
			if (is_dir($pkgPath)) {
				$bStatus |= addPath ($pkgPath);
			}
		}
	}

	if (!$bStatus) {
		// to avoid an infinite loop, we include these execeptions manually
// 		include_once(VSC_LIB_PATH . 'exceptions'.DIRECTORY_SEPARATOR.'vscexception.class.php');
// 		include_once(VSC_LIB_PATH . 'exceptions'.DIRECTORY_SEPARATOR.'vscexceptionpath.class.php');
// 		include_once(VSC_LIB_PATH . 'exceptions'.DIRECTORY_SEPARATOR.'vscexceptionpackageimport.class.php');

		trigger_error ('Bad package [' . $sIncPath . ']', E_USER_WARNING);
	} else {
		return true;
	}
}

function getErrorHeaderOutput ($e = null) {
	header ('HTTP/1.1 500 Internal Server Error');
	$sRet = '<?xml version="1.0" encoding="utf-8"?>';
	$sRet .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"';
	$sRet .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	$sRet .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">';
	$sRet .= '<head>';
	$sRet .= '<style>ul {padding:0; font-size:0.8em} li {padding:0.2em;display:inline} address {position:fixed;bottom:0;}</style>';
	if (!is_null($e)) {
		$sRet .= '<title>Internal Error' . (($e instanceof SoapFault) ? ': ' . $e->faultcode : ': '. substr($e->getMessage(), 0, 20) . '...') . '</title>';
	}
	$sRet .= '</head>';
	$sRet .= '<body>';
	if (!is_null($e)) {
		$sRet .= '<strong>Internal Error: '. $e->getMessage() . '</strong>';
		$sRet .= '<address>&copy; habarnam</address>';
		$sRet .= '<ul><li><a href="#" onclick="p = document.getElementById(\'trace\'); if (p.style.display==\'block\') p.style.display=\'none\';else p.style.display=\'block\'; return false">toggle trace</a></li><li><a href="javascript: p = document.getElementById(\'trace\'); document.location.href =\'mailto:marius@habarnam.ro?subject=Problems&body=\' + p.innerHTML; return false">mail me</a></li></ul>';
	
		if ($e instanceof Exception) {
			$sRet .= '<p style="font-size:.8em">Triggered in <strong>' . $e->getFile() . '</strong> at line ' . $e->getLine() .'</p>';
		}
	}
	//$myVars = array_diff(get_defined_vars(), array(array()));
	//$sRet .= var_export ($myVars, true);
	$sRet .= '<pre style="position:fixed;bottom:2em;display:block;font-size:.8em" id="trace">';
	return $sRet;
}

/**
 * @param Exception $e
 */
function _e ($e) {
	$aErrors = array();
	$iLevel = ob_get_level();
	for ($i = 0 ; $i < $iLevel - 2; $i++ ){
		$err = ob_get_clean();
		if (!empty($err)) $aErrors[] = $err;
	}
	$iLevel = ob_get_level();
	for ($i = 0 ; $i < $iLevel; $i++ ){
		ob_end_clean(); // 0
	}
	ob_start();

	if ($e instanceof SoapFault) {
		$sessionError = stristr ($e->faultcode, 'FORBIDDEN');
	} else {
		$sessionError = stristr ($e->getMessage(), 'FORBIDDEN');
	}
	if ($sessionError) {
		@session_destroy();
 		header ('HTTP/1.1 303 See Other');
 		header ('Location : /list-products/');
 		exit();
	}
	header ('HTTP/1.1 500 Internal Server Error');
	echo getErrorHeaderOutput ($e);
	if (isDebug()) {
		echo $e ? $e->getTraceAsString() : '';
	}
	if ($aErrors) echo '<p>' . implode ('<br/>', $aErrors) . '</p>';
	echo '</pre>';
	echo '</body>';
	echo '</html>';
	ob_end_flush();
	exit (0);
}

function memcache_cache_handler ($action, &$smarty_obj, &$cache_content, $tpl_file=null, $cache_id=null, $compile_id=null, $exp_time=null)
{
	switch ($action) {
		case 'read' :
			break;
		case 'write' :
			break;
		case 'clear' :
			break;
		default:
	}
	return 'asd';	
}
