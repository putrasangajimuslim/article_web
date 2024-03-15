<?php
require_once("config.php");
session_start();

// Fungsi untuk menghindari injeksi SQL
function cleanInput($input)
{
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Menghapus tag script
        '@<[\/\!]*?[^<>]*?>@si',            // Menghapus tag HTML
        '@<style[^>]*?>.*?</style>@siU',    // Menghapus tag style
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Menghapus komentar
    );
    $output = preg_replace($search, '', $input);
    return $output;
}

$isActionArticle = false;
$userLoginId = '';

if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    $userLoginId = $_SESSION['user']['id'];
    if ($role == 'writer' || $role == 'admin') {
        $isActionArticle = true;
    }
}

// Penanganan pencarian
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = cleanInput($_GET['search']);
    // Sesuaikan query SQL untuk melakukan pencarian
    $sql = "SELECT a.*, 
                b.username as user_publish, 
                b.nama_depan as nd, 
                b.nama_belakang as nb,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) as total_likes 
            FROM article a
            JOIN users b ON a.user_id = b.id
            WHERE a.title LIKE :search OR a.content LIKE :search"; // Sesuaikan dengan kolom yang ingin dicari
    $stmt = $db->prepare($sql);
    $stmt->execute(array(':search' => "%$search%"));
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    if (!empty($userLoginId)) { // Check if $userLoginId is not an empty string
        $sql = "SELECT a.*, 
                b.username as user_publish, 
                b.nama_depan as nd, 
                b.nama_belakang as nb,
                l.user_id as user_like_id,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) as total_likes
        FROM article a
        JOIN users b ON a.user_id = b.id
        LEFT JOIN likes l ON a.id = l.article_id AND l.user_id = :user_id";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(':user_id' => $userLoginId));
    } else {
        $sql = "SELECT a.*, 
                b.username as user_publish, 
                b.nama_depan as nd, 
                b.nama_belakang as nb,
                (SELECT COUNT(*) FROM likes WHERE article_id = a.id) as total_likes 
        FROM article a
        JOIN users b ON a.user_id = b.id";
        $stmt = $db->query($sql);
    }

    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($articles);
    // exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Web</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="navbar-custom">
        <span class="text-logo">BLOGSPOT</span>

        <div class="wrapper-navbar-left">
            <form action="" method="GET"> <!-- Ganti action dengan kosong agar form submit ke halaman ini sendiri -->
                <input type="text" placeholder="search.." class="search-input" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button type="submit" class="btn-search"><i class="fas fa-search"></i></button> <!-- Tombol pencarian -->
            </form>

            <i class="fas fa-user custom-icon-login" onmouseover="showLoginPage()" onclick="showLoginPage()" ondblclick="hideLoginPage()"></i>

            <!-- Sisipkan tombol login di sini -->

            <?php
            if (isset($_SESSION['user'])) {
            ?>
                <div class="label-admin">
                    <?php echo $_SESSION['user']['username'] ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <?php
    if (!isset($_SESSION['user'])) {
    ?>
        <div class="login-page" id="loginPage">
            <div class="label-login">Login</div>
            <form action="login_aksi.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Username" name="username" class="form-control">
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" class="form-control">
                </div>
                <button class="btn-login">MASUK</button>
                <div class="dont-any-account">
                    Belum Punya Akun? <a href="register.php" class="daftar">Daftar</a>
                </div>
            </form>
        </div>
    <?php
    }
    ?>

    <?php
    if (isset($_SESSION['error'])) {
    ?>
        <div class="alert-page" id="alertpage">
            <div class="label-alert"> <?php echo $_SESSION['error'] ?></div>
        </div>
    <?php
    }
    ?>

    <?php
    if (isset($_SESSION['user'])) {
    ?>
        <div class="logout-page" id="loginPage">
            <a href="logout.php" class="label-logout">
                Logout
            </a>
        </div>
    <?php
    }
    ?>

    <div class="content-wrapper">
        <?php if ($isActionArticle) : ?>
            <button class="btn-success mb-2" onclick="addForm()">Add Article</button>
        <?php endif; ?>
        <div class="d-flex-space-between">
            <div class="section-label">
                Featured Post
            </div>
            <div class="content-more">
                <span>More</span>
            </div>
        </div>
        <div class="border-separator">
            <div class="blue-box"></div>
        </div>

        <div class="container">
            <?php foreach ($articles as $article) : ?>
                <div id="article_id" data-id="<?php echo $article['id']; ?>" style="display:none;"></div>
                <a href="detail_article.php?id=<?php echo $article['id']; ?>" style="text-decoration: none;">
                    <div class="card">
                        <img src="assets/uploads/<?php echo $article['img_content']; ?>" alt="Gambar" class="card-img">
                        <div class="card-content">
                            <h2 class="card-title"><?php echo $article['title']; ?></h2>
                            <div class="wrapper-clamp-line-options">
                                <div class="clamp-line-options">
                                    <?php echo $article['content']; ?>
                                </div>
                            </div>
                            <div class="publish-user">
                                <img src="https://via.placeholder.com/300" alt="" style="margin-right: 10px;">
                                <div class="user-profile">
                                    <p style="font-size:14px;"><?php echo $article['user_publish'] == 'admin' ?  $article['user_publish'] : $article['nd'] . ' ' . $article['nb']; ?></p>
                                    <p style="font-size:10px;"><?php echo $article['published_date']; ?></p>
                                </div>
                            </div>
                            <div style="cursor: pointer;" class="container-likes">
                                <div style="display: flex; text-align:center;">

                                    <?php if ($userLoginId) : ?>
                                        <?php if ($article['user_like_id'] == $userLoginId) : ?>
                                            <img src="assets/images/love-red.png" alt="" width="20" style="margin-right: 8px; display:block;" class="liked-love" onclick="unlikePost()">
                                            <img src="assets/images/like.jpg" alt="" width="20" style="margin-right: 8px; display:none;" class="liked" onclick="likePost()">
                                        <?php else : ?>
                                            <img src="assets/images/love-red.png" alt="" width="20" style="margin-right: 8px; display:none;" class="liked-love" onclick="unlikePost()">
                                            <img src="assets/images/like.jpg" alt="" width="20" style="margin-right: 8px; display:block;" class="liked" onclick="likePost()">
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <img src="assets/images/like.jpg" alt="" width="20" style="margin-right: 8px; display:block;" class="liked" onclick="likePost()">
                                    <?php endif; ?>

                                    <!-- <img src="assets/images/love-red.png" alt="" width="20" style="margin-right: 8px;" class="liked-love" onclick="unlikePost()"> -->
                                    <!-- <img src="assets/images/like.jpg" alt="" width="20" style="margin-right: 8px;" class="liked" onclick="likePost()"> -->
                                    <?php if ($article['total_likes'] > 0) : ?>
                                        <span class="total-likes-sp"><?php echo $article['total_likes']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        var liked = document.getElementsByClassName("liked")[0];
        var likedLove = document.getElementsByClassName("liked-love")[0];

        function showLoginPage() {
            $.ajax({
                url: "hapus_session_alert.php",
                type: 'GET',
                success: function(res) {
                    if (res == 'success') {
                        var loginPage = document.getElementById("loginPage");
                        loginPage.style.display = "block";

                        var alertpage = document.getElementById("alertpage");
                        if (alertpage){
                            alertpage.style.display = "none";
                        }
                    }
                }
            });
        }

        function likePost() {

            var article_id = document.getElementById("article_id").getAttribute("data-id");

            $.ajax({
                url: "liked_article.php",
                type: 'GET',
                data: { // Data yang ingin Anda kirim
                    article_id: article_id
                },
                success: function(res) {
                    if (res == 'error') {
                        alert('silahkan login terlebih dahulu');
                    } else {
                        if (liked.style.display !== "none") {
                            liked.style.display = "none";
                            likedLove.style.display = "block"; // Menampilkan gambar yang disukai
                        } else {
                            liked.style.display = "block"; // Menampilkan gambar yang disukai
                            likedLove.style.display = "none";
                        }
                    }

                }
            });
        }

        function unlikePost() {
            var article_id = document.getElementById("article_id").getAttribute("data-id");

            if (liked.style.display === "nonne") {
                liked.style.display = "none";
                likedLove.style.display = "block";
            } else {
                liked.style.display = "block";
                likedLove.style.display = "none";

                $.ajax({
                    url: "unliked_article.php",
                    type: 'GET',
                    data: { // Data yang ingin Anda kirim
                        article_id: article_id
                    },
                    success: function(res) {

                    }
                });
            }
        }

        function hideLoginPage() {
            $.ajax({
                url: "hapus_session_alert.php",
                type: 'GET',
                success: function(res) {
                    if (res == 'success') {
                        var loginPage = document.getElementById("loginPage");
                        loginPage.style.display = "none";

                        var alertpage = document.getElementById("alertpage");

                        if (alertpage) {
                            alertpage.style.display = "none";
                        }
                    }
                }
            });
        }

        function addForm() {
            window.location.href = 'tambah_article.php';
        }
    </script>
</body>

</html>