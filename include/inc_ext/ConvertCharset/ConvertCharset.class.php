<?php
/**
 * @author Mikolaj Jedrzejak <mikolajj@op.pl>
 * @copyright Copyright Mikolaj Jedrzejak (c) 2003-2004
 * @version 1.0 2004-07-27 00:37
 * @link http://www.unicode.org Unicode Homepage
 * @link http://www.mikkom.pl My Homepage
 * 
 **/
//$PATH_TO_CLASS = dirname(ereg_replace("\\\\","/",__FILE__)) . "/" . "ConvertTables" . "/";
//define ("CONVERT_TABLES_DIR", $PATH_TO_CLASS);

// changed for phpwcms
define ("CONVERT_TABLES_DIR", PHPWCMS_ROOT.'/include/inc_ext/ConvertCharset/ConvertTables/');
define ("DEBUG_MODE", 0);

/**
 * -- 1.0 2004-07-28 --
 * 
 * -- The most important thing --
 * I want to thank all people who helped me fix all bugs, small and big once.
 * I hope that you don't mind that your names are in this file.
 * 
 * -- Some Apache issues --
 * I get info from Lukas Lisa, that in some cases with special apache configuration
 * you have to put header() function with proper encoding to get your result
 * displayed correctly.
 * If you want to see what I mean, go to demo.php and demo1.php
 * 
 * -- BETA 1.0 2003-10-21 --
 * 
 * -- You should know about... --
 * For good understanding this class you shouls read all this stuff first :) but if you are
 * in a hurry just start the demo.php and see what's inside.
 * 1. That I'm not good in english at 03:45 :) - so forgive me all mistakes
 * 2. This class is a BETA version because I haven't tested it enough
 * 3. Feel free to contact me with questions, bug reports and mistakes in PHP and this documentation (email below)
 * 
 * -- In a few words... --
 * Why ConvertCharset class?
 * 
 * I have made this class because I had a lot of problems with diferent charsets. First because people
 * from Microsoft wanted to have thair own encoding, second because people from Macromedia didn't
 * thought about other languages, third because sometimes I need to use text written on MAC, and of course
 * it has its own encoding :)
 * 
 * Notice & remember:
 * - When I'm saying 1 byte string I mean 1 byte per char.
 * - When I'm saying multibyte string I mean more than one byte per char.
 * 
 * So, this are main FEATURES of this class:
 * - conversion between 1 byte charsets
 * - conversion from 1 byte to multi byte charset (utf-8)
 * - conversion from multibyte charset (utf-8) to 1 byte charset
 * - every conversion output can be save with numeric entities (browser charset independent - not a full truth)
 * 
 * This is a list of charsets you can operate with, the basic rule is that a char have to be in both charsets,
 * otherwise you'll get an error.
 * 
 * - WINDOWS
 * - windows-1250 - Central Europe
 * - windows-1251 - Cyrillic
 * - windows-1252 - Latin I
 * - windows-1253 - Greek
 * - windows-1254 - Turkish
 * - windows-1255 - Hebrew
 * - windows-1256 - Arabic
 * - windows-1257 - Baltic
 * - windows-1258 - Viet Nam
 * - cp874 - Thai - this file is also for DOS
 * 
 * - DOS
 * - cp437 - Latin US
 * - cp737 - Greek
 * - cp775 - BaltRim
 * - cp850 - Latin1
 * - cp852 - Latin2
 * - cp855 - Cyrylic
 * - cp857 - Turkish
 * - cp860 - Portuguese
 * - cp861 - Iceland
 * - cp862 - Hebrew
 * - cp863 - Canada
 * - cp864 - Arabic
 * - cp865 - Nordic
 * - cp866 - Cyrylic Russian (this is the one, used in IE "Cyrillic (DOS)" )
 * - cp869 - Greek2
 * 
 * - MAC (Apple)
 * - x-mac-cyrillic
 * - x-mac-greek
 * - x-mac-icelandic
 * - x-mac-ce
 * - x-mac-roman
 * 
 * - ISO (Unix/Linux)
 * - iso-8859-1
 * - iso-8859-2
 * - iso-8859-3
 * - iso-8859-4
 * - iso-8859-5
 * - iso-8859-6
 * - iso-8859-7
 * - iso-8859-8
 * - iso-8859-9
 * - iso-8859-10
 * - iso-8859-11
 * - iso-8859-12
 * - iso-8859-13
 * - iso-8859-14
 * - iso-8859-15
 * - iso-8859-16
 * 
 * - MISCELLANEOUS
 * - gsm0338 (ETSI GSM 03.38)
 * - cp037
 * - cp424
 * - cp500 
 * - cp856
 * - cp875
 * - cp1006
 * - cp1026
 * - koi8-r (Cyrillic)
 * - koi8-u (Cyrillic Ukrainian)
 * - nextstep
 * - us-ascii
 * - us-ascii-quotes
 * 
 * - DSP implementation for NeXT
 * - stdenc
 * - symbol
 * - zdingbat
 * 
 * - And specially for old Polish programs
 * - mazovia
 *  
 * -- Now, to the point... --
 * Here are main variables.
 * 
 * DEBUG_MODE
 * 
 * You can set this value to:
 * - -1 - No errors or comments
 * - 0  - Only error messages, no comments
 * - 1  - Error messages and comments
 * 
 * Default value is 1, and during first steps with class it should be left as is. 
 *
 * CONVERT_TABLES_DIR
 * 
 * This is a place where you store all files with charset encodings. Filenames should have
 * the same names as encodings. My advise is to keep existing names, because thay
 * were taken from unicode.org (www.unicode.org), and after update to unicode 3.0 or 4.0
 * the names of files will be the same, so if you want to save your time...uff, leave the
 * names as thay are for future updates.
 * 
 * The directory with edings files should be in a class location directory by default,
 * but of course you can change it if you like. 
 * 
 * @package All about charset...
 * @author Mikolaj Jedrzejak <mikolajj@op.pl>
 * @copyright Copyright Mikolaj Jedrzejak (c) 2003-2004
 * @version 1.0 2004-07-27 23:11
 * @access public
 * 
 * @link http://www.unicode.org Unicode Homepage
 **/
