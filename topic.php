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
    <title>Konu Detayları</title>
</head>
<body>
<?php
session_start();
include "navbar.php";
require_once 'TopicClass.php';
$oturum = isset($_SESSION['oturum']) && $_SESSION['oturum'] === true;
if(isset($_GET['topic'])) {
    $selectedtopic = $_GET['topic'];
}else{
    $selectedtopic = "eğitim";
}
$maintopic = new TopicClass();

?>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-9">
            <h2 class="mb-4">Bu Konu Hakkında Tüm Yorumlar</h2>
        </div>
        <div class="col-lg-3"></div>
    </div>

    <div class="row">
        <!-- Sol tarafta Son Açılan Başlıklar divi -->
        <div class="col-lg-3 mb-3 bg-light py-3">
            <div class="p-3">
                <h5>Son Gelişmeler</h5>
                <ul class="list-group">
                    <?php
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
        <div class="col-lg-9">
            <div>
                <?php
                $topicinfos = $maintopic->topicinfo($selectedtopic);
                $formatted_date = date("d-m-Y", strtotime($topicinfos['created_date']));
                ?>
                <h2 class="mt-3 comment"><?php echo htmlspecialchars($selectedtopic); ?></h2>
                <div>Created by: <?php echo $topicinfos['nickname']?></div>
                <div>Date: <?php echo $formatted_date?></div>
            </div>
            <div class="row">

                <div class="col-lg-4">
                    <!-- Yorum ekleme formu -->
                    <?php if ($oturum): ?>
                        <button type="button" class="btn btn-success btn-lg my-3" style="width: 80%" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Yorum Yap
                        </button>
                    <?php else: ?>
                        <p class="text-muted my-3" style="font-size: 0.9rem;">!!!Yorum yapabilmek için oturum açmalısınız.</p>
                    <?php endif; ?>

                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Düşüncelerini Belirt</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" id="commentForm" name="yorumform">
                                        <input type="hidden" name="user_id" value="<?php if($oturum){ echo $_SESSION['user_ID']; } ?>"> <!-- Session'dan user_id alınıyor -->
                                        <input type="hidden" id="topic_id" name="topic_id" value="<?php echo $topicinfos['Topic_ID']; ?>"> <!-- Topic ID formdan alınıyor -->

                                        <div class="form-group">
                                            <label for="yorum">Yorum:</label>
                                            <textarea class="form-control" id="yorum" name="yorum" style="height: 300px" required></textarea>
                                        </div>

                                        <div class="d-flex justify-content-between mt-3">
                                            <button type="button" id="submitComment" class="btn btn-primary">Yorum Ekle</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                        </div>
                                    </form>
                                    <div id="commentResult" class="mt-3"></div> <!-- AJAX işlem sonucunu burada gösterecek -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $result = $maintopic->allTopicComments($selectedtopic);
            foreach ($result as $resultcom) {
                ?>
                <div class="card my-3">
                    <div class="card-body">
                        <p class="card-text">
                            <?php echo htmlspecialchars($resultcom['Comment']); ?>
                        </p>
                        <div class="text-end">
                            <small class="text-muted">
                                - <?php echo htmlspecialchars($resultcom['User_nickname'])." / ".$resultcom["comdate"]; ?>
                            </small>
                        </div>
                    </div>
                </div>
                <?php
            }
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
        $('#submitComment').on('click', function(e) {
            e.preventDefault(); // Sayfa yenilenmesini engeller
            var formData = $('#commentForm').serialize(); // Form verilerini alır

            $.ajax({
                url: 'NewCommentJSON.php', // PHP dosyanıza POST isteği yapılacak
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Gelen yanıta göre kullanıcıya mesaj göster
                    if (response.startsWith("Hata:")) {
                        $('#commentResult').html('<div class="alert alert-danger">' + response + '</div>');
                    } else {
                        $('#commentResult').html('<div class="alert alert-success">' + response + '</div>');
                        $('#commentForm')[0].reset(); // Formu sıfırlar
                    }
                },
                error: function() {
                    $('#commentResult').html('<div class="alert alert-danger">Bir hata oluştu!</div>');
                }
            });
        });
        $('#exampleModal').on('hidden.bs.modal', function () {
            location.reload(); // Sayfayı yenile
        });
    });
</script>
</body>
</html>
