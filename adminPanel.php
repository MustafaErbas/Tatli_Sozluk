<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="blog.css">
    <title>Admin Panel</title>
</head>
<body>
<?php
session_start();
include "navbar.php";
$oturum = isset($_SESSION['oturum']) && $_SESSION['oturum'] === true;
if($oturum){
    $userRole = $_SESSION['userRole'];
}else{
    $userRole = null;
}
if ($userRole != 'admin') {
    header("Location:index.php");
    exit;
}
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mb-3 bg-light py-3">
            <div class="p-3">
                <!-- Kayıtlı kullanıcıların listelendiği bölüm -->
                <h5>Kayıtlı Kullanıcılar</h5>
                <ul class="list-group">
                    <?php
                    require_once 'User.php';
                    require_once 'Admin.php';
                    $admin = new Admin();
                    $row=$admin->showUserList();
                    foreach ($row as $users) {
                        ?>
                        <li class="list-group-item"><a href="?users=<?php echo $users["userid"] ?>"><?php echo $users["nickname"] ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>

        <div class="col-md-8 p-3 mt-3">
            <?php
            // Eğer bir kullanıcı seçildiyse bilgilerini gösteriyoruz
            if (isset($_GET['users'])) {
                $userId = $_GET['users'];
                $userbyadmin = new User();
                $infouser=$userbyadmin->showinfo($userId);
                if ($infouser) {
                    ?>
                        <h5>Kullanıcı Bilgileri</h5>
                    <form method="post" class="border border-black rounded p-5" id="infoform">
                        <input type="hidden" name="infoform" value="<?php echo $userId; ?>">
                        <p><strong>Ad:</strong> <input required style="width: 80%" type="text" name="ad" value="<?php echo $infouser["user_name"]; ?>"></p>
                        <p><strong>Soyad:</strong> <input required style="width: 80%" type="text" name="soyad" value="<?php echo $infouser["user_surname"]; ?>"></p>
                        <p><strong>E-Mail:</strong> <input required style="width: 80%" type="email" name="mail" value="<?php echo $infouser["user_mail"]; ?>"></p>
                        <p><strong>Kullanıcı Ad:</strong> <input required style="width: 80%" type="text" name="nickname" value="<?php echo $infouser["nickname"]?>"></p>
                        <p><strong>Şifre:</strong> <input required style="width: 80%" pattern="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,}$" title="Lütfen en az 8 karakter kullanınız!! En az bir sayı, bir küçük harf ve bir büyük harf içermelidir. Boşluk içeremez." type="text" name="sifre" value="<?php echo $infouser["user_password"]; ?>"></p>
                        <div align="right">
                            <button type="submit" name="updateBtn" class="btn btn-primary">Güncelle</button>
                        </div>
                    </form>
                    <?php
                } else {
                    echo "<p>Kullanıcı bilgisi bulunamadı.</p>";
                }?>

                <!-- Site Aktifliğini gösteren bölüm -->
                <h5 class="mt-3">Site Aktifliği</h5>
                <div class="border border-black rounded p-5">
                    <p><strong>Yorum Sayısı:</strong> <?php echo $userbyadmin->comCount($userId) ?> </p>
                    <p><strong>Açtığı Başlık Sayısı:</strong> <?php echo $userbyadmin->topicCount($userId) ?> </p>
                </div>
                <?php
                if($userId!=1){
                    ?>
                    <!-- ID=1 olan admin kullanıcısı dışında olan kişilere yetkilendirme penceresi gözükür -->
                    <!-- site rolü ve yetki verme bölümü -->
                    <h5 class="mt-3">Yetkilendirme</h5>
                    <div class="border border-black rounded p-5">
                        <form method="POST" id="roleform">
                            <input type="hidden" name="roleform" value="<?php echo $userId; ?>">
                            <div class="mb-3">
                                <label for="role" class="form-label">Yetki Seçin</label>
                                <div>
                                    <input type="radio" id="admin" name="role" value="admin" <?php if($infouser['userRole'] == 'admin') echo 'checked'; ?> >
                                    <label for="admin">Admin</label>
                                </div>
                                <div>
                                    <input type="radio" id="yönetici" name="role" value="yönetici" <?php if($infouser['userRole'] == 'yönetici') echo 'checked'; ?> >
                                    <label for="yönetici">Yönetici</label>
                                </div>
                                <div>
                                    <input type="radio" id="yazar" name="role" value="yazar" <?php if($infouser['userRole'] == 'yazar') echo 'checked'; ?> >
                                    <label for="yazar">Yazar</label>
                                </div>
                            </div>
                            <button type="submit" name="updateRole" class="btn btn-primary">Rol Güncelle</button>
                        </form>
                        <hr>
                        <form method="post" id="yetkiform">
                            <input type="hidden" name="yetkiform" value="<?php echo $userId; ?>">
                            <div class="form-check">
                                <input class="form-check-input bg-secondary" type="checkbox" name="whois" id="whois" <?php echo ($infouser['whois'] ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="whois">
                                    WhoIs Sorgu
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input bg-secondary" type="checkbox" name="dns" id="dns" <?php echo ($infouser['dns'] ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="dns">
                                    DNS Sorgu
                                </label>
                            </div>
                            <div class="mt-3">
                                <button type="submit" name="updatePrivileges" class="btn btn-primary">Yetki Güncelle</button>
                            </div>
                        </form>
                    </div>
                        <?php
                }
                ?>
        <?php }
            ?>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Genel form submit işlemi için bir fonksiyon tanımla
        function ajaxFormSubmit(formId, successMessage) {
            $(formId).on('submit', function(e) {
                e.preventDefault(); // Formun varsayılan gönderimini engelle
                $.ajax({
                    type: 'POST',
                    url: 'adminJSON.php', // Form gönderim URL'si
                    data: $(this).serialize(), // Form verilerini al
                    success: function(response) {
                        console.log(response); // Yanıtı konsola yazdır
                        location.reload(); // Sayfayı yenile
                        try {
                            var jsonResponse = JSON.parse(response);
                            alert(jsonResponse.message || successMessage);
                        } catch (error) {
                            alert(successMessage);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Hata mesajını göster
                        alert("İşlem sırasında bir hata oluştu: " + error);
                    }
                });
            });
        }
        // Farklı formlar için aynı fonksiyonu kullanarak işlem yap
        ajaxFormSubmit('#infoform', 'Bilgiler başarıyla güncellendi.');
        ajaxFormSubmit('#roleform', 'Rol başarıyla güncellendi.');
        ajaxFormSubmit('#yetkiform', 'Yetki başarıyla güncellendi.');
    });
</script>
</body>
</html>

