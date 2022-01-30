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


// Language: French, Language Code: fr
// 04-04-2007 updated by Marcos Peebles www.piezo.be
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'utilisateurs en ligne';

// Login Page
$BL["login_text"]                       = 'entrez votre identifiant et votre mot de passe';
$BL['login_error']                      = 'erreur durant l&#39;identification!';
$BL["login_username"]                   = 'identifiant';
$BL["login_userpass"]                   = 'mot de passe';
$BL["login_button"]                     = 'connection';
$BL["login_lang"]                       = 'langue de l&#39;interface d&#39;administration';

// phpwcms.php
$BL['be_nav_logout']                    = 'DECONNEXION';
$BL['be_nav_articles']                  = 'ARTICLES';
$BL['be_nav_files']                     = 'FICHIERS';
$BL['be_nav_modules']                   = 'MODULES';
$BL['be_nav_messages']                  = 'MESSAGES';
$BL['be_nav_chat']                      = 'DISCUSSION';
$BL['be_nav_profile']                   = 'PROFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'DISCUTER';

$BL['be_page_title']                    = 'interface d&#39;administration';

$BL['be_subnav_article_center']         = 'centre d&#39;articles';
$BL['be_subnav_article_new']            = 'ajouter un article';
$BL['be_subnav_file_center']            = 'centre de fichiers';
$BL['be_subnav_file_ftptakeover']       = 'transfert depuis ftp';
$BL['be_subnav_mod_artists']            = 'artiste, cat&eacute;gorie, genre';
$BL['be_subnav_msg_center']             = 'centre messages';
$BL['be_subnav_msg_new']                = 'nouveau message';
$BL['be_subnav_msg_newsletter']         = 'lettres d&#39;infos > cat&eacute;gories'; // titre
$BL['be_subnav_chat_main']              = 'centre de discussion';
$BL['be_subnav_chat_internal']          = 'discussion interne';
$BL['be_subnav_profile_login']          = 'informations de connexion';
$BL['be_subnav_profile_personal']       = 'informations personnelles';
$BL['be_subnav_admin_pagelayout']       = 'mise en page';
$BL['be_subnav_admin_templates']        = 'gabarits';
$BL['be_subnav_admin_css']              = 'css par d&eacute;faut';
$BL['be_subnav_admin_sitestructure']    = 'structure du site';
$BL['be_subnav_admin_users']            = 'admin. des utilisateurs';
$BL['be_subnav_admin_filecat']          = 'cat&eacute;gories de fichiers';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'ID article';
$BL['be_func_struct_preview']           = 'pr&eacute;visualisation';
$BL['be_func_struct_edit']              = '&eacute;diter l&#39;article';
$BL['be_func_struct_sedit']             = '&eacute;diter la branche de l&#39;arborescence';
$BL['be_func_struct_cut']               = 'couper l&#39;article';
$BL['be_func_struct_nocut']             = 'd&eacute;sactiver couper l&#39;article';
$BL['be_func_struct_svisible']          = 'rendre visible/invisible';
$BL['be_func_struct_spublic']           = 'rendre public/non public';
$BL['be_func_struct_sort_up']           = 'tri croissant';
$BL['be_func_struct_sort_down']         = 'tri d&eacute;croissant';
$BL['be_func_struct_del_article']       = 'effacer article';
$BL['be_func_struct_del_jsmsg']         = 'Souhaitez-vous r&eacute;ellement effacer cet article?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'cr&eacute;er un nouvel article &agrave; ce niveau de l&#39;arborescence';
$BL['be_func_struct_paste_article']     = 'coller l&#39;article &agrave; ce niveau de l&#39;arborescence';
$BL['be_func_struct_insert_level']      = 'ins&eacute;rer la branche dans';
$BL['be_func_struct_paste_level']       = 'coller la branche';
$BL['be_func_struct_cut_level']         = 'couper la branche';
$BL['be_func_struct_no_cut']            = "impossible de couper la branche de base!";
$BL['be_func_struct_no_paste1']         = "impossible de coller &agrave; l'\endroit souhait&eacute;!";
$BL['be_func_struct_no_paste2']         = 'est enfant du r&eacute;peroire de base de l&#39;arborescence';
$BL['be_func_struct_no_paste3']         = 'devrait etre coll&eacute; ici';
$BL['be_func_struct_paste_cancel']      = 'annuler les modifications apport&eacute;es &agrave; l\arborescence';
$BL['be_func_struct_del_struct']        = 'effacer la branche';
$BL['be_func_struct_del_sjsmsg']        = 'voulez-vous vraiment effacer cette branche?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'ouvrir';
$BL['be_func_struct_close']             = 'fermer';
$BL['be_func_struct_empty']             = 'vide';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'texte brut';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'code';
$BL['be_ctype_textimage']               = 'texte +image';
$BL['be_ctype_images']                  = 'images';
$BL['be_ctype_bulletlist']              = 'liste &agrave; bulles';
$BL['be_ctype_link']                    = 'lien +email';
$BL['be_ctype_linklist']                = 'liste de liens';
$BL['be_ctype_linkarticle']             = 'lien articles';
$BL['be_ctype_multimedia']              = 'multimedia';
$BL['be_ctype_filelist']                = 'liste fichiers';
$BL['be_ctype_emailform']               = 'form. email';
$BL['be_ctype_newsletter']              = 'lettre d&#39;inf.';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'votre profil a &eacute;t&eacute; cr&eacute;&eacute;.';
$BL['be_profile_create_error']          = 'une erreur est survenue lors de la cr&eacute;ation du profil.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'mise &agrave; jour du profil effectu&eacute;e.';
$BL['be_profile_update_error']          = 'une erreur est survenue lors de la mise &agrave; jour du profil.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'nom d&#39;utilisateur {VAL} invalide';
$BL['be_profile_account_err2']          = 'mot de passe trop court (uniquement {VAL} caract&egrave;res: 5 caract&egrave;res au minimum)';
$BL['be_profile_account_err3']          = 'les mots de passe entr&eacute;s ne sont pas identiques';
$BL['be_profile_account_err4']          = 'Adresse Email {VAL} invalide';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'vos donn&eacute;es personnelles';
$BL['be_profile_data_text']             = 'l&#39;indication des donn&eacute;es personnelles n&#39;est pas obligatoire.';
$BL['be_profile_label_title']           = 'civilit&eacute;';
$BL['be_profile_label_firstname']       = 'pr&eacute;nom';
$BL['be_profile_label_name']            = 'nom';
$BL['be_profile_label_company']         = 'soci&eacute;t&eacute;';
$BL['be_profile_label_street']          = 'adresse';
$BL['be_profile_label_city']            = 'localit&eacute;';
$BL['be_profile_label_state']           = 'd&eacute;partement';
$BL['be_profile_label_zip']             = 'code postal';
$BL['be_profile_label_country']         = 'pays';
$BL['be_profile_label_phone']           = 't&eacute;l&eacute;phone';
$BL['be_profile_label_fax']             = 't&eacute;l&eacute;copie';
$BL['be_profile_label_cellphone']       = 't&eacute;l&eacute;phone mobile';
$BL['be_profile_label_signature']       = 'signature';
$BL['be_profile_label_notes']           = 'notes';
$BL['be_profile_label_profession']      = 'profession';
$BL['be_profile_label_newsletter']      = 'lettre d&#39;inf.';
$BL['be_profile_text_newsletter']       = 'je souhaite m&#39;abonner &agrave; la lettre d&#39;informations.';
$BL['be_profile_label_public']          = 'public';
$BL['be_profile_text_public']           = 'mon profil personnel est public et peut etre vu de tous.';
$BL['be_profile_label_button']          = 'valider les modifications';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'vos informations de connexion';
$BL['be_profile_account_text']          = 'par soucis de s&eacute;curit&eacute;, pensez &agrave; changer de mot de passe r&eacute;guli&egrave;rement!';
$BL['be_profile_label_err']             = 'veuillez v&eacute;rifier';
$BL['be_profile_label_username']        = 'identifiant';
$BL['be_profile_label_newpass']         = 'nouveau mot de passe';
$BL['be_profile_label_repeatpass']      = 'r&eacute;p&eacute;ter le nouveau mot de passe';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'valider';
$BL['be_profile_label_lang']            = 'langue';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'tranf&eacute;rer les fichiers charg&eacute;s par ftp';
$BL['be_ftptakeover_mark']              = 'marquer';
$BL['be_ftptakeover_available']         = 'fichiers disponibles';
$BL['be_ftptakeover_size']              = 'taille';
$BL['be_ftptakeover_nofile']            = 'aucun fichier disponible &#8211; veuillez charger vos fichiers par ftp au pr&eacute;alable';
$BL['be_ftptakeover_all']               = 'tous';
$BL['be_ftptakeover_directory']         = 'r&eacute;pertoire';
$BL['be_ftptakeover_rootdir']           = 'r&eacute;pertoire racine';
$BL['be_ftptakeover_needed']            = 'n&eacute;cessaire!(veuillez en choisir un)';
$BL['be_ftptakeover_optional']          = 'optionnel';
$BL['be_ftptakeover_keywords']          = 'mots cl&eacute;s';
$BL['be_ftptakeover_additional']        = 'additionnel';
$BL['be_ftptakeover_longinfo']          = 'descritpion longue';
$BL['be_ftptakeover_status']            = '&eacute;tat';
$BL['be_ftptakeover_active']            = 'actif';
$BL['be_ftptakeover_public']            = 'public';
$BL['be_ftptakeover_createthumb']       = 'cr&eacute;er une vignette';
$BL['be_ftptakeover_button']            = 'valider';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'centre de fichiers';
$BL['be_ftab_createnew']                = 'cr&eacute;er un nouveau r&eacute;pertoire dans la racine';
$BL['be_ftab_paste']                    = 'coller le contenu du presse papiers dans le r&eacute;pertoire racine';
$BL['be_ftab_disablethumb']             = 'd&eacute;sactiver l&#39;affichage des vignettes';
$BL['be_ftab_enablethumb']              = 'activer l&#39;affichage des vignettes';
$BL['be_ftab_private']                  = 'fichiers priv&eacute;s';
$BL['be_ftab_public']                   = 'fichiers publics';
$BL['be_ftab_search']                   = 'rechercher';
$BL['be_ftab_trash']                    = 'corbeille';
$BL['be_ftab_open']                     = 'ouvrir tous les r&eacute;pertoires';
$BL['be_ftab_close']                    = 'fermer tous les r&eacute;pertoires';
$BL['be_ftab_upload']                   = 'charger les fichiers dans le r&eacute;pertoire racine';
$BL['be_ftab_filehelp']                 = 'afficher l&#39;aide sur les fichiers';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'r&eacute;pertoire racine';
$BL['be_fpriv_title']                   = 'cr&eacute;er un nouveau r&eacute;pertoire';
$BL['be_fpriv_inside']                  = 'dans';
$BL['be_fpriv_error']                   = 'erreur: indiquez le nom du r&eacute;pertoire';
$BL['be_fpriv_name']                    = 'nom';
$BL['be_fpriv_status']                  = '&eacute;tat';
$BL['be_fpriv_button']                  = 'cr&eacute;er le r&eacute;pertoire';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = '&eacute;diter le repertoire';
$BL['be_fpriv_newname']                 = 'renommer';
$BL['be_fpriv_updatebutton']            = 'valider';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 's&eacute;lectionnez le fichier que vous souhaitez charger';
$BL['be_fprivup_err2']                  = 'taille du fichier charg&eacute; sup&eacute;rieure &agrave;';
$BL['be_fprivup_err3']                  = 'erreur durant l&#39;&eacute;criture du fichier';
$BL['be_fprivup_err4']                  = 'erreur lors de la cr&eacute;ation du r&eacute;pertoire utilisateur.';
$BL['be_fprivup_err5']                  = 'la vignette n&#39;existe pas';
$BL['be_fprivup_err6']                  = 'attention. Erreur serveur. Ne pas r&eacute;essayer. Veuillez contacter votre <a href="mailto:{VAL}">webmaster</a>!';
$BL['be_fprivup_title']                 = 'charger les fichiers';
$BL['be_fprivup_button']                = 'charger les fichiers';
$BL['be_fprivup_upload']                = 'charger';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = '&eacute;diter les informations fichier';
$BL['be_fprivedit_filename']            = 'nom du fichier';
$BL['be_fprivedit_created']             = 'cr&eacute;&eacute; le';
$BL['be_fprivedit_dateformat']          = 'd-m-Y H:i';
$BL['be_fprivedit_err1']                = 'v&eacute;rifiez le nom du fichier (renommer au nom originel)';
$BL['be_fprivedit_clockwise']           = 'tourner la vignette dans le sens horaire [+90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'tourner la vignette dans le sens horaire inverse [-90&deg;]';
$BL['be_fprivedit_button']              = 'valider les modifications';
$BL['be_fprivedit_size']                = 'taille';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'charger le fichier dans le r&eacute;pertoire';
$BL['be_fprivfunc_makenew']             = 'cr&eacute;er un nouveau r&eacute;pertoire dans';
$BL['be_fprivfunc_paste']               = 'coller le contenu du presse papiers dans le r&eacute;pertoire';
$BL['be_fprivfunc_edit']                = '&eacute;diter le r&eacute;pertoire';
$BL['be_fprivfunc_cactive']             = 'rendre actif/inactif';
$BL['be_fprivfunc_cpublic']             = 'rendre public/non public';
$BL['be_fprivfunc_deldir']              = 'supprimer le r&eacute;pertoire';
$BL['be_fprivfunc_jsdeldir']            = 'souhaitez-vous r&eacute;ellement supprimer ce r&eacute;pertoire';
$BL['be_fprivfunc_notempty']            = 'le r&eacute;pertoire {VAL} n&#39;est pas vide!';
$BL['be_fprivfunc_opendir']             = 'ouvrir le r&eacute;pertoire';
$BL['be_fprivfunc_closedir']            = 'fermer le r&eacute;pertoire';
$BL['be_fprivfunc_dlfile']              = 't&eacute;l&eacute;charger le fichier';
$BL['be_fprivfunc_clipfile']            = 'copier le fichier dans le presse papiers';
$BL['be_fprivfunc_cutfile']             = 'couper';
$BL['be_fprivfunc_editfile']            = '&eacute;diter l&#39;info fichier';
$BL['be_fprivfunc_cactivefile']         = 'rendre actif/inactif';
$BL['be_fprivfunc_cpublicfile']         = 'rendre public/non public';
$BL['be_fprivfunc_movetrash']           = 'mettre &agrave; la corbeille';
$BL['be_fprivfunc_jsmovetrash1']        = 'voulez-vous vraiment mettre';
$BL['be_fprivfunc_jsmovetrash2']        = '&agrave; la corbeille?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'aucun r&eacute;pertoire ou fichier priv&eacute;';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'utilisateur';
$BL['be_fpublic_nofiles']               = 'aucun r&eacute;pertoire ou fichier public';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'la corbeille est vide';
$BL['be_ftrash_show']                   = 'afficher les fichiers priv&eacute;s';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'souhaitez-vous restaurer {VAL} \n et le r&eacute;ins&eacute;rer dans la liste priv&eacute;e?';
$BL['be_ftrash_delete']                 = 'souhaitez-vous effacer  {VAL}?';
$BL['be_ftrash_undo']                   = 'restaurer (annuler la mise &agrave; la corbeille)';
$BL['be_ftrash_delfinal']               = 'effacer d&eacute;finitivement';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'la boite de recherche est vide.';
$BL['be_fsearch_title']                 = 'recherche de fichiers';
$BL['be_fsearch_infotext']              = 'recherche basique: La recherche est effectu&eacute;e au niveau des mots cl&eacute;s, dans les noms de fichiers et dans leur description longue. S&eacute;parez les noms multiples par des espaces.';
$BL['be_fsearch_nonfound']              = 'aucun fichier ne correspond &agrave; vos crit&egrave;res de recherche. veuillez modifier vos valeurs.!';
$BL['be_fsearch_fillin']                = 'veuillez entrer un crit&egrave;re de recherche dans le champ ci-dessus.';
$BL['be_fsearch_searchlabel']           = 'chercher';
$BL['be_fsearch_startsearch']           = 'lancer la recherche';
$BL['be_fsearch_and']                   = 'et';
$BL['be_fsearch_or']                    = 'ou';
$BL['be_fsearch_all']                   = 'tous';
$BL['be_fsearch_personal']              = 'priv&eacute;s';
$BL['be_fsearch_public']                = 'publics';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'discussion interne';
$BL['be_chat_info']                     = 'permet de communiquer avec les administrateurs et r&eacute;dacteurs connect&eacute;s. vous pouvez l&#39;utiliser pour discuter en temps r&eacute;el ou laisser un message destin&eacute; &agrave; toute personne allant se connecter ult&eacute;rieurement.';
$BL['be_chat_start']                    = 'd&eacute;marrer la discussion';
$BL['be_chat_lines']                    = 'lignes de discussin';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'centre de messages';
$BL['be_msg_new']                       = 'nouveaux';
$BL['be_msg_old']                       = 'anciens';
$BL['be_msg_senttop']                   = 'envoy&eacute;s';
$BL['be_msg_del']                       = 'effac&eacute;s';
$BL['be_msg_from']                      = 'de';
$BL['be_msg_subject']                   = 'sujet';
$BL['be_msg_date']                      = 'date/heure';
$BL['be_msg_close']                     = 'fermer le message';
$BL['be_msg_create']                    = 'cr&eacute;er un nouveau message';
$BL['be_msg_reply']                     = 'r&eacute;pondre &agrave; ce message';
$BL['be_msg_move']                      = 'mettre le message &agrave; la corbeille';
$BL['be_msg_unread']                    = 'nouveaux messages, messages non lus';
$BL['be_msg_lastread']                  = 'derniers {VAL} messages lus';
$BL['be_msg_lastsent']                  = 'derniers {VAL} messages envoy&eacute;s';
$BL['be_msg_marked']                    = 'messages marqu&eacute;s pour la suppression (corbeille)';
$BL['be_msg_nomsg']                     = 'aucun message trouv&eacute; dans ce r&eacute;pertoire';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'envoy&eacute; par';
$BL['be_msg_on']                        = 'le';
$BL['be_msg_msg']                       = 'message';
$BL['be_msg_err1']                      = 'veuillez indiquer le(s) destinataire(s)...';
$BL['be_msg_err2']                      = 'veuillez indiquer un sujet';
$BL['be_msg_err3']                      = 'l&#39;envoi d&#39;un message sans contenu n&#39;est pas conseill&eacute; ;-)';
$BL['be_msg_sent']                      = 'message envoy&eacute;!';
$BL['be_msg_fwd']                       = 'vous allez etre redirig&eacute; vers le centre de messages ou';
$BL['be_msg_newmsgtitle']               = 'r&eacute;diger un nouveau message';
$BL['be_msg_err']                       = 'erreur lors de l&#39;envoi du message';
$BL['be_msg_sendto']                    = 'envoyer le message &agrave;';
$BL['be_msg_available']                 = 'liste des destinataires';
$BL['be_msg_all']                       = 'envoyer le message &agrave; tous les destinataires s&eacute;lectionn&eacute;s';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'Cat&eacute;gorie(s) de souscription(s) de lettre(s) d&#39;infos';
$BL['be_newsletter_titleedit']          = '&eacute;diter le titre de la souscription';
$BL['be_newsletter_new']                = 'nouveau'; // bouton: cr&eacute;er et exp&eacute;dier une nouvelle lettre(s) d&#39;infos
$BL['be_newsletter_add']                = 'ajouter une souscription';
$BL['be_newsletter_name']               = 'nom';
$BL['be_newsletter_info']               = 'info';
$BL['be_newsletter_button_save']        = 'sauvegarder la souscription';
$BL['be_newsletter_button_cancel']      = 'annuler';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'identifiant non valide, veuillez un choisir un autre';
$BL['be_admin_usr_err2']                = 'champ identifiant vide (champ requis)';
$BL['be_admin_usr_err3']                = 'champ mot de passe vide (champ requis)';
$BL['be_admin_usr_err4']                = "adresse email non valide";
$BL['be_admin_usr_err']                 = 'erreur';
$BL['be_admin_usr_mailsubject']         = 'bienvenue';
$BL['be_admin_usr_mailbody']            = "bienvenue dans l&#39;interface d&#39;administation PHPWCMS\n\n    identifiant: {LOGIN}\n    mot de passe: {PASSWORD}\n\n\nVous pouvez vous connecter ici: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'ajouter un compte utilisateur';
$BL['be_admin_usr_realname']            = 'nom r&eacute;el';
$BL['be_admin_usr_setactive']           = 'activer le compte';
$BL['be_admin_usr_iflogin']             = 'si actif l&#39;utilisateur peut se connecter';
$BL['be_admin_usr_isadmin']             = 'administrateur';
$BL['be_admin_usr_ifadmin']             = 'si s&eacute;lectionn&eacute;, l&#39;utilisateur a des droits administrateur';
$BL['be_admin_usr_verify']              = 'v&eacute;rification';
$BL['be_admin_usr_sendemail']           = 'envoyer un mail contenant les informations du compte &agrave; l&#39;utilisateur';
$BL['be_admin_usr_button']              = 'valider les modifications';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = '&eacute;diter le compte utlisateur';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - donn&eacute;es compte utilisateur modifi&eacute;es';
$BL['be_admin_usr_emailbody']           = "Compte utilisateur PHPWCMS, donn&eacute;es modifi&eacute;es\n\n    identifiant: {LOGIN}\n    mot de passe: {PASSWORD}\n\n\Vous pouvez vous connecter ici: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[Aucune modification - Utilisez le mot de passe existant]';
$BL['be_admin_usr_ebutton']             = 'valider les modifications';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'liste des utilisateurs phpwcms';
$BL['be_admin_usr_ldel']                = 'ATTENTION!&#13Ceci va effacer cet utilisateur:';
$BL['be_admin_usr_create']              = 'cr&eacute;er un nouvel utilisateur';
$BL['be_admin_usr_editusr']             = '&eacute;diter l&#39;utilisateur';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'structure du site';
$BL['be_admin_struct_child']            = '(enfant de)';
$BL['be_admin_struct_index']            = 'index (racine du site)';
$BL['be_admin_struct_cat']              = 'titre de cat&eacute;gorie';
$BL['be_admin_struct_hide1']            = 'cacher';
$BL['be_admin_struct_hide2']            = 'cette cat&eacute;gorie dans le menu de navigation';
$BL['be_admin_struct_info']             = 'texte d&#39;information cat&eacute;gorie';
$BL['be_admin_struct_template']         = 'gabarit';
$BL['be_admin_struct_alias']            = 'alias de cette cat&eacute;gorie';
$BL['be_admin_struct_visible']          = 'visible';
$BL['be_admin_struct_button']           = 'valider les modifications';
$BL['be_admin_struct_close']            = 'fermer';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'cat&eacute;gorie de fichiers';
$BL['be_admin_fcat_err']                = 'nom de cat&eacute;gorie vide!';
$BL['be_admin_fcat_name']               = 'nom de cat&eacute;gorie';
$BL['be_admin_fcat_needed']             = 'requis';
$BL['be_admin_fcat_button1']            = 'actualiser';
$BL['be_admin_fcat_button2']            = 'cr&eacute;er';
$BL['be_admin_fcat_delmsg']             = 'Souhaitez-vous vraiment effacer la cl&eacute; fichier?';
$BL['be_admin_fcat_fcat']               = 'cat&eacute;gorie de fichier';
$BL['be_admin_fcat_err1']               = 'nom de cl&eacute; fichier vide!';
$BL['be_admin_fcat_fkeyname']           = 'nom de cl&eacute; fichier';
$BL['be_admin_fcat_exit']               = 'quitter l&#39;&eacute;dition';
$BL['be_admin_fcat_addkey']             = 'ajouter une cl&eacute;';
$BL['be_admin_fcat_editcat']            = '&eacute;diter le nom de la cat&eacute;gorie';
$BL['be_admin_fcat_delcatmsg']          = 'Souhaitez-vous vraiment effacer la cat&eacute;gorie de fichier?';
$BL['be_admin_fcat_delcat']             = 'effacer la cat&eacute;gorie de fichier';
$BL['be_admin_fcat_delkey']             = 'effacer le nom cl&eacute; de la cat&eacute;gorie';
$BL['be_admin_fcat_editkey']            = '&eacute;diter la cl&eacute;';
$BL['be_admin_fcat_addcat']             = 'cr&eacute;er une nouvelle cat&eacute;gorie de fichier';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'configuration du site: mise en page';
$BL['be_admin_page_align']              = 'alignement de la page';
$BL['be_admin_page_align_left']         = 'align&eacute;e &agrave; gauche';
$BL['be_admin_page_align_center']       = 'centr&eacute;e';
$BL['be_admin_page_align_right']        = 'align&eacute;e &agrave; droite';
$BL['be_admin_page_margin']             = 'marge';
$BL['be_admin_page_top']                = 'haut';
$BL['be_admin_page_bottom']             = 'bas';
$BL['be_admin_page_left']               = 'gauche';
$BL['be_admin_page_right']              = 'droit';
$BL['be_admin_page_bg']                 = 'arri&egrave;re plan';
$BL['be_admin_page_color']              = 'couleur';
$BL['be_admin_page_height']             = 'hauteur';
$BL['be_admin_page_width']              = 'largeur';
$BL['be_admin_page_main']               = 'principal';
$BL['be_admin_page_leftspace']          = 'esp. gauche';
$BL['be_admin_page_rightspace']         = 'esp. droit';
$BL['be_admin_page_class']              = 'classe';
$BL['be_admin_page_image']              = 'image';
$BL['be_admin_page_text']               = 'texte';
$BL['be_admin_page_link']               = 'liens';
$BL['be_admin_page_js']                 = 'javascript';
$BL['be_admin_page_visited']            = 'visit&eacute;s';
$BL['be_admin_page_pagetitle']          = 'titre du site';
$BL['be_admin_page_addtotitle']         = 'ajouter au titre';
$BL['be_admin_page_category']           = 'cat&eacute;gorie';
$BL['be_admin_page_articlename']        = 'nom de l&#39;article';
$BL['be_admin_page_blocks']             = 'blocs';
$BL['be_admin_page_allblocks']          = 'tous les blocs';
$BL['be_admin_page_col1']               = 'mise en page 3 colonnes';
$BL['be_admin_page_col2']               = 'mise en page 2 colonnes (colonne principale &agrave; droite, navigation &agrave; gauche)';
$BL['be_admin_page_col3']               = 'mise en page 2 colonnes (colonne principale &agrave; gauche, navigation &agrave; droite)';
$BL['be_admin_page_col4']               = 'mise en page 1 colonne';
$BL['be_admin_page_header']             = 'ent&ecirc;te';
$BL['be_admin_page_footer']             = 'pied';
$BL['be_admin_page_topspace']           = 'esp. haut';
$BL['be_admin_page_bottomspace']        = 'esp. bas';
$BL['be_admin_page_button']             = 'sauvegarder la mise en page';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'configuration site: donn&eacute;es css';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'valider les modifications';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'configuration site: gabarits';
$BL['be_admin_tmpl_default']            = 'd&eacute;faut';
$BL['be_admin_tmpl_add']                = 'ajouter un gabarit';
$BL['be_admin_tmpl_edit']               = '&eacute;diter le gabarit';
$BL['be_admin_tmpl_new']                = 'nouveau garbarit';
$BL['be_admin_tmpl_css']                = 'fichier css';
$BL['be_admin_tmpl_head']               = 'ent&ecirc;te html';
$BL['be_admin_tmpl_js']                 = 'js on load';
$BL['be_admin_tmpl_error']              = 'erreur';
$BL['be_admin_tmpl_button']             = 'valider les modifications';
$BL['be_admin_tmpl_name']               = 'nom';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'structure du site et liste d&#39;articles';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'le champ titre pour cet article est vide';
$BL['be_article_err2']                  = 'date de d&eacute;but erronn&eacute;e';
$BL['be_article_err3']                  = 'date de fin est erronn&eacute;e';
$BL['be_article_title1']                = 'information de base de l&#39;article';
$BL['be_article_cat']                   = 'cat&eacute;gorie';
$BL['be_article_atitle']                = 'titre de l&#39;article';
$BL['be_article_asubtitle']             = 'sous-titre';
$BL['be_article_abegin']                = 'd&eacute;but';
$BL['be_article_aend']                  = 'fin';
$BL['be_article_aredirect']             = 'renvoyer vers';
$BL['be_article_akeywords']             = 'mots cl&eacute;s';
$BL['be_article_asummary']              = 'r&eacute;sum&eacute;';
$BL['be_article_abutton']               = 'cr&eacute;er l&#39;article';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'date de fin erronn&eacute;e - entrez la date du jour + 1 semaine';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = '&eacute;diter les informations de base de l&#39;article';
$BL['be_article_eslastedit']            = 'derni&egrave;re &eacute;dition';
$BL['be_article_esnoupdate']            = 'la forme n&#39;a pas &eacute;t&eacute; mise &agrave; jour';
$BL['be_article_esbutton']              = 'valider les modifications';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'bloc de contenu';
$BL['be_article_cnt_type']              = 'type de contenu';
$BL['be_article_cnt_space']             = 'espacement';
$BL['be_article_cnt_before']            = 'avant';
$BL['be_article_cnt_after']             = 'apr&egrave;s';
$BL['be_article_cnt_top']               = 'haut';
$BL['be_article_cnt_ctitle']            = 'titre du bloc de contenu';
$BL['be_article_cnt_back']              = 'informations articles compl&egrave;tes';
$BL['be_article_cnt_button1']           = 'valider les modifications';
$BL['be_article_cnt_button2']           = 'cr&eacute;er le bloc de contenu';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'information article';
$BL['be_article_cnt_ledit']             = '&eacute;diter l&#39;article';
$BL['be_article_cnt_lvisible']          = 'rendre visible/invisible';
$BL['be_article_cnt_ldel']              = 'supprimer l&#39;article';
$BL['be_article_cnt_ldeljs']            = 'souhaitez-vous vraiment supprimer cet article?';
$BL['be_article_cnt_redirect']          = 'renvoi';
$BL['be_article_cnt_edited']            = '&eacute;dit&eacute; par';
$BL['be_article_cnt_start']             = 'date de d&eacute;but';
$BL['be_article_cnt_end']               = 'date de fin';
$BL['be_article_cnt_add']               = 'ajouter un bloc de contenu';
$BL['be_article_cnt_up']                = 'd&eacute;placer le bloc de contenu vers le haut';
$BL['be_article_cnt_down']              = 'd&eacute;placer le bloc de contenu vers le bas';
$BL['be_article_cnt_edit']              = '&eacute;diter le bloc de contenu';
$BL['be_article_cnt_delpart']           = 'effacer ce bloc de contenu';
$BL['be_article_cnt_delpartjs']         = 'souhaitez-vous vraiment effacer ce bloc de contenu?';
$BL['be_article_cnt_center']            = 'centre articles';

