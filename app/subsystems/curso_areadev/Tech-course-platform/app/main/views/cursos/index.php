<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#007A33">
    <title>Cursos - <?php echo APP_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Applying exact same design system as dashboard */
        :root {
            --primary-green: #00b348;
            --dark-green: #007A33;
            --accent-yellow: #ffb733;
            --bg-dark: #0f0f0f;
            --bg-card: #1a1a1a;
            --bg-elevated: #242424;
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-muted: #6b7280;
            --border-subtle: rgba(255, 255, 255, 0.06);
            --border-focus: rgba(0, 179, 72, 0.3);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.4);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.6);
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Navbar - exact same as dashboard */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-subtle);
            backdrop-filter: blur(12px);
        }

        .navbar .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .navbar .brand-group {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: opacity var(--transition-fast);
        }

        .navbar .logo:hover {
            opacity: 0.8;
        }

        .navbar .logo::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--primary-green);
            border-radius: 50%;
            box-shadow: 0 0 12px var(--primary-green);
        }

        .menu-toggle {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: transparent;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .menu-toggle:hover {
            background: var(--bg-elevated);
            border-color: var(--border-focus);
            color: var(--text-primary);
        }

        .navbar .nav-menu {
            display: flex;
            list-style: none;
            gap: 4px;
            align-items: center;
        }

        .navbar .nav-menu a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            transition: all var(--transition-fast);
        }

        .navbar .nav-menu a:hover {
            background: var(--bg-elevated);
            color: var(--text-primary);
        }

        .navbar .nav-menu a i {
            font-size: 16px;
            color: var(--text-muted);
            transition: color var(--transition-fast);
        }

        .navbar .nav-menu a:hover i {
            color: var(--primary-green);
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .navbar .user-info i {
            color: var(--primary-green);
        }

        /* Main Content */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        /* Alerts - exact same as dashboard */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert i {
            font-size: 18px;
            flex-shrink: 0;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        .alert-success {
            background: rgba(0, 179, 72, 0.1);
            border: 1px solid rgba(0, 179, 72, 0.2);
            color: #4ade80;
        }

        /* Page Header - matching dashboard header style */
        .page-header {
            margin-bottom: 40px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .page-header p {
            font-size: 16px;
            color: var(--text-secondary);
        }

        /* Courses Section - exact same as dashboard courses section */
        .courses-section {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 32px;
        }

        .courses-section h2 {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 24px;
        }

        .courses-section h2 i {
            color: var(--primary-green);
            font-size: 22px;
        }

        /* Course Grid */
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 20px;
        }

        /* Course Card - exact same style as dashboard course cards */
        .course-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            overflow: hidden;
            transition: all var(--transition-base);
            cursor: pointer;
            position: relative;
            /* Added flex layout for equal height cards */
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-green), var(--accent-yellow));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform var(--transition-base);
        }

        .course-card:hover {
            border-color: var(--border-focus);
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .course-card:hover::before {
            transform: scaleX(1);
        }

        /* Styling for locked courses */
        .course-card.bloqueado {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .course-card.bloqueado:hover {
            transform: translateY(-2px);
        }

        .course-card-header {
            padding: 24px 24px 16px;
        }

        .course-card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
            line-height: 1.4;
            letter-spacing: -0.01em;
        }

        .course-card-level {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: rgba(0, 179, 72, 0.1);
            border: 1px solid rgba(0, 179, 72, 0.2);
            border-radius: 6px;
            color: var(--primary-green);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Lock badge for blocked courses */
        .course-card-level.locked {
            background: rgba(255, 183, 51, 0.1);
            border: 1px solid rgba(255, 183, 51, 0.2);
            color: var(--accent-yellow);
        }

        .course-card-body {
            padding: 0 24px 24px;
            /* Added flex-grow to push footer to bottom */
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .course-card-description {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
            /* Allow description to grow and push footer down */
            flex: 1;
        }

        .course-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid var(--border-subtle);
            /* Added gap for better spacing on mobile */
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Button - exact same as dashboard */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            background: var(--primary-green);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-fast);
            white-space: nowrap;
            /* Prevent button from shrinking */
            flex-shrink: 0;
        }

        .btn:hover {
            background: var(--dark-green);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 179, 72, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn i {
            font-size: 14px;
        }

        /* Button variants for different actions */
        .btn-secondary {
            background: var(--accent-yellow);
        }

        .btn-secondary:hover {
            background: #f59e0b;
            box-shadow: 0 4px 12px rgba(255, 183, 51, 0.3);
        }

        .btn:disabled {
            background: var(--bg-elevated);
            color: var(--text-muted);
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn:disabled:hover {
            transform: none;
            box-shadow: none;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-base);
            z-index: 99;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive Design - exact same as dashboard */
        @media (max-width: 992px) {
            .menu-toggle {
                display: inline-flex;
            }

            .navbar .nav-menu {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: min(320px, 85vw);
                background: var(--bg-card);
                border-right: 1px solid var(--border-subtle);
                padding: 80px 16px 24px;
                flex-direction: column;
                align-items: stretch;
                gap: 4px;
                transform: translateX(-100%);
                transition: transform var(--transition-base);
                overflow-y: auto;
                z-index: 100;
            }

            body.menu-open .navbar .nav-menu {
                transform: translateX(0);
            }

            body.menu-open {
                overflow: hidden;
            }

            .navbar .nav-menu a {
                width: 100%;
            }

            .navbar .user-info {
                width: 100%;
                justify-content: flex-start;
                display: none;
            }
        }

        @media (max-width: 768px) {
            .navbar .container {
                padding: 0 16px;
                height: 56px;
            }

            .container {
                padding: 24px 16px;
            }

            .page-header {
                margin-bottom: 32px;
            }

            .page-header h1 {
                font-size: 26px;
            }

            .page-header p {
                font-size: 15px;
            }

            .courses-section {
                padding: 24px 16px;
            }

            .course-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            /* Make footer stack vertically on smaller screens */
            .course-card-footer {
                flex-direction: column;
                align-items: stretch;
            }
            
            /* Make buttons full width on mobile */
            .btn {
                width: 100%;
            }
            
            .course-card-status {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .course-grid {
                grid-template-columns: 1fr;
            }

            .btn {
                width: 100%;
            }
        }

        /* Focus Styles */
        .btn:focus-visible,
        .navbar .nav-menu a:focus-visible,
        .menu-toggle:focus-visible {
            outline: 2px solid var(--primary-green);
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="brand-group">
                <a href="<?php echo BASE_PATH; ?>/dashboard" class="logo">Área Dev</a>
            </div>
            <ul class="nav-menu" id="primary-menu">
                <li><a href="<?php echo BASE_PATH; ?>/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="<?php echo BASE_PATH; ?>/cursos"><i class="fas fa-book"></i> Cursos</a></li>
                <li><a href="<?php echo BASE_PATH; ?>/meus-cursos"><i class="fas fa-graduation-cap"></i> Meus Cursos</a></li>
                <li class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo ucfirst($_SESSION['usuario_nivel']); ?></span>
                </li>
                <li><a href="<?php echo BASE_PATH; ?>/logout"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            </ul>
            <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false" aria-controls="primary-menu">
                <i class="fas fa-bars"></i>
                <span>Menu</span>
            </button>
        </div>
    </nav>

    <div class="sidebar-overlay" aria-hidden="true"></div>

    <div class="container">
        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['sucesso'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></span>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h1>Catálogo de Cursos</h1>
            <p>Explore nossos cursos e comece sua jornada de aprendizado</p>
        </div>

        <div class="courses-section">
            <h2><i class="fas fa-book-open"></i> Cursos Disponíveis</h2>

            <div class="course-grid">
                <?php foreach ($cursos as $curso): ?>
                    <?php
                        $bloqueado = false;
                        $nivel_usuario = $_SESSION['usuario_nivel'];
                        $nivel_requerido = $curso['nivel_requerido'];

                        $niveis = ['iniciante' => 1, 'intermediario' => 2, 'avancado' => 3];
                        if ($niveis[$nivel_usuario] < $niveis[$nivel_requerido]) {
                            $bloqueado = true;
                        }
                    ?>
                    <div class="course-card <?php echo $bloqueado ? 'bloqueado' : ''; ?>" 
                         onclick="<?php echo !$bloqueado ? "window.location.href='" . BASE_PATH . "/curso/" . $curso['id'] . "'" : ''; ?>">
                        <div class="course-card-header">
                            <h3 class="course-card-title"><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                            <span class="course-card-level <?php echo $bloqueado ? 'locked' : ''; ?>">
                                <?php if ($bloqueado): ?>
                                    <i class="fas fa-lock"></i>
                                <?php endif; ?>
                                <?php echo ucfirst($curso['nivel_requerido']); ?>
                            </span>
                        </div>
                        <div class="course-card-body">
                            <p class="course-card-description">
                                <?php echo htmlspecialchars(substr($curso['descricao'], 0, 100)); ?>...
                            </p>
                            <div class="course-card-footer">
                                <?php if ($bloqueado): ?>
                                    <span class="course-card-status locked">
                                        <i class="fas fa-lock"></i> Bloqueado
                                    </span>
                                    <button class="btn" disabled>
                                        Nível Insuficiente
                                    </button>
                                <?php elseif ($curso['comprado']): ?>
                                    <span class="course-card-status purchased">
                                        <i class="fas fa-check-circle"></i> Adquirido
                                    </span>
                                    <a href="<?php echo BASE_PATH; ?>/curso/<?php echo $curso['id']; ?>" 
                                       class="btn btn-secondary" 
                                       onclick="event.stopPropagation();">
                                        Acessar <i class="fas fa-arrow-right"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="course-card-status">
                                        <i class="fas fa-tag"></i> Disponível
                                    </span>
                                    <a href="<?php echo BASE_PATH; ?>/curso/<?php echo $curso['id']; ?>" 
                                       class="btn" 
                                       onclick="event.stopPropagation();">
                                        Ver Curso <i class="fas fa-arrow-right"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const toggle = document.querySelector('.menu-toggle');
            const menu = document.getElementById('primary-menu');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (!toggle || !menu || !overlay) return;

            const closeMenu = () => {
                document.body.classList.remove('menu-open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                toggle.setAttribute('aria-expanded', 'false');
            };

            toggle.addEventListener('click', () => {
                const isOpen = document.body.classList.contains('menu-open');
                
                if (isOpen) {
                    closeMenu();
                } else {
                    document.body.classList.add('menu-open');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    toggle.setAttribute('aria-expanded', 'true');
                }
            });

            overlay.addEventListener('click', closeMenu);

            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 992) {
                        closeMenu();
                    }
                });
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && document.body.classList.contains('menu-open')) {
                    closeMenu();
                }
            });
        })();
    </script>
</body>
</html>
