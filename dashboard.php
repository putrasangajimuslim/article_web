<?php
require_once("config.php");
session_start();
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role != 'admin' && $role != 'writer') {
        header("Location: index.php"); // Misalnya, alihkan ke halaman login
        exit;
    }

    $sqlUsers = "SELECT COUNT(*) AS jumlah_user FROM users";
    $sqlArticle = "SELECT COUNT(*) AS jumlah_article FROM article";

    $sqlCategory = "SELECT kategori.id, kategori.name, COUNT(article.kategori_id) AS total_artikel
                    FROM kategori
                    LEFT JOIN article ON kategori.id = article.kategori_id
                    GROUP BY kategori.id, kategori.name";
        
    // Menyiapkan statement untuk pengguna
    $stmtUsers = $db->prepare($sqlUsers);
    // Menyiapkan statement untuk artikel
    $stmtArticles = $db->prepare($sqlArticle);

    $stmt = $db->prepare($sqlCategory);
    
    // Mengeksekusi statement untuk pengguna
    $stmtUsers->execute();
    // Mengeksekusi statement untuk artikel
    $stmtArticles->execute();
    
    // Mengambil hasil untuk pengguna
    $resultUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC);
    // Mengambil hasil untuk artikel
    $resultArticles = $stmtArticles->fetch(PDO::FETCH_ASSOC);

    $stmt->execute();

    $kategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Anda dapat mengakses jumlah pengguna dan artikel sebagai berikut
    $jmlUser = $resultUsers['jumlah_user'];
    $jmlArticle = $resultArticles['jumlah_article'];

    // var_dump($kategories); exit;

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
    <div class="navbar-custom">
        <?php if ($role == 'admin') { ?>
            <span class="text-logo">Admin Panel</span>
        <?php } else if ($role == 'writer') { ?>
            <span class="text-logo">BLOGSPOT</span>
        <?php } ?>

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
            <?php if ($role == 'admin') { ?>
                <div style="margin-bottom: 10px;">
                    <a href="user_list.php" class="label-logout">
                        Users
                    </a>
                </div>

                <div style="margin-bottom: 10px;">
                    <a href="article.php" class="label-logout">
                        Article
                    </a>
                </div>
            <?php } ?>

            <div>
                <a href="logout.php" class="label-logout">
                    Logout
                </a>
            </div>
        </div>
    <?php
    }
    ?>

    <div class="table-container">
        <span>Dashboard Panel</span>
    </div>

    <div class="table-container">
        <div class="container-custom">
            <div class="card-dashboard">
                <h3>Total Article</h3>
                <p><?php echo $jmlArticle; ?> Article.</p>
                <a href="article.php" class="btn">View Details</a>
            </div>

            <div class="card-dashboard">
                <h3>Total User</h3>
                <p><?php echo $jmlUser; ?> User.</p>
                <a href="user_list.php" class="btn">View Details</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name Category</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($kategories as $kategory) : ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $kategory['name']; ?></td>
                        <td><?php echo $kategory['total_artikel']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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