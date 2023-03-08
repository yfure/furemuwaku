<?php

namespace Yume\Fure\Locale;

/*
 * LocaleTranslationsTrait
 *
 * @package Yume\Fure\Locale\LocaleTranslationsTrait
 */
trait LocaleTranslationsTrait
{
	
	/*
	 * This is in case the default translation file is not found.
	 *
	 * @access Private Static
	 *
	 * @values Array
	 */
	private static Array $defaultLanguageTranslations = [
		"language" => "EN-US",
		"translation" => [
			"yume" => [
				"app" => [
					
					/*
					 * Yume's default controller translations.
					 *
					 * @include Welcome
					 */
					"http.controllers.Welcome" => []
				],
				"view" => [
					
					/*
					 * Yume's default views translation.
					 *
					 * @include welcome
					 */
					"welcome" => []
				],
				"fure" => [
					
					/*
					 * Command Line Interface Error Translations.
					 *
					 * @include ArgumentError
					 * @include CLIError
					 */
					"cli.CLIError" => [],
					"cli.argument.ArgumentError" => [
						"JSON_VALUE_ERROR" => "Invalid argument json value for {}",
						"SHORT_OPTION_ERROR" => "Invalid short option argument for {}"
					],
					"cli.argument.ArgumentJsonValueError" => [
						"JSON_VALUE_ERROR" => "^yume.fure[cli.argument.ArgumentError].JSON_VALUE_ERROR"
					],
					"cli.argument.ArgumentShortOptionError" => [
						"SHORT_OPTION_ERROR" => "^yume.fure[cli.argument.ArgumentError].SHORT_OPTION_ERROR"
					],
					
					/*
					 * Cache Error Translations.
					 *
					 * @include CacheError
					 */
					"cache.CacacheError" => [],
					
					/*
					 * Error Exception Thrown translations.
					 *
					 * @include AttributeError
					 * @include AssertionError
					 * @include BaseError
					 * @include ClassError
					 * @include ClassImplementationError
					 * @include ClassInstanceError
					 * @include ClassNameError
					 * @include ConstantError
					 * @include DeprecatedError
					 * @include EnumError
					 * @include EnumBackedError
					 * @include EnumUnitError
					 * @include ExtensionError
					 * @include FiberError
					 * @include FunctionError
					 * @include GeneratorError
					 * @include IndexError
					 * @include ImportError
					 * @include IOError
					 * @include KeyError
					 * @include LogicError
					 * @include LookupError
					 * @include MethodError
					 * @include ModuleError
					 * @include ModuleNotFoundError
					 * @include ParameterError
					 * @include PermissionError
					 * @include PropertyError
					 * @include ReferenceError
					 * @include RuntimeError
					 * @include SyntaxError
					 * @include TriggerError
					 * @include TypeError
					 * @include ValueError
					 */
					"error.AttributeError" => [
						"NAME_ERROR" => "No attribute named {}",
						"TYPE_ERROR" => "Can't instantiate non-attribute class {}"
					],
					"error.AssertionError" => [
						"VALUE_ERROR" => "Invalid value for {} value must be {}, {+:ucfirst} given"
					],
					"error.BaseError" => [],
					"error.ClassError" => [
						"IMPLEMENTS_ERROR" => "Class {} must implement interface {}",
						"INSTANCE_ERROR" => "Unable to create new instance for class {}, it's not instantiable class", 
						"NAME_ERROR" => "No class named {}"
					],
					"error.ClassImplementationError" => [
						"IMPLEMENTS_ERROR" => "^yume.fure[error.ClassError].IMPLEMENTS_ERROR"
					],
					"error.ClassInstanceError" => [
						"INSTANCE_ERROR" => "^yume.fure[error.ClassError].INSTANCE_ERROR"
					],
					"error.ClassNameError" => [ 
						"NAME_ERROR" => "^yume.fure[error.ClassError].NAME_ERROR"
					],
					"error.ConstantError" => [],
					"error.DeprecatedError" => [
						"FUNCTION_ERROR" => "Function {} has been deprecated, use {} instead",
						"METHOD_ERROR" => "Method {} has been deprecated, use {} instead"
					],
					"error.EnumError" => [],
					"error.EnumBackedError" => [],
					"error.EnumUnitError" => [],
					"error.ExtensionError" => [],
					"error.FiberError" => [],
					"error.FunctionError" => [
						"NAME_ERROR" => "No function named {}"
					],
					"error.GeneratorError" => [],
					"error.IndexError" => [
						"INDEX_ERROR" => "^yume.fure[error.LookupError].INDEX_ERROR"
					],
					"error.ImportError" => [
						"IMPORT_ERROR" => "^yume.fure[error.ModuleError].IMPORT_ERROR",
					],
					"error.IOError" => [
						"PERMISSION_ERROR" => "Access denied for {}"
					],
					"error.KeyError" => [
						"KEY_ERROR" => "^yume.fure[error.LookupError].KEY_ERROR"
					],
					"error.LogicError" => [],
					"error.LookupError" => [
						"INDEX_ERROR" => "Index {} out of range",
						"KEY_ERROR" => "Undefined key for {}"
					],
					"error.MethodError" => [
						"ACCESS_ERROR" => "Method {}::{} is not accessible from outsite class",
						"INVOKE_ERROR" => "Can't invoke method {}::{}, it's not accessible from outsite class",
						"NAME_ERROR" => "No method named {}::{}"
					],
					"error.ModuleError" => [
						"IMPORT_ERROR" => "Something wrong when import file {}",
						"NOT_FOUND_ERROR" => "No module named {}"
					],
					"error.ModuleNotFoundError" => [
						"NOT_FOUND_ERROR" => "^yume.fure[error.ModuleError].NOT_FOUND_ERROR"
					],
					"error.ParameterError" => [
						"REQUIRE_ERROR" => "Function {} requires parameters {} with type {}"
					],
					"error.PermissionError" => [
						"PERMISSION_ERROR" => "^yume.fure[error.IOError].PERMISSION_ERROR",
						"READ_ERROR" => "Can't read {}",
						"WRITE_ERROR" => "Can't write {}"
					],
					"error.PropertyError" => [
						"ACCESS_ERROR" => "Property {}::\${} is not accessible from outsite class",
						"NAME_ERROR" => "No property named {}::\${}"
					],
					"error.ReferenceError" => [],
					"error.RuntimeError" => [],
					"error.SyntaxError" => [],
					"error.TriggerError" => [],
					"error.TypeError" => [],
					"error.ValueError" => [],
					
					/*
					 * HTTP Error Translations.
					 *
					 * @include HeaderError
					 * @include HTTPError
					 * @include MessageError
					 * @include StreamError
					 * @include StreamBufferError
					 */
					"http.HTTPError" => [],
					"http.header.HeaderError" => [],
					"http.message.MessageError" => [],
					"http.stream.StreamError" => [
						"DETACH_ERROR" => "Stream {} has been detach, the stream is useless",
						"FREAD_ERROR" => "An error occurred when reading the {} stream",
						"FSEEK_ERROR" => "An error occurred in Stream {} when re-changing the pointer to {} with whence {}",
						"FTELL_ERROR" => "An error occurred in Stream {} when determine position",
						"FWRITE_ERROR" => "An error occurred in Stream {} when writing content",
						"LENGTH_ERROR" => "Cannot read stream content with length below zero, {} is given",
						"READ_ERROR" => "Can't read stream {}, because stream is unreadable",
						"READ_CONTENT_ERROR" => "An error occurred when getting the content stream {}",
						"SEEK_ERROR" => "Stream {} cannot seek, because the unseekable stream",
						"STRINGIFY_ERROR" => "Failed parse Object Stream {} into string",
						"TELL_ERROR" => "Cannot determine the position of a Stream {}",
						"WRITE_ERROR" => "Stream {} cannot be written, because Stream is unwritable"
					],
					"http.stream.StreamBufferError" => [
						"SEEK_ERROR" => "^yume.fure[http.stream.StreamError].SEEK_ERROR",
						"TELL_ERROR" => "^yume.fure[http.stream.StreamError].TELL_ERROR"
					],
					
					/*
					 * Logger Error Translations.
					 *
					 * @include LoggerError
					 */
					"logger.LoggerError" => [
						"HANDLER_ERROR" => "Logger does not have any handler",
						"LEVEL_ERROR" => "{+:ucfirst} is an invalid log level"
					],
					
					/*
					 * Security Error Translations.
					 *
					 * @include SecurityError
					 */
					"security.SecurityError" => [],
					
					/*
					 * Services Error Translations.
					 *
					 * @include ServicesError
					 * @include ServicesLookupError
					 * @include ServicesOverrideError
					 */
					"services.ServicesError" => [
						"LOOKUP_ERROR" => "No service named {}",
						"NAME_ERROR" => "Service name must be type Object|String, {type(+):ucfirst} given",
						"OVERRIDE_ERROR" => "Can't override service {}"
					],
					"services.ServicesLookupError" => [
						"LOOKUP_ERROR" => "^yume.fure[support.services.ServicesError].LOOKUP_ERROR"
					],
					"services.ServicesOverrideError" => [
						"OVERRIDE_ERROR" => "^yume.fure[support.services.ServicesError].OVERRIDE_ERROR"
					],
					
					/*
					 * Util Error Translations.
					 *
					 * @include EnvError
					 * @include JsonError
					 * @include RegExpError
					 */
					"util.env.EnvError" => [],
					"util.json.JsonError" => [],
					"util.regexp.RegExpError" => [],
					
					/*
					 * File Error Translations.
					 *
					 * @include FileError
					 * @include FileNotFoundError
					 */
					"util.file.FileError" => [
						"COPY_ERROR" => "Failed copy file from {} to {}",
						"FILE_ERROR" => "Target file {} is not file type",
						"MOVE_ERROR" => "Failed move file from {} to {}",
						"MODE_ERROR" => "Failed open file {}, invalid fopen mode for {}",
						"NAME_ERROR" => "The file name {} is invalid",
						"NOT_FOUND_ERROR" => "No such file {}",
						"OPEN_ERROR" => "Failed open file {}",
						"PATH_ERROR" => "Failed open file.{} because directory {} not found",
						"READ_ERROR" => "An error occurred while reading the contents of the file {}",
						"WRITE_ERROR" => "An error occurred while writing the contents of the file {}"
					],
					"util.file.FileNotFoundError" => [
						"NOT_FOUND_ERROR" => "^yume.fure[support.path.FileError].NOT_FOUND_ERROR"
					],
					
					/*
					 * Path Error Translations.
					 *
					 * @include PathError
					 * @include PathNotFoundError
					 */
					"util.file.path.PathError" => [
						"COPY_ERROR" => "Failed copy directory to {} from {}",
						"MOVE_ERROR" => "Failed move directory to {} from {}",
						"NOT_FOUND_ERROR" => "No such directory {}",
						"READ_ERROR" => "Cannot read anything in directory {}",
						"WRITE_ERROR" => "Could not write to file or directory in directory {}"
					],
					"util.file.path.PathNotFoundError" => [
						"NOT_FOUND_ERROR" => "^yume.fure[util.file.path.PathError].NOT_FOUND_ERROR"
					],
					
					/*
					 * Views Error Translations.
					 *
					 * @include ViewError
					 * @include ComponentError
					 * @include TemplateError
					 * @include TemplateClosingError
					 * @include TemplateIndentationError
					 * @include TemplateSyntaxError
					 * @include TemplateTokenError
					 * @include TemplateUnitializedTokenError
					 */
					"view.ViewError" => [
						"NOT_FOUND_ERROR" => "No such view file {}",
						"PARSE_ERROR" => "An error occurred while passing view {}"
					],
					"view.component.ComponentError" => [
						"NAME_ERROR" => "No component named {}"
					],
					"view.template.TemplateError" => [
						"CLOSING_ERROR" => "Invalid closing syntax \"{}\", unsupported multiple line for single line syntax",
						"INDENTATION_ERROR" => "Unexpected template indentation level for \"{}\"",
						"SYNTAX_ERROR" => "Invalid template syntax \"{}\"",
						"TOKEN_ERROR" => "Unexpected token @{}",
						"UNITIALIZED_TOKEN_ERROR" => "Syntax Template Class \"{}\" must initialize token name"
					],
					"view.template.TemplateClosingError" => [
						"CLOSING_ERROR" => "^yume.fure[view.template.TemplateError].CLOSING_ERROR"
					],
					"view.template.TemplateIndentationError" => [
						"INDENTATION_ERROR" => "^yume.fure[view.template.TemplateError].INDENTATION_ERROR"
					],
					"view.template.TemplateSyntaxError" => [
						"SYNTAX_ERROR" => "^yume.fure[view.template.TemplateError].SYNTAX_ERROR"
					],
					"view.template.TemplateTokenError" => [
						"TOKEN_ERROR" => "^yume.fure[view.template.TemplateError].TOKEN_ERROR"
					],
					"view.template.TemplateUninitializedTokenError" => [
						"UNITIALIZED_TOKEN_ERROR" => "^yume.fure[view.template.TemplateError].UNITIALIZED_TOKEN_ERROR"
					]
				]
			]
		]
	];
	
}

?>