<?php
/**
 * parser Class
 *
 *This mostly is code from Kitsune 0.0's core
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@version 0.9
 *@package Dante
 *@license http://www.opensource.org/licenses/gpl-license.php GNU General Public License
 */
/**
 * Contains parsers for various things.
 *
 *This mostly is code from Kitsune 0.0's core
 *@author SubjectX52873M <SubjectX52873M@gmail.com>
 *@copyright SubjectX52873M 2006
 *@version 0.9
 */
class parser{
  ###########################
	#DAMN PARSERS
	##
	/**
	 *Break Packet
	 *
	 *Breaks up a packet from the dAmnserver in an array
	 *@return array See params.txt for details on the returned array
	 */
	function breakPacket( $data, $lumps = TRUE ) {
		global $debug;
		if( $lumps ) $data = $this->parseTabLumps( $data );
		$temp = explode( " ", $data, 2 );
		$params = array(
		0	=>	array(
		0	=>	trim( $temp[0] )
		),
		1	=>	array(),
		2	=>	array(),
		3	=>	array()
		);
		//Single word packets
		if( $temp[0] == "dAmnServer" ) {
			$params[0][1] = $temp[1];
			return $params;
		} elseif( $temp[0] == "ping" ) {
			return $params;
		}
		$temps = explode( "\n", $temp[1] );
		$params[0][1] = $temps[0];
		unset( $temps[0] );
		//[0][1]
		switch( $temp[0] ) {
			case "login":
			case "join":
			case "part":
			case "send":
			case "kick":
			case "get":
			case "set":
			case "disconnect":
				foreach( $temps as $v ) {
					$parts = explode( "=", $v, 2 );
					$params[1][$parts[0]] = $parts[1];
				}
				break;
			case "recv":
				$types = explode( " ", $temps[2] );
				$params[1] = $types;
				switch( $types[0] ) {
					case "msg":
					case "action":
					case "kicked":
						$p = explode( "=", $temps[3] );
						$params[2][$p[0]] = $p[1];
						if($types[0] == "kicked" ) $params[3] = $temps[6];
						else {
							$tempa = explode( "\n\n", $data, 3 );
							$params[3] = $tempa[2];
						}
						break;
					case "join":
					case "part":
						foreach( $temps as $v ) {
							$parts = explode( "=", $v, 2 );
							if( !empty( $parts[1] ) ) $params[2][$parts[0]] = $parts[1];
						}
						break;
					case "privchg":
						$one = explode( "=", $temps[3] );
						$two = explode( "=", $temps[4] );
						$params[2] = array(
							"from"	=>	$types[1],
							$one[0]	=>	$one[1],
							$two[0]	=>	$two[1],
						);
						break;
					case "admin":
						if( $params[1][1] != "show" ) {
							foreach( $temps as $v ) {
								$parts = explode( "=", $v, 2 );
								if( !empty( $parts[1] ) ) $params[2][$parts[0]] = $parts[1];
							}
						} else {
							$p = explode( "=", $temps[3] );
							$params[2][$p[0]] = $p[1];
							$temps = explode( "\n\n", $data );
							$temps = $temps[2];
							$temps = explode( "\n", $temps );
							foreach( $temps as $part ) {
								$d = explode( " ", $part, 2 );
								$params[3][trim( $d[0], ":" )] = $d[1];
							}
						}
						break;
				}
				break;
			case "kicked":
				$temps = explode( "\n\n", $data );
				$parts = explode( "=", $temps[0], 2 );
				$params[1] = $parts;
				$params[2] = $temps[1];
				break;
			case "property":
				$types = explode( "=", $temps[1] );
				$value = explode( "\n\n", $data, 2 );
				if ( strstr( ":", $types[1] ) ) {
					$types[1] = explode( ":", $types[1] );
					$params[1][$types[0]] = $types[1][0];
					$params[1]["u"] = $types[1][1];
					break;
				}
				switch( $types[1] ) {
					case "topic":
					case "title":
						foreach( $temps as $v ) {
							$parts = explode( "=", $v, 2 );
							if( !empty( $parts[1] ) ) $params[1][$parts[0]] = $parts[1];
						}
						$value = explode( "\n\n", $data );
						$params[2] = $value[1];
						break;
					case "privclasses":
						foreach( $temps as $v ) {
							if( strstr( $v, "=" ) ) {
								$parts = explode( "=", $v, 2 );
								$params[1][$parts[0]] = $parts[1];
							} elseif( strstr( $v, ":" ) ) {
								$partsa = explode( ":", $v, 2 );
								if( is_array( $partsa ) ) $params[2][$partsa[0]] = $partsa[1];
							}
						}
						break;
					case "members":
						$members = explode( "\nmember ", $data );
						unset( $members[0] );
						$params[1]["p"] = "members";
						foreach( $members as $v ) {
							$b = explode( "\n", $v );
							foreach( $b as $n ) {
								$m = explode( "=", $n, 2 );
								if( !empty( $m[1] ) ) {
									$params[2][$b[0]][$m[0]] = strval( $m[1] );
								}
							}
						}
						break;
					case "info":
						$temps = explode( "\n\n", $data, 3 );
						//Get user
						$tempa = explode( ":", $temps[0] );
						$tempb = explode( "\n", $tempa[1] );
						$from = $tempb[0];
						$params[1]['p'] = 'info';
						//Process user data
						$tempa = explode( "\n", $temps[1] );
						foreach( $tempa as $v ) {
							$parts = explode( "=", $v, 2 );
							if( !empty( $parts[1] ) ) $params[2][$parts[0]] = $parts[1];
						}
						$params[2]['name'] = $from;
						//Process more
						$temps = explode( "\n\nconn", $data );
						unset( $temps[0] );
						foreach( $temps as $datas ) {
							$tempa = explode( "\n", $datas );
							$tempa[0] = explode( "=", $tempa[1] );
							$tempa[1] = explode( "=", $tempa[2] );
							//Got Connection Info
							$tp = array(
								$tempa[0][0]	=>	$tempa[0][1],
								$tempa[1][0]	=>	$tempa[1][1]
							);
							//Get rooms
							$tempb = explode( "\n\nns ", $datas );
							unset( $tempb[0] );
							foreach( $tempb as $i => $room ) {
								$tp['rooms'][] = trim( rChatName( $room ) );
							}
							$params[3][] = $tp;
						}
						break;
					default:
						$params = array(
							array(	0	=>	"error"	),
							$data
						);
						break;
				}
				break;
		}
		return $params;
	}

