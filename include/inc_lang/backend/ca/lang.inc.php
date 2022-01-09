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


// Language: Catalan, Language Code: ca
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'Usuaris connectats';

// Login Page
$BL["login_text"]                       = 'Introduiu dades d\'acc&eacute;s';
$BL['login_error']                      = 'Errors durant l\'acc&eacute;s!';
$BL["login_username"]                   = 'Nom d\'usuari';
$BL["login_userpass"]                   = 'Contrasenya';
$BL["login_button"]                     = 'Entrar';
$BL["login_lang"]                       = 'Idioma';

// phpwcms.php
$BL['be_nav_logout']                    = 'DESCONNECTAR';
$BL['be_nav_articles']                  = 'ARTICLES';
$BL['be_nav_files']                     = 'FITXERS';
$BL['be_nav_modules']                   = 'M&Ograve;DULS';
$BL['be_nav_messages']                  = 'MISSATGES';
$BL['be_nav_chat']                      = 'XAT';
$BL['be_nav_profile']                   = 'PERFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'F&Ograve;RUM';

$BL['be_page_title']                    = 'phpwcms - &Agrave;rea d\'administraci&oacute;';

$BL['be_subnav_article_center']         = 'Centre d\'articles';
$BL['be_subnav_article_new']            = 'Nou article';
$BL['be_subnav_file_center']            = 'Centre de fitxers';
$BL['be_subnav_file_ftptakeover']       = 'Inclusi&oacute; des de FTP';
$BL['be_subnav_mod_artists']            = 'Artista, categoria, g&egrave;nere';
$BL['be_subnav_msg_center']             = 'Centre de missatges';
$BL['be_subnav_msg_new']                = 'Nou missatge';
$BL['be_subnav_msg_newsletter']         = 'Subscripcions al butllet&iacute;';
$BL['be_subnav_chat_main']              = 'P&agrave;gina principal del xat';
$BL['be_subnav_chat_internal']          = 'Xat intern';
$BL['be_subnav_profile_login']          = 'Informaci&oacute; d\'acc&eacute;s';
$BL['be_subnav_profile_personal']       = 'Informaci&oacute; personal';
$BL['be_subnav_admin_pagelayout']       = 'Composici&oacute; de p&agrave;gina';
$BL['be_subnav_admin_templates']        = 'Plantilles';
$BL['be_subnav_admin_css']              = 'CSS predeterminada';
$BL['be_subnav_admin_sitestructure']    = 'Estructura del website';
$BL['be_subnav_admin_users']            = 'Administraci&oacute; d\'usuaris';
$BL['be_subnav_admin_filecat']          = 'Categories de fitxers';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'ID de l\'article';
$BL['be_func_struct_preview']           = 'Vista pr&egrave;via';
$BL['be_func_struct_edit']              = 'Edita article';
$BL['be_func_struct_sedit']             = 'Edita el nivell d\'estructura';
$BL['be_func_struct_cut']               = 'Elimina article';
$BL['be_func_struct_nocut']             = 'Desactiva eliminaci&oacute; de l\'article';
$BL['be_func_struct_svisible']          = 'Canvia visible/invisible';
$BL['be_func_struct_spublic']           = 'Canvia p&uacute;blic / no p&uacute;blic';
$BL['be_func_struct_sort_up']           = 'Ordre ascendent';
$BL['be_func_struct_sort_down']         = 'Ordre descendent';
$BL['be_func_struct_del_article']       = 'Elimina l\'article';
$BL['be_func_struct_del_jsmsg']         = 'Esteu segur que voleu eliminar l\'article?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'Crea nou article dins nivell d\'estructura';
$BL['be_func_struct_paste_article']     = 'Enganxa article dins nivell d\'estructura';
$BL['be_func_struct_insert_level']      = 'Inserta nivell d\'estructura a';
$BL['be_func_struct_paste_level']       = 'Enganxa en nivell d\'estructura';
$BL['be_func_struct_cut_level']         = 'Elimina nivell d\'estructura';
$BL['be_func_struct_no_cut']            = "No es pot eliminar el nivell arrel";
$BL['be_func_struct_no_paste1']         = "No es pot enganxar aqu&iacute;!";
$BL['be_func_struct_no_paste2']         = '&Eacute;s fill en el nivell principal de l\'arbre';
$BL['be_func_struct_no_paste3']         = 'Es pot enganxar aqu&iacute;';
$BL['be_func_struct_paste_cancel']      = 'Cancel&middot;la el canvi de nivell d\'estructura';
$BL['be_func_struct_del_struct']        = 'Elimina el nivell d\'estructura';
$BL['be_func_struct_del_sjsmsg']        = 'Esteu segur que voleu eliminar el nivell d\'estructura?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'Obrir';
$BL['be_func_struct_close']             = 'Tancar';
$BL['be_func_struct_empty']             = 'Buit';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'Sols text';
$BL['be_ctype_html']                    = 'HTML';
$BL['be_ctype_code']                    = 'Codi';
$BL['be_ctype_textimage']               = 'Text + imatge';
$BL['be_ctype_images']                  = 'Imatges';
$BL['be_ctype_bulletlist']              = 'Llista de punts';
$BL['be_ctype_ullist']                  = 'Llista ordenada';
$BL['be_ctype_link']                    = 'Enlla&ccedil; &amp; correu';
$BL['be_ctype_linklist']                = 'Llista d\'enlla&ccedil;os';
$BL['be_ctype_linkarticle']             = 'Enlla&ccedil; a article';
$BL['be_ctype_multimedia']              = 'Multim&egrave;dia';
$BL['be_ctype_filelist']                = 'Llista de fitxers';
$BL['be_ctype_emailform']               = 'Formulari de correu';
$BL['be_ctype_newsletter']              = 'Butllet&iacute;';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Perfil creat correctament.';
$BL['be_profile_create_error']          = 'Hi ha hagut un error en crear el perfil.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Informaci&oacute; del perfil actualitzada correctament.';
$BL['be_profile_update_error']          = 'Hi ha hagut un error en actualitzar.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'El nom d\'usuari {VAL} no &eacute;s v&agrave;lid';
$BL['be_profile_account_err2']          = 'La contrasenya &eacute;s curta (nom&eacute;s {VAL} caracters: m&iacute;nim 5)';
$BL['be_profile_account_err3']          = 'La contrasenya repetida ha de ser id&eacute;ntica';
$BL['be_profile_account_err4']          = 'L\'adre&ccedil;a de correu {VAL} no &eacute;s v&agrave;lida';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'La vostra informaci&oacute; personal';
$BL['be_profile_data_text']             = 'La informaci&oacute; personal &eacute;s optativa. Els usuaris del website veur&agrave;n (o no) la informaci&oacute; del vostre perfil dins l\'&agrave;rea p&uacute;blica o a les p&agrave;gines d\'articles segons les caselles que hagueu seleccionat.';
$BL['be_profile_label_title']           = 'Tractament';
$BL['be_profile_label_firstname']       = 'Nom';
$BL['be_profile_label_name']            = 'Cognom';
$BL['be_profile_label_company']         = 'Empresa';
$BL['be_profile_label_street']          = 'Carrer';
$BL['be_profile_label_city']            = 'Ciutat';
$BL['be_profile_label_state']           = 'Prov&iacute;ncia';
$BL['be_profile_label_zip']             = 'Codi postal';
$BL['be_profile_label_country']         = 'Pa&iacute;s';
$BL['be_profile_label_phone']           = 'Tel&egrave;fon';
$BL['be_profile_label_fax']             = 'Fax';
$BL['be_profile_label_cellphone']       = 'M&ograve;bil';
$BL['be_profile_label_signature']       = 'Signatura';
$BL['be_profile_label_notes']           = 'Observacions';
$BL['be_profile_label_profession']      = 'Professi&oacute;';
$BL['be_profile_label_newsletter']      = 'Butllet&iacute;';
$BL['be_profile_text_newsletter']       = 'Dessitjo rebre el butllet&iacute; de phpwcms.';
$BL['be_profile_label_public']          = 'P&uacute;blic';
$BL['be_profile_text_public']           = 'Tothom pot veure el meu perfil personal.';
$BL['be_profile_label_button']          = 'Actualitzar informaci&oacute; personal';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'Les vostres dades d\'acc&eacute;s';
$BL['be_profile_account_text']          = 'Per m&eacute;s seguretat, conv&eacute; canviar la contrasenya de tant en tant. <br />Normalment no cal canviar el nom de usuari.';
$BL['be_profile_label_err']             = 'Verifiqueu, sisplau';
$BL['be_profile_label_username']        = 'Nom d\'usuari';
$BL['be_profile_label_newpass']         = 'Nova contrasenya';
$BL['be_profile_label_repeatpass']      = 'Repetiu contrasenya';
$BL['be_profile_label_email']           = 'Correu electr&ograve;nic';
$BL['be_profile_account_button']        = 'Actualitzar dades d\'acc&eacute;s';
$BL['be_profile_label_lang']            = 'Idioma';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'Incloure fitxers enviats via FTP';
$BL['be_ftptakeover_mark']              = 'Marcar';
$BL['be_ftptakeover_available']         = 'Fitxers disponibles';
$BL['be_ftptakeover_size']              = 'Mida';
$BL['be_ftptakeover_nofile']            = 'Encara no hi ha cap fitxer disponible; pugeu-ne algun via FTP';
$BL['be_ftptakeover_all']               = 'TOTS';
$BL['be_ftptakeover_directory']         = 'Directori';
$BL['be_ftptakeover_rootdir']           = 'Directori arrel';
$BL['be_ftptakeover_needed']            = '&Eacute;s necessari!!! (N\'heu de seleccionar un)';
$BL['be_ftptakeover_optional']          = 'Opcional';
$BL['be_ftptakeover_keywords']          = 'Mots clau';
$BL['be_ftptakeover_additional']        = 'Addicional';
$BL['be_ftptakeover_longinfo']          = 'Descripci&oacute; llarga';
$BL['be_ftptakeover_status']            = 'Estat';
$BL['be_ftptakeover_active']            = 'Actiu';
$BL['be_ftptakeover_public']            = 'P&uacute;blic';
$BL['be_ftptakeover_createthumb']       = 'Crea miniatura';
$BL['be_ftptakeover_button']            = 'Transfereix els fitxers seleccionats';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'Centre de fitxers';
$BL['be_ftab_createnew']                = 'Crea nova carpeta dins l\'arrel';
$BL['be_ftab_paste']                    = 'Enganxa el contingut del portapapers dins el directori arrel';
$BL['be_ftab_disablethumb']             = 'Desactiva miniatures a la llista';
$BL['be_ftab_enablethumb']              = 'Activa miniatures a la llista';
$BL['be_ftab_private']                  = 'Privats';
$BL['be_ftab_public']                   = 'P&uacute;blics';
$BL['be_ftab_search']                   = 'Cerca';
$BL['be_ftab_trash']                    = 'Paperera';
$BL['be_ftab_open']                     = 'Obre totes les carpetes';
$BL['be_ftab_close']                    = 'Tanca totes les carpetes obertes';
$BL['be_ftab_upload']                   = 'Puja fitxer al directori arrel';
$BL['be_ftab_filehelp']                 = 'Obre l\'ajuda';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'Directori arrel';
$BL['be_fpriv_title']                   = 'Crear carpeta nova';
$BL['be_fpriv_inside']                  = 'Dins';
$BL['be_fpriv_error']                   = 'Error: cal escriure un nom pel directori';
$BL['be_fpriv_name']                    = 'Nom';
$BL['be_fpriv_status']                  = 'Estat';
$BL['be_fpriv_button']                  = 'Crea carpeta nova';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'Edita la carpeta';
$BL['be_fpriv_newname']                 = 'Nou nom';
$BL['be_fpriv_updatebutton']            = 'Actualitza la informaci&oacute; de la carpeta';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Seleccioneu el fitxer que volgueu pujar';
$BL['be_fprivup_err2']                  = 'La mida del fitxer enviat &eacute;s m&eacute;s gran de';
$BL['be_fprivup_err3']                  = 'Error en escriure el fitxer';
$BL['be_fprivup_err4']                  = 'Error en crear carpeta d\'usuari';
$BL['be_fprivup_err5']                  = 'No hi ha cap miniatura';
$BL['be_fprivup_err6']                  = 'Hi ha hagut un error del servidor. No ho intenteu un altre cop. Sisplau, comuniqueu-ho al vostre <a href="mailto:{VAL}">webmaster</a> tan aviat com pugueu!';
$BL['be_fprivup_title']                 = 'Envia fitxers';
$BL['be_fprivup_button']                = 'Envia fitxers';
$BL['be_fprivup_upload']                = 'Enviar';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'Edita la informaci&oacute; del fitxer';
$BL['be_fprivedit_filename']            = 'Nom del fitxer';
$BL['be_fprivedit_created']             = 'Creat';
$BL['be_fprivedit_dateformat']          = 'd-m-Y H:i';
$BL['be_fprivedit_err1']                = 'Verificar el nom del fitxer (Tornar al nom original)';
$BL['be_fprivedit_clockwise']           = 'Rota la miniatura d\'esquerra a dreta [arrxiu original +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'Rota la miniatura de dreta a esquerra [arrxiu original -90&deg;]';
$BL['be_fprivedit_button']              = 'Actualitza la informaci&oacute; del fitxer';
$BL['be_fprivedit_size']                = 'Mida';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'Envia fitxer a la carpeta';
$BL['be_fprivfunc_makenew']             = 'Crea carpeta nova dins';
$BL['be_fprivfunc_paste']               = 'Enganxa el contingut del portapapers dins la carpeta';
$BL['be_fprivfunc_edit']                = 'Edita la carpeta';
$BL['be_fprivfunc_cactive']             = 'Canvia actiu/inactiu';
$BL['be_fprivfunc_cpublic']             = 'Canvia p&uacute;blic/no p&uacute;blic';
$BL['be_fprivfunc_deldir']              = 'Elimina carpeta';
$BL['be_fprivfunc_jsdeldir']            = 'Esteu segur que voleu eliminar la carpeta?';
$BL['be_fprivfunc_notempty']            = '&iexcl;La carpeta {VAL} no &eacute;s buida!';
$BL['be_fprivfunc_opendir']             = 'Obre la carpeta';
$BL['be_fprivfunc_closedir']            = 'Tanca la carpeta';
$BL['be_fprivfunc_dlfile']              = 'Baixa el fitxer';
$BL['be_fprivfunc_clipfile']            = 'Fitxer del portapapers';
$BL['be_fprivfunc_cutfile']             = 'Talla';
$BL['be_fprivfunc_editfile']            = 'Edita la informaci&oacute; del fitxer';
$BL['be_fprivfunc_cactivefile']         = 'Canvia actiu/inactiu';
$BL['be_fprivfunc_cpublicfile']         = 'Canvia p&uacute;blic/no p&uacute;blic';
$BL['be_fprivfunc_movetrash']           = 'Envia a la paperera';
$BL['be_fprivfunc_jsmovetrash1']        = 'Esteu segur que voleu enviar el fitxer';
$BL['be_fprivfunc_jsmovetrash2']        = 'a la paperera?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'No hi ha fitxers ni carpetes privats';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'Usuari';
$BL['be_fpublic_nofiles']               = 'No hi ha fitxers ni carpetes p&uacute;blics';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'La paperera &eacute;s buida';
$BL['be_ftrash_show']                   = 'Veure fitxers privats';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Voleu recuperar {VAL} i posar-lo dins la llista privada?';
$BL['be_ftrash_delete']                 = 'Voleu eliminar {VAL}?';
$BL['be_ftrash_undo']                   = 'Recuperar (treure de la paperera)';
$BL['be_ftrash_delfinal']               = 'Eliminaci&oacute; definitiva';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'El camp de recerca &eacute;s buit';
$BL['be_fsearch_title']                 = 'Cercar fitxers';
$BL['be_fsearch_infotext']              = '<br/>Aquesta &eacute;s una recerca b&aacute;sica sobre la informaci&oacute; dels fitxers. Es busquen coincid&egrave;ncies en mots clau, noms de fitxers i descripci&oacute; de fitxers. <br/><br/>No s\'accepten metacaracters (comodins). <br/><br/>Separeu les paraules de la recerca amb un espai. Seleccioneu \'i/o\' i quins fitxers cal cercar: tots, privats o p&uacute;blics.<br/><br/>';
$BL['be_fsearch_nonfound']              = 'No s\'ha trobat cap fitxer. Modifiqueu els criteris de recerca.';
$BL['be_fsearch_fillin']                = 'Ompliu sisplau el camp de recerca.';
$BL['be_fsearch_searchlabel']           = 'Cercar';
$BL['be_fsearch_startsearch']           = 'Comen&ccedil;a la recerca';
$BL['be_fsearch_and']                   = 'I';
$BL['be_fsearch_or']                    = 'O';
$BL['be_fsearch_all']                   = 'Tots els fitxers';
$BL['be_fsearch_personal']              = 'Privats';
$BL['be_fsearch_public']                = 'P&uacute;blics';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'Xat intern';
$BL['be_chat_info']                     = 'Aqu&iacute; podeu xatejar amb altres usuaris de la administraci&oacute; sobre diversos temes. &Eacute;s un mitj&agrave; per conversar en temps real, per&ograve; tamb&eacute; es poden deixar missatges per ser llegits per tothom. Si dessitgeu intercanviar idees amb altres usuaris, podeu utilitzar el f&ograve;rum (versi&oacute; de phpwcms posterior).';
$BL['be_chat_start']                    = 'Cliqueu aqu&iacute; per entrar en el xat';
$BL['be_chat_lines']                    = 'Missatges de xat';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'Centre de missatges';
$BL['be_msg_new']                       = 'Nous';
$BL['be_msg_old']                       = 'Anteriors';
$BL['be_msg_senttop']                   = 'Enviats';
$BL['be_msg_del']                       = 'Eliminats';
$BL['be_msg_from']                      = 'De';
$BL['be_msg_subject']                   = 'Tema';
$BL['be_msg_date']                      = 'Data/Hora';
$BL['be_msg_close']                     = 'Tanca el missatge';
$BL['be_msg_create']                    = 'Crea un missatge nou';
$BL['be_msg_reply']                     = 'Contesta aquest missatge';
$BL['be_msg_move']                      = 'Envia aquest missatge a la paperera';
$BL['be_msg_unread']                    = 'Missatges no llegits o nous';
$BL['be_msg_lastread']                  = '&Uacute;ltims {VAL} missatges llegits';
$BL['be_msg_lastsent']                  = '&Uacute;ltims {VAL} missatges enviats';
$BL['be_msg_marked']                    = 'Missatges marcats per ser eliminats (paperera)';
$BL['be_msg_nomsg']                     = 'No s\'ha trobat cap missatge en aquesta carpeta';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'Enviat per';
$BL['be_msg_on']                        = 'El';
$BL['be_msg_msg']                       = 'Missatge';
$BL['be_msg_err1']                      = 'Cal indicar el destinatari...';
$BL['be_msg_err2']                      = 'Cal omplir el camp del tema (el destinatari podr&agrave; atendre millor el vostre missatge.';
$BL['be_msg_err3']                      = 'Sembla que no t&eacute; gaire sentit enviar un missatge sense missatge... ;-)';
$BL['be_msg_sent']                      = 'S\'ha enviat el nou missatge!';
$BL['be_msg_fwd']                       = 'Ara sereu redirigit al centre de missatges o';
$BL['be_msg_newmsgtitle']               = 'Escriu un missatge nou';
$BL['be_msg_err']                       = 'Error a l\'enviar el missatge';
$BL['be_msg_sendto']                    = 'Envia missatge a';
$BL['be_msg_available']                 = 'Llista de destinataris disponibles';
$BL['be_msg_all']                       = 'Envia el missatge a tots els destinataris seleccionats';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'Subscripcions al butllet&iacute;';
$BL['be_newsletter_titleedit']          = 'Editar la subscripci&oacute; al butllet&iacute;';
$BL['be_newsletter_new']                = 'Crear-ne una de nova';
$BL['be_newsletter_add']                = 'Afegeix subscripci&oacute; al butllet&iacute;';
$BL['be_newsletter_name']               = 'Nom';
$BL['be_newsletter_info']               = 'Info';
$BL['be_newsletter_button_save']        = 'Desar la suscripci&oacute;';
$BL['be_newsletter_button_cancel']      = 'Cancel&middot;lar';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'El nom d\'usuari no &eacute;s v&agrave;lid, esculliu-ne un altre';
$BL['be_admin_usr_err2']                = 'Cal indicar el nom d\'usuari (camp obligatori)';
$BL['be_admin_usr_err3']                = 'Cal indicar la contrasenya (camp obligatori)';
$BL['be_admin_usr_err4']                = "L'adre&ccedil;a de correu no &eacute;s v&agrave;lida";
$BL['be_admin_usr_err']                 = 'Error';
$BL['be_admin_usr_mailsubject']         = 'Benvingut a la administraci&oacute; de phpwcms';
$BL['be_admin_usr_mailbody']            = "BENVINGUT A LA ADMINISTRACI&Oacute; DE PHPWCMS\n\n    usuari: {LOGIN}\n    contrasenya: {PASSWORD}\n\n\nEs pot connectar aqu&iacute;: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'Afegeix nou compte d\'usuari';
$BL['be_admin_usr_realname']            = 'Nom real';
$BL['be_admin_usr_setactive']           = 'Activa el compte';
$BL['be_admin_usr_iflogin']             = 'Si ho seleccioneu, l\'usuari es pot connectar';
$BL['be_admin_usr_isadmin']             = 'Administrador';
$BL['be_admin_usr_ifadmin']             = 'Si ho seleccioneu, l\'usuari t&eacute; privilegis d\'administrador';
$BL['be_admin_usr_verify']              = 'Verificaci&oacute;';
$BL['be_admin_usr_sendemail']           = 'Envia un correu al nou usuari amb la informaci&oacute; del compte';
$BL['be_admin_usr_button']              = 'Envia les dades de l\'usuari';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'Edita el compte de l\'usuari';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - S\'ha modificat la informaci&oacute; del compte';
$BL['be_admin_usr_emailbody']           = "PHPWCMS S'HA MODIFICAT LA INFORMACI&Oacute; DEL COMPTE D'USUARI\n\n    usuari: {LOGIN}\n    CONTRASENYA: {PASSWORD}\n\n\nEs pot connectar aqu&iacute;: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[SENSE MODIFICAR - UTILITZAR LA CONTRASENYA CONEGUDA]';
$BL['be_admin_usr_ebutton']             = 'Actualitza la informaci&oacute; d\'usuari';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'Llista d\'usuaris de phpwcms';
$BL['be_admin_usr_ldel']                = 'ATENCI&Oacute;! Aix&ograve; eliminar&agrave; l\'usuari';
$BL['be_admin_usr_create']              = 'Afegeix un nou usuari';
$BL['be_admin_usr_editusr']             = 'Edita l\'usuari';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'Estructura del website';
$BL['be_admin_struct_child']            = '(Dintre de)';
$BL['be_admin_struct_index']            = 'Index (p&agrave;gina d\'inici)';
$BL['be_admin_struct_cat']              = 'T&iacute;tol de la categoria';
$BL['be_admin_struct_status']           = 'Estat dins del men&uacute;';
$BL['be_admin_struct_hide1']            = 'Ocultar';
$BL['be_admin_struct_hide2']            = 'aquesta categoria al men&uacute; de navegaci&oacute;';
$BL['be_admin_struct_info']             = 'Informaci&oacute; sobre la categoria';
$BL['be_admin_struct_template']         = 'Plantilla';
$BL['be_admin_struct_alias']            = 'Àlias de la categoria';
$BL['be_admin_struct_visible']          = 'Visible';
$BL['be_admin_struct_button']           = 'Introduir informaci&oacute; de la categora';
$BL['be_admin_struct_close']            = 'Cancel&middot;lar';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'Categories de fitxers';
$BL['be_admin_fcat_err']                = 'El nom de la categora &eacute;s buit';
$BL['be_admin_fcat_name']               = 'Nom de la categoria';
$BL['be_admin_fcat_needed']             = 'Necessari';
$BL['be_admin_fcat_button1']            = 'Actualitzar';
$BL['be_admin_fcat_button2']            = 'Afegir categoria';
$BL['be_admin_fcat_delmsg']             = 'Voleu eliminar la clau del fitxer?';
$BL['be_admin_fcat_fcat']               = 'Categoria de fitxers';
$BL['be_admin_fcat_err1']               = 'El nom de la clau de fitxer &eacute;s buit';
$BL['be_admin_fcat_fkeyname']           = 'Nom de la clau de fitxer';
$BL['be_admin_fcat_exit']               = 'Cancel&middot;lar';
$BL['be_admin_fcat_addkey']             = 'Afegeix clau nova';
$BL['be_admin_fcat_editcat']            = 'Edita el nom de la categoria';
$BL['be_admin_fcat_delcatmsg']          = 'Segur que voleu eliminar la categoria de fitxers?';
$BL['be_admin_fcat_delcat']             = 'Elimina la categoria de fitxers';
$BL['be_admin_fcat_delkey']             = 'Elimina nom de la clau de fitxer';
$BL['be_admin_fcat_editkey']            = 'Edita la clau';
$BL['be_admin_fcat_addcat']             = 'Crea una nova categoria de fitxers';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'Configuraci&oacute; del website: composici&oacute; de p&agrave;gina';
$BL['be_admin_page_align']              = 'Alineaci&oacute; de la p&agrave;gina';
$BL['be_admin_page_align_left']         = 'Alineaci&oacute; normal (esquerra) del contingut total de la p&agrave;gina';
$BL['be_admin_page_align_center']       = 'Centra el contingut total de la p&agrave;gina';
$BL['be_admin_page_align_right']        = 'Alineaci&oacute; dreta del contingut total de la p&agrave;gina';
$BL['be_admin_page_margin']             = 'Marge';
$BL['be_admin_page_top']                = 'Superior';
$BL['be_admin_page_bottom']             = 'Inferior';
$BL['be_admin_page_left']               = 'Esquerra';
$BL['be_admin_page_right']              = 'Dreta';
$BL['be_admin_page_bg']                 = 'Fons';
$BL['be_admin_page_color']              = 'Color';
$BL['be_admin_page_height']             = 'Al&ccedil;&agrave;ria';
$BL['be_admin_page_width']              = 'Amplada';
$BL['be_admin_page_main']               = 'Principal';
$BL['be_admin_page_leftspace']          = 'Espai esq.';
$BL['be_admin_page_rightspace']         = 'Espai dreta';
$BL['be_admin_page_class']              = 'Class';
$BL['be_admin_page_image']              = 'Imatge';
$BL['be_admin_page_text']               = 'Text';
$BL['be_admin_page_link']               = 'Enlla&ccedil;';
$BL['be_admin_page_js']                 = 'Javascript';
$BL['be_admin_page_visited']            = 'Visitat';
$BL['be_admin_page_pagetitle']          = 'T&iacute;tol de p&agrave;g.';
$BL['be_admin_page_addtotitle']         = 'Afegeix al t&iacute;tol';
$BL['be_admin_page_category']           = 'Categoria';
$BL['be_admin_page_articlename']        = 'Nom de l\'article';
$BL['be_admin_page_blocks']             = 'Blocs';
$BL['be_admin_page_allblocks']          = 'Tots els blocs';
$BL['be_admin_page_col1']               = 'Composici&oacute; amb 3 columnes';
$BL['be_admin_page_col2']               = 'Composici&oacute;n amb 2 columnes (principal a la dreta, men&uacute; a la esquerra)';
$BL['be_admin_page_col3']               = 'Composici&oacute;n amb 2 columnas (principal a la esquerra, men&uacute; a la dreta)';
$BL['be_admin_page_col4']               = 'Composici&oacute;n amb una columna';
$BL['be_admin_page_header']             = 'Cap&ccedil;alera';
$BL['be_admin_page_footer']             = 'Peu';
$BL['be_admin_page_topspace']           = 'Espai superior';
$BL['be_admin_page_bottomspace']        = 'Espai inferior';
$BL['be_admin_page_button']             = 'Desar composici&oacute; de p&agrave;gina';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'Configuraci&oacute; del website: informaci&oacute; de CSS';
$BL['be_admin_css_css']                 = 'CSS';
$BL['be_admin_css_button']              = 'Desar informaci&oacute; de CSS';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'Configuraci&oacute; del website: plantilles';
$BL['be_admin_tmpl_default']            = 'Predeterminada';
$BL['be_admin_tmpl_add']                = 'Afegeix plantilla';
$BL['be_admin_tmpl_edit']               = 'Edita la plantilla';
$BL['be_admin_tmpl_new']                = 'en crea una nova';
$BL['be_admin_tmpl_css']                = 'Fitxer CSS';
$BL['be_admin_tmpl_head']               = 'HTML head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'Error';
$BL['be_admin_tmpl_button']             = 'Desar plantilla';
$BL['be_admin_tmpl_name']               = 'Nom';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'Estructura del website i llista d\'articles';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'Cal afegir un t&iacute;tol';
$BL['be_article_err2']                  = 'La data de comen&ccedil;ament &eacute;s incorrecta - canviar-la a la data d\'avui';
$BL['be_article_err3']                  = 'La data de caducitat &eacute;s incorrecta - canviar-la a la data d\'avui';
$BL['be_article_title1']                = 'Informaci&oacute; sobre l\'article';
$BL['be_article_cat']                   = 'Categoria';
$BL['be_article_atitle']                = 'T&iacute;tol';
$BL['be_article_asubtitle']             = 'Subt&iacute;tol';
$BL['be_article_abegin']                = 'Data d\'aparici&oacute;';
$BL['be_article_aend']                  = 'Caduca';
$BL['be_article_aredirect']             = 'Redirigir a';
$BL['be_article_akeywords']             = 'Mots clau';
$BL['be_article_asummary']              = 'Resum';
$BL['be_article_abutton']               = 'Crear un article nou';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'La data de caducitat &eacute;s incorrecta - canviar-la a la data d\'avui + una setmana';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'Edita la informaci&oacute; b&agrave;sica de l\'article';
$BL['be_article_eslastedit']            = 'Darrera modificaci&oacute;';
$BL['be_article_esnoupdate']            = 'No actualitzat';
$BL['be_article_esbutton']              = 'Actualitzar la informaci&oacute; de l\'article';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'Contingut de l\'article';
$BL['be_article_cnt_type']              = 'Tipus';
$BL['be_article_cnt_space']             = 'Espai';
$BL['be_article_cnt_before']            = 'Abans';
$BL['be_article_cnt_after']             = 'Despr&eacute;s';
$BL['be_article_cnt_top']               = 'A dalt';
$BL['be_article_cnt_ctitle']            = 'T&iacute;tol';
$BL['be_article_cnt_back']              = 'Informaci&oacute; completa de l\'article';
$BL['be_article_cnt_button1']           = 'Actualitzar el contingut';
$BL['be_article_cnt_button2']           = 'Crear contingut';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'Informaci&oacute; de l\'article';
$BL['be_article_cnt_ledit']             = 'Edita l\'article';
$BL['be_article_cnt_lvisible']          = 'Canvia visible/invisible';
$BL['be_article_cnt_ldel']              = 'Elimina aquest article';
$BL['be_article_cnt_ldeljs']            = 'Segur que voleu eliminar l\'article?';
$BL['be_article_cnt_redirect']          = 'Redirecci&oacute;';
$BL['be_article_cnt_edited']            = 'Editat per';
$BL['be_article_cnt_start']             = 'Data d\'aparici&oacute;';
$BL['be_article_cnt_end']               = 'Data de caducitat';
$BL['be_article_cnt_add']               = 'Afegeix contingut nou';
$BL['be_article_cnt_up']                = 'Mou el contingut cap a dalt';
$BL['be_article_cnt_down']              = 'Mou el contingut cap a baix';
$BL['be_article_cnt_edit']              = 'Edita el contingut';
$BL['be_article_cnt_delpart']           = 'Elimina aquest contingut de l\'article';
$BL['be_article_cnt_delpartjs']         = 'Segur que voleu eliminar aquest contingut?';
$BL['be_article_cnt_center']            = 'Centre d\'articles';

