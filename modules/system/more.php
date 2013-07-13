<?php
//No not edit this file until talking to SubjectX52873M
switch($args[0]){
  case "ping":
		global $pingt;
		$pingt = microtime(true);
		$dAmn->say("Ping?",$c);
		break;
	case "commands":
		if($args[1]=="all"){
			$comList=array();
			foreach(array_keys($scripts) as $com) {
				$comList[$com]=parsePriv($config['access']['priv'][$com]);
			} 
			foreach($modules as $mn => $md){ 
				if(!empty($md->events['cmds']) && ($md->status)){
					foreach($md->events['cmds'] as $cmd => $cmdd) {
						$comList[$cmd]=parsePriv($cmdd);
					}
				}
			}
			$say="<sup><u>All Commands:</u><br/>";
			ksort($comList);
			foreach($comList as $com => $priv){
				$say.= "[<abbr title=\"".$config['access']['priv'][$com]."\">{$com}</abbr> <b>".$priv."</b>]";
			}
			$say.="<br/>".
			" Guests: ".$config['access']['default']['guests']." |".
			" Commands: ".$config['access']['default']['commands']." |".
			" Events:".$config['access']['default']['events']." |".
			" Errors: ".$config['access']['default']['errors']." |".
			" Trigcheck: ".$config['access']['default']['trigcheck'];
			$say .= "\n*This commands real setting is default";
		}else{
			$comList=array();
			foreach(array_keys($scripts) as $com) {
				if ($user->has($from,$config['access']['priv'][$com]))
					$comList[$com]=parsePriv($config['access']['priv'][$com]);
			} 
			foreach($modules as $mn => $md){ 
				if(!empty($md->events['cmds']) && ($md->status)){
					foreach($md->events['cmds'] as $cmd => $cmdd) {
						if ($user->has($from,$config['access']['priv'][$cmd]))
							$comList[$cmd]=parsePriv($config['access']['priv'][$cmd]);
						
					}
				}
			}
			$say="<sup><u>You have access to the following commands:</u><br/>";
			ksort($comList);
			foreach($comList as $com => $priv){
				$say.= "[<abbr title=\"Privilege: ".$priv."\">{$com}</abbr>] ";
			}
			$say.="";
			
		}
		$dAmn->say($say, $c);
		break;
	case "modules":
		$cmd = $args[2];
		$mn = $args[1];
		switch($cmd){
			case "on":
				if(!isset($modules[$mn])){ 
					$dAmn->say($f . "Module \"" . $mn . "\" was not found.", $c); 
				} elseif($modules[$mn]->status){ 
					$dAmn->say($f . "Module \"" . $mn . "\" is already switched on.", $c); 
				} else {
					$modules[$mn]->setStatus(1);
					$dAmn->say($f . "Module \"" . $mn . "\" switched on.", $c);
				}
				break;
			case "off":
				if($modules[$mn]->iscore){ 
					$dAmn->say($f . "Cannot unload a core module.", $c); 
				} else if(!isset($modules[$mn])){ 
					$dAmn->say($f . "Module \"" . $mn . "\" was not found.", $c); 
				} elseif(!$modules[$mn]->status){ 
					$dAmn->say($f . "Module \"" . $mn . "\" is already switched off.", $c); 
				} else {
					$modules[$mn]->setStatus(0);
					$dAmn->say($f . "Module \"" . $mn . "\" switched off.", $c);
				}
				break;
			case "info":
				if(!isset($modules[$mn])){
					$dAmn->say($f . "Module \"" . $mn . "\" was not found.", $c);
				} else {
					$say = "<strong><u>Module &#8220;" . $mn . "&#8221;</u></strong>:<br /><strong>Title:</strong> ".$modules[$mn]->title."<br /><strong>Author:</strong> ".$modules[$mn]->author."<br /><strong>Version:</strong> ".$modules[$mn]->version."<br /><strong>Description:</strong> ".$modules[$mn]->about." ";
					$dAmn->say($say, $c);
				}
				break;
			case "details":
				if(!isset($modules[$mn])){
					$dAmn->say($f . "Module \"" . $mn . "\" was not found.", $c);
				} else {	
					$say = "<strong><u>Module &#8220;" . $mn . "&#8221;</u></strong>:\n";
					$say .= "<strong>Status:</strong> " . (($modules[$mn]->status) ? "On" : "Off") . "\n";
					$mCmds = "";
					$mEvents = "";
					foreach($modules[$mn]->events as $type => $typedata) {
						if($type == "cmds"){
							var_dump(count($typedata));
							$mCmds .= "<strong>Commands:</strong>\n<sup><ul>";
							foreach($typedata as $index => $data) {
								$mCmds .= "<li><strong>" . $index . "</strong>: ";
								$mCmds .= "file&#61;" . $data['file'] . ", priv&#61;" . $data['priv'] . "</li>";
							}
							$mCmds .= "</ul></sup>";
						} else {
							$mEvents .= "<li><strong>" . $type . "</strong>:";
							foreach($typedata as $index => $data) {
								$mEvents .= " " . $index . ":[file&#61;" . $data['file'] . ", priv&#61;" . $data['priv'] . "]";
							}
							$mEvents .= "</li>";
						}
					}
					if(!$mEvents){
						$mEvents = "\nNone.";
						$mEventsHTML = $mEvents;
					} else {
						$mEventsHTML = "<ul>" . $mEvents . "</ul>";
					}
					$say .= $mCmds . "<strong>Events:</strong><sup>" . $mEventsHTML . "</sup>";
					$dAmn->say($say, $c);
				}
				break;
			default:
				if ($mn=="reload" && $cmd=='') {
					include f("system/callables/xload.php");
					$dAmn->say("$f Modules have been reloaded",$c);
				} elseif ($mn=="list" && $cmd=='') {
					$say = "<strong><u>Loaded modules</u></strong>:<br />";
					foreach($modules as $mn => $md) {
						$say .= ($md->status == 1) ? "<strong>" . $mn . "</strong>, " : $mn . ", ";
					}
					$say = rtrim($say, ", ");
					$dAmn->say($say . "<br /><sub>(Bolded modules are activated.)</sub>", $c);
				} else {
					$dAmn->say($f . "With this command, you can see more informations about the modules you&#8217;ve installed in your bot. Usage: " . $tr . "module [modulename|reload|list] [on|off|details|info]\n<sup>Example: " . $tr . "module calc details</sup>", $c);
				}
				break;
		}
		break;
	case "quit":
		if(!$user->has($from, 99)){ break; } // For now
		quit($from, $c);
		break;
	case "restart":
		if (CanRestart){
			echo "Restarting Bot...";
			file_put_contents(f('config/restart.txt'),"Don't delete this file while the bot is running.\n".$dAmn->disconnectCount."\nBot\n".time()."\nW00t");
			$dAmn->say("Restarting...",$c);
			quit($from, false);
		}else{
			$dAmn->say("Bot can not be restarted unless launched by the provided batch file.",$c);
		}
		break;
}
?>
