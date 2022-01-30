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


// Language: Spain, Language Code: es
// please use HTML safe strings ONLY,neccessary to reduce processing time
// normal line break:    '&#13', JavaScript Linebreak: '\n'


$BL['usr_online']                       = 'Usuarios online';

// Login Page
$BL["login_text"]                       = 'Introducir datos de ingreso';
$BL['login_error']                      = '&iexcl;Errores durante el acceso!';
$BL["login_username"]                   = 'Nombre de usuario';
$BL["login_userpass"]                   = 'Password';
$BL["login_button"]                     = 'Ingresar';
$BL["login_lang"]                       = 'Idioma';

// phpwcms.php
$BL['be_nav_logout']                    = 'SALIR';
$BL['be_nav_articles']                  = 'ART&Iacute;CULOS';
$BL['be_nav_files']                     = 'ARCHIVO';
$BL['be_nav_modules']                   = 'M&Oacute;DULOS';
$BL['be_nav_messages']                  = 'MENSAJES';
$BL['be_nav_chat']                      = 'CHAT';
$BL['be_nav_profile']                   = 'PERFIL';
$BL['be_nav_admin']                     = 'ADMIN';
$BL['be_nav_discuss']                   = 'FORO';

$BL['be_page_title']                    = 'phpwcms backend (Administraci&Oacute;n)';

$BL['be_subnav_article_center']         = 'Centro de art&iacute;culos';
$BL['be_subnav_article_new']            = 'Nuevo art&iacute;culo';
$BL['be_subnav_file_center']            = 'Centro de archivos';
$BL['be_subnav_file_ftptakeover']       = 'ftp takeover';
$BL['be_subnav_mod_artists']            = 'Artista, categor&iacute;a, g&eacute;nero';
$BL['be_subnav_msg_center']             = 'Centro de mensajes';
$BL['be_subnav_msg_new']                = 'Nuevo mensaje';
$BL['be_subnav_msg_newsletter']         = 'Suscripciones al newsletter';
$BL['be_subnav_chat_main']              = 'P&aacute;gina principal del chat';
$BL['be_subnav_chat_internal']          = 'Chat interno';
$BL['be_subnav_profile_login']          = 'informaci&oacute;n de ingreso';
$BL['be_subnav_profile_personal']       = 'informaci&oacute;n personal';
$BL['be_subnav_admin_pagelayout']       = 'Composici&oacute;n de p&aacute;gina';
$BL['be_subnav_admin_templates']        = 'Plantillas';
$BL['be_subnav_admin_css']              = 'css predeterminada';
$BL['be_subnav_admin_sitestructure']    = 'Estructura del sitio';
$BL['be_subnav_admin_users']            = 'Administraci&aacute;n de usuarios';
$BL['be_subnav_admin_filecat']          = 'categor&iacute;as de archivos';


// admin.functions.inc.php
$BL['be_func_struct_articleID']         = 'ID del art&iacute;culo';
$BL['be_func_struct_preview']           = 'Vista previa';
$BL['be_func_struct_edit']              = 'Editar art&iacute;culo';
$BL['be_func_struct_sedit']             = 'Editar el nivel de estructura';
$BL['be_func_struct_cut']               = 'Eliminar art&iacute;culo';
$BL['be_func_struct_nocut']             = 'Deshabilitar eliminaci&oacute;n del art&iacute;culo';
$BL['be_func_struct_svisible']          = 'Cambiar visible/invisible';
$BL['be_func_struct_spublic']           = 'Cambiar p&uacute;blico/no p&uacute;blico';
$BL['be_func_struct_sort_up']           = 'Orden ascendente';
$BL['be_func_struct_sort_down']         = 'Orden descendente';
$BL['be_func_struct_del_article']       = 'Eliminar el art&iacute;culo';
$BL['be_func_struct_del_jsmsg']         = 'Quiere eliminar \nel art&iacute;culo?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_new_article']       = 'Crear nuevo art&iacute;culo en nivel de estructura';
$BL['be_func_struct_paste_article']     = 'Pegar art&iacute;culo en nivel de estructura';
$BL['be_func_struct_insert_level']      = 'Insertar nivel de estructura en';
$BL['be_func_struct_paste_level']       = 'Pegar en nivel de estructura';
$BL['be_func_struct_cut_level']         = 'Eliminar nivel de estructura';
$BL['be_func_struct_no_cut']            = "&iexcl;No es posible eliminar el nivel ra&iacute;z!";
$BL['be_func_struct_no_paste1']         = "&iexcl;No es posible pegar aqui!";
$BL['be_func_struct_no_paste2']         = 'Es hijo en el nivel principal del &aacute;rbol';
$BL['be_func_struct_no_paste3']         = 'Se puede pegar aqu&iacute;';
$BL['be_func_struct_paste_cancel']      = 'Cancelar el cambio de nivel de estructura';
$BL['be_func_struct_del_struct']        = 'Eliminar el nivel de estructura';
$BL['be_func_struct_del_sjsmsg']        = 'Quiere eliminar \nel nivel de estructura?'; // "\n" = JavaScript Linebreak
$BL['be_func_struct_open']              = 'Abrir';
$BL['be_func_struct_close']             = 'Cerrar';
$BL['be_func_struct_empty']             = 'Vac&iacute;o';

// article.contenttype.inc.php
$BL['be_ctype_plaintext']               = 'Texto simple';
$BL['be_ctype_html']                    = 'html';
$BL['be_ctype_code']                    = 'C&oacute;digo';
$BL['be_ctype_textimage']               = 'Text c/imagen';
$BL['be_ctype_images']                  = 'Im&aacute;genes';
$BL['be_ctype_bulletlist']              = 'Lista punteada';
$BL['be_ctype_link']                    = 'link &amp; email';
$BL['be_ctype_linklist']                = 'Lista de links';
$BL['be_ctype_linkarticle']             = 'Link a art&iacute;culo';
$BL['be_ctype_multimedia']              = 'Multimedia';
$BL['be_ctype_filelist']                = 'Lista de archivos';
$BL['be_ctype_emailform']               = 'email form';
$BL['be_ctype_newsletter']              = 'Newsletter';