// content forms
$BL['be_cnt_plaintext']                 = 'texte brut';
$BL['be_cnt_htmltext']                  = 'texte html';
$BL['be_cnt_image']                     = 'image';
$BL['be_cnt_position']                  = 'position';
$BL['be_cnt_pos0']                      = 'au dessus, &agrave; gauche';
$BL['be_cnt_pos1']                      = 'au dessus, au centre';
$BL['be_cnt_pos2']                      = 'au dessus, &agrave; droite';
$BL['be_cnt_pos3']                      = 'en dessous, &agrave; gauche';
$BL['be_cnt_pos4']                      = 'en dessous, au centre';
$BL['be_cnt_pos5']                      = 'en dessous, &agrave; droite';
$BL['be_cnt_pos6']                      = 'dans le texte, &agrave; gauche';
$BL['be_cnt_pos7']                      = 'dans le texte, &agrave; droite';
$BL['be_cnt_pos0i']                     = 'aligner l&#39;image au dessus, &agrave; gauche du bloc de texte';
$BL['be_cnt_pos1i']                     = 'aligner l&#39;image au dessus, au centre du bloc de texte';
$BL['be_cnt_pos2i']                     = 'aligner l&#39;image au dessus, &agrave; droite du bloc de texte';
$BL['be_cnt_pos3i']                     = 'aligner l&#39;image en dessous, &agrave; gauche du bloc de texte';
$BL['be_cnt_pos4i']                     = 'aligner l&#39;image en dessous, au centre du bloc de texte';
$BL['be_cnt_pos5i']                     = 'aligner l&#39;image en dessous, &agrave; droite du bloc de texte';
$BL['be_cnt_pos6i']                     = 'aligner l&#39;image dans le bloc de texte, cot&eacute; gauche';
$BL['be_cnt_pos7i']                     = 'aligner l&#39;image dans le bloc de texte, cot&eacute; droit';
$BL['be_cnt_maxw']                      = 'largeur max.';
$BL['be_cnt_maxh']                      = 'hauteur max.';
$BL['be_cnt_enlarge']                   = 'clicker pour agrandir';
$BL['be_cnt_caption']                   = 'texte de remlacement';
$BL['be_cnt_subject']                   = 'sujet';
$BL['be_cnt_recipient']                 = 'destinataire';
$BL['be_cnt_buttontext']                = 'texte du bouton';
$BL['be_cnt_sendas']                    = 'envoyer comme';
$BL['be_cnt_text']                      = 'texte brut';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'champs';
$BL['be_cnt_code']                      = 'code';
$BL['be_cnt_infotext']                  = 'texte';
$BL['be_cnt_subscription']              = 'souscription';
$BL['be_cnt_labelemail']                = '&eacute;tiquette email';
$BL['be_cnt_tablealign']                = 'alignement de la table';
$BL['be_cnt_labelname']                 = '&eacute;tiquette nom';
$BL['be_cnt_labelsubsc']                = '&eacute;tiquette souscription';
$BL['be_cnt_allsubsc']                  = 'toutes les souscritions';
$BL['be_cnt_default']                   = 'par d&eacute;faut';
$BL['be_cnt_left']                      = '&agrave; gauche';
$BL['be_cnt_center']                    = 'au centre';
$BL['be_cnt_right']                     = '&agrave; droite';
$BL['be_cnt_buttontext']                = 'texte du bouton';
$BL['be_cnt_successtext']               = 'texte succ&egrave;s';
$BL['be_cnt_regmail']                   = 'adresse email enregistr&eacute;e';
$BL['be_cnt_logoffmail']                = 'adresse email de d&eacute;connection';
$BL['be_cnt_changemail']                = 'modifier l&#39;adresse email';
$BL['be_cnt_openimagebrowser']          = 'ouvrir le navigateur d&#39;images';
$BL['be_cnt_openfilebrowser']           = 'ouvrir le navigateur de fichiers';
$BL['be_cnt_sortup']                    = 'd&eacute;placer vers le haut';
$BL['be_cnt_sortdown']                  = 'd&eacute;placer vers le bas';
$BL['be_cnt_delimage']                  = 'supprimer l&#39;image s&eacute;lectionn&eacute;e';
$BL['be_cnt_delfile']                   = 'supprimer le fichier s&eacute;lectionn&eacute;';
$BL['be_cnt_delmedia']                  = 'supprimer le m&eacute;dia s&eacute;lectionn&eacute;';
$BL['be_cnt_column']                    = 'colonnes';
$BL['be_cnt_imagespace']                = 'espacement des images';
$BL['be_cnt_directlink']                = 'lien direct';
$BL['be_cnt_target']                    = 'cible';
$BL['be_cnt_target1']                   = 'dans une nouvelle fen&egrave;tre';
$BL['be_cnt_target2']                   = 'dans un cadre ou fen&egrave;tre parent';
$BL['be_cnt_target3']                   = 'dans la m&ecirc;me fen&egrave;tre sans cadres';
$BL['be_cnt_target4']                   = 'dans le m&ecirc;me cadre ou fen&egrave;tre';
$BL['be_cnt_bullet']                    = 'liste &agrave; bulles';
$BL['be_cnt_linklist']                  = 'liste de liens';
$BL['be_cnt_plainhtml']                 = 'html brut';
$BL['be_cnt_files']                     = 'fichiers';
$BL['be_cnt_description']               = 'description';
$BL['be_cnt_linkarticle']               = 'liens articles';
$BL['be_cnt_articles']                  = 'articles';
$BL['be_cnt_movearticleto']             = 'd&eacute;placer l&#39;article s&eacute;lectionn&eacute; vers la liste de liens articles';
$BL['be_cnt_removearticleto']           = 'supprimer l&#39;article s&eacute;lectionn&eacute; de la liste de liens articles';
$BL['be_cnt_mediatype']                 = 'type de m&eacute;dia';
$BL['be_cnt_control']                   = 'contrôle';
$BL['be_cnt_showcontrol']               = 'afficher la barre de contrôle';
$BL['be_cnt_autoplay']                  = 'lecture automatique';
$BL['be_cnt_source']                    = 'source';
$BL['be_cnt_internal']                  = 'interne';
$BL['be_cnt_openmediabrowser']          = 'ouvrir le navigateur de m&eacute;dias';
$BL['be_cnt_external']                  = 'externe';
$BL['be_cnt_mediapos0']                 = '&agrave; gauche (par d&eacute;faut)';
$BL['be_cnt_mediapos1']                 = 'au centre';
$BL['be_cnt_mediapos2']                 = '&agrave; droite';
$BL['be_cnt_mediapos3']                 = 'bloc, &agrave; gauche';
$BL['be_cnt_mediapos4']                 = 'bloc, &agrave; droite';
$BL['be_cnt_mediapos0i']                = 'aligner le m&eacute;dia au dessus, &agrave; gauche du bloc de texte';
$BL['be_cnt_mediapos1i']                = 'aligner le m&eacute;dia au dessus, au centre du bloc de texte';
$BL['be_cnt_mediapos2i']                = 'aligner le m&eacute;dia au dessus, &agrave; droite du bloc de texte';
$BL['be_cnt_mediapos3i']                = 'aligner le m&eacute;dia dans le bloc de texte, cot&eacute; gauche';
$BL['be_cnt_mediapos4i']                = 'aligner le m&eacute;dia dans le bloc de texte, cot&eacute; droit';
$BL['be_cnt_setsize']                   = 'd&eacute;finir la taille d&#39;affichage';
$BL['be_cnt_set1']                      = 'd&eacute;finir la taille d&#39;affichage &agrave; 160x120px';
$BL['be_cnt_set2']                      = 'd&eacute;finir la taille d&#39;affichage &agrave; 240x180px';
$BL['be_cnt_set3']                      = 'd&eacute;finir la taille d&#39;affichage &agrave; 320x240px';
$BL['be_cnt_set4']                      = 'd&eacute;finir la taille d&#39;affichage &agrave; 480x360px';
$BL['be_cnt_set5']                      = 'annuler la taille d&#39;affichage';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'cr&eacute;er une nouvelle mise en page';
$BL['be_admin_page_name']               = 'nom de la mise en page';
$BL['be_admin_page_edit']               = '&eacute;diter la mise en page';
$BL['be_admin_page_render']             = 'rendu';
$BL['be_admin_page_table']              = 'table';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'personnalis&eacute;';
$BL['be_admin_page_custominfo']         = 'du bloc principal de gabarit';
$BL['be_admin_tmpl_layout']             = 'mise en page';
$BL['be_admin_tmpl_nolayout']           = 'aucune mise en page disponible!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'rechercher';
$BL['be_cnt_results']                   = 'r&eacute;sultats';
$BL['be_cnt_results_per_page']          = 'par page (vide = 25)';
$BL['be_cnt_opennewwin']                = 'ouvrir une nouvele fen&egrave;tre';
$BL['be_cnt_searchlabeltext']           = 'valeurs de texte pr&eacute;d&eacute;finies du formulaire de recherche et de la page de r&eacute;sultats.';
$BL['be_cnt_input']                     = 'entr&eacute;e';
$BL['be_cnt_style']                     = 'style';
$BL['be_cnt_result']                    = 'r&eacute;sultat';
$BL['be_cnt_next']                      = 'suivant';
$BL['be_cnt_previous']                  = 'pr&eacute;c&eacute;dent';
$BL['be_cnt_align']                     = 'alignement';
$BL['be_cnt_searchformtext']            = 'textes d&#39;introduction. D&eacute;finissez ici le texte d&#39;introduction &agrave; afficher dans les cas de figure list&eacute;s.';
$BL['be_cnt_intro']                     = 'intro';
$BL['be_cnt_noresult']                  = 'pas de r&eacute;sultat';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'd&eacute;sactiver';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'propri&eacute;taire de l&#39;article';
$BL['be_article_adminuser']             = 'administrateur';
$BL['be_article_username']              = 'auteur';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'wysiwyg html';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'visible uniquement pour les utilisateurs enregistr&eacute;s';
$BL['be_admin_struct_status']           = '&eacute;tat du menu &#39;frontend&#39;';

