<?php
/*
Notes
*/
if($c !=rChatName("iRPG")){
if($c ==rChatName("MagicCorner")){ return false;}
global $config;
$tr = $config['bot']['trigger'];
$to = strtolower( $from );
if( $config['notes'][$to]['new'] ) {
	$txt = '';
	foreach( $config['notes'][$to] as $id => $msg ) {
		if( $id !== 'new' ) {
			$txt .= $msg . '<br>';
			$tot = $id;
		}
	}
	$tot++;
	$config['notes'][$to]['new'] = false;
	save_config( 'notes' );
	$dAmn->say( "<b>:note: $from: You have $tot note(s).</b><br>$txt<b>----------------------------</b><br><i>Say {$tr}note clear to clear your notes and {$tr}note read to have them read to you again.</i>", $c );
}
}else return false;
unset( $to, $tot, $txt, $tr );
?>