// profile.create.inc.php
$BL['be_profile_create_success']        = 'Perfil creado exitosamente.';
$BL['be_profile_create_error']          = 'Ocurri&oacute; un error al crear el perfil.';

// profile.update.inc.php
$BL['be_profile_update_success']        = 'informaci&oacute;n del perfil actualizada exitosamente.';
$BL['be_profile_update_error']          = 'Ocurri&oacute; un error al actualizar.';

// profile.updateaccount.inc.php
$BL['be_profile_account_err1']          = 'El nombre de usuario {VAL} no es v&aacute;lido';
$BL['be_profile_account_err2']          = 'La password es corta (solo {VAL} caracteres: minimo 5)';
$BL['be_profile_account_err3']          = 'La password repetida debe ser identica';
$BL['be_profile_account_err4']          = 'El email {VAL} no es v&aacute;lido';

// profile.data.tmpl.php
$BL['be_profile_data_title']            = 'Su informaci&oacute;n personal';
$BL['be_profile_data_text']             = 'La informaci&oacute;n personal es optativa. Ella puede ayudar a otros usuarios o visitantes del sitio a saber mas sobre usted, sus intereses y conocimientos. Los usuarios podran ver (o no) la informaci&oacute;n de su perfil en el area publica o en p&aacute;ginas de art&iacute;culos de acuerdo con los casilleros que usted seleccione.';
$BL['be_profile_label_title']           = 'Tratamiento';
$BL['be_profile_label_firstname']       = 'Nombre';
$BL['be_profile_label_name']            = 'Apellido';
$BL['be_profile_label_company']         = 'Empresa';
$BL['be_profile_label_street']          = 'Calle';
$BL['be_profile_label_city']            = 'Ciudad';
$BL['be_profile_label_state']           = 'Provincia, Estado';
$BL['be_profile_label_zip']             = 'zip, codigo postal';
$BL['be_profile_label_country']         = 'Pa&iacute;s';
$BL['be_profile_label_phone']           = 'Tel&eacute;fono';
$BL['be_profile_label_fax']             = 'Fax';
$BL['be_profile_label_cellphone']       = 'M&oacute;vil';
$BL['be_profile_label_signature']       = 'Firma';
$BL['be_profile_label_notes']           = 'Nota';
$BL['be_profile_label_profession']      = 'Profesi&oacute;n';
$BL['be_profile_label_newsletter']      = 'Newsletter';
$BL['be_profile_text_newsletter']       = 'Deseo recibir el newsletter de phpwcms.';
$BL['be_profile_label_public']          = 'P&uacute;blico';
$BL['be_profile_text_public']           = 'Cualquiera puede ver mi perfil personal.';
$BL['be_profile_label_button']          = 'Actualizar informaci&oacute;n personal';

// profile.account.tmpl.php
$BL['be_profile_account_title']         = 'Sus datos de ingreso';
$BL['be_profile_account_text']          = 'Normalmente no es necesario que cambie su nombre de usuario.<br />Sin embargo, cada tanto deber&iacute;a modificar su password por razones de seguridad.';
$BL['be_profile_label_err']             = 'Por favor controlar';
$BL['be_profile_label_username']        = 'Nombre de usuario';
$BL['be_profile_label_newpass']         = 'Nueva password';
$BL['be_profile_label_repeatpass']      = 'Repetir la nueva pwd';
$BL['be_profile_label_email']           = 'email';
$BL['be_profile_account_button']        = 'Actualizar los datos de ingreso';
$BL['be_profile_label_lang']            = 'Idioma';


// files.ftptakeover.tmpl.php
$BL['be_ftptakeover_title']             = 'Tomar los archivos subidos por ftp';
$BL['be_ftptakeover_mark']              = 'Marcar';
$BL['be_ftptakeover_available']         = 'Archivos disponibles';
$BL['be_ftptakeover_size']              = 'Tama&ntilde;o';
$BL['be_ftptakeover_nofile']            = 'No hay archivos disponibles &#8211; debe subir alguno por ftp';
$BL['be_ftptakeover_all']               = 'TODOS';
$BL['be_ftptakeover_directory']         = 'carpeta';
$BL['be_ftptakeover_rootdir']           = 'Directorio ra&iacute;z';
$BL['be_ftptakeover_needed']            = '&iexcl;Es necesario!!! (Debe seleccionar uno)';
$BL['be_ftptakeover_optional']          = 'opcional';
$BL['be_ftptakeover_keywords']          = 'Palabras clave';
$BL['be_ftptakeover_additional']        = 'Adicional';
$BL['be_ftptakeover_longinfo']          = 'M&aacute;s info';
$BL['be_ftptakeover_status']            = 'Estado';
$BL['be_ftptakeover_active']            = 'Activo';
$BL['be_ftptakeover_public']            = 'P&uacute;blico';
$BL['be_ftptakeover_createthumb']       = 'Crear miniatura';
$BL['be_ftptakeover_button']            = 'Tomar los archivos seleccionados';

// files.reiter.tmpl.php
$BL['be_ftab_title']                    = 'Centro de archivos';
$BL['be_ftab_createnew']                = 'Crear nueva carpeta en la ra&iacute;z';
$BL['be_ftab_paste']                    = 'Pegar el archivo del portapapeles en la ra&iacute;z';
$BL['be_ftab_disablethumb']             = 'Deshabilitar miniaturas en la lista';
$BL['be_ftab_enablethumb']              = 'Habilitar miniaturas en la lista';
$BL['be_ftab_private']                  = 'Privados';
$BL['be_ftab_public']                   = 'P&uacute;blicos';
$BL['be_ftab_search']                   = 'Buscar';
$BL['be_ftab_trash']                    = 'Papelera';
$BL['be_ftab_open']                     = 'Abrir todas las carpetas';
$BL['be_ftab_close']                    = 'Cerrar todas las carpetas abiertas';
$BL['be_ftab_upload']                   = 'Subir archivo al directorio ra&iacute;z';
$BL['be_ftab_filehelp']                 = 'Abrir archivo de ayuda';

