<?php
switch( $args[0] ) {
	case 'setaway':
	case 'away':
	if(empty($argsF)){$argsF="[silentaway]";}
		$to = strtolower( $from );
		$config['away'][$to] = array(
			'name'	=>	$from,
			'ts'	=>	array(	strtolower( $c )	=>	time()	),
			'msg'	=>	$argsF,
		);
		save_config( 'away' );
		
		$dAmn->say( "<i><b>* $from</b> is away: $argsF", $c );
		break;
	case 'setback':
	case 'back':
		$to = strtolower( $from );
		unset( $config['away'][$to] );
		save_config( 'away' );
		$dAmn->say( "<i><b>* $from</b> is back. $argsF", $c );
		break;
}
?>