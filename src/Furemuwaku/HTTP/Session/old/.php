<?php

/*
 * Application configuration.
 *
 * @author hxAri
 */
return([
	
	// Application name.
	"app.name" => "@ENV.APP_NAME",
	
	"database" => [
		
		// Default database connection name.
		"default" => "yume",
		
		// All connection lists.
		"connections" => [
			"yume" => [
				"database.type" => "MySQL",
				"database.host" => "@ENV.DATABASE_HOST",
				"database.port" => "@ENV.DATABASE_PORT",
				"database.user" => "@ENV.DATABASE_USER",
				"database.pass" => "@ENV.DATABASE_PASS",
				"database.name" => "@ENV.DATABASE_NAME"
			]
		]
		
	],
	
	"environment" => [
		
		/*
		 * Allow replace value on super global variable $_ENV.
		 *
		 * @values Bool
		 */
		"replace" => False,
		
		/*
		 * Give prefix name for super global constant name.
		 *
		 * @values False|String
		 */
		"prefix" => "ENV",
		
		/*
		 * Skip create super global constant if constant is exists.
		 *
		 * @values Bool
		 */
		"skiped" => True,
		
		/*
		 * The location where the environment files are stored.
		 *
		 * You can rename files and move environment
		 * files where no one can reach them.
		 *
		 * @path Default BASE_PATH
		 */
		"path" => "kankyou"
		
	],
	
	"http" => [
		"controller" => [
			
			// Default controller main method name.
			"default.method" => "main"
		],
		"cookies" => [
			"handler" => Yume\Fure\HTTP\Cookies\CookieHeader::class
		],
		"csrf" => [
			"cookie" => [
				"expires" => "+10 minutes",
				"httpOnly" => True,
				"secure" => True
			],
			"password" => "250845097de1cd2cf12f1e7f1a484cae25d5b84ff4fd4b32482255c43d5e52695d27d70754fbfc64925096e3417c48af7f810b1d3e3333ae979a4a21dafb73f4"
		],
		"request" => [
			
			// List allowed request methods.
			"methods" => [
				"DELETE",
				"HEAD",
				"GET",
				"PATCH",
				"POST",
				"PUT"
			]
		],
		"routing" => [
			
			// Routes file saved.
			"routes" => "system/routes/routes",
			
			// Route regular expression.
			"regexp" => [
				
				// Default route regular expression.
				"default" => "[a-zA-Z0-9\-\_]+",
				
				// Route regular expression for search segment name.
				"segment" => "/(?:(?<Segment>\:(?<SegmentName>([a-z]+))(\((?<SegmentRegExp>[^\)]*)\))*)|(?<SegmentMatchAll>\.\*\?*))/i"
			]
		],
		"session" => [
			
			/*
			 * Securing Session INI Settings
			 *
			 * By securing session related INI settings, developers can improve session security.
			 * Some important INI settings do not have any recommended settings. Developers are
			 * responsible for hardening session settings.
			 */
			"configs" => [
				"session.cookie_domain" => "",
				"session.cookie_httponly" => 1,
				"session.cookie_lifetime" => 0,
				"session.cookie_path" => "/",
				"session.cookie_samesite" => "Strict",
				"session.cookie_secure" => 1,
				"session.gc_maxlifetime" => 1440,
				"session.name" => "YUMESESSID",
				"session.sid_length" => 48,
				"session.sid_bits_per_character" => 6,
				"session.save_path" => "",
				"session.save_handler" => "files",
				"session.use_cookies" => 1,
				"session.use_only_cookies" => 1,
				"session.use_strict_mode" => 1,
				"session.use_trans_sid" => 0
			],
			
			/*
			 * Session Handler Class
			 *
			 * SessionHandler is a special class that can be used to expose
			 * the current internal PHP session save handler by inheritance.
			 */
			"handler" => Yume\Fure\HTTP\Session\SessionHandler::class,
			
			"hashing" => "AES256"
			
		]
	],
	
	"localization" => [
		
		"language" => "en-EN",
		
		/*
		 * Default Time Zone for app.
		 *
		 * Please visit the official PHP page for a full list of supported Time Zones.
		 *
		 * @webpage https://www.php.net/manual/en/timezones.php
		 *
		 * @default Asia/Tokyo
		 */
		"timezone" => "Asia/Tokyo"
	],
	
	"logger" => [
		"save.path" => "assets/loggers",
		"save.handler" => Yume\Fure\Logger\Logger::class
	],
	
	"seclib" => [
		
		// PHPSeclib3 Public key configuration.
		"public" => [],
		
		// PHPSeclib3 Symmetric key configuration.
		"symmetric" => [
			
			// Symmetric AES configs.
			"aes" => [
				
				// Default chiper mode.
				"mode" => "ctr",
				
				// OpenSLL is a default Engine.
				"engine" => phpseclib3\Crypt\AES::ENGINE_OPENSSL,
				
				// Default key length.
				"key.length" => 256,
				
				// Default IV size.
				"ivSize" => 16
			]
		]
	],
	
	/*
	 * List of all service classes.
	 *
	 * The service classes will be execute after configuration finish.
	 */
	"services" => [
		Yume\Fure\HTTP\Routing\RouteServices::class,
		Yume\Fure\Translator\TranslatorServices::class,
		Yume\Fure\View\ViewServices::class
	],
	
	"reflector" => [],
	
	/*
	 * The Trouble configuration.
	 *
	 * It also includes the name of the function that handles the error,
	 * where the error log is stored and also includes how the function
	 * displays error messages based on a template or schema.
	 */
	"trouble" => [
		"error" => [
			"scheme" => [
				"File" => [
					"File",
					"Line"
				],
				"Error" => [
					"Code",
					"Level"
				],
				"Message"
			],
			
			/*
			 * Trigger Handler Function
			 *
			 * Function sets a user-defined error handler function.
			 */
			"handler" => "Yume\Fure\Error\Toriga\Toriga::handler"
		],
		"exception" => [
			
			/*
			 * Note
			 *
			 * It is recommended to set the trace.all value to False when the
			 * application will be uploaded to the host, this is because
			 * Trace will display program code traces, such as Argument
			 * Values, Function|Class|File|Directory|Variable Names, etc
			 * this will be very dangerous if the data is leaked.
			 */
			"trace" => [
				
				// Allow show all traces.
				"all" => True,
				
				// Allow show all traces except args.
				"arg" => True
			],
			
			/*
			 * The exception scheme that will be displayed, you can change
			 * the order or even delete one of them at will and be careful.
			 *
			 */
			"scheme" => [
				"Object" => [
					"Code",
					"Type",
					"Class",
					"Trait",
					"Parent",
					"Interface",
					"Previous"
				],
				"File" => [
					"Line",
					"File"
				],
				"Message",
				"Trace"
			],
			
			/*
			 * Exception Handler Function.
			 *
			 * PHP allows you to catch the uncaught exceptions
			 * by registering a global exception handler. 
			 */
			"handler" => "Yume\Fure\Error\Memew\Memew::handler"
		]
	],
	
	"view" => [
		"save" => [
			"path" => "assets/views/{0}.view"
		],
		"cache" => [
			"loaded" => "assets/caches/views/{0}/__view.{0}.loaded",
			"parsed" => "assets/caches/views/{0}/__view.{0}.parsed"
		],
		"parsers" => [
			Yume\Fure\View\Syntax\SyntaxComment::class,
			Yume\Fure\View\Syntax\SyntaxOutput::class,
		],
		"comment" => [
			"remove" => False
		]
	]
	
]);

?>