// content forms
$BL['be_cnt_plaintext']                 = 'Sols text';
$BL['be_cnt_htmltext']                  = 'Text HTML';
$BL['be_cnt_image']                     = 'Imatge';
$BL['be_cnt_position']                  = 'Posici&oacute;';
$BL['be_cnt_pos0']                      = 'A dalt, esquerra';
$BL['be_cnt_pos1']                      = 'A dalt, centre';
$BL['be_cnt_pos2']                      = 'A dalt, dreta';
$BL['be_cnt_pos3']                      = 'A baix, esquerra';
$BL['be_cnt_pos4']                      = 'A baix, centre';
$BL['be_cnt_pos5']                      = 'A baix, dreta';
$BL['be_cnt_pos6']                      = 'Dins el text, esquerra';
$BL['be_cnt_pos7']                      = 'Dins el text, dreta';
$BL['be_cnt_pos0i']                     = 'Alinea la imatge a dalt i a la esquerra del bloc de text';
$BL['be_cnt_pos1i']                     = 'Alinea la imatge a dalt i al centre del bloc de text';
$BL['be_cnt_pos2i']                     = 'Alinea la imatge a dalt i a la dreta del bloc de text';
$BL['be_cnt_pos3i']                     = 'Alinea la imatge a baix i a la esquerra del bloc de text';
$BL['be_cnt_pos4i']                     = 'Alinea la imatge a baix i al centre del bloc de text';
$BL['be_cnt_pos5i']                     = 'Alinea la imatge a dalt i a la dreta del bloc de text';
$BL['be_cnt_pos6i']                     = 'Alinea la imatge a la esquerra dins del bloc de text';
$BL['be_cnt_pos7i']                     = 'Alinea la imatge a la dreta dins del bloc de text';
$BL['be_cnt_maxw']                      = 'Amplada m&agrave;x.';
$BL['be_cnt_maxh']                      = 'Al&ccedil;&agrave;ria m&agrave;x.';
$BL['be_cnt_enlarge']                   = 'S\'amplia al clicar';
$BL['be_cnt_caption']                   = 'Descripci&oacute;';
$BL['be_cnt_subject']                   = 'Tema';
$BL['be_cnt_recipient']                 = 'Destinatari';
$BL['be_cnt_buttontext']                = 'Text del bot&oacute;';
$BL['be_cnt_sendas']                    = 'Envia com';
$BL['be_cnt_text']                      = 'Text';
$BL['be_cnt_html']                      = 'HTML';
$BL['be_cnt_formfields']                = 'Camp de formulari';
$BL['be_cnt_code']                      = 'C&oacute;digo';
$BL['be_cnt_infotext']                  = 'Text informatiu';
$BL['be_cnt_subscription']              = 'Subscripci&oacute;';
$BL['be_cnt_labelemail']                = 'Etiqueta correu';
$BL['be_cnt_tablealign']                = 'Alinea taula';
$BL['be_cnt_labelname']                 = 'Etiqueta nom';
$BL['be_cnt_labelsubsc']                = 'Etiqueta subscr.';
$BL['be_cnt_allsubsc']                  = 'Totes les subscripcions';
$BL['be_cnt_default']                   = 'Predeterminada';
$BL['be_cnt_left']                      = 'Esquerra';
$BL['be_cnt_center']                    = 'Centre';
$BL['be_cnt_right']                     = 'Dreta';
$BL['be_cnt_buttontext']                = 'Text bot&oacute;';
$BL['be_cnt_successtext']               = 'Satisfactori';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'canvi.email';
$BL['be_cnt_openimagebrowser']          = 'Obre l\'explorador d\'imatges';
$BL['be_cnt_openfilebrowser']           = 'Obre l\'explorador de fitxers';
$BL['be_cnt_sortup']                    = 'Mou cap a dalt';
$BL['be_cnt_sortdown']                  = 'Mou cap a baix';
$BL['be_cnt_delimage']                  = 'Elimina la imatge seleccionada';
$BL['be_cnt_delfile']                   = 'Elimina el fitxer seleccionat';
$BL['be_cnt_delmedia']                  = 'Elimina el mitj&agrave; seleccionat';
$BL['be_cnt_column']                    = 'Columna';
$BL['be_cnt_imagespace']                = 'Espai de la imatge';
$BL['be_cnt_directlink']                = 'Enlla&ccedil; directe';
$BL['be_cnt_target']                    = 'Objectiu';
$BL['be_cnt_target1']                   = 'Dins una finestra nova';
$BL['be_cnt_target2']                   = 'Dins el marc superior de la finestra';
$BL['be_cnt_target3']                   = 'Dins la mateixa finestra sense marcs (frames)';
$BL['be_cnt_target4']                   = 'Dins el mateix marc o finestra';
$BL['be_cnt_bullet']                    = 'Llista de punts';
$BL['be_cnt_linklist']                  = 'Llista d\'enlla&ccedil;os';
$BL['be_cnt_plainhtml']                 = 'HTML';
$BL['be_cnt_files']                     = 'Fitxers';
$BL['be_cnt_description']               = 'Descripci&oacute;';
$BL['be_cnt_linkarticle']               = 'Enlla&ccedil; a article';
$BL['be_cnt_articles']                  = 'Articles';
$BL['be_cnt_movearticleto']             = 'Mou l\'article seleccionat a la llista d\'enlla&ccedil;os a articles';
$BL['be_cnt_removearticleto']           = 'Treu l\'article seleccionat de la llista d\'enlla&ccedil;os a articles';
$BL['be_cnt_mediatype']                 = 'Tipus de mitj&agrave;';
$BL['be_cnt_control']                   = 'Control';
$BL['be_cnt_showcontrol']               = 'Mostra la barra de control';
$BL['be_cnt_autoplay']                  = 'Autoplay';
$BL['be_cnt_source']                    = 'Origen';
$BL['be_cnt_internal']                  = 'Intern';
$BL['be_cnt_openmediabrowser']          = 'Obre l\'explorador de mitjans';
$BL['be_cnt_external']                  = 'Extern';
$BL['be_cnt_mediapos0']                 = 'Esquerra (predeterminat)';
$BL['be_cnt_mediapos1']                 = 'Centre';
$BL['be_cnt_mediapos2']                 = 'Dreta';
$BL['be_cnt_mediapos3']                 = 'Bloc, esquerra';
$BL['be_cnt_mediapos4']                 = 'Bloc, dreta';
$BL['be_cnt_mediapos0i']                = 'Alinea mitj&agrave; a dalt i a la esquerra del bloc de text';
$BL['be_cnt_mediapos1i']                = 'Alinea mitj&agrave; a dalt i al centre del bloc de text';
$BL['be_cnt_mediapos2i']                = 'Alinea mitj&agrave; a dalt i a la dreta del bloc de text';
$BL['be_cnt_mediapos3i']                = 'Alinea mitj&agrave; a la esquerra dins del bloc de text';
$BL['be_cnt_mediapos4i']                = 'Alinea mitj&agrave; a la dreta dins del bloc de text';
$BL['be_cnt_setsize']                   = 'Configura mida';
$BL['be_cnt_set1']                      = 'Configura mida del mitj&agrave; en 160x120px';
$BL['be_cnt_set2']                      = 'Configura mida del mitj&agrave; en 240x180px';
$BL['be_cnt_set3']                      = 'Configura mida del mitj&agrave; en 320x240px';
$BL['be_cnt_set4']                      = 'Configura mida del mitj&agrave; en 480x360px';
$BL['be_cnt_set5']                      = 'Esborra amplada i al&ccedil;&agrave;ria del mitj&agrave;';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'Crea nova composici&oacute; de p&agrave;gina';
$BL['be_admin_page_name']               = 'Nom de la composici&oacute;';
$BL['be_admin_page_edit']               = 'Edita la composici&oacute; de p&agrave;gina';
$BL['be_admin_page_render']             = 'Format amb';
$BL['be_admin_page_table']              = 'Taules';
$BL['be_admin_page_div']                = 'CSS div';
$BL['be_admin_page_custom']             = 'Personalitzat';
$BL['be_admin_page_custominfo']         = 'segons el bloc principal de la plantilla';
$BL['be_admin_tmpl_layout']             = 'Composici&oacute;';
$BL['be_admin_tmpl_nolayout']           = 'No hi ha cap composici&oacute; de p&agrave;gina disponible!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'Cerca';
$BL['be_cnt_results']                   = 'Resultats';
$BL['be_cnt_results_per_page']          = 'per p&agrave;gina (sense marcar, mostrar-los tots)';
$BL['be_cnt_opennewwin']                = 'Obre finestra nova';
$BL['be_cnt_searchlabeltext']           = 'Valors de text predefinits del formulari de recerca i de la p&agrave;gina de resultats.';
$BL['be_cnt_input']                     = 'Entrada';
$BL['be_cnt_style']                     = 'Estil';
$BL['be_cnt_result']                    = 'Resultat';
$BL['be_cnt_next']                      = 'Seg&uuml;ent';
$BL['be_cnt_previous']                  = 'Anterior';
$BL['be_cnt_align']                     = 'Alinear';
$BL['be_cnt_searchformtext']            = 'Els seg&uuml;ents texts es mostren quan s\'obre el formulari de recerca o els resultats d\'una recerca determinada no estan disponibles.';
$BL['be_cnt_intro']                     = 'Introduir';
$BL['be_cnt_noresult']                  = 'Cap resultat';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'Desactivar';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'Propietari de l\'article';
$BL['be_article_adminuser']             = 'L\'usuari &eacute;s administrador';
$BL['be_article_username']              = 'Autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'Visible nom&eacute;s per usuaris registrats';
$BL['be_admin_struct_status']           = 'Estat del men&uacute; \'frontend\'';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'Men&uacute; d\'articles';
$BL['be_cnt_sitelevel']                 = 'Nivell del website';
$BL['be_cnt_sitecurrent']               = 'Nivell actual del website';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'Text d\'acollida';
$BL['be_ctype_ecard']                   = 'E-card';
$BL['be_ctype_blog']                    = 'Blog';
$BL['be_cnt_ecardtext']                 = 'T&iacute;tol/e-card';
$BL['be_cnt_ecardtmpl']                 = 'tmpl correu';
$BL['be_cnt_ecard_image']               = 'Imatge e-card';
$BL['be_cnt_ecard_title']               = 'T&iacute;tol e-card';
$BL['be_cnt_alignment']                 = 'Alineació';
$BL['be_cnt_ecardform']                 = 'tmpl formulari';
$BL['be_cnt_ecardform_err']             = 'Els camps marcats * son obligatoris';
$BL['be_cnt_ecardform_sender']          = 'Remitent';
$BL['be_cnt_ecardform_recipient']       = 'Destinatari';
$BL['be_cnt_ecardform_name']            = 'Nom';
$BL['be_cnt_ecardform_msgtext']         = 'El vostre missatge al destinatari';
$BL['be_cnt_ecardform_button']          = 'Envia e-card';
$BL['be_cnt_ecardsend']                 = 'tmpl enviat';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Text d\'acollida predeterminat';
$BL['be_admin_startup_text']            = 'Text';
$BL['be_admin_startup_button']          = 'Desar text d\'acollida';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'Llibre de visites/comentaris';
$BL['be_cnt_guestbook_listing']         = 'Llistat';
$BL['be_cnt_guestbook_listing_all']     = 'Llista tots els registres';
$BL['be_cnt_guestbook_list']            = 'Llista';
$BL['be_cnt_guestbook_perpage']         = 'per p&agrave;gina';
$BL['be_cnt_guestbook_form']            = 'Formulari';
$BL['be_cnt_guestbook_signed']          = 'Firmat';
$BL['be_cnt_guestbook_nav']             = 'Navegaci&oacute;';
$BL['be_cnt_guestbook_before']          = 'Abans';
$BL['be_cnt_guestbook_after']           = 'Despr&eacute;s';
$BL['be_cnt_guestbook_entry']           = 'Annotació';
$BL['be_cnt_guestbook_edit']            = 'Edita';
$BL['be_cnt_ecardform_selector']        = 'Selector';
$BL['be_cnt_ecardform_radiobutton']     = 'But&oacute; de radio';
$BL['be_cnt_ecardform_javascript']      = 'Funcionalitat JavaScript';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'Nombre d\'articles presentats';

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'Butllet&iacute;';
$BL['be_newsletter_addnl']              = 'Afegeix butllet&iacute;';
$BL['be_newsletter_titleeditnl']        = 'Edita butllet&iacute;';
$BL['be_newsletter_newnl']              = 'Crear-ne un de nou';
$BL['be_newsletter_button_savenl']      = 'Desar butllet&iacute;';
$BL['be_newsletter_fromname']           = 'Nom del remitent';
$BL['be_newsletter_fromemail']          = 'Correu del remitent';
$BL['be_newsletter_replyto']            = 'Correu de resposta';
$BL['be_newsletter_changed']            = 'Darrera modificaci&oacute;';
$BL['be_newsletter_placeholder']        = 'Marcador d\'espai';
$BL['be_newsletter_htmlpart']           = 'Contingut HTML';
$BL['be_newsletter_textpart']           = 'Contingut TEXT';
$BL['be_newsletter_allsubscriptions']   = 'Totes les subscripcions';
$BL['be_newsletter_verifypage']         = 'Comprovar enlla&ccedil;';
$BL['be_newsletter_open']               = 'Entra HTML i TEXT';
$BL['be_newsletter_open1']              = '(clicar sobre la imatge per obrir-la)';
$BL['be_newsletter_sendnow']            = 'Enviar butllet&iacute;';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Atenci&oacute;!</strong> Enviar un butllet&iacute; a nombrosos destinataris és molt arriscat. Cal que comproveu abans els destinataris o possiblement estareu envian \'spam\'. Penseu-vos-ho b&eacute; abans d\'enviar el butllet&iacute; i envieu primer una prova.';
$BL['be_newsletter_attention1']         = 'Si heu fet canvis en las dades d\'aquest butllet&iacute;, cal que primer les deseu o no s\'aplicaran els canvis.';
$BL['be_newsletter_testemail']          = 'Comprovar adre&ccedil;a';
$BL['be_newsletter_sendnlbutton']       = 'Enviar butllet&iacute;';
$BL['be_newsletter_sendprocess']        = 'Proc&eacute;s d\'enviar';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Atenci&oacute;!</strong> No atureu el proc&eacute;s d\'enviar. Si ho feu, &eacute;s possible que un mateix destinatari rebi el butllet&iacute; m&eacute;s de dues vegades. Quan falla una tramesa, les adreces pendents d\'enviar es conserven en un array de sessi&oacute; i es tornen a utilitzar si repetiu immadiatament el proc&eacute;s d\'enviar.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">l\'adre&ccedil;a de correu comprovada <strong>###TEST###</strong> NO &eacute;s v&aacute;lida!<br />&nbsp;<br />Torni-ho a intentar!';
$BL['be_newsletter_to']                 = 'Destinataris';
$BL['be_newsletter_ready']              = 'Enviant butllet&iacute;: ENVIAT';
$BL['be_newsletter_readyfailed']        = 'No s\'ha pogut enviar el butllet&iacute; a';
$BL['be_subnav_msg_subscribers']        = 'Llistat de subscriptors';

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'Mapa del site';
$BL['be_cnt_sitemap_catimage']          = 'Icona de nivell';
$BL['be_cnt_sitemap_articleimage']      = 'Icona d\'article';
$BL['be_cnt_sitemap_display']           = 'Mostrar';
$BL['be_cnt_sitemap_structuronly']      = 'nom&eacute;s nivells d\'estructura';
$BL['be_cnt_sitemap_structurarticle']   = 'nivells d\'estructura + articles';
$BL['be_cnt_sitemap_catclass']          = 'classe del nivell';
$BL['be_cnt_sitemap_articleclass']      = 'classe de l\'article';
$BL['be_cnt_sitemap_count']             = 'comptador';
$BL['be_cnt_sitemap_classcount']        = 'afegir a nom de classe';
$BL['be_cnt_sitemap_noclasscount']      = 'no afegir a nom de classe';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'Oferta';
$BL['be_cnt_bid_bidtext']               = 'Text de la oferta';
$BL['be_cnt_bid_sendtext']              = 'Text enviat';
$BL['be_cnt_bid_verifiedtext']          = 'Text verificat';
$BL['be_cnt_bid_errortext']             = 'Oferta esborrada';
$BL['be_cnt_bid_verifyemail']           = 'Comprovar adre&ccedil;a de correu';
$BL['be_cnt_bid_startbid']              = 'Oferta inicial';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'Augmentar en';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'Contingut extern';
$BL['be_cnt_pages_select']              = 'Escolliu fitxer';
$BL['be_cnt_pages_fromfile']            = 'Fitxer de la estractura';
$BL['be_cnt_pages_manually']            = 'Fitxer/path/URL personalitzat';
$BL['be_cnt_pages_cust']                = 'Fitxer/URL';
$BL['be_cnt_pages_from']                = 'Origen';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'Imatges amb rollover';
$BL['be_cnt_reference_basis']           = 'Alineaci&oacute;';
$BL['be_cnt_reference_horizontal']      = 'Horizontal';
$BL['be_cnt_reference_vertical']        = 'Vertical';
$BL['be_cnt_reference_aligntext']       = 'Imatge de refer&egrave;ncia petita';
$BL['be_cnt_reference_largetext']       = 'Imatge de refer&egrave;ncia gran';
$BL['be_cnt_reference_zoom']            = 'Zoom';
$BL['be_cnt_reference_middle']          = 'Mig';
$BL['be_cnt_reference_border']          = 'Vora';
$BL['be_cnt_reference_block']           = 'Dimensions del bloc';

