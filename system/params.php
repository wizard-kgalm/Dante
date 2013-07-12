<?php
/**
 *Used to break up packets.
 *@return array
 */
function params( $datas, $firstpart = TRUE ) {
  // This function is used to parse dAmn responses
	$newdatas = array();
	$newdatas2 = array();
	if( !is_array( $datas ) ) {
		$datas = explode( "\n", $datas );
	}
	foreach( $datas as $data ) {
		if( str_replace( "=", "", $data ) != $data ) {
			$newdatas[] = explode( "=", $data, 2 );
		} elseif( str_replace( ":", "", $data ) != $data ) {
			$newdatas[] = explode( ":", $data, 2 );
		} elseif( str_replace( " ", "", $data ) != $data && empty( $newdatas[0] ) && $firstpart ) {
			//Space seperation only permitted if it's the first line
			$newdatas[] = explode( " ", $data, 2 );
		} else {
			$newdatas[] = $data;
		}
			
	}
	foreach( $newdatas as $newdata ) {
		if( is_array( $newdata ) ) {
			$newdatas2[$newdata[0]] = $newdata[1];
		} else {
			if( $newdata != "\n" && !empty( $newdata ) ) {
				$newdatas2[] = $newdata;
			}
		}
	}
	if( $newdatas2[''] == "" ) {
		unset( $newdatas2[''] );
	}
	return $newdatas2;
}
?>
