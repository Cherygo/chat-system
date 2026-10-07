const modal = document.getElementById('groupModal');
const openButton = document.getElementById('openGroupModal');
const closeButton = document.getElementById('closeGroupModal');

if (modal && openButton && closeButton) {
    openButton.addEventListener('click', () => modal.showModal());
    closeButton.addEventListener('click', () => modal.close());
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            const bounds = modal.getBoundingClientRect();
            if (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom) {
                modal.close();
            }
        }
    });
    modal.addEventListener('close', () => openButton.focus());
    if (modal.dataset.hasErrors === 'true') modal.showModal();
}
