import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('input', (event) => {
    if (!(event.target instanceof HTMLInputElement) || !event.target.matches('[data-table-search]')) {
        return;
    }

    const table = event.target.closest('.data-table-card')?.querySelector('table');
    const body = table?.tBodies[0];

    if (!body) {
        console.error('Table search must be placed inside a data table card with a table body.');
        return;
    }

    const query = event.target.value.trim().toLocaleLowerCase();
    const rows = Array.from(body.rows).filter((row) => !row.hasAttribute('data-search-empty') && !row.hasAttribute('data-empty-state'));
    let visibleRows = 0;

    rows.forEach((row) => {
        const matches = row.textContent.toLocaleLowerCase().includes(query);
        row.hidden = !matches;
        visibleRows += matches ? 1 : 0;
    });

    const emptyMessage = body.querySelector('[data-search-empty]');

    if (emptyMessage) {
        emptyMessage.hidden = query.length === 0 || visibleRows > 0;
    }
});
