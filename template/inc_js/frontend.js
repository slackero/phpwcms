/**
 * Restore swapped images to their original source URLs.
 * Reverts the images manipulated by MM_swapImage.
 */
function MM_swapImgRestore() {
  const sr = document.MM_sr;
  if (sr) {
    for (let i = 0; i < sr.length; i++) {
      const el = $(sr[i]);
      const oSrc = el.data('oSrc');
      if (oSrc) {
        el.attr('src', oSrc);
      }
    }
  }
}

/**
 * Locate a DOM element by its ID.
 * Kept for backwards compatibility with legacy layout dependencies.
 *
 * @param {string} n - The element ID.
 * @param {Document|HTMLElement} d - The context document or element (optional).
 * @returns {HTMLElement|null} The DOM element if found.
 */
function MM_findObj(n, d) {
  const el = $('#' + n, d || document);
  return el.length ? el[0] : null;
}

/**
 * Swap image source files dynamically (commonly used for image hovers).
 * Accepts arguments in groups of three: element ID, ignored dummy parameter, and target image URL.
 */
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

/**
 * Alert fallback for bookmarking the current page.
 * Legacy window.sidebar and window.external bookmark methods are defunct in modern browsers.
 *
 * @param {string} alerttext - Custom bookmark message (optional).
 */
function BookMark_Page(alerttext) {
  const alertMsg = alerttext || "To bookmark this page use [Ctrl+D] or [Cmd+D]";
  alert(alertMsg);
  return false;
}

/**
 * Replace the HTML content of a specified container.
 *
 * @param {string} id - The container element ID.
 * @param {string} text - The replacement HTML content.
 */
function addText(id, text) {
  $('#' + id).html(text);
}

/**
 * Legacy status bar message handler.
 * Setting window.status is blocked by modern browsers for security reasons.
 */
function MM_displayStatusMsg(msgStr) {
  window.status = msgStr;
  document.MM_returnValue = true;
}

// Global reference to the popup zoom window instance.
let clickZoomImage = null;

/**
 * Open a zoom/preview window for images.
 *
 * @param {string} url - Target image/page URL.
 * @param {string} imgname - Target window name.
 * @param {string} windowstatus - Specs and features of the popup window.
 */
function clickZoom(url, imgname, windowstatus) {
  clickZoomImage = window.open(url, imgname, windowstatus);
  if (clickZoomImage && window.focus) {
    clickZoomImage.focus();
  }
}

/**
 * Close the clickZoom preview popup window if it is currently open.
 */
function checkClickZoom() {
  if (clickZoomImage && !clickZoomImage.closed) {
    clickZoomImage.close();
  }
}

// Memory of current CSS display values mapped by layer ID.
const layerDisplayStatus = {};

/**
 * Toggle the CSS display mode of a specific element.
 *
 * @param {string} whichLayer - The element ID.
 * @param {string} status - Target CSS display value (e.g. 'none', 'block').
 */
function toggleLayerDisplay(whichLayer, status) {
  layerDisplayStatus[whichLayer] = status;
  $('#' + whichLayer).css('display', status);
}

/**
 * Trigger redirection to a mailto mail client scheme.
 *
 * @param {string} part1 - The mailbox name.
 * @param {string} part2 - The target domain name.
 */
function mailtoLink(part1, part2) {
  if (part1 && part2) {
    window.location.href = `mailto:${part1}@${part2}`;
    return true;
  }
  return false;
}

/**
 * Register a callback to fire when the page environment is fully loaded.
 * Uses jQuery document-ready shortcut internally.
 *
 * @param {function} func - The callback function.
 */
function addLoadEvent(func) {
  $(func);
}
