<?php 

function latinToMak($str) {
	$string="";
	for ($i = 0; $i < strlen ($str); $i++) {
		$chr = substr($str, $i, 1);
		switch($chr) {
			//----------------GOLEMI
			case '|': $string.="Ж"; break;
			case 'Q': $string.="Љ"; break;
			case 'W': $string.="Њ"; break;
			case 'E': $string.="Е"; break;
			case 'R': $string.="Р"; break;
			case 'T': $string.="Т"; break;
			case 'Y': $string.="Ѕ"; break;
			case 'U': $string.="У"; break;
			case 'I': $string.="И"; break;
			case 'O': $string.="О"; break;
			case 'P': $string.="П"; break;
			case '{': $string.="Ш"; break;
			case '}': $string.="Ѓ"; break;
			
			case 'A': $string.="А"; break;
			case 'S': $string.="С"; break;
			case 'D': $string.="Д"; break;
			case 'F': $string.="Ф"; break;
			case 'G': $string.="Г"; break;
			case 'H': $string.="Х"; break;
			case 'J': $string.="Ј"; break;
			case 'K': $string.="К"; break;
			case 'L': $string.="Л"; break;
			case ':': $string.="Ч"; break;
			case '\"': $string.="Ќ"; break;
			
			case 'Z': $string.="З"; break;
			case 'X': $string.="Џ"; break;
			case 'C': $string.="Ц"; break;
			case 'V': $string.="В"; break;
			case 'B': $string.="Б"; break;
			case 'N': $string.="Н"; break;	
			case 'M': $string.="М"; break;			
			
			//---------------MALI
			case '\\': $string.="ж"; break;
			case 'q': $string.="љ"; break;
			case 'w': $string.="њ"; break;
			case 'e': $string.="е"; break;
			case 'r': $string.="р"; break;
			case 't': $string.="т"; break;
			case 'y': $string.="ѕ"; break;
			case 'u': $string.="у"; break;
			case 'i': $string.="и"; break;
			case 'o': $string.="о"; break;
			case 'p': $string.="п"; break;
			case '[': $string.="ш"; break;
			case ']': $string.="ѓ"; break;
			
			case 'a': $string.="а"; break;
			case 's': $string.="с"; break;
			case 'd': $string.="д"; break;
			case 'f': $string.="Ф"; break;
			case 'g': $string.="г"; break;
			case 'h': $string.="х"; break;
			case 'j': $string.="ј"; break;
			case 'k': $string.="к"; break;
			case 'l': $string.="л"; break;
			case ';': $string.="ч"; break;
			case '\'': $string.="ќ"; break;
			
			case 'z': $string.="з"; break;
			case 'x': $string.="џ"; break;
			case 'c': $string.="ц"; break;
			case 'v': $string.="в"; break;
			case 'b': $string.="б"; break;
			case 'n': $string.="н"; break;	
			case 'm': $string.="м"; break;
			
			default : $string.=$chr; break;
		}
	}
	return $string;
}

?>