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


// Language: Italiano, Language Code: it
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'

// translated by: Fulvio Romanin (info@fulvioromanin.it)


$BL['usr_online']                       = 'utenti online';

// Login Page
$BL["login_text"]                       = 'Inserisci i tuoi dati di accesso';
$BL['login_error']                      = 'Errore durante il login!';
$BL["login_username"]                   = 'nome utente';
$BL["login_userpass"]                   = 'password';
$BL["login_button"]                     = 'Login';
$BL["login_lang"]                       = 'lingua del back office';

// phpwcms.php
$BL['be_nav_logout']                    = 'LOGOUT';
$BL['be_nav_articles']                  = 'ARTICOLO';
$BL['be_nav_files']                     = 'FILE';
$BL['be_nav_modules']                   = 'MODULI';
$BL['be_nav_messages']                  = 'MESSAGGI';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PROFILO';
$BL['be_nav_admin']                     = 'AMMINISTRAZIONE';
$BL['be_nav_discuss']                   = 'DISCUTI';

$BL['be_page_title']                    = 'phpwcms back office (amministrazione)';

$BL['be_subnav_article_center']         = 'amministrazione articoli';
$BL['be_subnav_article_new']            = 'nuovo articolo';
$BL['be_subnav_file_center']            = 'amministrazione files';
$BL['be_subnav_file_ftptakeover']       = 'caricamento da ftp';
$BL['be_subnav_mod_artists']            = 'artista, categoria, genere';
$BL['be_subnav_msg_center']             = 'amministrazione messaggi';
$BL['be_subnav_msg_new']                = 'nuovo messaggio';
$BL['be_subnav_msg_newsletter']         = 'iscrizioni alla newsletter';
$BL['be_subnav_chat_main']              = 'pagina principale della chat';
$BL['be_subnav_chat_internal']          = 'chat interna';
$BL['be_subnav_profile_login']          = 'informazioni di accesso';
$BL['be_subnav_profile_personal']       = 'dati personali';
$BL['be_subnav_admin_pagelayout']       = 'aspetto della pagina';
$BL['be_subnav_admin_templates']        = 'templates';
$BL['be_subnav_admin_css']              = 'css standard';
$BL['be_subnav_admin_sitestructure']    = 'struttura del sito';
$BL['be_subnav_admin_users']            = 'amministrazione utenti';
$BL['be_subnav_admin_filecat']          = 'categorie dei file';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'ID articolo';
$BL['be_func_struct_preview']           = 'anteprima';
$BL['be_func_struct_edit']              = 'edita articolo';
$BL['be_func_struct_sedit']             = 'edita il livello di struttura';
$BL['be_func_struct_cut']               = 'taglia articolo';
$BL['be_func_struct_nocut']             = 'disabilita taglia articolo';
$BL['be_func_struct_svisible']          = 'cambia visibile/invisibile';
$BL['be_func_struct_spublic']           = 'cambia pubblico/non pubblico';
$BL['be_func_struct_sort_up']           = 'ordine ascendente';
$BL['be_func_struct_sort_down']         = 'ordine discendente';
$BL['be_func_struct_del_article']       = 'cancella articolo';
$BL['be_func_struct_del_jsmsg']         = 'Comfermi?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'crea un articolo nuovo nel livello';
$BL['be_func_struct_paste_article']     = 'incolla articolo nel livello';
$BL['be_func_struct_insert_level']      = 'inserisci il livello di struttura in';
$BL['be_func_struct_paste_level']       = 'incolla nel livello';
$BL['be_func_struct_cut_level']         = 'taglia il livello';
$BL['be_func_struct_no_cut']            = "Non si può cancellare la root!";
$BL['be_func_struct_no_paste1']         = "Non si può incollare qui!";
$BL['be_func_struct_no_paste2']         = 'è figlio nella root del livello';
$BL['be_func_struct_no_paste3']         = 'che dovrebbe essere incollato qui';
$BL['be_func_struct_paste_cancel']      = 'annulla il cambiamento di struttura del livello';
$BL['be_func_struct_del_struct']        = 'cancella il livello di struttura';
$BL['be_func_struct_del_sjsmsg']        = 'vuoi davvero \ncancellare il livello di struttura?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'apri';
$BL['be_func_struct_close']             = 'chiudi';
$BL['be_func_struct_empty']             = 'vuoto';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'testo semplice';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'codice';
$BL['be_ctype_textimage']               = 'testo c/immagine';
$BL['be_ctype_images']                  = 'immagini';
$BL['be_ctype_bulletlist']              = 'elenco puntato';
$BL['be_ctype_link']                    = 'link &amp; email';
$BL['be_ctype_linklist']                = 'link lista';
$BL['be_ctype_linkarticle']             = 'link articolo';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'lista dei file';
$BL['be_ctype_emailform']               = 'email form';
$BL['be_ctype_newsletter']              = 'newsletter';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Profilo creato con successo.';
$BL['be_profile_create_error']          = 'Errore nella creazione del profilo.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'Dati del profilo aggiornati con successo.';
$BL['be_profile_update_error']          = 'Errore di aggiornamento dati.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'il nome utente {VAL} non è valido';
$BL['be_profile_account_err2']          = 'password troppo corta (solo {VAL} caratteri: ne servono almeno 5)';
$BL['be_profile_account_err3']          = 'la password deve essere identica';
$BL['be_profile_account_err4']          = 'email {VAL} non valido';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'i tuoi dati personali';
$BL['be_profile_data_text']             = 'i dati personali sono opzionali. Questo può aiutare altri utenti o visitatori del sito a sapere di più su di te, le tue abilità e interessi. Se selezioni la checkbox appropriata gli utenti possono vedere le informazioni sul tuo profilo nell"area pubblica o negli articoli (se desiderato).';
$BL['be_profile_label_title']           = 'titolo';
$BL['be_profile_label_firstname']       = 'nome';
$BL['be_profile_label_name']            = 'cognome';
$BL['be_profile_label_company']         = 'società';
$BL['be_profile_label_street']          = 'indirizzo';
$BL['be_profile_label_city']            = 'città';
$BL['be_profile_label_state']           = 'provincia, stato';
$BL['be_profile_label_zip']             = 'codice postale';
$BL['be_profile_label_country']         = 'stato';
$BL['be_profile_label_phone']           = 'telefono';
$BL['be_profile_label_fax']             = 'fax';
$BL['be_profile_label_cellphone']       = 'cellulare';
$BL['be_profile_label_signature']       = 'firma';
$BL['be_profile_label_notes']           = 'note';
$BL['be_profile_label_profession']      = 'professione';
$BL['be_profile_label_newsletter']      = 'newsletter';
$BL['be_profile_text_newsletter']       = 'voglio ricevere la newsletter di phpwcms.';
$BL['be_profile_label_public']          = 'pubblico';
$BL['be_profile_text_public']           = 'Chiunque può vedere i miei dati personali.';
$BL['be_profile_label_button']          = 'aggiorna i dati personali';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'informazioni di accesso';
$BL['be_profile_account_text']          = 'Normalmente non è necessario cambiare il nome utente.<br />Dovreste tuttavia cambiare la password di quando in quando per incrementare la sicurezza.';
$BL['be_profile_label_err']             = 'per cortesia controlla';
$BL['be_profile_label_username']        = 'nome utente';
$BL['be_profile_label_newpass']         = 'nuova password';
$BL['be_profile_label_repeatpass']      = 'ripeti nuova pwd';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'aggiorna i dati di accesso';
$BL['be_profile_label_lang']            = 'lingua';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'prendi i files caricati (uploadati) via ftp';
$BL['be_ftptakeover_mark']              = 'seleziona';
$BL['be_ftptakeover_available']         = 'files disponibili';
$BL['be_ftptakeover_size']              = 'dimensione';
$BL['be_ftptakeover_nofile']            = 'nessun file disponibile &#8211; devi caricarne almeno uno via ftp';
$BL['be_ftptakeover_all']               = 'TUTTI';
$BL['be_ftptakeover_directory']         = 'cartella';
$BL['be_ftptakeover_rootdir']           = 'cartella di root';
$BL['be_ftptakeover_needed']            = 'necessario!!! (devi selezionarne uno)';
$BL['be_ftptakeover_optional']          = 'opzionale';
$BL['be_ftptakeover_keywords']          = 'parole chiave';
$BL['be_ftptakeover_additional']        = 'aggiuntivo';
$BL['be_ftptakeover_longinfo']          = 'informazioni dettagliate';
$BL['be_ftptakeover_status']            = 'status';
$BL['be_ftptakeover_active']            = 'attivo';
$BL['be_ftptakeover_public']            = 'pubblico';
$BL['be_ftptakeover_createthumb']       = 'crea icona';
$BL['be_ftptakeover_button']            = 'prendi i files selezionati';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'file center';
$BL['be_ftab_createnew']                = 'creat una nuova cartella nella root';
$BL['be_ftab_paste']                    = 'incolla nella root il file in memoria';
$BL['be_ftab_disablethumb']             = 'disabilita le icone nella lista';
$BL['be_ftab_enablethumb']              = 'disabilita le icone nella lista';
$BL['be_ftab_private']                  = 'files&nbsp;privati';
$BL['be_ftab_public']                   = 'files&nbsp;pubblici';
$BL['be_ftab_search']                   = 'cerca';
$BL['be_ftab_trash']                    = 'cestino';
$BL['be_ftab_open']                     = 'apri tutte le cartelle';
$BL['be_ftab_close']                    = 'chiudi tutte le cartelle aperte';
$BL['be_ftab_upload']                   = 'carica il file nella cartella di root';
$BL['be_ftab_filehelp']                 = 'apri l"aiuto del file';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'cartella di root';
$BL['be_fpriv_title']                   = 'crea nuova cartella';
$BL['be_fpriv_inside']                  = 'dentro';
$BL['be_fpriv_error']                   = 'errore: dai un nome alla cartella';
$BL['be_fpriv_name']                    = 'nome';
$BL['be_fpriv_status']                  = 'status';
$BL['be_fpriv_button']                  = 'crea nuova cartella';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'edita cartella';
$BL['be_fpriv_newname']                 = 'nuovo nome';
$BL['be_fpriv_updatebutton']            = 'aggiorna le info della cartella';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'seleziona un file da caricare';
$BL['be_fprivup_err2']                  = 'la dimensione del file caricato è maggiore di';
$BL['be_fprivup_err3']                  = 'Errore nella scrittura del file nella cartella apposita';
$BL['be_fprivup_err4']                  = 'Errore nella creazione della cartella utente.';
$BL['be_fprivup_err5']                  = 'nessuna icona';
$BL['be_fprivup_err6']                  = 'Errore del server - contatta il tuo <a href="mailto:{VAL}">webmaster</a> per informazioni!';
$BL['be_fprivup_title']                 = 'carica files';
$BL['be_fprivup_button']                = 'carica files';
$BL['be_fprivup_upload']                = 'carica';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'edita le informazioni sul file';
$BL['be_fprivedit_filename']            = 'nome del file';
$BL['be_fprivedit_created']             = 'creato';
$BL['be_fprivedit_dateformat']          = 'm-d-Y H:i';
$BL['be_fprivedit_err1']                = 'nome del file(ritorna all"originale)';
$BL['be_fprivedit_clockwise']           = 'ruota l"icona in senso orario [file originale +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'ruota l"icona in senso antiorario [file originale -90&deg;]';
$BL['be_fprivedit_button']              = 'aggiorna le informazioni sul file';
$BL['be_fprivedit_size']                = 'dimensione';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'carica files nella cartella';
$BL['be_fprivfunc_makenew']             = 'crea nuova directory interna';
$BL['be_fprivfunc_paste']               = 'incolla file in memoria nella cartella';
$BL['be_fprivfunc_edit']                = 'edita cartella';
$BL['be_fprivfunc_cactive']             = 'cambia attivo/inattivo';
$BL['be_fprivfunc_cpublic']             = 'cambia pubblico/non pubblico';
$BL['be_fprivfunc_deldir']              = 'cancella cartella';
$BL['be_fprivfunc_jsdeldir']            = 'Vuoi davvero \ncancellare la cartella';
$BL['be_fprivfunc_notempty']            = 'cartella {VAL} non vuota!';
$BL['be_fprivfunc_opendir']             = 'apri cartella';
$BL['be_fprivfunc_closedir']            = 'chiudi cartella';
$BL['be_fprivfunc_dlfile']              = 'scarica file';
$BL['be_fprivfunc_clipfile']            = 'file in memoria';
$BL['be_fprivfunc_cutfile']             = 'taglia';
$BL['be_fprivfunc_editfile']            = 'edita le informazioni del file';
$BL['be_fprivfunc_cactivefile']         = 'cambia attivo/inattivo';
$BL['be_fprivfunc_cpublicfile']         = 'cambia pubblico/non pubblico';
$BL['be_fprivfunc_movetrash']           = 'metti nel cestino';
$BL['be_fprivfunc_jsmovetrash1']        = 'Vuoi davvero mettere';
$BL['be_fprivfunc_jsmovetrash2']        = 'nel cestino?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'nessun file o cartella privato';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'utente';
$BL['be_fpublic_nofiles']               = 'nessun file o cartella pubblico';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'il cestino è vuoto';
$BL['be_ftrash_show']                   = 'mostra i files privati';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Vuoi ripristianre {VAL} \ne rimetterlo nella lista privata?';
$BL['be_ftrash_delete']                 = 'Vuoi cancellare {VAL}?';
$BL['be_ftrash_undo']                   = 'ripristina';
$BL['be_ftrash_delfinal']               = 'cancellazione definitiva';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'la stringa di ricerca è vuota.';
$BL['be_fsearch_title']                 = 'cerca files';
$BL['be_fsearch_infotext']              = 'Questa è una ricerca semplificata per le informazioni dei files. Cerca nelle keywords, nomi dei files e descrizioni dei files.<br />. Nessun supporto per le wildcards. Separate la ricerca di parole<br />multiple con uno spazio. Selezionate AND/OR e quali files cercare: personali/pubblici.';
$BL['be_fsearch_nonfound']              = 'nessun file trovato. provate a modificare i termini di ricerca';
$BL['be_fsearch_fillin']                = 'per cortesia scrivete nel campo soprastante i termini di ricerca.';
$BL['be_fsearch_searchlabel']           = 'cerca';
$BL['be_fsearch_startsearch']           = 'inizia la ricerca';
$BL['be_fsearch_and']                   = 'AND';
$BL['be_fsearch_or']                    = 'OR';
$BL['be_fsearch_all']                   = 'tutti i files';
$BL['be_fsearch_personal']              = 'privati';
$BL['be_fsearch_public']                = 'pubblici';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'chat interna';
$BL['be_chat_info']                     = 'Qui potete chattare con altre persone che hanno accesso al back office. Questa chat è per parlarsi in tempo reale ma potete lasciare anche un messaggio se volete che tutti leggano.';
$BL['be_chat_start']                    = 'cliccate qui per iniziare a chattare';
$BL['be_chat_lines']                    = 'chat:';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'centro messaggi';
$BL['be_msg_new']                       = 'nuovi';
$BL['be_msg_old']                       = 'vecchi';
$BL['be_msg_senttop']                   = 'inviati';
$BL['be_msg_del']                       = 'cancellati';
$BL['be_msg_from']                      = 'da';
$BL['be_msg_subject']                   = 'argomento';
$BL['be_msg_date']                      = 'data/ora';
$BL['be_msg_close']                     = 'chiudi messaggio';
$BL['be_msg_create']                    = 'crea nuovo messaggio';
$BL['be_msg_reply']                     = 'rispondi a questo messaggio';
$BL['be_msg_move']                      = 'sposta questo messaggio nel cestino';
$BL['be_msg_unread']                    = 'messaggi nuovi o non letto';
$BL['be_msg_lastread']                  = 'ultimi {VAL} messaggi letti';
$BL['be_msg_lastsent']                  = 'ultimi {VAL} messaggi inviati';
$BL['be_msg_marked']                    = 'messaggi segnati per la cancellazione';
$BL['be_msg_nomsg']                     = 'nessun messaggio in questa cartella';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'mandato da';
$BL['be_msg_on']                        = 'su';
$BL['be_msg_msg']                       = 'messaggio';
$BL['be_msg_err1']                      = 'non hai indicato il destinatario...';
$BL['be_msg_err2']                      = 'non hai indicato l"argomento...';
$BL['be_msg_err3']                      = 'non hai scritto nulla nel messaggio! che lo mandi a fare? :)';
$BL['be_msg_sent']                      = 'il messaggio è satto inviato!';
$BL['be_msg_fwd']                       = 'sarai riindirizzato al centro messaggi o';
$BL['be_msg_newmsgtitle']               = 'scrivi un messaggio nuovo';
$BL['be_msg_err']                       = 'errore nell"invio del messaggio';
$BL['be_msg_sendto']                    = 'smanda messaggio a ';
$BL['be_msg_available']                 = 'lista disponibile di destinatari';
$BL['be_msg_all']                       = 'manda un messaggio a tutti i destinatari selezionati';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'iscrizione alla newsletter';
$BL['be_newsletter_titleedit']          = 'edita iscrizione newsletter';
$BL['be_newsletter_new']                = 'crea nuova';
$BL['be_newsletter_add']                = 'aggiungi&nbsp;abbonamento&nbsp;newsletter';
$BL['be_newsletter_name']               = 'nome';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'salva abbonamento';
$BL['be_newsletter_button_cancel']      = 'cancella';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'nome utente non valido, scegline un altro';
$BL['be_admin_usr_err2']                = 'unome utente vuoto (obbligatorio)';
$BL['be_admin_usr_err3']                = 'password vuota (obbligatorio)';
$BL['be_admin_usr_err4']                = "email non valido";
$BL['be_admin_usr_err']                 = 'errore';
$BL['be_admin_usr_mailsubject']         = 'benvenuto nel back office di phpwcms';
$BL['be_admin_usr_mailbody']            = "BENVENUTO NEL BACK OFFICE DI PHPWCMS\n\n    nome utente: {LOGIN}\n    password: {PASSWORD}\n\n\nPuoi accedere da qui: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'aggiungi nome utente';
$BL['be_admin_usr_realname']            = 'nome vero';
$BL['be_admin_usr_setactive']           = 'attiva utente';
$BL['be_admin_usr_iflogin']             = 'se selezionato l"utente può accedere';
$BL['be_admin_usr_isadmin']             = 'utente è amministratore';
$BL['be_admin_usr_ifadmin']             = 'se selezionato l"utente ha diritti di amministratore';
$BL['be_admin_usr_verify']              = 'verifica';
$BL['be_admin_usr_sendemail']           = 'manda un e-mail al nuovo utente con le informazioni dell"account';
$BL['be_admin_usr_button']              = 'invia i dati dell"utente';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'edita account utente';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - dati account modificati';
$BL['be_admin_usr_emailbody']           = "PHPWCMS - INFORMAZIONI ACCOUNT UTENTE MODIFICATE\n\n    nome: {LOGIN}\n    password: {PASSWORD}\n\n\nPuoi accedere da qui: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[NESSUNA MODIFICA - USA LA VECCHIA PASSWORD]';
$BL['be_admin_usr_ebutton']             = 'aggiorna dati utente';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'lista utenti phpwcms';
$BL['be_admin_usr_ldel']                = 'ATTENZIONE!&#13Questo cancellerà l"utente';
$BL['be_admin_usr_create']              = 'crea nuovo utente';
$BL['be_admin_usr_editusr']             = 'edita utente';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'struttura del sito';
$BL['be_admin_struct_child']            = '(figlio di)';
$BL['be_admin_struct_index']            = 'indice(prima pagina del sito)';
$BL['be_admin_struct_cat']              = 'titolo della categoria';
$BL['be_admin_struct_hide1']            = 'nascondi';
$BL['be_admin_struct_hide2']            = 'questa&nbsp;category&nbsp;nel&nbsp;menu';
$BL['be_admin_struct_info']             = 'testo di informazione della categoria';
$BL['be_admin_struct_template']         = 'template';
$BL['be_admin_struct_alias']            = 'dai un alias a questa categoria';
$BL['be_admin_struct_visible']          = 'visibile';
$BL['be_admin_struct_button']           = 'invia i dati della categoria';
$BL['be_admin_struct_close']            = 'chiudi';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'categorie dei file';
$BL['be_admin_fcat_err']                = 'il nome della categoria è vuoto!';
$BL['be_admin_fcat_name']               = 'nome della categoria';
$BL['be_admin_fcat_needed']             = 'obbligatorio';
$BL['be_admin_fcat_button1']            = 'aggiorna';
$BL['be_admin_fcat_button2']            = 'crea';
$BL['be_admin_fcat_delmsg']             = 'Vuoi davvero\ncancellare la key del file?';
$BL['be_admin_fcat_fcat']               = 'categoria del file';
$BL['be_admin_fcat_err1']               = 'nome della key del file vuota!';
$BL['be_admin_fcat_fkeyname']           = 'nome della key del file';
$BL['be_admin_fcat_exit']               = 'esci editing';
$BL['be_admin_fcat_addkey']             = 'aggiungi nuova key';
$BL['be_admin_fcat_editcat']            = 'edita nome categoria';
$BL['be_admin_fcat_delcatmsg']          = 'Vuoi davvero\ncancellare categoria file?';
$BL['be_admin_fcat_delcat']             = 'cancella categoria file';
$BL['be_admin_fcat_delkey']             = 'cancella nome della key del file';
$BL['be_admin_fcat_editkey']            = 'edita key';
$BL['be_admin_fcat_addcat']             = 'crea nuova categoria file';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'aspetto del sito: layout della pagina';
$BL['be_admin_page_align']              = 'allineamento della pagina';
$BL['be_admin_page_align_left']         = 'allineamento standard (sinistra) dell"intero contenuto della pagina';
$BL['be_admin_page_align_center']       = 'centra l"intero contenuto della pagina';
$BL['be_admin_page_align_right']        = 'allinea a destra l"intero contenuto della pagina';
$BL['be_admin_page_margin']             = 'margine';
$BL['be_admin_page_top']                = 'intestazione';
$BL['be_admin_page_bottom']             = 'fondo';
$BL['be_admin_page_left']               = 'sinistra';
$BL['be_admin_page_right']              = 'destra';
$BL['be_admin_page_bg']                 = 'sfondo (immagine)';
$BL['be_admin_page_color']              = 'colore';
$BL['be_admin_page_height']             = 'altezza';
$BL['be_admin_page_width']              = 'larghezza';
$BL['be_admin_page_main']               = 'principale';
$BL['be_admin_page_leftspace']          = 'spazio a sinistra';
$BL['be_admin_page_rightspace']         = 'spazio a destra';
$BL['be_admin_page_class']              = 'classe';
$BL['be_admin_page_image']              = 'immagine';
$BL['be_admin_page_text']               = 'testo';
$BL['be_admin_page_link']               = 'link';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'visitato';
$BL['be_admin_page_pagetitle']          = 'titolo della pagina';
$BL['be_admin_page_addtotitle']         = 'aggiungi&nbsp;al&nbsp;titolo';
$BL['be_admin_page_category']           = 'categoria';
$BL['be_admin_page_articlename']        = 'nome&nbsp;articolo';
$BL['be_admin_page_blocks']             = 'blocco';
$BL['be_admin_page_allblocks']          = 'tutti i blocchi';
$BL['be_admin_page_col1']               = 'layout a 3 colonne';
$BL['be_admin_page_col2']               = 'layout a 2 colonne (colonna principale destra, colonna menu sinistra)';
$BL['be_admin_page_col3']               = 'layout a 2 colonne (colonna principale sinistra, colonna menu destra)';
$BL['be_admin_page_col4']               = 'layout a 1 colonna';
$BL['be_admin_page_header']             = 'intestazione';
$BL['be_admin_page_footer']             = 'fondo della pagina';
$BL['be_admin_page_topspace']           = 'spazio&nbsp;in cima';
$BL['be_admin_page_bottomspace']        = 'spazio&nbsp;in fondo';
$BL['be_admin_page_button']             = 'modifica layout pagina';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'aspetto del sito: dati css';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'salva dati css';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'templates sito';
$BL['be_admin_tmpl_default']            = 'standard';
$BL['be_admin_tmpl_add']                = 'aggiungi&nbsp;template';
$BL['be_admin_tmpl_edit']               = 'edita template';
$BL['be_admin_tmpl_new']                = 'crea nuovo';
$BL['be_admin_tmpl_css']                = 'file css ';
$BL['be_admin_tmpl_head']               = 'head dell"html';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'errore';
$BL['be_admin_tmpl_button']             = 'salva template';
$BL['be_admin_tmpl_name']               = 'nome';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'struttura del sito e lista articoli';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'inserisci un titolo';
$BL['be_article_err2']                  = 'data inizio articolo errata - corretta ad ora';
$BL['be_article_err3']                  = 'data termine articolo errata - corretta ad ora';
$BL['be_article_title1']                = 'informazioni articolo';
$BL['be_article_cat']                   = 'categoria';
$BL['be_article_atitle']                = 'titolo articolo';
$BL['be_article_asubtitle']             = 'sottotitolo';
$BL['be_article_abegin']                = 'inizia';
$BL['be_article_aend']                  = 'finisce';
$BL['be_article_aredirect']             = 'riindirizza a ';
$BL['be_article_akeywords']             = 'parole chiave';
$BL['be_article_asummary']              = 'testo';
$BL['be_article_abutton']               = 'crea nuovo articolo';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'data di termine errata - corretta ad ora + 1 settimana';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'edita informazioni articolo';
$BL['be_article_eslastedit']            = 'ultimo edit';
$BL['be_article_esnoupdate']            = 'form non aggiornato';
$BL['be_article_esbutton']              = 'aggiorna dati articolo';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'contenuto articolo';
$BL['be_article_cnt_type']              = 'tipo contenuto';
$BL['be_article_cnt_space']             = 'spazio';
$BL['be_article_cnt_before']            = 'prima';
$BL['be_article_cnt_after']             = 'dopo';
$BL['be_article_cnt_top']               = 'intestazione';
$BL['be_article_cnt_ctitle']            = 'titolo del contenuto';
$BL['be_article_cnt_back']              = 'informazioni complete articolo';
$BL['be_article_cnt_button1']           = 'aggiorna contenuto';
$BL['be_article_cnt_button2']           = 'crea contenuto';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'informazioni articolo';
$BL['be_article_cnt_ledit']             = 'edita articolo';
$BL['be_article_cnt_lvisible']          = 'cambia visibile/invisibile';
$BL['be_article_cnt_ldel']              = 'cancella articolo';
$BL['be_article_cnt_ldeljs']            = 'Cancella articolo?';
$BL['be_article_cnt_redirect']          = 'riindirizzamento';
$BL['be_article_cnt_edited']            = 'editato da';
$BL['be_article_cnt_start']             = 'data inizio';
$BL['be_article_cnt_end']               = 'data termine';
$BL['be_article_cnt_add']               = 'aggiungi nuova parte contenuto';
$BL['be_article_cnt_up']                = 'muovi contenuto su';
$BL['be_article_cnt_down']              = 'muovi contenuto giù';
$BL['be_article_cnt_edit']              = 'aggiungi parte contenuto';
$BL['be_article_cnt_delpart']           = 'cancella questa parte contenuto articolo';
$BL['be_article_cnt_delpartjs']         = 'Cancella questa parte contenuto?';
$BL['be_article_cnt_center']            = 'centro articoli';

