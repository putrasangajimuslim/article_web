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
        echo "ID user tidak ditemukan.";
        exit;
    }

    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Pastikan artikel ditemukan
    if (!$user) {
        echo "User tidak ditemukan.";
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
    <title>Article Web</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</head>

<body>
    <div class="navbar-custom">
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
        <a href="user_list.php"><span>Home</span></a> <span style="margin-left: 8px; margin-right: 8px;">></span> <span>Form Edit User</span>
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

        <form action="update_user_aksi.php" method="post" class="add-form">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="form-control-custom">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>">
            </div>

            <div class="form-control-custom">
                <label for="nama_d">Nama Depan</label>
                <input type="text" id="nama_d" name="nama_d" value="<?php echo $user['nama_depan']; ?>">
            </div>

            <div class="form-control-custom">
                <label for="nama_b">Nama Belakang</label>
                <input type="text" id="nama_b" name="nama_b" value="<?php echo $user['nama_belakang']; ?>">
            </div>

            <div class="form-control-custom">
                <label for="birthday">Birthday</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo $user['birthday']; ?>">
            </div>

            <div class="form-control-custom">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>">
            </div>

            <div class="form-control-custom">
                <label for="role">Role</label>
                <select name="user_role" id="user_role">
                    <option value="">-- Silahkan Pilih Role --</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>user</option>
                    <option value="writer" <?php echo ($user['role'] == 'writer') ? 'selected' : ''; ?>>Writer</option>
                </select>
            </div>

            <div class="form-control-custom">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-control-custom">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
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
                        window.location.href = "user_list.php";
                    }
                }
            });
        }
    </script>
</body>

</html>