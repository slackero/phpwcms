<?php

$content['all'] .= LF.buildNavi().LF.str_repeat('<br />', 20);

$block['custom_htmlhead'][] = '
<style type="text/css">
/* ================================================================
This copyright notice must be untouched at all times.

The original version of this stylesheet and the associated (x)html
is available at http://www.cssplay.co.uk/menus/simple_vertical.html
Copyright (c) 2005-2007 Stu Nicholls. All rights reserved.
This stylesheet and the associated (x)html may be modified in any
way to fit your requirements.
=================================================================== */

/* Add a margin - for this demo only - and a relative position with a high z-index to make it appear over any element below */
#menu_container {
    margin:             0;
    position:           relative;
    height:             25px;
    z-index:            100;
}

/* Get rid of the margin, padding and bullets in the unordered lists */
#pmenu,
#pmenu ul {
    padding:            0;
    margin:             0;
    list-style-type:    none;
}

/* Set up the link size, color and borders */
#pmenu a,
#pmenu a:visited {
    display:            block;
    width:              120px;
    font-size:          11px;
    color:              #575757;
    height:             25px;
    line-height:        24px;
    text-decoration:    none;
    text-indent:        5px;
    border:             1px 0 1px 1px solid #000;
}

/* Set up the sub level borders */
#pmenu li ul li a,
#pmenu li ul li a:visited {
    border-width:       0 1px 1px 1px;
}
#pmenu li a.enclose,
#pmenu li a.enclose:visited {
    border-width:       1px;
}

/* Set up the list items */
#pmenu li {
    float:              left;
    background:         #dddddd;
}
#pmenu li ul li {
    background:         #bbbbbb;
}

/* For Non-IE browsers and IE7 */
#pmenu li:hover {
    position:           relative;
}
/* Make the hovered list color persist */
#pmenu li:hover > a,
#pmenu li:hover li > a {
    background:         #acacac;
    color:              #ffffff;
}
/* Set up the sublevel lists with a position absolute for flyouts and overrun padding. The transparent gif is for IE to work */
#pmenu li ul {
    display:            none;
}
/* For Non-IE and IE7 make the sublevels visible on list hover. This is all it needs */
#pmenu li:hover > ul {
    display:            block;
    position:           absolute;
    top:                -10px;
    left:               106px;
    padding:            10px 15px 15px 15px;
    background:         transparent url(img/leer.gif);
    width:              120px;
}
/* Position the first sub level beneath the top level liinks */
#pmenu > li:hover > ul {
    left:               -15px;
    top:                16px;
}

/* get rid of the table */
#pmenu table {
    position:           absolute;
    border-collapse:    collapse;
    top:                0;
    left:               0;
    z-index:            100;
    font-size:          1em;
}

/* For IE5.5 and IE6 give the hovered links a position relative and a change of background and foreground color. This is needed to trigger IE to show the sub levels */
* html #pmenu li a:hover {
    position:           relative;
    background:         #acacac;
    color:              #ffffff;
}

/* For accessibility of the top level menu when tabbing */
#pmenu li a:active,
#pmenu li a:focus {
    background:         #dfd7ca;
    color:              #c00;
}

/* Set up the pointers for the sub level indication */
#pmenu li.fly {
    background:         #dddddd url(http://www.cssplay.co.uk/menus/fly.gif) no-repeat right center;
}
#pmenu li.drop {
    background:         #dddddd url(http://www.cssplay.co.uk/menus/drop.gif) no-repeat right center;
}


/* This lot is for IE5.5 and IE6 ONLY and is necessary to make the sublevels appear */

/* change the drop down levels from display:none; to visibility:hidden; */
* html #pmenu li ul {
    visibility:         hidden;
    display:            block;
    position:           absolute;
    top:                -11px;
    left:               105px;
    padding:            10px 15px 15px 15px;
    background:         transparent url(img/leer.gif);
}

