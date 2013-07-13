<?php
/*
$welcome = array (
	':globals:' => array(
		'all' =>"",
		'indv' => array(
			$from="message",
		),
		'pc' => "",
		'order' => array(
			'isActive' => T:F
			'globalFirst' => T:F
			'all' => 0,1,2,3
			'pc' => 0,1,2,3
			'indv' => 0,1,2,3
			'combine' => T:F
			)
		),
	'#room' => array(
	'all' =>"",
	'indv' => array(
		$from="message",
		),
	'pc' => array(
		$name="message",
		),,
	),
	'order' => array(
		'isActive' => T:F
		'globalFirst' => T:F
		'all' => 0,1,2,3
		'pc' => 0,1,2,3
		'indv' => 0,1,2,3
		'combine' => T:F
		)
);
*/
$cc = $room = strtolower( rChatName( $c ) );
$order = $config['welcome'][$room]['order'];
if ( !isset( $order['combine'] ) ) {
	$config['welcome'][$room]['order']['combine'] = FALSE;
} 
if ( !isset( $order['globalFirst'] ) ) {
	$config['welcome'][$room]['order']['globalFirst'] = TRUE;
}
if ( !isset( $order['all'] ) ) {
	$config['welcome'][$room]['order']['all'] = 3;
} 
if ( !isset( $order['pc'] ) ) {
	$config['welcome'][$room]['order']['pc'] = 2;
}
if ( !isset( $order['indv'] ) ) {
	$config['welcome'][$room]['order']['indv'] = 1;
} 
if ( !isset( $order['isActive'] ) ) {
	$config['welcome'][$room]['order']['isActive'] = TRUE;
}
unset( $room, $order );

if( $config['welcome'][$cc]['order']['isActive'] === FALSE ) return;

$wglobal = $config['welcome'][':global:'];
$wroom = $config['welcome'][$cc];
$wfrom = strtolower($from);

if ( !isset( $config['welcome'][$cc]['order']['combine'] ) ) {
	$combine = $config['welcome'][':global:']['order']['combine'];
} else {
	$combine = $config['welcome'][$cc]['order']['combine'];
}
if ( !isset( $config['welcome'][$cc]['order']['globalFirst'] ) ) {
	$globalFirst = $config['welcome'][':global:']['order']['globalFirst'];
} else {
	$globalFirst = $config['welcome'][$cc]['order']['globalFirst'];
}
$order = $config['welcome'][':global:']['order'];
$roomorder = $config['welcome'][$cc]['order'];
$privclass = strtolower( $dAmn->room[$cc]->getUserData( $from,'pc' ) );

//Begin order sorting for rooms
$g = array(
	1	=>	'',
	2	=>	'',
	3	=>	'',
	4	=>	'',
	5	=>	'',
	6	=>	''
);

//Pulled out all that if statements.
//GLOBAL MESSAGES
//All 
$g[$order['all']] = $wglobal['all'];
//Privclass
$g[$order['pc']] = $wglobal['pc'][$privclass];
//Indv
$g[$order['indv']] = $wglobal['indv'][$wfrom];
//ROOM MESSAGES
//All 
$g[($roomorder['all']+3)] = $wroom['all'];
//Privclass
$g[($roomorder['pc']+3)] = $wroom['pc'][$privclass];
//Indv
$g[($roomorder['indv']+3)] = $wroom['indv'][$wfrom];

//Need to combine
if ( $globalFirst ) {
	if ( !$combine ) {
		$welcomemsg = NULL;
		if ( $g[1] ) $welcomemsg = $g[1];
		elseif ( $g[2] ) $welcomemsg = $g[2];
		elseif ( $g[3] ) $welcomemsg = $g[3];
		elseif ( $g[4] ) $welcomemsg = $g[4];
		elseif ( $g[5] ) $welcomemsg = $g[5];
		elseif ( $g[6] ) $welcomemsg = $g[6];
	} else $welcomemsg = array(	$g[1], $g[2], $g[3], $g[4], $g[5], $g[6]	);
} else {
	if (!$combine) {
		$welcomemsg = NULL;
		if ( $g[4] ) $welcomemsg = $g[4];
		elseif ( $g[5] ) $welcomemsg = $g[5];
		elseif ( $g[6] ) $welcomemsg = $g[6];
		elseif ( $g[1] ) $welcomemsg = $g[1];
		elseif ( $g[2] ) $welcomemsg = $g[2];
		elseif ( $g[3] ) $welcomemsg = $g[3];
	} else $welcomemsg = array(	$g[4], $g[5], $g[6], $g[1], $g[2], $g[3]	);
}

if ( is_array( $welcomemsg ) && !empty( $welcomemsg ) ) {
	foreach( $welcomemsg as $i => $wmsg ) {
		if( !empty( $wmsg ) ) {
			$wmsg = str_replace( "{from}", $from, $wmsg );
			$wmsg = str_replace( "{room}", $c, $wmsg );
			$wmsg = str_replace( "{privclass}", $dAmn->room[$cc]->getUserData( $from, 'pc' ), $wmsg );
			$welcomemsg = str_replace( "{bot}", $config['bot']['username'], $welcomemsg );
			if( !empty( $wmsg ) ) $dAmn->say( $wmsg, $c );
		}
	}
} elseif( $welcomemsg ) {
	if( !empty( $welcomemsg ) ) {
		$welcomemsg = str_replace( "{from}", $from, $welcomemsg );
		$welcomemsg = str_replace( "{bot}", $config['bot']['username'], $welcomemsg );
		$welcomemsg = str_replace( "{room}", $c, $welcomemsg );
		$welcomemsg = str_replace( "{privclass}", $dAmn->room[$cc]->getUserData( $from, 'pc' ), $welcomemsg );
		$dAmn->say( $welcomemsg, $c );
	}
} else return; 

//Clean these up so other modules don't blow a gasket.
unset( $wglobal, $globalFirst, $welcomemsg, $wmsg, $wglobal, $wroom, $wfrom, $combine, $roomorder, $privclass, $g );
?>