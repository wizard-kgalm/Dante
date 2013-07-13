<?php
switch ($args[0]){
	case "8ball":
		if ( $argsF == '' ) { 
			$dAmn->say( "$f Please ask a question.", $c ); 
			return; 
		}
		if(empty($config['8ball'])){
			$config['8ball']['responses'] = array(
				'Affirmative.',
				'Negatory.',
				'Outlook not so good.',
				'Signs point to Yes.',
				'Yes.',
				'You may rely on it.',
				'Ask again later.',
				'Concentrate and ask again.',
				'Outlook good.',
				'My sources say so.',
				'Better not tell you now.',
				'Without a doubt.',
				'It is decidedly so.',
				'My reply is no.',
				'As I see it, Yes.',
				'It is certain.',
				// Real 8 ball responses end here, the rest is random crap
				'Yes, definetely.',
				"Don't count on it.",
				'Most likely.',
				'My plushie says yes!',
				'My plushie says no!',
				'Why should I tell you?',
				'Are you serious, man? That question is SICK!',
				'I laugh at your incompetence.',
				'Infidel! You know nothing! Oh, and your answer is "Hell yes that\'s one fine yak."',
				'HELL YES!',
				'HELL NO!',
				'BLASPHEMY!!!',
				'NO TOUCHY :paranoid:',
				'I need to discuss this with my plushie. It could be a few minutes.',
				'After discussing this with your therapist I have decided this is not a wise thing to do.',
				'<b>Parse error</b>: parse error, unexpected \'}\', expecting \',\' or \';\' in <b>C:\wamp\www\index.php</b> on line <b>6</b>',
				'You\'ve been playing too much Final Fantasy kupo.',
				"Keep your paws off my cookies. :evileye:",	
				'Yes that will happen, right when you have that hot date with Michael Jackson on the Moon!',
				"My philosophy teacher says that your logic is valid but one of your prepositions is wrong.",
				'Yes, then you will fly to Uranus and eat chili with Elton John.',
				'How appropriate, you fight like a pregnant yak.',
				'No, you can\'t have my plushie.',
				'Oh look a cloud.... what was it you said?',
				'x^3 = 8 therefore no',
				'x^3 = 8 therefore x = 2',
				'Sorry, I only answer with boolean values.',
				"Nu--uh!! Ain't tellin yaa!",
				'Your question will be answered on CSI tonight.',
				'You should have voted for the other guy.',
				'Your fly is open :rofl:',
				"You will be attacked by furries in fursuits tomorrow.",
				'You have an exam tomorrow.',
				'Just in case you were curious, the world is being corrupted by flying monkeys and NO, you will not get laid tonight.',
				'My answer has to be no, but would you say no if I said your clothes would look great on my floor?',
				'Stop abusing me! :cries:',
				':evileye: Don\'t diss Star Trek.',
				'Your future would have been sucessful had you gotten a different response.',
				':nod: Popcorn is tasty.',
				'Mmmmmm.... :donut:. Oh. Your question. Yes.',
				'Shut up and eat the bug.',
				'I forsee that this will happen on the 30th of February.',
				'The police are here to put you up. :paranoid: I woudn\'t know who called them.',
				'If it is sooooo important why are you asking a chatroom 8ball? Answer that and get back to me.',
			);
			save_config('8ball');
		}
		$responses = $config['8ball']['responses'];
		$response = $responses[array_rand( $responses )];
		$dAmn->say( "$f You said '<i>$argsF?</i>' I say '<b>$response</b>'", $c);
		break;
	case "8ans":
		switch($args[1]){
			case "add":
				if(!empty($args[2])){
					$config['8ball']['responses'][] = $argsE[2];
					save_config('8ball');
					$dAmn->say("$from: Response added!",$c);
				}else
					$dAmn->say("$from: Usage: ".$tr."8ans add <i>response</i>.",$c);
				break;
			case "del":
			case "delete":
				if(!empty($args[2])){
					if(is_numeric($args[2])){
						$fhund = FALSE;
						foreach($config['8ball']['responses'] as $num => $lol){
							if($num == $args[2]){
								$fhund = TRUE;
								$para = $lol;
								$config['8ball']['responses'][$num] = NULL;
								unset($config['8ball']['responses'][$num]);
								save_config('8ball');
							}
						}
						if($fhund){
							$dAmn->say("$from: Response deleted!",$c);
						}else
							$dAmn->say("$from: $args[2] was not found. See ".$tr."8ans list for a list of 8ball responses.",$c);
					}else
						$dAmn->say("$from: You must provide the response number to delete. For a list of 8ball responses and their number, type ".$tr."8ans list.",$c);
				}else
					$dAmn->say("$from: Usage: ".$tr."8ans del <i>response#</i> where number is the numeric ID of the answer you are deleting. For a list of these, type ".$tr."8ans list.",$c);
				break;
			case "list":
				$say = "";
				if(empty($config['8ball']['responses'])){
					$config['8ball']['responses'] = array(
						'Affirmative.',
						'Negatory.',
						'Outlook not so good.',
						'Signs point to Yes.',
						'Yes.',
						'You may rely on it.',
						'Ask again later.',
						'Concentrate and ask again.',
						'Outlook good.',
						'My sources say so.',
						'Better not tell you now.',
						'Without a doubt.',
						'It is decidedly so.',
						'My reply is no.',
						'As I see it, Yes.',
						'It is certain.',
						// Real 8 ball responses end here, the rest is random crap
						'Yes, definetely.',
						"Don't count on it.",
						'Most likely.',
						'My plushie says yes!',
						'My plushie says no!',
						'Why should I tell you?',
						'Are you serious, man? That question is SICK!',
						'I laugh at your incompetence.',
						'Infidel! You know nothing! Oh, and your answer is "Hell yes that\'s one fine yak."',
						'HELL YES!',
						'HELL NO!',
						'BLASPHEMY!!!',
						'NO TOUCHY :paranoid:',
						'I need to discuss this with my plushie. It could be a few minutes.',
						'After discussing this with your therapist I have decided this is not a wise thing to do.',
						'<b>Parse error</b>: parse error, unexpected \'}\', expecting \',\' or \';\' in <b>C:\wamp\www\index.php</b> on line <b>6</b>',
						'You\'ve been playing too much Final Fantasy kupo.',
						"Keep your paws off my cookies. :evileye:",	
						'Yes that will happen, right when you have that hot date with Michael Jackson on the Moon!',
						"My philosophy teacher says that your logic is valid but one of your prepositions is wrong.",
						'Yes, then you will fly to Uranus and eat chili with Elton John.',
						'How appropriate, you fight like a pregnant yak.',
						'No, you can\'t have my plushie.',
						'Oh look a cloud.... what was it you said?',
						'x^3 = 8 therefore no',
						'x^3 = 8 therefore x = 2',
						'Sorry, I only answer with boolean values.',
						"Nu--uh!! Ain't tellin yaa!",
						'Your question will be answered on CSI tonight.',
						'You should have voted for the other guy.',
						'Your fly is open :rofl:',
						"You will be attacked by furries in fursuits tomorrow.",
						'You have an exam tomorrow.',
						'Just in case you were curious, the world is being corrupted by flying monkeys and NO, you will not get laid tonight.',
						'My answer has to be no, but would you say no if I said your clothes would look great on my floor?',
						'Stop abusing me! :cries:',
						':evileye: Don\'t diss Star Trek.',
						'Your future would have been sucessful had you gotten a different response.',
						':nod: Popcorn is tasty.',
						'Mmmmmm.... :donut:. Oh. Your question. Yes.',
						'Shut up and eat the bug.',
						'I forsee that this will happen on the 30th of February.',
						'The police are here to put you up. :paranoid: I woudn\'t know who called them.',
						'If it is sooooo important why are you asking a chatroom 8ball? Answer that and get back to me.',
					);
					save_config('8ball');
				}
				$say .="<sup>These are the 8ball responses stored on the bot: <br></sup>";
				foreach($config['8ball']['responses'] as $num => $letter){
					$say .="ID# $num ==> $letter<br>";
				}
				$dAmn->say($say,$c);
				break;
		}break;
}
?>