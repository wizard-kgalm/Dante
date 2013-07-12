<?php
/**
*This code is not under the GNU GPL License
*
*This library is used under special permission as it requires a modification of the license and can not be ported to any other GNU GPL program without permission from the original author.
*
*It can however be moved to a program under an identical Creative Commons license,  provided you follow the rules. 
*
*Thanks to the awesome Zeros-Elipticus
*@author Zeros-Elipticus
*@license Creative Commons Attribution-NonCommercial-ShareAlike 
*@project Dante 
*/
#############################################################################
# Copyright ( c ) 2007 Elliott Sprehn ( elliott@thaposse.net )
#        http://enfinitystudios.thaposse.com
#
# This file is part of zBot.
# zBot is free software; you can redistribute it and/or modify
# it under the terms of the Creative Commons Attribution-NonCommercial-ShareAlike 
# 2.5 License.
# 
# zBot is distributed in the hope that it will be useful, 
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
# 
# You should have received a copy of the applicable License
# along with zBot; if not visit http://creativecommons.org/licenses/by-nc-sa/2.5/
#
#############################################################################

/* How to use the error handler:

# Show all errors ( they'll be hanled by the ZAPI::ErrorHandler() method
error_reporting( E_ALL );

set_error_handler( array( 'ZAPI', 'Errorhandler' ) );

*/

// color messages
if( !defined( 'CONSOLE_ANSICOLOR' ) ) {
    define( 'CONSOLE_ANSICOLOR', true );
}

if( !defined( 'ZAPI_SHOW_ERRORS' ) ) {
    define( 'ZAPI_SHOW_ERRORS', true );
}

if( !defined( 'ZAPI_SHOW_NOTICES' ) ) {
    define( 'ZAPI_SHOW_NOTICES', true );
}

if( !defined( 'ZAPI_SHOW_WARNINGS' ) ) {
    define( 'ZAPI_SHOW_WARNINGS', true );
}

if( !defined( 'ZAPI_SOCKET_WARNING' ) ) {
    define( 'ZAPI_SOCKET_WARNING', false );
}

if( !defined( 'ZAPI_APPLICATION_STACKTRACE' ) ) {
    define( 'ZAPI_APPLICATION_STACKTRACE', true );
}

class ZAPI {
  /**
     * Outputs a message to the terminal.
     * 
     * @param string $style     style of message to output ( 'log', 'info', 'error', 'warning', 'notice', 'info, 'msg', 
     *                                                     'action', 'stdmessage', 'normal' )
     * @param string $text    part one of the message
     * @param string $text1    part two of the message
     * @param string $text2    part three of the message
     * @param string $text3    part four of the message
     * 
     * @return -nothing-
     */
    public static function message( $style='normal', $text , $text1='', $text2='', $text3='' ) {
 
        $timestamp= date( "H:i:s" );
		if( CONSOLE_ANSICOLOR ) {
			$boldon 	= 	chr( 27 ) . '[1m';
			$boldoff 	= 	chr( 27 ) . '[22m';
			$red 		= 	chr( 27 ) . '[31m';
			$yellow 	= 	chr( 27 ) . '[33m';
			$blue 		= 	chr( 27 ) . '[34m';
			$reset 		= 	chr( 27 ) . '[39m';
		} else {
			$boldon = $boldoff = $red = $yellow = $blue = $reset = '';    
		}
            
        switch( $style ) {
            case 'log':
                break;
            case 'info':
                echo "{$boldon}[Info @ $timestamp] {$text}{$boldoff}\n";
                break;
            case 'error':
                echo "{$boldon}[Error @ $timestamp]$red ** {$text}{$boldoff}{$reset}\n";
                break;
            case 'warning':
                echo "{$boldon}[Warning @ $timestamp]$yellow ** {$text}{$boldoff}{$reset}\n";
                break;
            case 'notice':
                echo "{$boldon}[Notice @ $timestamp] {$text}{$boldoff}{$reset}\n";
                break;
            case 'msg':
                echo "{$boldon}{$timestamp}{$blue} [{$text}]{$boldoff}{$reset} {$boldon}<{$text1}>{$boldoff} {$text2}{$reset}\n";
                break;
            case 'action':
                echo "{$boldon}{$timestamp}{$blue} [{$text}]{$boldoff}{$reset} {$boldon}* {$text1}{$boldoff} {$text2}{$reset}\n";
                break;
            case 'stdmsg':
                echo "{$boldon}{$text}{$boldoff}{$reset}\n";
                break;
            case 'normal':
            default:
                echo "{$boldon}{$timestamp}{$boldoff} {$text}{$reset}\n";
                break;
        }
        
    }
    
