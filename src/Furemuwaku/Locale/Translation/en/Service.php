<?php

/*
 * The Yume Framework Translations.
 *
 * This is a translation for English.
 * This is just include all Service Error messages.
 *
 * @author Ari Setiawan (hxAri)
 * @github https://github.com/hxAri
 * @source https://github.com/yfure/framework/blob/main/src/Furemuwaku/Locale/Translation/en/Service.php
 * @e-mail hxari@proton.me
 *
 */
return([
	"logger.ServiceError" => [
		"LookupError" => "No service named {}",
		"NameError" => "Service name must be type Object|String, {type(+):ucfirst} given",
		"OverrideError" => "Can't override service {}"
	],
	"logger.ServiceLookupError" => [
		"LookupError" => "@yume<fure<service.ServiceError<LookupError>>>"
	],
	"logger.ServiceOverrideError" => [
		"OverrideError" => "@yume<fure<service.ServiceError.OverrideError>>>"
	]
])

?>
