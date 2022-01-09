/*!
 * phpwcms content management system
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2022, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 * @link http://www.phpwcms.org
 *
 */

var imageBrowser, uploadWin, temp_url;

function login(fval) {
    if (fval.json.value == '2') {
        fval.customlang.value = 1;
        fval.submit();
        return true;
    }
    fval.json.value = 0;
    if (fval.form_password.value && fval.form_loginname.value) {
        fval.md5pass.value = hex_md5(fval.form_password.value);
        fval.form_password.value = '';
        fval.json.value = 1;
        return true;
    } else {
        showLayer('jserr');
        return false;
    }
}

function MM_findObj(n, d) { //v4.01
    var p, i, x;
    if (!d) d = document;
    if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
        d = parent.frames[n.substring(p + 1)].document;
        n = n.substring(0, p);
    }
    if (!(x = d[n]) && d.all) x = d.all[n];
    for (i = 0; !x && i < d.forms.length; i++) x = d.forms[i][n];
    for (i = 0; !x && d.layers && i < d.layers.length; i++) x = MM_findObj(n, d.layers[i].document);
    if (!x && d.getElementById) x = d.getElementById(n);
    return x;
}

function MM_swapImage() { //v3.0
    var i, j = 0, x, a = MM_swapImage.arguments;
    document.MM_sr = [];
    for (i = 0; i < (a.length - 2); i += 3) {
        if ((x = MM_findObj(a[i])) != null) {
            document.MM_sr[j++] = x;
            if (!x.oSrc) x.oSrc = x.src;
            x.src = a[i + 2];
        }
    }
}

function clearText(thefield) {
    if (thefield.defaultValue == thefield.value) {
        thefield.value = '';
    }
}

function confirmGoUrl(confirmtext, jumpurl) {
    if (confirm(confirmtext)) {
        location.href = jumpurl;
    }
}

function wordcount(s) {
    var formcontent = Trim(s);
    if (formcontent == "") {
        return 0;
    } else {
        formcontent = formcontent.split(" ");
        return formcontent.length;
    }
}

function LTrim(str) {
    var whitespace = new String(" \t\n\r");
    var s = new String(str);
    if (whitespace.indexOf(s.charAt(0)) != -1) {
        var j = 0,
            i = s.length;
        while (j < i && whitespace.indexOf(s.charAt(j)) != -1) {
            j++;
            s = s.substring(j, i);
        }
    }
    return s;
}

