/*!
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 */

function toggle_visibility(classstr) {
    const e = document.getElementByClass(classstr);
    e.style.display = e.style.display === 'block' ? 'none' : 'block';
}

var imageBrowser, uploadWin, temp_url;
function login(fval) {
    if (fval.json.value == '2') {
        fval.customlang.value = 1;
        fval.submit();
        return true;
    }
    fval.json.value = 0;
    if (fval.form_password.value && fval.form_loginname.value) {
        fval.json.value = 1;
        return true;
    } else {
        showLayer('jserr');
        return false;
    }
}
function MM_findObj(n, d) {
    const el = $('#' + n, d || document);
    return el.length ? el[0] : null;
}

function MM_swapImage() {
    const args = arguments;
    document.MM_sr = [];
    for (let i = 0; i < (args.length - 2); i += 3) {
        const el = $('#' + args[i]);
        if (el.length) {
            document.MM_sr.push(el[0]);
            if (!el.data('oSrc')) {
                el.data('oSrc', el.attr('src'));
            }
            el.attr('src', args[i + 2]);
        }
    }
}

function bsConfirm(confirmType, message, callback, customConfirmText, customCancelText) {
    if (window.parent && window.parent !== window && typeof window.parent.bsConfirm === 'function') {
        window.parent.bsConfirm(confirmType, message, callback, customConfirmText, customCancelText);
        return;
    }

    const modalId = 'bootstrapConfirmModal';
    let $modal = $('#' + modalId);
    if ($modal.length === 0) {
        var modalHtml =
            '<div class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 2000;">' +
            '  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">' +
            '    <div class="modal-content border-0 bg-transparent">' +
            '      <div class="alert shadow-lg mb-0 p-4 alert-container" role="alert" style="border-radius: 8px;">' +
            '        <div class="d-flex align-items-start">' +
            '          <div class="mr-3 icon-container" style="font-size: 2rem; line-height: 1;">' +
            '            <i></i>' +
            '          </div>' +
            '          <div style="flex: 1; min-width: 0;">' +
            '            <p class="confirm-message mb-3 text-dark font-weight-bold" style="font-size: 1.1rem;"></p>' +
            '            <div class="d-flex justify-content-end">' +
            '              <button type="button" class="btn btn-secondary mr-2 cancel-btn" data-dismiss="modal"></button>' +
            '              <button type="button" class="btn confirm-btn font-weight-bold"></button>' +
            '            </div>' +
            '          </div>' +
            '        </div>' +
            '      </div>' +
            '    </div>' +
            '  </div>' +
            '</div>';
        $('body').append(modalHtml);
        $modal = $('#' + modalId);
        $modal.on('hide.bs.modal', function () {
            if ($modal.has(document.activeElement).length) {
                document.activeElement.blur();
            }
        });
    }

    const cancelText = customCancelText || (window.PHPWCMS_LANG && window.PHPWCMS_LANG.cancel) || 'Cancel';
    $modal.find('.cancel-btn').text(cancelText);

    const confirmText = customConfirmText || (window.PHPWCMS_LANG && window.PHPWCMS_LANG.ok) || 'OK';
    const type = (confirmType || 'info').toLowerCase().trim();

    let btnClass = 'btn-info text-white';
    let textClass = 'text-info';
    let iconClass = 'fas fa-info-circle';

    if (type === 'danger' || type === 'delete') {
        btnClass = 'btn-danger text-white';
        textClass = 'text-danger';
        iconClass = 'fas fa-trash-alt';
    } else if (type === 'primary' || type === 'move') {
        btnClass = 'btn-primary text-white';
        textClass = 'text-primary';
        iconClass = 'fas fa-arrows-alt';
    } else if (type === 'warning' || type === 'flush') {
        btnClass = 'btn-warning text-dark';
        textClass = 'text-warning';
        iconClass = 'fas fa-exclamation-triangle';
    } else if (type === 'success') {
        btnClass = 'btn-success text-white';
        textClass = 'text-success';
        iconClass = 'fas fa-check-circle';
    }

    $modal.find('.alert-container')
        .removeClass('alert-warning alert-danger alert-primary alert-info alert-success')
        .addClass('alert-light')
        .css('border', '1px solid #dee2e6');

    $modal.find('.icon-container')
        .removeClass('text-warning text-danger text-primary text-info text-success')
        .addClass(textClass);

    $modal.find('.icon-container i')
        .removeClass()
        .addClass(iconClass);

    $modal.find('.confirm-btn')
        .removeClass('btn-danger btn-primary btn-info btn-warning btn-success text-white text-dark')
        .addClass(btnClass)
        .text(confirmText);

    const formattedMsg = (message || '').replace(/\\n/g, '<br>').replace(/\r?\n/g, '<br>');
    $modal.find('.confirm-message').html(formattedMsg);

    $modal.find('.confirm-btn').off('click').on('click', function() {
        $modal.modal('hide');
        if (typeof callback === 'function') {
            callback();
        }
    });

    $modal.modal('show');
}



