<?php
// Kullanıcının rolünü belirle (Örnek: admin, yönetici, yazar)
$oturum = isset($_SESSION['oturum']) && $_SESSION['oturum'] === true;
if($oturum){
    $userRole = $_SESSION['userRole']; // Bu değeri giriş yapan kullanıcıya göre dinamik yapabilirsin.
}else{
    $userRole = null;
}

// Navbar HTML başı
echo '
<nav class="navbar navbar-expand-lg bg-body-tertiary px-2">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">TatlıSözlük</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Anasayfa</a>
                </li>';

if ($userRole == 'yönetici' || $userRole == 'admin' || $userRole == 'yazar') {
    echo '
                <li class="nav-item">
                    <a class="nav-link" href="aktivite.php">Hesap Hareketliliği</a>
                </li>';
}

// Eğer kullanıcı yönetici veya admin ise, "Kullanıcı Listesi" linkini göster
if ($oturum == true && ($userRole == 'yönetici' || $userRole == 'admin')) {
    echo '
                <li class="nav-item">
                    <a class="nav-link" href="usertable.php">Üye Listesi</a>
                </li>';
}

// Eğer kullanıcı admin ise, "Admin Panel" linkini göster
if ($userRole == 'admin') {
    echo '
                <li class="nav-item">
                    <a class="nav-link" href="adminPanel.php">Admin Panel</a>
                </li>';
}
if($oturum){
    if ($_SESSION['whois'] == 1) {
        echo '
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Whois Sorgu </a>
                </li>';
    }
    if ($_SESSION['dns'] == 1) {
        echo '
                <li class="nav-item">
                    <a class="nav-link" href="index.php">DNS Sorgu</a>
                </li>';
    }
}


// Kayıt ol butonu veya profil bağlantısı
echo '
            </ul>
            <div class="d-flex">'; // Sağ tarafa yerleştirmek için d-flex ekledik.

if ($oturum) {
    echo '
                <a class="btn btn-primary me-2" href="profil.php">Profil</a>'; // Mavi buton
} else {
    echo '
                <a class="btn btn-success" href="login.php">Giriş yap</a>'; // Yeşil buton
}

echo '
            </div>
        </div>
    </div>
</nav>';
?>
