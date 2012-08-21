<?php
class SmartySecurityVault extends Smarty_Security {
	public $php_functions = null;//array('VaultMakeSha'); // no php functions
	public $static_classes = null;
	public $php_modifiers = null;//array('md5', 'sha1'); // native php functions that can be used as modifiers
	public $streams = null;
	public $allowed_modifiers = array('make_sha'); // smarty modifiers
	public $allowed_tags = array();
	public $allow_super_globals = false;
	public $allow_php_tag = false;
}