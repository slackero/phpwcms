<?php
/**
 * cmsGO!
 *
 * @author Pixels & Points GmbH <info@pixels-points.ch>
 * @copyright Copyright (c) 2002-2022, Pixels & Points GmbH
 * @license https://www.pixels-points.ch/cmsgo-license.html Pixels & Points cmsGO! license
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
