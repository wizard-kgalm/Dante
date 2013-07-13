<?php
if($args[1]{0} == "#" || substr($args[1], 0, 5) == "chat:" || $args[1]{0} == "@" || substr($args[1], 0, 6) == "pchat:"){
		$room = generateChatName($args[1]);
		$textToSay = $argsE[2];
		if(debug(false,5)){
			echo "-> TEXTA: "; var_dump($textToSayA);
			echo "-> ROOM: " . $room . "\n-> TEXT: " . $textToSay . "\n";
		}
		$dAmn->say(strrev($textToSay), $room);
	} else {
		$dAmn->say(strrev($argsF), $c);
	}
?>