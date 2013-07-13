<?php
if(substr($args[1],0,1)=='#' && is_numeric(str_replace('#','',$args[1]))){
	$lim=str_replace('#','',$args[1]);
	if($lim >3){
		$lim=3;
	}
	$quer2=$argsE[2];
}else{
	$lim = 3;
	$quer2=$argsE[1];
}
if(str_word_count($quer) > 1){
	$lol = explode(" ",$quer2);
	$quer = implode("_",$lol);
}else{
	$quer = $quer2;
}
if($quer){
	$url="http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q={$quer}";
	$raw=file_get_contents($url);
	$json = json_decode($raw);
	if($json->responseStatus==200){
		$i=0;
		$say="<ul>";
		do{
			if($json->responseData->results[$i]->titleNoFormatting){
				$say.="<li><b><a href=\"".$json->responseData->results[$i]->url."\">".$json->responseData->results[$i]->titleNoFormatting."</a></b><br/><sub>".$json->responseData->results[$i]->content."<br/>".$json->responseData->results[$i]->visibleUrl." | <a href=\"".$json->responseData->results[$i]->cacheUrl."\">[Cache]</a></sub></li>";
				$i++;
			}
		}while($i!=$lim);
	}else{
		$say="fail: ".$json->responseDetails;
	}
}else{
	$say="NO ARGS";
}
$dAmn->say($say,$c);
?>