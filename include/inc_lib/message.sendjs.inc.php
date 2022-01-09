<?php
/**
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 **/


$body_onload = ' onload="opt.init(document.forms[0])"';

$BE['HEADER']['optionselect.js']	 = getJavaScriptSourceLink('include/inc_js/optionselect.js');
$BE['HEADER']['message']			 = JS_START;
$BE['HEADER']['message']			.= 'var opt = new OptionTransfer("msg_send_to","msg_send_list");'.LF;
$BE['HEADER']['message']			.= 'opt.setAutoSort(true);'.LF;
$BE['HEADER']['message']			.= 'opt.setDelimiter(":");'.LF;
$BE['HEADER']['message']			.= 'opt.saveNewLeftOptions("msg_send_receiver");';
$BE['HEADER']['message']			.= JS_END;
