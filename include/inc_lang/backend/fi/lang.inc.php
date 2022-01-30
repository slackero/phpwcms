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


// Language: Finnish, Country Code: fi
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'

// translated by: Marko Hannula (marko@kimifan.com)

$BL['usr_online']                       = 'Kirjautuneet k&auml;ytt&auml;j&auml;t';

// Login Page
$BL["login_text"]                       = 'T&auml;yt&auml; sis&auml;&auml;nkirjautumiseen vaadittavat tiedot';
$BL['login_error']                      = 'Virhe kirjauduttaessa j&auml;rjestelm&auml;&auml;n!';
$BL["login_username"]                   = 'K&auml;ytt&auml;j&auml;nimi';
$BL["login_userpass"]                   = 'Salasana';
$BL["login_button"]                     = 'Kirjaudu';
$BL["login_lang"]                       = 'Ty&ouml;tilan kieli';

// phpwcms.php
$BL['be_nav_logout']                    = 'POISTU';
$BL['be_nav_articles']                  = 'ARTIKKELIT';
$BL['be_nav_files']                     = 'TIEDOSTOT';
$BL['be_nav_modules']                   = 'MODUULIT';
$BL['be_nav_messages']                  = 'VIESTIT';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFIILI';
$BL['be_nav_admin']                     = 'YLL&Auml;PITO';
$BL['be_nav_discuss']                   = 'KESKUSTELU';

$BL['be_page_title']                    = 'Yll&auml;pidon k&auml;ytt&ouml;liittym&auml; (hallinnan ty&ouml;tila)';

$BL['be_subnav_article_center']         = 'Artikkelien hallinta';
$BL['be_subnav_article_new']            = 'Uusi artikkeli';
$BL['be_subnav_file_center']            = 'Tiedostojen hallinta';
$BL['be_subnav_file_ftptakeover']       = 'Ftp-lataus';
$BL['be_subnav_mod_artists']            = 'Artisti, kategoria, tyyli';
$BL['be_subnav_msg_center']             = 'Viestikeskus';
$BL['be_subnav_msg_new']                = 'Uusi viesti';
$BL['be_subnav_msg_newsletter']         = 'Uutisviestin tilaus';
$BL['be_subnav_chat_main']              = 'Chat-p&auml;&auml;sivu';
$BL['be_subnav_chat_internal']          = 'Sis&auml;inen chat';
$BL['be_subnav_profile_login']          = 'K&auml;ytt&auml;j&auml;tiedot';
$BL['be_subnav_profile_personal']       = 'Henkil&ouml;kohtaiset tiedot';
$BL['be_subnav_admin_pagelayout']       = 'Ulkoasun muotoilupohjat';
$BL['be_subnav_admin_templates']        = 'Rakenteen tyylipohja';
$BL['be_subnav_admin_css']              = 'Oletus tyylim&auml;&auml;rittely (CSS)';
$BL['be_subnav_admin_sitestructure']    = 'Sivuston rakenne';
$BL['be_subnav_admin_users']            = 'K&auml;ytt&auml;jien hallinta';
$BL['be_subnav_admin_filecat']          = 'Tiedostojen kategoriat';

// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'Artikkelin tunniste';
$BL['be_func_struct_preview']           = 'Esikatselu';
$BL['be_func_struct_edit']              = 'Muokkaa artikkelia';
$BL['be_func_struct_sedit']             = 'Muokkaa hakemistorakennetta';
$BL['be_func_struct_cut']               = 'Leikkaa';
$BL['be_func_struct_nocut']             = 'Poista LEIKKAA k&auml;yt&ouml;st&auml;';
$BL['be_func_struct_svisible']          = 'N&auml;kyv&auml; / n&auml;kym&auml;t&ouml;n';
$BL['be_func_struct_spublic']           = 'Julkinen / rajoitettu';
$BL['be_func_struct_sort_up']           = 'Siirr&auml; yl&ouml;s';
$BL['be_func_struct_sort_down']         = 'Siirr&auml; alas';
$BL['be_func_struct_del_article']       = 'Poista artikkeli';
$BL['be_func_struct_del_jsmsg']         = 'Haluatko varmasti poistaa \nkyseisen artikkelin?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'Luo uusi artikkelin hakemistorakenteen t&auml;h&auml;n kohtaan';
$BL['be_func_struct_paste_article']     = 'Liit&auml; artikkeli hakemistorakenteeseen';
$BL['be_func_struct_insert_level']      = 'Lis&auml;&auml; taso hakemistorakenteeseen';
$BL['be_func_struct_paste_level']       = 'Liit&auml; hakemistorakenteeseen';
$BL['be_func_struct_cut_level']         = 'Leikkaa hakemistorakenteesta';
$BL['be_func_struct_no_cut']            = "Liittäminen ei mahdollista!";
$BL['be_func_struct_no_paste1']         = "Liittäminen ei mahdollista!";
$BL['be_func_struct_no_paste2']         = 'Liittäminen ei mahdollista!';
$BL['be_func_struct_no_paste3']         = 'Liit&auml;';
$BL['be_func_struct_paste_cancel']      = 'Peruuta';
$BL['be_func_struct_del_struct']        = 'Poista hakemistorakenne';
$BL['be_func_struct_del_sjsmsg']        = 'Poista hakemistorakenne?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'Avaa';
$BL['be_func_struct_close']             = 'Sulje';
$BL['be_func_struct_empty']             = 'Tyhj&auml;';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'Teksti';
$BL['be_ctype_html']                    = 'HTML';
$BL['be_ctype_code']                    = 'Koodi';
$BL['be_ctype_textimage']               = 'Teksti ja kuva';
$BL['be_ctype_images']                  = 'Kuvaluettelo/galleria';
$BL['be_ctype_bulletlist']              = 'Lista';
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';
$BL['be_ctype_link']                    = 'Linkki &amp; s&auml;hk&ouml;posti';
$BL['be_ctype_linklist']                = 'Linkkilista';
$BL['be_ctype_linkarticle']             = 'Linkki artikkeliin';
$BL['be_ctype_multimedia']              = 'Multimedia';
$BL['be_ctype_filelist']                = 'Tiedostolista';
$BL['be_ctype_emailform']               = 'S&auml;hk&ouml;postilomake';
$BL['be_ctype_newsletter']              = 'Uutisviesti';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profiili luotiin onnistuneesti.';
$BL['be_profile_create_error']          = 'Lis&auml;yst&auml; tallennettaessa tapahtui peruuttamaton virhe.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Profiilin tiedot p&auml;ivitettiin onnistuneesti.';
$BL['be_profile_update_error']          = 'Muutosta tallennettaessa tapahtui peruuttamaton virhe.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'K&auml;ytt&auml;tunnus {VAL} on virheellinen';
$BL['be_profile_account_err2']          = 'Salasana on liian lyhyt (vain {VAL} : salasanan tulee olla v&auml;hint&auml;&auml;n viisi (5) merkki&auml; pitk&auml;)';
$BL['be_profile_account_err3']          = 'Salasanojen tulee olla identtiset';
$BL['be_profile_account_err4']          = 'S&auml;hk&ouml;postiosoite {VAL} on virheellinen';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'Henkil&ouml;kohtaiset tietosi';
$BL['be_profile_data_text']             = 'Henkil&ouml;kohtaiset tiedot eiv&auml;t ole pakollisia. Lis&auml;&auml;m&auml;ll&auml; nimesi, yhteystietosi, allekirjoituksesi sek&auml; muut mielenkiinnon kohteesi sivuston yll&auml;pit&auml;jien saataville, parannat mm. sivuston hallinnan toimivuutta. Valitsemalla valintaruudusta voit jakaa tietosi my&ouml;s julkisesti kirjoitustesi yhteydess&auml; sivuston kaikkille lukijoille. (valinnainen).';
$BL['be_profile_label_title']           = 'Titteli';
$BL['be_profile_label_firstname']       = 'Etunimi';
$BL['be_profile_label_name']            = 'Sukunimi';
$BL['be_profile_label_company']         = 'Yritys';
$BL['be_profile_label_street']          = 'Osoite';
$BL['be_profile_label_city']            = 'Kaupunki';
$BL['be_profile_label_state']           = 'Provinssi / l&auml;&auml;ni';
$BL['be_profile_label_zip']             = 'Postinumero';
$BL['be_profile_label_country']         = 'Valtio';
$BL['be_profile_label_phone']           = 'Puhelin';
$BL['be_profile_label_fax']             = 'Fax';
$BL['be_profile_label_cellphone']       = 'Matkapuhelin';
$BL['be_profile_label_signature']       = 'Allekirjoitus';
$BL['be_profile_label_notes']           = 'Muuta';
$BL['be_profile_label_profession']      = 'Ammatti';
$BL['be_profile_label_newsletter']      = 'Uutisviesti';
$BL['be_profile_text_newsletter']       = 'Haluan vastaanottaa ajoittaisen uutisviestin s&auml;hk&ouml;postiini.';
$BL['be_profile_label_public']          = 'Julkinen';
$BL['be_profile_text_public']           = 'My&ouml;s sivuston lukijat voivat tarkistella profiiliani.';
$BL['be_profile_label_button']          = 'P&auml;ivit&auml;';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'Henkil&ouml;kohtaiset kirjautumistiedot';
$BL['be_profile_account_text']          = 'Normaalisti k&auml;ytt&auml;tunnuksen vaihtaminen ei ole tarpeellista.<br />Sen sijaan salasanan vaihtaminen riitt&auml;vin aikav&auml;lein parantaa tietoturvallisuutta.';
$BL['be_profile_label_err']             = 'Tarkista';
$BL['be_profile_label_username']        = 'K&auml;ytt&auml;j&auml;tunnus';
$BL['be_profile_label_newpass']         = 'Uusi salasana';
$BL['be_profile_label_repeatpass']      = 'Toista uusi salasana';
$BL['be_profile_label_email']           = 'S&auml;hk&ouml;postiosoite';
$BL['be_profile_account_button']        = 'P&auml;ivit&auml; tiedot';
$BL['be_profile_label_lang']            = 'Kieli';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'Lataa tiedoston ftp:n kautta';
$BL['be_ftptakeover_mark']              = 'Merkitse';
$BL['be_ftptakeover_available']         = 'Saatavilla olevat tiedostot';
$BL['be_ftptakeover_size']              = 'Koko';
$BL['be_ftptakeover_nofile']            = 'Yht&auml;&auml;n tiedostoa ei ole viel&auml; saatavilla &#8211; lataa tiedostoja k&auml;ytt&auml;en ftp:t&auml;';
$BL['be_ftptakeover_all']               = 'KAIKKI';
$BL['be_ftptakeover_directory']         = 'Hakemisto';
$BL['be_ftptakeover_rootdir']           = 'Juurihakemisto';
$BL['be_ftptakeover_needed']            = 'Pakollinen!!! (valitse yksi)';
$BL['be_ftptakeover_optional']          = 'Vapaaehtoinen';
$BL['be_ftptakeover_keywords']          = 'Avainsanat';
$BL['be_ftptakeover_additional']        = 'Lis&auml;tiedot';
$BL['be_ftptakeover_longinfo']          = 'Pitk&auml; kuvaus';
$BL['be_ftptakeover_status']            = 'Status';
$BL['be_ftptakeover_active']            = 'Aktiivinen';
$BL['be_ftptakeover_public']            = 'Julkinen';
$BL['be_ftptakeover_createthumb']       = 'Luo hakemistokuva';
$BL['be_ftptakeover_button']            = 'Valitse seuraavat tiedostot';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'Tiedostojen hallinta';
$BL['be_ftab_createnew']                = 'Luo uusi kansio juurihakemistoon';
$BL['be_ftab_paste']                    = 'Liit&auml; tiedosto leikep&ouml;d&auml;lt&auml; juurihakemistoon';
$BL['be_ftab_enablethumb']              = 'N&auml;yt&auml; kansioiden sis&auml;lt&auml;m&auml;t kuvat';
$BL['be_ftab_disablethumb']             = '&Auml;l&auml; n&auml;yt&auml; kansioiden sis&auml;lt&auml;mi&auml; kuvia';
$BL['be_ftab_private']                  = 'Yksityiset&nbsp;tiedostot';
$BL['be_ftab_public']                   = 'Julkiset&nbsp;tiedostot';
$BL['be_ftab_search']                   = 'Etsi';
$BL['be_ftab_trash']                    = 'Roskakori';
$BL['be_ftab_open']                     = 'Avaa kaikki kansiot';
$BL['be_ftab_close']                    = 'Sulje kaikki avoimet kansiot';
$BL['be_ftab_upload']                   = 'Lataa tiedosto juurihakemistoon';
$BL['be_ftab_filehelp']                 = 'APUA';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'Juurihakemisto';
$BL['be_fpriv_title']                   = 'Luo uusi kansio';
$BL['be_fpriv_inside']                  = 'Sis&auml;ll&auml;';
$BL['be_fpriv_error']                   = 'Virhe: nime&auml; kansio';
$BL['be_fpriv_name']                    = 'Nimi';
$BL['be_fpriv_status']                  = 'Status';
$BL['be_fpriv_button']                  = 'Luo kansio';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'Muokkaa kansiota';
$BL['be_fpriv_newname']                 = 'Uusi nimi';
$BL['be_fpriv_updatebutton']            = 'P&auml;ivit&auml; kansion tiedot';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Valitse palvelimelle ladattava tiedosto';
$BL['be_fprivup_err2']                  = 'Ladattavan tiedoston koko ylitt&auml;&auml;';
$BL['be_fprivup_err3']                  = 'Virhe lis&auml;tt&auml;ess&auml; tiedostoa tietokantaan';
$BL['be_fprivup_err4']                  = 'Virhe hakemistoa luotaessa.';
$BL['be_fprivup_err5']                  = 'Hakemistokuvaa ei l&ouml;ydy';
$BL['be_fprivup_err6']                  = 'VIRHE! &Auml;l&auml; yrit&auml; toimenpidett&auml; uudestaan - kyseess&auml; on palvelinongelma! Ota yhteytt&auml; <a href="mailto:{VAL}">sivuston yll&auml;pitoon</a> mahdollisimman pian!';
$BL['be_fprivup_title']                 = 'Lataa tiedostot';
$BL['be_fprivup_button']                = 'Lataa tiedostot';
$BL['be_fprivup_upload']                = 'Lataa';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'Muokkaa tiedoston tietoja';
$BL['be_fprivedit_filename']            = 'Tiedostonimi';
$BL['be_fprivedit_created']             = 'Luotu';
$BL['be_fprivedit_dateformat']          = 'm.d.Y H:i';
$BL['be_fprivedit_err1']                = 'proof name of file (set back to original)';
$BL['be_fprivedit_clockwise']           = 'K&auml;&auml;nn&auml; hakemistokuvaa my&ouml;t&auml;p&auml;iv&auml;&auml;n [alkuper&auml;inen asemointi +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'K&auml;&auml;nn&auml; hakemistokuvaa vastap&auml;iv&auml;&auml;n [alkuper&auml;inen asemointi -90&deg;]';
$BL['be_fprivedit_button']              = 'P&auml;ivit&auml; tiedoston tiedot';
$BL['be_fprivedit_size']                = 'Koko';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'Lataa tiedosto hakemistoon';
$BL['be_fprivfunc_makenew']             = 'Luo uusi alihakemisto';
$BL['be_fprivfunc_paste']               = 'Liit&auml; leikep&ouml;yd&auml;n tiedosto kansioon';
$BL['be_fprivfunc_edit']                = 'Muokkaa hakemistoa';
$BL['be_fprivfunc_cactive']             = 'Muuta aktiiviseksi/passiiviseksi';
$BL['be_fprivfunc_cpublic']             = 'Muuta julkiseksi/yksityiseksi';
$BL['be_fprivfunc_deldir']              = 'Poista kansio';
$BL['be_fprivfunc_jsdeldir']            = 'Haluatko todella \npoistaa hakemiston';
$BL['be_fprivfunc_notempty']            = 'Hakemisto {VAL} ei ole tyhj&auml;!';
$BL['be_fprivfunc_opendir']             = 'Avaa hakemisto';
$BL['be_fprivfunc_closedir']            = 'Sulje hakemisto';
$BL['be_fprivfunc_dlfile']              = 'Lataa tiedosto';
$BL['be_fprivfunc_clipfile']            = 'Leikep&ouml;yt&auml;tiedosto';
$BL['be_fprivfunc_cutfile']             = 'Leikkaa';
$BL['be_fprivfunc_editfile']            = 'Muokkaa tiedoston tietoja';
$BL['be_fprivfunc_cactivefile']         = 'Muuta aktiiviseksi/passiiviseksi';
$BL['be_fprivfunc_cpublicfile']         = 'Muuta julkiseksi/yksityiseksi';
$BL['be_fprivfunc_movetrash']           = 'Siirr&auml; roskakoriin';
$BL['be_fprivfunc_jsmovetrash1']        = 'Haluatko todella siirt&auml;&auml; tiedoston';
$BL['be_fprivfunc_jsmovetrash2']        = 'roskakoriin?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'Yksityisi&auml; tiedostoja tai kansioita ei l&ouml;ytynyt';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'K&auml;ytt&auml;j&auml;';
$BL['be_fpublic_nofiles']               = 'Julkisia tiedostoja tai kansioita ei l&ouml;ytynyt';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'Roskakori on tyhj&auml;';
$BL['be_ftrash_show']                   = 'N&auml;yt&auml; yksityiset tiedostot';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Haluatko palauttaa {VAL} \nyksityislistalle?';
$BL['be_ftrash_delete']                 = 'Haluatko poistaa {VAL}?';
$BL['be_ftrash_undo']                   = 'Palauta (kumoa poista)';
$BL['be_ftrash_delfinal']               = 'Lopullinen poistaminen';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'Hakutermej&auml; ei ole annettu.';
$BL['be_fsearch_title']                 = 'Etsi tiedostoista';
$BL['be_fsearch_infotext']              = 'T&auml;m&auml; on perushaku, jolla haetaan hakusanoja vastaavia<br />tiedostoja ja tiedostokuvauksia. Asteriskin k&auml;ytt&ouml;&auml; ei tueta. Erota hakusanat toisistaan<br />v&auml;lily&ouml;nnill&auml;. Valitse JA/TAI ja mit&auml; tiedostoja haluat etsi&auml;: julkisia/yksityisi&auml;.';
$BL['be_fsearch_nonfound']              = 'Hakutermej&auml; vastaavia tietoja ei l&ouml;ytynyt. Muuta tarvittaessa hakutermej&auml;!';
$BL['be_fsearch_fillin']                = 'Ole hyv&auml; ja anna ainakin yksi hakutermi yll&auml;olevaan kentt&auml;&auml;n.';
$BL['be_fsearch_searchlabel']           = 'Hakutermit';
$BL['be_fsearch_startsearch']           = 'Aloita haku';
$BL['be_fsearch_and']                   = 'JA';
$BL['be_fsearch_or']                    = 'TAI';
$BL['be_fsearch_all']                   = 'Kaikki tiedostot';
$BL['be_fsearch_personal']              = 'Yksityinen';
$BL['be_fsearch_public']                = 'Julkinen';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'Yll&auml;pidon chat';
$BL['be_chat_info']                     = 'T&auml;&auml;ll&auml; voit chattailla muiden sivuston yll&auml;pitoon osallistuvien kesken. Aihe on vapaa. Keskustelu on reaaliaikainen, mutta sitä voi hy&ouml;dynt&auml;&auml; my&ouml;s offline-ty&ouml;skentelyss&auml; esimerkiksi jakamalla ty&ouml;teht&auml;vi&auml; yll&auml;pitoon osallistuvien kesken.';
$BL['be_chat_start']                    = 'Liity keskusteluun t&auml;st&auml;';
$BL['be_chat_lines']                    = 'Viestien lukum&auml;&auml;r&auml;';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'Viestikeskus';
$BL['be_msg_new']                       = 'Saapuneet';
$BL['be_msg_old']                       = 'Luetut';
$BL['be_msg_senttop']                   = 'L&auml;hetetyt';
$BL['be_msg_del']                       = 'Poistetut';
$BL['be_msg_from']                      = 'L&auml;hett&auml;j&auml;';
$BL['be_msg_subject']                   = 'Aihe';
$BL['be_msg_date']                      = 'PVM / aika';
$BL['be_msg_close']                     = 'Sulje';
$BL['be_msg_create']                    = 'L&auml;het&auml; uusi viesti';
$BL['be_msg_reply']                     = 'Vastaa';
$BL['be_msg_move']                      = 'Siirr&auml; roskakoriin';
$BL['be_msg_unread']                    = 'Uudet tai lukemattomat viestit';
$BL['be_msg_lastread']                  = 'Viimeisimm&auml;t luetut viestit {VAL}';
$BL['be_msg_lastsent']                  = 'Viimeisimm&auml;t l&auml;hetetyt viesiti {VAL}';
$BL['be_msg_marked']                    = 'Poistettavaksi merkityt viestit (trash)';
$BL['be_msg_nomsg']                     = 'Kansio ei sis&auml;ll&auml; yht&auml;&auml;n viesti&auml;';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'VS';
$BL['be_msg_by']                        = 'L&auml;hett&auml;j&auml;';
$BL['be_msg_on']                        = '';
$BL['be_msg_msg']                       = 'Viesti';
$BL['be_msg_err1']                      = 'Unohdit laittaa vastaanottajan...';
$BL['be_msg_err2']                      = 'T&auml;yt&auml; aihekentt&auml; (vastaanottajan on helpompi luokitella viestisi)';
$BL['be_msg_err3']                      = 'T&auml;yt&auml; viestin runko';
$BL['be_msg_sent']                      = 'Viesti l&auml;hetettiin onnistuneesti!';
$BL['be_msg_fwd']                       = 'Sinut ohjataan viestikeskukseen tai';
$BL['be_msg_newmsgtitle']               = 'Kirjoita uusi viesti';
$BL['be_msg_err']                       = 'Viesti&auml; l&auml;hetett&auml;ess&auml; tapahtui peruuttamaton virhe';
$BL['be_msg_sendto']                    = 'Valitut vastaanottajat';
$BL['be_msg_available']                 = 'Lista mahdollisista vastaanottajista';
$BL['be_msg_all']                       = 'L&auml;het&auml; viesti kaikille valituille';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'Uutisviestin tilaukset';
$BL['be_newsletter_titleedit']          = 'Muokkaa uutisviestitilauksia';
$BL['be_newsletter_new']                = 'Luo uusi';
$BL['be_newsletter_add']                = 'Lis&auml;&auml;&nbsp;uutisviesti&nbsp;tilaus';
$BL['be_newsletter_name']               = 'Nimi';
$BL['be_newsletter_info']               = 'Tiedot';
$BL['be_newsletter_button_save']        = 'Tallenna tilaus';
$BL['be_newsletter_button_cancel']      = 'Peruuta';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'K&auml;ytt&auml;j&auml;tunnus on virheellinen, suorita uusi valinta';
$BL['be_admin_usr_err2']                = 'K&auml;ytt&auml;j&auml;tunnus-kentt&auml; on tyhj&auml; (pakollinen)';
$BL['be_admin_usr_err3']                = 'Salasana-kentt&auml; on tyhj&auml; (pakollinen)';
$BL['be_admin_usr_err4']                = "S&auml;hk&ouml;postiosoite on virheellinen";
$BL['be_admin_usr_err']                 = 'Virhe';
$BL['be_admin_usr_mailsubject']         = 'Tervetuloa PHPWCMS:n k&auml;ytt&ouml;liittym&auml;an';
$BL['be_admin_usr_mailbody']            = "TERVETULOA PHPWCMS:N k&auml;ytt&ouml;liittym&auml;AN\n\n    K&auml;ytt&auml;j&auml;tunnus: {LOGIN}\n    salasana: {PASSWORD}\n\n\nVoit kirjautua t&auml;st&auml; sivuston: {LOGIN_PAGE}\n\nsivuston yll&auml;pidon k&auml;ytt&ouml;liittym&auml;&auml;n\n ";
$BL['be_admin_usr_title']               = 'Lis&auml;&auml; uusi k&auml;ytt&auml;j&auml;tili';
$BL['be_admin_usr_realname']            = 'Oikea nimi';
$BL['be_admin_usr_setactive']           = 'Aseta k&auml;ytt&auml;j&auml; aktiiviseksi';
$BL['be_admin_usr_iflogin']             = 'K&auml;ytt&auml;j&auml; voi kirjautua palveluun vasta kun valintaruutu on valittuna';
$BL['be_admin_usr_isadmin']             = 'P&auml;&auml;k&auml;ytt&auml;j&auml;oikeudet';
$BL['be_admin_usr_ifadmin']             = 'Jos valittuna, k&auml;ytt&auml;j&auml;lle my&ouml;nnet&auml;&auml;n yll&auml;pidon p&auml;&auml;k&auml;ytt&auml;j&auml;n oikeudet';
$BL['be_admin_usr_verify']              = 'Vahvistus';
$BL['be_admin_usr_sendemail']           = 'L&auml;het&auml; viesti k&auml;ytt&auml;j&auml;tilin luomisesta k&auml;ytt&auml;j&auml;n s&auml;hk&ouml;postiin ';
$BL['be_admin_usr_button']              = 'Tallenna k&auml;ytt&auml;j&auml;tilin tiedot';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'Muokkaa k&auml;ytt&auml;j&auml;tili&auml;';
$BL['be_admin_usr_emailsubject']        = 'K&auml;ytt&auml;j&auml;tilin tietojen muutokset tallennettu';
$BL['be_admin_usr_emailbody']           = "PHPWCMS K&Auml;YTT&Auml;J&Auml;TILIN TIEDOT TALLENNETTU ONNISTUNEESTI\n\n    k&auml;ytt&auml;j&auml;tunnus: {LOGIN}\n    salasana: {PASSWORD}\n\n\nVoit kirjautua t&auml;st&auml; sivuston: {LOGIN_PAGE}\n\nsivuston yll&auml;pidon k&auml;ytt&ouml;liittym&auml;&auml;n\n ";
$BL['be_admin_usr_passnochange']        = '[MUUTOKSIA EI TALLENNETTU - V&Auml;&Auml;r&Auml; SALASANA]';
$BL['be_admin_usr_ebutton']             = 'P&auml;ivit&auml; k&auml;ytt&auml;j&auml;tilin tiedot';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'K&auml;ytt&auml;j&auml;tilien listaus';
$BL['be_admin_usr_ldel']                = 'HUOMIO!&#13T&auml;m&auml; poistaa k&auml;ytt&auml;j&auml;tilin pysyv&auml;sti';
$BL['be_admin_usr_create']              = 'Luo uusi k&auml;ytt&auml;j&auml;tili';
$BL['be_admin_usr_editusr']             = 'Muokkaa k&auml;ytt&auml;j&auml;tili&auml;';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'Sivuston rakenne';
$BL['be_admin_struct_child']            = '(lapsi)';
$BL['be_admin_struct_index']            = 'Etusivu (sivuston p&auml;&auml;sivu)';
$BL['be_admin_struct_cat']              = 'Kategorian otsikko';
$BL['be_admin_struct_hide1']            = 'Piilota';
$BL['be_admin_struct_hide2']            = 'N&auml;ytet&auml;&auml;n&nbsp;valikossa';
$BL['be_admin_struct_info']             = 'Kategorian aputeksti';
$BL['be_admin_struct_template']         = 'Tyylipohja';
$BL['be_admin_struct_alias']            = 'Kategorian alias';
$BL['be_admin_struct_visible']          = 'N&auml;kyv&auml;';
$BL['be_admin_struct_button']           = 'Tallenna tiedot';
$BL['be_admin_struct_close']            = 'Sulje';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'Tiedostoluokat';
$BL['be_admin_fcat_err']                = 'Tiedostoluokkaa ei ole nimetty!';
$BL['be_admin_fcat_name']               = 'Luokan nimi';
$BL['be_admin_fcat_needed']             = 'Pakollinen';
$BL['be_admin_fcat_button1']            = 'P&auml;vit&auml;';
$BL['be_admin_fcat_button2']            = 'Luo';
$BL['be_admin_fcat_delmsg']             = 'Haluatko varmasti\npoistaa tiedostotunnuksen?';
$BL['be_admin_fcat_fcat']               = 'Tiedostoluokka';
$BL['be_admin_fcat_err1']               = 'Tiedostotunnusta ei ole nimetty!';
$BL['be_admin_fcat_fkeyname']           = 'Tiedostotunnuksen nimitys';
$BL['be_admin_fcat_exit']               = 'Poista muokkaustilasta';
$BL['be_admin_fcat_addkey']             = 'Lis&auml;&auml; uusi tiedostotunnus';
$BL['be_admin_fcat_editcat']            = 'Muokkaa tiedostoluokan nime&auml;';
$BL['be_admin_fcat_delcatmsg']          = 'Haluatko varmasti\npoistaa tiedostoluokan?';
$BL['be_admin_fcat_delcat']             = 'Poista tiedostoluokka';
$BL['be_admin_fcat_delkey']             = 'Poista tiedostotunnuksen nimi';
$BL['be_admin_fcat_editkey']            = 'Muokkaa tiedostotunnusta';
$BL['be_admin_fcat_addcat']             = 'Luo uusi tiedostoluokka';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'Ulkoisen sivuston ulkoasun muokkaus: muotoilupohjat';
$BL['be_admin_page_align']              = 'Sivujen asemointi';
$BL['be_admin_page_align_left']         = 'Vakioasemointi (vasen)';
$BL['be_admin_page_align_center']       = 'Asemoi koko sivuston sis&auml;lt&ouml; keskelle';
$BL['be_admin_page_align_right']        = 'Asemoi koko sivuston sis&auml;lt&ouml; oikealle';
$BL['be_admin_page_margin']             = 'Marginaali';
$BL['be_admin_page_top']                = 'Yl&auml;';
$BL['be_admin_page_bottom']             = 'Ala';
$BL['be_admin_page_left']               = 'Vasen';
$BL['be_admin_page_right']              = 'Oikea';
$BL['be_admin_page_bg']                 = 'Tausta';
$BL['be_admin_page_color']              = 'V&auml;ri';
$BL['be_admin_page_height']             = 'Korkeus';
$BL['be_admin_page_width']              = 'Leveys';
$BL['be_admin_page_main']               = 'P&auml;&auml;';
$BL['be_admin_page_leftspace']          = 'Vasen tyhj&auml; tila';
$BL['be_admin_page_rightspace']         = 'Oikea tyhj&auml; tila';
$BL['be_admin_page_class']              = 'Luokkam&auml;&auml;ritys';
$BL['be_admin_page_image']              = 'Kuva';
$BL['be_admin_page_text']               = 'Teksti';
$BL['be_admin_page_link']               = 'Linkki';
$BL['be_admin_page_js']                 = 'Javascript';
$BL['be_admin_page_visited']            = 'Klikattu';
$BL['be_admin_page_pagetitle']          = 'Sivuston&nbsp;nimi';
$BL['be_admin_page_addtotitle']         = 'Lis&auml;&auml;&nbsp;nimen&nbsp;per&auml;&auml;n';
$BL['be_admin_page_category']           = 'Kategoria';
$BL['be_admin_page_articlename']        = 'Artikkelin&nbsp;nimi';
$BL['be_admin_page_blocks']             = 'Kolumnit';
$BL['be_admin_page_allblocks']          = 'Kaikki kolumnit';
$BL['be_admin_page_col1']               = '3-kolumninen layout';
$BL['be_admin_page_col2']               = '2-kolumninen layout (p&auml;&auml;kolumni oikealla, navigaatiopalkki vasemmalla)';
$BL['be_admin_page_col3']               = '2-kolumninen layout (p&auml;&auml;kolumni vasemmalla, navigaatiopalkki oikealla)';
$BL['be_admin_page_col4']               = '1-kolumninen layout';
$BL['be_admin_page_header']             = 'Ennen sis&auml;lt&ouml;&auml; tuleva muotoilu (header)';
$BL['be_admin_page_footer']             = 'Sis&auml;ll&ouml;n j&auml;lkeen tuleva muotoilu (footer)';
$BL['be_admin_page_topspace']           = 'Tila ylh&auml;&auml;ll&auml;';
$BL['be_admin_page_bottomspace']        = 'Tila alhaalla';
$BL['be_admin_page_button']             = 'P&auml;ivit&auml; muotoilupohja';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'Ulkoisen sivuston ulkoasun muokkaus: css-m&auml;&auml;rittelyt';
$BL['be_admin_css_css']                 = 'CSS';
$BL['be_admin_css_button']              = 'Tallenna css-m&auml;&auml;rittelyt';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'Ulkoisen sivuston ulkoasun muokkaus: tyylipohjat';
$BL['be_admin_tmpl_default']            = 'Oletus';
$BL['be_admin_tmpl_add']                = 'Lis&auml;&auml;&nbsp;tyylipohja';
$BL['be_admin_tmpl_edit']               = 'Muokkaa tyylipohjaa';
$BL['be_admin_tmpl_new']                = 'Luo uusi';
$BL['be_admin_tmpl_css']                = 'Css-tiedosto';
$BL['be_admin_tmpl_head']               = 'HTML:n &#13 head-osio';
$BL['be_admin_tmpl_js']                 = 'JavaScript &#13sivunlatauksen &#13yhteydess&auml;';
$BL['be_admin_tmpl_error']              = 'Virhesivu';
$BL['be_admin_tmpl_button']             = 'Tallenna tyylipohja';
$BL['be_admin_tmpl_name']               = 'Nimi';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'Sivustorakenne ja artikkelien listaus';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'Artikkelia ei ole nimetty';
$BL['be_article_err2']                  = 'Annetty alkamisp&auml;iv&auml;m&auml;&auml;r&auml; virheellinen - aseta nykyhetki';
$BL['be_article_err3']                  = 'Annetty p&auml;&auml;ttymisp&auml;iv&auml;m&auml;&auml;r&auml; virheellinen - aseta nykyhetki';
$BL['be_article_title1']                = 'Artikkelin perustiedot';
$BL['be_article_cat']                   = 'Kategoria';
$BL['be_article_atitle']                = 'Artikkelin otsikko';
$BL['be_article_asubtitle']             = 'Alaotsikko';
$BL['be_article_abegin']                = 'Alkaa';
$BL['be_article_aend']                  = 'P&auml;&auml;ttyy';
$BL['be_article_aredirect']             = 'Edelleenohjaus';
$BL['be_article_akeywords']             = 'Avainsanat';
$BL['be_article_asummary']              = 'Yhteenveto';
$BL['be_article_abutton']               = 'Luo uusi artikkeli';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'Annettu p&auml;&auml;ttymisp&auml;iv&auml; ei ole kelvollinen - nykyhetki + 1 viikko';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'Muokkaa artikkelin tietoja';
$BL['be_article_eslastedit']            = 'Muokattu viimeksi';
$BL['be_article_esnoupdate']            = 'Lomaketta ei p&auml;ivitetty';
$BL['be_article_esbutton']              = 'P&auml;ivit&auml; muutokset';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'Artikkelin sis&auml;lt&ouml;';
$BL['be_article_cnt_type']              = 'Sis&auml;lt&ouml;tyyppi';
$BL['be_article_cnt_space']             = 'Tyhj&auml; tila';
$BL['be_article_cnt_before']            = 'Ennen';
$BL['be_article_cnt_after']             = 'J&auml;lkeen';
$BL['be_article_cnt_top']               = 'Yl&auml;';
$BL['be_article_cnt_ctitle']            = 'Otsikko';
$BL['be_article_cnt_back']              = 'Artikkelin tiedot';
$BL['be_article_cnt_button1']           = 'P&auml;ivit&auml;';
$BL['be_article_cnt_button2']           = 'Luo uusi';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'Artikkelin tiedot';
$BL['be_article_cnt_ledit']             = 'Muokkaa artikkelia';
$BL['be_article_cnt_lvisible']          = 'Julkaistu / julkaisematon';
$BL['be_article_cnt_ldel']              = 'Poista artikkeli';
$BL['be_article_cnt_ldeljs']            = 'Poista artikkeli?';
$BL['be_article_cnt_redirect']          = 'Uudelleenohjaus';
$BL['be_article_cnt_edited']            = 'Muokkaaja';
$BL['be_article_cnt_start']             = 'Aloitus PVM';
$BL['be_article_cnt_end']               = 'P&auml;&auml;ttymis PVM';
$BL['be_article_cnt_add']               = 'Lis&auml;&auml; uusi sis&auml;lt&ouml;osa';
$BL['be_article_cnt_up']                = 'Siirr&auml; yl&ouml;s';
$BL['be_article_cnt_down']              = 'Siirr&auml; alas';
$BL['be_article_cnt_edit']              = 'Muokkaa sis&auml;lt&ouml;osaa';
$BL['be_article_cnt_delpart']           = 'Poista t&auml;m&auml; sis&auml;lt&ouml;osa';
$BL['be_article_cnt_delpartjs']         = 'Poista?';
$BL['be_article_cnt_center']            = 'Artikkelikeskus';

