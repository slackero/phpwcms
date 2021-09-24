<?php
/**
 * htmlfilter.inc
 * ---------------
 * This set of functions allows you to filter html in order to remove
 * any malicious tags from it. Useful in cases when you need to filter
 * user input for any cross-site-scripting attempts.
 *
 * Copyright (C) 2002-2004 by Duke University
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA
 * 02110-1301  USA
 *
 * @Author  Konstantin Riabitsev <icon@linux.duke.edu>
 * @Version 1.1 ($Date: 2005/06/30 18:06:08 $)
 */

/**
 * This is a debugging function used throughout the code. To enable
 * debugging you have to specify a global variable called "debug" before
 * calling sanitize() and set it to true.
 *
 * Note: Although insignificantly, debugging does slow you down even
 * when $debug is set to false. If you wish to get rid of all
 * debugging calls, run the following command:
 *
 * fgrep -v '//htmlfilter_debug("' htmlfilter.inc > htmlfilter.inc.new
 *
 * htmlfilter.inc.new will contain no debugging calls.
 *
 * @param  $message  A string with the message to output.
 * @return           void.
 */
function htmlfilter_debug($message){
	return NULL;
	/*
    global $debug;
    if ($debug == true){
        echo "$message";
    }
	*/
}

/**
 * This function returns the final tag out of the tag name, an array
 * of attributes, and the type of the tag. This function is called by
 * sanitize internally.
 *
 * @param  $tagname  the name of the tag.
 * @param  $attary   the array of attributes and their values
 * @param  $tagtype  The type of the tag (see in comments).
 * @return           a string with the final tag representation.
 */
function tagprint($tagname, $attary, $tagtype){
    $me = 'tagprint';
    if ($tagtype == 2){
        $fulltag = '</' . $tagname . '>';
    } else {
        $fulltag = '<' . $tagname;
        if (is_array($attary) && count($attary)) {
            foreach ($attary as $attname => $attvalue) {
                $fulltag .= ' ' . $attname . '=' . $attvalue;
            }
        }
        if ($tagtype == 3) {
            $fulltag .= ' /';
        }
        $fulltag .= '>';
    }
    //htmlfilter_debug("$me: $fulltag\n");
    return $fulltag;
}

/**
 * A small helper function to use with array_walk. Modifies a by-ref
 * value and makes it lowercase.
 *
 * @param  $val a value passed by-ref.
 * @return      void since it modifies a by-ref value.
 */
function casenormalize(&$val){
    $val = strtolower($val);
}

/**
 * This function skips any whitespace from the current position within
 * a string and to the next non-whitespace value.
 *
 * @param  $body   the string
 * @param  $offset the offset within the string where we should start
 *                 looking for the next non-whitespace character.
 * @return         the location within the $body where the next
 *                 non-whitespace char is located.
 */
function skipspace($body, $offset){
    $me = 'skipspace';
    if (preg_match('/^(\s*)/s', substr($body, $offset), $matches)) {
        $offset += strlen($matches[1]);
    }
    return $offset;
}

/**
 * This function looks for the next character within a string.  It's
 * really just a glorified "strpos", except it catches the failures
 * nicely.
 *
 * @param  $body   The string to look for needle in.
 * @param  $offset Start looking from this position.
 * @param  $needle The character/string to look for.
 * @return         location of the next occurance of the needle, or
 *                 strlen($body) if needle wasn't found.
 */
function findnxstr($body, $offset, $needle){
    $me = 'findnxstr';
    $pos = strpos($body, $needle, $offset);
    if ($pos === FALSE){
        $pos = strlen($body);
        //htmlfilter_debug("$me: end of body reached\n");
    }
    //htmlfilter_debug("$me: '$needle' found at pos $pos\n");
    return $pos;
}

/**
 * This function takes a PCRE-style regexp and tries to match it
 * within the string.
 *
 * @param  $body   The string to look for needle in.
 * @param  $offset Start looking from here.
 * @param  $reg    A PCRE-style regex to match.
 * @return         Returns a false if no matches found, or an array
 *                 with the following members:
 *                 - integer with the location of the match within $body
 *                 - string with whatever content between offset and the match
 *                 - string with whatever it is we matched
 */
