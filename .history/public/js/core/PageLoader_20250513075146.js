import { MAIN_ID, ROUTER_PATH } from '../config.js';

console.log('PageLoader.js chargé');

let MAIN_CONTAINER = null;

document.addEventListener('DOMContentLoaded', () => {
    MAIN_CONTAINER = document.getElementById(MAIN_ID);
    console.log('MAIN_CONTAINER:', MAIN_CONTAINER);
    if (!MAIN_CONTAINER) {
        console.error('MAIN_CONTAINER non trouvé avec l\'ID:', MAIN_ID);
    }
});

async function fileExists(filepath)