// added: 31-05-2004
$BL['be_article_rendering']             = 'Presentaci&oacute;';
$BL['be_article_nosummary']             = 'No mostrar el resum amb l\'article complet';
$BL['be_article_forlist']               = 'Llista d\'articles';
$BL['be_article_forfull']               = 'Article complet';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<div style="font-size: 14px;">ATENCI&Oacute;!</div>El directori &quot;SETUP&quot; encara existeix!<br>Cal que l\'esborreu: pot representar un problema de securitat.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'Paraules inacceptables';
$BL['be_cnt_guestbook_flooding']        = 'Flooding';
$BL['be_cnt_guestbook_setcookie']       = 'Set cookie';
$BL['be_cnt_guestbook_allowed']         = 'Novament perm&eacute;s despr&eacute;s de';
$BL['be_cnt_guestbook_seconds']         = 'segons';
$BL['be_alias_ID']                      = 'Alias ID';
$BL['be_ftrash_delall']                 = "Segur que voleu eliminar \nTOTS ELS FITXERS de la paperera?";
$BL['be_ftrash_delallfiles']            = 'Eliminar tots els fitxers de la paperera';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'Importa subscriptors CSV';
$BL['be_newsletter_importtitle']        = 'Importar subscriptors al butllet&iacute;';
$BL['be_newsletter_entriesfound']       = 'Registres trobats';
$BL['be_newsletter_foundinfile']        = 'al fitxer';
$BL['be_newsletter_addresses']          = 'Adreces';
$BL['be_newsletter_csverror']           = 'El fitxer CSV importat sembla incorrecte! Verifiqueu el delimitador!';
$BL['be_newsletter_importall']          = 'Importar tots el registres';
$BL['be_newsletter_addressesadded']     = 'Adreces afegides.';
$BL['be_newsletter_newimport']          = 'Nova importaci&oacute;';
$BL['be_newsletter_importerror']        = 'Verifiqueu el fitxer CSV - no es pot afegir cap adreça!';
$BL['be_newsletter_shouldbe1']          = 'El fitxer CSV hauria de tenir aquest format';
$BL['be_newsletter_shouldbe2']          = 'per&ograve; podeu escollir un delimitador personalitzat.';
$BL['be_newsletter_sample']             = 'mostra';
$BL['be_newsletter_selectCSV']          = 'Selecciona fitxer CSV';
$BL['be_newsletter_delimeter']          = 'Delimitador';
$BL['be_newsletter_importCSV']          = 'Importa fitxer CSV';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'Ordre dels articles assignats';
$BL['be_admin_struct_orderdate']        = 'Data de creaci&oacute;';
$BL['be_admin_struct_orderchangedate']  = 'Data de modificaci&oacute;';
$BL['be_admin_struct_orderstartdate']   = 'Data d\'activaci&oacute;';
$BL['be_admin_struct_orderdesc']        = 'Descendent';
$BL['be_admin_struct_orderasc']         = 'Ascendent';
$BL['be_admin_struct_ordermanual']      = 'Manual (fletxa amunt/avall)';
$BL['be_cnt_sitemap_startid']           = 'Comen&ccedil;ant per';
