<?php

define ("CONVERT_TABLES_DIR", PHPWCMS_ROOT.'/include/inc_ext/ConvertCharset/ConvertTables/');
define ("DEBUG_MODE", 0);

class ConvertCharset {
    var $RecognizedEncoding; // (boolean) This value keeps information if string contains multibyte chars.
    var $Entities; // (boolean) This value keeps information if output should be with numeric entities.
    var $FromCharset; // (string) This value keeps information about source (from) encoding
    var $ToCharset; // (string) This value keeps information about destination (to) encoding
    var $CharsetTable; // (array) This property keeps convert Table inside

    function __construct($FromCharset, $ToCharset, $TurnOnEntities = false)
    {

        $this->FromCharset = strtolower($FromCharset);
        $this->ToCharset = strtolower($ToCharset);
        $this->Entities = $TurnOnEntities;


        if ($this->FromCharset == $this->ToCharset)
        {
            print $this->DebugOutput(1, 0, $this->FromCharset);
        }
        if (($this->FromCharset == $this->ToCharset) AND ($this->FromCharset == "utf-8"))
        {
            print $this->DebugOutput(0, 4, $this->FromCharset);
            exit;
        }


        if ($this->FromCharset == "utf-8")
        {
            $this->CharsetTable = $this->MakeConvertTable ($this->ToCharset);
        }
        elseif ($this->ToCharset == "utf-8")
        {
            $this->CharsetTable = $this->MakeConvertTable ($this->FromCharset);
        }
        else
        {
            $this->CharsetTable = $this->MakeConvertTable ($this->FromCharset, $this->ToCharset);
        }

    }

    function DebugOutput ($Group, $Number, $Value = false)
    {
        $Debug[0][0] = "Error, can NOT read file: " . $Value . "<br>";
        $Debug[0][1] = "Error, can't find maching char \"" . $Value . "\" in destination encoding table!" . "<br>";
        $Debug[0][2] = "Error, can't find maching char \"" . $Value . "\" in source encoding table!" . "<br>";
        $Debug[0][3] = "Error, you did NOT set variable " . $Value . " in Convert() function." . "<br>";
        $Debug[0][4] = "You can NOT convert string from " . $Value . " to " . $Value . "!" . "<BR>";
        $Debug[1][0] = "Notice, you are trying to convert string from " . $Value . " to " . $Value . ", don't you feel it's strange? ;-)" . "<br>";
        $Debug[1][1] = "Notice, both charsets " . $Value . " are identical! Check encoding tables files." . "<br>";
        $Debug[1][2] = "Notice, there is no unicode char in the string you are trying to convert." . "<br>";

        if (DEBUG_MODE >= $Group)
        {
            return $Debug[$Group][$Number];
        }
    }