	/**
	* handles all php errors
	*/
    public static function ErrorHandler( $errno,  $errstr,  $errfile,  $errline ) { 
        switch( $errno ) {
            case E_ERROR:     
                if( ZAPI_SHOW_ERRORS ) {
                    $text = "\nType: Fatal\nNum: $errno\nMsg: $errstr\nFile: $errfile\nLine: $errline\n\n>> This is a fatal error, stopping execution.";
                    ZAPI::message( 'error', $text );
                    if( ZAPI_APPLICATION_STACKTRACE ) {
                        ZAPI::backtrace( true );
                    }
                }
                die();
            case E_WARNING:
                if( ZAPI_SHOW_WARNINGS ) {
                    if( substr( $errstr, 0, 7 ) == 'socket_' && !ZAPI_SOCKET_WARNING ) break;
                    
                    $text = "\nType: Warning\nNum: $errno\nMsg: $errstr\nFile: $errfile\nLine: $errline\n\n>> Attempt will be made to continue, may cause stability issues.\n";
                    ZAPI::message( 'warning', $text ); 
                    if( ZAPI_APPLICATION_STACKTRACE ) {
                        ZAPI::backtrace(); 
                    }
                }
                break;
            case E_NOTICE:
                if( ZAPI_SHOW_NOTICES ) {
                    $text = "\nType: Notice\nNum: $errno\nMsg: $errstr\nFile: $errfile\nLine: $errline\n\n>> Attempt will be made to continue, may cause stability issues.\n";
                    ZAPI::message( 'notice', $text );      
                }
                break;
            default:
                $text = "\nType: Unknown\nNum: $errno\nMsg: $errstr\nFile: $errfile\nLine: $errline\n\n>> Attempt will be made to continue, may cause stability issues.\n";
                ZAPI::message( 'info', $text );          
        }
    }

	/**
     * performs a backtrace,  ErrorHandler uses this. 
     * 
     *  if $returnval is set to true it doesn't output it to the terminal,  instead it
     *   returns the backtrace as a string.
     * 
     * @param boolean $fatal         if the backtrace should cause the bot to shutdown
     * @param boolean $returnval    if the backtrace output should be returned as a string
     * 
     * @return mixed     nothing on echo,  or string if $returnval is true
     */
    public static function backtrace( $fatal = FALSE, $returnval = FALSE ) { 
        if( !function_exists( 'debug_backtrace' ) ) { 
            echo "function debug_backtrace() does not exists\n"; 
            return false; 
        } 
    
        $result = "----------------\nDebug backtrace:\n----------------\n"; 
            
        foreach( debug_backtrace() as $t ) { 
             $result .= "\t" . '@ '; 
            
            if( isset( $t['file'] ) ) {
                $result .= basename( $t['file'] ) . ':' . $t['line']; 
            } else { 
                // if file was not set,  I assumed the functioncall 
                // was from PHP compiled source ( ie XML-callbacks ). 
                $result .= '<PHP inner-code>'; 
            } 

            $result .= ' -- '; 

            if( isset( $t['class'] ) ) {
                $result .= $t['class'] . $t['type']; 
            }
            
            $result .= $t['function'];

            if( isset( $t['args'] ) && sizeof( $t['args'] ) > 0 ) {
                $result .= '( ... )'; 
            } else {
                $result .= '()'; 
            }
            $result .= "\n"; 
        } 
        
        if( $returnval ) {
            return $result;    
        } else {
            echo $result;    
            
            if( $fatal ) {
                exit(); 
            }
        }
    }
}

?> 
