<?php
// Handle phpwcms Addresses
function sanitize_phone_number($text) {
    $text = str_replace('(0)', ' ', $text);
    $text = str_replace('/', ' ', $text);
    return str_replace('  ', ' ', $text);
}
function get_phone_link($number, $type='tel') {
    $number = sanitize_phone_number($number);
    return '<a href="' . $type . ':' . preg_replace('/[^\+0-9]/', '', $number) . '" class="phone-link">' . html($number) . '</a>';
}

$dealer = array();

$_continent_select  = isset($_POST['continent_filter']) ? xss_clean(clean_slweg($_POST['continent_filter'])) : ( isset($_GET['sar1']) ? xss_clean(clean_slweg($_GET['sar1'])) : 'EU' );
$_country_select    = isset($_POST['country_filter']) ? xss_clean(clean_slweg($_POST['country_filter'])) : ( isset($_GET['sar2']) ? xss_clean(clean_slweg($_GET['sar2'])) : 'DE' );
$_city_select       = isset($_POST['city_filter']) ? xss_clean(clean_slweg($_POST['city_filter'])) : '';
$_filter            = isset($_POST['filter']) ? xss_clean(clean_slweg($_POST['filter'])) : '';
$_filter_zip        = isset($_POST['filter_zip']) && $_POST['filter_zip'] !== '' ? clean_slweg($_POST['filter_zip']) : '';


// alle Adressen holen und entsprechend strukturiert zurückliefern
// 1) Kontinente
// 2) Länder
// 3) Stadt
// veränderte Logik bei phpwcms – hier läuft alles über die Länder
// eine Adresse kann auch Zuständigkeit für andere Länder haben
// deswegen zuerst einmal alle möglichen Länder holen
// benötigt wird:
// - detail_id
// - detail_text2 = alternative Länder
// - detail_country = Standardland
$result = _dbGet(
    'phpwcms_userdetail',
    'detail_id, detail_country, detail_text2',
    'detail_regkey='._dbEscape(PHPWCMS_ADDRESS_KEY).' AND detail_aktiv=1 AND detail_public=1'
);

