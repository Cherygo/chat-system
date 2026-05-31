    document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('groupModal');
    const openBtn = document.getElementById('openGroupModal');
    const closeBtn = document.getElementById('closeGroupModal');

    openBtn.addEventListener('click', () => {
    modal.classList.remove('hidden');
});

    closeBtn.addEventListener('click', () => {
    modal.classList.add('hidden');
});

    window.addEventListener('click', (e) => {
    if (e.target === modal) {
    modal.classList.add('hidden');
}
});
});

    const searchInput = document.getElementById('user-search');
    const resultsBox = document.getElementById('search-results');

    if (searchInput) {
    searchInput.addEventListener('input', function() {
        let query = this.value;

        if(query.length < 2) {
            resultsBox.classList.add('hidden');
            return;
        }

        fetch(`/chat/api/search?query=${query}`)
            .then(response => response.json())
            .then(users => {
                resultsBox.innerHTML = '';

                if(users.length === 0) {
                    resultsBox.innerHTML = '<div class="p-3 text-sm text-gray-500 text-center">No users found.</div>';
                } else {
                    users.forEach(user => {
                        resultsBox.innerHTML += `
                                <form action="/chat/start/${user.id}" method="POST" class="m-0 border-b border-gray-100 last:border-0">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition duration-150 font-medium cursor-pointer">
                                        ${user.username}
                                    </button>
                                </form>
                            `;
                    });
                }
                resultsBox.classList.remove('hidden');
            });
    });

    // hide dropdown when clicking outside of it
    document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative-search-container')) {
    resultsBox.classList.add('hidden');
}
});
}


    const userIdTag = document.head.querySelector('meta[name="user-id"]');

    if (userIdTag) {
        const userId = userIdTag.content;

        window.Echo.private('user.' + userId)
            .listen('ChatCreated', (e) => {
                console.log('I was just added to a new chat!', e.chat);

                window.Echo.private('user.' + userId)
                    .listen('ChatCreated', (e) => {

                        console.log('I was just added to a new chat!', e.chat);
                        const chatList = document.getElementById('your-sidebar-id');

                        const newChatHTML = `
            <a href="/chat/${e.chat.id}" class="block p-3 hover:bg-gray-100 border-b">
                <div class="font-bold">${e.chat.is_group ? e.chat.name : 'New Chat'}</div>
                <div class="text-sm text-gray-500">Just created!</div>
            </a>
        `;
                        if (chatList) {
                            chatList.insertAdjacentHTML('beforeend', newChatHTML);
                        }
                    });
            });
    } else {
        console.log('No user-id meta tag found. User is likely not logged in.');
    }

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
