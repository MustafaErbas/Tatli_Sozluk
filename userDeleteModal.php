<div class="modal fade" id="deleteUserModal-<?php echo $userin['userid']; ?>" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Kullanıcı Sil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo htmlspecialchars($userin['userad']) . ' ' . htmlspecialchars($userin['usersoyad']); ?> kullanıcısını ve onun tüm verilerini silmek istediğinizden emin misiniz?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                <form method="post" action="userDelete.php">
                    <input type="hidden" name="user_id" value="<?php echo $userin['userid']; ?>">
                    <button type="submit" class="btn btn-danger" name="delete_user">Sil</button>
                </form>
            </div>
        </div>
    </div>
</div>

