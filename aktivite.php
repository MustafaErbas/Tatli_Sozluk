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
    <title>Aktivite</title>
</head>
<body>
<?php
session_start(); // Oturum başlatılıyor
include "navbar.php"; // Navbar dosyası dahil ediliyor
require_once 'TopicClass.php'; // TopicClass dosyası dahil ediliyor
$maintopic = new TopicClass(); // TopicClass sınıfından nesne oluşturuluyor

// Oturum kontrolü, kullanıcı giriş yapmamışsa index.php'ye yönlendiriliyor
$oturum = isset($_SESSION['oturum']) && $_SESSION['oturum'] === true;
if ($oturum == false) {
    header("Location:index.php");
    exit;
}

// Eğer yönetici veya admin kullanıcı başka bir kullanıcıyı incelemek isterse
if(isset($_GET['userid']) && ($_SESSION['userRole']=='yönetici'||$_SESSION['userRole']=='admin')) {
    $userid = $_GET['userid']; // İncelenecek kullanıcı ID'si
}
// Eğer kullanıcı yönetici ya da admin değilse bu sayfaya erişmesi engelleniyor
elseif (isset($_GET['userid']) && ($_SESSION['userRole']!='yönetici'||$_SESSION['userRole']!='admin')){
    header("Location:index.php");
    exit;
}
// Eğer başka bir kullanıcı inceleme modunda değilse, kullanıcı kendi bilgileriyle sayfa görüntülenir
else{
    $userid = $_SESSION['user_ID'];
    $ad = $_SESSION['nickname'];
}
?>

<div class="container mt-5">
    <!-- Başlık kısmı -->
    <div class="row">
        <div class="col-lg-9">
            <h2 class="mb-4"><strong><?php echo $_SESSION['nickname'] ?></strong> Hesabının Hareketliliği</h2>
        </div>
    </div>

    <div class="row">
        <!-- Sol tarafta Son Açılan Başlıklar divi -->
        <div class="col-lg-3 mb-3 bg-light py-3">
            <div class="p-3">
                <h5>Son Gelişmeler</h5>
                <ul class="list-group">
                    <?php
                    // Son açılan başlıklar fonksiyonu çağrılarak başlıklar listeleniyor
                    $topics = $maintopic->lastTopics();
                    foreach ($topics as $topic) {
                        ?>
                        <li class="list-group-item">
                            <a href="topic.php?topic=<?php echo htmlspecialchars($topic); ?>">
                                <?php echo htmlspecialchars($topic); ?>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Sağ tarafta içerik kısmı -->
        <div class="col-lg-9">
            <!-- Kullanıcının açtığı başlıklar -->
            <h2>Açtığınız Başlıklar</h2>
            <ul class="list-group">
                <?php
                // Kullanıcının açtığı başlıklar fonksiyonu çağrılarak başlıklar listeleniyor
                $usertopics = $maintopic->userTopics($userid);
                if($usertopics == null){
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <p>Açtığınız bir başlık bulunmuyor</p>
                    </li>
                    <?php
                }else{
                    // Başlıkların her biri listeleniyor
                    foreach ($usertopics as $usertop) {
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="topic.php?topic=<?php echo $usertop["Topic_name"]; ?>" class="text-decoration-none text-dark">
                                <strong><?php echo $usertop["Topic_name"]; ?></strong>
                            </a>
                            <span class="badge bg-secondary"><?php echo $usertop["comment_count"]; ?></span>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>

            <!-- Kullanıcının attığı yorumlar bölümü -->
            <h2 class="mt-5">Attığınız Yorumlar</h2>
            <ul class="list-group">
                <?php
                // Kullanıcının attığı yorumlar fonksiyonu çağrılarak yorumlar listeleniyor
                $usercomments = $maintopic->userComments($userid);
                if($usercomments == null){
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <p>Herhangi bir başlığa yorum yapmamışınız</p>
                    </li>
                    <?php
                }else{
                    // Yorumlar listeleniyor ve eğer yönetici/admin ise silme butonu ekleniyor
                    foreach ($usercomments as $usercom){
                        ?>
                        <li class="list-group-item">
                            <a href="topic.php?topic=<?php echo $usercom["Topic_name"]; ?> " class="text-decoration-none text-dark">
                                <strong><h4><?php echo $usercom["Topic_name"]; ?></h4></strong>
                            </a>
                            <p class="comment"><?php echo $usercom["Comment"]; ?>
                                <span class="text-muted"><br> - <?php echo $usercom["comdate"];?></span>
                            </p>
                            <?php
                            if(isset($_GET['userid']) && ($_SESSION['userRole']=='yönetici'||$_SESSION['userRole']=='admin')){
                                ?>
                                <!-- Yorum silme formu -->
                                <form method='POST' action='deleteComment.php' style='display:inline;' onsubmit="return confirm('Bu yorumu silmek istediğinize emin misiniz?');">
                                    <input type='hidden' name='comment_id' value='<?php echo $usercom["comid"]; ?>'>
                                    <button type='submit' name='delete_comment' class='btn btn-danger'>Sil</button>
                                </form>
                                <?php
                            }
                            ?>
                        </li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<?php
// Footer dosyası dahil ediliyor
include "footer.php";
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