// new translation yet to be reviewed
// translated: 25-06-2005

// added: 15-02-2004

$BL['be_ctype_articlemenu']             = 'menu d&#39;articles';
$BL['be_cnt_sitelevel']                 = 'niveau du site';
$BL['be_cnt_sitecurrent']               = 'niveau du site actuel';

// added: 24-03-2004
$BL['be_subnav_admin_starttext']        = 'texte par d&eacute;faut de la zone d&#39;administration';
$BL['be_ctype_ecard']                   = 'e-card';
$BL['be_ctype_blog']                    = 'blog';
$BL['be_cnt_ecardtext']                 = 'titre/e-card';
$BL['be_cnt_ecardtmpl']                 = 'mail tmpl';
$BL['be_cnt_ecard_image']               = 'image de l&#39;e-card';
$BL['be_cnt_ecard_title']               = 'titre de l&#39;e-card';
$BL['be_cnt_alignment']                 = 'alignement';
$BL['be_cnt_ecardform']                 = 'formulaire tmpl';
$BL['be_cnt_ecardform_err']             = 'Tous les champs marqu&eacute;s * sont obligatoires';
$BL['be_cnt_ecardform_sender']          = 'Exp&eacute;diteur';
$BL['be_cnt_ecardform_recipient']       = 'Destinataire';
$BL['be_cnt_ecardform_name']            = 'Nom';
$BL['be_cnt_ecardform_msgtext']         = 'Votre message pour le destinataire';
$BL['be_cnt_ecardform_button']          = 'envoyer l&#39;e-card';
$BL['be_cnt_ecardsend']                 = 'envoyer tmpl';