if(isset($result[0])) {

    $countries = array();
    foreach($result as $country) {

        if(isset($countries[ $country['detail_country'] ])) {
            $countries[ $country['detail_country'] ][$country['detail_id']] = $country['detail_id'];
        } else {
            $countries[ $country['detail_country'] ] =array($country['detail_id'] => $country['detail_id']) ;
        }

        if($country['detail_text2']) {

            $country['detail_text2'] = convertStringToArray($country['detail_text2']);
            foreach($country['detail_text2'] as $alt) {

                if(isset($countries[ $alt ])) {
                    $countries[ $alt ][$country['detail_id']] = $country['detail_id'];
                } else {
                    $countries[ $alt ] =array($country['detail_id'] => $country['detail_id']) ;
                }

            }

        }
    }

    $sql  = 'SELECT * FROM '.DB_PREPEND.'phpwcms_userdetail pu ';
    $sql .= 'LEFT JOIN '.DB_PREPEND.'phpwcms_country pc ON pu.detail_country=pc.country_iso ';
    $sql .= "WHERE pu.detail_regkey="._dbEscape(PHPWCMS_ADDRESS_KEY)." AND ";
    $sql .= "pu.detail_aktiv=1 AND pu.detail_public=1 AND pu.detail_id IN (%s) ";
    $sql .= 'ORDER BY pu.detail_int2, pu.detail_zip, pu.detail_company';

    // Nur die tatsächlich benötigten Länderinformationen holen
    $where_countries = array();

    foreach(array_keys($countries) as $country) {
        $where_countries[] = _dbEscape($country);
    }
    $order_by_country_suffix = $phpwcms['default_lang'] === 'de' ? '_de' : '';
    $result = _dbGet(
        'phpwcms_country',
        '*',
        'country_iso IN ('.implode(',', $where_countries).')',
        '',
        'country_continent'.$order_by_country_suffix.', country_name'.$order_by_country_suffix
    );

    $data_row   = array();
    $data_title = array();
    $data_link  = array();

    foreach($result as $row) {

        $_con = $row['country_continent_code'];
        $_cou = $row['country_iso'];

        $_adr = _dbQuery(sprintf($sql, implode(',', $countries[$_cou])));

        foreach($_adr as $_city) {
            $_zip = empty($_city['detail_zip']) ? $_city['detail_city'] : $_city['detail_zip'];
            $data_row[ $_con ][ $_cou ][ $_zip ][] = $_city;
        }

        $data_link[ $_con . $_cou ] = array('sar1' => $_con, 'sar2' => $_cou);

        if($phpwcms['default_lang'] == 'de') {
            $data_title['continent_'.$_con] = $row['country_continent_de'];
            $data_title['country_'.$_cou]   = $row['country_name_de'];
        } else {
            $data_title['continent_'.$_con] = $row['country_continent'];
            $data_title['country_'.$_cou]   = $row['country_name'];
        }

    }

    // EU an den Anfang
    // DE an den Anfang

    if(isset($data_row['EU'])) {

        $_eu = $data_row['EU'];
        unset($data_row['EU']);
        $data_row = array('EU' => $_eu) + $data_row;

        if(isset($data_row['EU']['DE'])) {

            $_eu = $data_row['EU']['DE'];
            unset($data_row['EU']['DE']);
            $data_row['EU'] = array('DE' => $_eu) + $data_row['EU'];

        }

        unset($_eu);

    }

    $_con           = array();
    $_cou           = array();
    $_cit           = array();
    $_adr           = array();
    $city_count     = 0;
    $country_cur    = '';

    $_min_search_chars = 3;

    $c = 0;

    foreach($data_row as $key => $row) {

        $continent_name = html($data_title['continent_'.$key]);

        $_con[$c]  = '<option value="'.$key.'"';
        if($key == $_continent_select) {
            $_con[$c] .= ' selected="selected"';

            // Alle Länder durchlaufen
            $d = 0;
            foreach($row as $ckey => $crow) {

                $country_name = html($data_title['country_'.$ckey]);
                $_cou[$d]  = '      <option value="'.$ckey.'"';
                if($ckey == $_country_select) {
                    $_cou[$d] .= ' selected="selected"';

                    // Mitführen des aktuellen Landes als Filter, wenn nötig
                    // gilt für "alle Einträge anzeigen"
                    if(isset($_GET['sar2'])) {
                        $_filter = $data_title['country_'.$ckey];
                    }

                    $country_cur = $country_name;

                    // Alle Städte durchlaufen
                    $e = 0;
                    $city_count = count($crow);
                    foreach($crow as $dkey => $drow) {

                        $_adr = array_merge($_adr, $drow);

                    }

                }
                $_cou[$d] .= '>@@'.$country_name.'@@</option>';

                $d++;
            }

        }
        $_con[$c] .= '>@@'.$continent_name.'@@</option>';

        $c++;

    }

    $dealer[]   = '<div class="search-select" id="address">';
        $dealer[]   = '<form action="'.rel_url(array(), array('sar1', 'sar2')).'" method="post" class="my-3">';
            $dealer[]   = '<div class="row g-2  align-items-center">';

        // Select Menü Kontinent
        $dealer[]   = '<div class="col-auto d-flex flex-row">';
        $dealer[]   = ' <label class="col-form-label me-2">@@Kontinent@@</label>';
        $dealer[]   = ' <select class="form-select" name="continent_filter" id="continent_filter" onchange="this.form.submit()">';
        $dealer[]   = '     <option value="" style="font-style:italic"> - @@Select continent@@ - </option>';
        $dealer[]   = implode(LF, $_con);
        $dealer[]   = ' </select>';
        $dealer[]   = '</div>';

    if(count($_cou)) {

        // Select Menü Land
        $dealer[]   = '<div class="col-auto d-flex flex-row">';
        $dealer[]   = ' <label class="col-form-label me-2">@@Land@@</label>';
        $dealer[]   = ' <select class="form-select" name="country_filter" id="country_filter" onchange="this.form.submit()">';
        $dealer[]   = '     <option value="" style="font-style:italic"> - @@Select country@@ - </option>';
        $dealer[]   = implode(LF, $_cou);
        $dealer[]   = ' </select>';
        $dealer[]   = '</div>';

    }

    $_adr_count = count($_adr);

    // postcode area only for germany
    if($_adr_count && $_country_select == 'DE') {

        $_zip = '';
        foreach($_adr as $arow) {
            if($arow['detail_country'] == 'DE' && $arow['detail_varchar3']) {
                $_zip .= ', ' . $arow['detail_varchar3'];
            }
        }

        $_zip = $_zip == '' ? array() : convertStringToArray($_zip);

        if(count($_zip)) {

            sort($_zip);

            $dealer[] = '<div class="col-auto d-flex flex-row">';
            $dealer[] = '   <label class="col-form-label me-2 text-nowrap">@@Postcode area@@</label>';
            $dealer[] = '   <select class="form-select ziparea" name="filter_zip" onchange="this.form.submit()">';
            $dealer[] = '       <option value="">- @@Select postcode area@@ -</option>';

            foreach($_zip as $zip_area) {
                $dealer[] = '        <option value="'.$zip_area.'"'.($_filter_zip == $zip_area ? ' selected="selected"' : '').'>'.$zip_area.'</option>';
            }

            $dealer[] = '    </select>';
            $dealer[] = '</div>';

        }
    }

                // Submit Button
                $dealer[]   = '<div class="col-auto"><button type="submit" class="btn btn-primary">@@Search@@</button></div>';
            $dealer[]   = '</div>';
        $dealer[]   = '</form>';
    $dealer[]   = '</div>';


    if($_adr_count) {

        $result             = array();
        $maps               = array();
        $result_max         = count($_adr);
        $result_count       = 0;
        $related_contacts   = array();
        $result_contacts    = array();

        $result[]           = '<div id="dealer-results">';

        foreach($_adr as $_akey => $arow) {

            $arow['related_count']      = 0;
            $arow['detail_varchar3']    = $arow['detail_varchar3'] != '' ? convertStringToArray($arow['detail_varchar3']) : array();
            $arow['zip_code_count']     = count($arow['detail_varchar3']);

            // zip code check for germany only – continue if zip filter is defined address is not matching
            if($_country_select == 'DE' && $_filter_zip !== '' && !in_array($_filter_zip, $arow['detail_varchar3'])) {
                continue;
            }

            $result_count++;


            $result[] = '<div class="row">'; // 1
            $result[] = '    <div class="col-12 col-md-6 col-lg-4 my-3">';// 2 class = wenn Stars Partner
            $result[] = '        <div class="card '.( $arow['detail_varchar2'] == 1 ? 'branch-office' : 'phpwcms-office' ).'">'; // 3
            $result[] = '            <div class="card-header pb-0">'; // 4
            $result[] = '               <h3 class="mt-2 mb-0">'.html($arow['detail_company']).'</h3>';
            $result[] = '            </div>'; // 4
            $result[] = '            <div class="card-body">'; // 4

            if($arow['detail_varchar4']) {
                $result[] = '<h5>'.html(str_replace('Standort ', '@@Branch@@ ', $arow['detail_varchar4'])).'</h5>';
            }

            $address  = html($arow['detail_street']);
            $address  = trim($address . LF . html($arow['detail_add']) );
            $address  = trim($address . LF . html($arow['detail_zip'] . ' ' . $arow['detail_city']) );
            $address  = trim($address . LF . html($arow['detail_region'] ) );

            if($address != '') {

                $address = nl2br($address);

                $result[] = '';
                $result[] = '<p class="card-text">'. $address . '</p>';
                if($_filter) {
                    $result[] = html($data_title[ 'country_'.$arow['detail_country'] ]);
                }

            }

            $telcom = empty($arow['detail_fon']) ? '' : '<p class="mb-0"><i class="fa fa-phone fa-fw" aria-hidden="true"></i> ' . get_phone_link($arow['detail_fon']) . '</p>';
            if(!empty($arow['detail_fax'])) {
                $telcom = trim( $telcom . '<p class="mb-0"><i class="fa fa-fax fa-fw" aria-hidden="true"></i> ' . get_phone_link($arow['detail_fax'], 'fax') . '</p>' );
            }
            if(!empty($arow['detail_mobile'])) {
                $telcom = trim( $telcom . '<p class="mb-0"><i class="fa fa-mobile fa-fw" aria-hidden="true"></i> ' . get_phone_link($arow['detail_mobile']) . '</p>' );
            }
            if(is_valid_email($arow['detail_email'])) {
                $arow['detail_email'] = html($arow['detail_email']);
                $telcom = trim( $telcom . '<p class="mb-0"><i class="fa fa-envelope fa-fw" aria-hidden="true"></i> <a href="mailto:' . html($arow['detail_email']) . '">' . html($arow['detail_email']) . '</a></p>' );
            }
            if(!empty($arow['detail_website'])) {
                if(strpos(strtolower($arow['detail_website']), '://') === false) {
                    $arow['url'] = $arow['detail_website'];
                    $arow['detail_website'] = 'http://' . $arow['detail_website'];
                } else {
                    $arow['url'] = '';
                    list( , $arow['url']) = explode('://', $arow['detail_website'], 2);
                }
                $arow['detail_website'] = html($arow['detail_website']);
                $telcom = trim( $telcom . '<p class="mb-0"><i class="fa fa-link fa-fw" aria-hidden="true"></i> <a href="'.$arow['detail_website'].'" target="_blank">'.$arow['url'].'</a></p>' );
            }

            if($telcom != '') {
                $result[] = nl2br($telcom);
            }


            // select from contacts marked for inland and/or export and based on country
            if($_country_select == 'DE') {
                // inland only, means marked inland or marked nothing
                $contact_where = '(detail_int3=1 OR (detail_int3=0 AND detail_int4=0))';
            } else {
                // export only
                $contact_where = 'detail_int4=1';
            }

            // get contacts
            $contacts = _dbGet(
                'phpwcms_userdetail',
                'detail_varchar1, detail_varchar2, detail_lastname, detail_firstname, detail_fon, detail_fax, detail_mobile, detail_email, detail_title, detail_varchar3',
                'detail_aktiv=1 AND detail_regkey='._dbEscape(PHPWCMS_CONTACTS_KEY).' AND detail_int1='.$arow['detail_id'].' AND '.$contact_where,
                '',
                'detail_int2 DESC, detail_varchar1, detail_varchar2, detail_lastname'
            );

            $result[] = '           </div>'; // 4

            if(isset($contacts[0])) {

                $contact_js = true;


                /*
                if($_country_select == 'DE' && $arow['zip_code_count']) {
                    sort($arow['detail_varchar3'], SORT_STRING);
                    $result[] = '<h5>@@Postleitzahlbereich@@ '.implode(', ', $arow['detail_varchar3']).'</h5>';
                }
                */

                //$result_contacts[] = '       <div class="collapse" id="phpwcms-contact-section-'.$_akey.'"><div class="row">';
                foreach($contacts as $contact) {

                    $contact['head'] = '';
                    $contact['foot'] = '';
                    if($contact['detail_varchar1']) {
                        $contact['head'] .= '     <h5 class="department card-title mb-0">@@' . html($contact['detail_varchar1']) . '@@</h5>' . LF;
                    }
                    if($contact['detail_varchar2']) {
                        $contact['head'] .= '     <p class="function card-title">@@' . html($contact['detail_varchar2']) . '@@</p>' . LF;
                    }
                    $contact['head'] .= '         <p class="name card-text">' . html($contact['detail_firstname'].' '.$contact['detail_lastname']) . '</p>' . LF;
                    if($contact['detail_fon']) {
                        $contact['foot'] .= '     <p class="mb-0"><i class="fa fa-phone fa-fw" aria-hidden="true"></i> ' . get_phone_link($contact['detail_fon']) . '</p>' . LF;
                    }
                    if($contact['detail_mobile']) {
                        $contact['foot'] .= '     <p class="mb-0"><i class="fa fa-mobile fa-fw" aria-hidden="true"></i> ' . get_phone_link($contact['detail_mobile']) . '</p>' . LF;
                    }
                    if($contact['detail_fax']) {
                        $contact['foot'] .= '     <p class="mb-0"><i class="fa fa-fax fa-fw" aria-hidden="true"></i> ' . get_phone_link($contact['detail_fax'], 'fax') . '</p>' . LF;
                    }
                    if($contact['detail_email']) {
                        $contact['foot'] .= '     <p class="mb-0"><i class="fa fa-envelope fa-fw" aria-hidden="true"></i> <a href="mailto:'.html($contact['detail_email']).'" nofollow>@@Send email@@</a></p>' . LF;
                    }

                    if($contact['foot']) {
                        $contact['head'] .= '</div><div class="card-footer mt-auto">' . $contact['foot'];
                    }

                    if(!$contact['head']) {
                        continue;
                    }

                    $result_contacts[] = '   <div class="col-12 col-md-6 col-xl-4 my-3">';
                    $result_contacts[] = '       <div class="card h-100">';
                    $result_contacts[] = '           <div class="card-body">';

                    $result_contacts[] = $contact['head'];

                    $result_contacts[] = '           </div>';
                    $result_contacts[] = '       </div>';
                    $result_contacts[] = '   </div>';


                    // zip code check for contact
                    if($_country_select == 'DE' && $_filter_zip !== '' && $contact['detail_varchar3'] && strpos($contact['detail_varchar3'], $_filter_zip) !== false) {

                        $related_contacts[$arow['detail_id']]  = '<div class="col-12 col-md-6 my-3">';
                        $related_contacts[$arow['detail_id']] .= '  <div class="card h-100">' . LF;
                        $related_contacts[$arow['detail_id']] .= '      <div class="card-header"><h5>' . ($arow['related_count'] ? '&nbsp;' : 'Ihr Ansprechpartner vor Ort:') . '</h5></div>' . LF;
                        $related_contacts[$arow['detail_id']] .= '      <div class="card-body">';
                        $related_contacts[$arow['detail_id']] .= $contact['head'];
                        $related_contacts[$arow['detail_id']] .= '      </div>';
                        $related_contacts[$arow['detail_id']] .= '  </div>';
                        $related_contacts[$arow['detail_id']] .= '</div>';
                        $arow['related_count']++;

                    }

                }

                //$result_contacts[] = '</div></div>';

            }

            if(count($result_contacts)) { // && !isset($related_contacts[$arow['detail_id']])

                $result[] = '   <div class="card-footer pt-0 pb-3 mt-auto">';
                $result[] = '       <button type="button" class="btn btn-readmore-gray" data-bs-toggle="collapse" data-bs-target="#phpwcms-contact-section-'.$_akey.'">';
                $result[] = '           @@Show contact person@@';
                $result[] = '       </button>';
                $result[] = '   </div>';

            }

            /**
             * Render files
             */
            if(!empty($arow['detail_text3'])) {
                $arow['detail_text3'] = @json_decode($arow['detail_text3'], true);
            }

            if(isset($arow['detail_text3']['files']) && count($arow['detail_text3']['files'])) {

                $IS_NEWS_CP = true;

                // Set the var required to be able to render files using CP file rendering
                $value = array(
                    'files_direct_download' => 0, // Direct download or not, 0 recommend
                    'files_template' => 'default', // Set which file template should be used
                    'cnt_object' => array(
                        'cnt_files' => array(
                            'id' => $arow['detail_text3']['files'], // Take file IDs
                            'caption' => $arow['detail_text3']['descriptions'] // Take file descriptions
                        )
                    )
                );

                // include content part files renderer
                include PHPWCMS_ROOT.'/include/inc_front/content/cnt7.article.inc.php';

                if($news['files_result']) {
                    $result[] = '   <div class="card-footer pt-0 pb-3 mt-auto">';
                    $result[] = $news['files_result'];
                    $result[] = '   </div>';
                }

                unset($IS_NEWS_CP);
            }
            /**
             * End Render files
             */


            $result[] = '        </div>'; // 3
            $result[] = '    </div>'; // 2


            if(count($result_contacts)) {

                $result[] = '<div class="col-sm-6 col-xl-8">';

                if(isset($related_contacts[$arow['detail_id']])) {

                    $result[] = '<div class="row">';
                    $result[] = $related_contacts[$arow['detail_id']];
                    $result[] = '</div>';

                }

                $result[] = '<div class="collapse" id="phpwcms-contact-section-'.$_akey.'">';
                $result[] = '<div class="row">';
                $result[] = implode(LF, $result_contacts);
                $result[] = '</div>';
                $result[] = '</div>';

                $result[] = '</div>';

                $result_contacts = array();

            }

            $result[] = '</div>'; // 1 close outer row



            /*
            $arow['detail_float1'] = floatval($arow['detail_float1']);
            $arow['detail_float2'] = floatval($arow['detail_float2']);

            // Auf Google Maps prüfen
            if($arow['detail_float1'] != 0 && $arow['detail_float2'] != 0) {
                $maps[] = array(
                    'longitude' => $arow['detail_float2'],
                    'latitude'  => $arow['detail_float1'],
                    'zip'       => trim($arow['detail_zip'].' '.$arow['detail_city']),
                    'company'   => $arow['detail_company'],
                    'address'   => $address
                );
            }
            */
        }

        $result[] = '</div>';


        /*
        if(count($result_contacts)) {

            $result[] = implode(LF, $result_contacts);

        }
        */


        if($result_count) {
            $result = implode(LF, $result);

            /*
            foreach($related_contacts as $relid => $item) {
                $result = str_replace('<!-- Related Contacts '.$relid.' -->', $item, $result);
            }
            */

            $dealer[] = $result;
            $related_contacts = '';
        }

        /*
        if(count($maps)) {

            $icon_path = TEMPLATE_PATH.'img/';

            require_once( PHPWCMS_ROOT.'/include/inc_ext/GoogleMapsAPI/phpwcmsGoogleMapAPIv3.class.php' );

            // JavaScripts laden
            $block['custom_htmlhead']['google_maps1'] = '  <script src="//maps.googleapis.com/maps/api/js?sensor=false&amp;h1='.$phpwcms['default_lang'].'" type="text/javascript"></script>'; //key='.MAPS_API_KEY.'&amp;
            //$block['custom_htmlhead']['google_maps2'] = '  <script src="'.TEMPLATE_PATH.'inc_script/phpwcms/js/phpwcms.address.js" type="text/javascript"></script>';

            $map = new GoogleMapAPI('google_map');

            if($phpwcms['default_lang'] == 'de') {

                $map->browser_alert = '@@Leider ist Ihr Browser nicht kompatibel mit Google Maps.@@';
                $map->js_alert = '<b>Javascript muss aktiviert sein, um Google Maps nutzen zu k&ouml;nnen.</b>';

                $map_show_details = '@@Zeige Details für @@';

            } else {

                $map->browser_alert = '@@Unfortunately, your browser is not compatible with Google Maps.@@';
                $map->js_alert = '<b>JavaScript must be enabled to use Google Maps.</b>';

                $map_show_details = '@@Show details of @@';

            }


            $map->setAPIKey(MAPS_API_KEY);
            //$map->enableMooTools();
            $map->width     = '628px';
            $map->height    = '375px';
            $map->disableSidebar();
            $map->enablePointlist();
            //$map->disableTypeControls();
            $map->setZoomLevel(15);
            $map->setInfoWindowTrigger('mouseover');
            $map->document_root = PHPWCMS_ROOT.'/';

            foreach($maps as $row) {

                $jump_index = $map->addMarkerByCoords(
                    $row['longitude'],
                    $row['latitude'],
                    $row['zip'].', '.$row['company'],
                    '<b style="font-size:1.15em;">'.$row['company'].'</b><br />'.$row['address'],
                    $map_show_details.$row['company']
                );

                $map->addMarkerIcon($icon_path.'phpwcms-arrow.png',$icon_path.'phpwcms-arrow.shadow.png', 12, 30, 15, 15);

            }


            $block['custom_htmlhead']['maps'] = $map->getMapJS();

            $dealer[] = $map->getMap();

        }
        */

    } elseif( (isset($_POST['filter']) && strlen($_filter) >= $_min_search_chars) || !empty($_city_select)  || ($_filter_zip !== '' && !count($_adr))  ) {

        $dealer[] = '<p><strong>@@Leider existieren in unserem Datenbestand keine Informationen, <br />die auf Ihre Suchanfrage zutreffen.@@</strong></p>';

    }

    //$dealer[]   = '</div>';

    $dealer[]   = '<div class="visually-hidden">';
    foreach($data_link as $row) {
        //$dealer[] = LF.'    <!-- ' . $data_title[ 'continent_'.$row['sar1'] ] . ' / ' . $data_title[ 'country_'.$row['sar2'] ] . ' = ' . abs_url($row, array(), '', 'urlencode') . ' -->';
        $dealer[] = '   <a href="'.rel_url($row).'">@@Your phpwcms contacts in@@ ' . html($data_title[ 'continent_'.$row['sar1'] ] . ' > ' . $data_title[ 'country_'.$row['sar2'] ]) . '</a>';
    }
    $dealer[]   = '</div>';

} else {

    $dealer[]   = '<p>@@Derzeit sind keine Ansprechpartner im System hinterlegt. <br />Wir aktualisieren diese Informationen im Moment.@@</p>';

}

$content['all'] = str_replace('{PHPWCMS-ADDRESS-LOCATOR}', LF . implode(LF, $dealer), $content['all']);

//$block['css']['contacts.css'] = 'contacts.css';
