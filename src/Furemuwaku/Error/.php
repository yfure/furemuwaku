<?php

Error +
└── Error\YumeError +
   ├── Error\AssertionError +
   │   └── Error\ValueError +
   │       ├── Json\JsonError +
   │       ├── Error\LengthError +
   │       └── Error\UnicodeError +
   ├── Error\DeprecationError +
   ├── Error\HTTPError +
   │   ├── Error\ConnectionError +
   │   ├── Cookie\CookieError
   │   └── Session\SessionError
   ├── Error\IOError +
   │   ├── Buffer\BufferError
   │   ├── Cache\CacheError
   │   ├── File\FileError +
   │   │   └── File\FileNotFoundError +
   │   ├── Logger\LoggerError
   │   ├── Error\ModuleError +
   │   │   ├── Error\ImportError +
   │   │   └── Error\ModuleNotFoundError +
   │   ├── Path\PathError +
   │   │   └── Path\PathNotFoundError +
   │   ├── Error\PermissionError +
   │   └── Stream\StreamError
   ├── Error\LocaleError
   ├── Error\ReferenceError +
   │   └── Error\LookupError +
   │       ├── Error\IndexError +
   │       └── Error\KeyError +
   ├── Error\ReflectError +
   │   ├── Error\ClassError +
   │   │   ├── Error\AttributeError +
   │   │   ├── Error\ClassImplementationError +
   │   │   ├── Error\ClassInstanceError +
   │   │   ├── Error\ClassNameError +
   │   │   ├── Error\ConstantError +
   │   │   │   ├── Error\MethodError +
   │   │   │   └── Error\PropertyError +
   │   │   └── Error\EnumError +
   │   │       └── Error\EnumUnitError +
   │   │           └── Error\EnumBackedError +
   │   ├── Error\ParameterError +
   │   ├── Error\ExtensionError +
   │   ├── Error\FiberError +
   │   ├── Error\FunctionError +
   │   └── Error\GeneratorError +
   ├── Error\RuntimeError +
   │   ├── Service\ServiceError
   │   └── Error\LogicError + 
   ├── Error\SyntaxError +
   │   └── RegExp\RegExpError +
   ├── Error\TriggerError +
   └── Error\UnexpectedError +

?>