// added: 28-03-2004
$BL['be_admin_startup_title']           = 'Texte par d&eacute;faut de la zone d&#39;administration';
$BL['be_admin_startup_text']            = 'Texte par d&eacute;faut';
$BL['be_admin_startup_button']          = 'Sauver le texte par d&eacute;faut';

// added: 17-04-2004
$BL['be_ctype_guestbook']               = 'livre de visites/commentaires';
$BL['be_cnt_guestbook_listing']         = 'lister';
$BL['be_cnt_guestbook_listing_all']     = 'lister&nbsp;toutes&nbsp;les&nbsp;entr&eacute;es';
$BL['be_cnt_guestbook_list']            = 'liste';
$BL['be_cnt_guestbook_perpage']         = 'par&nbsp;page';
$BL['be_cnt_guestbook_form']            = 'formulaire';
$BL['be_cnt_guestbook_signed']          = 'sign&eacute;';
$BL['be_cnt_guestbook_nav']             = 'navigation';
$BL['be_cnt_guestbook_before']          = 'avant';
$BL['be_cnt_guestbook_after']           = 'apr&egrave;s';
$BL['be_cnt_guestbook_entry']           = 'entr&eacute;e';
$BL['be_cnt_guestbook_edit']            = '&eacute;diter';
$BL['be_cnt_ecardform_selector']        = 's&eacute;lecteur';
$BL['be_cnt_ecardform_radiobutton']     = 'bouton radio';
$BL['be_cnt_ecardform_javascript']      = 'fonctionnalit&eacute; JavaScript';
$BL['be_cnt_ecardform_over']            = 'onMouseOver';
$BL['be_cnt_ecardform_click']           = 'onClick';
$BL['be_cnt_ecardform_out']             = 'onMouseOut';
$BL['be_admin_struct_topcount']         = 'Nombre d&#39;articles &agrave; afficher au d&eacute;but'; //

