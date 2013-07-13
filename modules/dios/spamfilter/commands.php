<?php
if($args[1]{0}=="#"){
	$channel=$args[1];
	$cmd=$args[2];
	$cmd2=$args[3];
	$cmd3=$args[4];
	$cmd4=$args[5];
} else {
	$channel=$c;
	$cmd=$args[1];
	$cmd2=$args[2];
	$cmd3=$args[3];
	$cmd4=$args[4];
}
switch($cmd){
	case "on":
	  if($config['spam_control']['chans'][$channel]['on']){
		  $dAmn->say($f." SpamFilter is already on in {$channel}.",$c);
	  } else {
		  $config['spam_control']['chans'][$channel]['on']=true;
		  if(!$config['spam_control']['chans'][$channel]['SILENCETIME']){
			  $config['spam_control']['chans'][$channel]['SILENCETIME']=900;
			  $config['spam_control']['chans'][$channel]['BANTIME']=86400;
			  $config['spam_control']['chans'][$channel]['MSG_REP']=3;
			  $config['spam_control']['chans'][$channel]['MSG_QUANT']=400;
			  $config['spam_control']['chans'][$channel]['WORD_LENGTH']=150;
		  }
		  save_config('spam_control');
		  $dAmn->say($f." SpamFilter is now ON in <b>{$channel}</b>.",$c);
	  }	
	break;	  		  
	case "off":
	  if(!$config['spam_control']['chans'][$channel]['on']){
		  $dAmn->say($f." SpamFilter is already off in {$channel}.",$c);
	  } else {
		  $config['spam_control']['chans'][$channel]['on']=false;
		  save_config('spam_control');
		  $dAmn->say($f." SpamFilter is now OFF in <b>{$channel}</b>.",$c);
	  }	  
	break; 	
	case "chan":
	   switch($cmd2){
		   case 'add':
		      if(!$config['spam_control']['chans'][$cmd3]){
			      $config['spam_control']['chans'][$cmd3]['on']=true;
			      $config['spam_control']['chans'][$cmd3]['silence']="";
			      $config['spam_control']['chans'][$cmd3]['SILENCETIME']=900;
			      $config['spam_control']['chans'][$cmd3]['BANTIME']=86400;
			      $config['spam_control']['chans'][$cmd3]['MSG_REP']=3;
			      $config['spam_control']['chans'][$cmd3]['MSG_QUANT']=400;
			      $config['spam_control']['chans'][$cmd3]['WORD_LENGTH']=150;
			      save_config('spam_control');
			      $dAmn->say($f." <b>{$cmd3}</b> succesfully added to the SpamFilter channels!",$c);
		      } else {
			      $dAmn->say($f." {$cmd3} is already a SpamFilter channel.",$c);
		      }
		   break;			      
		   case 'del':
		      if($config['spam_control']['chans'][$cmd3]){
			      unset($config['spam_control']['chans'][$cmd3]);
			      save_config('spam_control');
			      $dAmn->say($f." <b>{$cmd3}</b> succesfully removed to the SpamFilter channels!",$c);
		      } else {
			      $dAmn->say($f." {$cmd3} is not a SpamFilter channel.",$c);
		      }
		   break;
		   case 'list':
		      if($cmd3){
			      if($config['spam_control']['chans'][$cmd3]){
				      switch($cmd4){
					      case 'kicked':
					      case 'banned':
					      case 'silenced':
					        if($cmd4=="kicked"){
						        if($config['spam_control']['chans'][$cmd3]['kicked']){
							        $n=count($config['spam_control']['chans'][$cmd3]['kicked']);
							        foreach($config['spam_control']['chans'][$cmd3]['kicked'] as $p => $data){
								        $ppl.=":dev".$p.": ,";
							        }
							        $say="<abbr title='{$f}'></abbr><b>{$cmd3} Kicked Persons ({$n})</b><br><sub>".
							        "{$ppl}";
							        $dAmn->say($say,$c);
						        } else {
							        $dAmn->say($f." No persons kicked in {$cmd3}.",$c);
						        }
					        } else 
					        if($cmd4=="silenced"){
						        if($config['spam_control']['chans'][$cmd3]['silenced']){
							        $n=count($config['spam_control']['chans'][$cmd3]['silenced']);
							        foreach($config['spam_control']['chans'][$cmd3]['silenced'] as $p => $data){
								        $ppl.=":dev".$p.": ,";
							        }
							        $say="<abbr title='{$f}'></abbr><b>{$cmd3} Silenced Persons ({$n})</b><br><sub>".
							        "{$ppl}";
							        $dAmn->say($say,$c);
						        } else {
							        $dAmn->say($f." No persons silenced in {$cmd3}.",$c);
						        }
					        } else		
					        if($cmd4=="banned"){			         
						        if($config['spam_control']['chans'][$cmd3]['banned']){
							        $n=count($config['spam_control']['chans'][$cmd3]['banned']);
							        foreach($config['spam_control']['chans'][$cmd3]['banned'] as $p => $data){
								        $ppl.=":dev".$p.": ,";
							        }
							        $say="<abbr title='{$f}'></abbr><b>{$cmd3} Silenced Persons ({$n})</b><br><sub>".
							        "{$ppl}";
							        $dAmn->say($say,$c);
						         } else {
							        $dAmn->say($f." No persons banned in {$cmd3}.",$c);
						         }
					         }							        				      
					      break;
					      default:
				            $on=$config['spam_control']['chans'][$cmd3]['on'];
			                $sil=$config['spam_control']['chans'][$cmd3]['silence'];
			                $stime=$config['spam_control']['chans'][$cmd3]['SILENCETIME'];
			                $btime=$config['spam_control']['chans'][$cmd3]['BANTIME'];
			                $msgrep=$config['spam_control']['chans'][$cmd3]['MSG_REP'];
			                $msgq=$config['spam_control']['chans'][$cmd3]['MSG_QUANT'];
			                $wl=$config['spam_control']['chans'][$cmd3]['WORD_LENGTH'];
			                $on=$on?"Turned On":"Turned Off"; 
			                if($sil){ $mode="Kick then Silence ($sil)";} else {$sil="No Silence Privclass Set"; $mode="Kick then Ban";}
			                $say="<abbr title='{$f}'></abbr><b>{$cmd3} SpamFilter Settings</b><br><sub>".
			                "- Status: {$on}<br>".
			                "- Mode: {$mode}<br>".
			                "- <b>Time Controls </b><br>".
			                "---- Ban: {$btime} secs.<br>".
			                "---- Silence: {$stime} secs.<br>".
			                "- <b>Message Limit Controls</b><br>".
			                "---- Repition: {$msgrep} sentences allowed.<br>".
			                "---- Quantity: {$msgq} Words allowed.<br>".
			                "---- Word length: {$wl} characters allowed.";
			                $dAmn->say($say,$c);
		              }
		          } else {
			          $dAmn->say($f." Invalid SpamFilter channel.",$c);
		          }
	          } else     
		      if($config['spam_control']['chans']){
			      $list="<abbr title='{$f}'></abbr><b>SpamFilter Channels</b><br><sub>";
			      foreach($config['spam_control']['chans'] as $chan => $data){
				      if($data['on']){
					      $on=":bulletgreen:";
				      } else {
					      $on=":bulletred:";
				      }
				      $list.="{$on}- {$chan}<br>";
			      }
			      $dAmn->say($list,$c);
		      } else {
			      $dAmn->say($f." No channels added.",$c);
		      }
		   break;
		   default: 
		   	   if($config['spam_control']['chans'][$cmd2]){
			   	   $on=$config['spam_control']['chans'][$cmd2]['on'];
			       $sil=$config['spam_control']['chans'][$cmd2]['silence'];
			       $stime=$config['spam_control']['chans'][$cmd2]['SILENCETIME'];
			       $btime=$config['spam_control']['chans'][$cmd2]['BANTIME'];
			       $msgrep=$config['spam_control']['chans'][$cmd2]['MSG_REP'];
			       $msgq=$config['spam_control']['chans'][$cmd2]['MSG_QUANT'];
			       $wl=$config['spam_control']['chans'][$cmd2]['WORD_LENGTH'];
			       $on=$on?"Turned On":"Turned Off"; 
			       if($sil){ $mode="Kick then Silence ($sil)";} else {$sil="No Silence Privclass Set"; $mode="Kick then Ban";}
			       $say="<abbr title='{$f}'></abbr><b>{$cmd2} SpamFilter Settings</b><br><sub>".
			       "- Status: {$on}<br>".
			       "- Mode: {$mode}<br>".
			       "- <b>Time Controls </b><br>".
			       "---- Ban: {$btime} secs.<br>".
			       "---- Silence: {$stime} secs.<br>".
			       "- <b>Message Limit Controls</b><br>".
			       "---- Repition: {$msgrep} sentences allowed.<br>".
			       "---- Quantity: {$msgq} Words allowed.<br>".
			       "---- Word length: {$wl} characters allowed.";
			       $dAmn->say($say,$c);
		       } else {
			       $dAmn->say($f." {$tr}spamfilter chan [add/del/list] (#room)",$c);
		       }
	   }
	break;	   
	case "silence":
	    if($config['spam_control']['chans'][$channel]){
		    if($cmd2){
			    if(strtolower($cmd2)!="clear"){
				    $config['spam_control']['chans'][$channel]['silence']=$cmd2;
			        save_config('spam_control');
			        $dAmn->say($f." <b>{$channel}</b> Silence privclass set to <b>\"{$cmd2}\"</b>",$c);
		        } else 
		        if(strtolower($cmd2)=="clear"){
			        $config['spam_control']['chans'][$channel]['silence']="";
			        save_config('spam_control');
			        $dAmn->say($f." <b>{$channel}</b> Silence privclass removed.",$c);
		        }
	        } else {
		        $dAmn->say($f." {$tr}spamfilter silence [privclass/clear]",$c);
	        }
        } else {
	        $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
        }
     break;
     case "setban":
        if($config['spam_control']['chans'][$channel]){
	        if(is_numeric($cmd2)){
		        $config['spam_control']['chans'][$channel]['BANTIME']=$cmd2;
		        save_config('spam_control');
		        $dAmn->say($f." BAN time set to <b>{$cmd2}</b> seconds in <b>{$channel}</b>.",$c);
	        } else {
		        $dAmn->say($f." {$tr}spamfilter setban [time] (in seconds)",$c);
	        }
        } else {
	        $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
        }
     break;		        
     case "setsilence":
        if($config['spam_control']['chans'][$channel]){
	        if(is_numeric($cmd2)){
		        $config['spam_control']['chans'][$channel]['SILENCETIME']=$cmd2;
		        save_config('spam_control');
		        $dAmn->say($f." SILENCE time set to <b>{$cmd2}</b> seconds in <b>{$channel}</b>.",$c);
	        } else {
		        $dAmn->say($f." {$tr}spamfilter setsilence [time] (in seconds)",$c);
	        }
        } else {
	        $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
        } 
     break;
     case "setkick":
	    if(is_numeric($cmd2)){
		    $config['spam_control']['KICKED']=$cmd2;
		    save_config('spam_control');
		    $dAmn->say($f." KICKED set to <b>{$cmd2}</b>.",$c);
	    } else {
		    $dAmn->say($f." {$tr}spamfilter setkick [times] (numeric)",$c);
	    }
     break;     
     case "msgrep":
        if($config['spam_control']['chans'][$channel]){
	        if(is_numeric($cmd2)){
		        $config['spam_control']['chans'][$channel]['MSG_REP']=$cmd2;
		        save_config('spam_control');
		        $dAmn->say($f." Message Repetition Set <b>| {$cmd2} times allowed |</b>.",$c);
	        } else {
		        $dAmn->say($f." {$tr}spamfilter msgrep [times allowed]",$c);
	        }
        } else {
	        $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
        }         
     break;
     case "msgquant":
        if($config['spam_control']['chans'][$channel]){
	        if(is_numeric($cmd2)){
		        $config['spam_control']['chans'][$channel]['MSG_QUANT']=$cmd2;
		        save_config('spam_control');
		        $dAmn->say($f." Message Quantity Set <b>| {$cmd2} words allowed |</b>.",$c);
	        } else {
		        $dAmn->say($f." {$tr}spamfilter msgquant [amt allowed]",$c);
	        }
        } else {
	        $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
        }         
     break;
     case "wordlength":
        if($config['spam_control']['chans'][$channel]){
	        if(is_numeric($cmd2)){
		        $config['spam_control']['chans'][$channel]['WORD_LENGTH']=$cmd2;
		        save_config('spam_control');
		        $dAmn->say($f." World Length Set <b>| {$cmd2} characters allowed |</b>.",$c);
	        } else {
		        $dAmn->say($f." {$tr}spamfilter worldlength [amt allowed]",$c);
	        }
        } else {
	        $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
        }         
     break;     
     case 'clear':
        switch($cmd2){
	        case 'kicked':
	        case 'silenced':
	        case 'banned':
	        case 'all':
	        if($cmd2=="kicked" || $cmd2=="all"){
	           if($config['spam_control']['chans'][$channel]){
		           if($config['spam_control']['chans'][$channel]['kicked']){
			           $n=count($config['spam_control']['chans'][$channel]['kicked']);
		               unset($config['spam_control']['chans'][$channel]['kicked']);		           
		               save_config('spam_control');
		               $dAmn->say($f." <b>{$n}</b> Kicked person(s) from <b>{$channel}</b> cleared successfully.",$c);
	               } else {
		               $dAmn->say($f."No kicked users in {$channel}",$c);
	               }
	           } else {
		            $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
	           }
            }
            if($cmd2=="silenced" || $cmd2=="all"){        	        
	           if($config['spam_control']['chans'][$channel]){
		           if($config['spam_control']['chans'][$channel]['silenced']){
			           $n=count($config['spam_control']['chans'][$channel]['silenced']);
		               foreach($config['spam_control']['chans'][$channel]['silenced'] as $pp => $data){
			               $dAmn->promote($pp,$data['priv'],$channel);
		               }
			           unset($config['spam_control']['chans'][$channel]['silenced']);		           
		               save_config('spam_control');
		               $dAmn->say($f." <b>{$n}</b> Silenced person(s) from <b>{$channel}</b> cleared successfully.",$c);
                   } else {
	                   $dAmn->say($f."No Silenced users in {$channel}",$c);
	               }
	           } else {
		            $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
	           } 
            } 
            if($cmd2=="banned" || $cmd2=="all"){  	        
	           if($config['spam_control']['chans'][$channel]){
		           if($config['spam_control']['chans'][$channel]['banned']){
			           $n=count($config['spam_control']['chans'][$channel]['banned']);
		               foreach($config['spam_control']['chans'][$channel]['banned'] as $pp => $data){
			               $dAmn->promote($pp,$data['priv'],$channel);
		               }
			           unset($config['spam_control']['chans'][$channel]['banned']);		           
		               save_config('spam_control');
		               $dAmn->say($f." <b>{$n}</b> Banned person(s) from <b>{$channel}</b> cleared successfully.",$c);
                   } else {
	                   $dAmn->say($f."No Banned users in {$channel}",$c);
	               }
	           } else {
		            $dAmn->say($f." {$channel} is not a SpamFilter channel.",$c);
	           }
            }	
            break;          	           
	        default:
	        if($cmd2){
	          if($config['spam_control']['chans'][$channel]['kicked'][$cmd2]){
		          unset($config['spam_control']['chans'][$channel]['kicked'][$cmd2]);
		          save_config('spam_control');
		          $dAmn->say($f." <b>{$cmd2}</b> removed from  <b>{$channel}</b> Kicked.",$c);
	          }
	          if($config['spam_control']['chans'][$channel]['silenced'][$cmd2]){
		          $dAmn-promote($cmd2,$config['spam_control']['chans'][$channel]['silenced'][$cmd2]['priv'],$channel);
		          unset($config['spam_control']['chans'][$channel]['silenced'][$cmd2]);
		          save_config('spam_control');
		          $dAmn->say($f." <b>{$cmd2}</b> was successfully unsilenced in <b>{$channel}</b>.",$c);
	          }
	          if($config['spam_control']['chans'][$channel]['banned'][$cmd2]){
		          $dAmn-promote($cmd2,$config['spam_control']['chans'][$channel]['banned'][$cmd2]['priv'],$channel);
		          unset($config['spam_control']['chans'][$channel]['banned'][$cmd2]);
		          save_config('spam_control');
		          $dAmn->say($f." <b>{$cmd2}</b> was succesfully unbanned in <b>{$channel}</b>.",$c);
	          }
            } else {
	           $dAmn->say("<abbr title=\"{$f}\"></abbr> {$tr}spamfilter clear [kicked/silenced/banned/all/username]",$c);
            }
        }      
	 break;	        	        
     case 'about':
        $dAmn->say("<abbr title=\"{$f}\"></abbr>  <b>SpamFilter 1.0</b> by :devMagaman: ( Credits :devFreaky-Guy-246: & :devtonbo87: )",$c);
     break;
     case 'cmds':
     case 'commands':
     default:
       $dAmn->say("<abbr title=\"{$f}\"></abbr> {$tr}spamfilter (#room) [on/off|chan/silence/clear|setban/setsilence/setkick|msglength/msgqaunt/wordlength]",$c);
 } 	
?>