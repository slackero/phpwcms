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
        { src: '../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', dest: '../template/lib/bootstrap5/bootstrap.bundle.min.js' },
        { src: '../../node_modules/dropzone/dist/min/dropzone.min.js', dest: 'inc_js/dropzone.min.js' },
        { src: '../../node_modules/flatpickr/dist/flatpickr.min.js', dest: 'inc_js/flatpickr.min.js' },
        { src: '../../node_modules/dayjs/dayjs.min.js', dest: 'inc_js/dayjs.min.js' },
        { src: '../../node_modules/jquery/dist/jquery.min.js', dest: 'inc_js/jquery/jquery-3.7.1.min.js' },
        { src: '../../node_modules/jquery/dist/jquery.min.js', dest: '../template/lib/jquery/jquery-3.7.1.min.js' },
        { src: '../../node_modules/video.js/dist/video.min.js', dest: '../template/lib/video-js/video.min.js' },
        { src: '../../node_modules/glightbox/dist/js/glightbox.min.js', dest: '../template/lib/glightbox/glightbox.min.js' },
        { src: '../../node_modules/vanilla-cookieconsent/dist/cookieconsent.umd.js', dest: '../template/lib/cookieconsent3/cookieconsent.umd.js' },
        { src: '../../node_modules/vanilla-cookieconsent/dist/core/cookieconsent-core.umd.js', dest: '../template/lib/cookieconsent3/core/cookieconsent-core.umd.js' },
        { src: '../../node_modules/vanilla-cookieconsent/LICENSE', dest: '../template/lib/cookieconsent3/LICENSE' }
    ];

    for (const item of copyMap) {
        const srcPath = path.join(__dirname, item.src);
        const destPath = path.join(rootDir, item.dest);
        if (fs.existsSync(srcPath)) {
            fs.mkdirSync(path.dirname(destPath), { recursive: true });
            let content = fs.readFileSync(srcPath, 'utf8');
            // Strip sourceMappingURL references
            content = content.replace(/\/\*# sourceMappingURL=.*?\*\//g, '').replace(/\/\/# sourceMappingURL=.*$/gm, '');
            fs.writeFileSync(destPath, content);
            console.log(`[✓] Synced ${item.dest}`);
        } else {
            console.warn(`[!] Warning: Source JS not found: ${srcPath}`);
        }
    }

    // 3. Copy curated Ace editor files from ace-builds
    const srcAce = path.join(__dirname, '../../node_modules/ace-builds/src-min-noconflict');
    const destAce = path.join(rootDir, 'inc_js/ace');
    if (fs.existsSync(srcAce)) {
        if (fs.existsSync(destAce)) {
            fs.rmSync(destAce, { recursive: true, force: true });
        }
        fs.mkdirSync(destAce, { recursive: true });

        const aceFiles = [
            'ace.js',
            // Modes
            'mode-html.js',
            'mode-php.js',
            'mode-javascript.js',
            'mode-css.js',
            'mode-scss.js',
            'mode-less.js',
            'mode-markdown.js',
            'mode-textile.js',
            'mode-text.js',
            'mode-plain_text.js',
            'mode-xml.js',
            'mode-svg.js',
            'mode-json.js',
            'mode-yaml.js',
            'mode-ini.js',
            'mode-apache_conf.js',
            'mode-sql.js',
            'mode-mysql.js',
            'mode-pgsql.js',
            'mode-sh.js',
            // Themes
            'theme-chrome.js',
            'theme-one_dark.js',
            'theme-github.js',
            'theme-github_dark.js',
            'theme-monokai.js',
            'theme-dracula.js',
            'theme-tomorrow_night.js',
            // Workers
            'worker-base.js',
            'worker-html.js',
            'worker-php.js',
            'worker-javascript.js',
            'worker-css.js',
            'worker-json.js',
            'worker-xml.js',
            'worker-yaml.js',
            // Extensions
            'ext-language_tools.js',
            'ext-searchbox.js',
            'ext-settings_menu.js',
            'ext-beautify.js',
            'ext-emmet.js'
        ];

        let copiedCount = 0;
        for (const file of aceFiles) {
            const sf = path.join(srcAce, file);
            const df = path.join(destAce, file);
            if (fs.existsSync(sf)) {
                fs.copyFileSync(sf, df);
                copiedCount++;
            }
        }
        console.log(`[✓] Synced ${copiedCount} Ace editor files (inc_js/ace)`);
    } else {
        console.warn(`[!] Warning: ace-builds not found at ${srcAce}`);
    }

    // 3. Minify and optimize cookieconsent2 (legacy v2)
    const srcCc2 = path.join(__dirname, '../../node_modules/cookieconsent2/cookieconsent.js');
    const destCc2 = path.join(rootDir, '../template/lib/cookieconsent2/cookieconsent.min.js');
    if (fs.existsSync(srcCc2)) {
        let codeCc2 = fs.readFileSync(srcCc2, 'utf8');
        codeCc2 = codeCc2.replace('http://silktide.com/cookieconsent', 'https://silktide.com/tools/cookie-consent/');
        codeCc2 = codeCc2.replace("document.readyState == 'complete'", "(document.readyState === 'complete' || document.readyState === 'interactive')");
        if (!codeCc2.includes('DOMContentLoaded')) {
            codeCc2 = codeCc2.replace(
                "Util.addEventListener(document, 'readystatechange', init);",
                "Util.addEventListener(document, 'DOMContentLoaded', init);\n  Util.addEventListener(document, 'readystatechange', init);"
            );
        }
        if (!codeCc2.includes('opts.cookie_name')) {
            codeCc2 = codeCc2.replace(
                'this.setDismissedCookie();',
                'if (this.options.cookie_name) { DISMISSED_COOKIE = this.options.cookie_name; }\n      this.setDismissedCookie();'
            );
            codeCc2 = codeCc2.replace(
                'if (document.cookie.indexOf(DISMISSED_COOKIE) > -1',
                'var opts = window[OPTIONS_VARIABLE] || {}; if (opts.cookie_name) { DISMISSED_COOKIE = opts.cookie_name; }\n  if (document.cookie.indexOf(DISMISSED_COOKIE) > -1'
            );
        }
        const minifiedCc2 = await terser.minify(codeCc2, { compress: true, mangle: true });
        if (minifiedCc2.code) {
            fs.writeFileSync(destCc2, minifiedCc2.code);
            console.log(`[✓] cookieconsent2 minified (${(minifiedCc2.code.length / 1024).toFixed(2)} KB)`);
        }
    }
}

buildJs().catch(err => {
    console.error(err);
    process.exit(1);
});