// files.private.newdir.tmpl.php
$BL['be_fpriv_rootdir']                 = 'Directorio ra&iacute;z';
$BL['be_fpriv_title']                   = 'Crear nueva carpeta';
$BL['be_fpriv_inside']                  = 'Adentro';
$BL['be_fpriv_error']                   = 'Error: escribir un nombre para el directorio';
$BL['be_fpriv_name']                    = 'Nombre';
$BL['be_fpriv_status']                  = 'Estado';
$BL['be_fpriv_button']                  = 'Crear nueva carpeta';

// files.private.editdir.tmpl.php
$BL['be_fpriv_edittitle']               = 'Editar la carpeta';
$BL['be_fpriv_newname']                 = 'Nuevo nombre';
$BL['be_fpriv_updatebutton']            = 'Actualizar la informaci&oacute;n de la carpeta';

// files.private.upload.tmpl.php
$BL['be_fprivup_err1']                  = 'Seleccionar un archivo que se desee subir';
$BL['be_fprivup_err2']                  = 'El tama&ntilde;o del archivo subido es mayor de';
$BL['be_fprivup_err3']                  = 'Error al escribir en la memoria';
$BL['be_fprivup_err4']                  = 'Error al crear carpeta de usuario.';
$BL['be_fprivup_err5']                  = 'No existen miniaturas';
$BL['be_fprivup_err6']                  = 'Por favor no intente nuevamente - &iexcl;este es un error del servidor! &iexcl;Cont&aacute;ctese con su <a href="mailto:{VAL}">webmaster</a> lo m&aacute;s pronto posible!';
$BL['be_fprivup_title']                 = 'Subir archivos';
$BL['be_fprivup_button']                = 'Subir archivos';
$BL['be_fprivup_upload']                = 'Subir';

// files.private.editfile.tmpl.php
$BL['be_fprivedit_title']               = 'Editar la informaci&oacute;n de archivo';
$BL['be_fprivedit_filename']            = 'Nombre de archivo';
$BL['be_fprivedit_created']             = 'Creado';
$BL['be_fprivedit_dateformat']          = 'd-m-Y H:i';
$BL['be_fprivedit_err1']                = 'Probar nombre de archivo (Volver al original)';
$BL['be_fprivedit_clockwise']           = 'Girar la miniatura de izquierda a derecha [archivo original +90&deg;]';
$BL['be_fprivedit_cclockwise']          = 'Girar la miniatura de derecha a izquierda [archivo original -90&deg;]';
$BL['be_fprivedit_button']              = 'Actualizar informaci&oacute;n de archivo';
$BL['be_fprivedit_size']                = 'Tama&ntilde;o';

// files.private-functions.inc.php
$BL['be_fprivfunc_upload']              = 'Subir archivo a la carpeta';
$BL['be_fprivfunc_makenew']             = 'Crear nueva carpeta adentro';
$BL['be_fprivfunc_paste']               = 'Pegar el archivo del portapapeles en la carpeta';
$BL['be_fprivfunc_edit']                = 'Editar la carpeta';
$BL['be_fprivfunc_cactive']             = 'Cambiar activo/inactivo';
$BL['be_fprivfunc_cpublic']             = 'Cambiar p&uacute;blico/no p&uacute;blico';
$BL['be_fprivfunc_deldir']              = 'Borrar carpeta';
$BL['be_fprivfunc_jsdeldir']            = 'Quiere eliminar \nla carpeta';
$BL['be_fprivfunc_notempty']            = '&iexcl;La carpeta {VAL} no esta vac&iacute;a!';
$BL['be_fprivfunc_opendir']             = 'Abrir la carpeta';
$BL['be_fprivfunc_closedir']            = 'Cerrar la carpeta';
$BL['be_fprivfunc_dlfile']              = 'Bajar el archivo';
$BL['be_fprivfunc_clipfile']            = 'Archivo del portapapeles';
$BL['be_fprivfunc_cutfile']             = 'Cortar';
$BL['be_fprivfunc_editfile']            = 'Editar la informaci&oacute;n de archivo';
$BL['be_fprivfunc_cactivefile']         = 'Cambiar activo/inactivo';
$BL['be_fprivfunc_cpublicfile']         = 'Cambiar p&uacute;blico/no p&uacute;blico';
$BL['be_fprivfunc_movetrash']           = 'Ubicar en la papelera';
$BL['be_fprivfunc_jsmovetrash1']        = 'Quiere ubicarlo';
$BL['be_fprivfunc_jsmovetrash2']        = 'en la papelera?';

// files.private.additions.inc.php
$BL['be_fprivadd_nofolders']            = 'No hay archivos ni carpetas privados';

// files.public.list.tmpl.php
$BL['be_fpublic_user']                  = 'Usuario';
$BL['be_fpublic_nofiles']               = 'No hay archivos ni carpetas p&uacute;blicos';

// files.private.trash.tmpl.php
$BL['be_ftrash_nofiles']                = 'La papelera est&aacute; vac&iacute;a';
$BL['be_ftrash_show']                   = 'Mostrar archivos privados';

// files.private-delfilelist.inc.php
$BL['be_ftrash_restore']                = 'Desea recuperarlo {VAL} \ny ubicarlo en la lista privada?';
$BL['be_ftrash_delete']                 = 'Quiere eliminarlo {VAL}?';
$BL['be_ftrash_undo']                   = 'recuperarlo (quitar de la papelera)';
$BL['be_ftrash_delfinal']               = 'eliminaci&oacute;n definitiva';

