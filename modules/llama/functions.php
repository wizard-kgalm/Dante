<?php
if(!function_exists("llama")){
	function llama($person, $cookie_jar, $password, $username){
		global $config, $dAmn;
		//Right here, if we were provided a username to send a llama to, we'll be going to their page.
		if(isset($person)){
			$devpage = @file_get_contents("http://".strtolower($person).".deviantart.com/");	
		}else{
			//No person was specified, so we'll just go to a random deviant's page.
			
			$devpage = @file_get_contents('http://www.deviantart.com/random/deviant');
			
			//Just gonna look on their page for their username so we can put it on a list of people we've sent llamas to.
			preg_match("/gmi-name=\"(.*)\"/Ums", $devpage, $matches);
			$person = $matches[1];
		}
		//Let's just mark the date and time we sent this person a llama down on a list. We're gonna keep track of that by username.
		$config['llama'][strtolower($username)][strtolower($person)] = date('d-m-y', time());
		//So.. $username is the account being used for the llama sending, $person is the person we're sending the llama to.
		save_config('llama');
		//Now then, hunting their page for their user id.
		preg_match("/gmi-gruser_id=\"(.*)\"/Ums", $devpage, $matches);
		$devpage2 = urlencode("http://{$person}.deviantart.com/");
		$dev = $matches[1];
		
		//It's time to get the required ID for the llama transaction..
		$llamapage = $dAmn->send_headers(
			fsockopen("tcp://www.deviantart.com", 80),
			"www.deviantart.com",
			"/modal/badge/give?badgetype=llama&to_user={$dev}&trade_id=0&referrer={$devpage2}",
			"http://www.deviantart.com",
			null,
			$cookie_jar
		);
		//$dAmn->say("https://www.deviantart.com/modal/badge/give?badgetype=llama&to_user={$dev}&trade_id=0&referrer={$devpage2}","#damnepic");
		//If we're successful in sending that, we'll be grabbing the token for the transaction.
		preg_match("/name=\"token\" value=\"(.*)\"/Ums", $llamapage, $matches2);
		preg_match("/name=\"validate_token\" value=\"(.*)\"/Ums", $llamapage, $matches3);
		preg_match("/name=\"validate_key\" value=\"(.*)\"/Ums", $llamapage, $matches4);
		$token = $matches2[1];
		$vToken = $matches3[1];
		$vKey = $matches4[1];
		//Let's set up our header for actually sending the badge..
		$toSend = '&quantity=1&userpass='.$password.'&tos=1&_toggle_tos=0&password_remembered=1&_toggle_password_remembered=0&badgetype=llama&to_user='.$dev.'&token='.$token."&validate_token=".$vToken."&validate_key=".$vKey;
		//$dAmn->say($toSend,"#damnepic");
		$e = $dAmn->send_headers(
			fsockopen("tcp://www.deviantart.com", 80),
			"www.deviantart.com",
			"/modal/badge/process_trade?",
			"http://www.deviantart.com",
			$toSend,
			$cookie_jar
		);
		//$dAmn->say($e,"#damnepic");
		
			//Voila! If all the login info was correct, check your llama list for the most recent person and go to their page to see that you sent the llama. Have fun with that.
	}
}