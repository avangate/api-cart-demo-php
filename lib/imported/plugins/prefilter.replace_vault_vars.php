<?php
/**
 * 
 * @param string $html
 * @param Smarty $template
 */
function smarty_prefilter_replace_vault_vars ($html, &$template) 
{
	return preg_replace ('/@@(.*)@@/iU', $template->left_delimiter . '$\1' . $template->right_delimiter, $html);
}
