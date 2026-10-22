<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="theme-color" content="#007A33">
    <title><?php echo htmlspecialchars($curso['titulo']); ?> - <?php echo APP_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Aplicando exatamente o mesmo design system do dashboard */
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

        /*Navbar - idêntica ao dashboard */
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

        /* Alerts */
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

        /* Course Header - adaptado do dashboard */
        .course-header {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-md);
        }

        .course-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .course-meta {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .course-level {
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

        .course-description {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .course-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        /* Progress Section */
        .progress-section {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
        }

        .progress-section h3 {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 20px;
        }

        .progress-section h3 i {
            color: var(--primary-green);
            font-size: 22px;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: rgba(20, 20, 20, 0.8);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 12px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-green) 0%, #7FB069 100%);
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 12px rgba(0, 179, 72, 0.5);
            border-radius: 10px;
        }

        .progress-section p {
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 15px;
        }

        /* Modules Section */
        .modules-section {
            background: var(--bg-card);
            border: 1px solid var(--border-subtle);
            border-radius: 16px;
            padding: 32px;
        }

        .modules-section h2 {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 24px;
        }

        .modules-section h2 i {
            color: var(--primary-green);
            font-size: 22px;
        }

        /* Module */
        .module {
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            margin-bottom: 16px;
            overflow: hidden;
            transition: all var(--transition-base);
            background: var(--bg-elevated);
        }

        .module:hover {
            border-color: var(--border-focus);
        }

        .module-header {
            background: rgba(0, 179, 72, 0.08);
            padding: 20px 24px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition-base);
        }

        .module-header:hover {
            background: rgba(0, 179, 72, 0.12);
        }

        .module-header h3 {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 600;
        }

        .module-header span {
            color: var(--primary-green);
            font-size: 20px;
            transition: var(--transition-base);
        }

        .module-content {
            padding: 0;
            display: none;
            background: rgba(15, 15, 15, 0.8);
        }

        .module-content.active {
            display: block;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 2px solid var(--border-subtle);
            background: rgba(20, 20, 20, 0.8);
            overflow-x: auto;
        }

        .tab {
            flex: 1;
            min-width: 140px;
            padding: 16px 24px;
            text-align: center;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-secondary);
            transition: var(--transition-base);
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .tab:hover {
            background: rgba(0, 179, 72, 0.08);
            color: var(--primary-green);
        }

        .tab.active {
            color: var(--primary-green);
            border-bottom-color: var(--primary-green);
            background: rgba(0, 179, 72, 0.12);
        }

        .tab-content {
            display: none;
            padding: 24px;
        }

        .tab-content.active {
            display: block;
        }

        /* Video Item */
        .video-item {
            margin-bottom: 24px;
            padding: 24px;
            background: var(--bg-elevated);
            border-radius: 12px;
            border: 1px solid var(--border-subtle);
            transition: var(--transition-base);
        }

        .video-item:hover {
            border-color: var(--border-focus);
            box-shadow: var(--shadow-md);
        }

        .video-item-header h4 {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .video-item-header h4 i {
            color: var(--primary-green);
        }

        .video-item-header p {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .video-container {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: #000;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-lg);
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        .video-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .video-progress-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: rgba(0, 179, 72, 0.12);
            border: 2px solid var(--primary-green);
            border-radius: 20px;
            color: var(--primary-green);
            font-weight: 600;
            font-size: 14px;
        }

        .video-progress-badge.watched {
            background: rgba(127, 176, 105, 0.15);
            border-color: #7FB069;
            color: #7FB069;
        }

        /* Button */
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

        .btn-secondary {
            background: var(--accent-yellow);
        }

        .btn-secondary:hover {
            background: #f59e0b;
            box-shadow: 0 4px 12px rgba(255, 183, 51, 0.3);
        }

        .btn-mark-watched {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(0, 179, 72, 0.3);
        }

        .btn-mark-watched:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 179, 72, 0.4);
        }

        .btn-mark-watched:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            background: rgba(0, 179, 72, 0.3);
        }

        /* Content Item */
        .content-item {
            padding: 16px 20px;
            border-left: 4px solid var(--primary-green);
            margin-bottom: 14px;
            background: var(--bg-elevated);
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: var(--transition-base);
        }

        .content-item:hover {
            transform: translateX(8px);
            border-left-color: var(--accent-yellow);
            box-shadow: var(--shadow-md);
            background: rgba(0, 179, 72, 0.06);
        }

        .content-item span {
            color: var(--text-primary);
            font-weight: 500;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .content-item span i {
            color: var(--primary-green);
            margin-right: 8px;
        }

        .content-item small {
            color: var(--text-secondary);
            font-size: 12px;
        }

        .content-item a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            background: rgba(0, 179, 72, 0.12);
            border-radius: 8px;
            transition: var(--transition-fast);
            border: 2px solid var(--primary-green);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .content-item a:hover {
            background: var(--primary-green);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 16px rgba(0, 179, 72, 0.4);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.3s;
            backdrop-filter: blur(8px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--bg-card);
            margin: 20px;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 900px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
            animation: slideIn 0.3s;
            border: 1px solid var(--border-subtle);
        }

        .modal-header {
            background: var(--bg-elevated);
            color: white;
            padding: 24px 32px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-subtle);
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-primary);
        }

        .close-modal {
            color: var(--text-secondary);
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition-fast);
            line-height: 1;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .close-modal:hover {
            background: var(--bg-elevated);
            color: var(--text-primary);
        }

        .modal-body {
            padding: 32px;
            background: var(--bg-card);
        }

        /* Question Card */
        .question-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            transition: var(--transition-base);
        }

        .question-card:hover {
            border-color: var(--border-focus);
        }

        .question-card h3 {
            color: var(--text-primary);
            margin-bottom: 18px;
            font-size: 18px;
            line-height: 1.5;
        }

        .option {
            background: rgba(20, 20, 20, 0.8);
            border: 2px solid var(--border-subtle);
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: var(--transition-base);
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-primary);
        }

        .option:hover {
            border-color: var(--primary-green);
            background: rgba(0, 179, 72, 0.06);
        }

        .option.selected {
            border-color: var(--primary-green);
            background: rgba(0, 179, 72, 0.12);
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-fast);
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 16px rgba(0, 179, 72, 0.3);
        }

        .submit-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 179, 72, 0.4);
        }

        .submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Custom Confirm Modal */
        .custom-confirm-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.3s ease;
        }

        .custom-confirm-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .confirm-modal-content {
            background: var(--bg-card);
            border: 2px solid var(--border-subtle);
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            box-shadow: var(--shadow-xl);
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .confirm-modal-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-green) 0%, var(--accent-yellow) 100%);
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px) scale(0.95);
                opacity: 0;
            }
            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .confirm-modal-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .confirm-modal-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(0, 179, 72, 0.15) 0%, rgba(255, 183, 51, 0.15) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 32px;
            color: var(--primary-green);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }

        .confirm-modal-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 10px;
        }

        .confirm-modal-subtitle {
            color: var(--text-secondary);
            font-size: 16px;
            line-height: 1.5;
        }

        .confirm-modal-body {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .confirm-info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 15px;
            color: var(--text-secondary);
        }

        .confirm-info-item:last-child {
            margin-bottom: 0;
        }

        .confirm-info-item i {
            color: var(--primary-green);
            width: 20px;
            text-align: center;
        }

        .confirm-info-item strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        .confirm-modal-footer {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .confirm-btn {
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
            justify-content: center;
        }

        .confirm-btn-primary {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            box-shadow: 0 4px 16px rgba(0, 179, 72, 0.3);
        }

        .confirm-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 179, 72, 0.4);
        }

        .confirm-btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-secondary);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .confirm-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--text-primary);
        }

        .confirm-warning {
            background: rgba(255, 183, 51, 0.08);
            border: 1px solid var(--accent-yellow);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .confirm-warning i {
            color: var(--accent-yellow);
            font-size: 18px;
            margin-top: 2px;
        }

        .confirm-warning-text {
            color: var(--accent-yellow);
            font-size: 14px;
            line-height: 1.4;
            font-weight: 500;
        }

        .result-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 24px;
            font-size: 16px;
        }

        .result-badge.success {
            background: rgba(0, 179, 72, 0.15);
            color: var(--primary-green);
            border: 2px solid var(--primary-green);
        }

        .result-badge.error {
            background: rgba(239, 68, 68, 0.15);
            border: 2px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Responsive Design */
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

            .course-header,
            .modules-section,
            .progress-section {
                padding: 24px;
            }

            .video-item {
                padding: 16px;
            }

            .video-item-header h4 {
                font-size: 16px;
            }

            .video-container {
                margin-left: -16px;
                margin-right: -16px;
                width: calc(100% + 32px);
                border-radius: 0;
                margin-bottom: 16px;
            }

            .video-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .video-progress-badge {
                width: 100%;
                justify-content: center;
            }

            .btn-mark-watched {
                width: 100%;
                justify-content: center;
            }

            .content-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .content-item a {
                width: 100%;
                justify-content: center;
            }

            .modal-content {
                width: 95%;
                margin: 10px;
            }

            .modal-body {
                padding: 20px;
            }
        }

        @media (max-width: 480px) {
            .btn {
                width: 100%;
            }

            .course-actions {
                flex-direction: column;
            }

            .video-item {
                padding: 12px;
            }

            .video-container {
                margin-left: -12px;
                margin-right: -12px;
                width: calc(100% + 24px);
            }

            .video-item-header h4 {
                font-size: 15px;
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

    <!-- Modal de Confirmação Personalizado -->
    <div id="customConfirmModal" class="custom-confirm-modal">
        <div class="confirm-modal-content">
            <div class="confirm-modal-header">
                <div class="confirm-modal-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="confirm-modal-title">Confirmar Envio</div>
                <div class="confirm-modal-subtitle">Revise suas respostas antes de enviar</div>
            </div>
            
            <div class="confirm-modal-body" id="confirmModalBody">
                <!-- Conteúdo dinâmico será inserido aqui -->
            </div>
            
            <div class="confirm-modal-footer">
                <button class="confirm-btn confirm-btn-secondary" onclick="fecharConfirmModal()">
                    <i class="fas fa-times"></i>
                    Cancelar
                </button>
                <button class="confirm-btn confirm-btn-primary" onclick="confirmarEnvioFinal()">
                    <i class="fas fa-paper-plane"></i>
                    Confirmar Envio
                </button>
            </div>
        </div>
    </div>

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

        <div class="course-header">
         
            <h1><?php echo htmlspecialchars($curso['titulo']); ?></h1>
            <div class="course-meta">
                <span class="course-level"><?php echo ucfirst($curso['nivel_requerido']); ?></span>
            </div>
            <p class="course-description"><?php echo nl2br(htmlspecialchars($curso['descricao'])); ?></p>
            
            <div class="course-actions">
                <?php if ($tem_acesso): ?>
                    <span class="btn btn-secondary"><i class="fas fa-check-circle"></i>  Matriculado no Curso</span>
                <?php else: ?>
                    <form method="POST" action="<?php echo BASE_PATH; ?>/comprar-curso/<?php echo $curso['id']; ?>" style="display: inline;">
                        <button type="submit" class="btn"><i class="fas fa-shopping-cart"></i> Matricular</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($tem_acesso && $progresso > 0): ?>
            <div class="progress-section">
                <h3><i class="fas fa-chart-line"></i> Seu Progresso</h3>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $progresso; ?>%"></div>
                </div>
                <p><?php echo $progresso; ?>% concluído</p>
            </div>
        <?php endif; ?>

        <div class="modules-section">
            <h2><i class="fas fa-list"></i> Conteúdo do Curso</h2>

            <?php if (!empty($modulos)): ?>
                <?php 
                require_once __DIR__ . '/../../models/Video.php';
                require_once __DIR__ . '/../../models/Atividade.php';
                $videoModelGlobal = new Video();
                $atividadeModel = new Atividade();
                ?>
                <?php foreach ($modulos as $modulo): ?>
                    <div class="module">
                        <div class="module-header" onclick="toggleModule(this)">
                            <h3><?php echo htmlspecialchars($modulo['titulo']); ?></h3>
                            <span>▼</span>
                        </div>
                        <div class="module-content">
                            <?php
                            $videos = $videoModelGlobal->buscarPorModulo($modulo['id']);
                            $atividades = $atividadeModel->buscarPorModulo($modulo['id']);
                            ?>

                            <div class="tabs">
                                <div class="tab active" onclick="switchTab(event, 'videos-<?php echo $modulo['id']; ?>')">
                                    <i class="fas fa-video"></i> Vídeos
                                </div>
                                <div class="tab" onclick="switchTab(event, 'atividades-<?php echo $modulo['id']; ?>')">
                                    <i class="fas fa-tasks"></i> Atividades
                                </div>
                            </div>

                            <div id="videos-<?php echo $modulo['id']; ?>" class="tab-content active">
                                <?php if (!empty($videos)): ?>
                                    <?php foreach ($videos as $video): ?>
                                        <?php
                                        $progresso_video = 0;
                                        if ($tem_acesso) {
                                            $progresso_video = $videoModelGlobal->verificarSeAssistido($_SESSION['usuario_id'], $video['id']);
                                        }
                                        ?>
                                        <div class="video-item" data-video-id="<?php echo $video['id']; ?>">
                                            <div class="video-item-header">
                                                <h4><i class="fas fa-video"></i><?php echo htmlspecialchars($video['titulo']); ?></h4>
                                                <?php if ($video['descricao']): ?>
                                                    <p><?php echo htmlspecialchars($video['descricao']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if ($tem_acesso): ?>
                                                <div class="video-container">
                                                    <iframe 
                                                        src="<?php echo htmlspecialchars($video['drive_embed_link']); ?>" 
                                                        allow="autoplay; fullscreen" 
                                                        allowfullscreen
                                                        loading="lazy">
                                                    </iframe>
                                                </div>
                                                
                                                <div class="video-actions">
                                                    <div>
                                                        <?php if ($progresso_video >= 90): ?>
                                                            <span class="video-progress-badge watched">
                                                                <i class="fas fa-check-circle"></i> Vídeo Assistido
                                                            </span>
                                                        <?php elseif ($progresso_video > 0): ?>
                                                            <span class="video-progress-badge">
                                                                <i class="fas fa-clock"></i> Em Progresso (<?php echo round($progresso_video); ?>%)
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="video-progress-badge">
                                                                <i class="fas fa-play-circle"></i> Não Assistido
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <button 
                                                        class="btn-mark-watched <?php echo $progresso_video >= 90 ? 'watched' : ''; ?>" 
                                                        onclick="marcarVideoComoAssistido(<?php echo $video['id']; ?>)" 
                                                        data-video-id="<?php echo $video['id']; ?>"
                                                        <?php echo $progresso_video >= 90 ? 'disabled' : ''; ?>>
                                                        <?php if ($progresso_video >= 90): ?>
                                                            <i class="fas fa-check-circle"></i> Já Visualizado
                                                        <?php else: ?>
                                                            <i class="fas fa-check"></i> Marcar como Visualizado
                                                        <?php endif; ?>
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <div style="padding: 40px; text-align: center; background: rgba(30, 30, 30, 0.8); border-radius: 12px;">
                                                    <i class="fas fa-lock" style="font-size: 48px; color: #94a3b8; margin-bottom: 16px;"></i>
                                                    <p style="color: #94a3b8; font-size: 16px;">Este vídeo está bloqueado. Compre o curso para ter acesso.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color: var(--text-secondary); padding: 20px; text-align: center;">
                                        <i class="fas fa-info-circle"></i> Nenhum vídeo disponível neste módulo.
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div id="atividades-<?php echo $modulo['id']; ?>" class="tab-content">
                                <?php if (!empty($atividades)): ?>
                                    <?php foreach ($atividades as $atividade): ?>
                                        <div class="content-item">
                                            <span>
                                                <div><i class="fas fa-tasks"></i><?php echo htmlspecialchars($atividade['titulo']); ?></div>
                                                <small><?php echo ucfirst($atividade['tipo']); ?></small>
                                            </span>
                                            <?php if ($tem_acesso): ?>
                                                <?php
                                                $respostas_atividade = $atividadeModel->verificarRespostasUsuario($_SESSION['usuario_id'], $atividade['id']);
                                                $ja_fez_atividade = !empty($respostas_atividade);
                                                ?>
                                                <a href="#" onclick="openAtividadeModal(<?php echo $atividade['id']; ?>); return false;">
                                                    <?php if ($ja_fez_atividade): ?>
                                                        <i class="fas fa-redo"></i> Refazer Atividade
                                                    <?php else: ?>
                                                        <i class="fas fa-tasks"></i> Fazer Atividade
                                                    <?php endif; ?>
                                                </a>
                                            <?php else: ?>
                                                <span style="color: #94a3b8;"><i class="fas fa-lock"></i> Bloqueado</span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="color: var(--text-secondary); padding: 20px; text-align: center;">
                                        <i class="fas fa-info-circle"></i> Nenhuma atividade disponível neste módulo.
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-secondary);">Este curso ainda não possui módulos cadastrados.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal de Atividade -->
    <div id="atividadeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Carregando...</h2>
                <span class="close-modal" onclick="closeAtividadeModal()">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <p style="text-align: center; color: var(--text-secondary);">Carregando atividade...</p>
            </div>
        </div>
    </div>

    <script>
        function toggleModule(header) {
            const content = header.nextElementSibling;
            const arrow = header.querySelector('span');
            content.classList.toggle('active');
            arrow.textContent = content.classList.contains('active') ? '▲' : '▼';
        }

        function switchTab(event, tabId) {
            const moduleContent = event.target.closest('.module-content');
            const tabs = moduleContent.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            const tabContents = moduleContent.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
        }

        function openAtividadeModal(atividadeId) {
            const modal = document.getElementById('atividadeModal');
            modal.classList.add('active');
            fetch('<?php echo BASE_PATH; ?>/atividade/' + atividadeId)
                .then(response => response.text())
                .then(text => {
                    const data = JSON.parse(text);
                    if (data.success) {
                        renderAtividade(data);
                    } else {
                        document.getElementById('modalBody').innerHTML =
                            '<p style="color: #fca5a5; text-align: center;"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar atividade:', error);
                    document.getElementById('modalBody').innerHTML =
                        '<p style="color: #fca5a5; text-align: center;"><i class="fas fa-exclamation-circle"></i> Erro ao carregar atividade.</p>';
                });
        }

        function closeAtividadeModal() {
            const modal = document.getElementById('atividadeModal');
            modal.classList.remove('active');
        }

        // Função para embaralhar array (Fisher-Yates shuffle)
        function shuffleArray(array) {
            const shuffled = [...array];
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }
            return shuffled;
        }

        function renderAtividade(data) {
            document.getElementById('modalTitle').innerHTML =
                '<i class="fas fa-tasks"></i> ' + data.atividade.titulo;
            let html = '';
            if (data.atividade.descricao) {
                html += '<p style="color: var(--text-secondary); margin-bottom: 20px;">' + data.atividade.descricao + '</p>';
            }
            if (data.tentativas) {
                const tentativas = data.tentativas;
                if (!tentativas.pode_tentar) {
                    if (tentativas.horas_restantes) {
                        html += '<div class="result-badge error">';
                        html += '<i class="fas fa-clock"></i> Você já utilizou todas as 3 tentativas. Tente novamente em ' + tentativas.horas_restantes + ' hora(s).';
                        html += '</div>';
                    } else if (data.nota >= 100) {
                        html += '<div class="result-badge success">';
                        html += '<i class="fas fa-check-circle"></i> Você acertou todas as questões! Não é possível refazer esta atividade.';
                        html += '</div>';
                    } else {
                        html += '<div class="result-badge error">';
                        html += '<i class="fas fa-ban"></i> Não é possível refazer esta atividade.';
                        html += '</div>';
                    }
                } else {
                    if (data.ja_fez) {
                        html += '<div class="result-badge" style="background: rgba(251, 191, 36, 0.15); border-color: rgba(251, 191, 36, 0.3); color: #fde047;">';
                        html += '<i class="fas fa-redo"></i> Refazendo atividade - Você só ganhará recompensa pelas questões que corrigir.';
                        html += '</div>';
                    }
                    if (tentativas.tentativas_restantes > 0) {
                        html += '<div class="result-badge" style="background: rgba(59, 130, 246, 0.15); border-color: rgba(59, 130, 246, 0.3); color: #93c5fd;">';
                        html += '<i class="fas fa-redo"></i> Tentativas restantes: ' + tentativas.tentativas_restantes + ' de 3';
                        html += '</div>';
                    }
                }
            }
            if (data.nota > 0) {
                html += '<div class="result-badge success"><i class="fas fa-check-circle"></i> Nota: ' + data.nota + '%</div>';
            }
            if (data.perguntas && data.perguntas.length > 0) {
                data.perguntas.forEach((pergunta, perguntaIndex) => {
                    html += '<div class="question-card">';
                    html += '<h3>' + (perguntaIndex + 1) + '. ' + pergunta.pergunta + '</h3>';
                    if (pergunta.opcoes && pergunta.opcoes.length > 0) {
                        // Criar array com opções e seus índices originais
                        const opcoesComIndices = pergunta.opcoes.map((opcao, indiceOriginal) => ({
                            opcao: opcao,
                            indiceOriginal: indiceOriginal
                        }));
                        
                        // Embaralhar as opções automaticamente
                        const opcoesEmbaralhadas = shuffleArray(opcoesComIndices);
                        
                        // Renderizar opções embaralhadas, mas mantendo o índice original no value
                        opcoesEmbaralhadas.forEach((item) => {
                            const respostaUsuario = data.respostas_usuario[perguntaIndex];
                            const isSelected = respostaUsuario && respostaUsuario.opcao_index == item.indiceOriginal;
                            const selectedClass = isSelected ? 'selected' : '';
                            html += '<div class="option ' + selectedClass + '" onclick="selectOption(this, ' + perguntaIndex + ', ' + item.indiceOriginal + ')">';
                            html += '<input type="radio" name="pergunta_' + perguntaIndex + '" value="' + item.indiceOriginal + '" ' + (isSelected ? 'checked' : '') + '>';
                            html += '<span>' + item.opcao.texto + '</span>';
                            html += '</div>';
                        });
                    }
                    html += '</div>';
                });
                const podeEnviar = data.tentativas && data.tentativas.pode_tentar !== false;
                const tentativasRestantes = data.tentativas ? data.tentativas.tentativas_restantes : 0;
                if (podeEnviar) {
                    html += '<button class="submit-btn" onclick="confirmarEnvio(' + data.atividade.id + ', ' + tentativasRestantes + ')"><i class="fas fa-paper-plane"></i> Enviar Respostas</button>';
                } else {
                    html += '<button class="submit-btn" style="opacity: 0.5; cursor: not-allowed;" disabled><i class="fas fa-lock"></i> Bloqueado - Aguarde ' + (data.tentativas ? data.tentativas.horas_restantes : 6) + ' hora(s)</button>';
                }
            } else {
                html += '<p style="color: var(--text-secondary); text-align: center;"><i class="fas fa-info-circle"></i> Esta atividade ainda não possui perguntas cadastradas.</p>';
            }
            document.getElementById('modalBody').innerHTML = html;
        }
        
        let globalAtividadeId = null;
        let globalTentativasRestantes = 0;
        let globalRespostas = [];

        function confirmarEnvio(atividadeId, tentativasRestantes) {
            const totalPerguntas = document.querySelectorAll('.question-card').length;
            const radios = document.querySelectorAll('#modalBody input[type="radio"]:checked');
            const respostasSelecionadas = radios.length;
            if (respostasSelecionadas === 0) {
                alert('Por favor, responda pelo menos uma pergunta!');
                return;
            }
            const respostas = [];
            radios.forEach(radio => {
                const perguntaIndex = parseInt(radio.name.replace('pergunta_', ''));
                const opcaoIndex = parseInt(radio.value);
                respostas.push({
                    pergunta_index: perguntaIndex,
                    opcao_index: opcaoIndex
                });
            });
            if (respostasSelecionadas < totalPerguntas) {
                const confirmar = confirm(`Você respondeu ${respostasSelecionadas} de ${totalPerguntas} perguntas.\n\nDeseja continuar mesmo assim?`);
                if (!confirmar) {
                    return;
                }
            }
            globalAtividadeId = atividadeId;
            globalTentativasRestantes = tentativasRestantes;
            globalRespostas = respostas;
            const modalBody = document.getElementById('confirmModalBody');
            modalBody.innerHTML = `
                <div class="confirm-info-item">
                    <i class="fas fa-question-circle"></i>
                    <span>Perguntas respondidas: <strong>${respostasSelecionadas} de ${totalPerguntas}</strong></span>
                </div>
                <div class="confirm-info-item">
                    <i class="fas fa-redo"></i>
                    <span>Tentativas restantes: <strong>${tentativasRestantes} de 3</strong></span>
                </div>
                <div class="confirm-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="confirm-warning-text">
                        Após 3 tentativas, você precisará aguardar 6 horas para tentar novamente.
                    </div>
                </div>
            `;
            document.getElementById('customConfirmModal').classList.add('show');
        }
        
        function fecharConfirmModal() {
            document.getElementById('customConfirmModal').classList.remove('show');
            globalAtividadeId = null;
            globalTentativasRestantes = 0;
            globalRespostas = [];
        }
        
        function confirmarEnvioFinal() {
            if (globalAtividadeId && globalRespostas.length > 0) {
                const atividadeId = globalAtividadeId;
                const respostas = [...globalRespostas];
                fecharConfirmModal();
                enviarRespostas(atividadeId, respostas);
            } else {
                alert('Erro: Dados da atividade não encontrados. Por favor, tente novamente.');
                return;
            }
        }

        function selectOption(element, perguntaIndex, opcaoIndex) {
            const questionCard = element.closest('.question-card');
            questionCard.querySelectorAll('.option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            element.querySelector('input[type="radio"]').checked = true;
        }

        function enviarRespostas(atividadeId, respostas) {
            if (!atividadeId || atividadeId === null || atividadeId === undefined) {
                alert('Erro: ID da atividade não pode ser nulo. Por favor, tente novamente.');
                return;
            }
            if (!respostas || respostas.length === 0) {
                alert('Erro: Nenhuma resposta foi selecionada.');
                return;
            }
            fetch('<?php echo BASE_PATH; ?>/responder-atividade', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    atividade_id: atividadeId,
                    respostas: respostas
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeAtividadeModal();
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar atividade:', error);
                alert('Erro ao enviar atividade. Por favor, tente novamente.');
            });
        }

        if (!window._atividadeModalClickHandlerAttached) {
            window.addEventListener('click', function(event) {
                const modal = document.getElementById('atividadeModal');
                if (modal && event.target === modal) {
                    closeAtividadeModal();
                }
            });
            window.addEventListener('click', function(event) {
                const confirmModal = document.getElementById('customConfirmModal');
                if (confirmModal && event.target === confirmModal) {
                    fecharConfirmModal();
                }
            });
            window.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const confirmModal = document.getElementById('customConfirmModal');
                    if (confirmModal && confirmModal.classList.contains('show')) {
                        fecharConfirmModal();
                    }
                    const atividadeModal = document.getElementById('atividadeModal');
                    if (atividadeModal && atividadeModal.style.display === 'block') {
                        closeAtividadeModal();
                    }
                }
            });
            window._atividadeModalClickHandlerAttached = true;
        }

        function marcarVideoComoAssistido(videoId) {
            const videoItem = document.querySelector(`.video-item[data-video-id="${videoId}"]`);
            const button = videoItem.querySelector('.btn-mark-watched');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';
            fetch('<?php echo BASE_PATH; ?>/marcar-video-assistido', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    video_id: videoId,
                    progresso: 100
                })
            })
            .then(response => response.text())
            .then(text => {
                const data = JSON.parse(text);
                if (data.success) {
                    const progressBadge = videoItem.querySelector('.video-progress-badge');
                    progressBadge.className = 'video-progress-badge watched';
                    progressBadge.innerHTML = '<i class="fas fa-check-circle"></i> Vídeo Assistido';
                    button.className = 'btn-mark-watched watched';
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-check-circle"></i> Já Visualizado';
                    atualizarProgresso();
                } else {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-check"></i> Marcar como Visualizado';
                    alert(data.message || 'Erro ao marcar vídeo como assistido.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check"></i> Marcar como Visualizado';
                alert('Erro ao processar. Tente novamente.');
            });
        }

        function atualizarProgresso() {
            const cursoId = <?php echo $curso['id']; ?>;
            fetch(`<?php echo BASE_PATH; ?>/api/progresso/${cursoId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.progresso !== undefined) {
                    const progressFill = document.querySelector('.progress-fill');
                    const progressText = document.querySelector('.progress-section p');
                    if (progressFill) {
                        progressFill.style.width = data.progresso + '%';
                    }
                    if (progressText) {
                        progressText.textContent = data.progresso + '% concluído';
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao atualizar progresso:', error);
            });
        }

        (function(){
            const toggle = document.querySelector('.menu-toggle');
            const menu = document.getElementById('primary-menu');
            const overlay = document.querySelector('.sidebar-overlay');
            if (!toggle || !menu || !overlay) return;
            const closeMenu = () => {
                document.body.classList.remove('menu-open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                toggle.setAttribute('aria-expanded','false');
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
