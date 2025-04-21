<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}

// Simpan entri baru
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    $timestamp = date('Y-m-d H:i:s');
    
    $entry = $timestamp . '|' . $name . '|' . $email . '|' . $message . "\n";
    
    $file = 'guestbook.txt';
    if (file_put_contents($file, $entry, FILE_APPEND | LOCK_EX) === false) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan entri']);
        exit;
    }
    
    $newEntry = [
        'timestamp' => $timestamp,
        'name' => $name,
        'email' => $email,
        'message' => $message
    ];
    
    echo json_encode(['status' => 'success', 'entry' => $newEntry]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #f8fafc;
            --text: #1e293b; /* Darker text color for light mode */
            --card-bg: #ffffff;
            --input-bg: #ffffff;
            --border: #e5e7eb;
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .dark {
            --primary: #818cf8;
            --bg: #0f172a;
            --text: #f8fafc;
            --card-bg: #1e293b;
            --input-bg: #1e293b;
            --border: #334155;
            --shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            transition: all 0.3s ease;
        }

        .entry-card {
            opacity: 0;
            transform: translateY(20px);
            animation: slideIn 0.5s ease forwards;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: -300px;
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .toast.show {
            right: 20px;
        }

        .gradient-text {
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .glass-nav {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.7);
        }

        .dark .glass-nav {
            background-color: rgba(15, 23, 42, 0.7);
        }

        .avatar {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
        }

        /* Form input styling */
        .form-input {
            background-color: var(--input-bg);
            border: 1px solid var(--border);
            color: var(--text);
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3);
        }

        /* Card styling */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        /* Theme toggle button */
        .theme-toggle {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    <!-- Toast Notification -->
    <div id="toast" class="toast p-4 rounded-lg shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700"></div>

    <header class="fixed w-full top-0 z-50 glass-nav border-b border-gray-200 dark:border-gray-800">
        <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="hamburger lg:hidden cursor-pointer" onclick="toggleMenu()">
                    <div class="w-6 h-0.5 bg-gray-800 dark:bg-gray-200 my-1.5 transition-all duration-300"></div>
                    <div class="w-6 h-0.5 bg-gray-800 dark:bg-gray-200 my-1.5 transition-all duration-300"></div>
                    <div class="w-6 h-0.5 bg-gray-800 dark:bg-gray-200 my-1.5 transition-all duration-300"></div>
                </div>
                <h1 class="text-2xl font-bold gradient-text">Buku Tamu Digital</h1>
            </div>
            
            <div class="flex items-center space-x-6">
                <button id="themeToggle" class="theme-toggle p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i class="fas fa-moon dark:hidden text-gray-600"></i>
                    <i class="fas fa-sun hidden dark:inline text-yellow-300"></i>
                </button>
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="logout.php" class="px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-lg hover:opacity-90 transition-all shadow-md hover:shadow-lg">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </a>
                </div>
            </div>
        </nav>
        
        <!-- Mobile Menu -->
        <div class="lg:hidden menu-items bg-white dark:bg-gray-800 overflow-hidden max-h-0 transition-all duration-300">
            <div class="container mx-auto px-6 py-2">
                <a href="logout.php" class="block py-3 text-red-500 hover:text-red-600 transition-colors">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 pt-24 pb-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Input Form -->
            <div class="lg:w-1/3">
                <div class="sticky top-28 p-6 rounded-2xl card backdrop-blur-sm shadow-xl animate__animated animate__fadeIn">
                    <h2 class="text-xl font-semibold mb-6 text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-pen-fancy mr-3 gradient-text"></i>Tinggalkan Pesan
                    </h2>
                    <form id="guestbookForm" method="post" class="space-y-5">
                        <div class="relative">
                            <input type="text" name="name" required 
                                class="form-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                                placeholder="Nama Anda">
                            <i class="fas fa-user absolute right-4 top-3.5 text-gray-400"></i>
                        </div>
                        
                        <div class="relative">
                            <input type="email" name="email" 
                                class="form-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                                placeholder="Email (opsional)">
                            <i class="fas fa-envelope absolute right-4 top-3.5 text-gray-400"></i>
                        </div>
                        
                        <div class="relative">
                            <textarea name="message" required rows="4"
                                class="form-input w-full px-4 py-3 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm"
                                placeholder="Pesan Anda"></textarea>
                            <i class="fas fa-comment-dots absolute right-4 top-3.5 text-gray-400"></i>
                        </div>
                        
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-xl hover:opacity-90 transition-all shadow-md hover:shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Entries List -->
            <div class="lg:w-2/3">
                <div class="flex flex-col gap-6" id="entriesContainer">
                    <?php if(empty($entries)): ?>
                        <div class="card p-8 text-center rounded-2xl">
                            <i class="fas fa-book-open text-4xl mb-4 gradient-text"></i>
                            <h3 class="text-xl font-medium text-gray-800 dark:text-gray-300">Belum ada pesan</h3>
                            <p class="text-gray-700 dark:text-gray-400 mt-2">Jadilah yang pertama meninggalkan pesan!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($entries as $entry): ?>
                            <?php list($timestamp, $name, $email, $message) = explode('|', $entry, 4); ?>
                            <div class="entry-card card p-6 rounded-2xl backdrop-blur-sm hover:shadow-md transition-shadow">
                                <div class="flex items-start mb-4">
                                    <div class="avatar w-10 h-10 rounded-full flex items-center justify-center text-white font-medium mr-3 shadow-md">
                                        <?= strtoupper(substr($name, 0, 1)) ?>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-semibold text-gray-800 dark:text-white"><?= htmlspecialchars($name) ?></h3>
                                            <span class="text-xs text-gray-600 dark:text-gray-400"><?= htmlspecialchars($timestamp) ?></span>
                                        </div>
                                        <?php if(!empty($email)): ?>
                                            <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1">
                                                <i class="fas fa-envelope mr-1"></i><?= htmlspecialchars($email) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="pl-13">
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                        <i class="fas fa-quote-left text-gray-500 dark:text-gray-500 mr-2"></i>
                                        <?= nl2br(htmlspecialchars($message)) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Dark Mode Toggle with LocalStorage
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        
        // Check for saved theme preference or use system preference
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
            html.classList.add('dark');
        }

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            
            // Update button icon
            const moonIcon = themeToggle.querySelector('.fa-moon');
            const sunIcon = themeToggle.querySelector('.fa-sun');
            
            if (isDark) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        });

        // Set initial icon state
        function setInitialThemeIcon() {
            const isDark = html.classList.contains('dark');
            const moonIcon = themeToggle.querySelector('.fa-moon');
            const sunIcon = themeToggle.querySelector('.fa-sun');
            
            if (isDark) {
                moonIcon.classList.add('hidden');
                sunIcon.classList.remove('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }

        // Hamburger Menu
        function toggleMenu() {
            const hamburger = document.querySelector('.hamburger');
            const menu = document.querySelector('.menu-items');
            
            hamburger.classList.toggle('active');
            menu.style.maxHeight = menu.style.maxHeight ? null : menu.scrollHeight + 'px';
            
            // Animate hamburger icon
            const bars = hamburger.querySelectorAll('div');
            if (hamburger.classList.contains('active')) {
                bars[0].style.transform = 'translateY(6px) rotate(45deg)';
                bars[1].style.opacity = '0';
                bars[2].style.transform = 'translateY(-6px) rotate(-45deg)';
            } else {
                bars[0].style.transform = '';
                bars[1].style.opacity = '';
                bars[2].style.transform = '';
            }
        }

        // Toast Notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle text-green-500' : 'fa-exclamation-circle text-red-500'} mr-3"></i>
                    <span>${message}</span>
                </div>
            `;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // AJAX Form Submission
        document.getElementById('guestbookForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('guestbook.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    form.reset();
                    showToast('Pesan berhasil dikirim!', 'success');
                    
                    // Create new entry element
                    const entryHTML = `
                        <div class="entry-card card p-6 rounded-2xl backdrop-blur-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start mb-4">
                                <div class="avatar w-10 h-10 rounded-full flex items-center justify-center text-white font-medium mr-3 shadow-md">
                                    ${result.entry.name.charAt(0).toUpperCase()}
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-semibold text-gray-800 dark:text-white">${result.entry.name}</h3>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">${result.entry.timestamp}</span>
                                    </div>
                                    ${result.entry.email ? `
                                    <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1">
                                        <i class="fas fa-envelope mr-1"></i>${result.entry.email}
                                    </p>` : ''}
                                </div>
                            </div>
                            
                            <div class="pl-13">
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                    <i class="fas fa-quote-left text-gray-500 dark:text-gray-500 mr-2"></i>
                                    ${result.entry.message.replace(/\n/g, '<br>')}
                                </p>
                            </div>
                        </div>
                    `;
                    
                    // Prepend new entry with animation
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = entryHTML;
                    const newEntry = tempDiv.firstElementChild;
                    
                    // Remove empty state if exists
                    const emptyState = document.querySelector('#entriesContainer > div:not(.entry-card)');
                    if (emptyState) emptyState.remove();
                    
                    document.getElementById('entriesContainer').prepend(newEntry);
                    
                    // Trigger animation
                    setTimeout(() => {
                        newEntry.style.opacity = '1';
                        newEntry.style.transform = 'translateY(0)';
                    }, 50);
                    
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan saat mengirim pesan', 'error');
            } finally {
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Pesan';
                submitBtn.disabled = false;
            }
        });

        // Animate entry cards on load
        document.addEventListener('DOMContentLoaded', () => {
            setInitialThemeIcon();
            
            const entries = document.querySelectorAll('.entry-card');
            entries.forEach((entry, index) => {
                setTimeout(() => {
                    entry.style.opacity = '1';
                    entry.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>