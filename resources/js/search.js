const searchInput = document.getElementById('user-search');
const resultsBox = document.getElementById('search-results');

if (searchInput && resultsBox) {
    let timer;
    let controller;

    const showStatus = (message) => {
        const status = document.createElement('p');
        status.className = 'p-3 text-sm text-gray-600 text-center';
        status.textContent = message;
        resultsBox.replaceChildren(status);
        resultsBox.classList.remove('hidden');
    };

    searchInput.addEventListener('input', () => {
        clearTimeout(timer);
        controller?.abort();
        resultsBox.replaceChildren();
        resultsBox.classList.add('hidden');
        const query = searchInput.value.trim();
        if (query.length < 2) return;

        timer = setTimeout(async () => {
            controller = new AbortController();
            const requestController = controller;
            showStatus('Searching…');
            try {
                const url = new URL(searchInput.dataset.searchUrl, window.location.origin);
                url.searchParams.set('query', query);
                const response = await fetch(url, {
                    signal: requestController.signal,
                    headers: { Accept: 'application/json' },
                });
                if (!response.ok) throw new Error('Search failed');
                const users = await response.json();
                if (requestController.signal.aborted || searchInput.value.trim() !== query) return;
                resultsBox.replaceChildren();
                if (users.length === 0) {
                    showStatus('No users found.');
                    return;
                }
                for (const user of users) {
                    const form = document.createElement('form');
                    form.action = `${searchInput.dataset.startUrl}/${user.id}`;
                    form.method = 'POST';
                    form.className = 'm-0 border-b border-gray-100 last:border-0';
                    const token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = document.querySelector('meta[name="csrf-token"]').content;
                    const button = document.createElement('button');
                    button.type = 'submit';
                    button.className = 'w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 focus-visible:outline-2 focus-visible:outline-teal-600 font-medium cursor-pointer break-words';
                    button.textContent = user.username;
                    form.append(token, button);
                    resultsBox.append(form);
                }
                resultsBox.classList.remove('hidden');
            } catch (error) {
                if (error.name !== 'AbortError' && !requestController.signal.aborted) {
                    showStatus('Could not search. Please try again.');
                }
            }
        }, 250);
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('.relative-search-container')) resultsBox.classList.add('hidden');
    });
    searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') resultsBox.classList.add('hidden');
    });
}
