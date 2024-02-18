<?php
session_start();
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role != 'admin') {
        header("Location: index.php"); // Misalnya, alihkan ke halaman login
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
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>Jl. Contoh No. 123</td>
                    <td>
                        <button class="edit-btn"><i class="fas fa-edit"></i></button>
                        <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Doe</td>
                    <td>Jl. Contoh No. 456</td>
                    <td>
                        <button class="edit-btn"><i class="fas fa-edit"></i></button>
                        <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
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