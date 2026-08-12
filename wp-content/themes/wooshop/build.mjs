import fs from 'fs-extra';
import path from 'path';
import { fileURLToPath } from 'url';
import chokidar from 'chokidar';
import * as sass from 'sass';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import { build } from 'esbuild';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const ROOT = __dirname;

/*
 * WooShop source/build directories.
 */
const SRC = path.join(ROOT, 'assets', 'src');
const BUILD = path.join(ROOT, 'assets', 'build');

const SRC_CSS = path.join(SRC, 'css');
const SRC_SCSS = path.join(SRC, 'scss');
const SRC_JS = path.join(SRC, 'js');

const BUILD_CSS = path.join(BUILD, 'css');
const BUILD_JS = path.join(BUILD, 'js');

/**
 * CSS entry points.
 */
const cssEntries = [
    'app',
    'shop',
    'single-product',
    'cart',
    'checkout',
    'my-account',
];

/**
 * JavaScript entry points.
 */
const jsEntries = [
    'app',
    'shop',
    'single-product',
    'cart',
    'checkout',
    'my-account',
];

/**
 * Ensure build directories exist.
 */
async function prepare() {
    await fs.ensureDir(BUILD_CSS);
    await fs.ensureDir(BUILD_JS);
}

/**
 * Process CSS with PostCSS.
 *
 * @param {string} css CSS source.
 * @param {string} from Source file.
 * @returns {Promise<string>}
 */
async function processCss(css, from) {

    const result = await postcss([
        autoprefixer(),
        cssnano(),
    ]).process(css, {
        from,
    });

    return result.css;
}

async function compileScss(input) {

    const result = sass.compile(input, {

        loadPaths: [
            SRC_SCSS,
            path.join(
                ROOT,
                'node_modules'
            ),
        ],

        style: 'expanded',

        sourceMap: false,
    });

    return result.css;
}

/**
 * Build CSS entry.
 *
 * @param {string} name Entry name.
 * @returns {Promise<void>}
 */
async function buildCss(name) {

    const scssEntry = path.join(
        SRC_SCSS,
        `${name}.scss`
    );

    const cssEntry = path.join(
        SRC_CSS,
        `${name}.css`
    );

    let css = '';

    /*
     * Prefer SCSS.
     */
    if (
        await fs.pathExists(
            scssEntry
        )
    ) {

        css = await compileScss(
            scssEntry
        );

        /*
         * Fallback to plain CSS.
         */
    } else if (
        await fs.pathExists(
            cssEntry
        )
    ) {

        css = await fs.readFile(
            cssEntry,
            'utf8'
        );

    } else {

        console.warn(
            `CSS entry not found: ${name}`
        );

        return;
    }

    const processed = await processCss(
        css,
        scssEntry
    );

    const output = path.join(
        BUILD_CSS,
        `${name}.min.css`
    );

    await fs.writeFile(
        output,
        processed
    );

    console.log(
        `CSS  ✓ ${name}.min.css`
    );
}

/**
 * Build JavaScript entry.
 *
 * @param {string} name Entry name.
 * @returns {Promise<void>}
 */
async function buildJs(name) {

    const input = path.join(
        SRC_JS,
        `${name}.js`
    );

    if (!(await fs.pathExists(input))) {

        console.warn(
            `JS entry not found: ${name}.js`
        );

        return;
    }

    const output = path.join(
        BUILD_JS,
        `${name}.min.js`
    );

    await build({

        entryPoints: [
            input,
        ],

        bundle: true,

        minify: true,

        sourcemap: false,

        format: 'iife',

        target: [
            'es2018',
        ],

        outfile: output,
    });

    console.log(
        `JS   ✓ ${name}.min.js`
    );
}

/**
 * Build all assets.
 *
 * @returns {Promise<void>}
 */
async function buildAll() {

    await prepare();

    for (const entry of cssEntries) {

        await buildCss(entry);
    }

    for (const entry of jsEntries) {

        await buildJs(entry);
    }

    console.log(
        'WooShop assets built successfully.'
    );
}

/**
 * Watch source files.
 *
 * @returns {void}
 */
function watch() {

    const watcher = chokidar.watch(
        [
            `${SRC_CSS}/**/*`,
            `${SRC_JS}/**/*`,
        ],
        {
            ignoreInitial: true,
        }
    );

    let timer;

    watcher.on(
        'all',
        () => {

            clearTimeout(timer);

            timer = setTimeout(
                async () => {

                    console.log(
                        'Changes detected...'
                    );

                    await buildAll();
                },
                100
            );
        }
    );

    console.log(
        'Watching WooShop assets...'
    );
}

await buildAll();

if (
    process.argv.includes('--watch')
) {

    watch();
}