function bsConfirmWarning(message, callback, customConfirmText, customCancelText) {
    bsConfirm('warning', message, callback, customConfirmText, customCancelText);
}

function bsConfirmDanger(message, callback, customConfirmText, customCancelText) {
    bsConfirm('danger', message, callback, customConfirmText, customCancelText);
}

function bsConfirmInfo(message, callback, customConfirmText, customCancelText) {
    bsConfirm('info', message, callback, customConfirmText, customCancelText);
}

function bsConfirmSuccess(message, callback, customConfirmText, customCancelText) {
    bsConfirm('success', message, callback, customConfirmText, customCancelText);
}

$(document).on('click', '[data-confirm], [data-confirm-danger], [data-confirm-warning]', function (e) {
    const $el = $(this);
    const href = $el.attr('href');
    const msg = $el.attr('data-confirm') || $el.attr('data-confirm-danger') || $el.attr('data-confirm-warning');
    const type = $el.attr('data-confirm-type') || ($el.attr('data-confirm-danger') ? 'danger' : ($el.attr('data-confirm-warning') ? 'warning' : 'danger'));
    const btnText = $el.attr('data-confirm-btn');

    if (!msg) {
        return true;
    }

    e.preventDefault();
    bsConfirm(type, msg, function () {
        if (href && href !== '#' && href !== 'javascript:void(0);') {
            window.location.href = href;
        } else if ($el.is(':submit') || $el.is('button[type="submit"]')) {
            $el.closest('form').submit();
        }
    }, btnText);
    return false;
});


function bsConfirmDelete(message, callback, customConfirmText, customCancelText) {
    bsConfirm('delete', message, callback, customConfirmText, customCancelText);
}

function initTomSelectTagAutosuggest(inputSelector, hiddenSelector, actionType, options) {
    const $input = $(inputSelector);
    const $hidden = $(hiddenSelector);
    if (!$input.length || typeof TomSelect !== 'function') {
        return null;
    }

    const initialValues = ($hidden.val() || '').split(',').map(s => s.trim()).filter(Boolean);
    const initialOptions = initialValues.map(v => ({ value: v, text: v }));

    const config = Object.assign({
        plugins: ['remove_button'],
        valueField: 'text',
        labelField: 'text',
        searchField: 'text',
        create: true,
        createFilter: function(input) {
            return input.trim().length > 0;
        },
        options: initialOptions,
        items: initialValues,
        load: function(query, callback) {
            if (!query.length) return callback();
            const baseUrl = (window.PHPWCMS_URL || '') + 'include/inc_act/ajax_connector.php';
            $.ajax({
                url: baseUrl,
                type: 'GET',
                dataType: 'json',
                data: {
                    action: actionType || 'category',
                    method: 'json',
                    value: query
                },
                error: function() { callback(); },
                success: function(res) {
                    const results = (res || []).map(item => {
                        const val = typeof item === 'object' ? (item.cat_name || item.text || item.allowed_lang) : item;
                        return { value: val, text: val };
                    });
                    callback(results);
                }
            });
        },
        onChange: function(values) {
            const valStr = Array.isArray(values) ? values.join(', ') : (values || '');
            $hidden.val(valStr);
        }
    }, options || {});

    const ts = new TomSelect($input[0], config);
    $input.closest('form').on('submit', function() {
        const valStr = ts.getValue().join(', ');
        $hidden.val(valStr);
    });

    return ts;
}


function bsConfirmMove(message, callback, customConfirmText, customCancelText) {
    bsConfirm('move', message, callback, customConfirmText, customCancelText);
}

function bsConfirmFlush(message, callback, customConfirmText, customCancelText) {
    bsConfirm('flush', message, callback, customConfirmText, customCancelText);
}

