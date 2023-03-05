<?php

namespace Yume\Fure\Util\Array;

class Tree
{
    
    const LINE = 1;
    const POINT = 4;
    
    /*
     * Space Distance.
     *
     * @access Public
     * @value String
     */
    const SPACE = "    ";
    
    const NONE = "";
    
    /*
     * Line Types.
     *
     * @access Public
     * @value String
     */
    const STRAIGHT_LINE = "│   ";
    const MIDDLE_LINE = "├── ";
    const LAST_LINE = "└── ";
    
    /*
     * Double Point.
     *
     * @access Public
     * @value String
     */
    const DOUBLE_POINT = ".. ";
    
    protected static $keyHandler;
    protected static $valHandler;
    
    public static function create( Array $data, Int $start = 0, Int $flags = 0 )
    {
        if( self::$keyHandler === Null ) {
            self::$keyHandler = function( Mixed $key ) {
                return( $key );
            };
        }
        if( self::$valHandler === Null ) {
            self::$valHandler = function( Mixed $val ) {
                return( $val );
            };
        }
        
        $space = "";
        
        if( $start !== 0 ) {
            for( $i = 0; $i < $start; $i++ ) {
                $space .= " ";
            }
        }
        
        return( self::fELoop( $data, $space, $flags === 0? self::LINE : $flags ) );
    }
    
    /*
     * Set key element handler.
     *
     * @access Public, Static
     *
     * @params Callable <handler>
     *
     * @return Void
     */
    public static function setKeyHandler( Callable $handler ): Void
    {
        self::$keyHandler = $handler;
    }
    
    /*
     * Set element value handler.
     *
     * @access Public, Static
     *
     * @params Callable <handler>
     *
     * @return Void
     */
    public static function setValHandler( Callable $handler ): Void
    {
        self::$valHandler = $handler;
    }
    
    private static function key( Mixed $key )
    {
        return( call_user_func_array( self::$keyHandler, [$key] ) . "\n" );
    }
    
    private static function val( Mixed $val, String $space, String $line, Int $flags ): String
    {
        if( is_array( $val ) ) {
            $result = self::fELoop( $val, $space . $line, $flags );
        } else {
            $result = call_user_func_array( self::$valHandler, [$val] );
        }
        return "{$result}\n";
    }
    
    private static function fELoop( Array $data, String $space, Int $flags )
    {
        $r = "";
        $i = 0;
        
        if( count( $data ) !== 0 ) {
            foreach( $data As $k => $v ) {
                
                $i++;
                
                if( count( $data ) === $i ) {
                    if( $flags !== self::POINT ) {
                        $lK = self::LAST_LINE;
                    } else {
                        $lK = self::DOUBLE_POINT;
                    }
                    $lA = self::SPACE;
                } else {
                    if( $flags !== self::POINT ) {
                        $lK = self::MIDDLE_LINE;
                        $lA = self::STRAIGHT_LINE;
                    } else {
                        $lK = self::DOUBLE_POINT;
                        $lA = self::SPACE;
                    }
                }
                
                $r .= $space;
                $r .= $lK;
                
                if( is_int( $k ) ) {
                    if( is_array( $v ) ) {
                        $r .= self::key( "Array" );
                        $r .= self::fELoop( $v, $space . $lA, $flags );
                    } else {
                        $r .= self::val( $v, $space, $lA, $flags );
                    }
                } else {
                    
                    $r .= self::key( $k );;
                    
                    if( is_array( $v ) ) {
                        $r .= self::fELoop( $v, $space . $lA, $flags );
                    } else {
                        $r .= $space;
                        $r .= $lA;
                        
                        if( $flags !== self::POINT ) {
                            $r .= self::LAST_LINE;
                        } else {
                            $r .= self::DOUBLE_POINT;
                        }
                        $r .= self::val( $v, $space, $lA, $flags );
                    }
                }
            }
        } else {
            
            $r .= $space;
            
            if( $flags !== self::POINT ) {
                $r .= self::LAST_LINE;
            } else {
                $r .= self::DOUBLE_POINT;
            }
            $r .= "Empty\n";
        }
        return( $r );
    }
    
}

?>