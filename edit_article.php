<?php
require_once("config.php");
session_start();
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role != 'admin') {
        header("Location: index.php"); // Misalnya, alihkan ke halaman login
        exit;
    }

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {
        echo "ID artikel tidak ditemukan.";
        exit;
    }

    $sql = "SELECT * FROM article WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    // Pastikan artikel ditemukan
    if (!$article) {
        echo "Artikel tidak ditemukan.";
        exit;
    }
} else {
    header("Location: index.php"); // Misalnya, alihkan ke halaman login
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="navbar">
        <span class="text-logo">Admin Panel</span>

        <div class="wrapper-navbar-left">
            <i class="fas fa-user custom-icon-login" onclick="showLoginPage()" onmouseover="showLoginPage()" ondblclick="hideLoginPage()"></i>

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
        <a href="article.php"><span>Home</span></a> <span style="margin-left: 8px; margin-right: 8px;">></span> <span>Form Edit Article</span>
    </div>

    <div class="container-article">
        <button type="submit" class="btn-back" style="margin-bottom: 40px;" onclick="back()">Kembali</button>

        <?php
        if (isset($_SESSION['error'])) {
        ?>
            <div class="alert alert-warning" style="margin-bottom: 18px;" role="alert">
                <?php echo $_SESSION['error'] ?>
            </div>
        <?php
        }
        ?>
        <?php
        if (isset($_SESSION['message'])) {
        ?>
            <div class="alert alert-success" style="margin-bottom: 18px;" role="alert">
                <?php echo $_SESSION['message'] ?>
            </div>
        <?php
        }
        ?>

        <form action="update_article.php" method="post" class="add-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">
            <div class="form-control-custom">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo $article['title']; ?>">
            </div>
            <div class="form-control-custom">
                <label for="content">Content</label>
                <textarea name="content" id="content" cols="20" rows="20"><?php echo $article['content']; ?></textarea>
            </div>
            <div class="form-control-custom">
                <label for="imageUpload">Hasil Upload:</label>
                <div style="margin-top: 10px; margin-bottom: 10px;">
                    <img src="assets/uploads/<?php echo $article['img_content']; ?>" alt="Gambar" style="max-width: 200px;">
                </div>
            </div>
            <div class="form-control-custom">
                <label for="imageUpload">Pilih Gambar:</label>
                <input type="file" id="imageUpload" name="imageUpload" accept="image/*">
            </div>
            <div class="form-control-custom">
                <label for="publish_date">Publis Date</label>
                <input type="date" id="publish_date" name="publish_date" value="<?php echo $article['published_date']; ?>">
            </div>
            <button type="submit" class="btn btn-save">Simpan</button>
        </form>
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

        function back() {
            $.ajax({
                url: "hapus_session_alert.php",
                type: 'GET',
                success: function(res) {
                    if (res == 'success') {
                        window.location.href = "article.php";
                    }
                }
            });
        }
    </script>
</body>

</html>