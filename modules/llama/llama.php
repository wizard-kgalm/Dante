<?php
include_once("modules/llama/functions.php");
switch($args[0]){
	case "rllama":
	case "rspama":
	case "llama":
		if($args[0] == "llama" && empty($args[1])){
			return $dAmn->say("$from: You must provide a username to send a llama to, or use {$tr}rllama to send it to someone random.",$c);
		}
		//Setting up for a llama sending spree..
		if($args[0] == "rspama"){
			//Default loop is set at 50. We assume if $args[1] isn't a number that it's the account you want to send llamas from.
			if(!is_numeric($args[1])){
				return $dAmn->say("$from: You must specify the number of llamas you want to send." ,$c);
			}
			if(empty($args[1])){
				$loopnum = 50;
			}
			//If a number was supplied AND you supplied a second argument, we'll try to use that for llama sending.
			if(isset($args[2])){
				$loopnum = $args[1];
				$person2 = strtolower($args[2]);
			}elseif(isset($args[1]) && $args[1] <= 77){
				$loopnum = $args[1];
			}else $loopnum = 77;
		}
		//If any $args were provided in the rllama command, we'll be using that as $person2, which will be checked against the stored logins list.
		if($args[0] == "rllama" && isset($args[1])){
			$person2 = strtolower($args[1]);
		}
		if($args[0] == "llama" && isset($args[2])){
			$person2 = strtolower($args[2]);
		}
		$login = parse_ini_file( f( "config.ini" ) ); //This loads the $login
		if(!empty($person2)){ 
		//$person2 is optional, if you provide a username after $rllama or after $llama [username], that username will be checked on the logins list. 
			if(isset($config['logins']['login'][$person2])){
			//Just looking to see if a username was on the list. If so, it'll be using that dA account to send the llama instead of the bot's.
				$username = $person2;
				//Since the passwords are encoded in the file, it'll be decoding those.
				$password = base64_decode($config['logins']['login'][$person2]);
			}else{
				//If the username wasn't a stored login, it'll just use the bot's default login, the one in the config.ini file.
				$username = $login['username'];
				$password = $login['password'];
			}
		}else{
			//No alternate username to send the llama with was provided, we're just gonna use the bot's and hope the info is right.
			$username = $login['username'];
			$password = $login['password'];
		}
		if($args[0] == "llama"){
		//Let's just see if we've sent a llama to the specified user.. If we have, no sense in wasting our time through this process.
			if(isset($config['llama'][strtolower($username)][strtolower($args[1])])){
				return $dAmn->say("$from: $username can't send another llama to $args[1].",$c);
			}
		}
		
			//Method to get the cookie! Yeah! :D
			// Our first job is to open an SSL connection with our host.
			$socket = fsockopen("ssl://www.deviantart.com", 443);
			// If we didn't manage that, we need to exit!
			if($socket === false) {
			return array(
					'status' => 2,
					'error' => 'Could not open an internet connection');
			}
			// Fill up the form payload
			$POST = '&username='.urlencode($username);
			$POST.= '&password='.urlencode($password);
			$POST.= '&remember_me=1';
			// And now we send our header and post data and retrieve the response.
			$response = $dAmn->send_headers(
				$socket,
				"www.deviantart.com",
				"/users/login",
				"http://www.deviantart.com/users/rockedout",
				$POST
			);
		   
			// Now that we have our data, we can close the socket.
			fclose ($socket);
			// And now we do the normal stuff, like checking if the response was empty or not.
			if(empty($response))
			return array(
					'status' => 3,
					'error' => 'No response returned from the server'
			);
			if(stripos($response, 'set-cookie') === false)
			return array(
					'status' => 4,
					'error' => 'No cookie returned'
				);
			// Grab the cookies from the header
			$response=explode("\r\n", $response);
			$cookie_jar = array();
			
			foreach ($response as $line)
				if (strpos($line, "Set-Cookie:")!== false)
					$cookie_jar[] = substr($line, 12, strpos($line, "; ")-12);
				$config['llamasend']['llamauser'][$username] = $cookie_jar;
				save_config('llamasend');
		
			if(strtolower($args[0]) == "rllama"){
				//This is the part where we kick out the info to the llama badge sender. This part is for the random llama so it'll just be sending the cookie and password to use.
				llama(null, $cookie_jar, $password, $username);
				
				$dAmn->say("$from: Random llama sent as $username!",$c);
			
			}elseif( strtolower($args[0]) == "llama"){
				//This one is for the specified user. It'll kick out the username, cookie, and the password for the account used.
				llama($args[1], $cookie_jar, $password, $username);
				
				$dAmn->say("$from: Llama sent to $args[1] as $username!",$c);
			
			}elseif( strtolower($args[0]) == "rspama"){
				//This is where we'll be kicking out the llama loop. 
				for($i = 0; $i <= $loopnum; $i++){
					llama(null, $cookie_jar, $password, $username);
					$dAmn->send( "pong\n" . chr( 0 ) );
				}
				
				$dAmn->say("$from: $loopnum llamas sent as $username!",$c);
			}
		
	break;
	case "fullama":
		if(isset($args[1])){
			foreach($config['logins']['login'] as $person2 => $password){
				if(!isset($config['llamasend']['llamauser'][$person2])){
					parseCommand($config['bot']['trigger']."llama {$args[1]} {$person2}", $config['bot']['owner'], "#rand", "msg");
				}else $cookie_jar = $config['llamasend']['llamauser'][$person2];
					llama($args[1], $cookie_jar, $password, $person2);
					$dAmn->send( "pong\n" . chr( 0 ) );
			}
			
			$dAmn->say("$from: ".count($config['logins']['login'])." llamas sent to $args[1]!",$c);
		}else{
			foreach($config['logins']['login'] as $person2 => $password){
				if(!isset($config['llamasend']['llamauser'][$person2])){
					parseCommand($config['bot']['trigger']."rllama {$person2}", $config['bot']['owner'], "#rand", "msg");
				}else $cookie_jar = $config['llamasend']['llamauser'][$person2];
					llama(null, $cookie_jar, $password, $person2);
					$dAmn->send( "pong\n" . chr( 0 ) );
			}
			
			$dAmn->say("$from: ".count($config['logins']['login'])." random llamas sent!",$c);
		}
	break;
	case "cookieclear":
		unset($config['llamasend']['llamauser']);
		save_config('llamasend');
		$dAmn->say("$from: Llama user cookies cleared.",$c);
	break;
}