<?php

Error
└── Error\BaseError
    ├──  Error\DeprecatedError
    ├──  Error\IOError
    │    └──  Error\PermissionError
    ├──  Error\ReferenceError
    │    └──  Error\LookupError
    │         ├──  Error\IndexError
    │         └──  Error\KeyError
    ├──  Error\RuntimeError
    │    └──  Error\LogicError
    ├──  Error\SyntaxError
    ├──  Error\TriggerError
    ├──  Error\TypeError
    │    ├──  Error\ClassError
    │    │    ├──  Error\AttributeError
    │    │    ├──  Error\ClassImplementationError
    │    │    ├──  Error\ClassInstanceError
    │    │    ├──  Error\ClassNameError
    │    │    ├──  Error\MethodError
    │    │    ├──  Error\PropertyError
    │    └──  Error\ModuleError
    │         ├──  Error\ImportError
    │         └──  Error\ModuleNotFoundError
    └──  Error\ValueError
        └──  Error\AssertionError

?>