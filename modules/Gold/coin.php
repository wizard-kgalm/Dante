<?php
$points = $config['coins'];
		$new = ($points[$from] +1);
		$old = ($points[$from] -1);
		$coins = array("heads","tails");
		$lock = array_rand($coins);
		$luck = $coins[$lock];
		$person = $from;

if($args[1] == "score"){
		$score = $config['coins'];
		$num = 5;
		$i=1;
		arsort($score); 
		$b = array_slice($score,0,$num,true); 
		foreach($b as $player => $pointss) { 
		$say.= ("(".$i.")<b> :dev".ucfirst($player).": = </b>  ".round($pointss).";<br/>");
		$i++;
}
$dAmn->say("<b><u>Top $num</u></b><br/><sub>".$say."",$c);
}
elseif($args[1] == ""){
		$dAmn->say("<sub>$from: You must call the coin; Heads or Tails.",$c);
}
else{
		$call = strtolower($args[1]);
		if($call == $luck ){
			$dAmn->say(":party: Good job $from, you called it right.",$c);
			$config['coins'][$from]=($new);
			save_config('coins');
		}else{
			$dAmn->say("$from: <b>YOU FAIL</b> The coin fell on ".ucfirst($luck)." and you called ".ucfirst($call).".",$c);
			$config['coins'][$from]=($old);
			save_config('coins');
		}return;
}
?>