<?php
require_once('../models/model.php');
$usuarios = lista_usuario();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuário | Sistema</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-image: linear-gradient(135deg, #f6f8fb 0%, #f1f4f9 100%);
        }

        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.9);
        }

        .delete-container {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .input-focus-effect:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
        }

        .btn-hover-effect {
            transition: all 0.2s ease;
        }

        .btn-hover-effect:hover {
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 text-gray-800">
    <div class="w-full max-w-lg delete-container">
        <!-- Main Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 mx-4">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <button onclick="history.back()"
                        class="p-2.5 rounded-xl hover:bg-gray-100/80 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                        Excluir Usuário
                    </h1>
                </div>
            </div>

            <!-- Warning Card -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-100 rounded-2xl p-5 mb-8">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-amber-800 mb-1">Atenção</h3>
                        <p class="text-sm text-amber-700 leading-relaxed">
                            Esta é uma ação irreversível. Uma vez excluído, todos os dados do usuário serão
                            permanentemente removidos do sistema.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="../controllers/controller_excluir/excluir_usuario.php"  class="space-y-8" method="post">
                <div class="space-y-3">
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        Nome do usuário
                    </label>
                    <div class="relative">
                        <!--<input
                            type="text"
                            id="username"
                            name="username"
                            required
                            class="input-focus-effect w-full px-5 py-4 rounded-xl border border-gray-200 
                                   focus:outline-none focus:border-red-400 transition-all duration-200 
                                   placeholder:text-gray-400"
                            placeholder="Digite o nome completo do usuário"
                        >-->
                        <select name="nome_usuario" id="usuario">
                            <?php foreach ($usuarios as $usuario) { ?>

                                <option value="<?php echo $usuario['nome_user'] ?>"><?php echo $usuario['nome_user'] ?></option>
                            <?php } ?>
                        </select>

                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button
                        type="button"
                        onclick="history.back()"
                        class="btn-hover-effect w-full px-6 py-4 bg-white border border-gray-200 text-gray-700 
                               rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-300 
                               transition-all duration-200">
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="btn-hover-effect w-full px-6 py-4 bg-gradient-to-r from-red-600 to-red-500 
                               text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-600 
                               transition-all duration-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                        </svg>
                        Excluir Usuário
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const username = document.getElementById('username').value.trim();

            if (!username) {
                Swal.fire({
                    title: 'Campo obrigatório',
                    text: 'Por favor, insira o nome do usuário.',
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6 py-3'
                    }
                });
                return;
            }

            Swal.fire({
                title: 'Confirmar exclusão',
                html: `Você está prestes a excluir o usuário <strong>${username}</strong>.<br>
                       Esta ação não poderá ser desfeita.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sim, excluir usuário',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6 py-3',
                    cancelButton: 'rounded-xl px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aqui você faria a chamada para excluir o usuário
                    Swal.fire({
                        title: 'Usuário excluído',
                        html: `O usuário <strong>${username}</strong> foi excluído com sucesso.`,
                        icon: 'success',
                        confirmButtonColor: '#dc2626',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-6 py-3'
                        }
                    }).then(() => {
                        document.getElementById('username').value = '';
                    });
                }
            });
        });
    </script>
</body>

</html>