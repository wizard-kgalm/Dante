<?php
global $dAmn, $feature, $Timer, $config;   
     if($config['feature']['channels']){  
	       foreach($config['feature']['channels'] as $chan => $boo){
		     if($boo){
			   $con['yes']=true;
		       $ran=rand(1,count($config['feature']['devs']));
               $getit=1;
		       foreach($config['feature']['devs'] as $tid => $f){
			       if($getit==$ran){
				       $author=$config['feature']['devs'][$tid]['author'];
	                   $title=$config['feature']['devs'][$tid]['title'];
	                   $by=$config['feature']['devs'][$tid]['by'];
	                   $txt=str_replace("%id", $tid, $config['feature']['format']);
	                   $txt=str_replace("%a", $author, $txt);
	                   $txt=str_replace("%t", $title, $txt);
                       $txt=str_replace("%b", $by, $txt);
	                   $dAmn->say($txt, $chan); 
                   }
                   $getit++;
               }
            } 
           }           
           if($con['yes']){
	           $Timer->register($feature,$data,"modules/dios/timer.php",null);
           }
       }
?>