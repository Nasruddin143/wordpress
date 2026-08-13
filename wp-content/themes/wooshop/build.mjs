import fs from 'fs-extra';
import path from 'path';
import {fileURLToPath} from 'url';
import chokidar from 'chokidar';
import * as sass from 'sass';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import {build as esbuild} from 'esbuild';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

/**
 * ============================================================
 * WooShop Build Configuration
 * ============================================================
 */

const PATHS = {
    src: path.resolve(__dirname, 'assets/src'),
    build: path.resolve(__dirname, 'assets/build'),

    scss: path.resolve(__dirname, 'assets/src/scss'),
    css: path.resolve(__dirname, 'assets/build/css'),

    js: path.resolve(__dirname, 'assets/src/js'),
    jsBuild: path.resolve(__dirname, 'assets/build/js'),

    appScss: path.resolve(__dirname, 'assets/src/scss/app.scss'),
    appCss: path.resolve(__dirname, 'assets/build/css/app.min.css'),

    appJs: path.resolve(__dirname, 'assets/src/js/app.js'),
    appJsBuild: path.resolve(__dirname, 'assets/build/js/app.min.js'),

    nodeModules: path.resolve(__dirname, 'node_modules'),
};

/**
 * ============================================================
 * Ensure Build Directories
 * ============================================================
 */

async function ensureDirectories() {
    await fs.ensureDir(PATHS.build);
    await fs.ensureDir(PATHS.css);
    await fs.ensureDir(PATHS.jsBuild);
}

/**
 * ============================================================
 * Compile SCSS
 * ============================================================
 */

async function compileSCSS() {
    console.log('🎨 Compiling SCSS...');

    try {
        const result = await sass.compileAsync(PATHS.appScss, {
            style: 'expanded',

            sourceMap: false,

            // Bootstrap is resolved from node_modules.
            loadPaths: [PATHS.nodeModules,],

            quietDeps: true,
        });

        const processed = await postcss([autoprefixer(), cssnano({
            preset: 'default',
        }),]).process(result.css, {
            from: PATHS.appScss, to: PATHS.appCss,
        });

        await fs.writeFile(PATHS.appCss, processed.css, 'utf8');

        console.log(`✅ CSS → ${path.relative(__dirname, PATHS.appCss)}`);
    } catch (error) {
        console.error('❌ SCSS compilation failed.');

        if (error?.formatted) {
            console.error(error.formatted);
        } else {
            console.error(error);
        }

        throw error;
    }
}

/**
 * ============================================================
 * Compile JavaScript
 * ============================================================
 */

async function compileJS() {
    console.log('⚡ Compiling JavaScript...');

    try {
        await esbuild({
            entryPoints: [PATHS.appJs], outfile: PATHS.appJsBuild,

            bundle: true, minify: true,

            sourcemap: false,

            platform: 'browser', format: 'iife',

            target: ['es2018',],

            legalComments: 'none',
        });

        console.log(`✅ JS → ${path.relative(__dirname, PATHS.appJsBuild)}`);
    } catch (error) {
        console.error('❌ JavaScript compilation failed.');
        console.error(error);

        throw error;
    }
}

/**
 * ============================================================
 * Full Build
 * ============================================================
 */

async function buildAll() {
    console.log('');
    console.log('======================================');
    console.log(' WooShop Asset Build');
    console.log('======================================');
    console.log('');

    await ensureDirectories();

    await compileSCSS();
    await compileJS();

    console.log('');
    console.log('🚀 WooShop build completed.');
    console.log('');
}

/**
 * ============================================================
 * Watch Mode
 * ============================================================
 */

function watch() {
    console.log('');
    console.log('👀 WooShop watch mode enabled...');
    console.log('');

    const scssWatcher = chokidar.watch(PATHS.scss, {
        ignoreInitial: true,
    });

    const jsWatcher = chokidar.watch(PATHS.js, {
        ignoreInitial: true,
    });

    let scssBuilding = false;
    let jsBuilding = false;

    scssWatcher.on('all', async (event, file) => {
        if (scssBuilding) {
            return;
        }

        console.log(`\n🎨 SCSS changed: ${path.relative(__dirname, file)}`);

        scssBuilding = true;

        try {
            await compileSCSS();
        } catch (error) {
            console.error(error);
        } finally {
            scssBuilding = false;
        }
    });

    jsWatcher.on('all', async (event, file) => {
        if (jsBuilding) {
            return;
        }

        console.log(`\n⚡ JS changed: ${path.relative(__dirname, file)}`);

        jsBuilding = true;

        try {
            await compileJS();
        } catch (error) {
            console.error(error);
        } finally {
            jsBuilding = false;
        }
    });

    process.on('SIGINT', () => {
        console.log('\n🛑 Stopping watch mode...');

        scssWatcher.close();
        jsWatcher.close();

        process.exit(0);
    });
}

/**
 * ============================================================
 * CLI
 * ============================================================
 */

const command = process.argv[2];

if (command === 'watch') {
    await buildAll();
    watch();
} else {
    await buildAll();
}