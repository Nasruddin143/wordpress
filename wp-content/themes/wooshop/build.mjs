import {build} from 'esbuild';
import {execSync} from 'child_process';
import chokidar from 'chokidar';
import fs from 'fs-extra';
import path from 'path';

const watch = process.argv.includes('--watch');

const paths = {
    css: {
        src: 'assets/src/css/app.css',
        dest: 'assets/build/css'
    },

    js: {
        src: 'assets/src/js/app.js',
        dest: 'assets/build/js'
    },

    images: {
        src: 'assets/src/images',
        dest: 'assets/build/images'
    },

    fonts: {
        src: 'assets/src/fonts',
        dest: 'assets/build/fonts'
    }
};

/**
 * Build CSS.
 */
function buildCSS() {

    console.log('Building CSS...');

    fs.ensureDirSync(paths.css.dest);

    execSync(
        `npx postcss "${paths.css.src}" -o "${paths.css.dest}/app.min.css"`,

        {
            stdio: 'inherit'
        }
    );

}

/**
 * Build JavaScript.
 */
async function buildJS() {

    console.log('Building JS...');

    fs.ensureDirSync(paths.js.dest);

    await build({

        entryPoints: [

            paths.js.src

        ],

        outfile: path.join(
            paths.js.dest,

            'app.min.js'
        ),

        bundle: true,

        minify: true,

        sourcemap: watch,

        target: 'es2018'

    });

}

/**
 * Copy assets.
 */
function copyAssets() {

    console.log('Copying assets...');

    if (fs.existsSync(paths.images.src)) {

        fs.copySync(
            paths.images.src,
            paths.images.dest,
            { overwrite: true }
        );

    }

    if (fs.existsSync(paths.fonts.src)) {

        fs.copySync(
            paths.fonts.src,
            paths.fonts.dest,
            { overwrite: true }
        );

    }

}

/**
 * Build everything.
 */
async function compile() {

    buildCSS();

    await buildJS();

    copyAssets();

    console.log('WooShop build complete.');

}

/**
 * Watch mode.
 */
if (watch) {

    compile();

    chokidar.watch('assets/src').on(
        'all',

        async () => {

            console.clear();

            await compile();

        }
    );

} else {

    compile();

}