<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- FontAwesome ve Bootstrap CSS kütüphanelerini dahil eder -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="blog.css">
    <title>Ana Sayfa</title>
</head>
<body>
<?php
// Oturum başlatılır
session_start();

// Navbar dosyasını dahil eder
include "navbar.php";

// TopicClass sınıfı dahil edilir ve sınıf örneği oluşturulur
require_once 'TopicClass.php';
$maintopic = new TopicClass(); // Yeni bir TopicClass nesnesi oluşturulur
$oturum = isset($_SESSION['oturum']) && $_SESSION['oturum'] === true; // Oturum durumu kontrol edilir

?>
<div class="container mt-5">

    <!-- Başlık kısmı -->
    <div class="row">
        <div class="col-lg-9">
            <!-- Ana sayfa başlığı -->
            <h2 class="mb-4">Tatlı Sözlük İle Özgürce Konuş</h2>
        </div>
        <div class="col-lg-3">
            <!-- Başlık ekleme butonu ve modal -->
            <button type="button" class="btn btn-primary btn-lg mb-3" style="width: 80%" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Yeni Konu Aç
            </button>
            <!-- Yorum ekleme modal dosyasını dahil eder -->
            <?php include "newTopicModal.php";?>
        </div>
    </div>

    <div class="row">
        <!-- Sol tarafta Son Açılan Başlıklar bölümü -->
        <div class="col-lg-3 mb-3 bg-light py-3">
            <div class="p-3">
                <h5>Son Gelişmeler</h5>
                <ul class="list-group">
                    <?php
                    // Son açılan başlıklar listelenir
                    $topics = $maintopic->lastTopics();
                    foreach ($topics as $topic) {
                        ?>
                        <li class="list-group-item"><a href="topic.php?topic=<?php echo htmlspecialchars($topic); ?>"><?php echo htmlspecialchars($topic); ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Sağ tarafta içerik kısmı -->
        <div class="col-lg-9">
            <!-- Popüler Başlıklar bölümü -->
            <h2>Popüler Gündem</h2>
            <ul class="list-group">
                <?php
                // Popüler başlıklar ve yorum sayıları listelenir
                $toptopics = $maintopic->topTopics();
                foreach ($toptopics as $topiccom) {
                    ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="topic.php?topic=<?php echo $topiccom["Topic_name"]; ?>" class="text-decoration-none text-dark"><strong><?php echo $topiccom["Topic_name"]; ?></strong></a>
                        <span class="badge bg-secondary"><?php echo $topiccom["comment_count"]; ?></span>
                    </li>
                    <?php
                }
                ?>
            </ul>

            <!-- Son Yorumlar bölümü -->
            <h2 class="mt-5">Son Atılan Yorumlar</h2>
            <ul class="list-group">
                <?php
                // Son atılan yorumlar listelenir
                $lastcomments = $maintopic->lastComments();
                foreach ($lastcomments as $lastcom) {
                    ?>
                    <li class="list-group-item">
                        <a href="topic.php?topic=<?php echo $lastcom["Topic_name"]; ?>" class="text-decoration-none text-dark">
                            <strong><h4><?php echo $lastcom["Topic_name"]; ?></h4></strong>
                        </a>
                        <!-- Yorum ve kullanıcı bilgisi gösterilir -->
                        <p class="comment"><?php echo $lastcom["Comment"]; ?><span class="text-muted"><br> - <?php echo $lastcom["User_nickname"]." / ".$lastcom["comdate"];?></span></p>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<?php
// Footer dosyasını dahil eder
include "footer.php";
?>
<!-- Bootstrap, FontAwesome ve jQuery kütüphaneleri dahil edilir -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery ile form gönderme ve modal işlemleri -->
<script>
    $(document).ready(function() {
        // Formun gönderilmesini engelleyip AJAX ile verileri sunucuya yollar
        $('#submitForm').on('click', function(e) {
            e.preventDefault(); // Sayfa yenilenmesini engeller
            var formData = $('#myForm').serialize(); // Form verilerini alır

            // AJAX isteği ile form verilerini sunucuya yollar
            $.ajax({
                url: 'NewTopicJSON.php', // PHP dosyasına POST isteği yapılır
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Sunucudan gelen yanıta göre kullanıcıya mesaj gösterir
                    if (response.startsWith("Hata:")) {
                        $('#formResult').html('<div class="alert alert-danger">' + response + '</div>');
                    } else {
                        $('#formResult').html('<div class="alert alert-success">' + response + '</div>');
                        $('#myForm')[0].reset(); // Formu sıfırlar
                    }
                },
                error: function() {
                    $('#formResult').html('<div class="alert alert-danger">Bir hata oluştu!</div>');
                }
            });
        });

        // Modal kapandığında sayfayı yeniler
        $('#exampleModal').on('hidden.bs.modal', function () {
            location.reload(); // Sayfayı yenile
        });
    });
</script>
</body>
</html>