// files.search.tmpl.php
$BL['be_fsearch_err1']                  = 'El campo de b&uacute;squeda est&aacute; vac&iacute;o.';
$BL['be_fsearch_title']                 = 'Buscar archivos';
$BL['be_fsearch_infotext']              = 'Esta es una b&uacute;squeda b&aacute;sica sobre informaci&oacute;n de archivos. Se buscan coincidencias en palabras clave,<br />nombres de archivos y descripci&oacute;n de archivos. No hay soporte para metacaracteres (wildcards). Separar la b&uacute;squeda de varias palabras<br />con un espacio. Seleccionar Y/O y en qu&eacute; archivos buscar: privados/p&uacute;blicos.';
$BL['be_fsearch_nonfound']              = 'No se encontraron archivos para su b&uacute;squeda. &iexcl;Modifique su criterio de b&uacute;squeda!';
$BL['be_fsearch_fillin']                = 'Por favor complete el campo de b&uacute;squeda.';
$BL['be_fsearch_searchlabel']           = 'Buscar por';
$BL['be_fsearch_startsearch']           = 'Comenzar la b&uacute;squeda';
$BL['be_fsearch_and']                   = 'Y';
$BL['be_fsearch_or']                    = 'O';
$BL['be_fsearch_all']                   = 'Todos los archivos';
$BL['be_fsearch_personal']              = 'Privados';
$BL['be_fsearch_public']                = 'P&uacute;blicos';

// chat.main.tmpl.php & chat.list.tmpl.php
$BL['be_chat_title']                    = 'Chat interno';
$BL['be_chat_info']                     = 'Aqu&iacute; es posible chatear con otros usuarios de la administraci&oacute;n sobre diversos temas. Este medio es para conversar en tiempo real, pero tambi&eacute;n se pueden dejar mensajes que podr&aacute;n ser le&iacute;dos por todos. Si se desea intercambiar ideas con otros usuarios, por favor utilizar la secci&oacute;n de foro, que aparecer&aacute; en una versi&oacute;n posterior.';
$BL['be_chat_start']                    = 'Hacer clic aqu&iacute; para comenzar el chat';
$BL['be_chat_lines']                    = 'Mensajes de chat';

// message.center.tmpl.php
$BL['be_msg_title']                     = 'Centro de mensajes';
$BL['be_msg_new']                       = 'Nuevo';
$BL['be_msg_old']                       = 'Anterior';
$BL['be_msg_senttop']                   = 'Enviado';
$BL['be_msg_del']                       = 'Eliminado';
$BL['be_msg_from']                      = 'De';
$BL['be_msg_subject']                   = 'Asunto';
$BL['be_msg_date']                      = 'Fecha/Hora';
$BL['be_msg_close']                     = 'Cerrar el mensaje';
$BL['be_msg_create']                    = 'Crear un nuevo mensaje';
$BL['be_msg_reply']                     = 'Responder a este mensaje';
$BL['be_msg_move']                      = 'Enviar este mensaje a la papelera';
$BL['be_msg_unread']                    = 'Mensajes sin leer o nuevos';
$BL['be_msg_lastread']                  = '&Uacute;ltimos {VAL} mensajes le&iacute;dos';
$BL['be_msg_lastsent']                  = '&Uacute;ltimos {VAL} mensajes enviados';
$BL['be_msg_marked']                    = 'Mensajes marcados para ser eliminados (trash)';
$BL['be_msg_nomsg']                     = 'No se encontr&oacute; ning&uacute;n mensaje en esta carpeta';

// message.send.tmpl.php
$BL['be_msg_RE']                        = 'RE';
$BL['be_msg_by']                        = 'Enviado por';
$BL['be_msg_on']                        = 'En';
$BL['be_msg_msg']                       = 'Mensaje';
$BL['be_msg_err1']                      = 'Falta indicar el destinatario...';
$BL['be_msg_err2']                      = 'Completar el campo del asunto (As&iacute; el destinatario podr&aacute; atender mejor su mensaje.';
$BL['be_msg_err3']                      = 'No tiene sentido enviar un mensaje sin mensaje ;-)';
$BL['be_msg_sent']                      = '&iexcl;Fue enviado un nuevo mensaje!';
$BL['be_msg_fwd']                       = 'Ir al centro de mensajes o';
$BL['be_msg_newmsgtitle']               = 'escribir un nuevo mensaje';
$BL['be_msg_err']                       = 'Error al enviar el mensaje';
$BL['be_msg_sendto']                    = 'Enviar mensaje a';
$BL['be_msg_available']                 = 'Lista de destinatarios disponibles';
$BL['be_msg_all']                       = 'Enviar el mensaje a todos los destinatarios seleccionados';

// message.subscription.tmpl.php
$BL['be_newsletter_title']              = 'Suscripciones al newsletter';
$BL['be_newsletter_titleedit']          = 'Editar la suscripci&oacute;n al newsletter';
$BL['be_newsletter_new']                = 'Crear una nueva';
$BL['be_newsletter_add']                = 'Agregar&nbsp;suscripci&oacute;n&nbsp;al&nbsp;newsletter';
$BL['be_newsletter_name']               = 'Nombre';
$BL['be_newsletter_info']               = 'Info';
$BL['be_newsletter_button_save']        = 'Guardar la suscripci&oacute;n';
$BL['be_newsletter_button_cancel']      = 'cancelar';

// admin.newuser.tmpl.php
$BL['be_admin_usr_err1']                = 'El nombre de usuario no es v&aacute;lido, elija otro';
$BL['be_admin_usr_err2']                = 'Falta completar el nombre de usuario (Es necesario)';
$BL['be_admin_usr_err3']                = 'Falta completar la password (Es necesaria)';
$BL['be_admin_usr_err4']                = "El email no es v&aacute;lido";
$BL['be_admin_usr_err']                 = 'Error';
$BL['be_admin_usr_mailsubject']         = 'Bienvenido a la administraci&oacute;n de phpwcms';
$BL['be_admin_usr_mailbody']            = "BIENVENIDO A LA ADMINISTRACI&Oacute;N DE PHPWCMS\n\n    usuario: {LOGIN}\n    password: {PASSWORD}\n\n\nPuede ingresar aqu&iacute;: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_title']               = 'Agregar nuevo usuario';
$BL['be_admin_usr_realname']            = 'Nombre real';
$BL['be_admin_usr_setactive']           = 'Activaci&oacute;n';
$BL['be_admin_usr_iflogin']             = 'Si se marca la casilla el usuario puede ingresar';
$BL['be_admin_usr_isadmin']             = 'Administraci&oacute;n';
$BL['be_admin_usr_ifadmin']             = 'Si se marca la casilla el usuario tiene derechos de administrador';
$BL['be_admin_usr_verify']              = 'Verificaci&oacute;n';
$BL['be_admin_usr_sendemail']           = 'Enviar un email al nuevo usuario con la informaci&oacute;n de la cuenta';
$BL['be_admin_usr_button']              = 'Enviar los datos del usuario';

