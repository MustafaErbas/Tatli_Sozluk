<div class="modal fade" id="userInfoModal-<?php echo $userin['userid']; ?>" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userInfoModalLabel">Kullanıcı Bilgileri - <?php echo htmlspecialchars($userin['userad']) . ' ' . htmlspecialchars($userin['usersoyad']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong>Ad:</strong> <?php echo htmlspecialchars($userin['userad']); ?>
                </div>
                <div class="mb-3">
                    <strong>Soyad:</strong> <?php echo htmlspecialchars($userin['usersoyad']); ?>
                </div>
                <div class="mb-3">
                    <strong>Nickname:</strong> <?php echo htmlspecialchars($userin['nickname']); ?>
                </div>
                <div class="mb-3">
                    <strong>E-posta:</strong> <?php echo htmlspecialchars($userin['usermail']); ?>
                </div>
                <div class="mb-3">
                    <strong>Rol:</strong> <?php echo htmlspecialchars($userin['userrole']); ?>
                </div>
                <div class="mb-3">
                    <strong>Kaydolma Tarihi:</strong> <?php echo htmlspecialchars($userin['userdate']); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
