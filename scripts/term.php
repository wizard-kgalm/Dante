<?php
// DANTE SCRIPT ======
// Name: Terms
// Description: Displays the definition of a defined term
// Author: SubjectX52873M
// Version: 0.1
// Priv: 50
// Help: term [add/del/list] or term [term]
// ====================
global $config;
switch ( $args[1] ) {
  case 'add':
		if ( !empty( $argsE[3] ) ) {
			$config->df['terms'][$args[2]] = $argsE[3];
			ksort( $config->df['terms'] );
			$config->save_info( "./config/terms.df", $config->df['terms'] );
			$say = "Added term {$args[2]}:<br/>{$argsE[3]}";
		} else {
			$say = "Ah... term and definition plz";
		}
	break;
	case 'list':
		ksort(  $config->df['terms'] );
		$say = '<u><b>Terms list</b></u><br/>'.
		implode( ', ', array_keys( $config->df['terms'] ) );
		break;
	case 'del':
		if ( array_key_exists( $args[2], $config->df['terms'] ) ) {
			unset( $config->df['terms'][$args[2]] );
			ksort( $config->df['terms'] );
			$config->save_info( "./config/terms.df", $config->df['terms'] );
			$say = "[Definition of <b>\"<u>{$args[2]}</u>\"</b>] has been deleted";
		} else {
			$say = "$f Definition of <b>\"<u>{$args[2]}</u>\"</b> not found";
		}
	break;
	case 'about':
		$say = "$f <abbr title=\"From Noodlebot 3.0\">Stolen</abbr> and much improved (on the code level) terms script";
		break;
	case '':
		$say = "term [add/del/list] or term [term]";
	break;
	default:
		if ( array_key_exists( $args[1], $config->df['terms'] ) ) {
			$say = ":magnify:<b>{$argsE[2]}</b> [Definition of <b>\"<u>{$args[1]}</u>\"</b> ]"
			. "<br/>" . $config->df['terms'][$args[1]];
		} else {
			$say = "$f Definition of <b>|<u>{$args[1]}</u>|</b> not found";
		}
	break;
}
$dAmn->say( $say, $c );
?>
