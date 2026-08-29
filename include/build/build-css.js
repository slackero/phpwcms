const fs = require('fs');
const path = require('path');
const lightningcss = require('lightningcss');

const includeDir = path.dirname(__dirname); // include/

const cssFiles = [
    path.join(__dirname, '../../node_modules/bootstrap/dist/css/bootstrap.min.css'),
    path.join(__dirname, '../../node_modules/flag-icons/css/flag-icons.min.css'),
    path.join(__dirname, '../../node_modules/@fortawesome/fontawesome-free/css/all.min.css'),
    path.join(__dirname, '../../node_modules/@fortawesome/fontawesome-free/css/v4-shims.min.css'),
    path.join(includeDir, 'inc_css/phpwcms.css')
];

let combined = '';
for (const file of cssFiles) {
    if (fs.existsSync(file)) {
        combined += fs.readFileSync(file, 'utf8') + '\n';
    } else {
        console.warn(`[!] Warning: Missing CSS file: ${file}`);
    }
}

const noMinify = process.argv.includes('--no-minify') || process.env.MINIFY === 'false';

// 1. Build backend.min.css with lightningcss
const res = lightningcss.transform({
    filename: 'backend.css',
    code: Buffer.from(combined),
    minify: !noMinify
});

const targetCss = path.join(includeDir, 'inc_css/backend.min.css');
fs.writeFileSync(targetCss, res.code);
console.log(`[✓] backend.min.css built with lightningcss (minified: ${!noMinify}) (${(res.code.length / 1024).toFixed(2)} KB)`);



// 2. Copy conditional CSS assets from node_modules
const copyMap = [
    { src: '../../node_modules/dropzone/dist/min/dropzone.min.css', dest: 'inc_css/dropzone.min.css' },
    { src: '../../node_modules/flatpickr/dist/flatpickr.min.css', dest: 'inc_css/flatpickr.min.css' },
    { src: '../../node_modules/flatpickr/dist/themes/material_blue.css', dest: 'inc_css/flatpickr-material.min.css' },
    { src: '../../node_modules/tom-select/dist/css/tom-select.bootstrap5.css', dest: 'inc_css/tom-select.bootstrap5.css' },
    { src: '../../node_modules/video.js/dist/video-js.min.css', dest: '../template/lib/video-js/video-js.min.css' },
    { src: '../../node_modules/glightbox/dist/css/glightbox.min.css', dest: '../template/lib/glightbox/glightbox.min.css' },
    { src: '../../node_modules/vanilla-cookieconsent/dist/cookieconsent.css', dest: '../template/lib/cookieconsent3/cookieconsent.css' },
    { src: '../../node_modules/vanilla-cookieconsent/dist/css-components/base.css', dest: '../template/lib/cookieconsent3/css-components/base.css' },
    { src: '../../node_modules/vanilla-cookieconsent/dist/css-components/consent-modal.css', dest: '../template/lib/cookieconsent3/css-components/consent-modal.css' },
    { src: '../../node_modules/vanilla-cookieconsent/dist/css-components/dark-scheme.css', dest: '../template/lib/cookieconsent3/css-components/dark-scheme.css' },
    { src: '../../node_modules/vanilla-cookieconsent/dist/css-components/light-scheme.css', dest: '../template/lib/cookieconsent3/css-components/light-scheme.css' },
    { src: '../../node_modules/vanilla-cookieconsent/dist/css-components/preferences-modal.css', dest: '../template/lib/cookieconsent3/css-components/preferences-modal.css' }
];

for (const item of copyMap) {
    const srcPath = path.join(__dirname, item.src);
    const destPath = path.join(includeDir, item.dest);
    if (fs.existsSync(srcPath)) {
        fs.mkdirSync(path.dirname(destPath), { recursive: true });
        fs.copyFileSync(srcPath, destPath);
        console.log(`[✓] Synced ${item.dest}`);
    } else {
        console.warn(`[!] Warning: Source not found: ${srcPath}`);
    }
}

// 3. Sync FontAwesome webfonts to include/webfonts/
const fontSrcDir = path.join(__dirname, '../../node_modules/@fortawesome/fontawesome-free/webfonts');
const fontDestDir = path.join(includeDir, 'webfonts');

if (fs.existsSync(fontSrcDir)) {
    fs.mkdirSync(fontDestDir, { recursive: true });
    // Clean old font files in webfonts dir
    const existingFonts = fs.readdirSync(fontDestDir);
    for (const f of existingFonts) {
        if (f.startsWith('fa-')) {
            fs.unlinkSync(path.join(fontDestDir, f));
        }
    }
    const fonts = fs.readdirSync(fontSrcDir);
    for (const font of fonts) {
        fs.copyFileSync(path.join(fontSrcDir, font), path.join(fontDestDir, font));
    }
    console.log(`[✓] Synced ${fonts.length} FontAwesome webfonts to include/webfonts/`);
}

// 4. Minify cookieconsent2 CSS themes
const cc2ThemeDir = path.join(includeDir, '../template/lib/cookieconsent2');
if (fs.existsSync(cc2ThemeDir)) {
    const cc2Files = fs.readdirSync(cc2ThemeDir).filter(f => f.endsWith('.css'));
    for (const f of cc2Files) {
        const filePath = path.join(cc2ThemeDir, f);
        const cssContent = fs.readFileSync(filePath, 'utf8');
        try {
            const minified = lightningcss.transform({
                filename: f,
                code: Buffer.from(cssContent),
                minify: true
            });
            fs.writeFileSync(filePath, minified.code);
        } catch (e) {
            console.warn(`[!] Warning: Failed to minify ${f}: ${e.message}`);
        }
    }
    console.log(`[✓] Minified ${cc2Files.length} cookieconsent2 CSS theme files`);
}