// admin.edituser.tmpl.php
$BL['be_admin_usr_etitle']              = 'Editar la cuenta del usuario';
$BL['be_admin_usr_emailsubject']        = 'phpwcms - Se modific&oacute; la informaci&oacute;n de la cuenta';
$BL['be_admin_usr_emailbody']           = "PHPWCMS SE MODIFICO LA INFORMACI&Oacute;N DE LA CUENTA DE USUARIO\n\n    usuario: {LOGIN}\n    password: {PASSWORD}\n\n\nPuede ingresar aqu&iacute;: {LOGIN_PAGE}\n\nphpwcms admin\n ";
$BL['be_admin_usr_passnochange']        = '[SIN MODIFICAR - USAR LA PASSWORD CONOCIDA]';
$BL['be_admin_usr_ebutton']             = 'Actualizar informaci&oacute;n de usuario';

// admin.listuser.tmpl.php
$BL['be_admin_usr_ltitle']              = 'Lista de usuarios de phpwcms';
$BL['be_admin_usr_ldel']                = 'ATENCI&Oacute;N!&#13Esto eliminar&aacute; al usuario';
$BL['be_admin_usr_create']              = 'Crear nuevo usuario';
$BL['be_admin_usr_editusr']             = 'Editar el usuario';

// admin.structform.tmpl.php
$BL['be_admin_struct_title']            = 'Estructura del sitio';
$BL['be_admin_struct_child']            = '(Dentro de)';
$BL['be_admin_struct_index']            = 'Homepage (Inicio)';
$BL['be_admin_struct_cat']              = 'T&iacute;tulo de la categor&iacute;a';
$BL['be_admin_struct_status']           = 'Estado dentro del men&uacute;';
$BL['be_admin_struct_hide1']            = 'Ocultar';
$BL['be_admin_struct_hide2']            = 'Esta&nbsp;categor&iacute;a&nbsp;en&nbsp;el&nbsp;men&uacute;';
$BL['be_admin_struct_info']             = 'Informaci&oacute;n sobre la categor&iacute;a';
$BL['be_admin_struct_template']         = 'Plantilla';
$BL['be_admin_struct_alias']            = 'Alias de la categor&iacute;a';
$BL['be_admin_struct_visible']          = 'Visible';
$BL['be_admin_struct_button']           = 'Enviar informaci&oacute;n de la categor&iacute;a';
$BL['be_admin_struct_close']            = 'Cerrar';

// admin.filecat.tmpl.php
$BL['be_admin_fcat_title']              = 'Categor&iacute;as de archivos';
$BL['be_admin_fcat_err']                = '&iexcl;El nombre de la categor&iacute;a est&aacute; vac&iacute;o!';
$BL['be_admin_fcat_name']               = 'Nombre de la categor&iacute;a';
$BL['be_admin_fcat_needed']             = 'Necesario';
$BL['be_admin_fcat_button1']            = 'Actualizar';
$BL['be_admin_fcat_button2']            = 'Crear';
$BL['be_admin_fcat_delmsg']             = 'Quiere eliminar\nla clave del archivo?';
$BL['be_admin_fcat_fcat']               = 'Categor&iacute;a de archivo';
$BL['be_admin_fcat_err1']               = 'El nombre de la clave de archivo est&aacute; vac&iacute;o!';
$BL['be_admin_fcat_fkeyname']           = 'Nombre de la clave de archivo';
$BL['be_admin_fcat_exit']               = 'Salir de la edici&oacute;n';
$BL['be_admin_fcat_addkey']             = 'Agregar nueva clave';
$BL['be_admin_fcat_editcat']            = 'Editar el nombre de la categor&iacute;a';
$BL['be_admin_fcat_delcatmsg']          = 'Quiere eliminar\nla categor&iacute;a de archivo?';
$BL['be_admin_fcat_delcat']             = 'Eliminar la categor&iacute;a de archivo';
$BL['be_admin_fcat_delkey']             = 'Eliminar nombre de la clave de archivo';
$BL['be_admin_fcat_editkey']            = 'Editar la clave';
$BL['be_admin_fcat_addcat']             = 'Crear nueva categor&iacute;a de archivo';