function bsAlert(message, callback) {
    if (window.parent && window.parent !== window && typeof window.parent.bsAlert === 'function') {
        window.parent.bsAlert(message, callback);
        return;
    }

    const modalId = 'bootstrapAlertModal';
    let $modal = $('#' + modalId);
    if ($modal.length === 0) {
        var modalHtml =
            '<div class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 2000;">' +
            '  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">' +
            '    <div class="modal-content border-0 bg-transparent">' +
            '      <div class="alert alert-light shadow-lg mb-0 p-4" role="alert" style="border-radius: 8px; border: 1px solid #dee2e6;">' +
            '        <div class="d-flex align-items-start">' +
            '          <div class="mr-3 text-primary" style="font-size: 2rem; line-height: 1;">' +
            '            <i class="fas fa-info-circle"></i>' +
            '          </div>' +
            '          <div style="flex: 1; min-width: 0;">' +
            '            <p class="confirm-message mb-3 text-dark font-weight-bold" style="font-size: 1.1rem;"></p>' +
            '            <div class="d-flex justify-content-end">' +
            '              <button type="button" class="btn btn-primary confirm-btn text-white font-weight-bold" data-dismiss="modal">OK</button>' +
            '            </div>' +
            '          </div>' +
            '        </div>' +
            '      </div>' +
            '    </div>' +
            '  </div>' +
            '</div>';
        $('body').append(modalHtml);
        $modal = $('#' + modalId);
        $modal.on('hide.bs.modal', function () {
            if ($modal.has(document.activeElement).length) {
                document.activeElement.blur();
            }
        });
    }

    const formattedMsg = (message || '').replace(/\\n/g, '<br>').replace(/\r?\n/g, '<br>');
    $modal.find('.confirm-message').html(formattedMsg);

    $modal.find('.confirm-btn').off('click').on('click', function() {
        $modal.modal('hide');
        if (typeof callback === 'function') {
            callback();
        }
    });

    $modal.modal('show');
}



// Global alert override
window.alert = function(msg) {
    bsAlert(msg);
};

function confirmGoUrl(confirmtext, jumpurl) {
    bsConfirm('info', confirmtext, function() {
        location.href = jumpurl;
    });
}

function wordcount(s) {
    var formcontent = Trim(s);
    if (formcontent === '') {
        return 0;
    } else {
        formcontent = formcontent.split(" ");
        return formcontent.length;
    }
}

function LTrim(str) {
    const whitespace = String(' \t\n\r');
    let s = String(str);
    if (whitespace.indexOf(s.charAt(0)) !== -1) {
        let j = 0,
            i = s.length;
        while (j < i && whitespace.indexOf(s.charAt(j)) !== -1) {
            j++;
            s = s.substring(j, i);
        }
    }
    return s;
}

function RTrim(str) {
    const whitespace = String(' \t\n\r');
    let s = String(str);
    if (whitespace.indexOf(s.charAt(s.length - 1)) !== -1) {
        let i = s.length - 1;
        while (i >= 0 && whitespace.indexOf(s.charAt(i)) !== -1) {
            i--;
            s = s.substring(0, i + 1);
        }
    }
    return s;
}

function Trim(str) {
    return RTrim(LTrim(str));
}

function set_chatlist(lines) {
    document.sendchatmessage.chatlist.value = lines;
    document.sendchatmessage.submit();
}

function flevPopupLink() { // v1.2
    const v1 = arguments,
        v2 = window.open(v1[0], v1[1], v1[2]),
        v3 = (v1.length > 3) ? v1[3] : false;
    if (v3) {
        v2.focus();
    }
    document.MM_returnValue = false;
}

function MM_showHideLayers() {
    const args = arguments;
    for (let i = 0; i < (args.length - 2); i += 3) {
        const obj = MM_findObj(args[i]);
        if (obj != null) {
            let v = args[i + 2];
            if (obj.style) {
                v = (v === 'show') ? 'visible' : (v === 'hide') ? 'hidden' : v;
                obj.style.visibility = v;
            }
        }
    }
}

function tmt_winOpen(u, id, f, df) {
    const win = window[id];
    if (win == null || win.closed) {
        window[id] = window.open(u, id, f);
        if (window[id]) window[id].focus();
    } else if (df) {
        win.focus();
    } else {
        window[id] = window.open(u, id, f);
        if (window[id]) window[id].focus();
    }
}

function tmt_winControl(id, c) {
    const win = window[id];
    if (win && !win.closed) {
        if (c === 'focus()') {
            win.focus();
        } else if (c === 'close()') {
            win.close();
        }
    }
}