// added: 19-04-2004
$BL['be_subnav_msg_newslettersend']     = 'lettres d&#39;infos > exp&eacute;di&eacute;es'; // titre
$BL['be_newsletter_addnl']              = 'ajouter lettre d&#39;infos';
$BL['be_newsletter_titleeditnl']        = '&eacute;diter lettre d&#39;infos';
$BL['be_newsletter_newnl']              = 'cr&eacute;er nouvelle';
$BL['be_newsletter_button_savenl']      = 'sauvegarder la lettre d&#39;infos';
$BL['be_newsletter_fromname']           = 'Depuis nom';
$BL['be_newsletter_fromemail']          = 'Depuis l&#39;email';
$BL['be_newsletter_replyto']            = 'r&eacute;pondre &agrave; l&#39;email';
$BL['be_newsletter_changed']            = 'dernier changement';
$BL['be_newsletter_placeholder']        = 'propir&eacute;taire de la lettre d&#39;infos';
$BL['be_newsletter_htmlpart']           = 'contenu HTML de la lettre d&#39;infos';
$BL['be_newsletter_textpart']           = 'contenu TEXTE de la lettre d&#39;infos';
$BL['be_newsletter_allsubscriptions']   = 'tous les abonnements';
$BL['be_newsletter_verifypage']         = 'verifiez le lien';
$BL['be_newsletter_open']               = 'donn&eacute;es HTML et TEXTE';
$BL['be_newsletter_open1']              = '(cliquez sur l&#39;image pour l&#39;ouvrir)';
$BL['be_newsletter_sendnow']            = 'Envoyer la lettre d&#39;infos';
$BL['be_newsletter_attention']          = '<strong style="color:#CC3300;">Attention!</strong> Envoyer une lettre d&#39;infos &agrave; plusiers destinataires peut s&#39;av&eacute;rer risqu&eacute;. Les adresses des destinataires doivent avoir &eacute;t&eacute; v&eacute;rifi&eacute;es sinon vous pourriez envoyer du spam potentiel. Pensez-y &agrave; deux fois avant d&#39;envoyer des lettre d&#39;infos. Faites un test avant de tout envoyer.';
$BL['be_newsletter_attention1']         = 'Si vous avez modifi&eacute; des donn&eacute;es dans votre lettre d&#39;infos sauvez-le, sinon vous perdrez les changements.';
$BL['be_newsletter_testemail']          = 'Email de test';
$BL['be_newsletter_sendnlbutton']       = 'envoyez la lettre d&#39;infos';
$BL['be_newsletter_sendprocess']        = 'processus d&#39;envoi';
$BL['be_newsletter_attention2']         = '<strong style="color:#CC3300;">Attention!</strong> Svp n&#39;arr&ecirc;tez pas le processus d&#39;envoi. Vous risquez d&#39;envoyer le lettre d&#39;infos au m&ecirc;me destinataire plusieurs fois. Quand l&#39;envoi pr&eacute;sente des probl&egrave;mes les destinataires non atteints sont stock&eacute;s dans une table, juqu&#39;au prochain envoi, et sont r&eacute;envoy&eacute;s de façon automatique.';
$BL['be_newsletter_testerror']          = '<span style="color:#CC3300;font-size:11px;">l&#39;adresse de test <strong>###TEST###</strong> n&#39;est PAS valide!<br />&nbsp;<br />Veuillez r&eacute;essayer svp!';
$BL['be_newsletter_to']                 = 'Destinataires';
$BL['be_newsletter_ready']              = 'envoi de lettre d&#39;infos: FAIT';
$BL['be_newsletter_readyfailed']        = 'Erreur d&#39;envoi de la lettre d&#39;infos &agrave;';
$BL['be_subnav_msg_subscribers']        = 'lettres d&#39;infos > abonn&eacute;s'; // titre

