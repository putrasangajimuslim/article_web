<?php
require_once("config.php");
session_start();
if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    if ($role != 'admin') {
        header("Location: index.php"); // Misalnya, alihkan ke halaman login
        exit;
    }

    $sql = "SELECT * FROM users";
    $stmt = $db->query($sql);

    // Memuat data artikel ke dalam array
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <span class="text-logo">Admin Panel</span>

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
    if (isset($_SESSION['user'])) {
    ?>
        <div class="logout-page" id="loginPage">
            <div style="margin-bottom: 10px;">
                <a href="dashboard.php" class="label-logout">
                    Dashboard
                </a>
            </div>
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
        <span>Users List</span>
    </div>

    <div class="table-container">
        <button class="btn-success mb-2" onclick="addForm()">Add</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama Depan</th>
                    <th>Nama Belakang</th>
                    <th>Birthday</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($users as $users) : ?>
                    <tr data-id="<?php echo $users['id']; ?>">
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $users['username']; ?></td>
                        <td><?php echo $users['nama_depan']; ?></td>
                        <td><?php echo $users['nama_belakang']; ?></td>
                        <td><?php echo $users['birthday']; ?></td>
                        <td><?php echo $users['email']; ?></td>
                        <td><?php echo $users['role']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $users['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i></a>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <script>
        $(document).ready(function() {
            // Tambahkan event click pada tombol hapus
            $('.delete-btn').on('click', function() {
                // Simpan referensi tombol hapus yang diklik
                var deleteButton = $(this);

                var userid = $(this).closest('tr').data('id');
                // Tampilkan dialog konfirmasi
                if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                    // Jika pengguna mengonfirmasi, lakukan tindakan hapus
                    // Anda dapat menambahkan AJAX di sini untuk menghapus item dari server
                    // Misalnya, dengan menggunakan $.ajax() atau $.post()
                    // Di sini saya hanya menambahkan log ke konsol
                    $.ajax({
                        url: 'delete_user.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            user_id: userid
                        },
                        success: function(response) {
                            if (response.success) {
                                // Jika penghapusan berhasil, hapus baris tabel dari DOM
                                alert(response.message);
                                location.reload();
                            } else {
                                // Jika terjadi kesalahan saat menghapus, tampilkan pesan kesalahan
                                alert(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            // Tangani kesalahan AJAX
                            alert('Terjadi kesalahan: ' + error);
                        }
                    });

                } else {
                    // Jika pengguna membatalkan konfirmasi, tidak lakukan apa-apa
                    console.log('Penghapusan dibatalkan.');
                }
            });
        });

        function showLoginPage() {
            var loginPage = document.getElementById("loginPage");
            loginPage.style.display = "block";
        }

        function hideLoginPage() {
            var loginPage = document.getElementById("loginPage");
            loginPage.style.display = "none";
        }

        function addForm() {
            window.location.href = 'tambah_user.php';
        }
    </script>
</body>

</html>