<?php
require_once("config.php");
session_start();
$sql = "SELECT a.*, b.username as user_publish, b.nama_depan as nd, b.nama_belakang as nb FROM article a
        JOIN users b ON a.user_id = b.id"; // Sesuaikan nama tabel dan kolom sesuai dengan struktur database Anda
$stmt = $db->query($sql);

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
    <div class="navbar">
        <span class="text-logo">BLOGSPOT</span>

        <div class="wrapper-navbar-left">
            <input type="text" placeholder="search.." class="search-input">
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

    <div class="content-wrapper">
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
                            <h2><?php echo $article['title']; ?></h2>
                            <span class="clamp-line-options">
                                <?php echo $article['content']; ?>
                            </span>
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