<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Ne Hakkında Konu Açmak İstiyorsunuz</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="myForm" name="ilkform">
                    <input type="hidden" name="username" value="<?php if($oturum){echo $_SESSION['nickname'];}?>">

                    <?php if($oturum): ?>
                        <div class="form-group">
                            <label for="baslik">Konu Başlığı:</label>
                            <input type="text" class="form-control" id="baslik" name="baslik" required>
                        </div>
                    <?php else: ?>
                        <p>Başlık açmak için giriş yapmalısınız.</p>
                    <?php endif; ?>

                    <div class="form-group mt-3">
                        <label for="yorum">Yorum:</label>
                        <textarea class="form-control" id="yorum" name="yorum" style="height: 300px" required></textarea>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <?php if($oturum): ?>
                            <button type="button" id="submitForm" class="btn btn-primary">Başlık Ekle</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
                <div id="formResult" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>
