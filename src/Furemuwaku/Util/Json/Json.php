<?php

namespace Yume\Fure\Util\Json;

/*
 * JSON
 *
 * @package Yume\Fure\Util\Json
 */
final class Json
{
    
    /*
     * @inherit https://www.php.net/manual/en/function.json-last-error.php
     *
     */
    public static function errno(): Int
    {
        return( json_last_error() );
    }
    
    /*
     * https://www.php.net/manual/en/function.json-last-error-msg.php
     *
     */
    public static function error(): String
    {
        return( json_last_error_msg() );
    }
    
    /*
     * Get last error type constant.
     *
     * @access Public Static
     *
     * @return String
     */
    public static function etype(): String
    {
        return( match( self::errno() )
        {
            0 => "ERROR_NONE",
            1 => "ERROR_DEPTH",
            2 => "ERROR_STATE_MISMATCH",
            3 => "ERROR_CTRL_CHAR",
            4 => "ERROR_SYNTAX",
            5 => "ERROR_UTF8",
            6 => "ERROR_RECURSION",
            7 => "ERROR_INF_OR_NAN",
            8 => "ERROR_UNSUPPORTED_TYPE",
            9 => "ERROR_INVALID_PROPERTY_NAME",
            10 => "ERROR_UTF16"
        });
    }
    
    /*
     * @inherit https://www.php.net/manual/en/function.json-decode.php
     *
     */
    public static function decode( String $json, ? Bool $associative = Null, Int $depth = 512, Int $flags = 0 ): Mixed
    {
        // Decodes a JSON string.
        $decode = json_decode( $json, $associative, $depth, $flags );
        
        // Check if an error occurred.
        if( $errno = self::errno() )
        {
            throw new JSONError( self::error(), $errno );
        }
        
        return( $decode );
    }
    
    /*
     * @inherit https://www.php.net/manual/en/function.json-encode.php
     *
     */
    public static function encode( Mixed $value, Int $flags = 0, Int $depth = 512 ): False | String
    {
        // Get the JSON representation of a value.
        $encode = json_encode( $value, $flags, $depth );
        
        // Check if an error occurred.
        if( $errno = self::errno() )
        {
            throw new JSONError( self::error(), $errno );
        }
        
        return( $encode );
    }
    
}

?>