// admin.pagelayout.tmpl.php
$BL['be_admin_page_title']              = 'Configuraci&oacute;n de la interfaz: composici&oacute;n de p&aacute;gina';
$BL['be_admin_page_align']              = 'Alineamiento de la p&aacute;gina';
$BL['be_admin_page_align_left']         = 'Alineamiento normal (a la izquierda) del contenido total de la p&aacute;gina';
$BL['be_admin_page_align_center']       = 'Centrar el contenido total de la p&aacute;gina';
$BL['be_admin_page_align_right']        = 'Alineamiento a la derecha del contenido total de la p&aacute;gina';
$BL['be_admin_page_margin']             = 'Margen';
$BL['be_admin_page_top']                = 'Superior';
$BL['be_admin_page_bottom']             = 'Inferior';
$BL['be_admin_page_left']               = 'Izquierdo';
$BL['be_admin_page_right']              = 'Derecho';
$BL['be_admin_page_bg']                 = 'Fondo';
$BL['be_admin_page_color']              = 'Color';
$BL['be_admin_page_height']             = 'Altura';
$BL['be_admin_page_width']              = 'Ancho';
$BL['be_admin_page_main']               = 'Principal';
$BL['be_admin_page_leftspace']          = 'Espacio a la izquierda';
$BL['be_admin_page_rightspace']         = 'Espacio a la derecha';
$BL['be_admin_page_class']              = 'Class';
$BL['be_admin_page_image']              = 'Imagen';
$BL['be_admin_page_text']               = 'Texto';
$BL['be_admin_page_link']               = 'Link';
$BL['be_admin_page_js']                 = 'Javascript';
$BL['be_admin_page_visited']            = 'Visitado';
$BL['be_admin_page_pagetitle']          = 'T&iacute;tulo de p&aacute;g.';
$BL['be_admin_page_addtotitle']         = 'Agregar al t&iacute;tulo';
$BL['be_admin_page_category']           = 'Categor&iacute;a';
$BL['be_admin_page_articlename']        = 'Nombre del art&iacute;culo';
$BL['be_admin_page_blocks']             = 'Bloques';
$BL['be_admin_page_allblocks']          = 'Todos los bloques';
$BL['be_admin_page_col1']               = 'Composici&oacute;n de 3 columnas';
$BL['be_admin_page_col2']               = 'Composici&oacute;n de 2 columnas (principal a la derecha, men&uacute; a la izquierda)';
$BL['be_admin_page_col3']               = 'Composici&oacute;n de 2 columnas (principal a la izquierda, men&uacute; a la derecha)';
$BL['be_admin_page_col4']               = 'Composici&oacute;n de una columna';
$BL['be_admin_page_header']             = 'Encabezado';
$BL['be_admin_page_footer']             = 'Pie';
$BL['be_admin_page_topspace']           = 'Espacio superior';
$BL['be_admin_page_bottomspace']        = 'Espacio inferior';
$BL['be_admin_page_button']             = 'Guardar composici&oacute;n de p&aacute;gina';

// admin.frontendcss.tmpl.php
$BL['be_admin_css_title']               = 'Configuraci&oacute;n de la interfaz: informaci&oacute;n de css';
$BL['be_admin_css_css']                 = 'css';
$BL['be_admin_css_button']              = 'Guardar informaci&oacute;n de css';

// admin.templates.tmpl.php
$BL['be_admin_tmpl_title']              = 'Configuraci&oacute;n de la interfaz: plantillas';
$BL['be_admin_tmpl_default']            = 'Default';
$BL['be_admin_tmpl_add']                = 'Agregar `plantilla';
$BL['be_admin_tmpl_edit']               = 'Editar la plantilla';
$BL['be_admin_tmpl_new']                = 'Crear nueva';
$BL['be_admin_tmpl_css']                = 'Archivo css';
$BL['be_admin_tmpl_head']               = 'html head';
$BL['be_admin_tmpl_js']                 = 'js onload';
$BL['be_admin_tmpl_error']              = 'Error';
$BL['be_admin_tmpl_button']             = 'Guardar plantilla';
$BL['be_admin_tmpl_name']               = 'Nombre';

// article.structlist.tmpl.php
$BL['be_article_title']                 = 'Estructura del sitio y lista de art&iacute;culos';

// article.new.tmpl.php
$BL['be_article_err1']                  = 'Falta agregar el t&iacute;tulo';
$BL['be_article_err2']                  = 'La fecha de inicio es incorrecta - corregirla ahora';
$BL['be_article_err3']                  = 'La fecha de terminaci&oacute;n es incorrecta - corregirla ahora';
$BL['be_article_title1']                = 'Informaci&oacute;n sobre el art&iacute;culo';
$BL['be_article_cat']                   = 'Categor&iacute;a';
$BL['be_article_atitle']                = 'T&iacute;tulo del art&iacute;culo';
$BL['be_article_asubtitle']             = 'Subt&iacute;tulo';
$BL['be_article_abegin']                = 'Comienza';
$BL['be_article_aend']                  = 'Termina';
$BL['be_article_aredirect']             = 'Redirigir a';
$BL['be_article_akeywords']             = 'Palabras clave';
$BL['be_article_asummary']              = 'Resumen';
$BL['be_article_abutton']               = 'Crear nuevo art&iacute;culo';

// article.editcontent.inc.php
$BL['be_article_err4']                  = 'La fecha de terminaci&oacute;n es incorrecta - Configurar para ahora + una semana';

// article.editsummary.tmpl.php
$BL['be_article_estitle']               = 'Editar la informaci&oacute;n b&aacute;sica del art&iacute;culo';
$BL['be_article_eslastedit']            = '&Uacute;ltima edici&oacute;n';
$BL['be_article_esnoupdate']            = 'No actualizado';
$BL['be_article_esbutton']              = 'Actualizar la informaci&oacute;n del art&iacute;culo';

// articlecontent.edit.tmpl.php
$BL['be_article_cnt_title']             = 'Contenido del art&iacute;culo';
$BL['be_article_cnt_type']              = 'Tipo de contenido';
$BL['be_article_cnt_space']             = 'Espacio';
$BL['be_article_cnt_before']            = 'Antes';
$BL['be_article_cnt_after']             = 'Despu&eacute;s';
$BL['be_article_cnt_top']               = 'Arriba';
$BL['be_article_cnt_ctitle']            = 'T&iacute;tulo del contenido';
$BL['be_article_cnt_back']              = 'Informaci&oacute;n completa del art&iacute;culo';
$BL['be_article_cnt_button1']           = 'Actualizar el contenido';
$BL['be_article_cnt_button2']           = 'Crear contenido';

