/**
 * WooShop PostCSS Configuration
 *
 * Processes compiled Sass through Autoprefixer and cssnano.
 *
 * @package WooShop
 */

import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';

export default {
    plugins: [
        autoprefixer(),
        cssnano({
            preset: 'default',
        }),
    ],
};