    function MakeConvertTable ($FromCharset, $ToCharset = '')
    {
        $ConvertTable = array();
        for($i = 0; $i < func_num_args(); $i++)
        {

            $FileName = func_get_arg($i);
            if (!is_file(CONVERT_TABLES_DIR . $FileName))
            {
                print $this->DebugOutput(0, 0, CONVERT_TABLES_DIR . $FileName); //Print an error message
                exit;
            }
            $FileWithEncTabe = fopen(CONVERT_TABLES_DIR . $FileName, "r") or die(); //This die(); is just to make sure...
            while(!feof($FileWithEncTabe))
            {

                if($OneLine = trim(fgets($FileWithEncTabe, 1024)))
                {

                    if (substr($OneLine, 0, 1) != "#")
                    {

                        $HexValue = preg_split ("/[\s,]+/", $OneLine, 3); //We need only first 2 values

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

        if(!is_array($ConvertTable[$FromCharset])) $ConvertTable[$FromCharset] = array();

        if ((func_num_args() > 1) && (count($ConvertTable[$FromCharset]) == count($ConvertTable[$ToCharset])) && (count(array_diff_assoc($ConvertTable[$FromCharset], $ConvertTable[$ToCharset])) == 0))
        {
            print $this->DebugOutput(1, 1, "$FromCharset, $ToCharset");
        }
        return $ConvertTable;
    }

    function ConvertArray(& $array)
    {
        if (!is_array($array))
        {
            $array = $this->Convert($array);
            return;
        }
        foreach($array as $k => $v)
        {
            $this->ConvertArray($v);
            $array[$k] = $v;
        }
    }

    function Convert ($StringToChange)
    {
        if(!strlen($StringToChange)) return '';
        $StringToChange = (string)($StringToChange);

        if($this->FromCharset == $this->ToCharset) return $StringToChange;

        $NewString = "";

        if ($this->FromCharset != "utf-8")
        {

            for ($i = 0; $i < strlen($StringToChange); $i++)
            {
                $HexChar = "";
                $UnicodeHexChar = "";
                $HexChar = strtoupper(dechex(ord($StringToChange[$i])));
                if (strlen($HexChar) == 1) $HexChar = "0" . $HexChar;
                if (($this->FromCharset == "gsm0338") && ($HexChar == '1B')){
                    $i++;
                    $HexChar .= strtoupper(dechex(ord($StringToChange[$i])));
                }
                if ($this->ToCharset != "utf-8")
                {
                    if (in_array($HexChar, $this->CharsetTable[$this->FromCharset]))
                    {
                        $UnicodeHexChar = array_search($HexChar, $this->CharsetTable[$this->FromCharset]);
                        $UnicodeHexChars = explode("+", $UnicodeHexChar);
                        for($UnicodeHexCharElement = 0; $UnicodeHexCharElement < count($UnicodeHexChars); $UnicodeHexCharElement++)
                        {
                            if (array_key_exists($UnicodeHexChars[$UnicodeHexCharElement], $this->CharsetTable[$this->ToCharset]))
                            {
                                if ($this->Entities == true)
                                {
                                    $NewString .= $this->UnicodeEntity($this->HexToUtf($UnicodeHexChars[$UnicodeHexCharElement]));
                                }
                                else
                                {
                                    $NewString .= chr(hexdec($this->CharsetTable[$this->ToCharset][$UnicodeHexChars[$UnicodeHexCharElement]]));
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
                        print $this->DebugOutput(0, 2, $StringToChange[$i]);
                    }
                }
                else
                {
                    if (in_array("$HexChar", $this->CharsetTable[$this->FromCharset]))
                    {
                        $UnicodeHexChar = array_search($HexChar, $this->CharsetTable[$this->FromCharset]);

                        $UnicodeHexChars = explode("+", $UnicodeHexChar);
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

        elseif($this->FromCharset == "utf-8")
        {
            $HexChar = "";
            $UnicodeHexChar = "";
            $this->CharsetTable = $this->MakeConvertTable ($this->ToCharset);
            foreach ($this->CharsetTable[$this->ToCharset] as $UnicodeHexChar => $HexChar)
            {
                if ($this->Entities == true){
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

    function UnicodeEntity ($UnicodeString)
    {
        $OutString = "";
        $StringLenght = strlen ($UnicodeString);
        for ($CharPosition = 0; $CharPosition < $StringLenght; $CharPosition++)
        {
            $Char = $UnicodeString [$CharPosition];
            $AsciiChar = ord ($Char);

            if ($AsciiChar < 128){
                $OutString .= $Char;
            }
            elseif ($AsciiChar >> 5 == 6){
                $FirstByte = ($AsciiChar & 31);
                $CharPosition++;
                $Char = $UnicodeString [$CharPosition];
                $AsciiChar = ord ($Char);
                $SecondByte = ($AsciiChar & 63);
                $AsciiChar = ($FirstByte * 64) + $SecondByte;
                $Entity = sprintf ("&#%d;", $AsciiChar);
                $OutString .= $Entity;
            }
            elseif ($AsciiChar >> 4 == 14){
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
            elseif ($AsciiChar >> 3 == 30){
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

    function HexToUtf ($UtfCharInHex)
    {
        $OutputChar = "";
        $UtfCharInDec = hexdec($UtfCharInHex);
        if($UtfCharInDec < 128)
        {
            $OutputChar .= chr($UtfCharInDec);
        }
        elseif($UtfCharInDec < 2048) {
            $OutputChar .= chr(($UtfCharInDec >> 6) + 192) . chr(($UtfCharInDec & 63) + 128);
        }
        elseif($UtfCharInDec < 65536) {
            $OutputChar .= chr(($UtfCharInDec >> 12) + 224) . chr((($UtfCharInDec >> 6) & 63) + 128) . chr(($UtfCharInDec & 63) + 128);
        }
        elseif($UtfCharInDec < 2097152) {
            $OutputChar .= chr($UtfCharInDec >> 18 + 240) . chr((($UtfCharInDec >> 12) & 63) + 128) . chr(($UtfCharInDec >> 6) & 63 + 128) . chr($UtfCharInDec & 63 + 128);
        }

        return $OutputChar;
    } // function DebugOutput

} //class ends here
