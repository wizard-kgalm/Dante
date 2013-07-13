<?php
if($argsF=="") {
    return $dAmn->say($f." {$tr}urbandict [#] [word]",$c);
}
if(empty($args[2])){ 
	$num = 1; 
	$term=$argsE[1];
} else 
if(is_numeric($args[1])){	
	$term = $argsE[2];
	$num = $args[1];
} else {
	$num = 1; 
	$term=$argsE[1];
}
if($num > 10){ 
	$num = 10; 
}
$page=@file_get_contents("http://www.urbandictionary.com/define.php?term=".urlencode($term));
if(preg_match("/<i>$term<\/i> isn't defined/Ums",$page,$matches)){  
     $result="<i><b>{$term}</b></i> isn't defined <a href=\"http://www.urbandictionary.com/insert.php?word=".urlencode($term)."\" title=\"Click to define it!\"><b>yet</b></a>";
     $dAmn->say($f." {$result}.", $c);
} else {   
	preg_match_all("/<p[^>]*>(.*)<\/p>/Ums",$page,$matches);
	$result=$matches[0];
	$found=count($result)/2;	
	if($found<$num){
		$num=$found;
	}	
    if($found>=$num){
	    $total=$num; $key=0; $n=0;
	    foreach($result as $k => $val){
		    if($key==$k && $num>0){
			    $n++;
			    $val=strip_tags($val);
			    $val=str_replace("\n","|",$val);
			    $val=explode("|",$val);
			    $done=count($val);
			    $done+=1;
			    for($i=0;$i<$done;$i+=2){
				    $stuff.=$val[$i].="\n";
			    }
			    $val=rtrim($stuff,"\n"); unset($stuff);					    
		        $e=$key+1;
		        if(preg_match("/<p style/",$result[$e],$matches)){
			        $eg=strip_tags($result[$e]);
			        $eg=str_replace("\n","|",$eg);
			        $eg=explode("|",$eg);
			        $done=count($eg);
			        $done+=1;			        
			        for($i=0;$i<$done;$i+=2){
				        $stuff.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$eg[$i].="\n";
			        }
			        $eg=rtrim($stuff,"\n"); unset($stuff);		        
		            $key+=2;
		            if($total==1){
			            $dis.="-<b>({$n})</b>-<br>".$val."<br><br><sub><i>".$eg."</i></sub><br><br>";
			        } else {
			            $dis.="<sub>-<b>({$n})</b>-<br>".$val."<br><br><i>".$eg."</i></sub><br><br>";
		            }
	            } else {
		            $key+=1;
		            if($total==1){
			            $dis.="-<b>({$n})</b>-<br>".$val."</sub><br>";
			        } else {
			            $dis.="<sub>-<b>({$n})</b>-<br>".$val."</sub><br>";
		            }		            
	            }
		        $num--;		            	        
	       }	       
        }
    }
    $top="<abbr title='{$f}'></abbr>:magnify: ({$n}) <a href=\"http://www.urbandictionary.com/\"><b>UrbanDictionary</b></a> Definition(s) for <a href=\"http://www.urbandictionary.com/define.php?term=".urlencode($term)."\"><b>\"{$term}\"</b></a><br>";
    $top.=$dis;
    $dAmn->say($top,$c);
} 
?>