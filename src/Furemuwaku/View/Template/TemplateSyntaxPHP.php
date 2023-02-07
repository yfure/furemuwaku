<?php

namespace Yume\Fure\View\Template;

/*
 * TemplateSyntaxPHP
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateSyntax
 */
class TemplateSyntaxPHP extends TemplateSyntax
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	protected Array | String $token = [
		"break",
		"catch",
		"case",
		"continue",
		"default",
		"do",
		"elif",
		"else",
		"elseif",
		"for",
		"foreach",
		"if",
		"match",
		"switch",
		"throw",
		"try",
		"use",
		"while"
	];
	
	// Membutuhkan kondisi
	public function isConditional( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "catch", "case", "elif", "elseif", "for", "foreach", "if", "match", "switch", "throw", "use", "while" ] ) );
	}
	
	// Tidak membutuhkan kondisi
	public function isUnconditional( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "break", "continue", "default", "do", "else", "try" ] ) );
	}
	
	// Mendukung konten indentasi
	public function isPaired( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "catch", "case", "do", "elif", "else", "elseif", "for", "foreach", "if", "match", "switch", "try", "while" ] ) );
	}
	
	// Tidak mendukung konten indentasi
	public function isUnpaired( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "break", "continue", "default", "throw", "use" ] ) );
	}
	
	// Tidak & Mendukung konten indentasi
	public function isMultipaired( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "catch", "case", "default", "elif", "else", "elseif", "for", "foreach", "if", "while" ] ) );
	}
	
	// Mendukung satu baris
	public function isInline( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "break", "catch", "case", "continue", "default", "elif", "else", "elseif", "for", "foreach", "if", "throw", "use", "while" ] ) );
	}
	
	// Tidak & Mendukung multibaris
	public function isMultiline( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "catch", "case", "default", "elif", "else", "elseif", "for", "foreach", "if", "while" ] ) );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $captured ): Array | String
	{
		echo $captured;
		echo "\n\n";
		
		if( $captured->multiline )
		{
			if( $this->isMultiline( $captured->token ) === False )
			{
				echo 7;
			}
			
			if( valueIsEmpty( $captured->children ) )
			{
				if( $this->isSingle( $captured->token ) )
				{}
			}
			else {
				
			}
		}
		else {
			
		}
		exit;
	}
	
}

?>