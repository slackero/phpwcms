/**
 * Automatically adjusts the zoom popup window size and centers it on the screen
 * based on the natural dimensions of the loaded image.
 */
function adjust_popup() {
    const fixedW = window.innerWidth;
    const fixedH = window.innerHeight;
    window.resizeTo(fixedW, fixedH);
    
    const diffW = fixedW - window.innerWidth;
    const diffH = fixedH - window.innerHeight;
    
    let w = fixedW + diffW;
    let h = fixedH + diffH;
    
    if (h >= screen.availHeight) {
        w += 16;
    }
    if (w >= screen.availWidth) {
        h += 16;
    }
    
    w = Math.min(w, screen.availWidth);
    h = Math.min(h, screen.availHeight);
    
    window.resizeTo(w, h);
    window.moveTo((screen.availWidth - w) / 2, (screen.availHeight - h) / 2);
}

window.addEventListener('DOMContentLoaded', adjust_popup);
