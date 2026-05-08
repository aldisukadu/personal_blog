<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Blog Pribadi Saya</title>
    <!-- Bootstrap 5 CSS dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        nav {
            box-shadow: 0 2px 8px rgba(0,0,0,0.5);
            background-color: #1a1a1a !important;
            border-bottom: 2px solid #333;
        }
        
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #fff !important;
        }
        
        .nav-link {
            color: #b0b0b0 !important;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: #fff !important;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.75rem;
            background-color: #667eea !important;
        }
        
        .btn {
            border-radius: 5px;
            font-weight: 600;
        }
        
        .card {
            border: 1px solid #333;
            background-color: #252525;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
            transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }
        
        .card-title {
            color: #fff;
            font-weight: bold;
        }
        
        .card-text {
            color: #b0b0b0;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .table {
            background: #252525;
            color: #e0e0e0;
        }
        
        .table thead {
            border-bottom: 2px solid #333;
        }
        
        .table-light {
            background-color: #333 !important;
        }
        
        .btn-sm {
            border-radius: 3px;
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        .container {
            background: rgba(25, 25, 25, 0.8);
            border: 1px solid #333;
            border-radius: 10px;
            padding: 2rem;
            margin-top: 2rem !important;
            margin-bottom: 2rem !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: #fff;
        }
        
        .display-4 {
            color: #667eea !important;
            font-weight: 700;
        }
        
        .display-5 {
            color: #fff !important;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 1.5rem;
        }
        
        .text-muted {
            color: #888 !important;
        }
        
        .alert {
            border-radius: 8px;
            border: 1px solid #444;
            color: #e0e0e0;
        }
        
        .alert-warning {
            background-color: #332200;
            border-color: #665500;
            color: #ffcc00;
        }
        
        .alert-danger {
            background-color: #330000;
            border-color: #660000;
            color: #ff6666;
        }
        
        .alert-success {
            background-color: #003300;
            border-color: #006600;
            color: #66ff66;
        }
        
        footer {
            background: #0f0f0f !important;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.5);
            border-top: 2px solid #333;
            color: #888;
        }

        /* STYLING UNTUK HALAMAN ARTIKEL (post.php) */
        article {
            background: rgba(37, 37, 37, 0.9);
            border: 1px solid #333;
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }

        article header {
            border-bottom: 2px solid #667eea;
            padding-bottom: 2rem;
        }

        article h1 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.4;
            font-size: 2.5rem;
        }

        .article-meta {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
            color: #999;
            font-size: 0.95rem;
        }

        .article-meta span {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .article-content {
            line-height: 1.8;
            font-size: 1.1rem;
            color: #d0d0d0;
        }

        .article-content p {
            margin-bottom: 1.5rem;
            text-align: justify;
            color: #c5c5c5;
        }

        .article-content p:first-letter {
            font-size: 1.1em;
            font-weight: 600;
        }

        .article-content strong {
            color: #667eea;
            font-weight: 700;
        }

        article footer {
            background: transparent;
            border-top: 2px solid #333;
            padding-top: 2rem;
            margin-top: 2rem;
            box-shadow: none;
        }

        article footer .btn {
            transition: all 0.3s;
        }

        article footer .btn:hover {
            transform: translateX(-3px);
        }

        @media (max-width: 768px) {
            article {
                padding: 1.5rem;
            }

            article h1 {
                font-size: 1.8rem;
            }

            .article-meta {
                gap: 1rem;
            }

            .article-content {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR / MENU NAVIGASI -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?php echo isset($_SERVER['HTTP_HOST']) ? '/personal_blog/index.php' : '#'; ?>">
                📝 Blog Saya
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/personal_blog/index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/personal_blog/posts/index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/personal_blog/posts/create.php">Tulis Artikel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/personal_blog/categories/index.php">Kategori</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- CONTAINER UTAMA -->
    <div class="container mt-4">
