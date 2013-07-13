<?php
global $config;
$room	 	=	strtolower( rChatName( $c ) );
$order		=	$config['welcome'][$room]['order'];
$saveStuff	=	FALSE;
if ( !isset( $order['combine'] ) ) {
	$config['welcome'][$room]['order']['combine'] = FALSE;
	$saveStuff = TRUE;
} 
if ( !isset($order['globalFirst'] ) ) {
	$config['welcome'][$room]['order']['globalFirst'] = TRUE;
	$saveStuff = TRUE;
}
if ( !isset($order['all'] ) ) {
	$config['welcome'][$room]['order']['all'] = 3;
	$saveStuff = TRUE;
} 
if ( !isset($order['pc'] ) ) {
	$config['welcome'][$room]['order']['pc'] = 2;
	$saveStuff = TRUE;
}
if ( !isset($order['indv'] ) ) {
	$config['welcome'][$room]['order']['indv'] = 1;
	$saveStuff = TRUE;
} 
if ( !isset( $order['isActive'] ) ) {
	$config['welcome'][$room]['order']['isActive'] = FALSE;
	$saveStuff = TRUE;
}
if ( $saveStuff ) {
	save_config( 'welcome' );
}
unset( $saveStuff, $room, $order );
?>