<?php

/*
 * The Yume Framework Translations.
 *
 * This is a translation for English.
 * This is just include all Environment Error messages.
 *
 * @author Ari Setiawan (hxAri)
 * @github https://github.com/hxAri
 * @source https://github.com/yfure/framework/blob/main/src/Furemuwaku/Locale/Translation/en/Environment.php
 * @e-mail hxari@proton.me
 *
 */
return([
	"util.env.EnvError" => [
		"AssigmentError" => "Unable to determine value \"{}\", with type \"{}\" in variable \"{}\"",
		"CommentError" => "Unterminated \"{}\" variable starting, this usually happens because there is a comment symbol inside the variable value",
		"JsonError" => "Invalid JSON string value; {} in variable {}",
		"NameError" => "Environment named \"{}\" is undefined",
		"OverrideError" => "Cannot override variable \"{}\", variable has been defined in line {}",
		"SyntaxError" => "Syntax error, unexpected token \"{trim(0)}\"",
		"TypedefError" => "Value type \"{}\" is not supported, maybe you meant ({})?"
	]
])

?>
