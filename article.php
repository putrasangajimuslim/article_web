<?php
require_once("config.php");
session_start();

function cleanInput($input)
{
    // Membersihkan input dari skrip berbahaya
    $search = array(
        '@<script[^>]*?>.*?</script>@si',   // Menghapus tag script
        '@<[\/\!]*?[^<>]*?>@si',            // Menghapus tag HTML
        '@<style[^>]*?>.*?</style>@siU',    // Menghapus tag style
        '@<![\s\S]*?--[ \t\n\r]*>@'         // Menghapus komentar
    );
    $output = preg_replace($search, '', $input);
    return $output;
}

if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];

    // Periksa apakah peran pengguna adalah admin atau penulis
    if ($role != 'admin' && $role != 'writer') {
        header("Location: index.php"); // Redirect ke halaman login jika bukan admin atau penulis
        exit;
    }

    $userId = $_SESSION['user']['id'];

    // Periksa apakah parameter 'search' telah diset dan tidak kosong
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = cleanInput($_GET['search']);

        // Bangun kueri berdasarkan peran pengguna
        if ($role == 'admin') {
            $sql = "SELECT * FROM article WHERE title LIKE :search OR content LIKE :search";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(':search' => "%$search%"));
        } else {
            $sql = "SELECT * FROM article WHERE user_id = :id AND (title LIKE :search OR content LIKE :search)";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(':id' => $userId, ':search' => "%$search%"));
        }
    } else {
        // Jika tidak ada parameter pencarian, ambil semua artikel berdasarkan peran pengguna
        if ($role == 'admin') {
            $sql = "SELECT * FROM article";
            $stmt = $db->query($sql);
        } else {
            $sql = "SELECT * FROM article WHERE user_id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(':id' => $userId));
        }
    }

    // Memuat data artikel ke dalam array
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirect ke halaman login jika tidak ada pengguna yang masuk
    header("Location: index.php");
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
            <?php if ($role == 'admin') { ?>
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
        <span>Article List</span>
    </div>

    <div class="table-container">
        <button class="btn-success mb-2" onclick="addForm()">Add</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Image</th>
                    <th>Publish Date</th>
                    <th>Jumlah Like</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($articles as $article) : ?>
                    <tr data-id="<?php echo $article['id']; ?>">
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $article['title']; ?></td>
                        <td>
                            <span class="clamp-line-options">
                                <?php echo $article['content']; ?>
                            </span>
                        </td>
                        <td><img src="assets/uploads/<?php echo $article['img_content']; ?>" alt="Gambar" style="max-width: 100px;"></td>
                        <td><?php echo $article['published_date']; ?></td>
                        <td>
                            
                        </td>
                        <td>
                            <a href="edit_article.php?id=<?php echo $article['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i></a>
                            <a href="view_article.php?id=<?php echo $article['id']; ?>" class="edit-btn"><i class="fas fa-eye"></i></a>
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

                var articleId = $(this).closest('tr').data('id');
                // Tampilkan dialog konfirmasi
                if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                    // Jika pengguna mengonfirmasi, lakukan tindakan hapus
                    // Anda dapat menambahkan AJAX di sini untuk menghapus item dari server
                    // Misalnya, dengan menggunakan $.ajax() atau $.post()
                    // Di sini saya hanya menambahkan log ke konsol
                    $.ajax({
                        url: 'delete_article.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            article_id: articleId
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
            window.location.href = 'tambah_article.php';
        }
    </script>
</body>

</html>