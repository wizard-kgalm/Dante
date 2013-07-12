<?php
// DANTE SCRIPT ======
// Name: Bug List
// Description: Stores the bug list for this bot
// Author: SubjectX52873M
// Version: 0.4
// Priv: 25
// Help: bug [add/del/list/update]
// ====================
global $config;
switch ( strtolower( $args[1] ) ) {
  case "list":
		$say = "<u><b>List of things to do.</b></u>\n";
		foreach ( $config->df['todo'] as $bt => $com ) {
			$say .= "$bt. <i>$com</i>\n";
		}
		if ( empty( $config->df['todo'] ) ) {
			$say = "List is empty...have you finished your homework?";
		}
		$dAmn->say( $say, $c );
	break;
	case "add":
		if ( !is_array( $config['todo'] ) ) {
			$config->df['todo'] = array();
		}
		if ( !empty( $argsE[2] ) ) {
			$config->df['todo'][] = $argsE[2];
			$config->save_info( "./config/todo.df", $config->df['todo'] );
			$dAmn->say( "Added to the list.", $c );
		} else {
			$dAmn->say( "You didn't supply anything to add.", $c );
		}
	break;
	case "update":
		if( !isset( $config->df['todo'][$args[2]] ) ) {
			$dAmn->say( "Item doesn't exist, can't update.", $c );
		} else {
			if ( !empty( $argsE[3] ) ) {
				$config->df['todo'][$args[2]] = $argsE[3];
				$config->save_info( "./config/todo.df", $config->df['todo'] );
				$dAmn->say( "Item {$args[2]} updated.", $c );
			} else {
				$dAmn->say( "You didn't describe the item.", $c );
			}
		}
	break;
	case "sort":
		array_unshift( $config->df['todo'], "placeholder" );
		unset( $config->df['todo'][0] );
		$config->save_info( "./config/todo.df", $config->df['todo'] );
		$dAmn->say( "List sorted", $c );
	break;
	case "del":
		if ( isset( $config->df['todo'][$args[2]] ) ) {
			unset( $config->df['todo'][$args[2]] );
			ksort( $config->df['todo'] );
			$config->save_info( "./config/todo.df", $config->df['todo'] );
			$dAmn->say( "Removed from the list.", $c );
		} else {
			$dAmn->say( "Not in the list.", $c );
		}
	break;
	case "about":
		$dAmn->say( "A list.", $c );
	break;
	default:
		$dAmn->say( "todo [list]\ntodo [add/update] [bug] [text]\ntodo [del] [bug]", $c );
	break;
}
?>
