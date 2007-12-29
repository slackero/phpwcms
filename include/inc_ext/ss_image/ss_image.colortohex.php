<?PHP
################################################################################
#	Purpose:	This is Image Processor include. It contains colortohex() 
#				function which was separated from ImageProcessor to save file 
#				size and so
#				loading time. You can also use this function without Image Processor
#	File:		ss_image.colortohex.php
#	Author:		Yuriy Horobey, SmiledSoft.com
#	Copyright:	Yuriy Horobey, SmiledSoft.com
#	Contact:	http://smiledsoft.com/
#	
# License information:
# this version of ss_image.class is a special licenced version for
# using inside of phpwcms only. There is no license for forked versions.
# It is NOT released under the GPL and it is NOT FREE.
#
#	Please do not remove this notice.
#	
###########################################################################

function colortohex($color){
//translate color name to RGB hex string or return same $color as input parameter with no changes
	$color=strtolower(trim($color));
	switch($color){

			case "aliceblue"		: $res="F0F8FF"; break;
			case "antiquewhite"		: $res="FAEBD7"; break;
			case "aqua"				: $res="00FFFF"; break;
			case "aquamarine"		: $res="7FFFD4"; break;
			case "azure"			: $res="F0FFFF"; break;
			case "beige"			: $res="F5F5DC"; break;
			case "bisque"			: $res="FFE4C4"; break;
			case "black"			: $res="000000"; break;
			case "blanchedalmond"	: $res="FFEBCD"; break;
			case "blue"				: $res="0000FF"; break;
			case "blueviolet"		: $res="8A2BE2"; break;
			case "brown"			: $res="A52A2A"; break;
			case "burlywood"		: $res="DEB887"; break;
			case "cadetblue"		: $res="5F9EA0"; break;
			case "chartreuse"		: $res="7FFF00"; break;
			case "chocolate"		: $res="D2691E"; break;
			case "coral"			: $res="FF7F50"; break;
			case "cornflowerblue"	: $res="6495ED"; break;
			case "cornsilk"			: $res="FFF8DC"; break;
			case "crimson"			: $res="DC143C"; break;
			case "cyan"				: $res="00FFFF"; break;
			case "darkblue"			: $res="00008B"; break;
			case "darkcyan"			: $res="008B8B"; break;
			case "darkgoldenrod"	: $res="B8860B"; break;
			case "darkgray"			: $res="A9A9A9"; break;
			case "darkgreen"		: $res="006400"; break;
			case "darkkhaki"		: $res="BDB76B"; break;
			case "darkmagenta"		: $res="8B008B"; break;
			case "darkolivegreen"	: $res="556B2F"; break;
			case "darkorange"		: $res="FF8C00"; break;
			case "darkorchid"		: $res="9932CC"; break;
			case "darkred"			: $res="8B0000"; break;
			case "darksalmon"		: $res="E9967A"; break;
			case "darkseagreen" 	: $res="8FBC8F"; break;
			case "darkslateblue"	: $res="483D8B"; break;
			case "darkslategray"	: $res="2F4F4F"; break;
			case "darkturquoise"	: $res="00CED1"; break;
			case "darkviolet"		: $res="9400D3"; break;
			case "deeppink"			: $res="FF1493"; break;
			case "deepskyblue"		: $res="00BFFF"; break;
			case "dimgray"			: $res="696969"; break;
			case "dodgerblue"		: $res="1E90FF"; break;
			case "firebrick"		: $res="B22222"; break;
			case "floralwhite"		: $res="FFFAF0"; break;
			case "forestgreen"		: $res="228B22"; break;
			case "fuchsia"			: $res="FF00FF"; break;
			case "gainsboro"		: $res="DCDCDC"; break;
			case "ghostwhite"		: $res="F8F8FF"; break;
			case "gold"				: $res="FFD700"; break;
			case "goldenrod"		: $res="DAA520"; break;
			case "gray"				: $res="808080"; break;
			case "green"			: $res="008000"; break;
			case "greenyellow"		: $res="ADFF2F"; break;
			case "honeydew"			: $res="F0FFF0"; break;
			case "hotpink"			: $res="FF69B4"; break;
			case "indianred"		: $res="CD5C5C"; break;
			case "indigo"			: $res="4B0082"; break;
			case "ivory"			: $res="FFFFF0"; break;
			case "khaki"			: $res="F0E68C"; break;
			case "lavender"			: $res="E6E6FA"; break;
			case "lavenderblush"	: $res="FFF0F5"; break;
			case "lawngreen"		: $res="7CFC00"; break;
			case "lemonchiffon"		: $res="FFFACD"; break;
			case "lightblue"		: $res="ADD8E6"; break;
			case "lightcoral"		: $res="F08080"; break;
			case "lightcyan"		: $res="E0FFFF"; break;
			case "lightgoldenrodyellow"	: $res="FAFAD2"; break;
			case "lightgreen"		: $res="90EE90"; break;
			case "lightgrey"		: $res="D3D3D3"; break;
			case "lightpink"		: $res="FFB6C1"; break;
			case "lightsalmon"		: $res="FFA07A"; break;
			case "lightseagreen"	: $res="20B2AA"; break;
			case "lightskyblue"		: $res="87CEFA"; break;
			case "lightslategray"	: $res="778899"; break;
			case "lightsteelblue"	: $res="B0C4DE"; break;
			case "lightyellow"		: $res="FFFFE0"; break;
			case "lime"				: $res="00FF00"; break;
			case "limegreen"		: $res="32CD32"; break;
			case "linen"			: $res="FAF0E6"; break;
			case "magenta"			: $res="FF00FF"; break;
			case "maroon"			: $res="800000"; break;
			case "mediumaquamarine"	: $res="66CDAA"; break;
			case "mediumblue"		: $res="0000CD"; break;
			case "mediumorchid"		: $res="BA55D3"; break;
			case "mediumpurple"		: $res="9370DB"; break;
			case "mediumseagreen"	: $res="3CB371"; break;
			case "mediumslateblue"	: $res="7B68EE"; break;
			case "mediumspringgreen": $res="00FA9A"; break;
			case "mediumturquoise"	: $res="48D1CC"; break;
			case "mediumvioletred"	: $res="C71585"; break;
			case "midnightblue"		: $res="191970"; break;
			case "mintcream"		: $res="F5FFFA"; break;
			case "mistyrose"		: $res="FFE4E1"; break;
			case "moccasin"			: $res="FFE4B5"; break;
			case "navajowhite"		: $res="FFDEAD"; break;
			case "navy"				: $res="000080"; break;
			case "oldlace"			: $res="FDF5E6"; break;
			case "olive"			: $res="808000"; break;
			case "olivedrab"		: $res="6B8E23"; break;
			case "orange"			: $res="FFA500"; break;
			case "orangered"		: $res="FF4500"; break;
			case "orchid"			: $res="DA70D6"; break;
			case "palegoldenrod"	: $res="EEE8AA"; break;
			case "palegreen"		: $res="98FB98"; break;
			case "paleturquoise"	: $res="AFEEEE"; break;
			case "palevioletred"	: $res="DB7093"; break;
			case "papayawhip"		: $res="FFEFD5"; break;
			case "peachpuff"		: $res="FFDAB9"; break;
			case "peru"				: $res="CD853F"; break;
			case "pink"				: $res="FFC0CB"; break;
			case "plum"				: $res="DDA0DD"; break;
			case "powderblue"		: $res="B0E0E6"; break;
			case "purple"			: $res="800080"; break;
			case "red"				: $res="FF0000"; break;
			case "rosybrown"		: $res="BC8F8F"; break;
			case "royalblue"		: $res="4169E1"; break;
			case "saddlebrown"		: $res="8B4513"; break;
			case "salmon"			: $res="FA8072"; break;
			case "sandybrown"		: $res="F4A460"; break;
			case "seagreen"			: $res="2E8B57"; break;
			case "seashell"			: $res="FFF5EE"; break;
			case "sienna"			: $res="A0522D"; break;
			case "silver"			: $res="C0C0C0"; break;
			case "skyblue"			: $res="87CEEB"; break;
			case "slateblue"		: $res="6A5ACD"; break;
			case "slategray"		: $res="708090"; break;
			case "snow"				: $res="FFFAFA"; break;
			case "springgreen"		: $res="00FF7F"; break;
			case "steelblue"		: $res="4682B4"; break;
			case "tan"				: $res="D2B48C"; break;
			case "teal"				: $res="008080"; break;
			case "thistle"			: $res="D8BFD8"; break;
			case "tomato"			: $res="FF6347"; break;
			case "turquoise"		: $res="40E0D0"; break;
			case "violet"			: $res="EE82EE"; break;
			case "wheat"			: $res="F5DEB3"; break;
			case "white"			: $res="FFFFFF"; break;
			case "whitesmoke"		: $res="F5F5F5"; break;
			case "yellow"			: $res="FFFF00"; break;
			case "yellowgreen"		: $res="9ACD32"; break;
			default					: $res=$color;	 break;	
			
		} 

		if($res{0}=="#")$res=substr($color,1);
		return $res;
}
?>