class ConvertCharset {
	var $RecognizedEncoding; //This value keeps information if string contains multibyte chars.
	var $Entities; // This value keeps information if output should be with numeric entities.

	/**
	 * CharsetChange::NumUnicodeEntity()
	 * 
	 * Unicode encoding bytes, bits representation.
	 * Each b represents a bit that can be used to store character data.
	 * - bytes, bits, binary representation
	 * - 1,   7,  0bbbbbbb
	 * - 2,  11,  110bbbbb 10bbbbbb
	 * - 3,  16,  1110bbbb 10bbbbbb 10bbbbbb
	 * - 4,  21,  11110bbb 10bbbbbb 10bbbbbb 10bbbbbb
	 * 
	 * This function is written in a "long" way, for everyone who woluld like to analize
	 * the process of unicode encoding and understand it. All other functions like HexToUtf
	 * will be written in a "shortest" way I can write tham :) it does'n mean thay are short
	 * of course. You can chech it in HexToUtf() (link below) - very similar function.
	 * 
	 * IMPORTANT: Remember that $UnicodeString input CANNOT have single byte upper half
	 * extended ASCII codes, why? Because there is a posibility that this function will eat
	 * the following char thinking it's miltibyte unicode char.
	 * 
	 * @param string $UnicodeString Input Unicode string (1 char can take more than 1 byte)
	 * @return string This is an input string olso with unicode chars, bus saved as entities
	 * @see HexToUtf()
	 **/
	function UnicodeEntity ($UnicodeString) 
	{
	  $OutString  = "";
	  $StringLenght = strlen ($UnicodeString);
	  for ($CharPosition = 0; $CharPosition < $StringLenght; $CharPosition++) 
		{
	    $Char = $UnicodeString [$CharPosition];
	    $AsciiChar = ord ($Char);

	   if ($AsciiChar < 128) //1 7 0bbbbbbb (127)
	   {
		   $OutString .= $Char; 
	   }
	   elseif ($AsciiChar >> 5 == 6) //2 11 110bbbbb 10bbbbbb (2047)
	   {
		   $FirstByte = ($AsciiChar & 31);
		   $CharPosition++;
		   $Char = $UnicodeString [$CharPosition];
		   $AsciiChar = ord ($Char);
		   $SecondByte = ($AsciiChar & 63);
		   $AsciiChar = ($FirstByte * 64) + $SecondByte;
		   $Entity = sprintf ("&#%d;", $AsciiChar);
		   $OutString .= $Entity;
	   }
	   elseif ($AsciiChar >> 4  == 14)  //3 16 1110bbbb 10bbbbbb 10bbbbbb
	   {
			$FirstByte = ($AsciiChar & 31);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$SecondByte = ($AsciiChar & 63);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$ThidrByte = ($AsciiChar & 63);
			$AsciiChar = ((($FirstByte * 64) + $SecondByte) * 64) + $ThidrByte;
			
			$Entity = sprintf ("&#%d;", $AsciiChar);
			$OutString .= $Entity;
	    }
		elseif ($AsciiChar >> 3 == 30) //4 21 11110bbb 10bbbbbb 10bbbbbb 10bbbbbb
		{
			$FirstByte = ($AsciiChar & 31);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$SecondByte = ($AsciiChar & 63);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$ThidrByte = ($AsciiChar & 63);
			$CharPosition++;
			$Char = $UnicodeString [$CharPosition];
			$AsciiChar = ord ($Char);
			$FourthByte = ($AsciiChar & 63);
			$AsciiChar = ((((($FirstByte * 64) + $SecondByte) * 64) + $ThidrByte) * 64) + $FourthByte;
	
			$Entity = sprintf ("&#%d;", $AsciiChar);
			$OutString .= $Entity;
	    }
	  }
	  return $OutString;
	} 
	
