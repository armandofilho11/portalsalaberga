<?php 
session_start();
 ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Informações Pessoais</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        .input-transition {
            transition: all 0.3s ease;
        }

        .input-transition:focus {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        input:disabled {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'ceara-green': '#008C45',
                        'ceara-orange': '#FFA500',
                        'ceara-white': '#FFFFFF',
                        primary: '#4CAF50',
                        secondary: '#FFB74D',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl">
        <div class="text-center mb-8 p-4 bg-gray-100 rounded-lg shadow-md">
        <h2 class="text-4xl font-extrabold text-gray-900 flex items-center justify-center">
            <i class="fas fa-user-graduate text-blue-500 mr-2"></i> Informações do Aluno
        </h2>
        <p class="mt-4 text-gray-700 flex items-center justify-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i> Atualize as informações com atenção!
        </p>
    </div>
        
            <div class="bg-white shadow-2xl rounded-2xl p-8 transition-all duration-300 hover:shadow-xl">
    <form class="space-y-6" action="../controllers/atualizar.php" method="post">
        <!-- Dados Pessoais Section -->
        <div class="form-section">
        <h3 class="text-3xl font-extrabold text-gray-900 mb-8 border-b-2 border-gray-300 pb-3 flex items-center">
        <i class="fas fa-user text-blue-500 mr-3"></i> Dados Pessoais
        </h3>
            <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                <div class="md:col-span-6">
                    <input type="text"
                           name="nome"
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 text-center transition-all duration-300 hover:bg-gray-100 disabled:opacity-75"
                           placeholder="Nome Completo"
                           value="<?php echo $_SESSION['nome']; ?>"
                           disabled>
                </div>

                <div class="md:col-span-2">
                    <input type="text"
                           name="nasc"
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 text-center transition-all duration-300 hover:bg-gray-100 disabled:opacity-75"
                           placeholder="Data de Nascimento"
                           value="<?php echo $_SESSION['nasc']; ?>"
                           disabled>
                </div>

                <div class="md:col-span-2">
                    <input type="text"
                           value="<?php echo $_SESSION['curso']; ?>"
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 text-center transition-all duration-300 hover:bg-gray-100 disabled:opacity-75"
                           disabled>
                </div>

                <div class="md:col-span-2">
                    <input type="text"
                           value="<?php echo $_SESSION['publica'] . $_SESSION['bairro'] . $_SESSION['pcd'];  ?>"
                           class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-gray-700 text-center transition-all duration-300 hover:bg-gray-100 disabled:opacity-75"
                           disabled>
                </div>
            </div>
        </div>

        <!-- Notas Alunos Section -->
        <div class="form-section mt-10">
        <h3 class="text-3xl font-extrabold text-gray-900 mb-8 border-b-2 border-gray-300 pb-3 flex items-center">
        <i class="fas fa-graduation-cap text-green-500 mr-3"></i> Notas Alunos
        </h3>
            <div class="grid grid-cols-3 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-6">
                <div class="input-group">
                    <label for="lp" class="block text-sm font-semibold text-gray-700 mb-2">PORTUGUÊS</label>
                    <input type="text"
                           id="lp" oninput="maskNota(this)"
                           name="lp"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['lp']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="ma" class="block text-sm font-semibold text-gray-700 mb-2">MATEMÁTICA</label>
                    <input type="text"
                           id="ma" oninput="maskNota(this)"
                           name="ma"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['ma']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="hi" class="block text-sm font-semibold text-gray-700 mb-2">HISTÓRIA</label>
                    <input type="text"
                           id="hi" oninput="maskNota(this)"
                           name="hi"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['hi']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="ge" class="block text-sm font-semibold text-gray-700 mb-2">GEOGRAFIA</label>
                    <input type="text"
                           id="ge" oninput="maskNota(this)"
                           name="ge"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['ge']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="ci" class="block text-sm font-semibold text-gray-700 mb-2">CIÊNCIAS</label>
                    <input type="text"
                           id="ci" oninput="maskNota(this)"
                           name="ci"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['ci']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="li" class="block text-sm font-semibold text-gray-700 mb-2">INGLÊS</label>
                    <input type="text"
                           id="li" oninput="maskNota(this)"
                           name="li"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['li']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="ar" class="block text-sm font-semibold text-gray-700 mb-2">ARTES</label>
                    <input type="text"
                           id="ar" oninput="maskNota(this)"
                           name="ar"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['ar']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="ef" class="block text-sm font-semibold text-gray-700 mb-2">ED. FÍSICA</label>
                    <input type="text"
                           id="ef" oninput="maskNota(this)"
                           name="ef"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['ef']; ?>"
                           required>
                </div>

                <div class="input-group">
                    <label for="re" class="block text-sm font-semibold text-gray-700 mb-2">RELIGIÃO</label>
                    <input type="text"
                           id="re" oninput="maskNota(this)"
                           name="re"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-center transition-all duration-300 hover:border-green-400"
                           value="<?php echo $_SESSION['re']; ?>"
                           required>
                </div>
            </div>
        </div>

        <!-- Buttons Section -->
        <div class="flex justify-center space-x-4 mt-10 pt-6 border-t border-gray-200">
            <button type="button"
                    class="px-6 py-3.5 border-2 border-gray-400 rounded-xl text-gray-600 hover:bg-gray-50 hover:border-gray-600 transition-all duration-300 flex items-center font-semibold shadow-sm hover:shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Cancelar
            </button>

            <button type="button"
                    id="editButton"
                    class="px-6 py-3.5 bg-[#008C45] text-white rounded-xl transition-all duration-300 flex items-center font-semibold shadow-md hover:shadow-lg hover:bg-[#007038]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Editar
            </button>

            <button type="submit"
                    id="updateButton"
                    name="atualizar_nota"
                    class="hidden px-6 py-3.5 bg-[#008C45] text-white rounded-xl transition-all duration-300 flex items-center font-semibold shadow-md hover:shadow-lg hover:bg-[#007038]">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Atualizar
            </button>
        </div>
    </form>
</div>

<style>
    .input-transition {
        transition: all 0.3s ease-in-out;
    }
    
    .input-group:hover input {
        border-color: #008C45;
    }
    
    .input-group input:focus {
        transform: translateY(-1px);
    }

    /* Adiciona uma animação suave ao carregar a página */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        animation: fadeIn 0.5s ease-out forwards;
    }

    /* Adiciona delay para cada seção */
    .form-section:nth-child(2) {
        animation-delay: 0.2s;
    }

    /* Efeito de hover suave nos botões */
    button {
        transition: all 0.3s ease;
    }

    button:hover {
        transform: translateY(-2px);
    }

    /* Estilo para inputs desabilitados mais elegante */
    input:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
</style>
    <script>
        function disableAllInputs() {
            const inputs = document.querySelectorAll('input:not([disabled])');
            inputs.forEach(input => {
                input.disabled = true;
            });
        }

        function enableAllInputs() {
            const inputs = document.querySelectorAll('input:not([name="nome"]):not([name="nasc"]):not([value="Enfermagem"]):not([value="Pública"])');
            inputs.forEach(input => {
                input.disabled = false;
            });
        }

        document.addEventListener('DOMContentLoaded', disableAllInputs);

        document.getElementById('editButton').addEventListener('click', function() {
            enableAllInputs();
            
            this.classList.add('hidden');
            document.getElementById('updateButton').classList.remove('hidden');
        });

        document.querySelector('button[type="button"]').addEventListener('click', function() {
            disableAllInputs();
            
            document.getElementById('editButton').classList.remove('hidden');
            document.getElementById('updateButton').classList.add('hidden');
        });
       
function maskNota(input) {
    let value = input.value.replace(/[^0-9.]/g, '');

    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('');
    }

    // Special handling for 101-110
    if (['101', '102', '103', '104', '105', '106', '107', '108', '109', '110'].includes(value)) {
        value = '10';
        input.value = value;
        return;
    }

    if (!value.includes('.') && value.length >= 2) {
        value = value.slice(0, 1) + '.' + value.slice(1);
    }

    // Ensure maximum 2 decimal places
    if (value.includes('.')) {
        const decimals = value.split('.')[1];
        if (decimals.length > 2) {
            value = value.slice(0, value.indexOf('.') + 3);
        }
    }

    if (input.value === '10') {
        input.value = '10';
        return;
    }

    const numericValue = parseFloat(value);
    if (numericValue > 10) {
        value = value[0] + '.0';
    }

    input.value = value;
}
    </script>
</body>
</html>