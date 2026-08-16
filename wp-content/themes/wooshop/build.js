/**
 * WooShop Asset Build System
 *
 * Builds the global assets and every WooShop component asset
 * as an independent production file.
 *
 * Source directories are mirrored into assets/build so that
 * the PHP asset configuration can enqueue only what is needed.
 *
 * @package WooShop
 */

import fs from 'fs-extra';
import path from 'path';
import {fileURLToPath} from 'url';
import chokidar from 'chokidar';
import * as sass from 'sass';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import {build as esbuild} from 'esbuild';

/**
 * Resolve the current build directory.
 */
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/**
 * WooShop asset paths.
 */
const paths = {
    src: path.join(__dirname, 'assets', 'src'), build: path.join(__dirname, 'assets', 'build'),

    scss: path.join(__dirname, 'assets', 'src', 'scss'), js: path.join(__dirname, 'assets', 'src', 'js'),

    css: path.join(__dirname, 'assets', 'build', 'css'), jsOutput: path.join(__dirname, 'assets', 'build', 'js'),
};

/**
 * Bootstrap CSS load path.
 */
const bootstrapPath = path.join(__dirname, 'node_modules');

/**
 * Ensure build directories exist.
 *
 * @returns {Promise<void>}
 */
async function prepareDirectories() {

    await fs.ensureDir(paths.css);
    await fs.ensureDir(paths.jsOutput);
}

/**
 * Determine whether a file should be treated as a build entry.
 *
 * Files beginning with "_" are treated as Sass partials.
 *
 * @param {string} file File path.
 *
 * @returns {boolean}
 */
function isBuildEntry(file) {

    const name = path.basename(file);

    return !name.startsWith('_');
}

/**
 * Recursively find files with a specific extension.
 *
 * @param {string} directory Source directory.
 * @param {string} extension File extension.
 *
 * @returns {string[]}
 */
function findFiles(directory, extension) {

    const files = [];

    if (!fs.existsSync(directory)) {
        return files;
    }

    for (const entry of fs.readdirSync(directory, {
        withFileTypes: true,
    })) {

        const fullPath = path.join(directory, entry.name);

        if (entry.isDirectory()) {

            files.push(...findFiles(fullPath, extension));

            continue;
        }

        if (entry.isFile() && entry.name.endsWith(extension) && isBuildEntry(fullPath)) {
            files.push(fullPath);
        }
    }

    return files;
}

/**
 * Build one SCSS entry.
 *
 * @param {string} input Source SCSS file.
 *
 * @returns {Promise<void>}
 */
async function buildSassFile(input) {

    const relative = path.relative(paths.scss, input);

    const output = path.join(paths.css, relative.replace(/\.scss$/, '.min.css'));

    await fs.ensureDir(path.dirname(output));

    const result = sass.compile(input, {
        style: 'expanded',

        loadPaths: [paths.scss, bootstrapPath,],

        sourceMap: false,
    });

    const processed = await postcss([autoprefixer(), cssnano({
        preset: 'default',
    }),]).process(result.css, {
        from: input, to: output,
    });

    await fs.writeFile(output, processed.css, 'utf8');

    console.log(`CSS: ${path.relative(__dirname, output)}`);
}

/**
 * Build all SCSS files.
 *
 * Every SCSS entry receives its own CSS output.
 *
 * @returns {Promise<void>}
 */
async function buildCSS() {

    const files = findFiles(paths.scss, '.scss');

    for (const file of files) {
        await buildSassFile(file);
    }
}

/**
 * Build one JavaScript entry.
 *
 * @param {string} input Source JavaScript file.
 *
 * @returns {Promise<void>}
 */
async function buildJavaScriptFile(input) {

    const relative = path.relative(paths.js, input);

    const output = path.join(paths.jsOutput, relative.replace(/\.js$/, '.min.js'));

    await fs.ensureDir(path.dirname(output));

    await esbuild({
        entryPoints: [input],

        bundle: true,

        minify: true,

        sourcemap: false,

        platform: 'browser',

        target: ['es2019',],

        format: 'iife',

        outfile: output,

        legalComments: 'none',
    });

    console.log(`JS: ${path.relative(__dirname, output)}`);
}

/**
 * Build all JavaScript files.
 *
 * Every JavaScript entry receives its own production bundle.
 *
 * @returns {Promise<void>}
 */
async function buildJS() {

    const files = findFiles(paths.js, '.js');

    for (const file of files) {
        await buildJavaScriptFile(file);
    }
}

/**
 * Build the complete WooShop asset system.
 *
 * @returns {Promise<void>}
 */
async function build() {

    try {

        await prepareDirectories();

        await buildCSS();
        await buildJS();

        console.log('WooShop assets built successfully.');

    } catch (error) {

        console.error('WooShop asset build failed:', error);

        process.exitCode = 1;
    }
}

/**
 * Watch WooShop source assets.
 *
 * @returns {void}
 */
function watch() {

    const watcher = chokidar.watch([path.join(paths.scss, '**/*.scss'),

        path.join(paths.js, '**/*.js'),], {
        ignoreInitial: true,
    });

    watcher.on('change', async (file) => {

        console.log(`Changed: ${path.relative(__dirname, file)}`);

        await build();
    });

    console.log('WooShop asset watcher started.');
}

/**
 * Run the requested build mode.
 */
const command = process.argv[2];

if (command === 'watch') {

    await build();

    watch();

} else {

    await build();
}