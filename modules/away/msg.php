<?php
//This handles the away messages.
if(strtolower($from)!=strtolower("Wizard-Kgalm")){
if(strtolower($c)!=strtolower("#iRPG")){

global $dAmn,$config;
$delay = 10; //Flood protection
$args = explode( " ", $message, 2 );
$to = strtolower( rtrim( $args[0], ",: " ) );
if($config['away'][$to]['msg'] !="[silentaway]"){
if ( is_array( $config['away'][$to] ) && strtolower( $to ) != strtolower( $from ) ) {
	if ( ( $config['away'][$to]['ts'][$cc] + $delay ) < time() ) {
		$config['away'][$to]['ts'][$cc] = time();
		$dAmn->say( "$from, $to is currently away. Reason: " . $config['away'][$to]['msg'] . "", $c );
	}
}
}else return false;
}else return false;
}else return false;
unset( $to,$args,$delay );
?>