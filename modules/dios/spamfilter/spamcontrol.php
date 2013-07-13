<?php
global $GET_SPAM_MSGS, $Timer, $SP_SILENCED;
if($config['spam_control']['chans'][$c]['on']){
	$GET_SPAM_MSGS[$c][$from]['m'][]=$message;
	if(!$GET_SPAM_MSGS[$c][$from]['t']){
		$GET_SPAM_MSGS[$c][$from]['t']=time()+microtime();
		$GET_SPAM_MSGS[$c][$from]['s']=4+time()+microtime();
		$GET_SPAM_MSGS[$c][$from]['clear']=30+time()+microtime();
	}
	foreach($GET_SPAM_MSGS[$c][$from]['m'] as $key => $msg){
		$MSGS[$from][$msg]++;
	}	
	foreach($MSGS[$from] as $msg => $amt){
		// Reptitive message spam!
		if($amt>$config['spam_control']['chans'][$c]['MSG_REP']){
			$reason="Repetitive Spamming";
			$ACTIVATE_SPAM_CONTROL=true;
			break;
		} else {
			$words=explode(" ",$msg);
			// Message Quantity Spam
			if(count($words)>$config['spam_control']['chans'][$c]['MSG_QUANT']){
				$reason="Huge Message Spamming";
			 	$ACTIVATE_SPAM_CONTROL=true;
			 	break;
		    }		
		    $kill_other=false;
			foreach($words as $key => $word){
				// Word Length Spam!
				if(strlen($word)>$config['spam_control']['chans'][$c]['WORD_LENGTH']){
					$reason="Stretching Words Spam";
					$kill_other=true;
					$ACTIVATE_SPAM_CONTROL=true;
			 	    break;
		 	    }
	 	    }
	 	    if($kill_other==true){
		 	    $kill_other=false;
		 	    break;
			}	
		}		
	}
	if(count($GET_SPAM_MSGS[$c][$from]['m'])>=5 && time()+microtime()<=$GET_SPAM_MSGS[$c][$from]['s']){
		$ACTIVATE_SPAM_CONTROL=true;			
	}	
	if($ACTIVATE_SPAM_CONTROL==true){
			if(!$config['spam_control']['chans'][$c]['kicked'][$from]){
				$config['spam_control']['chans'][$c]['kicked'][$from]=1;
			} else {
				$config['spam_control']['chans'][$c]['kicked'][$from]++;
			}
			if($config['spam_control']['chans'][$c]['kicked'][$from]<=3){
				save_config('spam_control');
				unset($GET_SPAM_MSGS[$c][$from]);
				// Kick!
				$dAmn->kick($from,$c," Reason: {$reason} | Warning {$config['spam_control']['chans'][$c]['kicked'][$from]} | (autokicked)");				
			} else
			if($config['spam_control']['chans'][$c]['kicked'][$from]>$config['spam_control']['KICKED']){
				// Silence!
				if($config['spam_control']['chans'][$c]['silence']){
					unset($config['spam_control']['chans'][$c]['kicked'][$from]);
					unset($GET_SPAM_MSGS[$c][$from]);					
					$config['spam_control']['chans'][$c]['silenced'][$from]['priv']=$dAmn->room[$cc]->getUserData($from,"pc");
					$privclass=$config['spam_control']['chans'][$c]['silence'];
					$dAmn->demote($from,$privclass,$c);
					$config['spam_control']['chans'][$c]['silenced'][$from]['t']=$config['spam_control']['chans'][$c]['SILENCETIME']+time()+microtime();
					save_config('spam_control');
					$um['f']=$from;
					$time=$config['spam_control']['chans'][$c]['SILENCETIME'];
					$Timer->register($time,$um,"modules/dios/spamfilter/unsilence.php",null);
					$dAmn->say($from.": You have been Silenced for spamming, you will be repromoted in {$config['spam_control']['chans'][$c]['SILENCETIME']} seconds.",$c);										
				} else {	
				// Ban!
				   unset($config['spam_control']['chans'][$c]['kicked'][$from]);
				   unset($GET_SPAM_MSGS[$c][$from]);
				   $dAmn->say($from.": You will now be banned and repromoted {$config['spam_control']['chans'][$c]['BANTIME']} seconds.",$c);
				   $config['spam_control']['chans'][$c]['banned'][$from]['priv']=$dAmn->room[$cc]->getUserData($from,"pc");
				   $config['spam_control']['chans'][$c]['banned'][$from]['t']=$config['spam_control']['chans'][$c]['BANTIME']+time()+microtime();
				   $dAmn->ban($from,$c);				   
				   $um['f']=$from;
				   $time=$config['spam_control']['chans'][$c]['BANTIME'];
				   $Timer->register($time,$um,"modules/dios/spamfilter/unban.php",null);
			   }				
			}
			$ACTIVATE_SPAM_CONTROL=false;
		} 	
	if(time()+microtime()>=$GET_SPAM_MSGS[$c][$from]['clear']) {
		unset($GET_SPAM_MSGS[$c][$from]);
	}		
}
?>