// content forms
$BL['be_cnt_plaintext']                 = 'Vain teksti';
$BL['be_cnt_htmltext']                  = 'Teksti, sis&auml;t&auml; html-m&auml;&auml;rittelyit&auml;';
$BL['be_cnt_image']                     = 'Kuva';
$BL['be_cnt_position']                  = 'Kuvan asemointi';
$BL['be_cnt_pos0']                      = 'Yll&auml;, vasen';
$BL['be_cnt_pos1']                      = 'Yll&auml;, keskitetty';
$BL['be_cnt_pos2']                      = 'Yll&auml;, oikea';
$BL['be_cnt_pos3']                      = 'Pohjalla, vasen';
$BL['be_cnt_pos4']                      = 'Pohjalla, keskitetty';
$BL['be_cnt_pos5']                      = 'Pohjalla, oikea';
$BL['be_cnt_pos6']                      = 'Tekstin tasalla, vasen';
$BL['be_cnt_pos7']                      = 'Tekstin tasalla, oikea';
$BL['be_cnt_pos0i']                     = 'Tasaa kuva vasemmalle tekstin yl&auml;puolelle';
$BL['be_cnt_pos1i']                     = 'Tasaa kuva keskelle tekstin yl&auml;puolelle';
$BL['be_cnt_pos2i']                     = 'Tasaa kuva oikealle tekstin yl&auml;puolelle';
$BL['be_cnt_pos3i']                     = 'Tasaa kuva vasemmalle tekstin alapuolelle';
$BL['be_cnt_pos4i']                     = 'Tasaa kuva keskelle tekstin alapuolelle';
$BL['be_cnt_pos5i']                     = 'Tasaa kuva oikealle tekstin alapuolelle';
$BL['be_cnt_pos6i']                     = 'Tasaa kuva vasemmalle tekstin tasalle';
$BL['be_cnt_pos7i']                     = 'Tasaa kuva oikealle tekstin tasalle';
$BL['be_cnt_maxw']                      = 'Max.&nbsp;leveys';
$BL['be_cnt_maxh']                      = 'Max.&nbsp;korkeus';
$BL['be_cnt_enlarge']                   = 'Klikattava&nbsp;kokokuva';
$BL['be_cnt_caption']                   = 'Kuvateksti';
$BL['be_cnt_subject']                   = 'Aihe';
$BL['be_cnt_recipient']                 = 'Vastaanottaja';
$BL['be_cnt_buttontext']                = 'Painiketeksti';
$BL['be_cnt_sendas']                    = 'L&auml;het&auml;';
$BL['be_cnt_text']                      = 'Teksti';
$BL['be_cnt_html']                      = 'HTML';
$BL['be_cnt_formfields']                = 'Lomakekent&auml;t';
$BL['be_cnt_code']                      = 'Koodi';
$BL['be_cnt_infotext']                  = 'Aputeksti';
$BL['be_cnt_subscription']              = 'Tilaus';
$BL['be_cnt_labelemail']                = 'S&auml;hk&ouml;postin nimi';
$BL['be_cnt_tablealign']                = 'Taulukon asemointi';
$BL['be_cnt_labelname']                 = 'Nimi';
$BL['be_cnt_labelsubsc']                = 'Tilaaja';
$BL['be_cnt_allsubsc']                  = 'Kaikki tilaajat';
$BL['be_cnt_default']                   = 'Oletus';
$BL['be_cnt_left']                      = 'Vasen';
$BL['be_cnt_center']                    = 'Keskitetty';
$BL['be_cnt_right']                     = 'Oikea';
$BL['be_cnt_buttontext']                = 'Painikkeen teksti';
$BL['be_cnt_successtext']               = 'OK-teksti';
$BL['be_cnt_regmail']                   = 'Rekisteröinti-s&auml;hk&ouml;posti';
$BL['be_cnt_logoffmail']                = 'Uloskirjaus-s&auml;hk&ouml;posti';
$BL['be_cnt_changemail']                = 'Muutos-s&auml;hk&ouml;posti';
$BL['be_cnt_openimagebrowser']          = 'Avaa kuvaselain';
$BL['be_cnt_openfilebrowser']           = 'Avaa tiedostoselain';
$BL['be_cnt_sortup']                    = 'Siirr&auml; yl&ouml;s';
$BL['be_cnt_sortdown']                  = 'Siirr&auml; alas';
$BL['be_cnt_delimage']                  = 'Poista valittu kuva';
$BL['be_cnt_delfile']                   = 'Poista valittu tiedosto';
$BL['be_cnt_delmedia']                  = 'Poista valittu media';
$BL['be_cnt_column']                    = 'Kolumni';
$BL['be_cnt_imagespace']                = 'Kuvav&auml;li';
$BL['be_cnt_directlink']                = 'Suora linkki';
$BL['be_cnt_target']                    = 'Kohde';
$BL['be_cnt_target1']                   = 'Uudessa ikkunassa';
$BL['be_cnt_target2']                   = 'P&auml;&auml;ikkunassa';
$BL['be_cnt_target3']                   = 'Samassa ikkunassa ilman kehyksi&auml;';
$BL['be_cnt_target4']                   = 'Samassa kehyksess&auml; tai ikkunassa';
$BL['be_cnt_bullet']                    = 'Lista';
$BL['be_cnt_ullist']                    = 'Lista';
$BL['be_cnt_ullist_desc']               = '~ = 1. taso, &nbsp; ~~ = 2. taso, &nbsp; jne';
$BL['be_cnt_linklist']                  = 'Linkkilista';
$BL['be_cnt_plainhtml']                 = 'Pelkk&auml; html';
$BL['be_cnt_files']                     = 'Tiedostot';
$BL['be_cnt_description']               = 'Kuvaus';
$BL['be_cnt_linkarticle']               = 'Artikkelilinkki';
$BL['be_cnt_articles']                  = 'Artikkelit';
$BL['be_cnt_movearticleto']             = 'Siirr&auml; valittu artikkeli artikkelilistalle';
$BL['be_cnt_removearticleto']           = 'Poista valittu artikkeli artikkelilistalta';
$BL['be_cnt_mediatype']                 = 'Median tyyppi';
$BL['be_cnt_control']                   = 'Ohjaus';
$BL['be_cnt_showcontrol']               = 'N&auml;yt&auml; ty&ouml;kalurivi';
$BL['be_cnt_autoplay']                  = 'Autoplay';
$BL['be_cnt_source']                    = 'L&auml;hde';
$BL['be_cnt_internal']                  = 'Sis&auml;inen';
$BL['be_cnt_openmediabrowser']          = 'Avaa mediaselain';
$BL['be_cnt_external']                  = 'Ulkoinen';
$BL['be_cnt_mediapos0']                 = 'Vasen (oletus)';
$BL['be_cnt_mediapos1']                 = 'Keskitetty';
$BL['be_cnt_mediapos2']                 = 'Oikea';
$BL['be_cnt_mediapos3']                 = 'Osio, vasen';
$BL['be_cnt_mediapos4']                 = 'Osio, oikea';
$BL['be_cnt_mediapos0i']                = 'Tasaa media vasemmalle tekstin yl&auml;puolelle';
$BL['be_cnt_mediapos1i']                = 'Tasaa media keskelle tekstin yl&auml;puolelle';
$BL['be_cnt_mediapos2i']                = 'Tasaa media oikealle tekstin yl&auml;puolelle';
$BL['be_cnt_mediapos3i']                = 'Tasaa media vasemmalle tekstin tasalle';
$BL['be_cnt_mediapos4i']                = 'Tasaa media oikealle tekstin tasalle';
$BL['be_cnt_setsize']                   = 'M&auml;&auml;rit&auml; koko';
$BL['be_cnt_set1']                      = 'M&auml;&auml;rit&auml; 160x120px';
$BL['be_cnt_set2']                      = 'M&auml;&auml;rit&auml; 240x180px';
$BL['be_cnt_set3']                      = 'M&auml;&auml;rit&auml; 320x240px';
$BL['be_cnt_set4']                      = 'M&auml;&auml;rit&auml; 480x360px';
$BL['be_cnt_set5']                      = 'Tyhjenn&auml; median korkeus ja leveys';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'Luo uusi sivustopohja';
$BL['be_admin_page_name']               = 'Pohjan nimi';
$BL['be_admin_page_edit']               = 'Muokkaa sivustopohjaa';
$BL['be_admin_page_render']             = 'Muotoilu';
$BL['be_admin_page_table']              = 'Taulu';
$BL['be_admin_page_div']                = 'CSS div';
$BL['be_admin_page_custom']             = 'Kustomoitu';
$BL['be_admin_page_custominfo']         = 'Sivustopohjan p&auml;&auml;osasta';
$BL['be_admin_tmpl_layout']             = 'Muotoilupohja';
$BL['be_admin_tmpl_nolayout']           = 'Sivun muotoilupohjaa ei l&ouml;ydy!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'Haku';
$BL['be_cnt_results']                   = 'Tulokset';
$BL['be_cnt_results_per_page']          = 'Per sivu (tyhj&auml; n&auml;ytt&auml;&auml; kaikki)';
$BL['be_cnt_opennewwin']                = 'Avaa uusi ikkuna';
$BL['be_cnt_searchlabeltext']           = 'N&auml;m&auml; ovat ennaltam&auml;&auml;r&auml;ttyj&auml; tekstej&auml; hakusivulle ja hakutulossivulle, jotka n&auml;kyv&auml;t, jos hakutuloksia on enemm&auml;n kuin sivullinen.';
$BL['be_cnt_input']                     = 'Sy&ouml;te';
$BL['be_cnt_style']                     = 'Tyyli';
$BL['be_cnt_result']                    = 'Tulos';
$BL['be_cnt_next']                      = 'Seuraava';
$BL['be_cnt_previous']                  = 'Edellinen';
$BL['be_cnt_align']                     = 'Tasaa';
$BL['be_cnt_searchformtext']            = 'Seuraavat tekstit ovat listattuina hakulomakkeen n&auml;kyess&auml; tai kun yht&auml;&auml;n hakutulosta ei l&ouml;ydy.';
$BL['be_cnt_intro']                     = 'Intro';
$BL['be_cnt_noresult']                  = 'Ei tuloksia';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'Pois p&auml;&auml;lt&auml;';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'Artikkelin luoja';
$BL['be_article_adminuser']             = 'Admin-k&auml;ytt&auml;j&auml;';
$BL['be_article_username']              = 'Artisti';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'N&auml;kyviss&auml; vain kirjautuneille k&auml;ytt&auml;jille';
$BL['be_admin_struct_status']           = 'Normaalisivuston valikon status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'Artikkelivalikko';
$BL['be_cnt_sitelevel']                 = 'Sivustotaso';
$BL['be_cnt_sitecurrent']               = 'Nykyinen sivustotaso';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'Hallintaj&auml;rjestelm&auml;n oletusteksti';
$BL['be_ctype_ecard']                   = 'eKortti';
$BL['be_ctype_blog']                    = 'Blogi';
$BL['be_cnt_ecardtext']                 = 'Nimi/eKortti';
$BL['be_cnt_ecardtmpl']                 = 'Viestipohja';
$BL['be_cnt_ecard_image']               = 'eKortin kuva';
$BL['be_cnt_ecard_title']               = 'eKortin otsikko';
$BL['be_cnt_alignment']                 = 'Tasaus';
$BL['be_cnt_ecardform']                 = 'Lomakepohja';
$BL['be_cnt_ecardform_err']             = 'Kaikki t&auml;hdell&auml; (*) merkityt kent&auml;t ovat pakollisia';
$BL['be_cnt_ecardform_sender']          = 'L&auml;hett&auml;j&auml;';
$BL['be_cnt_ecardform_recipient']       = 'Vastaanottaja';
$BL['be_cnt_ecardform_name']            = 'Nimi';
$BL['be_cnt_ecardform_msgtext']         = 'Viestisi vastaanottajalle';
$BL['be_cnt_ecardform_button']          = 'L&auml;het&auml; eKortti';
$BL['be_cnt_ecardsend']                 = 'L&auml;hetetty-pohja';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Hallintaj&auml;rjestelm&auml;n aloitussivun oletusteksti';
$BL['be_admin_startup_text']            = 'Aloitusteksti (HTML-muotoilu sallittu)';
$BL['be_admin_startup_button']          = 'Tallenna aloitusteksti';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'Vieraskirja';
$BL['be_cnt_guestbook_listing']         = 'Listaus';
$BL['be_cnt_guestbook_listing_all']     = 'Listaa kaikki viestit';
$BL['be_cnt_guestbook_list']            = 'Lista';
$BL['be_cnt_guestbook_perpage']         = 'per&nbsp;sivu';
$BL['be_cnt_guestbook_form']            = 'Lomake';
$BL['be_cnt_guestbook_signed']          = 'Allekirjoitettu';
$BL['be_cnt_guestbook_nav']             = 'nav';
$BL['be_cnt_guestbook_before']          = 'Ennen';
$BL['be_cnt_guestbook_after']           = 'Jälkeen';
$BL['be_cnt_guestbook_entry']           = 'Viesti';
$BL['be_cnt_guestbook_edit']            = 'Muokkaa';
$BL['be_cnt_ecardform_selector']        = 'Valitsin';
$BL['be_cnt_ecardform_radiobutton']     = 'Valintanappi';
$BL['be_cnt_ecardform_javascript']      = 'JavaScript';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'Artikkelien lukum&auml;&auml;r&auml;';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'Uutisviesti';
$BL['be_newsletter_addnl']              = 'Lis&auml;&auml; uutisviesti';
$BL['be_newsletter_titleeditnl']        = 'Muokkaa uutissviesti&auml;';
$BL['be_newsletter_newnl']              = 'Luo uusi';
$BL['be_newsletter_button_savenl']      = 'Tallenna uutisviesti';
$BL['be_newsletter_fromname']           = 'Kenelt&auml;';
$BL['be_newsletter_fromemail']          = 'Kenelt&auml; (s&auml;hk&ouml;posti)';
$BL['be_newsletter_replyto']            = 'Vastausosoite';
$BL['be_newsletter_changed']            = 'Viimeksi muokattu';
$BL['be_newsletter_placeholder']        = 'Ennalta m&auml;&auml;ritetyt koodit';
$BL['be_newsletter_htmlpart']           = 'HTML-sis&auml;lt&ouml;';
$BL['be_newsletter_textpart']           = 'Tekstisis&auml;lt&ouml;';
$BL['be_newsletter_allsubscriptions']   = 'Kaikki tilaajat';
$BL['be_newsletter_verifypage']         = 'Varmenna linkki';
$BL['be_newsletter_open']               = 'HTML ja teksti';
$BL['be_newsletter_open1']              = '(klikkaa kuvaa avaaksesi)';
$BL['be_newsletter_sendnow']            = 'L&auml;het&auml; uutisviesti';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Attention!</strong> Uutisviestin l&auml;hett&auml;minen on eritt&auml;in riskialtista. Vastaanottajilta pit&auml;&auml; olla suostumus, muutoin syyllistyt roskapostin l&auml;hett&auml;miseen. Eli mietip&auml; kahteen kertaan l&auml;hett&auml;mist&auml;. Testaa ensin l&auml;hett&auml;mist&auml;.';
$BL['be_newsletter_attention1']         = 'Jos olet muokannut uutisviesti&auml;, tallenna se ennen l&auml;hett&auml;mist&auml;.';
$BL['be_newsletter_testemail']          = 'Testis&auml;hk&ouml;postiosoite';
$BL['be_newsletter_sendnlbutton']       = 'L&auml;het&auml; uutisviesti';
$BL['be_newsletter_sendprocess']        = 'L&auml;hetyksen kehittyminen';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Huomio!</strong> &Auml;l&auml; lopeta l&auml;hetysprosessia, voit vahingossa l&auml;hett&auml;&auml; viestin useampaan kertaan. Jos l&auml;hett&auml;minen ep&auml;onnistuu, ne, joille viesti EI l&auml;htenyt, saavat viestin, jos l&auml;hetät sen heti uudestaan.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">Testis&auml;hk&ouml;postiosoite<strong>###TEST###</strong> ei kelpaa!<br />&nbsp;<br />Yrit&auml; uudelleen!';
$BL['be_newsletter_to']                 = 'Vastaanottajat';
$BL['be_newsletter_ready']              = 'Uutisviestin l&auml;hetys: VALMIS';
$BL['be_newsletter_readyfailed']        = 'L&auml;hett&auml;minen ep&auml;onnistui:';
$BL['be_subnav_msg_subscribers']        = 'Uutisviestin tilaajat';

