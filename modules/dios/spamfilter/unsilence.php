<?php
global $dAmn, $config;
if($config['spam_control']['chans']){
  foreach($config['spam_control']['chans'] as $chan => $stuff){
	  if($config['spam_control']['chans'][$chan]['silenced'][$data['f']]){
		  $c=$chan;
		  $dAmn->promote($data['f'],$config['spam_control']['chans'][$c]['silenced'][$data['f']]['priv'],$c);
		  unset($config['spam_control']['chans'][$c]['silenced'][$data['f']]);
		  save_config('spam_control');		
	  }
  }
}
?>