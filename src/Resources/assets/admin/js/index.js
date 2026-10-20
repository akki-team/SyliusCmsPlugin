import {HandleCsvUpload, HandleSlugUpdate, HandlePreview} from './bitbag';

if (document.querySelector('[data-bb-target="cms-import"]')) {
    new HandleCsvUpload().init();
}

if (document.querySelectorAll('[data-bb-target="cms-slug-update"]').length > 0) {
    new HandleSlugUpdate().init();
}

if (document.querySelectorAll('[data-bb-cms-preview-btn]').length > 0) {
    new HandlePreview().init();
}
