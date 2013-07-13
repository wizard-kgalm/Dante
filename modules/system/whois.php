<?php
if( $config->bot['showWhois'] ) {
	$info = $params[2];
	$conn = $params[3];
	$say = ":icon{$info['name']}: :dev{$info['name']}:";
	$say .= "<br> {$info['realname']}".
		"<br> {$info['typename']}";
	if( $info['gpc'] != 'guest' ) $say .= "<br> {$info['gpc']}";

	foreach( $conn as $i => $o ) {
		$say .= "<br><br><em>Connection " . ( $i + 1 ) . "</em><br>".
			"<strong>Online:</strong> <abbr title=\"Local Time\">" . date( "D, M j, Y H:m:s", time() - $o['online'] ) . "</abbr> (" . timeString( $o['online'], TRUE ) . ")<br>" .
			"<strong>Idle:</strong> ".  timeString( $o['idle'] ) . "<br>" .
			implode( ", ", $o['rooms'] ) . "";
	}

	if( !empty( $c ) ) {
		$this->say( $say, $c );
	}
}
?>