function get_cookie(Name) {
    var search = Name + "=";
    if (document.cookie.length > 0) {
        let offset = document.cookie.indexOf(search);
        // if cookie exists
        if (offset !== -1) {
            offset += search.length; // set index of beginning of value
            let end = document.cookie.indexOf(';', offset); // set index of end of cookie value
            if (end === -1) {
                end = document.cookie.length;
            }
            return decodeURIComponent(document.cookie.substring(offset, end));
        }
    }
    return '';
}

function write_cookie(wert) {
    window.document.cookie = "chatstring=" + (wert ? window.document.sendchatmessage.chatmsg.value : '');
}

function cut(objekt, len) {
    if (objekt.value.length > len) {
        objekt.value = objekt.value.substr(0, len);
    }
}

function changeImagePos(x, f) {
    if (f) {
        document.article.cimage_pos.selectedIndex = x;
    } else {
        document.articlecontent.cimage_pos.selectedIndex = x;
    }
    for (let i = 0; i <= 9; i++) {
        MM_swapImage('imgpos' + i, '', i === x ? 'img/symbole/content_selected.gif' : 'img/leer.gif', 0);
    }
}

function changeImagePosMenu(f) {
    const x = f ? document.article.cimage_pos.selectedIndex : document.articlecontent.cimage_pos.selectedIndex;
    for (let i = 0; i <= 9; i++) {
        MM_swapImage('imgpos' + i, '', i === x ? 'img/symbole/content_selected.gif' : 'img/leer.gif', 0);
    }
    if (f) {
        document.article.cimage_pos.focus();
    } else {
        document.articlecontent.cimage_pos.focus();
    }
}

function switchToggleFTP(field) {
    return parseInt(field.value, 10) ? 0 : 1;
}

function toggleAllFTP(field, proof) {
    for (let i = 0; i < field.length; i++) {
        field[i].checked = !!proof;
    }
}

function int_only(value) {
    value = parseInt(value, 10);
    if (value < 0) {
        value = value * -1;
    }
    return value.toString(10);
}

function hideLayer(whichLayer) {
    toggleDisplayById(whichLayer, 'none');
}

function showLayer(whichLayer) {
    toggleDisplayById(whichLayer, '');
}

function toggleDisplayById(whichLayer, status) {
    const el = document.getElementById(whichLayer);
    if (el) {
        el.style.display = status;
    }
}

function doMapChange() {
    document.articlecontent.cmap_location_edited.value = '1';
}

function subrstr(str, nbr) {
    return str.substr(str.length - nbr);
}

// for placing text at position
function setCursorPos(textObj) {
    if (textObj.createTextRange) {
        textObj.cursorPos = document.selection.createRange().duplicate();
    }
}

function insertAtCursorPos(textObj, textFieldValue) {
    textObj.focus();
    if (typeof textObj.selectionStart === 'number' && typeof textObj.selectionEnd === 'number') {
        const rangeStart = textObj.selectionStart;
        const rangeEnd = textObj.selectionEnd;
        const tempStr1 = textObj.value.substring(0, rangeStart);
        const tempStr2 = textObj.value.substring(rangeEnd);
        textObj.value = tempStr1 + textFieldValue + tempStr2;
        textObj.selectionStart = textObj.selectionEnd = rangeStart + textFieldValue.length;
    } else {
        textObj.value += textFieldValue;
    }
}

function getFieldById(fld) {
    return document.getElementById(fld) || true;
}

function switchDisabled(fld) {
    fld.disabled = !fld.disabled;
    return false;
}

function enableStatusMessage(fld, showHide, text) {
    const obj = document.getElementById(fld);
    if (!obj) {
        return true;
    }
    if (text) {
        obj.innerHTML = text;
    }
    obj.style.display = showHide ? 'block' : 'none';
    return true;
}

