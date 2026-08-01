const fs = require('fs');
const path = require('path');
const lightningcss = require('lightningcss');

const includeDir = path.dirname(__dirname); // include/

const cssFiles = [
    path.join(__dirname, '../../node_modules/bootstrap/dist/css/bootstrap.min.css'),
    path.join(__dirname, '../../node_modules/flag-icons/css/flag-icons.min.css'),
    path.join(includeDir, 'inc_css/phpwcms-fontawesome.css'),
    path.join(includeDir, 'inc_css/phpwcms.css'),
    path.join(includeDir, 'inc_css/phpwcmsspecial.css'),
    path.join(includeDir, 'inc_css/login.css')
];

let combined = '';
for (const file of cssFiles) {
    if (fs.existsSync(file)) {
        combined += fs.readFileSync(file, 'utf8') + '\n';
    } else {
        console.warn(`[!] Warning: Missing CSS file: ${file}`);
    }
}

// 1. Build backend.min.css
const res = lightningcss.transform({
    filename: 'backend.css',
    code: Buffer.from(combined),
    minify: true
});

const targetCss = path.join(includeDir, 'inc_css/backend.min.css');
fs.writeFileSync(targetCss, res.code);
console.log(`[✓] backend.min.css built from node_modules + phpwcms styles (${(res.code.length / 1024).toFixed(2)} KB)`);

// 2. Copy conditional CSS assets from node_modules
const copyMap = [
    { src: '../../node_modules/dropzone/dist/min/dropzone.min.css', dest: 'inc_css/dropzone.min.css' },
    { src: '../../node_modules/flatpickr/dist/flatpickr.min.css', dest: 'inc_css/flatpickr.min.css' },
    { src: '../../node_modules/flatpickr/dist/themes/material_blue.css', dest: 'inc_css/flatpickr-material.min.css' },
    { src: '../../node_modules/tom-select/dist/css/tom-select.bootstrap4.css', dest: 'inc_css/tom-select.bootstrap4.css' }
];

for (const item of copyMap) {
    const srcPath = path.join(__dirname, item.src);
    const destPath = path.join(includeDir, item.dest);
    if (fs.existsSync(srcPath)) {
        fs.copyFileSync(srcPath, destPath);
        console.log(`[✓] Synced ${item.dest}`);
    } else {
        console.warn(`[!] Warning: Source not found: ${srcPath}`);
    }
}
