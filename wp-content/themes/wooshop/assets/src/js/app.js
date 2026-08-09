/**
 * WooShop Frontend Application
 *
 * @package WooShop
 */

/*
 * ==========================================
 * Utilities
 * ==========================================
 */

import './utils/utils.js';
import './utils/dom.js';
import './utils/events.js';
import './utils/debounce.js';
import './utils/throttle.js';
import './utils/storage.js';


/*
 * ==========================================
 * Components
 * ==========================================
 */

import './components/announcement.js';
import './components/header.js';
import './components/navigation.js';
import './components/mobile-menu.js';
import './components/dropdown.js';

import './components/search.js';
import './components/categories.js';

import './components/drawer.js';
import './components/modal.js';

import './components/newsletter.js';
import './components/spinner.js';
import './components/tabs.js';

import './components/sticky-header.js';
import './components/back-to-top.js';


/*
 * ==========================================
 * Modules
 * ==========================================
 */

import './modules/navigation.js';
import './modules/search.js';
import './modules/search-toggle.js';
import './modules/offcanvas.js';
import './modules/sticky-header.js';


/*
 * ==========================================
 * Sliders
 * ==========================================
 */

import './sliders/embla.js';


/*
 * ==========================================
 * Pages
 * ==========================================
 */

import './pages/home.js';
import './pages/search.js';

document.addEventListener('DOMContentLoaded', () => {

    document.documentElement.classList.add('ws-ready');

});