function findnxreg($body, $offset, $reg){
    $me = 'findnxreg';
    $matches = array();
    $retarr = array();
    $preg_rule = '%^(.*?)(' . $reg . ')%s';
    $offset = intval($offset);
    if(preg_match($preg_rule, substr($body, $offset), $matches)) {
        $retarr[0] = $offset + strlen($matches[1]);
        $retarr[1] = $matches[1];
        $retarr[2] = $matches[2];
        //htmlfilter_debug("$me: '$reg' found at pos $offset matching '".$matches[2]."'\n");
    } else {
        //htmlfilter_debug("$me: No matches found.\n");
        $retarr = false;
    }
    return $retarr;
}

/**
 * This function looks for the next tag.
 *
 * @param  $body   String where to look for the next tag.
 * @param  $offset Start looking from here.
 * @return         false if no more tags exist in the body, or
 *                 an array with the following members:
 *                 - string with the name of the tag
 *                 - array with attributes and their values
 *                 - integer with tag type (1, 2, or 3)
 *                 - integer where the tag starts (starting "<")
 *                 - integer where the tag ends (ending ">")
 *                 first three members will be false, if the tag is invalid.
 */
function getnxtag($body, $offset){
    $me = 'getnxtag';
    if ($offset > strlen($body)){
        //htmlfilter_debug("$me: Past the end of body\n");
        return false;
    }
    $lt = findnxstr($body, $offset, '<');
    if ($lt == strlen($body)){
        //htmlfilter_debug("$me: No more tags found!\n");
        return false;
    }
    /**
     * We are here:
     * blah blah <tag attribute="value">
     * \---------^
     */
    //htmlfilter_debug("$me: Found '<' at pos $lt\n");
    $pos = skipspace($body, $lt + 1);
    if ($pos >= strlen($body)){
        //htmlfilter_debug("$me: End of body reached.\n");
        return array(false, false, false, $lt, strlen($body));
    }
    /**
     * There are 3 kinds of tags:
     * 1. Opening tag, e.g.:
     *    <a href="blah">
     * 2. Closing tag, e.g.:
     *    </a>
     * 3. XHTML-style content-less tag, e.g.:
     *    <img src="blah"/>
     */
    $tagtype = false;
    switch (substr($body, $pos, 1)){
    case '/':
        //htmlfilter_debug("$me: This is a closing tag (type 2)\n");
        $tagtype = 2;
        $pos++;
        break;
    case '!':
        /**
         * A comment or an SGML declaration.
         */
        if (substr($body, $pos+1, 2) == '--'){
            //htmlfilter_debug("$me: A comment found. Stripping.\n");
            $gt = strpos($body, '-->', $pos);
            if ($gt === false){
                $gt = strlen($body);
            } else {
                $gt += 2;
            }
            return array(false, false, false, $lt, $gt);
        } else {
            //htmlfilter_debug("$me: An SGML declaration found. Stripping.\n");
            $gt = findnxstr($body, $pos, '>');
            return array(false, false, false, $lt, $gt);
        }
        break;
    default:
        /**
         * Assume tagtype 1 for now. If it's type 3, we'll switch values
         * later.
         */
        $tagtype = 1;
        break;
    }

    $tag_start = $pos;
    $tagname = '';
    /**
     * Look for next [\W-_], which will indicate the end of the tag name.
     */
    $regary = findnxreg($body, $pos, '[^\w\-_]');
    if ($regary == false){
        //htmlfilter_debug("$me: End of body reached while analyzing tag name\n");
        return array(false, false, false, $lt, strlen($body));
    }
    list($pos, $tagname, $match) = $regary;
    $tagname = strtolower($tagname);

    /**
     * $match can be either of these:
     * '>'  indicating the end of the tag entirely.
     * '\s' indicating the end of the tag name.
     * '/'  indicating that this is type-3 xhtml tag.
     *
     * Whatever else we find there indicates an invalid tag.
     */
    switch ($match){
    case '/':
        /**
         * This is an xhtml-style tag with a closing / at the
         * end, like so: <img src="blah"/>. Check if it's followed
         * by the closing bracket. If not, then this tag is invalid
         */
        if (substr($body, $pos, 2) == '/>'){
            //htmlfilter_debug("$me: XHTML-style tag found.\n");
            $pos++;
            //htmlfilter_debug("$me: Setting tagtype to 3\n");
            $tagtype = 3;
        } else {
            //htmlfilter_debug("$me: Found invalid character '/'.\n");
            $gt = findnxstr($body, $pos, '>');
            //htmlfilter_debug("$me: Tag is invalid. Returning.\n");
            return array(false, false, false, $lt, $gt);
        }
    case '>':
        //htmlfilter_debug("$me: End of tag found at $pos\n");
        //htmlfilter_debug("$me: Tagname is '$tagname'\n");
        //htmlfilter_debug("$me: This tag has no attributes\n");
        return array($tagname, false, $tagtype, $lt, $pos);
        break;
    default:
        /**
         * Check if it's whitespace
         */
        if (preg_match('/\s/', $match)){
            //htmlfilter_debug("$me: Tagname is '$tagname'\n");
        } else {
            /**
             * This is an invalid tag! Look for the next closing ">".
             */
            //htmlfilter_debug("$me: Invalid characters found in tag name: $match\n");
            $gt = findnxstr($body, $lt, '>');
            return array(false, false, false, $lt, $gt);
        }
    }

    /**
     * At this point we're here:
     * <tagname  attribute='blah'>
     * \-------^
     *
     * At this point we loop in order to find all attributes.
     */
    $attname = '';
    $atttype = false;
    $attary = array();

    while ($pos <= strlen($body)){
        $pos = skipspace($body, $pos);
        if ($pos == strlen($body)){
            /**
             * Non-closed tag.
             */
            //htmlfilter_debug("$me: End of body reached before end of tag. Discarding.\n");
            return array(false, false, false, $lt, $pos);
        }
        /**
         * See if we arrived at a ">" or "/>", which means that we reached
         * the end of the tag.
         */
        $matches = array();
        if(preg_match('%^(\s*)(>|/>)%s', substr($body, $pos), $matches)) {
            /**
             * Yep. So we did.
             */
            //htmlfilter_debug("$me: Arrived at the end of the tag.\n");
            $pos += strlen($matches[1]);
            if ($matches[2] == '/>'){
                $tagtype = 3;
                $pos++;
            }
            return array($tagname, $attary, $tagtype, $lt, $pos);
        }

        /**
         * There are several types of attributes, with optional
         * [:space:] between members.
         * Type 1:
         *   attrname[:space:]=[:space:]'CDATA'
         * Type 2:
         *   attrname[:space:]=[:space:]"CDATA"
         * Type 3:
         *   attr[:space:]=[:space:]CDATA
         * Type 4:
         *   attrname
         *
         * We leave types 1 and 2 the same, type 3 we check for
         * '"' and convert to "&quot" if needed, then wrap in
         * double quotes. Type 4 we convert into:
         * attrname="yes".
         */
        $regary = findnxreg($body, $pos, '[^\w\-_]');
        if ($regary == false){
            /**
             * Looks like body ended before the end of tag.
             */
            //htmlfilter_debug("$me: End of body found before end of tag.\n");
            //htmlfilter_debug("$me: Invalid, returning\n");
            return array(false, false, false, $lt, strlen($body));
        }
        list($pos, $attname, $match) = $regary;
        $attname = strtolower($attname);
        //htmlfilter_debug("$me: Attribute '$attname' found\n");
        /**
         * We arrived at the end of attribute name. Several things possible
         * here:
         * '>'  means the end of the tag and this is attribute type 4
         * '/'  if followed by '>' means the same thing as above
         * '\s' means a lot of things -- look what it's followed by.
         *      anything else means the attribute is invalid.
         */
        switch($match){
        case '/':
            /**
             * This is an xhtml-style tag with a closing / at the
             * end, like so: <img src="blah"/>. Check if it's followed
             * by the closing bracket. If not, then this tag is invalid
             */
            if (substr($body, $pos, 2) == '/>'){
                //htmlfilter_debug("$me: This is an xhtml-style tag.\n");
                $pos++;
                //htmlfilter_debug("$me: Setting tagtype to 3\n");
                $tagtype = 3;
            } else {
                //htmlfilter_debug("$me: Found invalid character '/'.\n");
                $gt = findnxstr($body, $pos, '>');
                //htmlfilter_debug("$me: Tag is invalid. Returning.\n");
                return array(false, false, false, $lt, $gt);
            }
        case '>':
            //htmlfilter_debug("$me: found type 4 attribute.\n");
            //htmlfilter_debug("$me: Additionally, end of tag found at $pos\n");
            //htmlfilter_debug("$me: Attname is '$attname'\n");
            //htmlfilter_debug("$me: Setting attvalue to 'yes'\n");
            $attary[$attname] = '"yes"';
            return array($tagname, $attary, $tagtype, $lt, $pos);
            break;
        default:
            /**
             * Skip whitespace and see what we arrive at.
             */
            $pos = skipspace($body, $pos);
            $char = substr($body, $pos, 1);
            /**
             * Two things are valid here:
             * '=' means this is attribute type 1 2 or 3.
             * \w means this was attribute type 4.
             * anything else we ignore and re-loop. End of tag and
             * invalid stuff will be caught by our checks at the beginning
             * of the loop.
             */
            if ($char == '='){
                //htmlfilter_debug("$me: Attribute type 1, 2, or 3 found.\n");
                $pos++;
                $pos = skipspace($body, $pos);
                /**
                 * Here are 3 possibilities:
                 * "'"  attribute type 1
                 * '"'  attribute type 2
                 * everything else is the content of tag type 3
                 */
                $quot = substr($body, $pos, 1);
                if ($quot == '\''){
                    //htmlfilter_debug("$me: In fact, this is attribute type 1\n");
                    //htmlfilter_debug("$me: looking for closing quote\n");
                    $regary = findnxreg($body, $pos+1, '\'');
                    if ($regary == false){
                        //htmlfilter_debug("$me: end of body reached before end of val\n");
                        //htmlfilter_debug("$me: Returning\n");
                        return array(false, false, false, $lt, strlen($body));
                    }
                    list($pos, $attval, $match) = $regary;
                    //htmlfilter_debug("$me: Attvalue is '$attval'\n");
                    $pos++;
                    $attary[$attname] = '\'' . $attval . '\'';
                } else if ($quot == '"'){
                    //htmlfilter_debug("$me: In fact, this is attribute type 2\n");
                    //htmlfilter_debug("$me: looking for closing quote\n");
                    $regary = findnxreg($body, $pos+1, '\"');
                    if ($regary == false){
                        //htmlfilter_debug("$me: end of body reached before end of val\n");
                        //htmlfilter_debug("$me: Returning\n");
                        return array(false, false, false, $lt, strlen($body));
                    }
                    list($pos, $attval, $match) = $regary;
                    //htmlfilter_debug("$me: Attvalue is \"$attval\"\n");
                    $pos++;
                    $attary[$attname] = '"' . $attval . '"';
                } else {
                    //htmlfilter_debug("$me: This looks like attribute type 3\n");
                    /**
                     * These are hateful. Look for \s, or >.
                     */
                    //htmlfilter_debug("$me: Looking for end of attval\n");
                    $regary = findnxreg($body, $pos, '[\s>]');
                    if ($regary == false){
                        //htmlfilter_debug("$me: end of body reached before end of val\n");
                        //htmlfilter_debug("$me: Returning\n");
                        return array(false, false, false, $lt, strlen($body));
                    }
                    list($pos, $attval, $match) = $regary;
                    /**
                     * If it's ">" it will be caught at the top.
                     */
                    //htmlfilter_debug("$me: translating '\"' into &quot;\n");
                    $attval = preg_replace('/\"/s', '&quot;', $attval);
                    //htmlfilter_debug("$me: wrapping in quotes\n");
                    $attary[$attname] = '"' . $attval . '"';
                }
            } else if (preg_match('|[\w/>]|', $char)) {
                /**
                 * That was attribute type 4.
                 */
                //htmlfilter_debug("$me: attribute type 4 found.\n");
                //htmlfilter_debug("$me: Setting value to 'yes'\n");
                $attary[$attname] = '"yes"';
            } else {
                /**
                 * An illegal character. Find next '>' and return.
                 */
                //htmlfilter_debug("$me: illegal character '$char' found.\n");
                //htmlfilter_debug("$me: returning\n");
                $gt = findnxstr($body, $pos, '>');
                return array(false, false, false, $lt, $gt);
            }
        }
    }
    /**
     * The fact that we got here indicates that the tag end was never
     * found. Return invalid tag indication so it gets stripped.
     */
    //htmlfilter_debug("$me: No tag end found\n");
    return array(false, false, false, $lt, strlen($body));
}

