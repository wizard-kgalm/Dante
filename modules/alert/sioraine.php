<?php
if($c == "#dAmnEpic"){
	switch($from){
		case "sioraine":
			echo "\x07";
			echo "\x07";
			$dAmn->say("Wizard-Kgalm: sioraine has joined.","#denoffire");
			$dateobj = date_create( "now", timezone_open( date_default_timezone_get() ) );
			$config['sioraine']['seen'] = date( "Y-m-d" )." at ". date_format( $dateobj, "g:i:sA" );
			$config['sioraine']['times'][] = date( "Y-m-d" )." at ". date_format( $dateobj, "g:i:sA" );
			save_config("sioraine");
		break;
		case "Victimize-Pixie":
			echo "\x07";
			echo "\x07";
			$dAmn->say("Wizard-Kgalm: Victimize-Pixie has joined.","#denoffire");
			$dateobj = date_create( "now", timezone_open( date_default_timezone_get() ) );
			$config['Kenna']['seen'] = date( "Y-m-d" )." at ". date_format( $dateobj, "g:i:sA" );
			$config['Kenna']['times'][] = date( "Y-m-d" )." at ". date_format( $dateobj, "g:i:sA" );
			save_config("Kenna");
		break;
		default: 
			$dateobj = date_create( "now", timezone_open( date_default_timezone_get() ) );
			$config['alert'][strtolower($from)]['seen'] = date( "Y-m-d" )." at ". date_format( $dateobj, "g:i:sA" );
			$config['alert'][strtolower($from)]['times'][] = date( "Y-m-d" )." at ". date_format( $dateobj, "g:i:sA" );
			save_config("alert");
		break;
	}	
	
}
switch($args[0]){
	case "spotted":
		if(!empty($args[1])){
			if(isset($config['alert'][strtolower($args[1])])){
				$dAmn->say("{$args[1]} last spotted at {$config['alert'][strtolower($args[1])]['seen']}.", $c);
			}else
				$dAmn->say("$from: {$args[1]} hasn't been marked.",$c);
		}else
		if(isset($config['sioraine']['seen'])){
			$dAmn->say("Last spotted at ".$config['sioraine']['seen'].".",$c);
		}else
		$dAmn->say("$from: No time marked.",$c);
	break;
	case "kspot":
		if(isset($config['Kenna']['seen'])){
			$dAmn->say("Last spotted at ".$config['Kenna']['seen'].".",$c);
		}else
		$dAmn->say("$from: No time marked.",$c);
	break;
	case "ktimes":
		if(isset($config['Kenna']['times'])){
			$say = "<sup><ol>";
			foreach($config['Kenna']['times'] as $timeson => $now){
				$say .= "<li> $now</li>";
			}
			$dAmn->say($say,$c);
		}else
		$dAmn->say("$from: No time marked.",$c);
	break;
	case "times":
		if(!empty($args[1])){
			if(isset($config['alert'][strtolower($args[1])]['times'])){
				$say = "Times for <b>$args[1]</b><sup><br><ol>";
				foreach($config['alert'][strtolower($args[1])]['times'] as $timeson => $now){
					$say .= "<li> $now</li>";
				}
				$dAmn->say($say,$c);
			}else
				$dAmn->say("{$from}: $args[1] hasn't been tracked.",$c);
		}else
		if(isset($config['sioraine']['times'])){
			$say = "<sup><ol>";
			foreach($config['sioraine']['times'] as $timeson => $now){
				$say .= "<li> $now</li>";
			}
			$dAmn->say($say,$c);
		}else
		$dAmn->say("$from: No time marked.",$c);
	break;
}