	/**
	 * ConvertCharset::HexToUtf()
	 * 
	 * This simple function gets unicode  char up to 4 bytes and return it as a regular char.
	 * It is very similar to  UnicodeEntity function (link below). There is one difference 
	 * in returned format. This time it's a regular char(s), in most cases it will be one or two chars. 
	 * 
	 * @param string $UtfCharInHex Hexadecimal value of a unicode char.
	 * @return string Encoded hexadecimal value as a regular char.
	 * @see UnicodeEntity()
	 **/
	function HexToUtf ($UtfCharInHex)
	{
		$OutputChar = "";
		$UtfCharInDec = hexdec($UtfCharInHex);
		if($UtfCharInDec<128)
		{
			$OutputChar .= chr($UtfCharInDec);
		}
    	elseif($UtfCharInDec<2048)
		{
			$OutputChar .= chr(($UtfCharInDec>>6)+192).chr(($UtfCharInDec&63)+128);
		}
    	elseif($UtfCharInDec<65536)
		{
			$OutputChar .= chr(($UtfCharInDec>>12)+224).chr((($UtfCharInDec>>6)&63)+128).chr(($UtfCharInDec&63)+128);
		}
    	elseif($UtfCharInDec<2097152)
		{
			$OutputChar .= chr($UtfCharInDec>>18+240).chr((($UtfCharInDec>>12)&63)+128).chr(($UtfCharInDec>>6)&63+128). chr($UtfCharInDec&63+128);
		}
		
		return $OutputChar;
	}