/**
 * Translates entities into literal values so they can be checked.
 *
 * @param $attvalue the by-ref value to check.
 * @param $regex    the regular expression to check against.
 * @param $hex      whether the entites are hexadecimal.
 * @return          True or False depending on whether there were matches.
 */
function deent(&$attvalue, $regex, $hex=false){
    $me = 'deent';
    //htmlfilter_debug("$me: matching '$regex' against: $attvalue\n");
    $ret_match = false;
    if(preg_match_all($regex, $attvalue, $matches)){
        //htmlfilter_debug("$me: found " . sizeof($matches[0]) . " matches\n");
        $repl = array();
        for ($i = 0; $i < sizeof($matches[0]); $i++){
            $numval = $matches[1][$i];
            //htmlfilter_debug("$me: numval is $numval\n");
            if ($hex){
                $numval = hexdec($numval);
                //htmlfilter_debug("$me: hex! Numval is now $numval\n");
            }
            $repl[$matches[0][$i]] = chr($numval);
        }
        $attvalue = strtr($attvalue, $repl);
        //htmlfilter_debug("$me: attvalue after translation: $attvalue\n");
        return true;
    } else {
        //htmlfilter_debug("$me: no matches! Returning false.\n");
        return false;
    }
}

/**
 * This function checks attribute values for entity-encoded values
 * and returns them translated into 8-bit strings so we can run
 * checks on them.
 *
 * @param  $attvalue A string to run entity check against.
 * @return           Nothing, modifies a reference value.
 */
