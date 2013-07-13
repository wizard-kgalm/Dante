<?php
$to=strtolower($args[1]);
$msg=$argsE[2];


if($to=='clear') {
		$to=strtolower($from);
		$config['notes'][$to]=null;
		unset($config['notes'][$to]);
		$dAmn->say("<b>:note: $from: Notes deleted. :chew:", $c);
		save_config('notes');
		return;
}
if($to=='new') {
		if ($config['notes'][$from]) {
			$config['notes'][$from]['new']=true;
			$dAmn->say(":note: $from: your notes will be read to you the next time you speak.",$c);
		} else {
			$dAmn->say(":note: $from:  You don't have any notes.",$c);
		}
}


if($to=='read')
{
		global $dir;
		$to=strtolower($from);
		if($config['notes'][$to]) {
			$txt='';
			foreach($config['notes'][$to] as $id => $msg)
				if($id!=='new')
				{
					$txt.=$msg.'<br>';
					$tot=$id;
				}
			$tot++;
			$config['notes'][$to]['new']=false;
			save_config('notes');
			$dAmn->say("<b>:note: $from: You have $tot note(s).</b><br>$txt<b>----------------------------</b><br><i>Say ${tr}note clear to clear your notes and ${tr}note read to have them read to you again.</i>", $c);
		}
		else $dAmn->say("<b>:note: $from: You have no notes.", $c);
		return;
}

if($to==false || $msg==false) {
		$dAmn->say("$from: note [to] [msg]", $c);
		return;
}
if(!$config['notes'][$to]) { $config['notes'][$to]=array();}
$config['notes'][$to][]='<b>From</b> '.$from.' <b>on</b> '.date("r").'<br><i>'.$msg.'</i>';
$config['notes'][$to]['new']=true;
save_config('notes');
$dAmn->say("$f :note: Note has been sent to $to.", $c);
?>