// added: 20-04-2004
$BL['be_ctype_sitemap']                             = 'Sivukartta';
$BL['be_cnt_sitemap_catimage']          = 'Tason kuvake';
$BL['be_cnt_sitemap_articleimage']      = 'Artikkelin kuvake';
$BL['be_cnt_sitemap_display']           = 'N&auml;ytt&ouml;tapa';
$BL['be_cnt_sitemap_structuronly']      = 'Vain rakennetasot';
$BL['be_cnt_sitemap_structurarticle']   = 'Rakennetasot + artikkelit';
$BL['be_cnt_sitemap_catclass']          = 'Tason luokka';
$BL['be_cnt_sitemap_articleclass']      = 'Artikkelin luokka';
$BL['be_cnt_sitemap_count']             = 'LAskuri';
$BL['be_cnt_sitemap_classcount']        = 'Lis&auml;&auml; luokkaan';
$BL['be_cnt_sitemap_noclasscount']      = '&Auml;l&auml; lis&auml;&auml; luokkaan';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'Tarjous';
$BL['be_cnt_bid_bidtext']               = 'Tarjousteksti';
$BL['be_cnt_bid_sendtext']              = 'L&auml;hetetty-teksti';
$BL['be_cnt_bid_verifiedtext']          = 'Varmistettu-teksti';
$BL['be_cnt_bid_errortext']             = 'Tarjous poistettu';
$BL['be_cnt_bid_verifyemail']           = 'Tarkista s&auml;hk&ouml;osoite';
$BL['be_cnt_bid_startbid']              = 'Aloita tarjous';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'Nosta tarjousta';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'Ulkoinen materiaali';
$BL['be_cnt_pages_select']              = 'Valitse tiedosto';
$BL['be_cnt_pages_fromfile']            = 'Tiedosto rakenteesta';
$BL['be_cnt_pages_manually']            = 'Polku/tiedosto tai URL';
$BL['be_cnt_pages_cust']                = 'Tiedosto/URL';
$BL['be_cnt_pages_from']                = 'L&auml;hde';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'Rollover-kuvat';
$BL['be_cnt_reference_basis']           = 'Tasaus';
$BL['be_cnt_reference_horizontal']      = 'Vaaka';
$BL['be_cnt_reference_vertical']        = 'Pysty';
$BL['be_cnt_reference_aligntext']       = 'Pieni hakemistokuva';
$BL['be_cnt_reference_largetext']       = 'Iso hakemistokuva';
$BL['be_cnt_reference_zoom']            = 'L&auml;henn&auml;';
$BL['be_cnt_reference_middle']          = 'Keskelle';
$BL['be_cnt_reference_border']          = 'Reunus';
$BL['be_cnt_reference_block']           = 'Osa lev. x kork.';

