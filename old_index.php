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

// Penanganan pencarian
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = cleanInput($_GET['search']);
    // Sesuaikan query SQL untuk melakukan pencarian
    $sql = "SELECT a.*, b.username as user_publish, b.nama_depan as nd, b.nama_belakang as nb 
            FROM article a
            JOIN users b ON a.user_id = b.id
            WHERE a.title LIKE :search OR a.content LIKE :search"; // Sesuaikan dengan kolom yang ingin dicari
    $stmt = $db->prepare($sql);
    $stmt->execute(array(':search' => "%$search%"));
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Jika tidak ada pencarian, tampilkan semua artikel seperti sebelumnya
    $sql = "SELECT a.*, b.username as user_publish, b.nama_depan as nd, b.nama_belakang as nb FROM article a
            JOIN users b ON a.user_id = b.id";
    $stmt = $db->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
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