function defang(&$attvalue){
    $me = 'defang';
    /**
     * Skip this if there aren't ampersands or backslashes.
     */
    //htmlfilter_debug("$me: Checking '$attvalue' for suspicious content\n");
    if (strpos($attvalue, '&') === false
        && strpos($attvalue, '\\') === false){
        //htmlfilter_debug("$me: no suspicious content found, returning.\n");
        return;
    }
    $m = false;
    do {
        $m = false;
        $m = $m || deent($attvalue, '/\&#0*(\d+);*/s');
        $m = $m || deent($attvalue, '/\&#x0*((\d|[a-f])+);*/si', true);
        $m = $m || deent($attvalue, '/\\\\(\d+)/s', true);
        //htmlfilter_debug("$me: m=$m\n");
    } while ($m == true);
    $attvalue = stripslashes($attvalue);
    //htmlfilter_debug("$me: translated into: $attvalue\n");
}

/**
 * Kill any tabs, newlines, or carriage returns. Our friends the
 * makers of the browser with 95% market value decided that it'd
 * be funny to make "java[tab]script" be just as good as "javascript".
 *
 * @param  attvalue  The attribute value before extraneous spaces removed.
 * @return attvalue  Nothing, modifies a reference value.
 */
function unspace(&$attvalue){
    $me = 'unspace';
    if (strcspn($attvalue, "\t\r\n\0 ") != strlen($attvalue)){
        //htmlfilter_debug("$me: Killing whitespace.\n");
        $attvalue = str_replace(array("\t", "\r", "\n", "\0", " "),
                                array('',   '',   '',   '',   ''), $attvalue);
    }
    //htmlfilter_debug("$me: after unspace: $attvalue\n");
}

