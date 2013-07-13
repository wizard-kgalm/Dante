<?php
 if($config['spam_control']['chans'][$c]){
	 if($config['spam_control']['chans'][$c]['banned']){
		 foreach($config['spam_control']['chans'][$c]['banned'] as $person => $stuff){
			 if(time()+microtime()>=$config['spam_control']['chans'][$c]['banned'][$person]['t']){
				 $dAmn->promote($person,$config['spam_control']['chans'][$c]['banned'][$person]['priv'],$c);
			     unset($config['spam_control']['chans'][$c]['banned'][$person]);
			     save_config('spam_control');
		     }
	     }
     }
     if($config['spam_control']['chans'][$c]['silenced']){
	     foreach($config['spam_control']['chans'][$c]['silenced'] as $perso => $stuf){
		     if(time()+microtime()>=$config['spam_control']['chans'][$c]['silenced'][$perso]['t']){
			     $dAmn->promote($perso,$config['spam_control']['chans'][$c]['silenced'][$perso]['priv'],$c);
			     unset($config['spam_control']['chans'][$c]['silenced'][$perso]);
			     save_config('spam_control');
		     }
	     }
     }
 }	 		 
?>