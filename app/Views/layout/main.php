<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Purchase System' ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    
    <style>
        /* CSS Variables untuk theming */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #64748b;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #0f172a;
            --dark-card: #1e293b;
            --light: #f1f5f9;
            --white: #ffffff;
            --gray: #94a3b8;
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --radius: 12px;
            --radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-store-alt"></i>
                <span>Admin<span class="highlight">POS</span></span>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="<?= base_url() ?>" class="<?= current_url() == base_url() ? 'active' : '' ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('products') ?>" class="<?= strpos(current_url(), 'products') !== false ? 'active' : '' ?>">
                        <i class="fas fa-box"></i>
                        <span>Produk</span>
                        <span class="badge-nav" id="productCount">0</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('purchases') ?>" class="<?= strpos(current_url(), 'purchases') !== false ? 'active' : '' ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Pembelian</span>
                        <span class="badge-nav" id="purchaseCount">0</span>
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <span class="user-name">Admin</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Overlay untuk mobile -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title"><?= $title ?? 'Dashboard' ?></h1>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Cari..." id="globalSearch" onkeyup="globalSearch()">
                </div>
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-dot"></span>
                </button>
                <div class="user-menu">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff&size=40" alt="Admin" class="user-avatar">
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success animate__animated animate__fadeInDown" id="alertMessage">
                    <i class="fas fa-check-circle"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger animate__animated animate__fadeInDown" id="alertMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger animate__animated animate__fadeInDown">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    </div>
                    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            
            <?= $content ?? '' ?>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <span>&copy; <?= date('Y') ?> Admin POS System. All rights reserved.</span>
                <span>Version 1.0.0</span>
            </div>
        </footer>
    </main>

    <!-- JavaScript -->
    <script src="<?= base_url('js/main.js') ?>"></script>
    <script>
        // Sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        // Global search
        function globalSearch() {
            const search = document.getElementById('globalSearch').value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        }

        // Auto dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.classList.add('animate__fadeOutUp');
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });

        // Update badge counts
        async function updateBadges() {
            try {
                const response = await fetch('<?= base_url("api/stats") ?>');
                const data = await response.json();
                if (data.success) {
                    document.getElementById('productCount').textContent = data.products;
                    document.getElementById('purchaseCount').textContent = data.purchases;
                }
            } catch (e) {
                console.log('Badge update failed');
            }
        }
        updateBadges();
    </script>
</body>
</html>