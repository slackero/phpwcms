// special function for content part form

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

// special function for content part form
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
