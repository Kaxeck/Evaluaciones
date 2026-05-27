// Client-side search across sections: filters table rows in the current page.
(function () {
    const input = document.getElementById('global-search-input');
    const button = document.getElementById('global-search-btn');
    if (!input) return;

    function debounce(fn, wait) {
        let t;
        return function (...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    function textMatchesAllTokens(text, tokens) {
        const hay = text.toLowerCase();
        return tokens.every(token => hay.indexOf(token) !== -1);
    }

    function filterTables(query) {
        const tokens = query.trim().toLowerCase().split(/\s+/).filter(Boolean);
        const tables = Array.from(document.querySelectorAll('.table-responsive table.table'));

        if (tables.length === 0) return;

        tables.forEach(table => {
            const tbody = table.tBodies[0];
            if (!tbody) return;

            const rows = Array.from(tbody.rows);
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent || '';
                const match = tokens.length === 0 || textMatchesAllTokens(text, tokens);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            // Handle no-results row (create if necessary)
            const noResultsId = 'no-results-' + (table.id || Math.random().toString(36).slice(2,8));
            let noRow = table.querySelector('tbody tr[data-no-results]');
            if (visibleCount === 0) {
                if (!noRow) {
                    noRow = document.createElement('tr');
                    noRow.setAttribute('data-no-results', '1');
                    const cols = table.tHead ? table.tHead.rows[0].cells.length : table.querySelectorAll('tr')[0]?.cells.length || 1;
                    const td = document.createElement('td');
                    td.setAttribute('colspan', cols);
                    td.className = 'text-center text-muted py-3';
                    td.textContent = 'No se encontraron resultados.';
                    noRow.appendChild(td);
                    tbody.appendChild(noRow);
                }
            } else {
                if (noRow) noRow.remove();
            }
        });
    }

    const doFilter = debounce((e) => {
        const q = (e && e.target) ? e.target.value : input.value;
        filterTables(q);
    }, 220);

    input.addEventListener('input', doFilter);
    input.addEventListener('search', doFilter);

    // Button triggers search immediately
    if (button) {
        button.addEventListener('click', () => filterTables(input.value || ''));
    }

    // Run once on load to apply empty filter (show all) or persisted query in URL
    document.addEventListener('DOMContentLoaded', () => {
        // If the input has any initial value (from server), run filter
        if (input.value && input.value.trim().length > 0) {
            filterTables(input.value);
        }
    });
})();

// Sidebar mobile toggle: opens the fixed sidebar as an off-canvas panel on small screens.
(function () {
    document.addEventListener('DOMContentLoaded', () => {
        const toggleButton = document.querySelector('[data-sidebar-toggle]');
        const backdrop = document.querySelector('[data-sidebar-backdrop]');
        const sidebar = document.querySelector('.sidebar-fijo');

        if (!toggleButton || !backdrop || !sidebar) return;

        const closeSidebar = () => {
            document.body.classList.remove('sidebar-open');
            toggleButton.setAttribute('aria-expanded', 'false');
        };

        const openSidebar = () => {
            document.body.classList.add('sidebar-open');
            toggleButton.setAttribute('aria-expanded', 'true');
        };

        toggleButton.addEventListener('click', () => {
            if (document.body.classList.contains('sidebar-open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });

        backdrop.addEventListener('click', closeSidebar);

        sidebar.querySelectorAll('a.nav-link, .dropdown-item').forEach((element) => {
            element.addEventListener('click', () => {
                if (window.innerWidth <= 991.98) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 991.98) {
                closeSidebar();
            }
        });
    });
})();