	/**
	 * CharsetChange::MakeConvertTable()
	 * 
	 * This function creates table with two SBCS (Single Byte Character Set). Every conversion
	 * is through this table.
	 *  
	 * - The file with encoding tables have to be save in "Format A" of unicode.org charset table format! This is usualy writen in a header of every charset file.
	 * - BOTH charsets MUST be SBCS
	 * - The files with encoding tables have to be complet (Non of chars can be missing, unles you are sure you are not going to use it)
	 * 
	 * "Format A" encoding file, if you have to build it by yourself should aplly these rules:
	 * - you can comment everything with #
	 * - first column contains 1 byte chars in hex starting from 0x..
	 * - second column contains unicode equivalent in hex starting from 0x....
	 * - then every next column is optional, but in "Format A" it should contain unicode char name or/and your own comment
	 * - the columns can be splited by "spaces", "tabs", "," or any combination of these
	 * - below is an example
	 * 
	 * <code>
	 * #
	 * #	The entries are in ANSI X3.4 order.
	 * #
	 * 0x00	0x0000	#	NULL end extra comment, if needed
	 * 0x01	0x0001	#	START OF HEADING
	 * # Oh, one more thing, you can make comments inside of a rows if you like.
	 * 0x02	0x0002	#	START OF TEXT
	 * 0x03	0x0003	#	END OF TEXT
	 * next line, and so on...
	 * </code>
	 * 
	 * You can get full tables with encodings from http://www.unicode.org
	 * 
	 * @param string $FirstEncoding Name of first encoding and first encoding filename (thay have to be the same)
	 * @param string $SecondEncoding Name of second encoding and second encoding filename (thay have to be the same). Optional for building a joined table.
	 * @return array Table necessary to change one encoding to another.
	 **/
	function MakeConvertTable ($FirstEncoding, $SecondEncoding = "") 
	{
		$ConvertTable = array();
		for($i = 0; $i < func_num_args(); $i++)
		{
			/**
			 * Because func_*** can't be used inside of another function call
			 * we have to save it as a separate value.
			 **/
			$FileName = func_get_arg($i);
			if (!is_file(CONVERT_TABLES_DIR . $FileName)) 
			{
			    print $this->DebugOutput(0, 0, CONVERT_TABLES_DIR . $FileName); //Print an error message
					exit;
			}
			$FileWithEncTabe = fopen(CONVERT_TABLES_DIR . $FileName, "r") or die(); //This die(); is just to make sure...
		  while(!feof($FileWithEncTabe))
			{
				/**
				 * We asume that line is not longer
				 * than 1024 which is the default value for fgets function 
				 **/
		   if($OneLine=trim(fgets($FileWithEncTabe, 1024)))
			 {
				/**
				 * We don't need all comment lines. I check only for "#" sign, because
				 * this is a way of making comments by unicode.org in thair encoding files
				 * and that's where the files are from :-)
				 **/
		   	if (substr($OneLine, 0, 1) != "#") 
				{
					/**
					 * Sometimes inside the charset file the hex walues are separated by
					 * "space" and sometimes by "tab", the below preg_split can also be used
					 * to split files where separator is a ",", "\r", "\n" and "\f"
					 **/
					$HexValue = preg_split ("/[\s,]+/", $OneLine, 3);  //We need only first 2 values
						/**
						 * Sometimes char is UNDEFINED, or missing so we can't use it for convertion
						 **/
						if (substr($HexValue[1], 0, 1) != "#") 
						{
								$ArrayKey = strtoupper(str_replace(strtolower("0x"), "", $HexValue[1]));
								$ArrayValue = strtoupper(str_replace(strtolower("0x"), "", $HexValue[0]));
								$ConvertTable[func_get_arg($i)][$ArrayKey] = $ArrayValue;
						}
				} //if (substr($OneLine,...
		   } //if($OneLine=trim(f...
		  } //while(!feof($FirstFileWi...
		} //for($i = 0; $i < func_...
	/**
	 * The last thing is to check if by any reason both encoding tables are not the same.
	 * For example, it will happen when you save the encoding table file with a wrong name
	 *  - of another charset. 
	 **/
	if ((func_num_args() > 1) && (count($ConvertTable[$FirstEncoding]) == count($ConvertTable[$SecondEncoding])) && (count(array_diff_assoc($ConvertTable[$FirstEncoding], $ConvertTable[$SecondEncoding])) == 0)) 
	{
	    print $this->DebugOutput(1, 1, "$FirstEncoding, $SecondEncoding");
	}
	return $ConvertTable;
	}
	
	
	