function RTrim(str) {
    var whitespace = new String(" \t\n\r");
    var s = new String(str);
    if (whitespace.indexOf(s.charAt(s.length - 1)) != -1) {
        var i = s.length - 1;
        while (i >= 0 && whitespace.indexOf(s.charAt(i)) != -1) {
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
    var v1 = arguments,
        v2 = window.open(v1[0], v1[1], v1[2]),
        v3 = (v1.length > 3) ? v1[3] : false;
    if (v3) {
        v2.focus();
    }
    document.MM_returnValue = false;
}

function MM_showHideLayers() { //v6.0
    var i, p, v, obj, args = MM_showHideLayers.arguments;
    for (i = 0; i < (args.length - 2); i += 3) if ((obj = MM_findObj(args[i])) != null) {
        v = args[i + 2];
        if (obj.style) {
            obj = obj.style;
            v = (v == 'show') ? 'visible' : (v == 'hide') ? 'hidden' : v;
        }
        obj.visibility = v;
    }
}

function tmt_winOpen(u, id, f, df) {
    if (eval(id) == null || eval(id + ".closed")) {
        eval(id + "=window.open('" + u + "','" + id + "','" + f + "')");
        eval(id + ".focus()");
    } else if (df) {
        eval(id + ".focus()");
    } else {
        eval(id + "=window.open('" + u + "','" + id + "','" + f + "')");
        eval(id + ".focus()");
    }
}

function tmt_winControl(id, c) {
    var d = eval(id) == null || eval(id + ".closed");
    if (!d) {
        eval(id + "." + c);
    }
}

function get_cookie(Name) {
    var search = Name + "=";
    if (document.cookie.length > 0) {
        var offset = document.cookie.indexOf(search);
        // if cookie exists
        if (offset !== -1) {
            offset += search.length; // set index of beginning of value
            var end = document.cookie.indexOf(";", offset); // set index of end of cookie value
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
    for (var i = 0; i <= 9; i++) {
        MM_swapImage('imgpos' + i, '', i === x ? 'img/symbole/content_selected.gif' : 'img/leer.gif', 0);
    }
}

function changeImagePosMenu(f) {
    var x = f ? document.article.cimage_pos.selectedIndex : document.articlecontent.cimage_pos.selectedIndex;
    for (var i = 0; i <= 9; i++) {
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
    for (var i = 0; i < field.length; i++) {
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
    if (document.getElementById) {
        document.getElementById(whichLayer).style.display = status;
    } else if (document.all) {
        document.all[whichLayer].style.display = status;
    } else if (document.layers) {
        document.layers[whichLayer].display = status;
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
    if (document.all) {
        if (textObj.createTextRange && textObj.cursorPos) {
            var cursorPos = textObj.cursorPos;
            cursorPos.text = cursorPos.text.charAt(cursorPos.text.length - 1) === ' ' ? textFieldValue + ' ' : textFieldValue;
        } else {
            textObj.value = textObj.value + textFieldValue;
        }
    } else {
        if (textObj.setSelectionRange) {
            var rangeStart = textObj.selectionStart;
            var rangeEnd = textObj.selectionEnd;
            var tempStr1 = textObj.value.substring(0, rangeStart);
            var tempStr2 = textObj.value.substring(rangeEnd);
            textObj.value = tempStr1 + textFieldValue + tempStr2;
        } else {
            alert("This version of Mozilla based browser does not support setSelectionRange");
        }
    }
}

function getFieldById(fld) {
    if (document.getElementById && document.getElementById(fld) != null) {
        return document.getElementById(fld);
    } else if (document.layers && document.layers[fld] != null) {
        return document.layers[fld];
    } else if (document.all) {
        return document.all(fld);
    } else {
        return true;
    }
}

function getObjectById(fld) {
    if (document.getElementById && document.getElementById(fld) != null) {
        return document.getElementById(fld);
    } else if (document.layers && document.layers[fld] != null) {
        return document.layers[fld];
    } else if (document.all) {
        return document.all(fld);
    } else {
        return false;
    }
}

function switchDisabled(fld) {
    fld.disabled = !fld.disabled;
    return false;
}

function enableStatusMessage(fld, showHide, text) {
    var obj;
    if (document.getElementById && document.getElementById(fld) != null) {
        obj = document.getElementById(fld);
    } else if (document.layers && document.layers[fld] != null) {
        obj = document.layers[fld];
    } else {
        return true;
    }
    if (text) {
        obj.innerHTML = text;
    }
    obj.style.display = showHide ? 'block' : 'none';
    return true;
}

function create_alias(str, encoding, ucfirst) {
    str = str.toUpperCase();
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
        var c = str.charAt(0);
        str = c.toUpperCase() + str.slice(1);
    }
    return str;
}

var fbw = 450,
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
    var alias_basis = 'article_title',
        alias_target = 'article_alias';
    if (alias_type === 'struct') {
        alias_basis = 'acat_name';
        alias_target = 'acat_alias';
    }
    var aalias = getObjectById(alias_target);
    if (onempty_only && aalias.value !== '') {
        return false;
    }
    var atitle = getObjectById(alias_basis);
    aalias.value = create_alias((category ? category + '/' : '') + atitle.value);
    return false;
}

function flush_image_cache(link, url) {
    link.addClass('ajax-running');
    new Ajax(url, {
        method: 'get',
        onComplete: function () {
            link.removeClass('ajax-running');
        }
    }).request();
    return false;
}

// Autosize textarea
var autosizeTextareas = [];
if (typeof jQuery == 'undefined') { // still mootools
    window.addEvent('domready', function () {
        autosizeTextareas = $$('textarea.autosize');
        if (autosizeTextareas.length) {
            autosize(autosizeTextareas);
        }
    });
} else {
    $(function () {
        autosizeTextareas = $('textarea.autosize');
        if (autosizeTextareas.length) {
            autosize(autosizeTextareas);
        }
    });
}


var validation = {
    isEmailAddress: function (str) {
        var pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        return pattern.test(str);  // returns a boolean
    },
    isNotEmpty: function (str) {
        var pattern = /\S+/;
        return pattern.test(str);  // returns a boolean
    },
    isNumber: function (str) {
        var pattern = /^\d+$/;
        return pattern.test(str);  // returns a boolean
    },
    isSame: function (str1, str2) {
        return str1 === str2;
    },
    isInt: function (str) {
        var pattern = /^(\-?|\+?)\d+$/;
        return pattern.test(str);  // returns a boolean
    },
};

function togglePasswordVisibility(id) {
    var pwdField = document.getElementById(id);
    pwdField.type = pwdField.type === "password" ? "text" : "password";
    return pwdField.type === 'text' ? 'hide' : 'show';
}

function copyToClipboard(str) {
    var el = document.createElement('textarea');
    el.value = str;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    var selected = document.getSelection().rangeCount > 0 ? document.getSelection().getRangeAt(0) : false;
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    if (selected) {
        document.getSelection().removeAllRanges();
        document.getSelection().addRange(selected);
    }
}