// added: 20-04-2004
$BL['be_ctype_sitemap']                 = 'plan du site';
$BL['be_cnt_sitemap_catimage']          = 'icône du niveau';
$BL['be_cnt_sitemap_articleimage']      = 'icône de l&#39;article';
$BL['be_cnt_sitemap_display']           = 'montrer';
$BL['be_cnt_sitemap_structuronly']      = 'seulement les structures des niveaux';
$BL['be_cnt_sitemap_structurarticle']   = 'structure des niveaux + articles';
$BL['be_cnt_sitemap_catclass']          = 'classe du niveau';
$BL['be_cnt_sitemap_articleclass']      = 'classe de l&#39;article';
$BL['be_cnt_sitemap_count']             = 'compteur';
$BL['be_cnt_sitemap_classcount']        = 'ajouter &agrave; la classe nom';
$BL['be_cnt_sitemap_noclasscount']      = 'n&#39;ajouter pas &agrave; la classe nom';

// added: 23-04-2004
$BL['be_ctype_bid']                     = 'faire offre';
$BL['be_cnt_bid_bidtext']               = 'texte de l&#39;offre';
$BL['be_cnt_bid_sendtext']              = 'envoyer le texte';
$BL['be_cnt_bid_verifiedtext']          = 'texte v&eacute;rifi&eacute;';
$BL['be_cnt_bid_errortext']             = 'offre annul&eacute;e';
$BL['be_cnt_bid_verifyemail']           = 'v&eacute;rifiez l&#39;email';
$BL['be_cnt_bid_startbid']              = 'commencer l&#39;offre';

// added: 29-04-2004
$BL['be_cnt_bid_nextbidadd']            = 'augmenter&nbsp;de';

// added: 10-05-2004
$BL['be_ctype_pages']                   = 'contenu ext.';
$BL['be_cnt_pages_select']              = 's&eacute;lectionner le fichier';
$BL['be_cnt_pages_fromfile']            = 'fichier de la structure';
$BL['be_cnt_pages_manually']            = 'chemin/fichier ou URL personnalis&eacute;e';
$BL['be_cnt_pages_cust']                = 'fichier/URL';
$BL['be_cnt_pages_from']                = 'source';

// added: 24-05-2004
$BL['be_ctype_reference']               = 'images rollover';
$BL['be_cnt_reference_basis']           = 'alignement';
$BL['be_cnt_reference_horizontal']      = 'horizontal';
$BL['be_cnt_reference_vertical']        = 'vertical';
$BL['be_cnt_reference_aligntext']       = 'images de r&eacute;f&eacute;rence petites';
$BL['be_cnt_reference_largetext']       = 'images de r&eacute;f&eacute;rence grandes';
$BL['be_cnt_reference_zoom']            = 'zoom';
$BL['be_cnt_reference_middle']          = 'milieu';
$BL['be_cnt_reference_border']          = 'bordure';
$BL['be_cnt_reference_block']           = 'block h x l';

// added: 31-05-2004
$BL['be_article_rendering']             = 'affichage';
$BL['be_article_nosummary']             = 'ne pas afficher le sommaire dans l&#39;article complet';
$BL['be_article_forlist']               = 'liste d&#39;articles';
$BL['be_article_forfull']               = 'afficher tout l&#39;article';

// added: 08-07-2004
$BL["setup_dir_exists"]                 = '<strong>ATTENTION!</strong> Ce r&eacute;pertoire &quot;SETUP&quot; existe encore! Effacer ce r&eacute;pertoire - il pr&eacute;sente des risques potentiel au niveau de la s&eacute;curit&eacute;.';

// added: 12-08-2004
$BL['be_cnt_guestbook_banned']          = 'mots interdits';
$BL['be_cnt_guestbook_flooding']        = 'flooding';
$BL['be_cnt_guestbook_setcookie']       = 'placer un cookie';
$BL['be_cnt_guestbook_allowed']         = 'permis apr&egrave;s';
$BL['be_cnt_guestbook_seconds']         = 'secondes';
$BL['be_alias_ID']                      = 'ID alias';
$BL['be_ftrash_delall']                 = "Voulez-vous vraiment &eacute;ffacer \nTOUS LES FICHIERS dans la corbeille?";
$BL['be_ftrash_delallfiles']            = '&eacute;ffacer tous les fichiers dans la corbeille';

// added: 16-08-2004
$BL['be_subnav_msg_importsubscribers']  = 'lettres d&#39;infos > import @'; // titre
$BL['be_newsletter_importtitle']        = 'Importer les abonn&eacute;s &agrave; la lettre d&#39;infos';
$BL['be_newsletter_entriesfound']       = 'entr&eacute;es&nbsp;trouv&eacute;es';
$BL['be_newsletter_foundinfile']        = 'dans le fichier';
$BL['be_newsletter_addresses']          = 'adresses';
$BL['be_newsletter_csverror']           = 'Le fichier CSV import&eacute;s pr&eacute;sente des probl&egrave;mes! V&eacute;rifier le d&eacute;limiteur!';
$BL['be_newsletter_importall']          = 'importer toutes les entr&eacute;es';
$BL['be_newsletter_addressesadded']     = 'adresses ajout&eacute;es.';
$BL['be_newsletter_newimport']          = 'nouvelle importation';
$BL['be_newsletter_importerror']        = 'Svp v&eacute;rifiez votre fichier CSV - aucune adresse ne peut &ecirc;tre ajout&eacute;e!';
$BL['be_newsletter_shouldbe1']          = 'Votre fichier CSV fdevrait &ecirc;tre format&eacute; comme ceci';
$BL['be_newsletter_shouldbe2']          = 'mais vous pouvez choisir un d&eacute;limiteur personnalis&eacute;';
$BL['be_newsletter_sample']             = '&eacute;chantillon';
$BL['be_newsletter_selectCSV']          = 's&eacute;lectionner le fichier CSV';
$BL['be_newsletter_delimeter']          = 'd&eacute;limeteur';
$BL['be_newsletter_importCSV']          = 'importer le fichier CSV';

// added: 24-08-2004
$BL['be_admin_struct_orderarticle']     = 'mettre de l&#39;ordre dans les articles assign&eacute;s';
$BL['be_admin_struct_orderdate']        = 'date de cr&eacute;ation';
$BL['be_admin_struct_orderchangedate']  = 'date de modification';
$BL['be_admin_struct_orderstartdate']   = 'date de d&eacute;but';
$BL['be_admin_struct_orderdesc']        = 'descendant';
$BL['be_admin_struct_orderasc']         = 'ascendant';
$BL['be_admin_struct_ordermanual']      = 'manuel (fl&egrave;che haut/bas)';
$BL['be_cnt_sitemap_startid']           = 'd&eacute;buter &agrave;';