/**
 * This function runs various checks against the attributes.
 *
 * @param  $tagname         String with the name of the tag.
 * @param  $attary          Array with all tag attributes.
 * @param  $rm_attnames     See description for sanitize
 * @param  $bad_attvals     See description for sanitize
 * @param  $add_attr_to_tag See description for sanitize
 * @return                  Array with modified attributes.
 */
function fixatts($tagname,
                 $attary,
                 $rm_attnames,
                 $bad_attvals,
                 $add_attr_to_tag
                 ){
    $me = 'fixatts';
    //htmlfilter_debug("$me: Fixing attributes\n");
    if (is_array($attary) && count($attary)) {
        foreach ($attary as $attname => $attvalue) {
            /**
             * See if this attribute should be removed.
             */
            foreach ($rm_attnames as $matchtag=>$matchattrs){
                if (preg_match($matchtag, $tagname)){
                    foreach ($matchattrs as $matchattr){
                        if (preg_match($matchattr, $attname)){
                            //htmlfilter_debug("$me: Attribute '$attname' defined as bad.\n");
                            //htmlfilter_debug("$me: Removing.\n");
                            unset($attary[$attname]);
                            continue;
                        }
                    }
                }
            }
            /**
             * Remove any backslashes, entities, or extraneous whitespace.
             */
            defang($attvalue);
            unspace($attvalue);

            /**
             * Now let's run checks on the attvalues.
             * I don't expect anyone to comprehend this. If you do,
             * get in touch with me so I can drive to where you live and
             * shake your hand personally. :)
             */
            foreach ($bad_attvals as $matchtag=>$matchattrs){
                if (preg_match($matchtag, $tagname)){
                    foreach ($matchattrs as $matchattr=>$valary){
                        if (preg_match($matchattr, $attname)){
                            /**
                             * There are two arrays in valary.
                             * First is matches.
                             * Second one is replacements
                             */
                            list($valmatch, $valrepl) = $valary;
                            $newvalue = preg_replace($valmatch,$valrepl,$attvalue);
                            if ($newvalue != $attvalue){
                                //htmlfilter_debug("$me: attvalue is now $newvalue\n");
                                $attary[$attname] = $newvalue;
                            }
                        }
                    }
                }
            }
        }
    }
    /**
     * See if we need to append any attributes to this tag.
     */
    foreach ($add_attr_to_tag as $matchtag=>$addattary){
        if (preg_match($matchtag, $tagname)){
            $attary = array_merge($attary, $addattary);
            //htmlfilter_debug("$me: Added attributes to this tag\n");
        }
    }
    return $attary;
}

