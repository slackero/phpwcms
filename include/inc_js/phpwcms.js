/*!
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 */

'use strict';

let temp_url = null;
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

function swapImage(...args) {
    document.MM_sr = document.MM_sr || [];
    for (let i = 0; i < (args.length - 2); i += 3) {
        const el = document.getElementById(args[i]);
        if (el) {
            document.MM_sr.push(el);
            if (!el.dataset.oSrc) {
                el.dataset.oSrc = el.src;
            }
            el.src = args[i + 2];
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
        const modalHtml =
            '<div class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 2000;">' +
            '  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">' +
            '    <div class="modal-content border-0 bg-transparent">' +
            '      <div class="alert shadow-lg mb-0 p-4 alert-container" role="alert" style="border-radius: 8px;">' +
            '        <div class="d-flex align-items-start">' +
            '          <div class="me-3 icon-container" style="font-size: 2rem; line-height: 1;">' +
            '            <i></i>' +
            '          </div>' +
            '          <div style="flex: 1; min-width: 0;">' +
            '            <p class="confirm-message mb-3 text-dark fw-bold" style="font-size: 1.1rem;"></p>' +
            '            <div class="d-flex justify-content-end">' +
            '              <button type="button" class="btn btn-secondary me-2 cancel-btn" data-bs-dismiss="modal"></button>' +
            '              <button type="button" class="btn confirm-btn fw-bold"></button>' +
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

    const bsModalInstance = (typeof bootstrap !== 'undefined' && bootstrap.Modal)
        ? bootstrap.Modal.getOrCreateInstance($modal[0])
        : null;

    $modal.find('.confirm-btn').off('click').on('click', function() {
        if (bsModalInstance) {
            bsModalInstance.hide();
        } else {
            $modal.modal('hide');
        }
        if (typeof callback === 'function') {
            callback();
        }
    });

    if (bsModalInstance) {
        bsModalInstance.show();
    } else {
        $modal.modal('show');
    }
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
    if ($input[0].tomselect) {
        return $input[0].tomselect;
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
        const val = ts.getValue();
        const valStr = Array.isArray(val) ? val.join(', ') : (val || '');
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
        const modalHtml =
            '<div class="modal fade" id="' + modalId + '" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 2000;">' +
            '  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">' +
            '    <div class="modal-content border-0 bg-transparent">' +
            '      <div class="alert alert-light shadow-lg mb-0 p-4" role="alert" style="border-radius: 8px; border: 1px solid #dee2e6;">' +
            '        <div class="d-flex align-items-start">' +
            '          <div class="me-3 text-primary" style="font-size: 2rem; line-height: 1;">' +
            '            <i class="fas fa-info-circle"></i>' +
            '          </div>' +
            '          <div style="flex: 1; min-width: 0;">' +
            '            <p class="confirm-message mb-3 text-dark fw-bold" style="font-size: 1.1rem;"></p>' +
            '            <div class="d-flex justify-content-end">' +
            '              <button type="button" class="btn btn-primary confirm-btn text-white fw-bold" data-bs-dismiss="modal">OK</button>' +
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

    const bsModalInstance = (typeof bootstrap !== 'undefined' && bootstrap.Modal)
        ? bootstrap.Modal.getOrCreateInstance($modal[0])
        : null;

    $modal.find('.confirm-btn').off('click').on('click', function() {
        if (bsModalInstance) {
            bsModalInstance.hide();
        } else {
            $modal.modal('hide');
        }
        if (typeof callback === 'function') {
            callback();
        }
    });

    if (bsModalInstance) {
        bsModalInstance.show();
    } else {
        $modal.modal('show');
    }
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
    var formcontent = String(s || '').trim();
    if (formcontent === '') {
        return 0;
    } else {
        formcontent = formcontent.split(/\s+/);
        return formcontent.length;
    }
}

function flevPopupLink(url, name, features, focus) {
    const win = window.open(url, name, features);
    if (focus && win) {
        win.focus();
    }
    return false;
}

function popupOpen(url, id, features, doFocus) {
    const win = window[id];
    if (!win || win.closed) {
        window[id] = window.open(url, id, features);
        if (window[id] && doFocus) window[id].focus();
    } else if (doFocus) {
        win.focus();
    } else {
        window[id] = window.open(url, id, features);
        if (window[id]) window[id].focus();
    }
}

function popupControl(id, cmd) {
    if (id === 'self') {
        window.close();
        return;
    }
    const win = window[id];
    if (win && !win.closed) {
        if (cmd === 'focus()') {
            win.focus();
        } else if (cmd === 'close()') {
            win.close();
        }
    }
}

function tmt_winOpen(u, id, f, df) {
    popupOpen(u, id, f, df);
}

function tmt_winControl(id, c) {
    popupControl(id, c);
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

// for placing text at position
function setCursorPos(textObj) {
    if (textObj.createTextRange) {
        textObj.cursorPos = document.selection.createRange().duplicate();
    }
}

function insertAtCursorPos(textObj, textFieldValue) {
    if (!textObj) return;
    if (typeof textFieldValue !== 'string' || textFieldValue === '') return;
    
    // Ace Editor support
    if (textObj._aceEditor) {
        textObj._aceEditor.insert(textFieldValue);
        textObj._aceEditor.focus();
        return;
    }

    const fieldId = textObj.id || (textObj.name || '');

    // CKEditor support
    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances && (CKEDITOR.instances[fieldId] || CKEDITOR.instances[textObj.name])) {
        const inst = CKEDITOR.instances[fieldId] || CKEDITOR.instances[textObj.name];
        inst.insertHtml(textFieldValue);
        inst.focus();
        return;
    }

    // TinyMCE support
    if (typeof tinymce !== 'undefined' && (tinymce.get(fieldId) || tinymce.get(textObj.name))) {
        const inst = tinymce.get(fieldId) || tinymce.get(textObj.name);
        inst.insertContent(textFieldValue);
        inst.focus();
        return;
    }

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
    return document.getElementById(fld) || null;
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

function create_alias(str, encoding, ucfirst, allowSlashes) {
    if (typeof allowSlashes === 'undefined' || allowSlashes === null) {
        allowSlashes = aliasAllowSlashes;
    } else {
        allowSlashes = Boolean(allowSlashes);
    }
    if (typeof str !== 'string') {
        str = String(str || '');
    }
    try {
        if (str.indexOf('%') !== -1) {
            str = decodeURIComponent(str);
        }
    } catch (e) {}
    str = str.toLowerCase();
    str = str.replace(/\[br\]/g, ' ');
    str = str.replace(/__/g, ' ');
    str = str.replace(/\s+/g, '-');
    str = str.replace(/[\?\+#=]/g, '-');
    if (!allowSlashes) {
        str = str.replace(/\//g, '-');
    }
    str = str.replace(/-+\/+-+/g, '/');
    if (aliasUtf8) {
        if (allowSlashes) {
            str = str.replace(/[^a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF0-9_\-\/\.]+/g, '');
        } else {
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
        if (allowSlashes) {
            str = str.replace(/[^a-z0-9_\-\/\.]+/g, '');
        } else {
            str = str.replace(/[^a-z0-9_\-\.]+/g, '');
        }
    }
    if (allowSlashes) {
        str = str.replace(/\/+/g, '/');
        str = str.replace(/[\-_]*\/+[\-_]*/g, '/');
    }
    str = str.replace(/\-+/g, '-');
    str = str.replace(/_+/g, '_');
    if (allowSlashes) {
        str = str.replace(/\/+/g, '/');
        str = str.replace(/^[\-_\/\.]+|[\-_\/\.]+$/g, '');
    } else {
        str = str.replace(/^[\-_\.]+|[\-_\.]+$/g, '');
    }
    if (ucfirst === 1 || ucfirst === true) {
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

function set_file_alias(onempty_only, target_id, source_id) {
    const alias_target = target_id ? target_id : 'file_alias';
    const alias_basis = source_id ? source_id : 'file_name';
    const falias = document.getElementById(alias_target);
    if (!falias || (onempty_only && falias.value !== '')) {
        return false;
    }
    const fname = document.getElementById(alias_basis);
    if (fname) {
        let name = fname.value;
        const dotIndex = name.lastIndexOf('.');
        if (dotIndex > 0) {
            name = name.substring(0, dotIndex);
        }
        falias.value = create_alias(name, null, null, false);
    }
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
                    const msg = success_msg ? success_msg.replace('%d', response.file_count || 0) : 'Success';
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


function togglePasswordVisibility(id) {
    const pwdField = document.getElementById(id);
    if (pwdField) {
        pwdField.type = pwdField.type === "password" ? "text" : "password";
        return pwdField.type === 'text' ? 'hide' : 'show';
    }
}

function copyToClipboard(str) {
    if (navigator.clipboard && window.isSecureContext) {
        return navigator.clipboard.writeText(str);
    } else {
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
}

function keyword_submit_action(obj, id, action) {
    const form = obj.form || document.getElementById('keywordListing');
    if (form) {
        form.keyword_selected_id.value = id;
        form.keyword_action.value = action;
        form.submit();
    }
}

function toggleKeywordCheckboxes(master) {
    const checkboxes = document.querySelectorAll('.keyword-checkbox');
    checkboxes.forEach(function (cb) {
        cb.checked = master.checked;
    });
}

// phpwcms Addons & UI Handlers
$(function () {
    const $doc = $(document);
    const $win = $(window);
    const $body = $('body');
    const $pageWrapper = $('#page-wrapper');
    const $modalBody = $('.modal .modal-body');
    const $iframe = $('#browserModal iframe');
    const $modalHeader = $('#browserModal h2');

    $('#button-menu').on('click', function (e) {
        e.preventDefault();
        $('#column-left').toggleClass('active');
    });

    $doc.on('click', '.confirm-link', function (e) {
        e.preventDefault();
        const $this = $(this);
        const message = $this.attr('data-confirm') || 'Are you sure?';
        const action = $this.attr('data-confirm-action');
        const confirmType = $this.attr('data-confirm-type') || 'info';
        const href = $this.attr('href');
        bsConfirm(confirmType, message, function () {
            window.location.href = href;
        }, action);
    });

    // Intercept native confirm calls in inline onclick attributes dynamically
    $doc.on('click', 'a[onclick*="confirm("], button[onclick*="confirm("], input[type="submit"][onclick*="confirm("], input[type="button"][onclick*="confirm("]', function(e) {
        const $el = $(this);
        const onclickStr = $el.attr('onclick');
        if (!onclickStr) return;

        const match = onclickStr.match(/confirm\(\s*(['"])([\s\S]*?)\1\s*\)/);
        if (match) {
            e.preventDefault();
            e.stopImmediatePropagation();

            // Temporarily disable native window.confirm during any bubbling handler
            const origConfirm = window.confirm;
            window.confirm = function() { return false; };
            setTimeout(function() { window.confirm = origConfirm; }, 1);

            const confirmMsg = match[2];
            const action = $el.attr('data-confirm-action');
            const confirmType = $el.attr('data-confirm-type') || 'info';
            bsConfirm(confirmType, confirmMsg, function() {
                if ($el.is('a')) {
                    const href = $el.attr('href');
                    if (href && href !== '#') {
                        const target = $el.attr('target');
                        if (target && target !== '_self') {
                            window.open(href, target);
                        } else {
                            window.location.href = href;
                        }
                    }
                } else if ($el.is('input[type="submit"], button[type="submit"]')) {
                    const name = $el.attr('name');
                    const form = $el.closest('form');
                    if (form.length) {
                        if (name) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: name,
                                value: $el.val() || '1'
                            }).appendTo(form);
                        }
                        form.submit();
                    }
                } else {
                    $el.attr('onclick', onclickStr.replace(/return\s+confirm\(.*?\);?/, ''));
                    $el.click();
                    $el.attr('onclick', onclickStr);
                }
            }, action, confirmType);
        }
    });

    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        document.querySelectorAll('[data-bs-toggle="tooltip"], [data-toggle="tooltip"]').forEach(function (el) {
            bootstrap.Tooltip.getOrCreateInstance(el, {
                html: true,
                delay: {
                    show: 200,
                    hide: 50
                },
                container: 'body',
                sanitize: false
            });
        });
    } else if (typeof $.fn.tooltip === 'function') {
        $body.tooltip({
            selector: '[data-bs-toggle="tooltip"], [data-toggle="tooltip"]',
            html: true,
            delay: {
                show: 200,
                hide: 50
            },
            container: 'body',
            sanitize: false
        });
    }

    $doc.on('click', '.modalButton', function (e) {
        const $this = $(this);
        let src = $this.attr('data-src') || '';
        if (!src) return;

        if (typeof CSRF_GET_TOKEN !== 'undefined' && CSRF_GET_TOKEN && src.indexOf('csrftoken=') === -1) {
            src += (src.indexOf('?') === -1 ? '?' : '&') + CSRF_GET_TOKEN;
        }
        const modaltitle = $this.attr('alt') || $this.attr('title') || '';

        $iframe.attr({'src': src, 'height': '100%', 'width': '100%'});
        $modalHeader.html(modaltitle);
    });

    $('#browserModal').on('hidden.bs.modal', function () {
        $iframe.attr('src', 'about:blank');
    });

    //ajaxfunction
    $('[id^="abtn"]').on('click', function (e) {
        let $this = $(this);
        const type = $this.attr('data-type');
        const table = $this.attr('data-table');
        const field = $this.attr('data-field');
        const fieldid = $this.attr('data-fieldid');
        const id = $this.attr('data-id');
        const thisbtn = '#abtn' + type + $this.attr('data-id');
        const csrfToken = (typeof CSRF_GET_TOKEN !== 'undefined' && CSRF_GET_TOKEN) ? CSRF_GET_TOKEN : '';

        $.ajax({
            url: 'include/inc_act/ajax_changer.php' + (csrfToken ? '?' + csrfToken : ''),
            xhrFields: {
                withCredentials: true
            },
            data: {
                'table': table,
                'id': id,
                'field': field,
                'fieldid': fieldid,
            },
            success: function (data) {
                let $target = $(thisbtn);
                if (!$target.length) {
                    $target = $this;
                }
                if ($target.hasClass('btn-success')) {
                    $target.removeClass('btn-success').addClass('btn-warning');
                } else {
                    $target.removeClass('btn-warning btn-danger').addClass('btn-success');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' + thrownError);
            }
        });
    });

    $('[id^="imgpos"]').on('click', function () {
        const id = $(this).attr('id') || '';
        const x = parseInt(id.replace(/\D/g, ''), 10);
        $('#cimage_pos').val(isNaN(x) ? '' : x);
        for (let i = 0; i <= 9; i++) {
            if (i === x) {
                $('#imgpos' + i).removeClass('btn-blue').addClass('btn-success');
            } else {
                $('#imgpos' + i).removeClass('btn-success').addClass('btn-blue');
            }
        }
    });

    $('#cimage_pos').on('change', function () {
        const x = parseInt($(this).val(), 10);
        for (let i = 0; i <= 9; i++) {
            if (i === x) {
                $('#imgpos' + i).removeClass('btn-blue').addClass('btn-success');
            } else {
                $('#imgpos' + i).removeClass('btn-success').addClass('btn-blue');
            }
        }
    });

    $('#side-menu li').on('click', function () {
        $('#side-menu ul').css('display', 'none');
        $(this).children('ul').css('display', 'block');
        const topOffset = 95;
        let winHeight = (window.innerHeight > 0) ? window.innerHeight : screen.height;
        winHeight = winHeight - topOffset;
        const newheight = $('#side-menu').height() + (topOffset * 2);
        if (winHeight < newheight) {
            $('#page-wrapper').css('min-height', (newheight) + 'px');
        }
    });

});




// Select Box & OptionTransfer Helpers
function hasOptions(obj) {
    return obj != null && obj.options != null;
}

function selectUnselectMatchingOptions(obj, regex, which, only) {
    if (!window.RegExp || !hasOptions(obj)) return;
    const selected1 = which === 'select';
    const selected2 = !selected1;
    const re = new RegExp(regex);
    for (let i = 0; i < obj.options.length; i++) {
        if (re.test(obj.options[i].text)) {
            obj.options[i].selected = selected1;
        } else if (only) {
            obj.options[i].selected = selected2;
        }
    }
}

function selectMatchingOptions(obj, regex) {
    selectUnselectMatchingOptions(obj, regex, 'select', false);
}

function selectOnlyMatchingOptions(obj, regex) {
    selectUnselectMatchingOptions(obj, regex, 'select', true);
}

function unSelectMatchingOptions(obj, regex) {
    selectUnselectMatchingOptions(obj, regex, 'unselect', false);
}

function sortSelect(obj) {
    if (!hasOptions(obj)) return;
    const opts = Array.from(obj.options).map(o => new Option(o.text, o.value, o.defaultSelected, o.selected));
    if (!opts.length) return;
    opts.sort((a, b) => a.text.localeCompare(b.text));
    for (let i = 0; i < opts.length; i++) {
        obj.options[i] = opts[i];
    }
}

function selectAllOptions(obj) {
    if (!hasOptions(obj)) return;
    for (let i = 0; i < obj.options.length; i++) {
        obj.options[i].selected = true;
    }
}

function moveSelectedOptions(from, to, autoSort = true, regex = '') {
    if (regex) {
        unSelectMatchingOptions(from, regex);
    }
    if (!hasOptions(from)) return;
    for (let i = 0; i < from.options.length; i++) {
        const o = from.options[i];
        if (o.selected) {
            const index = hasOptions(to) ? to.options.length : 0;
            to.options[index] = new Option(o.text, o.value, false, false);
        }
    }
    for (let i = from.options.length - 1; i >= 0; i--) {
        if (from.options[i].selected) {
            from.options[i] = null;
        }
    }
    if (autoSort) {
        sortSelect(from);
        sortSelect(to);
    }
    from.selectedIndex = -1;
    to.selectedIndex = -1;
}

function copySelectedOptions(from, to, autoSort = true) {
    const existing = {};
    if (hasOptions(to)) {
        for (let i = 0; i < to.options.length; i++) {
            existing[to.options[i].value] = to.options[i].text;
        }
    }
    if (!hasOptions(from)) return;
    for (let i = 0; i < from.options.length; i++) {
        const o = from.options[i];
        if (o.selected && existing[o.value] === undefined) {
            const index = hasOptions(to) ? to.options.length : 0;
            to.options[index] = new Option(o.text, o.value, false, false);
        }
    }
    if (autoSort) {
        sortSelect(to);
    }
    from.selectedIndex = -1;
    to.selectedIndex = -1;
}

function moveAllOptions(from, to, autoSort = true, regex = '') {
    selectAllOptions(from);
    moveSelectedOptions(from, to, autoSort, regex);
}

function copyAllOptions(from, to, autoSort = true) {
    selectAllOptions(from);
    copySelectedOptions(from, to, autoSort);
}

function swapOptions(obj, i, j) {
    const o = obj.options;
    const iSel = o[i].selected;
    const jSel = o[j].selected;
    const tempI = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
    const tempJ = new Option(o[j].text, o[j].value, o[j].defaultSelected, o[j].selected);
    o[i] = tempJ;
    o[j] = tempI;
    o[i].selected = jSel;
    o[j].selected = iSel;
}

function moveOptionUp(obj) {
    if (!hasOptions(obj)) return;
    for (let i = 0; i < obj.options.length; i++) {
        if (obj.options[i].selected && i !== 0 && !obj.options[i - 1].selected) {
            swapOptions(obj, i, i - 1);
            obj.options[i - 1].selected = true;
        }
    }
}

function moveOptionDown(obj) {
    if (!hasOptions(obj)) return;
    for (let i = obj.options.length - 1; i >= 0; i--) {
        if (obj.options[i].selected && i !== obj.options.length - 1 && !obj.options[i + 1].selected) {
            swapOptions(obj, i, i + 1);
            obj.options[i + 1].selected = true;
        }
    }
}

function removeSelectedOptions(from) {
    if (!hasOptions(from)) return;
    for (let i = from.options.length - 1; i >= 0; i--) {
        if (from.options[i].selected) {
            from.options[i] = null;
        }
    }
    from.selectedIndex = -1;
}

function removeAllOptions(from) {
    if (!hasOptions(from)) return;
    for (let i = from.options.length - 1; i >= 0; i--) {
        from.options[i] = null;
    }
    from.selectedIndex = -1;
}

function addOption(obj, text, value, selected) {
    if (hasOptions(obj)) {
        obj.options[obj.options.length] = new Option(text, value, false, selected);
    }
}

class OptionTransfer {
    constructor(left, right) {
        this.form = null;
        this.left = left;
        this.right = right;
        this.autoSort = true;
        this.delimiter = ',';
        this.staticOptionRegex = '';
        this.originalLeftValues = {};
        this.originalRightValues = {};
        this.removedLeftField = null;
        this.removedRightField = null;
        this.addedLeftField = null;
        this.addedRightField = null;
        this.newLeftField = null;
        this.newRightField = null;
    }

    init(theForm) {
        this.form = theForm;
        if (!theForm[this.left] || !theForm[this.right]) {
            return false;
        }
        this.left = theForm[this.left];
        this.right = theForm[this.right];

        for (let i = 0; i < this.left.options.length; i++) {
            this.originalLeftValues[this.left.options[i].value] = 1;
        }
        for (let i = 0; i < this.right.options.length; i++) {
            this.originalRightValues[this.right.options[i].value] = 1;
        }

        if (this.removedLeftField) this.removedLeftField = theForm[this.removedLeftField];
        if (this.removedRightField) this.removedRightField = theForm[this.removedRightField];
        if (this.addedLeftField) this.addedLeftField = theForm[this.addedLeftField];
        if (this.addedRightField) this.addedRightField = theForm[this.addedRightField];
        if (this.newLeftField) this.newLeftField = theForm[this.newLeftField];
        if (this.newRightField) this.newRightField = theForm[this.newRightField];

        this.update();
    }

    transferLeft() {
        moveSelectedOptions(this.right, this.left, this.autoSort, this.staticOptionRegex);
        this.update();
    }

    transferRight() {
        moveSelectedOptions(this.left, this.right, this.autoSort, this.staticOptionRegex);
        this.update();
    }

    transferAllLeft() {
        moveAllOptions(this.right, this.left, this.autoSort, this.staticOptionRegex);
        this.update();
    }

    transferAllRight() {
        moveAllOptions(this.left, this.right, this.autoSort, this.staticOptionRegex);
        this.update();
    }

    saveRemovedLeftOptions(f) { this.removedLeftField = f; }
    saveRemovedRightOptions(f) { this.removedRightField = f; }
    saveAddedLeftOptions(f) { this.addedLeftField = f; }
    saveAddedRightOptions(f) { this.addedRightField = f; }
    saveNewLeftOptions(f) { this.newLeftField = f; }
    saveNewRightOptions(f) { this.newRightField = f; }

    setDelimiter(val) { this.delimiter = val; }
    setAutoSort(val) { this.autoSort = val; }
    setStaticOptionRegex(val) { this.staticOptionRegex = val; }

    update() {
        const removedLeft = {}, removedRight = {}, addedLeft = {}, addedRight = {}, newLeft = {}, newRight = {};
        for (let i = 0; i < this.left.options.length; i++) {
            const o = this.left.options[i];
            newLeft[o.value] = 1;
            if (this.originalLeftValues[o.value] === undefined) {
                addedLeft[o.value] = 1;
                removedRight[o.value] = 1;
            }
        }
        for (let i = 0; i < this.right.options.length; i++) {
            const o = this.right.options[i];
            newRight[o.value] = 1;
            if (this.originalRightValues[o.value] === undefined) {
                addedRight[o.value] = 1;
                removedLeft[o.value] = 1;
            }
        }
        if (this.removedLeftField) this.removedLeftField.value = Object.keys(removedLeft).join(this.delimiter);
        if (this.removedRightField) this.removedRightField.value = Object.keys(removedRight).join(this.delimiter);
        if (this.addedLeftField) this.addedLeftField.value = Object.keys(addedLeft).join(this.delimiter);
        if (this.addedRightField) this.addedRightField.value = Object.keys(addedRight).join(this.delimiter);
        if (this.newLeftField) this.newLeftField.value = Object.keys(newLeft).join(this.delimiter);
        if (this.newRightField) this.newRightField.value = Object.keys(newRight).join(this.delimiter);
    }
}

// Content Part Form Helpers
function showHide_TeaserArticleSelection(value) {
    if (!value) {
        toggleDisplayById('calink_auto_0', 'none');
        toggleDisplayById('calink_auto_1', 'none');
        toggleDisplayById('prio0', 'none');
        toggleDisplayById('prio1', 'none');
        toggleDisplayById('calink_manual_0', '');
        toggleDisplayById('calink_manual_1', '');
        toggleDisplayById('calink_manual_2', '');
    } else {
        toggleDisplayById('calink_manual_0', 'none');
        toggleDisplayById('calink_manual_1', 'none');
        toggleDisplayById('calink_manual_2', 'none');
        toggleDisplayById('calink_auto_0', '');
        toggleDisplayById('calink_auto_1', '');
        toggleDisplayById('prio0', '');
        toggleDisplayById('prio1', '');
    }
}

function showHide_CntFormfieldRow(whichLayer, status, rowplus) {
    let innerLink = `<a href="#" onclick="return showHide_CntFormfieldRow('${whichLayer}', `;

    if (status === 'block') {
        innerLink += "'none'";
        if (rowplus === 5) {
            innerLink += ', 5';
        }
        innerLink += ')"><i class="fas fa-caret-down fa-lg"></i></a>';
    } else {
        innerLink += "'block'";
        if (rowplus === 5) {
            innerLink += ', 5';
        }
        innerLink += ')"><i class="fas fa-caret-right fa-lg"></i></a>';
    }

    const rows = [1, 2, 3, 4];
    if (rowplus === 5) {
        rows.push(5);
    }

    rows.forEach(num => {
        const el = document.getElementById(`${whichLayer}_${num}`);
        if (el) {
            el.style.display = (status === 'block') ? 'table-row' : status;
        }
    });

    const triggerEl = document.getElementById(whichLayer);
    if (triggerEl) {
        triggerEl.innerHTML = innerLink;
    }

    return false;
}

function insertOptionBefore(objectId, optionValue, optionText) {
    const elSel = document.getElementById(objectId);
    if (elSel && elSel.selectedIndex >= 0) {
        const elOptNew = new Option(optionText, optionValue);
        const elOptOld = elSel.options[elSel.selectedIndex];
        elSel.add(elOptNew, elOptOld);
    }
}

function removeOptionSelected(objectId) {
    const elSel = document.getElementById(objectId);
    if (elSel) {
        for (let i = elSel.length - 1; i >= 0; i--) {
            if (elSel.options[i].selected) {
                elSel.remove(i);
            }
        }
    }
}

function appendOptionLast(objectId, optionValue, optionText) {
    const elSel = document.getElementById(objectId);
    if (elSel) {
        elSel.add(new Option(optionText, optionValue));
    }
}

function removeOptionLast(objectId) {
    const elSel = document.getElementById(objectId);
    if (elSel && elSel.length > 0) {
        elSel.remove(elSel.length - 1);
    }
}

// Theme Management (Light, Dark, Automatic)
let phpwcmsThemeInitialized = false;

function initPhpwcmsTheme() {
    function getStoredTheme() {
        return localStorage.getItem('phpwcms_theme') || document.documentElement.getAttribute('data-theme') || 'auto';
    }

    function updateThemeUI(theme) {
        // Update active class & checkmarks in dropdown
        document.querySelectorAll('[data-set-theme]').forEach(el => {
            const isMatch = el.getAttribute('data-set-theme') === theme;
            el.classList.toggle('active', isMatch);
            const check = el.querySelector('.theme-check');
            if (check) {
                check.classList.toggle('d-none', !isMatch);
            }
        });

        // Update active icon on theme switcher button
        const activeIcon = document.querySelector('.theme-switcher .theme-icon-active');
        if (activeIcon) {
            activeIcon.classList.remove('fa-adjust', 'fa-sun', 'fa-moon');
            if (theme === 'dark') {
                activeIcon.classList.add('fa-moon');
            } else if (theme === 'light') {
                activeIcon.classList.add('fa-sun');
            } else {
                activeIcon.classList.add('fa-adjust');
            }
        }

        // Also sync theme select in form if present
        const formThemeSelects = document.querySelectorAll('#form_theme, [data-theme-select]');
        formThemeSelects.forEach(select => {
            if (select.value !== theme) {
                select.value = theme;
            }
        });
    }

    function resolveTheme(theme) {
        if (theme === 'auto') {
            theme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        }
        return theme === 'dark' ? 'dark' : 'light';
    }

    function setTheme(theme, saveRemote = true) {
        if (!['light', 'dark', 'auto'].includes(theme)) {
            theme = 'auto';
        }
        document.documentElement.setAttribute('data-theme', theme);
        // bootstrap 5.3 native dark mode: resolved theme for auto
        document.documentElement.setAttribute('data-bs-theme', resolveTheme(theme));
        try {
            localStorage.setItem('phpwcms_theme', theme);
        } catch (e) {}
        document.cookie = 'phpwcmsBETheme=' + encodeURIComponent(theme) + '; path=/; max-age=31536000; SameSite=Lax';
        updateThemeUI(theme);
        updatePhpwcmsAceThemes();

        if (saveRemote && typeof $ !== 'undefined' && (!document.body || document.body.id !== 'login')) {
            $.post('include/inc_act/ajax_connector.php', {
                action: 'set_theme',
                value: theme
            }).catch(() => {});
        }
    }

    const currentTheme = getStoredTheme();
    setTheme(currentTheme, false);

    if (!phpwcmsThemeInitialized) {
        phpwcmsThemeInitialized = true;

        // keep auto theme in sync with OS color scheme changes
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (getStoredTheme() === 'auto') {
                    setTheme('auto', false);
                }
            });
        }

        document.addEventListener('click', e => {
            const themeBtn = e.target.closest('[data-set-theme]');
            if (themeBtn) {
                e.preventDefault();
                const chosenTheme = themeBtn.getAttribute('data-set-theme');
                setTheme(chosenTheme, true);
            }
        });

        document.addEventListener('change', e => {
            const themeSelect = e.target.closest('#form_theme, [data-theme-select]');
            if (themeSelect) {
                setTheme(themeSelect.value, true);
            }
        });
    }
}

// Ace Code Editor Integration for phpwcms
window.phpwcmsAceEditors = window.phpwcmsAceEditors || [];

function getPhpwcmsAceTheme() {
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    return isDark ? 'ace/theme/one_dark' : 'ace/theme/chrome';
}

function updatePhpwcmsAceThemes() {
    if (!window.phpwcmsAceEditors || !window.phpwcmsAceEditors.length) return;
    const theme = getPhpwcmsAceTheme();
    window.phpwcmsAceEditors.forEach(ed => {
        if (ed && typeof ed.setTheme === 'function') {
            ed.setTheme(theme);
        }
    });
}

function initAceForTextarea(textarea) {
    if (!window.ace || !textarea || textarea.dataset.aceInitialized) return;

    if (!ace.config.get('basePath')) {
        ace.config.set('basePath', 'include/inc_js/ace/');
    }

    let mode = (textarea.dataset.mode || 'html').toLowerCase();
    if (mode === 'js') mode = 'javascript';
    if (mode === 'plain') mode = 'text';

    const minLines = parseInt(textarea.dataset.minLines || textarea.rows || 8, 10);
    const maxLines = parseInt(textarea.dataset.maxLines || 45, 10);

    // Create wrapper & toolbar
    const wrap = document.createElement('div');
    wrap.className = 'phpwcms-ace-wrap';

    const toolbar = document.createElement('div');
    toolbar.className = 'phpwcms-ace-toolbar';

    const modeBadge = document.createElement('span');
    modeBadge.className = 'ace-mode-badge';
    modeBadge.textContent = mode.toUpperCase();

    const langWrap = (window.PHPWCMS_LANG && window.PHPWCMS_LANG.editorWordWrap) || 'Word wrap';
    const langFull = (window.PHPWCMS_LANG && window.PHPWCMS_LANG.editorFullscreen) || 'Fullscreen';

    const btnGroup = document.createElement('div');
    btnGroup.className = 'ace-btn-group';

    const btnWrap = document.createElement('button');
    btnWrap.type = 'button';
    btnWrap.className = 'btn-ace-tool active';
    btnWrap.title = langWrap;
    btnWrap.setAttribute('aria-label', langWrap);
    btnWrap.innerHTML = '<i class="fas fa-align-left"></i>';

    const btnFullscreen = document.createElement('button');
    btnFullscreen.type = 'button';
    btnFullscreen.className = 'btn-ace-tool';
    btnFullscreen.title = langFull;
    btnFullscreen.setAttribute('aria-label', langFull);
    btnFullscreen.innerHTML = '<i class="fas fa-expand"></i>';

    btnGroup.appendChild(btnWrap);
    btnGroup.appendChild(btnFullscreen);

    toolbar.appendChild(modeBadge);
    toolbar.appendChild(btnGroup);

    const editorDiv = document.createElement('div');
    editorDiv.className = 'phpwcms-ace-editor';

    wrap.appendChild(toolbar);
    wrap.appendChild(editorDiv);

    textarea.parentNode.insertBefore(wrap, textarea);
    textarea.style.display = 'none';
    textarea.dataset.aceInitialized = 'true';

    const isUrlInitial = (mode === 'url');
    const effectiveInitialMode = isUrlInitial ? 'text' : mode;

    const editor = ace.edit(editorDiv);
    editor.setTheme(getPhpwcmsAceTheme());
    editor.session.setMode('ace/mode/' + effectiveInitialMode);
    editor.session.setUseWorker(false); // Disable web worker linting for snippets (prevents DOCTYPE warning)
    editor.setValue(textarea.value, -1);
    editor.session.setUseWrapMode(true);
    editor.setOptions({
        showPrintMargin: false,
        tabSize: 4,
        useSoftTabs: true,
        autoScrollEditorIntoView: true,
        minLines: isUrlInitial ? 1 : minLines,
        maxLines: isUrlInitial ? 1 : maxLines
    });
    if (isUrlInitial) {
        modeBadge.textContent = 'URL';
    }

    // Intercept Enter key and multi-line paste when in URL mode
    editor.commands.addCommand({
        name: 'disableEnterInUrl',
        bindKey: { win: 'Enter|Shift-Enter', mac: 'Enter|Shift-Enter' },
        exec: function(ed) {
            if (modeBadge.textContent === 'URL') {
                return true; // Suppress Enter in URL mode
            }
            return false; // Allow default enter in normal modes
        },
        multiSelectAction: 'forEach',
        readOnly: false
    });

    editor.on('paste', function(e) {
        if (modeBadge.textContent === 'URL' && e.text) {
            e.text = e.text.replace(/[\r\n]+/g, ' ').trim();
        }
    });

    // Two-way sync
    editor.session.on('change', () => {
        let val = editor.getValue();
        if (modeBadge.textContent === 'URL' && /[\r\n]/.test(val)) {
            val = val.replace(/[\r\n]+/g, '').trim();
            editor.setValue(val, 1);
        }
        textarea.value = val;
    });

    if (textarea.form) {
        textarea.form.addEventListener('submit', () => {
            textarea.value = editor.getValue();
        });
    }

    // Fullscreen toggle
    btnFullscreen.addEventListener('click', e => {
        e.preventDefault();
        const isFull = wrap.classList.toggle('ace-fullscreen');
        btnFullscreen.classList.toggle('active', isFull);
        btnFullscreen.innerHTML = isFull ? '<i class="fas fa-compress"></i>' : '<i class="fas fa-expand"></i>';
        if (isFull) {
            editor.setOption('maxLines', null);
        } else {
            const isUrl = (modeBadge.textContent === 'URL');
            editor.setOption('maxLines', isUrl ? 1 : maxLines);
        }
        editor.resize();
    });

    // Word wrap toggle
    btnWrap.addEventListener('click', e => {
        e.preventDefault();
        const currentWrap = editor.session.getUseWrapMode();
        editor.session.setUseWrapMode(!currentWrap);
        btnWrap.classList.toggle('active', !currentWrap);
    });

    // Support mode switching via data-ace-target / data-ace-mode attributes or form format radios
    if (textarea.form || textarea.id) {
        const form = textarea.form || document;
        const selector = textarea.id ? `#${textarea.id}` : (textarea.name ? `textarea[name="${textarea.name}"]` : '');

        // 1. Explicit data-ace-target elements
        let explicitControls = [];
        if (textarea.id) {
            explicitControls = Array.from(form.querySelectorAll(`[data-ace-target="#${textarea.id}"]`));
        }
        if (textarea.name && !explicitControls.length) {
            explicitControls = Array.from(form.querySelectorAll(`[data-ace-target="${textarea.name}"], [data-ace-target="[name='${textarea.name}']"]`));
        }

        const updateEditorMode = (modeStr) => {
            let m = (modeStr || 'text').toLowerCase().trim();
            const isUrl = (m === 'url' || m === 'redirect');

            if (m === 'markdown') m = 'markdown';
            else if (m === 'textile') m = 'textile';
            else if (m === 'html' || m === '2' || m === 'wysiwyg') m = 'html';
            else if (m === 'php') m = 'php';
            else if (m === 'javascript' || m === 'js') m = 'javascript';
            else if (m === 'css') m = 'css';
            else if (m === 'json') m = 'json';
            else if (m === 'url' || m === 'redirect') m = 'text';
            else if (m === 'plain' || m === 'br' || m === '0' || m === '1') m = 'text';
            else m = 'text';

            editor.session.setMode('ace/mode/' + m);
            editor.session.setUseWorker(false);

            if (isUrl) {
                modeBadge.textContent = 'URL';
                // Strip existing newlines if switching to URL
                let currentVal = editor.getValue();
                if (/[\r\n]/.test(currentVal)) {
                    editor.setValue(currentVal.replace(/[\r\n]+/g, ' ').trim(), -1);
                }
                editor.setOptions({
                    minLines: 1,
                    maxLines: 1
                });
            } else {
                modeBadge.textContent = m.toUpperCase();
                editor.setOptions({
                    minLines: minLines,
                    maxLines: maxLines
                });
            }
            editor.resize();
        };

        if (explicitControls.length) {
            explicitControls.forEach(ctrl => {
                ctrl.addEventListener('change', () => {
                    if (ctrl.type === 'radio' || ctrl.type === 'checkbox') {
                        if (ctrl.checked) {
                            const mode = ctrl.dataset.aceMode || ctrl.value;
                            updateEditorMode(mode);
                        }
                    } else if (ctrl.tagName === 'SELECT') {
                        const opt = ctrl.options[ctrl.selectedIndex];
                        const mode = opt ? (opt.dataset.aceMode || ctrl.value) : ctrl.value;
                        updateEditorMode(mode);
                    }
                });
            });
        } else if (textarea.form) {
            // 2. Fallback: discover sibling/form format radios
            const formatRadios = textarea.form.querySelectorAll('input[name="ctext_format"], input[name="cnt_textformat"], input[name$="_format"], input[name$="_textformat"]');
            if (formatRadios.length) {
                formatRadios.forEach(radio => {
                    radio.addEventListener('change', () => {
                        if (radio.checked) {
                            updateEditorMode(radio.value);
                        }
                    });
                });
            }
        }
    }

    window.phpwcmsAceEditors.push(editor);
    textarea._aceEditor = editor;
    textarea._aceWrap = wrap;

    // Automatic resize when hidden tab/accordion/container becomes visible
    if (window.ResizeObserver) {
        let lastWidth = 0;
        let lastHeight = 0;
        const ro = new ResizeObserver(entries => {
            for (const entry of entries) {
                const { width, height } = entry.contentRect;
                if (width > 0 && height > 0 && (width !== lastWidth || height !== lastHeight)) {
                    lastWidth = width;
                    lastHeight = height;
                    editor.resize();
                }
            }
        });
        ro.observe(wrap);
        wrap._aceResizeObserver = ro;
    }

    return editor;
}

function initPhpwcmsCodeEditors() {
    if (typeof ace === 'undefined') return;
    document.querySelectorAll('textarea.code-editor, textarea[data-ace]').forEach(el => {
        initAceForTextarea(el);
    });

    // Resize editors when tabs, accordions, or modal dialogs are shown
    if (!window._phpwcmsAceTabListenersBound) {
        window._phpwcmsAceTabListenersBound = true;
        ['shown.bs.tab', 'shown.bs.collapse', 'shown.bs.modal'].forEach(evtName => {
            document.addEventListener(evtName, () => {
                window.phpwcmsAceEditors?.forEach(ed => ed.resize());
            });
        });
    }
}

function initSidebarToggle() {
    const toggleBtn = document.getElementById('sidebar-toggle');
    if (!toggleBtn) return;

    toggleBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
        try {
            localStorage.setItem('phpwcms_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        } catch (err) {}

        // Trigger resize event after transition so charts, Ace editors, tables re-render
        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
            window.phpwcmsAceEditors?.forEach(ed => ed.resize());
        }, 220);
    });
}

function initScrollAnchor() {
    if (window.location.hash) {
        try {
            const hash = window.location.hash.substring(1);
            if (!hash) return;
            const targetEl = document.getElementById(hash) || (window.location.hash ? document.querySelector(window.location.hash) : null);
            if (targetEl) {
                setTimeout(() => {
                    targetEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 80);
            }
        } catch (e) {
            try {
                const hash = window.location.hash.substring(1);
                const targetEl = document.getElementById(hash);
                if (targetEl) {
                    setTimeout(() => {
                        targetEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 80);
                }
            } catch (err) {}
        }
    }
}

if (typeof document !== 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initPhpwcmsTheme();
            initPhpwcmsCodeEditors();
            initSidebarToggle();
            initScrollAnchor();
        });
    } else {
        initPhpwcmsTheme();
        initPhpwcmsCodeEditors();
        initSidebarToggle();
        initScrollAnchor();
    }
    window.addEventListener('load', () => {
        initScrollAnchor();
    });
}


