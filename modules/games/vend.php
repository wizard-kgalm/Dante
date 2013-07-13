<?php

// Add drinks using same syntax
global $config;
if( empty( $config['vending_machine'] ) ) {
	$config['vending_machine'] = array(
		'coke',
		'diet coke',
		'pepsi',
		'diet pepsi',
		'lemonade',
		'wine',
		'beer',
		'mountain dew',
		'tea',
		'electictocnet doll',
		'sprite',
		'coffee',
		'root beer',
		'hot chocolate',
		'cream soda',
		'fuzzy dice',
	);
	sort( $config['vending_machine'] );
}
// Quality of vending machine
// High # = Better quality
// 0 quality always jams
$quality = 5;

$found = in_array( strtolower( $argsE[2] ), $config['vending_machine'] );
switch( strtolower( $args[1] ) ) {
	case '':
		$dAmn->say( "$from: Please order something.", $c );
		break;
	case 'add':
		if( $found ) {
			$dAmn->say( "Can't add, the machine already stocks {$argsE[2]}.", $c );
		} else {
			$dAmn->say( "{$argsE[2]} added!", $c );
			$config['vending_machine'][] = strtolower( $argsE[2] );
			sort( $config['vending_machine'] );
			save_config( 'vending_machine' );
		}
		break;
	case 'del':
		$canfind = FALSE;
		foreach($config['vending_machine'] as $ID =>$VEND){
			if(strtolower($VEND) === strtolower($argsE[2])){
				unset( $config['vending_machine'][$ID] );
				save_config( 'vending_machine' );
				$canfind = TRUE;
			}
		}
		if($canfind){
			$dAmn->say( "{$argsE[2]} removed!", $c );
		} else {
			$dAmn->say( "Can't remove, the machine doesn't stock {$argsE[2]}.", $c );
		}
		break;
	case 'list':
		$say = "<sup>The machine stocks: ";
		$say2 ="<sup>";
		$say3 ="<sup>";
		$say4 ="<sup>";
		$say5 ="<sup>";
		$say6 ="<sup>";
		$say7 ="<sup>";
		foreach( $config['vending_machine'] as $tc => $vensd){
			if($tc < 220){
				$say .="{$vensd}, ";
			}
			if($tc > 220){
				if($tc < 440){
					$say2 .="{$vensd}, ";
				}
			}
			if($tc > 440){
				if($tc < 660){
					$say3 .="{$vensd}, ";
				}
			}
			if($tc > 660){
				if($tc < 880){
					$say4 .="{$vensd}, ";
				}
			}
			if($tc > 880){
				if($tc < 1010){
					$say5 .="{$vensd}, ";
				}
			}
			if($tc > 1010){
				if($tc < 1230){
					$say6 .="{$vensd}, ";
				}
			}
			if($tc > 1230){
				if($tc < 1450){
					$say7 .="{$vensd}, ";
				}
			}
		}
		$dAmn->say( $say, $c );
		$dAmn->say( $say2,$c);
		$dAmn->say( $say3,$c);
		$dAmn->say( $say4,$c);
		$dAmn->say( $say5,$c);
		$dAmn->say( $say6,$c);
		$dAmn->say( $say7,$c);
		break;
	default:
		if( !in_array( strtolower( $argsF ), $config['vending_machine'] ) ) {
			$dAmn->say( "$f The machine doesn't stock $argsF.", $c );
			return;
		}
		$items	=	count( $config['vending_machine'] );
		$max	=	intval( floor( ( $items * $quality ) + 1 ) );
		$get	=	mt_rand( 1, $max );
		$jam	=	intval( floor( .5 * $items ) );
		$oops	=	$items;
		$i		=	array_rand( $config['vending_machine'] );
		$vend	=	$config['vending_machine'][$i];
		if( $vend == strtolower( $argsF ) ) $get = $oops + 1;
		if( $jam > $get ) $dAmn->say( "$f It's jammed. :thumb14796735:", $c );
		elseif( $oops > $get ) $dAmn->say( "$f You got a $vend. :shakefist: ", $c );
		else $dAmn->say( "$f Here is your $argsF.", $c );
		break;
}


?>