/**
 * This is the main function and the one you should actually be calling.
 * There are several variables you should be aware of an which need
 * special description.
 *
 * $tag_list
 * ----------
 * This is a simple one-dimentional array of strings, except for the
 * very first one. The first member should be einter false or true.
 * In case it's FALSE, the following list will be considered a list of
 * tags that should be explicitly REMOVED from the body, and all
 * others that did not match the list will be allowed.  If the first
 * member is TRUE, then the list is the list of tags that should be
 * explicitly ALLOWED -- any tag not matching this list will be
 * discarded.
 *
 * Examples:
 * $tag_list = array(
 *                   false,
 *                   "blink",
 *                   "link",
 *		     "object",
 *		     "meta",
 *                   "marquee",
 *                   "html"
 *		            );
 *
 * This will allow all tags except for blink, link, object, meta, marquee,
 * and html.
 *
 * $tag_list = array(
 *                   true,
 *                   "b",
 *                   "a",
 *                   "i",
 *                   "img",
 *                   "strong",
 *                   "em",
 *                   "p"
 *                  );
 *
 * This will remove all tags from the body except b, a, i, img, strong, em and
 * p.
 *
 * $rm_tags_with_content
 * ---------------------
 * This is a simple one-dimentional array of strings, which specifies the
 * tags to be removed with any and all content between the beginning and
 * the end of the tag.
 * Example:
 * $rm_tags_with_content = array(
 *                               "script",
 *                               "style",
 *                               "applet",
 *                               "embed"
 *                              );
 *
 * This will remove the following structure:
 * <script>
 *  window.alert("Isn't cross-site-scripting fun?!");
 * </script>
 *
 * $self_closing_tags
 * ------------------
 * This is a simple one-dimentional array of strings, which specifies which
 * tags contain no content and should not be forcefully closed if this option
 * is turned on (see further).
 * Example:
 * $self_closing_tags =  array(
 *                             "img",
 *                             "br",
 *                             "hr",
 *                             "input"
 *                            );
 *
 * $force_tag_closing
 * ------------------
 * Set it to true to forcefully close any tags opened within the document.
 * This is good if you want to take care of people who like to screw up
 * the pages by leaving unclosed tags like <a>, <b>, <i>, etc.
 *
 * $rm_attnames
 * -------------
 * Now we come to parameters that are more obscure. This parameter is
 * a nested array which is used to specify which attributes should be
 * removed. It goes like so:
 *
 * $rm_attnames = array(
 *   "PCRE regex to match tag name" =>
 *     array(
 *           "PCRE regex to match attribute name"
 *           )
 *   );
 *
 * Example:
 * $rm_attnames = array(
 *   "|.*|" =>
 *     array(
 *           "|target|i",
 *           "|^on.*|i"
 *          )
 *   );
 *
 * This will match all attributes (.*), and specify that all attributes
 * named "target" and starting with "on" should be removed. This will take
 * care of the following problem:
 * <em onmouseover="window.alert('muahahahaha')">
 * The "onmouseover" will be removed.
 *
 * $bad_attvals
 * ------------
 * This is where it gets ugly. This is a nested array with many levels.
 * It goes like so:
 *
 * $bad_attvals = array(
 *   "pcre regex to match tag name" =>
 *     array(
 *           "pcre regex to match attribute name" =>
 *             array(
 *                   "pcre regex to match attribute value"
 *                  )
 *             array(
 *                   "pcre regex replace a match from above with"
 *                  )
 *          )
 *   );
 *
 * An extensive example:
 *
 * $bad_attvals = array(
 *   "|.*|" =>
 *      array(
 *            "/^src|background|href|action/i" =>
 *                array(
 *                      array(
 *                            "/^([\'\"])\s*\S+script\s*:.*([\'\"])/si"
 *                            ),
 *                      array(
 *                            "\\1http://veryfunny.com/\\2"
 *                            )
 *                      ),
 *            "/^style/i" =>
 *                array(
 *                      array(
 *                            "/expression/si",
 *                            "/url\(([\'\"])\s*https*:.*([\'\"])\)/si",
 *                            "/url\(([\'\"])\s*\S+script:.*([\'\"])\)/si"
 *                           ),
 *                      array(
 *                            "idiocy",
 *                            "url(\\1http://veryfunny.com/\\2)",
 *                            "url(\\1http://veryfynny.com/\\2)"
 *                           )
 *                      )
 *            )
 *  );
 *
 * This will take care of nearly all known cross-site scripting exploits,
 * plus some (see my filter sample at
 * http://www.mricon.com/html/phpfilter.html for a working version).
 *
 * $add_attr_to_tag
 * ----------------
 * This is a useful little feature which lets you add attributes to
 * certain tags. It is a nested array as well, but not at all like
 * the previous one. It goes like so:
 *
 * $add_attr_to_tag = array(
 *   "PCRE regex to match tag name" =>
 *     array(
 *           "attribute name"=>'"attribute value"'
 *          )
 *   );
 *
 * Note: don't forget quotes around attribute value.
 *
 * Example:
 *
 * $add_attr_to_tag = array(
 *   "/^a$/si" =>
 *     array(
 *           'target'=>'"_new"'
 *          )
 *   );
 *
 * This will change all <a> tags and add target="_new" to them so all links
 * open in a new window.
 *
 *
 *
 * @param $body                 the string with HTML you wish to filter
 * @param $tag_list             see description above
 * @param $rm_tags_with_content see description above
 * @param $self_closing_tags    see description above
 * @param $force_tag_closing    see description above
 * @param $rm_attnames          see description above
 * @param $bad_attvals          see description above
 * @param $add_attr_to_tag      see description above
 * @return                      sanitized html safe to show on your pages.
 */