// added: 20-10-2004
$BL['be_ctype_map']                     = 'plan';
$BL['be_save_btn']                      = 'Sauvegarder';
$BL['be_cmap_location_error_notitle']   = 'choisissez un titre pour cet endroit.';
$BL['be_cnt_map_add']                   = 'ajouter endroit';
$BL['be_cnt_map_edit']                  = '&eacute;diter endroit';
$BL['be_cnt_map_title']                 = 'titre de l&#39;endroit';
$BL['be_cnt_map_info']                  = 'entr&eacute;e/info';
$BL['be_cnt_map_list']                  = 'liste d&#39;endroits';
$BL['be_btn_delete']                    = 'Voulez-vous vraiment &eacute;ffacer cet endroit?';

// added: 05-11-2004
$BL['be_ctype_phpvar']                  = 'variables PHP';
$BL['be_cnt_vars']                      = 'variables';

// added: 19-11-2004 -- copy - Fernando Batista http://fernandobatista.net
$BL['be_func_struct_copy']              = 'copier l&#39;article';
$BL['be_func_struct_nocopy']            = 'd&eacute;sactiver la copie de l&#39;article';
$BL['be_func_struct_copy_level']        = 'copie le niveau de la structure';
$BL['be_func_struct_no_copy']           = "Il est impossible de copier la niveau de la racine!";

// added: 27-11-2004
$BL['be_date_minute']                   = 'minute';
$BL['be_date_minutes']                  = 'minutes';
$BL['be_date_hour']                     = 'heure';
$BL['be_date_hours']                    = 'heures';
$BL['be_date_day']                      = 'jour';
$BL['be_date_days']                     = 'jours';
$BL['be_date_week']                     = 'semaine';
$BL['be_date_weeks']                    = 'semaines';
$BL['be_date_month']                    = 'mois';
$BL['be_date_months']                   = 'mois';
$BL['be_off']                           = 'Eteint';
$BL['be_on']                            = 'Allum&eacute;';
$BL['be_cache']                         = 'cache';
$BL['be_cache_timeout']                 = 'arr&ecirc;t';

// added: 13-12-2004
$BL['be_subnav_admin_groups']           = 'groupe &amp; d&#39;utlisateurs';

// added: 20-12-2004
$BL['be_ctype_forum']                   = 'forum';
$BL['be_subnav_msg_forum']              = 'liste des forums';
$BL['be_forum_title']                   = 'titre du forum';
$BL['be_forum_permission']              = 'permissions';
$BL['be_forum_add']                     = 'ajouter forum';
$BL['be_forum_titleedit']               = '&eacute;diter forum';

// added: 15-01-2005
$BL['be_admin_page_customblocks']       = 'personnalis&eacute;';
$BL['be_show_content']                  = 'afficher';
$BL['be_main_content']                  = 'colonne principale';
$BL['be_admin_template_jswarning']      = 'Avertissement!!! \nLes blocks personnalis&eacute;s peuvent changer! \n\nSi vous annuler ou \nmodifier les donn&eacute;es de votre mise en page! \n\nChanger le mod&egrave;le?\n\n';

$BL['be_ctype_rssfeed']                 = 'fil RSS';
$BL['be_cnt_rssfeed_url']               = 'url du RSS';
$BL['be_cnt_rssfeed_item']              = '&eacute;l&eacute;ment';
$BL['be_cnt_rssfeed_max']               = 'max.';
$BL['be_cnt_rssfeed_cut']               = 'cacher le 1er &eacute;l&eacute;ment';

$BL['be_ctype_simpleform']              = 'formulaire de contact';

$BL['be_cnt_onsuccess']                 = 'en cas de r&eacute;ussite';
$BL['be_cnt_onerror']                   = 'en cas d&#39;erreur';
$BL['be_cnt_onsuccess_redirect']        = 'redirection en cas de r&eacute;ussite';
$BL['be_cnt_onerror_redirect']          = 'redirection en cas d&#39;erreur';

$BL['be_cnt_form_class']                = 'classe du formlaire';
$BL['be_cnt_label_wrap']                = '&eacute;tiquette de l&#39;enveloppe';
$BL['be_cnt_error_class']               = 'classe d&#39;erreur';
$BL['be_cnt_req_mark']                  = 'marque requise';
$BL['be_cnt_mark_as_req']               = 'marquer comme requise';
$BL['be_cnt_mark_as_del']               = 'marquer l&#39;&eacute;l&eacute;ment pour l&#39;effacer';


$BL['be_cnt_type']                      = 'type';
$BL['be_cnt_label']                     = '&eacute;tiquette';
$BL['be_cnt_needed']                    = 'requis';
$BL['be_cnt_delete']                    = 'effacer';
$BL['be_cnt_value']                     = 'valeur';
$BL['be_cnt_error_text']                = 'texte d&#39;erreur';
$BL['be_cnt_css_style']                 = 'style CSS';

$BL['be_cnt_field']                     = array("text"=>'texte', "email"=>'email', "textarea"=>'texte (multi-lignes)',
"hidden"=>'cach&eacute;', "password"=>'mot de passe', "select"=>'s&eacute;lectionner le menu',
"list"=>'menu de la liste', "checkbox"=>'checkbox', "radio"=>'bouton radio',
"upload"=>'upload fichier', "submit"=>'bouton d&#39;envoi', "reset"=>'bouton reset',
"break"=>'s&eacute;parateur', "breaktext"=>'s&eacute;parateur (texte)', "special"=>'texte (sp&eacute;cial)');

$BL['be_cnt_access']                    = 'acc&egrave;s';
$BL['be_cnt_activated']                 = 'activ&eacute;';
$BL['be_cnt_available']                 = 'disponible';
$BL['be_cnt_guests']                    = 'invit&eacute;s';
$BL['be_cnt_admin']                     = 'admin';
$BL['be_cnt_write']                     = '&eacute;crire';
$BL['be_cnt_read']                      = 'lire';

$BL['be_cnt_no_wysiwyg_editor']         = 'd&eacute;sactiver l&#39;&eacute;diteur WYSIWYG';
$BL['be_cnt_cache_update']              = 'remise &agrave; 0 du cache';
$BL['be_cnt_cache_delete']              = 'effacer le cache';
$BL['be_cnt_cache_delete_msg']          = 'Voulez-vous vraiment &eacute;ffacer le cache?  \nCeci peut affecter la recherche aussi.  \n';

$BL['be_admin_usr_issection']           = 'section du login';
$BL['be_admin_usr_ifsection0']          = 'frontend';
$BL['be_admin_usr_ifsection1']          = 'backend';
$BL['be_admin_usr_ifsection2']          = 'frontend et backend';

// added: 31-03-2005 -- Copy/Paste Article Content - Fernando Batista http://fernandobatista.net
$BL['be_func_content_edit']              = '&eacute;diter cette partie de contenu de l&#39;article';
$BL['be_func_content_paste0']            = 'copier dans l&#39;article';
$BL['be_func_content_paste']             = 'coller dans la partie de contenu de l&#39;article';
$BL['be_func_content_cut']               = 'couper cette partie de contenu de l&#39;article';
$BL['be_func_content_no_cut']            = "Il n'est pas possible de couper cette partie de contenu de l&#39;article!";
$BL['be_func_content_copy']              = 'copier cette partie de contenu de l&#39;article';
$BL['be_func_content_no_copy']           = "Il n'est pas possible de copier cette partie de contenu de l&#39;article!";
$BL['be_func_content_paste_cancel']      = 'annuler le changement de la partie de contenu';

// translate to french - kosse
$BL['be_cnt_move_deleted']               = 'supprimer les fichiers effac&eacute;s';
$BL['be_cnt_move_deleted_msg']           = 'Voulez-vous vraiment d&eacute;placer tous les fichiers \nmarqu&eacute;s comme effac&eacute;s du r&eacute;pertoire sp&eacute;cial d&#39;effacement?  \n';

$BL['be_admin_struct_permit']            = 'autoris&eacute;s &agrave; acc&eacute;der (laiss&eacute; vide pour tous)';
$BL['be_admin_struct_adduser_all']       = 'reprendre tous les utilisateurs';
$BL['be_admin_struct_adduser_this']      = 'reprendre les utilisateurs s&eacute;lectionn&eacute;s';
$BL['be_admin_struct_remove_all']        = 'retirer tous les utilisateurs';
$BL['be_admin_struct_remove_this']       = 'retirer l&#39;utilisateur s&eacute;lectionn&eacute;';


$BL['be_ctype_alias']                    = 'alias de la partie de contenu';
$BL['be_cnt_setting']                    = 'reprendre';
$BL['be_cnt_spaces']                     = 'espaces de l&#39;alias de la partie de contenu';
$BL['be_cnt_toplink']                    = 'lien haut de page pour l&#39;alias de la partie de contenu';
$BL['be_cnt_block']                      = 'montrer (block) de l&#39;alias de la partie de contenu';
$BL['be_cnt_title']                      = 'titres de l&#39;alias de la partie de contenu';

$BL['be_file_replace']                   = 'Remplacer les fichiers &eacute;ponymes';