// content forms
$BL['be_cnt_plaintext']                 = 'testo semplice';
$BL['be_cnt_htmltext']                  = 'testo html';
$BL['be_cnt_image']                     = 'immagine';
$BL['be_cnt_position']                  = 'posizione';
$BL['be_cnt_pos0']                      = 'Sopra, sinistra';
$BL['be_cnt_pos1']                      = 'Sopra, centro';
$BL['be_cnt_pos2']                      = 'Sopra, destra';
$BL['be_cnt_pos3']                      = 'Sotto, sinistra';
$BL['be_cnt_pos4']                      = 'Sotto, centro';
$BL['be_cnt_pos5']                      = 'Sotto, destra';
$BL['be_cnt_pos6']                      = 'Nel testo, sinistra';
$BL['be_cnt_pos7']                      = 'Nel testo, destra';
$BL['be_cnt_pos0i']                     = 'allinea immagine sopra e a sinistra del blocco di testo';
$BL['be_cnt_pos1i']                     = 'allinea immagine sopra e al centro del blocco di testo';
$BL['be_cnt_pos2i']                     = 'allinea immagine sopra e a destra del blocco di testo';
$BL['be_cnt_pos3i']                     = 'allinea immagine sotto e a sinistra del blocco di testo';
$BL['be_cnt_pos4i']                     = 'allinea immagine sotto e al centro del blocco di testo';
$BL['be_cnt_pos5i']                     = 'allinea immagine sotto e a destra del blocco di testo';
$BL['be_cnt_pos6i']                     = 'allinea immagine a sinistra dentro il blocco di testo';
$BL['be_cnt_pos7i']                     = 'allinea immagine a destra dentro il blocco di testo';
$BL['be_cnt_maxw']                      = 'max.&nbsp;larghezza';
$BL['be_cnt_maxh']                      = 'max.&nbsp;altezza';
$BL['be_cnt_enlarge']                   = 'clicca&nbsp;per fare allargare';
$BL['be_cnt_caption']                   = 'titolo';
$BL['be_cnt_subject']                   = 'argomento';
$BL['be_cnt_recipient']                 = 'destinatario';
$BL['be_cnt_buttontext']                = 'bottone testo';
$BL['be_cnt_sendas']                    = 'manda come';
$BL['be_cnt_text']                      = 'testo';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'campi del form';
$BL['be_cnt_code']                      = 'codice';
$BL['be_cnt_infotext']                  = 'testo&nbsp;info';
$BL['be_cnt_subscription']              = 'iscrizione';
$BL['be_cnt_labelemail']                = 'etichetta&nbsp;email';
$BL['be_cnt_tablealign']                = 'allinea&nbsp;tabella';
$BL['be_cnt_labelname']                 = 'nome&nbsp;etichetta';
$BL['be_cnt_labelsubsc']                = 'iscr.&nbsp;etichetta';
$BL['be_cnt_allsubsc']                  = 'iscr.&nbsp;tutte.';
$BL['be_cnt_default']                   = 'standard';
$BL['be_cnt_left']                      = 'sinistra';
$BL['be_cnt_center']                    = 'centro';
$BL['be_cnt_right']                     = 'destra';
$BL['be_cnt_buttontext']                = 'testo&nbsp;bottone';
$BL['be_cnt_successtext']               = 'testo&nbsp;successp';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'email logoff';
$BL['be_cnt_changemail']                = 'cambio.email';
$BL['be_cnt_openimagebrowser']          = 'apri browser immagini';
$BL['be_cnt_openfilebrowser']           = 'apri browser files';
$BL['be_cnt_sortup']                    = 'muovi su';
$BL['be_cnt_sortdown']                  = 'muovi giù';
$BL['be_cnt_delimage']                  = 'rimuovi immagine selezionata';
$BL['be_cnt_delfile']                   = 'rimuovi file selezionato';
$BL['be_cnt_delmedia']                  = 'rimuovi media selezionato';
$BL['be_cnt_column']                    = 'colonna';
$BL['be_cnt_imagespace']                = 'spazio&nbsp;immagine';
$BL['be_cnt_directlink']                = 'link diretto';
$BL['be_cnt_target']                    = 'obiettivo';
$BL['be_cnt_target1']                   = 'finestra nuova';
$BL['be_cnt_target2']                   = 'frame superiore della finestra';
$BL['be_cnt_target3']                   = 'stessa finestra senza frames';
$BL['be_cnt_target4']                   = 'stesso frame o finestra';
$BL['be_cnt_bullet']                    = 'indice puntuato';
$BL['be_cnt_linklist']                  = 'lista link';
$BL['be_cnt_plainhtml']                 = 'html semplice';
$BL['be_cnt_files']                     = 'files';
$BL['be_cnt_description']               = 'descrizione';
$BL['be_cnt_linkarticle']               = 'link articolo';
$BL['be_cnt_articles']                  = 'articoli';
$BL['be_cnt_movearticleto']             = 'muovi articolo selezionato a lista link articoli';
$BL['be_cnt_removearticleto']           = 'togli articolo selezionato a lista link articoli';
$BL['be_cnt_mediatype']                 = 'tipo di media';
$BL['be_cnt_control']                   = 'controllo';
$BL['be_cnt_showcontrol']               = 'mostra barra controlli';
$BL['be_cnt_autoplay']                  = 'autoplay';
$BL['be_cnt_source']                    = 'sorgente';
$BL['be_cnt_internal']                  = 'interno';
$BL['be_cnt_openmediabrowser']          = 'apri browser media';
$BL['be_cnt_external']                  = 'esterno';
$BL['be_cnt_mediapos0']                 = 'sinistra (standard)';
$BL['be_cnt_mediapos1']                 = 'centro';
$BL['be_cnt_mediapos2']                 = 'destra';
$BL['be_cnt_mediapos3']                 = 'blocco, sinistra';
$BL['be_cnt_mediapos4']                 = 'blocco, destra';
$BL['be_cnt_mediapos0i']                = 'allinea media sopra e sinistra del blocco di testo';
$BL['be_cnt_mediapos1i']                = 'allinea media sopra e centro del blocco di testo';
$BL['be_cnt_mediapos2i']                = 'allinea media sopra e destra del blocco di testo';
$BL['be_cnt_mediapos3i']                = 'allinea media a sinistra dentro al blocco di testo';
$BL['be_cnt_mediapos4i']                = 'allinea media a destra dentro al blocco di testo';
$BL['be_cnt_setsize']                   = 'definisci dimensioni';
$BL['be_cnt_set1']                      = 'definisci dimensioni media 160x120px';
$BL['be_cnt_set2']                      = 'definisci dimensioni media 240x180px';
$BL['be_cnt_set3']                      = 'definisci dimensioni media 320x240px';
$BL['be_cnt_set4']                      = 'definisci dimensioni media 480x360px';
$BL['be_cnt_set5']                      = 'annulla altezza e larghezza media';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'create new pagelayout';
$BL['be_admin_page_name']               = 'layout name';
$BL['be_admin_page_edit']               = 'edit pagelayout';
$BL['be_admin_page_render']             = 'rendering';
$BL['be_admin_page_table']              = 'table';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'custom';
$BL['be_admin_page_custominfo']         = 'from template main block';
$BL['be_admin_tmpl_layout']             = 'layout';
$BL['be_admin_tmpl_nolayout']           = 'No page layout available!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'search';
$BL['be_cnt_results']                   = 'results';
$BL['be_cnt_results_per_page']          = 'per&nbsp;page (if empty show max. 25)';
$BL['be_cnt_opennewwin']                = 'open new window';
$BL['be_cnt_searchlabeltext']           = 'these are predefined texts and values for the search form and search result page and texts are shown when more than the given count of results per page should be shown.';
$BL['be_cnt_input']                     = 'input';
$BL['be_cnt_style']                     = 'style';
$BL['be_cnt_result']                    = 'result';
$BL['be_cnt_next']                      = 'next';
$BL['be_cnt_previous']                  = 'previous';
$BL['be_cnt_align']                     = 'align';
$BL['be_cnt_searchformtext']            = 'the following texts are listed when the search form is opened or results for given search are (not) available.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'no result';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'disable';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'article owner';
$BL['be_article_adminuser']             = 'admin user';
$BL['be_article_username']              = 'author';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'visible for users logged on only';
$BL['be_admin_struct_status']           = 'frontend menu status';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
$BL['be_cnt_sitecurrent']               = 'current site level';

