<?php
/**
 *Dante Core File
 *
 *Scripts loader.
 *
 * "If it's open source it's not stealing, it's sharing" - Olyrion
 *@copyright SubjectX52873M (2006)
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 *@version 0.8
 */

global $scripts;
$scripts = array();

intMsg( '** Searching for scripts...' );

$loadList = array();
$modfolder = scandir( f( "scripts" ), 1 );
foreach( $modfolder as $m ) {
	if( is_file( f( "scripts/$m" ) ) && is_php( "scripts/$m" ) && $m != "." && $m != ".." ) {
		$filedata = file_get_contents( f( "scripts/$m" ) );
		$filename = $m;
		$m = basename( $m, ".php" );
		$m = strtolower( $m );
		//Need to parse for metadata, later
		$sfiledata = explode( "\n", $filedata );
		foreach( $sfiledata as $line ) {
			$line = rtrim( $line );
			if( $detecting ) {
				if( preg_match( "/^[ \t]*\/\/ (.+): (.+)$/", $line, $ma ) ) {
					$meta = $ma[2];
					$meta = str_replace( "{tr}", $config->bot['trigger'], $meta );
					$scripts[$m]['metadata'][$ma[1]] = $meta;
				}
				if( preg_match( "/^[ \t]*\/\/ \=+$/", $line ) ) {
					$detecting = FALSE;
					break;
				}
			} else {
				if( preg_match( "/^[ \t]*\/\/ DANTE SCRIPT \=+$/", $line ) ) {
					$detecting = TRUE;
				}
			}
		}

		if( !isset( $scripts[$m]['metadata']['Name'] ) || !isset( $scripts[$m]['metadata']['Description'] ) || !isset( $scripts[$m]['metadata']['Author'] ) || !isset( $scripts[$m]['metadata']['Help'] ) ) {
			internalMessage( "Not all required metadatas are set for script \"" . $m . "\". Please make sure that the Name, Description, Author, and Help metadatas are set. \nThis script will not be loaded." );
		} else {
			if ( !isset( $config->df['access']['priv'][$m] ) ) {
				if ( is_null( $scripts[$m]['metadata']['Priv'] ) ) {
					$config->df['access']['priv'][$m] = 'default';
				} else {
					$config->df['access']['priv'][$m] = $scripts[$m]['metadata']['Priv'];
				}
				$config->save_info( "./config/access.df", $config->df['access'] );
			}
			$scripts[$m]['filename'] = $filename;
			$loadList[] = basename( $filename, ".php" );
				
		}
	}
}
sort( $loadList );
intmsg( "Loaded: " . implode( ", ", $loadList ) );
unset( $loadList );
?>
