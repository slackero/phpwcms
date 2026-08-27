/**
 * phpwcms frontend JavaScript helpers
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 */

/**
 * Restore swapped images to their original source URLs.
 * Reverts the images manipulated by swapImage / MM_swapImage.
 */
function restoreSwappedImages() {
  const sr = document.MM_sr;
  if (Array.isArray(sr)) {
    for (const el of sr) {
      if (el && el.dataset && el.dataset.oSrc) {
        el.src = el.dataset.oSrc;
      }
    }
  }
}

function MM_swapImgRestore() {
  restoreSwappedImages();
}

/**
 * Swap image source files dynamically.
 * Accepts arguments in groups of three: element ID, ignored dummy parameter, and target image URL.
 *
 * @param {...string} args - Triplet tuples of (elementId, dummy, targetSrc)
 */
function swapImage(...args) {
  document.MM_sr = document.MM_sr || [];
  for (let i = 0; i < args.length - 2; i += 3) {
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

function MM_swapImage(...args) {
  swapImage(...args);
}

/**
 * Fallback message for bookmarking the current page.
 *
 * @param {string} [alerttext] - Custom bookmark message.
 * @returns {boolean} Always returns false to prevent link navigation.
 */
function BookMark_Page(alerttext) {
  const alertMsg = alerttext || 'To bookmark this page use [Ctrl+D] or [Cmd+D]';
  alert(alertMsg);
  return false;
}

/**
 * Replace the HTML content of a specified container element.
 *
 * @param {string} id - The container element ID.
 * @param {string} text - The replacement HTML content.
 */
function addText(id, text) {
  const el = document.getElementById(id);
  if (el) {
    el.innerHTML = text;
  }
}

/**
 * Legacy status bar message handler stub.
 *
 * @returns {boolean}
 */
function MM_displayStatusMsg() {
  return true;
}

// Global reference to the popup zoom window instance.
let clickZoomImage = null;

/**
 * Open a zoom/preview window for images.
 *
 * @param {string} url - Target image/page URL.
 * @param {string} [imgname] - Target window name.
 * @param {string} [windowstatus] - Specs and features of the popup window.
 */
function clickZoom(url, imgname = 'clickZoomWindow', windowstatus = '') {
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
  const el = document.getElementById(whichLayer);
  if (el) {
    el.style.display = status;
  }
}

/**
 * Trigger redirection to a mailto mail client scheme.
 *
 * @param {string} part1 - The mailbox name.
 * @param {string} part2 - The target domain name.
 * @returns {boolean}
 */
function mailtoLink(part1, part2) {
  if (part1 && part2) {
    window.location.href = `mailto:${part1}@${part2}`;
    return true;
  }
  return false;
}

/**
 * Register a callback to fire when the page DOM is fully loaded.
 * Works natively in vanilla JavaScript without requiring jQuery.
 *
 * @param {function} func - The callback function.
 */
function addLoadEvent(func) {
  if (typeof func !== 'function') {
    return;
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', func);
  } else {
    func();
  }
}
