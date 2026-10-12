import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Swaps the quotation items table with freshly server-rendered HTML after an inline add/edit/delete.
window.refreshItemsSection = function (html) {
    const el = document.getElementById('items-section');
    if (el) {
        el.outerHTML = html;
    }
};

Alpine.start();