	/**
	 *Get the name that a symbol is assocated with.
	 *@return string
	 */
	function getSymbolName( $symbol = '=' ) {
		$symbols = array(
		  	'~' 	=>	'Member',
		  	'-' 	=> 	'Shop Account',
			'*' 	=> 	'Subscriber',
			'=' 	=> 	'Official Beta Tester',
			'`' 	=> 	'Former Staff or Senior Member',
			'°' 	=> 	'Alumni Staff',
			'#' 	=> 	'Art Group Member',
			'@' 	=> 	'Shoutbox/dAmn Staff',
			':' 	=> 	'Premium Content Staff',
			'©' 	=> 	'Policy Enforcement Staff',
			'%' 	=> 	'deviantART Prints Staff',
			'+' 	=> 	'General Staff',
			'¢' 	=> 	'Creative Staff',
			'^' 	=> 	'Gallery Director',
			'$' 	=> 	'Core Administrator',
			'!' 	=> 	'Banned User'
			);
			if( array_key_exists( $symbol, $symbols ) ) return $symbols[$symbol];
			else return 'Unknown Type (' . $symbol . ')';
	}

	/**
	 *Parse Tablumps
	 *
	 *Html tags and emoticons are incased in tab characters. This function makes messages more human readable and easier to program for.
	 *@author doofsmack?
	 *@return string
	 *@version 0.1
	 */
	function parseTablumps( $text ) {
		$search[]="/&emote\t([^\t])\t([0-9]+)\t([0-9]+)\t(.+)\t(.+)\t/U";
		$replace[]=":\\1:";
		$search[]="/&emote\t(.+)\t([0-9]+)\t([0-9]+)\t(.+)\t(.+)\t/U";
		$replace[]="\\1";
		$search[]="/&br\t/";
		$replace[]="\n\t";
		$search[]="/&(b|i|s|u|sub|sup|code|ul|ol|li|p|bcode)\t/";
		$replace[]="<\\1>";
		$search[]="/&\\/(b|i|s|u|sub|sup|code|ul|ol|li|p|bcode)\t/";
		$replace[]="</\\1>";
		$search[]="/&acro\t(.*)\t(.*)&\\/acro\t/U";
		$replace[]="<acronym title=\"\\1\">\\2</acronym>";
		$search[]="/&abbr\t(.*)\t(.*)&\\/abbr\t/U";
		$replace[]="<abbr title=\"\\1\">\\2</abbr>";
		$search[]="/&link\t([^\t]*)\t([^\t]*)\t&\t/U";
		$replace[]="\\1 (\\2)";
		$search[]="/&link\t([^\t]*)\t&\t/U";
		$replace[]="\\1";
		$search[]="/&a\t(.*)\t(.*)\t(.*)&\\/a\t/U";
		$replace[]="<a href=\"\\1\" title=\"\\2\">\\3</a>";
		$search[]="/&(iframe|embed)\t(.*)\t([0-9]*)\t([0-9]*)\t&\\/(iframe|embed)\t/U";
		$replace[]="<\\1 src=\"\\2\" width=\"\\3\" height=\"\\4\" />";
		$search[]="/&img\t(.*)\t([0-9]*)\t([0-9]*)\t/U";
		$replace[]="<img src=\"\\1\" width=\"\\2\" height=\"\\3\" />";
		$search[]="/&thumb\t([0-9]*)\t(.*)\t(.*)\t(.*)\t(.*)\t(.*)\t(.*)\t/U";
		$replace[]=":thumb\\1:";
		$search[]="/&dev\t([^\t])\t([^\t]+)\t/U";
		$replace[]=":dev\\2:";
		$search[]="/&avatar\t([^\t]+)\t([^\t]+)\t/U";
		$replace[]=":icon\\1:";
		$search[]="/ width=\"\"/";
		$replace[]="";
		$search[]="/ height=\"\"/";
		$replace[]="";
		$search[]="/&gt;/";
		$replace[]=">";
		$search[]="/&lt;/";
		$replace[]="<";
		$search[]="/&amp;/";
		$replace[]="&";
		$search[] = "/\x07/";
		$replace[] = "";
		$search[]="/\t\n\t/";
		$replace[]="\n";
		$oldtext		=	'';

		while( $text != $oldtext ) {
			$oldtext = $text;
			$text = preg_replace( $search, $replace, $text );
		}
		return($text);
	}
	###########################
	#Regular PARSERS
	##
	//Temp Function
	function formatTime( $int ) {
		return formatTime( $int );
	}

}
global $parser;
$parser = new parser;
?>