	/**
	 * ConvertCharset::Convert()
	 * 
	 * This is a basic function you are using. I hope that you can figure out this function syntax :-)
	 * 
	 * @param string $StringToChange The string you want to change :)
	 * @param string $FromCharset Name of $StringToChange encoding, you have to know it.
	 * @param string $ToCharset Name of a charset you want to get for $StringToChange.
	 * @param boolean $TurnOnEntities Set to true or 1 if you want to use numeric entities insted of regular chars.
	 * @return string Converted string in brand new encoding :)
	 * @version 1.0 2004-07-27 01:09
	 **/
	function Convert ($StringToChange, $FromCharset, $ToCharset, $TurnOnEntities = false)
	{
		/**
		 * Check are there all variables 
		 **/
		 if ($StringToChange == "") 
		 {
				print $this->DebugOutput(0, 3, "\$StringToChange");
		 }
		 elseif ($FromCharset == "") 
		 {
		 		print $this->DebugOutput(0, 3, "\$FromCharset");	
		 }
		 elseif ($ToCharset == "") 
		 {
		 		print $this->DebugOutput(0, 3, "\$ToCharset");	
		 }
		 
		/**
		 * Now a few variables need to be set. 
		 **/
		$NewString = "";
		$this->Entities = $TurnOnEntities;
		
		/**
		 * For all people who like to use uppercase for charset encoding names :) 
		 **/
		$FromCharset = strtolower($FromCharset);
		$ToCharset   = strtolower($ToCharset);

		/**
		 * Of course you can make a conversion from one charset to the same one :) 
		 * but I feel obligate to let you know about it. 
		 **/
		if ($FromCharset == $ToCharset) 
		{
		    print $this->DebugOutput(1, 0, $FromCharset);
		}
		if (($FromCharset == $ToCharset) AND ($FromCharset == "utf-8")) 
		{
		    print $this->DebugOutput(0, 4, $FromCharset);
				exit;
		}
		
		/**
		 * This divison was made to prevent errors during convertion to/from utf-8 with
		 * "entities" enabled, because we need to use proper destination(to)/source(from)
		 * encoding table to write proper entities.
		 * 
		 * This is the first case. We are convertinf from 1byte chars...
		 **/
		if ($FromCharset != "utf-8") 
		{
				/**
				 * Now build table with both charsets for encoding change. 
				 **/
				if ($ToCharset != "utf-8") 
				{
					$CharsetTable = $this->MakeConvertTable ($FromCharset, $ToCharset);
				}
				else
				{
					$CharsetTable = $this->MakeConvertTable ($FromCharset);
				}
				/**
				 * For each char in a string... 
				 **/
				for ($i = 0; $i < strlen($StringToChange); $i++)
				{
					$HexChar = "";
					$UnicodeHexChar = "";
					$HexChar = strtoupper(dechex(ord($StringToChange[$i])));
					// This is fix from Mario Klingemann, it prevents
					// droping chars below 16 because of missing leading 0 [zeros]
					if (strlen($HexChar)==1) $HexChar = "0".$HexChar;
					//end of fix by Mario Klingemann
					// This is quick fix of 10 chars in gsm0338
					// Thanks goes to Andrea Carpani who pointed on this problem
					// and solve it ;)
					if (($FromCharset == "gsm0338") && ($HexChar == '1B')) {
						$i++;
						$HexChar .= strtoupper(dechex(ord($StringToChange[$i])));
					}
					// end of workarround on 10 chars from gsm0338
					if ($ToCharset != "utf-8") 
					{
						if (in_array($HexChar, $CharsetTable[$FromCharset]))
						{
							$UnicodeHexChar = array_search($HexChar, $CharsetTable[$FromCharset]);
							$UnicodeHexChars = explode("+",$UnicodeHexChar);
							for($UnicodeHexCharElement = 0; $UnicodeHexCharElement < count($UnicodeHexChars); $UnicodeHexCharElement++)
							{
							  if (array_key_exists($UnicodeHexChars[$UnicodeHexCharElement], $CharsetTable[$ToCharset])) 
								{
									if ($this->Entities == true) 
									{
										$NewString .= $this->UnicodeEntity($this->HexToUtf($UnicodeHexChars[$UnicodeHexCharElement]));
									}
									else
									{
										$NewString .= chr(hexdec($CharsetTable[$ToCharset][$UnicodeHexChars[$UnicodeHexCharElement]]));
									}
								}
							 	else
								{
										print $this->DebugOutput(0, 1, $StringToChange[$i]);
								}
							} //for($UnicodeH...
						}
						else
						{
							print $this->DebugOutput(0, 2,$StringToChange[$i]);
						}
					}
					else
					{
						if (in_array("$HexChar", $CharsetTable[$FromCharset])) 
						{
							$UnicodeHexChar = array_search($HexChar, $CharsetTable[$FromCharset]);
							/**
					     * Sometimes there are two or more utf-8 chars per one regular char.
							 * Extream, example is polish old Mazovia encoding, where one char contains
							 * two lettes 007a (z) and 0142 (l slash), we need to figure out how to
							 * solve this problem.
							 * The letters are merge with "plus" sign, there can be more than two chars.
							 * In Mazowia we have 007A+0142, but sometimes it can look like this
							 * 0x007A+0x0142+0x2034 (that string means nothing, it just shows the possibility...)
					     **/
							$UnicodeHexChars = explode("+",$UnicodeHexChar);
							for($UnicodeHexCharElement = 0; $UnicodeHexCharElement < count($UnicodeHexChars); $UnicodeHexCharElement++)
							{
								if ($this->Entities == true) 
								{
									$NewString .= $this->UnicodeEntity($this->HexToUtf($UnicodeHexChars[$UnicodeHexCharElement]));
								}
								else
								{
									$NewString .= $this->HexToUtf($UnicodeHexChars[$UnicodeHexCharElement]);
								}
							} // for							
						}
						else
						{
							print $this->DebugOutput(0, 2, $StringToChange[$i]);
						}
					}					
				}
		}
		/**
		 * This is second case. We are encoding from multibyte char string. 
		 **/
		elseif($FromCharset == "utf-8")
		{
			$HexChar = "";
			$UnicodeHexChar = "";
			$CharsetTable = $this->MakeConvertTable ($ToCharset);
			foreach ($CharsetTable[$ToCharset] as $UnicodeHexChar => $HexChar)
			{
					if ($this->Entities == true) {
						$EntitieOrChar = $this->UnicodeEntity($this->HexToUtf($UnicodeHexChar));
					}
					else
					{
						$EntitieOrChar = chr(hexdec($HexChar));
					}
					$StringToChange = str_replace($this->HexToUtf($UnicodeHexChar), $EntitieOrChar, $StringToChange);
			}
			$NewString = $StringToChange;
		}
	
	return $NewString;
	}
	