// added: 31-05-2004
$BL['be_article_rendering']             = 'Muotoilu';
$BL['be_article_nosummary']             = '&Auml;l&auml; n&auml;yt&auml; yhteenvetoa artikkelissa';
$BL['be_article_forlist']               = 'Artikkelilistaus';
$BL['be_article_forfull']               = 'N&auml;yt&auml; koko artikkeli';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>HUOM!</strong> &quot;SETUP-kansiota&quot; ei ole poistettu! Poista hakemisto palvelimen juurihakemistosta - T&auml;m&auml; kansio on mahdollinen tietoturvariski.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'Kielletyt sanat';
$BL['be_cnt_guestbook_flooding']        = 'ylivuoto';
$BL['be_cnt_guestbook_setcookie']       = 'Aseta ev&auml;ste';
$BL['be_cnt_guestbook_allowed']         = 'kuluttua sallitaan';
$BL['be_cnt_guestbook_seconds']         = 'sekuntia';
$BL['be_alias_ID']                      = 'alias ID';
$BL['be_ftrash_delall']                 = "Haluatko varmasti poistaa \nKAIKKI TIEDOSTOT roskakorista?";
$BL['be_ftrash_delallfiles']            = 'poista kaikki roskakorin sis&auml;lt&auml;m&auml;t tiedostot';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'Tilaajatietojen lataaminen (CSV)';
$BL['be_newsletter_importtitle']        = 'Lataa uutiskirjeen tilaajatiedot';
$BL['be_newsletter_entriesfound']       = 'merkint&auml;&auml;&nbsp;l&ouml;ydetty';
$BL['be_newsletter_foundinfile']        = 'tiedostossa';
$BL['be_newsletter_addresses']          = 'osoitteet';
$BL['be_newsletter_csverror']           = 'Ladattu CSV-tiedosto on virheellinen! Tarkista erottimet!';
$BL['be_newsletter_importall']          = 'lataa kaikki tietueet';
$BL['be_newsletter_addressesadded']     = 'osoitetta lis&auml;tty.';
$BL['be_newsletter_newimport']          = 'uusi lataus';
$BL['be_newsletter_importerror']        = 'Ole hyv&auml; ja tarkista CSV-tiedosto - yht&auml;&auml;n tietuetta ei lis&auml;tty!';
$BL['be_newsletter_shouldbe1']          = 'CSV-tiedosto tulisi muotoilla n&auml;in';
$BL['be_newsletter_shouldbe2']          = 'voit m&auml;&auml;ritt&auml;&auml; my&ouml;s oman erottimen';
$BL['be_newsletter_sample']             = 'esimerkki';
$BL['be_newsletter_selectCSV']          = 'valitse CSV-tiedosto';
$BL['be_newsletter_delimeter']          = 'erotin';
$BL['be_newsletter_importCSV']          = 'lataa CSV-tiedosto';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'Artikkeleiden j&auml;rjest&auml;minen';
$BL['be_admin_struct_orderdate']        = 'luonti pvm';
$BL['be_admin_struct_orderchangedate']  = 'muutos pvm';
$BL['be_admin_struct_orderstartdate']   = 'aloitus pvm';
$BL['be_admin_struct_orderdesc']        = 'laskeva';
$BL['be_admin_struct_orderasc']         = 'nouseva';
$BL['be_admin_struct_ordermanual']      = 'manuaalinen (nuoli yl&ouml;s/alas)';
$BL['be_cnt_sitemap_startid']           = 'alkaa';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'kartta';
$BL['be_save_btn']                      = 'Tallenna';
$BL['be_cmap_location_error_notitle']   = 'anna nimi sijainnille.';
$BL['be_cnt_map_add']                   = 'lis&auml;&auml; sijainti';
$BL['be_cnt_map_edit']                  = 'muokkaa sijaintia';
$BL['be_cnt_map_title']                 = 'sijainnin nimi';
$BL['be_cnt_map_info']                  = 'entry/info';
$BL['be_cnt_map_list']                  = 'sijaintilistaus';
$BL['be_btn_delete']                    = 'Haluatko varmasti \npoistaa sijaintitiedon?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'PHP-muuttujat';
$BL['be_cnt_vars']                      = 'muuttujat';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'kopioi artikkeli';
$BL['be_func_struct_nocopy']            = 'poista artikkeleiden kopiointi k&auml;yt&ouml;st&auml;';
$BL['be_func_struct_copy_level']        = 'kopioi rakennehakemisto';
$BL['be_func_struct_no_copy']           = "J&auml;rjestelm&auml;n juureen kopiointi ei ole mahdollista!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minuutti';
$BL['be_date_minutes']                  = 'minuuttia';
$BL['be_date_hour']                     = 'tunti';
$BL['be_date_hours']                    = 'tuntia';
$BL['be_date_day']                      = 'p&auml;iv&auml;';
$BL['be_date_days']                     = 'p&auml;iv&auml;&auml;';
$BL['be_date_week']                     = 'viikko';
$BL['be_date_weeks']                    = 'viikkoa';
$BL['be_date_month']                    = 'kuukausi';
$BL['be_date_months']                   = 'kuukautta';
$BL['be_off']                           = 'Pois p&auml;&auml;lt&auml;';
$BL['be_on']                            = 'p&auml;&auml;ll&auml;';
$BL['be_cache']                         = 'V&auml;limuisti';
$BL['be_cache_timeout']                 = 'aikav&auml;li';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'k&auml;ytt&auml;j&auml;t &amp; ryhm&auml;t';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'keskustelu';
$BL['be_subnav_msg_forum']              = 'keskusteluaiheiden listaus';
$BL['be_forum_title']                   = 'keskustelualueen otsikko';
$BL['be_forum_permission']              = 'k&auml;ytt&ouml;oikeudet';
$BL['be_forum_add']                     = 'lis&auml;&auml; keskustelualue';
$BL['be_forum_titleedit']               = 'muokkaa keskustelualuetta';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'kustomoitu';
$BL['be_show_content']                  = 'n&auml;ytt&ouml;';
$BL['be_main_content']                  = 'p&auml;&auml;otsake';
$BL['be_admin_template_jswarning']      = 'Varoitus!!! \nKustomoidut osat saattavat muuttua! \n\nJos peruutat toimenpiteen \npalautat sivuston oletusulkoasun! \n\nVaihda muotoilupohja?\n\n';

$BL['be_ctype_rssfeed']                 = 'RSS-sy&ouml;te';
$BL['be_cnt_rssfeed_url']               = 'RSS-polku';
$BL['be_cnt_rssfeed_item']              = 'aiheet';
$BL['be_cnt_rssfeed_max']               = 'max.';
$BL['be_cnt_rssfeed_cut']               = 'piilota 1. aihe';


