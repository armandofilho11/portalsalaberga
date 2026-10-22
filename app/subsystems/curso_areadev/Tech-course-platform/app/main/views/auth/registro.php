<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#007A33">
    <title>Registro - <?php echo APP_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Applied dashboard design system with CSS variables */
        :root {
            --color-primary: #00b348;
            --color-primary-dark: #007A33;
            --color-secondary: #ffb733;
            --color-background: #0a0a0a;
            --color-surface: #1a1a1a;
            --color-surface-elevated: #242424;
            --color-border: rgba(255, 255, 255, 0.1);
            --color-text-primary: #ffffff;
            --color-text-secondary: #94a3b8;
            --color-text-muted: #64748b;
            --color-error: #ef4444;
            --color-success: var(--color-primary);
            
            --spacing-xs: 0.5rem;
            --spacing-sm: 0.75rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --spacing-2xl: 3rem;
            
            --radius-sm: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.4);
            --shadow-primary: 0 8px 24px rgba(0, 179, 72, 0.25);
        }

        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-background);
            background-image: radial-gradient(circle at 10% 20%, rgba(0, 179, 72, 0.06) 0%, transparent 50%),
                              radial-gradient(circle at 90% 80%, rgba(255, 183, 51, 0.04) 0%, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-lg);
            color: var(--color-text-primary);
            line-height: 1.6;
        }

        .register-container {
            background: var(--color-surface);
            padding: var(--spacing-2xl);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid var(--color-border);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            text-align: center;
            margin-bottom: var(--spacing-xl);
        }

        .register-header h1 {
            color: var(--color-text-primary);
            font-size: 2rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .register-header p {
            color: var(--color-text-secondary);
            font-size: 0.9375rem;
        }

        .input-group {
            position: relative;
            margin-bottom: var(--spacing-lg);
        }

        .input-group input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            background: var(--color-surface-elevated);
            font-family: 'Inter', sans-serif;
            color: var(--color-text-primary);
        }

        .input-group input::placeholder {
            color: var(--color-text-muted);
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(0, 179, 72, 0.1);
            background: var(--color-surface-elevated);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-primary);
            font-size: 1.125rem;
        }

        .btn {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Poppins', sans-serif;
            box-shadow: var(--shadow-primary);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(0, 179, 72, 0.35);
        }

        .btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 0.875rem 1rem;
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-lg);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: var(--color-error);
        }

        .alert-success {
            background: rgba(0, 179, 72, 0.1);
            border: 1px solid rgba(0, 179, 72, 0.3);
            color: var(--color-success);
        }

        .login-link {
            text-align: center;
            margin-top: var(--spacing-lg);
            color: var(--color-text-secondary);
            font-size: 0.875rem;
        }

        .login-link a {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .login-link a:hover {
            color: var(--color-secondary);
        }

        @media (max-width: 480px) {
            .register-container {
                padding: var(--spacing-xl) var(--spacing-lg);
            }

            .register-header h1 {
                font-size: 1.75rem;
            }

            .input-group {
                margin-bottom: var(--spacing-md);
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Área Dev</h1>
            <p>Crie sua conta para começar</p>
        </div>

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

        <form method="POST" action="<?php echo BASE_PATH; ?>/registro">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="nome" placeholder="Nome completo" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="senha" placeholder="Senha" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-user-plus"></i> Registrar
            </button>
        </form>

        <div class="login-link">
            Já tem uma conta? <a href="<?php echo BASE_PATH; ?>/login">Faça login</a>
        </div>
    </div>
</body>
</html>
