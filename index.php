<?php
session_start();

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'akademik');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }   
        .login-container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-in-out;
        }
        .login-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: none;
        }
        .card-header {
            background-color: #3f51b5;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
            background-color: #ffffff;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }
        .btn-login {
            background-color: #3f51b5;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #303f9f;
            transform: translateY(-2px);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .welcome-text {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-5 col-md-7">
                <div class="text-center mb-5">
                    <h1 class="welcome-text">Selamat Datang di Sistem Akademik</h1>
                    <p class="text-white">Kelola data siswa/mahasiswa dengan mudah dan efisien</p>
                </div>
                
                <div class="login-container">
                    <div class="login-card">
                        <div class="card-header text-center">
                            <h3 class="m-0 text-white"><i class="fas fa-user-graduate me-2"></i>Login Akademik</h3>
                        </div>
                        <div class="card-body">
                            <?php if(isset($error)): ?>
                                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-login w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-white">Belum punya akun? <a href="index.php" class="text-white fw-bold" onclick="alert('Mohon maaf belum dapat membuat akun baru!!');">Hubungi Admin</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome untuk ikon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>