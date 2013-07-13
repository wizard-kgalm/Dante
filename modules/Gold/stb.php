<?php
$from = strtolower($from);
global $config;
$a = $config['stb'];
switch ($args[1]) {
	case '':
		$dAmn->say("$f [spin/join/about/list/part/score/kick/reset/spinner/ban/unban] :thumb55343223:",$c);
	break;
case 'join':
		if(in_array(strtolower($from),$config['stban'])){	
				$dAmn->say("$from sorry but you are not allowed to play.",$c);
		}else{
				if(in_array($from,$config['stb'])){
						$dAmn->say("You are part of the game already {$from}.:thumb55343223:",$c);
				}else{
						$froms = strtolower($from);
						$dAmn->say("You joined the game {$from}.:thumb55343223:",$c);
						$config['stb'][$froms]=($froms);
						save_config('stb');
					}
			}	
	break;
case 'part':
		$from = strtolower($from);
			if(!in_array($from,$config['stb'])){
					$dAmn->say("You haven't joined the game $from.:thumb53878678:",$c);
			}else{
					$someshit = (count("$f") -3); 
					$bull = substr("$f",0,$someshit);
					$dAmn->say("You've left the game {$from}.:thumb53878678:",$c);
					unset($config['stb'][$from]);
					save_config('stb');
				}
	break;
case 'spin':
	if(!in_array(strtolower($from),$config['spinners'])){
			$dAmn->say("$from: Sorry only spinners can spin.",$c);
	}else{
			$room=$c;  
			$dalist = $dAmn->room[$cc]->getUserList();
			foreach($dalist as $key => $value){
					$key = strtolower($key);
					$value = strtolower($value);
					$dalist[$key]=$value;
				}
			$at = array_intersect($dalist,$config['stb']);  
			if(count($at) <= 1){
						$dAmn->say("<B> Error: </B> More players are required to play.",$c);
				}else{
						$d = ($config['stb'][array_rand($config['stb'])]); 
						$r = ($config['stb'][array_rand($config['stb'])]);
							if(empty($config['stb'])){
									$dAmn->say("<B>Need people to join the game in order to play.",$c);
						}
							elseif(count($config['stb']) <= 1){
									$dAmn->say("<B>Error: <b/>1 person is playing the game. More people are needed to start",$c);
							}else{
									do { 
											$d = ($config['stb'][array_rand($config['stb'])]);
											$r = ($config['stb'][array_rand($config['stb'])]); 
											$todo=array("<B>:dev$r: gets to ask :dev$d: a question","<B>:dev$r: gets to dare :dev$d:"); 
											$wtf=($todo[array_rand($todo)]); 
										}
									while("$d" == "$r");
											$plz = (in_array($r,$dalist));
											$thnx = (in_array($d,$dalist));
					if($plz && $thnx){
								$dAmn->say(":thumb55343223: Spinning the bottle...",$c);
								$dAmn->say("<B>Person #1: :dev$r:",$c);
								$dAmn->say("<B>Person #2: :dev$d:",$c); 
								$todo=array("<B>:dev$r: gets to ask :dev$d: a question","<B>:dev$r: gets to dare :dev$d:"); 
								$wtf=($todo[array_rand($todo)]); 
								$dAmn->say("<B> Task selected: $wtf",$c);
								$th = $config['stbgame'][$r];
								$config['stbgame'][$r]=($th + 1);
								$ht = $config['stbgame'][$d];
								$config['stbgame'][$d]=($ht + 1);
								save_config('stbgame');
					}else{
							do { 
									$h = ($config['stb'][array_rand($config['stb'])]);
									$r = ($config['stb'][array_rand($config['stb'])]); 
									$todo=array("<B>:dev$r: gets to ask :dev$d: a question","<B>:dev$r: gets to dare :dev$d:"); 
									$wtf=($todo[array_rand($todo)]); 
								}
							while("$h" == "$r");
							do { 
									$h = ($config['stb'][array_rand($config['stb'])]);
								}
							while((!in_array($h,$dalist)));
							do { 
									$r = ($config['stb'][array_rand($config['stb'])]); 
								}
							while(!in_array($r,$dalist) || $r == $h);

									$dAmn->say("Spinning the bottle...",$c);
									$dAmn->say("<B>Person #1: :dev$r:",$c);
									$dAmn->say("<B>Person #2: :dev$h: ",$c); 
									$todo=array("<B>:dev$r: gets to ask :dev$h: a question","<B>:dev$r: gets to dare :dev$h:"); 
									$wtf=($todo[array_rand($todo)]); 
									$dAmn->say("<B> Task selected: $wtf",$c);
									$th = $config['stbgame'][$r];
									$config['stbgame'][$r]=($th+ 1);
									$ht = $config['stbgame'][$h];
									$config['stbgame'][$h]=($ht + 1);
									save_config('stbgame');
								}
						}
				}
		}
	break;
case 'about':
		$dAmn->say("$f Script developed by <B>:devgoldenskl: <br/> :devdabingo: also gets credit for giving some create ideas. </B>",$c);
	break;
case 'list':
		$tupac = $dAmn->room[$cc]->getUserList();
		foreach($tupac as $key => $value){
			$key = strtolower($key);
			$value = strtolower($value);
			$array[$key]=$value;
			}
		$lol = array_intersect($array,$config['stb']);
		$eme = implode("<br/>",$lol); 
		$dAmn->say("<b><u>$c:</u></b><br/> $eme",$c);
	break;
case 'score':
	if($args[2] == ""){
			$dAmn->say("<sub> <b>".ucfirst($from)."</b> your score is <b>".$config['stbgame'][$from].".</b>",$c);}
	else{
			$num = 5;
			$i=1;
			$rawr = $config['stbgame'];
			arsort($rawr); 
			$te = array_slice($rawr,0,$num,true); 
			foreach($te as $playerr => $pointss) { 
					$say.= ("(".$i.")<b> :dev".ucfirst($playerr).": = </b>  ".round($pointss).";<br/>");
					$i++;
				}
			$dAmn->say("<b><u>Top $num</u></b><br/><sub>".$say."",$c); 
			}
	break;
case 'kick':
	$args[2] = strtolower($args[2]);
		if(strtolower($from) != "".strtolower($config['bot']['owner']).""){
				$dAmn->say("$from: Sorry only the bot's owner can kick.",$c);
		}else{
			if($args[2] == ""){
					$dAmn->say("$from You must give the name of the person you wish to kick.",$c);
			}else{
				if(!in_array($args[2],$config['stb'])){
						$dAmn->say("This person is not currently playing $from.:thumb53878678:",$c);
				}else{
						$dAmn->say("$args[2] was kicked from the game {$from}.:thumb53878678:",$c);
						unset($config['stb'][$args[2]]);
						save_config('stb');
					}
			}
	}
	break;	
case 'reset':
		$from = strtolower($from);
		$args[2] = strtolower($args[2]);
		if(strtolower($from) != "".strtolower($config['bot']['owner']).""){
				$dAmn->say("$from: Sorry only the bot's owner can reset the scores.",$c);
		}else{
				if($args[2] == ""){
						$dAmn->say("$from: [user/all] ",$c);
				}
				elseif($args[2] == "all"){
						$dir = getcwd()."\config\stbgame.vudf";
						foreach($config['stbgame'] as $pls => $feetlolwtf){  
								$config['stbgame'][$pls]=(0); } 
						save_config('stbgame');
						$dAmn->say("$from: All scores have been set to 0.",$c);}
				else{
						$dAmn->say("$from: $args[2]'s score was set to 0.",$c);
						unset($config['stbgame'][$args[2]]);
						save_config('stbgame');
					}
			}
	break;
case 'spinner':
		if(strtolower($from) != "".strtolower($config['bot']['owner']).""){
				$dAmn->say("$from: Sorry you're not allowed to use this function.",$c);
		}else{
				if($args[2] == ""){
						$dAmn->say("This can't be left empty, $from.",$c);
						}
				elseif(strtolower($args[2]) == "list"){
						$infospin = $config['spinners'];
						$spinner = implode($infospin,"<br/>");
						$dAmn->say("People allowed to spin:<br/>".$spinner,$c);
						}
				elseif(strtolower($args[2]) == "add"){
						if($args[3] == ""){
								$dAmn->say("$from: Name the person",$c);
						}else{
								$config['spinners'][strtolower($args[3])]=(strtolower($args[3]));
								save_config('spinners');
								$dAmn->say("$from: $args[3] was added to the people allowed to spin",$c);}
									}
				elseif(strtolower($args[2]) == "del"){
						if($args[3] == ""){
								$dAmn->say("$from: Name the person",$c);
						}else{
								unset($config['spinners'][strtolower($args[3])]);
								save_config('spinners');
								$dAmn->say("$from: User deleted",$c);
							}
					}
			}
	break;
case 'ban':
		$args[2] = strtolower($args[2]);
		if(strtolower($from) != "".strtolower($config['bot']['owner']).""){
				$dAmn->say("$from: Sorry only the bot's owner can ban.",$c);
		}else{
				if($args[2] ==""){
						$dAmn->say($from." Name of the user you wish to ban.",$c);
				}else{
						$config['stban'][$args[2]] = ($args[2]);
						save_config('stban');
						$dAmn->say($from.": ".$args[2]." is now banned from the game.",$c);
					}
		}
	break; 
case 'unban':
		$args[2] = strtolower($args[2]);
		if(strtolower($from) != "".strtolower($config['bot']['owner']).""){
				$dAmn->say("$from: Sorry only the bot's owner can unban.",$c);
		}else{
				if($args[2] ==""){
						$dAmn->say($from." Name of the user you wish to unban.",$c);
				}else{
						unset($config['stban'][$args[2]]);
						save_config('stban');
						$dAmn->say($from.": ".$args[2]." is now able to play again.",$c);
					}
		}
	break; 
}
?>