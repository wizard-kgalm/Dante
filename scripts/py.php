<?php
// DANTE SCRIPT ======
// Name: Run Python Script
// Description: Runs a python script from a specificed folder
// Author: SubjectX52873M
// Version: 0.4
// Help: "py [(command)/list]"
// ====================

if( $args[1] == 'list' ) {
  $result = "<b>Listing Script Folder</b><br>" . shell_exec( 'dir/l/b/og "c:/python24/prog/"' );
} elseif ( $args[1] == 'list' ) {
	$result = "py [(command)/list]";
} else {
	$result = shell_exec( 'python "c:/python24/prog/' . $argsF . '.py"' );
}
if ( $result == '' ) {
	$result =" Script either crashed or was not found.";
}
$dAmn->say( $result, $c );
?>
