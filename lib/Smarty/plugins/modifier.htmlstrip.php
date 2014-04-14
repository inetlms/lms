<?php

function smarty_modifier_htmlstrip($args)
{
	$search = array (
	    "'<'i",
	    "'>'i",
	    "'\"'i",
	    "'\''i",
	    "'`'i",
	    "'~'i",
	    "'{'i",
	    "'}'i",
			);
	
	$replace = array (
	    "&#60;",
	    "&#62;",
	    "&#34;",
	    "&#39;",
	    "&#96;",
	    "&#126;",
	    "&#123;",
	    "&#125;",
	);
	
	return preg_replace ($search, $replace, $args);
}

?>
