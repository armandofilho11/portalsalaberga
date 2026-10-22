<?php
session_start();

// Check if user is logged in and is an administrator
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'administrador') {
    header("Location: ../view/login.php?error=Por favor, faça login como administrador para acessar o portal");
    exit();
}

// Get user's first name and profile photo
$userData = $_SESSION['usuario'];
$firstName = explode(" ", $userData['nome'])[0]; // Extract first name
$profilePhoto = isset($userData['profile_photo']) && file_exists("../assets/img/profiles/{$userData['profile_photo']}")
    ? "../assets/img/profiles/{$userData['profile_photo']}"
    : "https://via.placeholder.com/120?text=Administrador";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EEEP STGM - Portal do Administrador</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../assets/img/Design sem nome.svg" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        :root {
            --primary-color: #2e7d32;
            --dark-green: #0f2a1d; /* Verde bem escuro */
            --medium-dark-green: #1a3c2e; /* Verde escuro médio */
            --text-color: #333333;
            --bg-color: #f5f7fa;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, var(--bg-color) 0%, #c3cfe2 100%);
            margin: 0;
            overflow-x: hidden;
        }

        .header {
            background: linear-gradient(90deg, var(--dark-green), var(--medium-dark-green));
            padding: 1rem 2rem;
            color: white;
            box-shadow: 0 4px 6px var(--shadow-color);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo-container span {
            font-weight: 700;
            font-size: 1.5rem;
            color: white;
        }

        .portal-title {
            font-size: 0.9rem;
            color: white;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            position: relative;
            height: 40px;
            cursor: pointer; /* Indica que é clicável */
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            object-fit: cover;
            vertical-align: middle;
        }

        .user-info span {
            font-size: 1rem;
            vertical-align: middle;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 6px var(--shadow-color);
            border-radius: 4px;
            min-width: 150px;
            margin-top: 0.5rem;
        }

        /* Removido o :hover para abrir o dropdown, agora controlado por JavaScript */
        .user-dropdown a {
            display: block;
            padding: 0.5rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .user-dropdown a:hover {
            background-color: #f0f0f0;
        }

        .banner {
            width: 100%;
            height: 350px;
            background: url('https://via.placeholder.com/1200x350?text=Portal+do+Administrador+EEEP+STGM') no-repeat center;
            background-size: cover;
            margin-top: 4rem;
            position: relative;
            box-shadow: 0 4px 6px var(--shadow-color);
        }

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 1rem;
        }

        .banner-overlay h1 {
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .banner-overlay p {
            font-size: 1.2rem;
            max-width: 600px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
            min-height: calc(100vh - 350px - 80px - 100px);
        }

        .welcome-message {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-message h2 {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 600;
        }

        .welcome-message p {
            color: #666;
            font-size: 1rem;
            margin-top: 0.5rem;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .card {
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px var(--shadow-color);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px var(--shadow-color);
        }

        .card-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .footer {
            background-color: var(--dark-green);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
            width: 100%;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1002;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px var(--shadow-color);
            position: relative;
            text-align: center;
        }

        .modal-content h2 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

        .profile-pic-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1.5rem;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: linear-gradient(45deg, var(--dark-green), var(--medium-dark-green));
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .camera-icon:hover {
            background-color: var(--primary-color);
        }

        .profile-info p {
            font-size: 1rem;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .profile-info p span {
            font-weight: 600;
            color: var(--primary-color);
        }

        .close-modal {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: var(--text-color);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            .banner {
                height: 250px;
            }
            .banner-overlay h1 {
                font-size: 1.8rem;
            }
            .banner-overlay p {
                font-size: 1rem;
            }
            .content {
                padding: 1.5rem;
            }
            .content h2 {
                font-size: 1.5rem;
            }
            .card {
                width: 100%;
            }
            .modal-content {
                width: 95%;
                padding: 1.5rem;
            }
            .profile-pic {
                width: 100px;
                height: 100px;
            }
            .camera-icon {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .banner {
                height: 200px;
            }
            .banner-overlay h1 {
                font-size: 1.5rem;
            }
            .banner-overlay p {
                font-size: 0.9rem;
            }
            .profile-pic {
                width: 90px;
                height: 90px;
            }
            .camera-icon {
                width: 30px;
                height: 30px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-section">
            <div class="logo-container">
                <span>EEEP STGM</span>
            </div>
            
        </div>
        <div class="user-info">
            <img src="<?php echo $profilePhoto; ?>" alt="User Profile">
            <span><?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?></span>
            <div class="user-dropdown" id="userDropdown">
                <a href="#" id="openProfileModal">Perfil</a>
                <a href="../view/logout.php">Sair</a>
            </div>
        </div>
    </header>

    <div class="banner">
        <div class="banner-overlay">
            <h1>Portal do Administrador</h1>
            <p>Gerencie cardápios, usuários e acompanhe relatórios com facilidade.</p>
        </div>
    </div>

    <main class="content">
        <div class="welcome-message">
            <h2>Olá, <?php echo htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'); ?>!</h2>
            <p>Explore os recursos disponíveis para gerenciar o sistema escolar.</p>
        </div>
        <div class="cards-container">
            <div class="card" onclick="redirectWithLoading('gerenciarCardapio.php')">
                <div class="card-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3>Gerenciar Cardápios</h3>
                <p>Visualize e edite os cardápios da escola de forma prática.</p>
            </div>
            <div class="card" onclick="redirectWithLoading('usuarios.php')">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Gerenciamento de Usuários</h3>
                <p>Adicione, edite e remova usuários do sistema.</p>
            </div>
            <div class="card" onclick="redirectWithLoading('relatorios.php')">
                <div class="card-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Relatórios de Satisfação</h3>
                <p>Acompanhe as avaliações das refeições pelos alunos.</p>
            </div>
            <div class="card" onclick="redirectWithLoading('sugestoes.php')">
                <div class="card-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Sugestões dos Alunos</h3>
                <p>Visualize e gerencie as sugestões enviadas pelos alunos.</p>
            </div>
        </div>
    </main>

    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeModal">×</span>
            <h2>Perfil do Administrador</h2>
            <div class="profile-pic-container">
                <img src="<?php echo $profilePhoto; ?>" alt="Profile Picture" class="profile-pic" id="profilePic">
                <i class="fas fa-camera camera-icon" id="cameraIcon"></i>
            </div>
            <div class="profile-info">
                <p><span>Nome:</span> <?php echo htmlspecialchars($userData['nome'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><span>E-mail:</span> <?php echo htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><span>Tipo de Usuário:</span> <?php echo htmlspecialchars(ucfirst($userData['tipo_usuario']), ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    </div>

    <footer class="bg-black text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Contêiner para as seções EEEP STGM empilhadas -->
                <div class="md:col-span-4 space-y-6">
                    <!-- Seção EEEP STGM original -->
                    <div>
                        <h3 class="text-sm font-semibold">EEEP STGM</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            EEEP Salaberga Torquato Gomes de Matos - Educação profissional de qualidade.
                        </p>
                    </div>
                    <!-- Seção Endereço -->
                    <div>
                        <h3 class="text-sm font-semibold">Endereço</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Avenida Marta Maria Carvalho Nojoza, Outra Banda<br>
                            Maranguape - CE<br>
                            CEP: 61942-355
                        </p>
                    </div>
                </div>
                <!-- Seção Contatos -->
                <div class="md:col-span-4 md:col-start-5 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Contatos</h3>
                    <ul class="space-y-2">
                        <li><a href="https://www.instagram.com/eeepsalabergamtg/" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-brands fa-instagram mr-2"></i>Instagram</a></li>
                        <li><a href="tel:+558533414000" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-phone mr-2"></i>(85) 3341-4000</a></li>
                        <li><a href="mailto:eeepsalab@gmail.com?subject=Contato%20EEEP%20STGM" onclick="window.open('https://mail.google.com/mail/?view=cm&fs=1&to=eeepsalab@gmail.com', '_blank'); return true;" class="text-sm text-gray-400 hover:text-secondary transition-colors duration-300"><i class="fa-solid fa-envelope mr-2"></i>Email</a></li>
                    </ul>
                </div>
                <!-- Seção Desenvolvedores -->
                <div class="md:col-span-4 md:col-start-10 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Desenvolvedores</h3>
                    <p class="text-sm text-gray-400">Christian Santos</p>
                    <p class="text-sm text-gray-400">José Arimatéia</p>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center">
                <p class="text-sm text-gray-400">© 2025. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Remove any existing loading animation when the page loads
        window.addEventListener('load', () => {
            const existingLoading = document.querySelector('div[style*="position: fixed"][style*="background: rgba(0,0,0,0.5)"]');
            if (existingLoading) {
                existingLoading.remove();
            }
        });

        // Clean up loading animation before navigating away
        window.addEventListener('beforeunload', () => {
            const loading = document.querySelector('div[style*="position: fixed"][style*="background: rgba(0,0,0,0.5)"]');
            if (loading) {
                loading.remove();
            }
        });

        // Redirect with loading animation
        function redirectWithLoading(url) {
            const existingLoading = document.querySelector('div[style*="position: fixed"][style*="background: rgba(0,0,0,0.5)"]');
            if (existingLoading) {
                existingLoading.remove();
            }

            const loading = document.createElement('div');
            loading.id = 'loading-overlay';
            loading.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; z-index: 1002;';
            loading.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Carregando...';
            document.body.appendChild(loading);

            setTimeout(() => {
                loading.remove();
                window.location.href = url;
            }, 1000);
        }

        // Ensure cards are clickable
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', (e) => {
                e.preventDefault();
                const href = card.getAttribute('onclick').match(/'([^']+)'/)[1];
                redirectWithLoading(href);
            });
        });

        // Modal functionality
        const profileModal = document.getElementById('profileModal');
        const openModalBtn = document.getElementById('openProfileModal');
        const closeModalBtn = document.getElementById('closeModal');
        const cameraIcon = document.getElementById('cameraIcon');
        const profilePic = document.getElementById('profilePic');
        const userDropdown = document.getElementById('userDropdown');
        const userInfo = document.querySelector('.user-info');

        // Create hidden file input for photo selection
        const hiddenPhotoInput = document.createElement('input');
        hiddenPhotoInput.type = 'file';
        hiddenPhotoInput.accept = 'image/*';
        hiddenPhotoInput.style.display = 'none';
        document.body.appendChild(hiddenPhotoInput);

        openModalBtn.addEventListener('click', (e) => {
            e.preventDefault();
            profileModal.style.display = 'flex';
            userDropdown.style.display = 'none'; // Fecha o dropdown ao abrir o modal
        });

        closeModalBtn.addEventListener('click', () => {
            profileModal.style.display = 'none';
        });

        // Close modal and dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!profileModal.contains(e.target) && e.target !== userInfo) {
                profileModal.style.display = 'none';
                userDropdown.style.display = 'none';
            }
        });

        // Toggle dropdown on user-info click
        userInfo.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
        });

        // Photo selection on camera icon click
        cameraIcon.addEventListener('click', () => {
            hiddenPhotoInput.click();
        });

        hiddenPhotoInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    profilePic.src = event.target.result;
                };
                reader.readAsDataURL(file);

                const formData = new FormData();
                formData.append('profilePhoto', file);

                const response = await fetch('../control/updateProfile.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    location.reload();
                } else {
                    alert(result.error || 'Erro ao atualizar a foto');
                    profilePic.src = '<?php echo $profilePhoto; ?>';
                }
            }
        });
    </script>
</body>
</html>