	/**
	 * ConvertCharset::DebugOutput()
	 * 
	 * This function is not really necessary, the debug output could stay inside of
	 * source code but like this, it's easier to manage and translate.
	 * Besides I couldn't find good coment/debug class :-) Maybe I'll write one someday... 
	 * 
	 * All messages depend on DEBUG_MODE level, as I was writing before you can set this value to:
   * - -1 - No errors or notces are shown
   * - 0  - Only error messages are shown, no notices 
   * - 1  - Error messages and notices are shown
	 * 
	 * @param int $Group Message groupe: error - 0, notice - 1
	 * @param int $Number Following message number 
	 * @param mix $Value This walue is whatever you want, usualy it's some parameter value, for better message understanding.
	 * @return string String with a proper message.
	 **/
	function DebugOutput ($Group, $Number, $Value = false)
	{
		//$Debug [$Group][$Number] = "Message, can by with $Value";
		//$Group[0] - Errors
		//$Group[1] - Notice
		$Debug[0][0] = "Error, can NOT read file: " . $Value . "<br>";
		$Debug[0][1] = "Error, can't find maching char \"". $Value ."\" in destination encoding table!" . "<br>";
		$Debug[0][2] = "Error, can't find maching char \"". $Value ."\" in source encoding table!" . "<br>";
		$Debug[0][3] = "Error, you did NOT set variable " . $Value . " in Convert() function." . "<br>";
		$Debug[0][4] = "You can NOT convert string from " . $Value . " to " . $Value . "!" .  "<BR>";
		$Debug[1][0] = "Notice, you are trying to convert string from ". $Value ." to ". $Value .", don't you feel it's strange? ;-)" . "<br>";
		$Debug[1][1] = "Notice, both charsets " . $Value . " are identical! Check encoding tables files." . "<br>";
		$Debug[1][2] = "Notice, there is no unicode char in the string you are trying to convert." . "<br>";
		
		if (DEBUG_MODE >= $Group) 
		{
	  	return $Debug[$Group][$Number];
		}
	} // function DebugOutput

} //class ends here
?>