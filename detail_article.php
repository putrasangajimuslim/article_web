<?php
require_once("config.php");
session_start();
$id = isset($_GET['id']) ? $_GET['id'] : null;

$sql = "SELECT a.*, b.username as user_publish, b.nama_depan as nd, b.nama_belakang as nb FROM article a
        JOIN users b ON a.user_id = b.id WHERE a.id = :id"; // Sesuaikan nama tabel dan kolom sesuai dengan struktur database Anda
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();

// Memuat data artikel ke dalam array
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Web</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
</head>

<body>
    <div class="navbar-custom">
        <span class="text-logo">BLOGSPOT</span>

        <div class="wrapper-navbar-left">
            <!-- <input type="text" placeholder="search.." class="search-input"> -->
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

    <div class="table-container">
        <a href="index.php"><span>Home</span></a> <span style="margin-left: 8px; margin-right: 8px;">></span> <span>Detail Article</span>
    </div>

    <div class="content-wrapper-detail-article">
        <div class="container">
            <?php $article = $articles[0]; ?>
            <header>
                <h1 style="text-align: center;"><?php echo $article['title']; ?></h1>
                <p class="meta" style="text-align: center; margin-top: 10px;">Diposting pada <span class="date"><?php echo $article['published_date']; ?></span> oleh <span class="author"><?php echo $article['user_publish'] == 'admin' ?  $article['user_publish'] : $article['nd'] . ' ' . $article['nb']; ?></span></p>
            </header>
            <article>
            <img src="assets/uploads/<?php echo $article['img_content']; ?>" alt="Gambar Berita" style="margin-top: 10px; margin-bottom: 10px;">
            <p>
                <?php echo $article['content']; ?>
            </p>
            </article>
        </div>
    </div>

    <script>
        function showLoginPage() {
            var loginPage = document.getElementById("loginPage");
            loginPage.style.display = "block";
        }

        function hideLoginPage() {
            var loginPage = document.getElementById("loginPage");
            loginPage.style.display = "none";
        }
    </script>
</body>

</html>