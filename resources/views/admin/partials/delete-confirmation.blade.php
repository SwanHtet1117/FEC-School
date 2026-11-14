<dialog class="admin-delete-dialog" id="adminDeleteDialog" aria-labelledby="adminDeleteTitle" aria-describedby="adminDeleteDescription">
    <div class="admin-delete-dialog-heading">
        <span class="admin-delete-dialog-icon"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></span>
        <div>
            <h2 id="adminDeleteTitle">Delete this item?</h2>
            <p>This action cannot be undone.</p>
        </div>
    </div>
    <p class="admin-delete-dialog-description" id="adminDeleteDescription">
        You are about to delete <strong id="adminDeleteName"></strong>.
    </p>
    <div class="admin-delete-dialog-actions">
        <button class="admin-delete-dialog-cancel" id="adminDeleteCancel" type="button" autofocus>Cancel</button>
        <button class="admin-delete-dialog-confirm" id="adminDeleteConfirm" type="button">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i> Delete permanently
        </button>
    </div>
</dialog>
