const fs = require('fs');
const path = require('path');
const terser = require('terser');

const rootDir = path.dirname(__dirname); // include/

async function buildJs() {
    // 1. Minify phpwcms.js -> phpwcms.min.js
    const srcJs = path.join(rootDir, 'inc_js/phpwcms.js');
    const destJs = path.join(rootDir, 'inc_js/phpwcms.min.js');

    if (fs.existsSync(srcJs)) {
        const code = fs.readFileSync(srcJs, 'utf8');
        const minified = await terser.minify(code, { compress: true, mangle: true });
        if (minified.code) {
            fs.writeFileSync(destJs, minified.code);
            console.log(`[✓] phpwcms.min.js built (${(minified.code.length / 1024).toFixed(2)} KB)`);
        }
    }

    // 2. Copy third-party JS assets from node_modules into include/inc_js/
    const copyMap = [
        { src: '../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', dest: 'inc_js/bootstrap.bundle.min.js' },
        { src: '../../node_modules/dropzone/dist/min/dropzone.min.js', dest: 'inc_js/dropzone.min.js' },
        { src: '../../node_modules/flatpickr/dist/flatpickr.min.js', dest: 'inc_js/flatpickr.min.js' },
        { src: '../../node_modules/dayjs/dayjs.min.js', dest: 'inc_js/dayjs.min.js' },
        { src: '../../node_modules/jquery/dist/jquery.min.js', dest: 'inc_js/jquery/jquery-3.7.1.min.js' }
    ];

    for (const item of copyMap) {
        const srcPath = path.join(__dirname, item.src);
        const destPath = path.join(rootDir, item.dest);
        if (fs.existsSync(srcPath)) {
            fs.mkdirSync(path.dirname(destPath), { recursive: true });
            fs.copyFileSync(srcPath, destPath);
            console.log(`[✓] Synced ${item.dest}`);
        } else {
            console.warn(`[!] Warning: Source JS not found: ${srcPath}`);
        }
    }
}

buildJs().catch(err => {
    console.error(err);
    process.exit(1);
});
