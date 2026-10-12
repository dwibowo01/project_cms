import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Swaps a server-rendered inline-editable section (identified by its element id)
// with freshly rendered HTML after an add/edit/delete, keeping the server as the
// single source of truth for computed values (outline codes, totals, PIC cascades).
window.refreshSection = function (elementId, html) {
    const el = document.getElementById(elementId);
    if (el) {
        el.outerHTML = html;
    }
};

// Back-compat alias used by the Quotation items table.
window.refreshItemsSection = function (html) {
    window.refreshSection('items-section', html);
};

Alpine.start();