function create_alias(str, encoding, ucfirst) {
    str = str.toLowerCase();
    str = str.replace(/\[br\]/g, ' ');
    str = str.replace(/__/g, ' ');
    str = str.replace(/\s+/g, '-');
    str = str.replace(/[\?\+#=]/g, '-');
    str = str.replace(/-+\/+-+/g, '/');
    if (aliasUtf8) {
        if (aliasAllowSlashes) {
            str = str.replace(/[^a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF0-9_\-\/\.]+/g, '');
        } else {
            str = str.replace('/', '-');
            str = str.replace(/[^a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF0-9_\-\.]+/g, '');
        }
    } else {
        str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E5]/g, 'a');
        str = str.replace(/[\u00E7]/g, 'c');
        str = str.replace(/[\u00E8\u00E9\u00EA\u00EB]/g, 'e');
        str = str.replace(/[\u00EC\u00ED\u00EE\u00EF]/g, 'i');
        str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F8]/g, 'o');
        str = str.replace(/[\u00F9\u00FA\u00FB]/g, 'u');
        str = str.replace(/[\u00FD\u00FF]/g, 'y');
        str = str.replace(/[\u00F1]/g, 'n');
        str = str.replace(/[\u0153\u00F6]/g, 'oe');
        str = str.replace(/[\u00E6\u00E4]/g, 'ae');
        str = str.replace(/[\u00DF]/g, 'ss');
        str = str.replace(/[\u00FC]/g, 'ue');
        if (aliasAllowSlashes) {
            str = str.replace(/[^a-z0-9_\-\/\.]+/g, '');
        } else {
            str = str.replace('/', '-');
            str = str.replace(/[^a-z0-9_\-\.]+/g, '');
        }
    }
    str = str.replace(/\-+/g, '-');
    str = str.replace(/\/+/g, '/');
    str = str.replace(/_+/g, '_');
    str = str.replace(/^-+|-+$/g, '');
    str = str.replace(/^\/+|\/+$/g, '');
    str = str.replace(/^-+|-+$/g, '');
    if (ucfirst == 1) {
        const c = str.charAt(0);
        str = c.toUpperCase() + str.slice(1);
    }
    return str;
}

let fbw = 450,
    fbh = 575;
if (screen.width !== undefined) {
    fbw = Math.ceil(Math.max(screen.width / 5, fbw));
}
if (screen.height !== undefined) {
    fbh = Math.ceil(Math.max(screen.height / 2, fbh));
}

function openFileBrowser(url) {
    if (url != null && url !== '') {
        if (window.imageBrowser && temp_url !== url) {
            tmt_winControl('imageBrowser', 'close()');
        }
        tmt_winOpen(url, 'imageBrowser', 'width=' + fbw + ',height=' + fbh + ',left=8,top=8,scrollbars=yes,resizable=yes', 1);
        temp_url = url;
    }
}

function set_article_alias(onempty_only, alias_type, category) {
    let alias_basis = 'article_title',
        alias_target = 'article_alias';
    if (alias_type === 'struct') {
        alias_basis = 'acat_name';
        alias_target = 'acat_alias';
    }
    const aalias = document.getElementById(alias_target);
    if (onempty_only && aalias.value !== '') {
        return false;
    }
    const atitle = document.getElementById(alias_basis);
    aalias.value = create_alias((category ? category + '/' : '') + atitle.value);
    return false;
}

function flush_image_cache(link, url, confirm_msg, success_msg) {
    const proceed = function() {
        link.classList.add('ajax-running');
        $.ajax({
            url: url,
            dataType: 'json',
            xhrFields: {
                withCredentials: true,
            },
            success: function(response) {
                link.classList.remove('ajax-running');
                if (response && response.status === 'ok') {
                    var msg = success_msg ? success_msg.replace('%d', response.file_count || 0) : 'Success';
                    alert(msg);
                } else {
                    alert('Error flushing image cache');
                }
            },
            error: function() {
                link.classList.remove('ajax-running');
                alert('Error connecting to server');
            },
        });
    };

    if (confirm_msg) {
        const $link = $(link);
        const customConfirmText = $link.attr('data-confirm-action');
        const confirmType = $link.attr('data-confirm-type');
        bsConfirm(confirmType, confirm_msg, proceed, customConfirmText);
    } else {
        proceed();
    }
    return false;
}


const validation = {
    isEmailAddress: function(str) {
        const pattern = /^[\w+]+(?:[.-][\w+]+)*@\w+(?:[.-]\w+)*(?:\.\w{2,3})+$/;
        return pattern.test(str);  // returns a boolean
    },
    isNotEmpty: function(str) {
        const pattern = /\S+/;
        return pattern.test(str);  // returns a boolean
    },
    isNumber: function(str) {
        const pattern = /^\d+$/;
        return pattern.test(str);  // returns a boolean
    },
    isSame: function(str1, str2) {
        return str1 === str2;
    },
    isInt: function(str) {
        const pattern = /^(\-?|\+?)\d+$/;
        return pattern.test(str);  // returns a boolean
    },
};

function togglePasswordVisibility(id) {
    const pwdField = document.getElementById(id);
    pwdField.type = pwdField.type === "password" ? "text" : "password";
    return pwdField.type === 'text' ? 'hide' : 'show';
}

function copyToClipboard(str) {
    const el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    const selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false;
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    if (selected) {
        document.getSelection().removeAllRanges();
        document.getSelection().addRange(selected);
    }
}
