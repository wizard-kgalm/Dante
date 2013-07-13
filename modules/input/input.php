<?php
$fom = $config['bot']['owner'];


global $config, $dAmn;
if(!file_exists(f('config/input2.vudf'))){
	return false;
}
	$todo = file('config/input2.vudf');
	$find = array("%l","%g","%v","%a",);
	$replace = array("<",">","|","&",);
	$todo = str_ireplace($find, $replace, $todo);
		$com = $todo[0];
		$in = explode( " ", $com );
		$coc=array();
		foreach( $in as $n => $jar ) {
		$x = explode( " ", $com, $n + 1 );
		$coc[] = $x[count( $x ) - 1];
	} 
	//$fins=array("%l","%g","%v","%a",);
	//$raps=array("<",">","|","&",);
	//$com = str_ireplace($fins,$raps,$com);
	$bZikes = $config['bzikes2'][':on:'];
	if($bZikes){
		$biz = strtolower( substr( $com, 0, strlen( $config['bot']['trigger']."bzikes" ) + 1 ) );
		if(substr( $com, 0, strlen( "bzikes" ))!= "bzikes"){
			foreach($config['bzikes'] as $code => $thumb){
				$com = str_replace($code, ":thumb".$thumb.":",$com);
			}
		}
	}
	$fom=$config['bot']['owner'];
	if(substr($com,0,1) == "/") {
		$cm = substr($com, 1);
		
		$in[0] = substr($in[0], 1);
		$coc[0] = substr($coc[0],1);
		if(strtolower($in[0]) == "restart" || strtolower($in[0]) == "quit"){
			unlink(f('config/input2.vudf'));
		}
		$cocks = $com[2];
		if($in[1][0]=="#"){
			$chatname = $in[1];
			$config['input']['dochat'] = $in[1];
			save_config('input');
		}
		$chatname = $config['input']['dochat'];
		parseCommand($config['bot']['trigger'].$coc[0], $fom, $chatname, "msg");
		return unlink(f('config/input2.vudf'));
	}
	if($in[0][0]=="#"){
		$chatname = $in[0];
		$config['input']['dochat'] = $in[0];
		save_config('input');
	}
	$chatname = $config['input']['dochat'];
	parseCommand($config['bot']['trigger'].'say '.$com, $fom, $chatname, "msg");
	unlink(f('config/input2.vudf'));
	
	