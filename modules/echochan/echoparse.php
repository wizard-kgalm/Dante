<?php
//$event = event type
if(isset($config['techo'][strtolower($c)])) {
	$outstring = "";
	switch($event) {
		case 'recv-msg':
			$outstring = "[$c] <<b>$from</b>> $message";
			break;
		case 'recv-action':
			$outstring = "[$c] * <i><b>$from</b> $message</i>";
			break;
		case 'recv-join':
			$outstring = "[$c] ** <b>$from</b> joined channel";
			break;
		case 'recv-part':
			$outstring = "[$c] ** <b>$from</b> left channel";
			break;
		case 'recv-kicked':
			$outstring = "[$c] ** <b>$from</b> was kicked by $by";
			if(strlen($message) > 0) {
				$outstring.=" (".$message.")";
			}
			break;
		case 'recv-privchg':
			$outstring = "[$c] ** <b>$from</b> has been made a member of $pc by $by";
			break;
		default:
			print "EVENTTOFIXX: $event\n";
	}
	if(strlen($outstring) > 0) {//Stops blank strings from going about the place
		foreach($config['techo'][strtolower($c)] as $k => $v) {
			if($v===true) {//For when echoes can be turned on and off without deletion
				$dAmn->say($outstring,$k);
			}
		}
	}
}
?>