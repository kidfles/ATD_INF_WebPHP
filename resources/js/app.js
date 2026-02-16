/**
 * Dit is de hoofd-ingang voor de JavaScript-assets van de applicatie.
 * Hier laden we de bootstrap-configuratie en initialiseren we Alpine.js
 * voor reactieve UI-componenten.
 */

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