// articlecontent.list.tmpl.php
$BL['be_article_cnt_ltitle']            = 'Informaci&oacute;n del art&iacute;culo';
$BL['be_article_cnt_ledit']             = 'Editar el art&iacute;culo';
$BL['be_article_cnt_lvisible']          = 'Cambiar visible/invisible';
$BL['be_article_cnt_ldel']              = 'Eliminar este art&iacute;culo';
$BL['be_article_cnt_ldeljs']            = 'Elimina el art&iacute;culo?';
$BL['be_article_cnt_redirect']          = 'Redirecci&oacute;n';
$BL['be_article_cnt_edited']            = 'Editado por';
$BL['be_article_cnt_start']             = 'Fecha de comienzo';
$BL['be_article_cnt_end']               = 'Fecha de terminaci&oacute;n';
$BL['be_article_cnt_add']               = 'Agregar nuevo contenido';
$BL['be_article_cnt_up']                = 'Desplazar el contenido hacia arriba';
$BL['be_article_cnt_down']              = 'Desplazar el contenido hacia abajo';
$BL['be_article_cnt_edit']              = 'Editar el contenido';
$BL['be_article_cnt_delpart']           = 'Eliminar este contenido del art&iacute;culo';
$BL['be_article_cnt_delpartjs']         = 'Elimina este contenido?';
$BL['be_article_cnt_center']            = 'Centro de art&iacute;culos';

// content forms
$BL['be_cnt_plaintext']                 = 'Texto simple';
$BL['be_cnt_htmltext']                  = 'Texto html';
$BL['be_cnt_image']                     = 'Imagen';
$BL['be_cnt_position']                  = 'Posici&oacute;n';
$BL['be_cnt_pos0']                      = 'Arriba, izquierda';
$BL['be_cnt_pos1']                      = 'Arriba, centro';
$BL['be_cnt_pos2']                      = 'Arriba, derecha';
$BL['be_cnt_pos3']                      = 'Abajo, izquierda';
$BL['be_cnt_pos4']                      = 'Abajo, centro';
$BL['be_cnt_pos5']                      = 'Abajo, derecha';
$BL['be_cnt_pos6']                      = 'En el texto, izquierda';
$BL['be_cnt_pos7']                      = 'En el texto, derecha';
$BL['be_cnt_pos0i']                     = 'Alinear la imagen arriba y a la izquierda del bloque de texto';
$BL['be_cnt_pos1i']                     = 'Alinear la imagen arriba y en el centro del bloque de texto';
$BL['be_cnt_pos2i']                     = 'Alinear la imagen arriba y a la derecha del bloque de texto';
$BL['be_cnt_pos3i']                     = 'Alinear la imagen abajo y a la izquierda del bloque de texto';
$BL['be_cnt_pos4i']                     = 'Alinear la imagen abajo y en el centro del bloque de texto';
$BL['be_cnt_pos5i']                     = 'Alinear la imagen arriba y a la derecha del bloque de texto';
$BL['be_cnt_pos6i']                     = 'Alinear la imagen a la izquierda dentro del bloque de texto';
$BL['be_cnt_pos7i']                     = 'Alinear la imagen a la derecha dentro del bloque de texto';
$BL['be_cnt_maxw']                      = 'Ancho&nbsp;m&aacute;x.';
$BL['be_cnt_maxh']                      = 'Altura&nbsp;m&aacute;x.';
$BL['be_cnt_enlarge']                   = 'clic&nbsp;para&nbsp;ampliar';
$BL['be_cnt_caption']                   = 'Descripci&oacute;n';
$BL['be_cnt_subject']                   = 'Asunto';
$BL['be_cnt_recipient']                 = 'Destinatario';
$BL['be_cnt_buttontext']                = 'Texto del bot&oacute;n';
$BL['be_cnt_sendas']                    = 'Enviar como';
$BL['be_cnt_text']                      = 'Texto';
$BL['be_cnt_html']                      = 'html';
$BL['be_cnt_formfields']                = 'Campos de formulario';
$BL['be_cnt_code']                      = 'C&oacute;digo';
$BL['be_cnt_infotext']                  = 'Texto&nbsp;informativo';
$BL['be_cnt_subscription']              = 'Subscripci&oacute;n';
$BL['be_cnt_labelemail']                = 'Etiqueta&nbsp;email';
$BL['be_cnt_tablealign']                = 'Alinear&nbsp;tabla';
$BL['be_cnt_labelname']                 = 'Etiqueta&nbsp;nombre';
$BL['be_cnt_labelsubsc']                = 'Etiqueta&nbsp;subscr.';
$BL['be_cnt_allsubsc']                  = 'Subscribir&nbsp;a&nbsp;todas&nbsp;las&nbsp;subscr.';
$BL['be_cnt_default']                   = 'Est&aacute;ndar';
$BL['be_cnt_left']                      = 'Izquierda';
$BL['be_cnt_center']                    = 'Centro';
$BL['be_cnt_right']                     = 'Derecha';
$BL['be_cnt_buttontext']                = 'Texto&nbsp;del&nbsp;bot&oacute;n';
$BL['be_cnt_successtext']               = 'Satisfactorio';
$BL['be_cnt_regmail']                   = 'regist.email';
$BL['be_cnt_logoffmail']                = 'logoff.email';
$BL['be_cnt_changemail']                = 'cambio.email';
$BL['be_cnt_openimagebrowser']          = 'Abrir explorador de im&aacute;genes';
$BL['be_cnt_openfilebrowser']           = 'Abrir explorador de archivos';
$BL['be_cnt_sortup']                    = 'Desplazar hacia arriba';
$BL['be_cnt_sortdown']                  = 'Desplazar hacia abajo';
$BL['be_cnt_delimage']                  = 'Eliminar la imagen seleccionada';
$BL['be_cnt_delfile']                   = 'Eliminar el archivo seleccionado';
$BL['be_cnt_delmedia']                  = 'Eliminar el medio seleccionado';
$BL['be_cnt_column']                    = 'Columna';
$BL['be_cnt_imagespace']                = 'Espacio&nbsp;de&nbsp;imagen';
$BL['be_cnt_directlink']                = 'Link directo';
$BL['be_cnt_target']                    = 'Objetivo';
$BL['be_cnt_target1']                   = 'En una nueva ventana';
$BL['be_cnt_target2']                   = 'en el parent frame de la ventana';
$BL['be_cnt_target3']                   = 'En la misma ventana sin frames';
$BL['be_cnt_target4']                   = 'En el mismo frame o ventana';
$BL['be_cnt_bullet']                    = 'Lista punteada';
$BL['be_cnt_linklist']                  = 'Lista de links';
$BL['be_cnt_plainhtml']                 = 'Html simple';
$BL['be_cnt_files']                     = 'Archivos';
$BL['be_cnt_description']               = 'Descripci&oacute;n';
$BL['be_cnt_linkarticle']               = 'Link a art&iacute;culo';
$BL['be_cnt_articles']                  = 'Art&iacute;culos';
$BL['be_cnt_movearticleto']             = 'Desplazar el art&iacute;culo seleccionado a la lista de links a art&iacute;culos';
$BL['be_cnt_removearticleto']           = 'Quitar el art&iacute;culo seleccionado de la lista de links a art&iacute;culos';
$BL['be_cnt_mediatype']                 = 'Tipo de medio';
$BL['be_cnt_control']                   = 'Control';
$BL['be_cnt_showcontrol']               = 'Mostrar la barra de control';
$BL['be_cnt_autoplay']                  = 'Autoplay';
$BL['be_cnt_source']                    = 'Origen';
$BL['be_cnt_internal']                  = 'Interno';
$BL['be_cnt_openmediabrowser']          = 'Abrir el explorador de medios';
$BL['be_cnt_external']                  = 'Externo';
$BL['be_cnt_mediapos0']                 = 'Izquierdo (default)';
$BL['be_cnt_mediapos1']                 = 'Centro';
$BL['be_cnt_mediapos2']                 = 'Derecha';
$BL['be_cnt_mediapos3']                 = 'Bloque, izquierda';
$BL['be_cnt_mediapos4']                 = 'Bloque, derecha';
$BL['be_cnt_mediapos0i']                = 'Alinear medio arriba y a la izquierda del bloque de texto';
$BL['be_cnt_mediapos1i']                = 'Alinear medio arriba y al centro del bloque de texto';
$BL['be_cnt_mediapos2i']                = 'Alinear medio arriba y a la derecha del bloque de texto';
$BL['be_cnt_mediapos3i']                = 'Alinear medio a la izquierda dentro del bloque de texto';
$BL['be_cnt_mediapos4i']                = 'Alinear medio a la derecha dentro del bloque de texto';
$BL['be_cnt_setsize']                   = 'Configurar tama&ntilde;o';
$BL['be_cnt_set1']                      = 'Configurar tama&ntilde;o de medio en 160x120px';
$BL['be_cnt_set2']                      = 'Configurar tama&ntilde;o de medio en 240x180px';
$BL['be_cnt_set3']                      = 'Configurar tama&ntilde;o de medio en 320x240px';
$BL['be_cnt_set4']                      = 'Configurar tama&ntilde;o de medio en 480x360px';
$BL['be_cnt_set5']                      = 'Borrar ancho y altura de la medio';