/* keep the third level+ hidden when you hover on first level link */
#pmenu li a:hover ul ul {
    visibility:         hidden;
}
/* keep the fourth level+ hidden when you hover on second level link */
#pmenu li a:hover ul a:hover ul ul {
    visibility:         hidden;
}
/* keep the fifth level hidden when you hover on third level link */
#pmenu li a:hover ul a:hover ul a:hover ul ul {
    visibility:         hidden;
}
/* keep the sixth level hidden when you hover on fourth level link */
#pmenu li a:hover ul a:hover ul a:hover ul a:hover ul ul {
    visibility:         hidden;
}

/* make the second level visible when hover on first level link and position it */
#pmenu li a:hover ul {
    visibility:         visible;
    left:               -15px;
    top:                14px;
    lef\t:              -16px;
    to\p:               15px;
}

/* make the third level visible when you hover over second level link and position it and all further levels */
#pmenu li a:hover ul a:hover ul {
    visibility:         visible;
    top:                -11px;
    left:               105px;
}
/* make the fourth level visible when you hover over third level link */
#pmenu li a:hover ul a:hover ul a:hover ul {
    visibility:         visible;
}
/* make the fifth level visible when you hover over fourth level link */
#pmenu li a:hover ul a:hover ul a:hover ul a:hover ul {
    visibility:         visible;
}
/* make the sixth level visible when you hover over fifth level link */
#pmenu li a:hover ul a:hover ul a:hover ul a:hover ul a:hover ul {
    visibility:         visible;
}
/* If you can see the pattern in the above IE5.5 and IE6 style then you can add as many sub levels as you like */

</style>
';



function buildNavi($start=0, $counter=0) {

    $t = array();

    $struct = getStructureChildData($start);

    if($counter == 0) {
        $last = count($struct) - 1;
    } else {
        $last = 0;
    }

    $x = 0;

    foreach($struct as $value) {

        //if( isset($GLOBALS['LEVEL_KEY'][ $value['acat_id'] ]) ) {

        /*  $p1 = ' path';
        } else {
            $s  = '';
            $p1 = '';
        }

        if($GLOBALS['content']['cat_id'] == $value['acat_id']) {
            $a1 = ' active';
            $a3 = 'active';
        } else {
            $a1 = $p1;
            $a3 = '';
        }
        */
        $s = buildNavi($value['acat_id'], $counter+1);
        if($s) {
            $g  = '<!--[if IE 7]><!--></a><!--<![endif]-->';
            $g .= $s;
            $g .= LF . str_repeat(' ', $counter);

            $class = $counter ? ' class="fly"' : ' class="drop"';

            $close_li = str_repeat('    ', $counter+1);

        } else {
            $g  = '</a>';
            $class = '';
            $close_li = '';
        }

        if( $last && $last == $x ) {
            $enclose = ' class="enclose"';
        } elseif( $x || ($counter == 0 && $x == 0) ) {
            $enclose = '';
        } else {
            $enclose = ' class="enclose"';
        }

        $l  = str_repeat('  ', $counter+1) . '<li'. $class . '>';
        $l .= get_level_ahref($value['acat_id'], $enclose) . html_specialchars($value['acat_name']);
        $l .= $g;



        $l .=  $close_li . '</li>';

        $t[] = $l;

        $x++;

    }

    if($counter) {
        $A = LF . str_repeat('  ', $counter) . '<!--[if lte IE 6]><table><tr><td><![endif]-->';
        $B = LF . str_repeat('  ', $counter) . '<!--[if lte IE 6]></td></tr></table></a><![endif]-->';
    } else {
        $A = '';
        $B = '';
    }


    $t = implode(LF, $t);
    if($t) {
        $t =    $A . LF . str_repeat('  ', $counter) .  '<ul'.($counter?'':' id="pmenu"').'>' . LF . $t . LF . str_repeat(' ', $counter) . '</ul>'. $B ;
    }

    return $t;

}