function htmlfilter_sanitize($body,
                  $tag_list = array(),
                  $rm_tags_with_content = array(),
                  $self_closing_tags = array(),
                  $force_tag_closing = true,
                  $rm_attnames = array(),
                  $bad_attvals = array(),
                  $add_attr_to_tag = array()
                  ){
    $me = 'sanitize';
    /**
     * Normalize rm_tags and rm_tags_with_content.
     */
    @array_walk($tag_list, 'casenormalize');
    @array_walk($rm_tags_with_content, 'casenormalize');
    @array_walk($self_closing_tags, 'casenormalize');
    /**
     * See if tag_list is of tags to remove or tags to allow.
     * false  means remove these tags
     * true   means allow these tags
     */
    $rm_tags = array_shift($tag_list);
    $curpos = 0;
    $open_tags = array();
    $trusted = ''; //"<!-- begin sanitized html -->\n";
    $skip_content = false;
    /**
     * Take care of netscape's stupid javascript entities like
     * &{alert('boo')};
     */
    $body = preg_replace('/&(\{.*?\};)/si', '&amp;\\1', trim($body));
    //htmlfilter_debug("$me: invoking the loop\n");
    while (($curtag = getnxtag($body, $curpos)) != FALSE){
        list($tagname, $attary, $tagtype, $lt, $gt) = $curtag;
        //htmlfilter_debug("$me: grabbing free-standing content\n");
        $free_content = substr($body, $curpos, $lt - $curpos);
        //htmlfilter_debug("$me: " . strlen($free_content) . " chars grabbed\n");
        if ($skip_content == false){
            //htmlfilter_debug("$me: appending free content to trusted.\n");
            $trusted .= $free_content;
        } else {
            //htmlfilter_debug("$me: Skipping free content.\n");
        }
        if ($tagname != FALSE){
            //htmlfilter_debug("$me: tagname is '$tagname'\n");
            if ($tagtype == 2){
                //htmlfilter_debug("$me: This is a closing tag\n");
                if ($skip_content == $tagname){
                    /**
                     * Got to the end of tag we needed to remove.
                     */
                    //htmlfilter_debug("$me: Finished removing tag with content\n");
                    $tagname = false;
                    $skip_content = false;
                } else {
                    if ($skip_content == false){
                        if (isset($open_tags[$tagname]) && $open_tags[$tagname] > 0) {
                            //htmlfilter_debug("$me: popping '$tagname' from open_tags\n");
                            $open_tags[$tagname]--;
                        } else {
                            //htmlfilter_debug("$me: '$tagname' was never opened\n");
                            //htmlfilter_debug("$me: removing\n");
                            $tagname = false;
                        }
                    } else {
                        //htmlfilter_debug("$me: Skipping this tag\n");
                    }
                }
            } else {
                /**
                 * $rm_tags_with_content
                 */
                if ($skip_content == false){
                    /**
                     * See if this is a self-closing type and change
                     * tagtype appropriately.
                     */
                    if ($tagtype == 1
                        && in_array($tagname, $self_closing_tags)){
                        //htmlfilter_debug("$me: Self-closing tag. Changing tagtype.\n");
                        $tagtype = 3;
                    }
                    /**
                     * See if we should skip this tag and any content
                     * inside it.
                     */
                    if ($tagtype == 1
                        && in_array($tagname, $rm_tags_with_content)){
                        //htmlfilter_debug("$me: removing this tag with content\n");
                        $skip_content = $tagname;
                    } else {
                        if (($rm_tags == false
                             && in_array($tagname, $tag_list)) ||
                            ($rm_tags == true
                             && !in_array($tagname, $tag_list))){
                            //htmlfilter_debug("$me: Removing this tag.\n");
                            $tagname = false;
                        } else {
                            if ($tagtype == 1){
                                //htmlfilter_debug("$me: adding '$tagname' to open_tags\n");
                                if (isset($open_tags[$tagname])){
                                    $open_tags[$tagname]++;
                                } else {
                                    $open_tags[$tagname] = 1;
                                }
                            }
                            /**
                             * This is where we run other checks.
                             */
                            if (is_array($attary) && sizeof($attary) > 0){
                                $attary = fixatts($tagname,
                                                  $attary,
                                                  $rm_attnames,
                                                  $bad_attvals,
                                                  $add_attr_to_tag);
                            }
                        }
                    }
                } else {
                    //htmlfilter_debug("$me: Skipping this tag\n");
                }
            }
            if ($tagname != false && $skip_content == false){
                //htmlfilter_debug("$me: Appending tag to trusted.\n");
                $trusted .= tagprint($tagname, $attary, $tagtype);
            }
        } else {
            //htmlfilter_debug("$me: Removing invalid tag\n");
        }
        $curpos = $gt + 1;
    }
    //htmlfilter_debug("$me: Appending any leftover content\n");
    $trusted .= substr($body, $curpos, strlen($body) - $curpos);
    if ($force_tag_closing == true){
        foreach ($open_tags as $tagname=>$opentimes){
            while ($opentimes > 0){
                //htmlfilter_debug("$me: '$tagname' left open. Closing by force.\n");
                $trusted .= '</' . $tagname . '>';
                $opentimes--;
            }
        }
        $trusted .= " ";
    }
    // $trusted .= "<!-- end sanitized html -->\n";
    return $trusted;
}
