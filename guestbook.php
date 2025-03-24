<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Simpan entri baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $timestamp = date('Y-m-d H:i:s');
    
    $entry = $timestamp . '|' . $name . '|' . $email . '|' . $message . "\n";
    
    $file = 'guestbook.txt';
    if (file_put_contents($file, $entry, FILE_APPEND | LOCK_EX) === false) {
        $saveError = "Terjadi kesalahan saat menyimpan data.";
    } else {
        header('Location: guestbook.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .entry-card:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .gradient-bg {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex justify-between items-center mb-8 fade-in">
            <h1 class="text-4xl font-bold text-slate-800">📖 Buku Tamu Digital</h1>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-full transition-all duration-300 transform hover:scale-105">
                Keluar
            </a>
        </div>

        <?php if (isset($saveError)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 animate__animated animate__shakeX">
                <?= $saveError ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-2xl p-6 mb-8 fade-in">
            <form id="guestbookForm" method="post" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Nama</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Email (opsional)</label>
                    <input type="email" name="email" 
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Pesan</label>
                    <textarea name="message" required rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"></textarea>
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-[1.02]">
                    ✨ Kirim Pesan
                </button>
            </form>
        </div>

        <h2 class="text-3xl font-semibold text-slate-800 mb-6 fade-in">📝 Entri Terbaru</h2>
        <div class="space-y-4">
            <?php
            $file = 'guestbook.txt';
            $entries = file_exists($file) ? array_reverse(file($file)) : array();
            if (!empty($entries)) {
                foreach ($entries as $index => $entry) {
                    list($timestamp, $name, $email, $message) = explode('|', $entry, 4);
                    echo '<div class="entry-card bg-white rounded-xl shadow-lg p-6 fade-in" style="animation-delay: '.($index*0.1).'s">';
                    echo '<div class="flex items-center mb-4">';
                    echo '<div class="bg-blue-100 p-3 rounded-full mr-3">👤</div>';
                    echo '<div>';
                    echo '<h3 class="font-bold text-lg text-gray-800">'.htmlspecialchars($name).'</h3>';
                    echo '<p class="text-sm text-gray-500">'.htmlspecialchars($timestamp).'</p>';
                    echo '</div></div>';
                    if (!empty($email)) {
                        echo '<p class="text-gray-600 mb-2"><span class="font-semibold">📧 Email:</span> '.htmlspecialchars($email).'</p>';
                    }
                    echo '<p class="text-gray-700 leading-relaxed">'.nl2br(htmlspecialchars($message)).'</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="bg-white rounded-xl p-6 text-center text-gray-500 animate__animated animate__fadeIn">Belum ada entri</div>';
            }
            ?>
        </div>
    </div>

    <script>
        document.getElementById('guestbookForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '⏳ Mengirim...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '✨ Kirim Pesan';
                btn.disabled = false;
            }, 2000);
        });
    </script>
</body>
</html>