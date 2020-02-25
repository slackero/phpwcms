// special function for content part form

function showHide_TeaserArticleSelection(value) {

    if(!value) {

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

// special function for content part form
function showHide_CntFormfieldRow(whichLayer, status, rowplus) {

    var innerLink = '<a href="#" onclick="return showHide_CntFormfieldRow(\''+whichLayer+'\', ';

    if(status === 'block') {
        innerLink += "'none'";
        if(rowplus == 5) {
            innerLink += ', 5';
        }
        innerLink += ')"><img src="img/button/arrow_opened.gif" alt="" border="0" /></a>';
    } else {
        innerLink += "'block'";
        if(rowplus == 5) {
            innerLink += ', 5';
        }
        innerLink += ')"><img src="img/button/arrow_closed.gif" alt="" border="0" /></a>';
    }

    if (document.getElementById) {
        // this is the way the standards work
        document.getElementById(whichLayer+'_1').style.display = status;
        document.getElementById(whichLayer+'_2').style.display = status;
        document.getElementById(whichLayer+'_3').style.display = status;
        document.getElementById(whichLayer+'_4').style.display = status;

        if(rowplus == 5) {
            document.getElementById(whichLayer+'_5').style.display = status;
        }

        document.getElementById(whichLayer).innerHTML = innerLink;

        if(status === 'block') {
            document.getElementById(whichLayer+'_1').style.display = 'table-row';
            document.getElementById(whichLayer+'_2').style.display = 'table-row';
            document.getElementById(whichLayer+'_3').style.display = 'table-row';
            document.getElementById(whichLayer+'_4').style.display = 'table-row';
            if(rowplus == 5) {
                document.getElementById(whichLayer+'_5').style.display = 'table-row';
            }
        }

    }
    else if (document.all) {
        // this is the way old msie versions work
        document.all[whichLayer+'_1'].style.display = status;
        document.all[whichLayer+'_2'].style.display = status;
        document.all[whichLayer+'_3'].style.display = status;
        document.all[whichLayer+'_4'].style.display = status;
        if(rowplus == 5) {
            document.all[whichLayer+'_5'].style.display = status;
        }
        document.all[whichLayer].innerHTML = innerLink;
    }
    else if (document.layers) {
        // this is the way nn4 works
        document.layers[whichLayer+'_1'].display = status;
        document.layers[whichLayer+'_2'].display = status;
        document.layers[whichLayer+'_3'].display = status;
        document.layers[whichLayer+'_4'].display = status;
        if(rowplus == 5) {
            document.layers[whichLayer+'_5'].display = status;
        }
        document.layers[whichLayer].innerHTML = innerLink;
    }
    return false;
}

function insertOptionBefore(objectId, optionValue, optionText) {
    var elSel = document.getElementById(objectId);
    if (elSel.selectedIndex >= 0) {
        var elOptNew = document.createElement('option');
        elOptNew.text = optionText;
        elOptNew.value = optionValue;
        var elOptOld = elSel.options[elSel.selectedIndex];
        try {
            elSel.add(elOptNew, elOptOld); // standards compliant; doesn't work in IE
        } catch(ex) {
            elSel.add(elOptNew, elSel.selectedIndex); // IE only
        }
    }
}

function removeOptionSelected(objectId) {
    var elSel = document.getElementById(objectId);
    var i;
    for (i = elSel.length - 1; i>=0; i--) {
        if (elSel.options[i].selected) {
            elSel.remove(i);
        }
    }
}

function appendOptionLast(objectId, optionValue, optionText) {
    var elOptNew = document.createElement('option');
    elOptNew.text = optionText;
    elOptNew.value = optionValue;
    var elSel = document.getElementById(objectId);
    try {
        elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
    } catch(ex) {
        elSel.add(elOptNew); // IE only
    }
}

function removeOptionLast(objectId) {
    var elSel = document.getElementById(objectId);
    if (elSel.length > 0) {
        elSel.remove(elSel.length - 1);
    }
}
