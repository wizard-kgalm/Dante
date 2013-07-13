 <?php
$reggie = array("/[<]+img[\s]+src=\"([^\"]*)\"[\s]+width=\"(\d+)\"[\s]+height=\"(\d+)\"[\s]+alt=\"/",
"/\"[\s]+title=\"([^\"]*)\"[>]+/",
"/[<]+img[\s]+src=\"([^\"]*)\"[\s]+alt=\"/",
"/\"[\s]+title=\"([^\"]*)\"width=\"(\d+)\"[\s]+height=\"(\d+)\"[\s]+[>]+/",
"/\"[\s]+title=\"([^\"]*)\"[\s]+.[>]+/",
"/<a\shref=\"([^\"]*)\"\stitle=\"\">/",
"/[<]+a[\s]+target=\"(.*?)\"[\s]+href=\"(.*?)\"[\s]+title=\"(.*?)\"[>]+/",
"/[<]+img[\s]+class=\"avatar\"[\s]+src=\"([^\"]*)\"[\s]+alt=\"/",
"/\"[\s]+title=\"([^\"]*)\".[>]+/",
'/\<img(^alt=*)alt=":icon([^:]*):"([^>]*)\>/i',
"<a href=\"([^\"]*)\">",);
$owned = array();
switch ($args[1]) {
	case '':
		$a = file_get_contents("http://chat.deviantart.com/damnit/?section=randompopular"); $b = preg_match_all("/<div class=\"text\">(.*)<\/div>/Us",$a,$matches);
		$saylol = str_replace("<div class=\"text\">"," ",$matches[0][1]);
		$es = $saylol;
		$som = explode(" ",$es);
		$som = implode(" ",$som);
		$som = str_replace("&lt<img src=\"http://e.deviantart.net/emoticons/w/winkrazz.gif\" width=\"15\" height=\"15\" alt=\";P\" title=\"Wink/Razz\" />", "<P", trim($som));
		$som = preg_replace($reggie, $owned, $som);;
		$som = preg_replace($reggie, $owned, $som);
		$som = str_replace("</div>", "", $som);
		$som = str_replace("</a>", "", $som);
		$dAmn->say("<b>dAmn it funny random quote:</b><br>".$som,$c);
		break;
	default:
		if(is_numeric($args[1])) {
$a = file_get_contents("http://chat.deviantart.com/damnit/quote/".$args[1]."/"); $b = preg_match_all("/<div class=\"text\">(.*)<\/div>/Us",$a,$matches);
			$saylol = str_replace("<div class=\"text\">"," ",$matches[0][0]);
			$es = $saylol;
			$som = explode(" ",$es);
      $som = implode(" ",$som);
      $som = str_replace("&lt<img src=\"http://e.deviantart.net/emoticons/w/winkrazz.gif\" width=\"15\" height=\"15\" alt=\";P\" title=\"Wink/Razz\" />", "<P", trim($som));
      $som = preg_replace($reggie, $owned, $som);
      $som = preg_replace($reggie, $owned, $som);
      $som = str_replace("</div>", "", $som);
      $som = str_replace("</a>", "", $som);
      $som = str_replace("<>", "", $som);			$dAmn->say("<b><a href='http://chat.deviantart.com/damnit/quote/{$args[1]}/'>dAmn it quote #".$args[1]."</a>:</b><br>".$som."",$c);
			} else {
			$a = file_get_contents("http://chat.deviantart.com/damnit/?section=randompopular"); $b = preg_match_all("/<div class=\"text\">(.*)<\/div>/Us",$a,$matches);
			$saylol = str_replace("<div class=\"text\">"," ",$matches[0][0]);
			$es = $saylol;
			$som = explode(" ",$es);
			$som = implode(" ",$som);
			$som = str_replace("&lt<img src=\"http://e.deviantart.net/emoticons/w/winkrazz.gif\" width=\"15\" height=\"15\" alt=\";P\" title=\"Wink/Razz\" />", "<P", trim($som));
			$som = preg_replace($reggie, $owned, $som);;
			$som = preg_replace($reggie, $owned, $som);
			$som = str_replace("</div>", "", $som);
			$som = str_replace("</a>", "", $som);
			$som = str_replace("<>", "", $som);
			$dAmn->say("<b>dAmn it funny random quote:</b><br>".$som,$c);
    }
break;
}
?>