// added: 28-12-2003
$BL['be_admin_page_add']                = 'Crear nueva composici&oacute;n de p&aacute;gina';
$BL['be_admin_page_name']               = 'Nombre de la composici&oacute;n';
$BL['be_admin_page_edit']               = 'Editar la composici&oacute;n de p&aacute;gina';
$BL['be_admin_page_render']             = 'Rendering';
$BL['be_admin_page_table']              = 'Tabla';
$BL['be_admin_page_div']                = 'css div';
$BL['be_admin_page_custom']             = 'custom';
$BL['be_admin_page_custominfo']         = 'Seg&uacute;n bloque principal de la plantilla';
$BL['be_admin_tmpl_layout']             = 'Composici&oacute;n';
$BL['be_admin_tmpl_nolayout']           = '&iexcl;No hay composici&oacute;n de p&aacute;gina disponible!';

// added: 31-12-2003
$BL['be_ctype_search']                  = 'Buscar';
$BL['be_cnt_results']                   = 'Resultados';
$BL['be_cnt_results_per_page']          = 'Por&nbsp;p&aacute;gina (si está vac&iacute;o mostrar todo)';
$BL['be_cnt_opennewwin']                = 'Abrir nueva ventana';
$BL['be_cnt_searchlabeltext']           = 'Estos son textos y valores predefinidos para el formulario de b&uacute;squeda y para los resultados.';
$BL['be_cnt_input']                     = 'Input';
$BL['be_cnt_style']                     = 'Estilo';
$BL['be_cnt_result']                    = 'Resultado';
$BL['be_cnt_next']                      = 'Siguiente';
$BL['be_cnt_previous']                  = 'Anterior';
$BL['be_cnt_align']                     = 'Alinear';
$BL['be_cnt_searchformtext']            = 'Los siguientes textos se muestran cuando se abre la b&uacute;squeda o los resultados para una determinada b&uacute;squeda no est&aacute;n disponibles.';
$BL['be_cnt_intro']                     = 'Intro';
$BL['be_cnt_noresult']                  = 'Sin resultados';

// added: 02-01-2004
$BL['be_admin_page_disable']            = 'Deshabilitado';

// added: 09-01-2004
$BL['be_article_articleowner']          = 'Propietario del art&iacute;culo';
$BL['be_article_adminuser']             = 'Usuario de administraci&oacute;n';
$BL['be_article_username']              = 'Autor';

// added: 10-01-2004
$BL['be_ctype_wysiwyg']                 = 'WYSIWYG HTML';

// added, changed: 11-01-2004
$BL['be_admin_struct_regonly']          = 'Visible s&oacute;lo para usuarios registrados';
$BL['be_admin_struct_status']           = 'Estado del menú frontend';

// added: 15-02-2004
$BL['be_ctype_articlemenu']             = 'article menu';
$BL['be_cnt_sitelevel']                 = 'site level';
$BL['be_cnt_sitecurrent']               = 'current site level';