$BL['be_alias_articleID']                = 'alias ID';
$BL['be_alias_useAll']                   = "utiliser les donn&eacute;es de l&#39;ent&ecirc;te de cet article";
$BL['be_article_morelink']               = 'lien [en savoir plus&#8230;]';
$BL['be_admin_tmpl_copy']                = 'copier le gabarit';

$BL['be_ctype_filelist1']                = 'liste de fichiers pro';
$BL['be_cnt_fpro_usecaption']            = 'utiliser le centre de fichiers &quot;'.$BL['be_ftptakeover_longinfo'].'&quot;';

$BL['be_admin_keywords']                = 'Mots clefs';
$BL['be_admin_keywords_key']            = 'MOT CLEF';
$BL['be_admin_keywords_err']            = 'Ins&eacute;rer un nom de MOT CLEF unique';
$BL['be_admin_keyword_edit']            = '&eacute;diter le MOT CLEF';
$BL['be_admin_keyword_del']             = 'effacer le MOT CLEF';
$BL['be_admin_keyword_delmsg']          = 'Voulez-vous vraiment \neffacer le MOT CLEF?';
$BL['be_admin_keyword_add']             = 'ajouter un MOT CLEF';

$BL['be_cnt_transparent']               = 'Flash transparent';


// added: 02-04-2006
$BL['be_admin_struct_orderkilldate']    = 'date de suppression';
$BL['be_func_switch_contentpart'] = 'Voulez-vous vraiment changer la PARTIE DE CONTENU? \n\nSoyez tr&egrave;s prudents! \nVous pourriez perdre une grande partie de vos donn&eacute;es (comme la mise en forme, pour plus de s&eacute;curit&eacute; copier/coller le code source)! \n';
$BL["phpwcms_code_snippets_dir_exists"] = '<strong>ATTENTION!</strong> Le r&eacute;pertoire &quot;CODE-SNIPPETS&quot; existe encore! Effacer le r&eacute;pertoire <strong>phpwcms_code_snippets</strong> - vous &eacute;viterez des probl&egrave;mes de s&eacute;curit&eacute; potentiels.';

$BL['be_ctype_poll']                    = 'enqu&ecirc;te';
$BL['be_cnt_pos8']                      = 'tableau, gauche';
$BL['be_cnt_pos9']                      = 'tableau, droite';
$BL['be_cnt_pos8i']                     = 'aligner l&#39;image &agrave; gauche dans le tableau';
$BL['be_cnt_pos9i']                     = 'aligner l&#39;image &agrave; droite dans le tableau';

$BL['be_WYSIWYG']                       = '&eacute;diteur WYSIWYG';
$BL['be_WYSIWYG_disabled']              = '&eacute;diteur WYSIWYG d&eacute;sactiv&eacute;';
$BL['be_admin_struct_acat_hiddenactive'] = 'visible lorsqu&#39;activ&eacute;';

$BL['be_login_jsinfo']                  = 'Svp activer votre JavaScript qui est n&eacute;cessaire dans le panneau d&#39;administration!';

$BL['be_admin_struct_maxlist']          = 'articles max. en mode liste';

$BL['be_admin_optgroup_label']          = array(1 => 'texte', 2 => 'image', 3 => 'formulaire', 4 => 'administration', 5 => 'sp&eacute;cial');
$BL['be_cnt_articlemenu_maxchar']       = 'Caract&egrave;res max.';

$BL['be_cnt_sysadmin_system']           = 'syst&egrave;me';

// version check - taken from phpBB ;-)
$BL['Version_up_to_date']               = 'Votre version de phpwcms est &agrave; jour, il n&#39;y a pas de mises &agrave; jour disponibles.';
$BL['Version_not_up_to_date']           = 'Votre version n\est <b>pas</b> &agrave; jour. Il existe des versions plus neuves, svp visitez le forum &agrave; <a href="https://github.com/slackero/phpwcms/releases" target="_blank">GitHub Releases</a> pour obtenir la dern&egrave;re version.';
$BL['Latest_version_info']              = 'La derni&egrave;re version est <b>phpwcms %s</b>.';
$BL['Current_version_info']             = 'Vous avez la version <b>phpwcms %s</b>.';
$BL['Connect_socket_error']             = 'Impossible de se conncter au serveur phpwcms, l&#39;erreur rapport&eacute;e est:<br />%s';
$BL['Socket_functions_disabled']        = 'Impossible d&#39;utiliser les fonctions socket.';
$BL['Mailing_list_subscribe_reminder']  = 'Pour les infos des dern&egrave;res versions de phpwcms, souscrivez &agrave; notre <a href="http://eepurl.com/bm-BrH" target="_blank">liste d&#39;infos (en anglais)</a>.';
$BL['Version_information']              = 'Information de version de phpwcms';

$BL['be_cnt_search_highlight']          = 'surligner';
$BL['be_cnt_results_wordlimit']         = 'mots max. pour le sommaire';
$BL['be_cnt_page_of_pages']             = 'navigation de recherche';
$BL['be_cnt_page_of_pages_descr']       = '{PREV:Retour} page #/##, result ###-####, {NAVI:123}, {NAVI:1-3}, {NEXT:Prochain}';
$BL['be_cnt_search_show_top']           = 'haut de page';
$BL['be_cnt_search_show_bottom']        = 'bas de page';
$BL['be_cnt_search_show_next']          = 'prochain (aussi si pas de lien)';
$BL['be_cnt_search_show_prev']          = 'pr&eacute;c&eacute;dent (aussi si pas de lien)';
$BL['be_cnt_search_show_forall']        = 'toujours visible';
$BL['be_cnt_search_startlevel']         = 'd&eacute;but de la recherche';
$BL['be_cnt_results_minchar']           = 'caract&egrave;res minimaux pour lancer la recherche';

$BL['be_cnt_pagination']                = 'paginer les parties de contenu';
$BL['be_article_pagination']            = 'paginer les articles';
$BL['be_article_per_page']              = 'articles par page';
$BL['be_pagination']                    = 'pagination';


$BL['be_ctype_recipe']                  = 'recette';
$BL['be_ctype_faq']                     = 'faq';
$BL['be_cnt_additional']                = 'addition';
$BL['be_cnt_question']                  = 'question';
$BL['be_cnt_answer']                    = 'r&eacute;ponse';
$BL['be_cnt_same_as_summary']           = 'utiliser l&#39;image de l&#39;article';
$BL['be_cnt_sorting']                   = 'classifier';
$BL['be_cnt_imgupload']                 = 'upload&nbsp;d&#39;image';
$BL['be_cnt_filesize']                  = 'taille du fichier';
$BL['be_cnt_captchalength']             = 'longueur de code du captcha';
$BL['be_cnt_chars']                     = 'caract&egrave;res';
$BL['be_cnt_download']                  = 't&eacute;l&eacute;chargez';
$BL['be_cnt_download_direct']           = 'direct';
$BL['be_cnt_database']                  = 'base de donn&eacute;es';
$BL['be_cnt_formsave_in_db']            = 'sauvegarder &agrave; partir des r&eacute;sultats';

$BL['be_cnt_email_notify']              = 'notifier par email';
$BL['be_cnt_notify_by_email']           = 'par email &agrave;';
$BL['be_cnt_last_edited']               = 'dernier changement';

$BL['be_cnt_export_selection']          = 'exporter la s&eacute;lection';
$BL['be_cnt_delete_duplicates']         = 'effacer les doublons';
$BL['be_cnt_new_recipient']             = 'ajouter un destinataire';


$BL['be_cnt_newsletter_prepare']        = 'newsletter est activ&eacute;e';
$BL['be_cnt_newsletter_prepare1']       = 'tous les destinataires seront mis dans file d&#39;attente';
$BL['be_cnt_newsletter_prepare2']       = 'la file d&#39;attente sera mise &agrave; jour&#8230;';

$BL['be_cnt_export']                    = 'exporter';
$BL['be_cnt_formsave_profile']          = 'sauvegarder les donn&eacute;es de l&#39;utilisateur';
$BL['be_profile_label_add']             = 'Ajouter';
$BL['be_profile_label_website']         = 'Site web (URL)';
$BL['be_profile_label_gender']          = 'genre';
$BL['be_profile_label_birthday']        = 'anniversaire';

$BL['be_cnt_store_in']                  = 'sauvegarder dans le champs';
$BL['be_aboutlink_title']               = 'info &agrave; propos de phpwcms et la licence';

$BL['be_shortdate']                     = 'n/j/y';
$BL['be_shortdatetime']                 = 'n/j/y G:i';

$BL['be_confirm_sending']               = 'confirmer l&#39;envoi';
$BL['be_confirm_text']                  = 'Oui, envoyer la newsletter &agrave; tous les destinataires!';

$BL['be_cnt_queued']                    = 'liste d&#39;attente';
$BL['be_last_sending']                  = 'dernier envoi';
$BL['be_last_edited']                   = 'deni&egrave;re &eacute;dition';
$BL['be_total']                         = 'total';

$BL['be_settings']                      = 'settings';
$BL['be_ctype']                         = 'parties de contenu';
$BL['be_selection']                     = 's&eacute;lection';

$BL['be_ctype_module']                  = 'plug-in';

