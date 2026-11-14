(() => {
    const dialog = document.getElementById('adminDeleteDialog');
    const deleteName = document.getElementById('adminDeleteName');
    const deleteTitle = document.getElementById('adminDeleteTitle');
    const cancelButton = document.getElementById('adminDeleteCancel');
    const confirmButton = document.getElementById('adminDeleteConfirm');

    if (!dialog || !deleteName || !deleteTitle || !cancelButton || !confirmButton) {
        return;
    }

    let pendingForm = null;

    document.querySelectorAll('form[data-delete-confirmation]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === 'true') {
                return;
            }

            if (typeof dialog.showModal !== 'function') {
                if (!window.confirm(`Delete "${form.dataset.deleteName}"?`)) {
                    event.preventDefault();
                }

                return;
            }

            event.preventDefault();
            pendingForm = form;
            deleteName.textContent = form.dataset.deleteName;
            deleteTitle.textContent = `Delete this ${form.dataset.deleteType}?`;
            dialog.showModal();
        });
    });

    cancelButton.addEventListener('click', () => dialog.close());

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) {
            return;
        }

        const form = pendingForm;
        form.dataset.confirmed = 'true';
        dialog.close();
        form.requestSubmit();
    });

    dialog.addEventListener('close', () => {
        pendingForm = null;
    });
})();
