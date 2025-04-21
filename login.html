<?php
session_start();
$isLoginPage = strpos($_SERVER['REQUEST_URI'], 'login.php') !== false;

// Konfigurasi database sederhana (file text)
$usersFile = 'users.txt';

// Membuat file users.txt jika belum ada
if (!file_exists($usersFile)) {
    file_put_contents($usersFile, "");
}

// Fungsi untuk menyimpan user baru
function registerUser($username, $password) {
    global $usersFile;
    $users = file($usersFile, FILE_IGNORE_NEW_LINES);
    
    foreach ($users as $user) {
        list($existingUser, $hash) = explode('|', $user);
        if ($existingUser === $username) {
            return "Username sudah digunakan!";
        }
    }
    
    $hash = password_hash($password, PASSWORD_DEFAULT);
    file_put_contents($usersFile, "$username|$hash\n", FILE_APPEND);
    return true;
}

// Fungsi untuk login
function loginUser($username, $password) {
    global $usersFile;
    $users = file($usersFile, FILE_IGNORE_NEW_LINES);
    
    foreach ($users as $user) {
        list($existingUser, $hash) = explode('|', $user);
        if ($existingUser === $username && password_verify($password, $hash)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            return true;
        }
    }
    return "Username atau password salah!";
}

// Handle form submission
$error = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        // Proses registrasi
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if (strlen($username) < 3) {
            $error = "Username minimal 3 karakter!";
        } elseif (strlen($password) < 6) {
            $error = "Password minimal 6 karakter!";
        } else {
            $result = registerUser($username, $password);
            if ($result === true) {
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header('Location: login.php');
                exit;
            } else {
                $error = $result;
            }
        }
    } else {
        // Proses login
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $result = loginUser($username, $password);
        
        if ($result === true) {
            header('Location: guestbook.php');
            exit;
        } else {
            $error = $result;
        }
    }
}

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: guestbook.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register - Buku Tamu Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
        }
        .tab-active {
            border-bottom-color: #7c3aed;
            color: #7c3aed;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <!-- Header with tabs -->
            <div class="flex border-b">
                <button onclick="showForm('login')" class="tab-btn flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-purple-600 transition-colors border-b-2 border-transparent tab-active" data-form="login">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login
                </button>
                <button onclick="showForm('register')" class="tab-btn flex-1 py-4 px-6 text-center font-medium text-gray-700 hover:text-purple-600 transition-colors border-b-2 border-transparent" data-form="register">
                    <i class="fas fa-user-plus mr-2"></i>Daftar
                </button>
            </div>
            
            <div class="p-6">
                <!-- Success Message Container (initially hidden) -->
                <div id="successMessage" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="successText"></span>
                </div>

                <!-- Error Message Container (initially hidden) -->
                <div id="errorMessage" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="errorText"></span>
                </div>

                <!-- Login Form -->
                <form method="post" class="form-container space-y-4 active" id="loginForm">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="username" required 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-all">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password" required 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-all">
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors mt-2">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </button>
                </form>

                <!-- Register Form -->
                <form method="post" class="form-container space-y-4" id="registerForm">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" name="username" required 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-all">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 3 karakter</p>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password" required 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg input-focus focus:outline-none transition-all">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter</p>
                    </div>
                    
                    <button type="submit" name="register" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors mt-2">
                        <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize with login form active
        document.addEventListener('DOMContentLoaded', function() {
            // Check URL parameters for success/error messages
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            const error = urlParams.get('error');
            
            if (success) {
                showMessage('success', success);
            }
            
            if (error) {
                showMessage('error', error);
            }
        });

        function showForm(formType) {
            // Update tab styles
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.add('border-transparent', 'text-gray-700');
            });
            
            // Active tab style
            event.target.classList.add('tab-active');
            event.target.classList.remove('border-transparent');
            
            // Show selected form
            document.querySelectorAll('.form-container').forEach(form => {
                form.classList.remove('active');
            });
            document.getElementById(formType + 'Form').classList.add('active');
        }
        
        function showMessage(type, message) {
            if (type === 'success') {
                document.getElementById('successText').textContent = message;
                document.getElementById('successMessage').classList.remove('hidden');
            } else if (type === 'error') {
                document.getElementById('errorText').textContent = message;
                document.getElementById('errorMessage').classList.remove('hidden');
            }
        }
    </script>
</body>
</html>