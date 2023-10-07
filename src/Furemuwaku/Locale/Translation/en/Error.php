<?php

/*
 * The Yume Framework Translations.
 *
 * This is a translation for English.
 * This is just include all Error messages.
 *
 * @author Ari Setiawan (hxAri)
 * @github https://github.com/hxAri
 * @source https://github.com/yfure/framework/blob/main/src/Furemuwaku/Locale/Translation/en/Error.php
 * @e-mail hxari@proton.me
 *
 */
return([
	"error.AssertionError" => [
		"ValueError" => "Invalid value for {}, value must be {}, {+:ucfirst} given"
	],
	"error.AttributeError" => [
		"NameError" => "No attribute named {}",
		"TypeError" => "Can't instantiate non-attribute class {}"
	],
	"error.ClassError" => [
		"ImplementsError" => "Class {} must implement interface {}",
		"InstanceError" => "Unable to create new instance for class {}, it's not instantiable class", 
		"NameError" => "No class named {}"
	],
	"error.ClassImplementationError" => [
		"ImplementsError" => "@yume<fure<error.ClassError<ImplementsError>>>"
	],
	"error.ClassInstanceError" => [
		"InstanceError" => "@yume<fure<error.ClassError<InstanceError>>>"
	],
	"error.ClassNameError" => [
		"NameError" => "@yume<fure<error.ClassError<NameError>>>"
	],
	"error.ConnectionError" => [],
	"error.ConstantError" => [
		"AccessError" => "Constant {}::{} is not accessible from outsite class",
		"NameError" => "Class {} has no constant named {}"
	],
	"error.DeprecationError" => [
		"FunctionError" => "Function {} has been deprecated, use {} instead",
		"MethodError" => "Method {} has been deprecated, use {} instead"
	],
	"error.EnumBackedError" => [],
	"error.EnumError" => [
		"NameError" => "No enum named {}"
	],
	"error.EnumUnitError" => [],
	"error.ExtensionError" => [],
	"error.FiberError" => [],
	"error.FunctionError" => [
		"NameError" => "No function named {}"
	],
	"error.GeneratorError" => [],
	"error.HTTPError" => [],
	"error.IOError" => [],
	"error.ImportError" => [
		"ImportError" => "@yume<fure<error.ModuleError<ImportError>>>"
	],
	"error.IndexError" => [
		"IndexError" => "@yume<fure<error.LookupError<IndexError>>>"
	],
	"error.IOError" => [],
	"error.KeyError" => [
		"KeyError" => "@yume<fure<error.LookupError.KeyError>>>"
	],
	"error.LengthError" => [
		"GreaterError" => "The length of the value {} must be greater than {}, {} is given",
		"LengthError" => "Length of {} must be {}, {} given",
		"LessterError" => "The length of {} value must be less than {}, {} is given"
	],
	"error.LogicError" => [],
	"error.LookupError" => [
		"IndexError" => "Index {} out of range",
		"KeyError" => "Undefined key for {}"
	],
	"error.MethodError" => [
		"AccessError" => "Method {}::{} is not accessible from outsite class",
		"InvokeError" => "Can't invoke method {}::{}, it's not accessible from outsite class",
		"NameError" => "Class {} has no method named {}"
	],
	"error.ModuleError" => [
		"ImportError" => "Something wrong when import module {}",
		"NotFoundError" => "No module named {}"
	],
	"error.ModuleNotFoundError" => [
		"NotFoundError" => "@yume<fure<error.ModuleError<NotFoundError>>>"
	],
	"error.ParameterError" => [
		"NameError" => "Function {} has no parameter named {}",
		"RequireError" => "Function {} requires parameters {} with type {}"
	],
	"error.PermissionError" => [
		"ReadError" => "Access denied, unable to read {}",
		"WriteError" => "Access denied, unable to write {}"
	],
	"error.PropertyError" => [
		"AccessError" => "Property {}::\${} is not accessible from outsite class",
		"NameError" => "Class {} has no property named {}",
		"UnititializeError" => "Property {}::\${} is unitialized"
	],
	"error.ReferenceError" => [],
	"error.ReflectError" => [],
	"error.RuntimeError" => [],
	"error.SyntaxError" => [],
	"error.TriggerError" => [],
	"error.UnexpectedError" => [],
	"error.UnicodeError" => [
		"UnicodeError" => "@yume<fure<error.ValueError<UnicodeError>>>"
	],
	"error.ValueError" => [
		"LengthError" => "Length of {} must be {}, {} given",
		"UnicodeError" => "Unkown and Invalid unicode for {}"
	],
	"error.YumeError" => []
]);

?>
