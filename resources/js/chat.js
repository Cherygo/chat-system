const userId = Number(document.querySelector('meta[name="user-id"]')?.content);
const messages = document.getElementById('chat-messages');
const chatList = document.getElementById('chat-list');
const chatForm = document.getElementById('chat-form');

const element = (tag, className, text) => {
    const node = document.createElement(tag);
    node.className = className;
    if (text !== undefined) node.textContent = text;
    return node;
};

const formatTime = (timestamp) => new Date(timestamp).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
for (const time of document.querySelectorAll('[data-message-time]')) {
    time.textContent = formatTime(time.dateTime);
}
if (messages) messages.scrollTop = messages.scrollHeight;
if (chatForm) {
    const button = document.getElementById('submit-btn');
    chatForm.addEventListener('submit', () => {
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
    });
    window.addEventListener('pageshow', () => {
        button.disabled = false;
        button.classList.remove('opacity-50', 'cursor-not-allowed');
    });
}

if (userId && window.Echo) {
    if (chatList) {
        window.Echo.private(`user.${userId}`).listen('.chat.created', ({ chat }) => {
            if (chatList.querySelector(`[data-chat-id="${Number(chat.id)}"]`)) return;
            chatList.querySelector('[data-empty-chats]')?.remove();
            const link = element('a', 'block p-4 border-b border-gray-100 hover:bg-gray-100 transition duration-150');
            link.href = `/chat/${Number(chat.id)}`;
            link.dataset.chatId = chat.id;
            const title = chat.is_group ? chat.name : chat.users.find((user) => Number(user.id) !== userId)?.username ?? 'New Chat';
            link.append(element('span', 'font-semibold text-gray-900 block truncate', title));
            link.append(element('p', 'text-sm text-gray-600', 'No messages yet.'));
            chatList.prepend(link);
        });
    }
    if (messages) {
        window.Echo.private(`chat.${messages.dataset.chatId}`).listen('.message.sent', ({ message }) => {
            if (Number(message.user_id) === userId || messages.querySelector(`[data-message-id="${Number(message.id)}"]`)) return;
            const row = element('div', 'flex justify-start');
            row.dataset.messageId = message.id;
            const wrapper = element('div', 'flex items-end gap-2 max-w-[min(28rem,85%)] min-w-0');
            const sender = message.sender?.username ?? 'Unknown user';
            wrapper.append(element('div', 'w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold shrink-0', Array.from(sender)[0]?.toUpperCase() ?? 'U'));
            const bubble = element('div', 'bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-tl-sm shadow-sm min-w-0');
            if (messages.dataset.isGroup === 'true') {
                bubble.append(element('span', 'text-xs font-bold text-teal-700 block mb-1 break-words', sender));
            }
            bubble.append(element('p', 'text-sm whitespace-pre-wrap break-words', message.content));
            const time = element('time', 'text-xs text-gray-600 mt-1 block', formatTime(message.created_at));
            time.dateTime = message.created_at;
            bubble.append(time);
            wrapper.append(bubble);
            row.append(wrapper);
            messages.append(row);
            messages.scrollTop = messages.scrollHeight;
            const preview = chatList?.querySelector(`[data-chat-id="${Number(message.chat_id)}"] p`);
            if (preview) preview.textContent = message.content;
        });
    }
}
