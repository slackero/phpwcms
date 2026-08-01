/*!
 * phpwcms
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 *
 */

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
let topOffset = 95;
let height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;

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

    $win.bind("load resize", function () {
        topOffset = 95;
        let width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse')
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse')
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - topOffset;
        if (height < 1) {
            height = 1;
        }
        if (height > topOffset) {
            $pageWrapper.css("min-height", (height) + "px");
        }
    });

    if (typeof $.fn.tooltip === 'function') {
        $body.tooltip({
            selector: '[data-toggle="tooltip"]',
            delay: {
                show: 200,
                hide: 50
            },
            container: 'body',
            boundary: 'window',
            sanitize: false
        });
    }

    $doc.on('click', '.modalButton', function (e) {
        const $this = $(this);
        const src = $this.attr('data-src');
        const modaltitle = $this.attr('alt') || '';

        $modalBody.css({
            'overflow-y': 'auto',
            'min-height': $win.height() * 0.8
        });

        $iframe.attr({'src': src, 'height': '100%', 'width': '100%'});
        $modalHeader.html(modaltitle);
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

        $.ajax({
            url: 'include/inc_act/ajax_changer.php?' + CSRF_GET_TOKEN,
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
                if ($(thisbtn).hasClass('btn-success')) {
                    $(thisbtn).removeClass('btn-success').addClass('btn-danger');
                } else {
                    $(thisbtn).removeClass('btn-danger').addClass('btn-success');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + ' ' + thrownError);
            }
        });
    });

    $('[id^="imgpos"]').on('click', function () {
        const id = $(this).attr('id');
        const x = id.match(/[\d\.]+/g);
        $("#cimage_pos").val(x);
        for (let i = 0; i <= 9; i++) {
            if (i === x) {
                $("#imgpos" + i).removeClass('btn-blue').addClass('btn-success');
            } else {
                $("#imgpos" + i).removeClass('btn-success').addClass('btn-blue');
            }
        }
    });

    $('#cimage_pos').on('change', function () {
        const x = $(this).val();
        for (let i = 0; i <= 9; i++) {
            if (i === x) {
                $("#imgpos" + i).removeClass('btn-blue').addClass('btn-success');
            } else {
                $("#imgpos" + i).removeClass('btn-success').addClass('btn-blue');
            }
        }
    });

    $('#side-menu li').on('click', function () {
        $('#side-menu ul').css("display", "none");
        $(this).children('ul').css("display", "block");
        height = height - topOffset;
        let newheight = $('#side-menu').height() + (topOffset * 2);
        if (height < newheight) {
            $("#page-wrapper").css("min-height", (newheight) + "px");
        }
    });

});

function SendData1(sVar1, sVar2, sVar3, stoken) {
    // get the values
    let sVar4;
    let sVaricon;
    if (sVar1 == 4 || sVar1 == 6) {
        sVar4 = 'public';
        sVaricon = 'fa-lock';
    } else {
        sVar4 = 'visible';
        sVaricon = 'fa-eye';
    }
    let sImage = sVar4 + '_' + sVar1 + '_' + sVar2 + '_' + sVar3;

    let sVar5 = 3;
    let sLen;
    if ($('#' + sImage).is('i')) {
        if ($('#' + sImage).hasClass('icolor1')) {
            sVar5 = 0;
        } else {
            sVar5 = 1;
        }
    } else {
        sLen = document.getElementById(sImage).src.length;
        sVar5 = document.getElementById(sImage).src.substring(sLen - 5, sLen - 4);
        if (sVar5 == 1) {
            sVar5 = 0;
        } else {
            sVar5 = 1;
        }
    }

    //Change image
    $.ajax({
        url: 'include/inc_act/act_articlecontent.php?' + stoken + '&do=' + sVar1 + ',' + sVar2 + ',' + sVar3 + ',' + sVar5,
        xhrFields: {
            withCredentials: true
        },
        context: document.body
    }).done(function () {
        $("#" + sImage).replaceWith('<i class="fa ' + sVaricon + ' icolor' + sVar5 + '" id="' + sVar4 + '_' + sVar1 + '_' + sVar2 + '_' + sVar3 + '" aria-hidden="true" onclick="SendData(' + "'" + sVar1 + "','" + sVar2 + "','" + sVar3 + "','" + stoken + "'" + ')"></i>');
    });
}

//Ajax Sort contentpart
function SendDataSort(sVar) {
    const url = 'include/inc_act/act_articlesort.php?' + CSRF_GET_TOKEN + '&sortid=' + sVar;
    $.ajax({
        url: url,
        xhrFields: {
            withCredentials: true
        }
    });
}




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
