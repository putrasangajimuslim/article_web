<?php
require_once("config.php");
session_start();
$sql = "SELECT a.*, b.username as user_publish, b.nama_depan as nd, b.nama_belakang as nb FROM article a
        JOIN users b ON a.user_id = b.id"; // Sesuaikan nama tabel dan kolom sesuai dengan struktur database Anda
$stmt = $db->query($sql);

// Memuat data artikel ke dalam array
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$isActionArticle = false;

if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];

    if ($role == 'writer' || $role == 'admin') {
        $isActionArticle = true;
    }
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
            <!-- <input type="text" placeholder="search.." class="search-input" name="search" onchange="searchArticle()"> -->
            <i class="fas fa-user custom-icon-login" onmouseover="showLoginPage()" onclick="showLoginPage()" ondblclick="hideLoginPage()"></i>
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
                <a href="detail_article.php?id=<?php echo $article['id']; ?>" style="text-decoration: none;">
                    <div class="card">
                        <!-- <img src="https://via.placeholder.com/300" alt="Card Image"> -->
                        <img src="assets/uploads/<?php echo $article['img_content']; ?>" alt="Card Image">
                        <div class="card-content">
                            <div class="wrapper-content">
                                <?php echo $article['title']; ?>
                                <div class="wrapper-clamp-line-options">
                                    <div class="clamp-line-options">
                                        <?php echo $article['content']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="publish-user">
                                <img src="https://via.placeholder.com/300" alt="" style="margin-right: 10px;">
                                <div class="user-profile">
                                    <p style="font-size:14px;"><?php echo $article['user_publish'] == 'admin' ?  $article['user_publish'] : $article['nd'] . ' ' . $article['nb']; ?></p>
                                    <p style="font-size:10px;"><?php echo $article['published_date']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function showLoginPage() {
            $.ajax({
                url: "hapus_session_alert.php",
                type: 'GET',
                success: function(res) {
                    if (res == 'success') {
                        var loginPage = document.getElementById("loginPage");
                        loginPage.style.display = "block";

                        var alertpage = document.getElementById("alertpage");
                        alertpage.style.display = "none";
                    }
                }
            });
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
                        alertpage.style.display = "none";
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