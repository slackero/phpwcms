/**
 * phpwcms image zoom popup handler
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 */

/**
 * Automatically adjusts the zoom popup window size and centers it on screen
 * based on the natural dimensions of the loaded image.
 */
function adjust_popup() {
  const img = document.querySelector('img');
  const imgW = img ? (img.naturalWidth || img.offsetWidth || 300) : 300;
  const imgH = img ? (img.naturalHeight || img.offsetHeight || 200) : 200;

  try {
    const chromeW = window.outerWidth - window.innerWidth;
    const chromeH = window.outerHeight - window.innerHeight;

    let targetW = imgW + (chromeW > 0 ? chromeW : 0);
    let targetH = imgH + (chromeH > 0 ? chromeH : 0);

    const maxW = screen.availWidth || 1024;
    const maxH = screen.availHeight || 768;

    if (targetW > maxW) {
      targetW = maxW;
      targetH = Math.min(targetH + 16, maxH);
    }
    if (targetH > maxH) {
      targetH = maxH;
      targetW = Math.min(targetW + 16, maxW);
    }

    const left = Math.max(0, Math.round(((screen.availWidth || window.innerWidth) - targetW) / 2));
    const top = Math.max(0, Math.round(((screen.availHeight || window.innerHeight) - targetH) / 2));

    window.resizeTo(targetW, targetH);
    window.moveTo(left, top);
  } catch {
    // Ignore browser restrictions on window resize/move
  }
}

function initImageZoom() {
  const img = document.querySelector('img');
  if (img) {
    if (img.complete) {
      adjust_popup();
    } else {
      img.addEventListener('load', adjust_popup, { once: true });
    }
  } else {
    adjust_popup();
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      window.close();
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initImageZoom);
} else {
  initImageZoom();
}
