<?php
session_start();
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
            <div class="card">
                <img src="https://via.placeholder.com/300" alt="Card Image">
                <div class="card-content">
                    <h2>Post Title 1</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
                    <div class="publish-user">
                        <img src="https://via.placeholder.com/300" alt="" style="margin-right: 10px;">
                        <div class="user-profile">
                            <p style="font-size:14px;">Ayunda Zikrina</p>
                            <p style="font-size:10px;">Sept 15 2022</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/300" alt="Card Image">
                <div class="card-content">
                    <h2>Post Title 2</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
                    <div class="publish-user">
                        <img src="https://via.placeholder.com/300" alt="" style="margin-right: 10px;">
                        <div class="user-profile">
                            <p style="font-size:14px;">Ayunda Zikrina</p>
                            <p style="font-size:10px;">Sept 15 2022</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/300" alt="Card Image">
                <div class="card-content">
                    <h2>Post Title 3</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
                    <div class="publish-user">
                        <img src="https://via.placeholder.com/300" alt="" style="margin-right: 10px;">
                        <div class="user-profile">
                            <p style="font-size:14px;">Ayunda Zikrina</p>
                            <p style="font-size:10px;">Sept 15 2022</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <img src="https://via.placeholder.com/300" alt="Card Image">
                <div class="card-content">
                    <h2>Post Title 4</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
                    <div class="publish-user">
                        <img src="https://via.placeholder.com/300" alt="" style="margin-right: 10px;">
                        <div class="user-profile">
                            <p style="font-size:14px;">Ayunda Zikrina</p>
                            <p style="font-size:10px;">Sept 15 2022</p>
                        </div>
                    </div>
                </div>
            </div>
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