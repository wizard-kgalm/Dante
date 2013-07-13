<?php

$com = $message;
if(stristr($message,$config['bot']['owner'])){
	if($c == "#DataShare"){
		return;
	}
	if($c == "#DerkleCave"){
		return;
	}
	echo "\x07";
}

/*	$in = explode( " ", $com );
	$coc=array();
	foreach( $in as $n => $jar ) {
		$x = explode( " ", $com, $n + 1 );
		$coc[] = $x[count( $x ) - 1];
	}
	foreach($in as $var => $word){
		if($word === "Wizard-Kgalm:" || strtolower($word) === "wizard-kgalm:"){
			echo "\x07";
		}
		if($word === "Wizard-Kgalm" || strtolower($word) === "wizard-kgalm"){
			echo "\x07";
		}
		if($word === "Wizard-Kgalm," || strtolower($word) === "wizard-kgalm,"){
			echo "\x07";
		}
	}
	*/