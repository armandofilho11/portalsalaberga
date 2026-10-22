<?php
// Adicionar busca de concedente via AJAX
if (isset($_GET['buscar_concedente']) && isset($_GET['nome_concedente'])) {
    $pdo = new PDO('mysql:host=localhost;dbname=u750204740_gestaoestagio', 'root', '');
    $nome = trim($_GET['nome_concedente']);
    $stmt = $pdo->prepare('SELECT id, nome, numero_vagas, perfis, endereco FROM concedentes WHERE nome LIKE :nome LIMIT 1');
    $stmt->bindValue(':nome', '%' . $nome . '%');
    $stmt->execute();
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($dados ? $dados : []);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Seleção de Estágio</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="../../assets/img/Design sem nome.svg" type="image/x-icon">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #F5F5F5; }
        .input-group { position: relative; margin-bottom: 1.5rem; }
        .input-group input, .input-group select { transition: all 0.3s ease; }
        .input-group input:focus, .input-group select:focus { box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1); }
        .input-group label { transition: all 0.3s ease; }
        .error-message { display: none; color: #ff4444; font-size: 0.8rem; margin-top: 0.25rem; }
        .input-group.error .error-message { display: block; }
        .input-group.error input, .input-group.error select { border-color: #ff4444; }
        .input-group.error label { color: #ff4444; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-3xl flex bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="w-full p-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 mb-4">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Cadastro de Seleção de Estágio</h2>
                <p class="text-gray-600">Preencha os dados da seleção</p>
            </div>
            <form method="POST" action="../controllers/Controller-form_selecao.php" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="input-group">
                        <label for="hora" class="block text-sm font-medium text-gray-700 mb-1">Data e Hora</label>
                        <input type="datetime-local" id="hora" name="hora" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            min="<?php echo date('Y-m-d\TH:i'); ?>">
                        <span class="error-message" id="horaError">Por favor, insira a data e hora</span>
                    </div>

                    <div class="input-group">
                        <label for="local" class="block text-sm font-medium text-gray-700 mb-1">Local</label>
                        <input type="text" id="local" name="local" maxlength="100" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite o local">
                        <span class="error-message" id="localError">Por favor, insira o local</span>
                    </div>

                    <div class="input-group col-span-2">
                        <label for="nome_concedente" class="block text-sm font-medium text-gray-700 mb-1">Nome da Empresa</label>
                        <input type="text" id="nome_concedente" name="nome_concedente" required
                            class="w-full input-field px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-[#005A24] transition-all duration-300"
                            placeholder="Digite o nome da empresa">
                        <input type="hidden" id="id_concedente" name="id_concedente">
                        <span class="error-message" id="nome_concedenteError">Por favor, insira o nome da empresa</span>
                        <div id="concedente_info" class="mt-2 text-sm text-green-700"></div>
                    </div>
                </div>

                <?php if (isset($_GET['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <?php
                        switch ($_GET['error']) {
                            case 'campos_vazios':
                                echo 'Por favor, preencha todos os campos obrigatórios.';
                                break;
                            case 'erro_cadastro':
                                echo 'Erro ao cadastrar a seleção. Tente novamente.';
                                break;
                            case 'erro_banco':
                                echo 'Erro no banco de dados. Por favor, tente novamente mais tarde.';
                                break;
                            default:
                                echo 'Ocorreu um erro. Por favor, tente novamente.';
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <input type="submit" name="btn" value="Cadastrar Seleção"
                    class="w-full bg-[#005A24] hover:bg-[#004A1D] text-white py-3 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#005A24] transition-all duration-300 font-semibold text-lg">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const nomeConcedenteInput = document.getElementById('nome_concedente');
            const idConcedenteInput = document.getElementById('id_concedente');
            const localInput = document.getElementById('local');
            const concedenteInfo = document.getElementById('concedente_info');
            let searchTimeout;

            // Função para buscar informações do concedente
            async function buscarConcedente(nome) {
                try {
                    const response = await fetch(`?buscar_concedente=1&nome_concedente=${encodeURIComponent(nome)}`);
                    const data = await response.json();
                    
                    if (data && data.nome) {
                        concedenteInfo.innerHTML = `
                            <div class="p-2 bg-green-50 rounded">
                                <p class="font-medium">${data.nome}</p>
                                <p class="text-sm">Vagas: ${data.numero_vagas}</p>
                                <p class="text-sm">Perfis: ${data.perfis}</p>
                            </div>
                        `;
                        idConcedenteInput.value = data.id;
                        // Preenche o campo local com o endereço da empresa
                        if (data.endereco) {
                            localInput.value = data.endereco;
                            localInput.readOnly = true; // Torna o campo somente leitura
                            localInput.classList.add('bg-gray-50'); // Adiciona um estilo visual para indicar que está preenchido automaticamente
                        }
                    } else {
                        concedenteInfo.innerHTML = '<p class="text-red-600">Empresa não encontrada</p>';
                        idConcedenteInput.value = '';
                        localInput.value = '';
                        localInput.readOnly = false;
                        localInput.classList.remove('bg-gray-50');
                    }
                } catch (error) {
                    console.error('Erro ao buscar empresa:', error);
                    concedenteInfo.innerHTML = '<p class="text-red-600">Erro ao buscar informações</p>';
                    idConcedenteInput.value = '';
                    localInput.value = '';
                    localInput.readOnly = false;
                    localInput.classList.remove('bg-gray-50');
                }
            }

            // Evento para buscar concedente quando o nome é alterado
            nomeConcedenteInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                if (this.value.length >= 3) { // Busca após digitar 3 caracteres
                    searchTimeout = setTimeout(() => {
                        buscarConcedente(this.value);
                    }, 300); // Aguarda 300ms após o usuário parar de digitar
                } else {
                    concedenteInfo.innerHTML = '';
                    idConcedenteInput.value = '';
                    localInput.value = '';
                    localInput.readOnly = false;
                    localInput.classList.remove('bg-gray-50');
                }
            });

            // Permite editar o local manualmente se necessário
            localInput.addEventListener('focus', function() {
                this.readOnly = false;
                this.classList.remove('bg-gray-50');
            });

            // Validação do formulário
            form.addEventListener('submit', function(e) {
                let isValid = true;
                const inputs = form.querySelectorAll('input[required]');
                
                inputs.forEach(input => {
                    if (!input.value) {
                        input.parentElement.classList.add('error');
                        document.getElementById(input.id + 'Error').textContent = 'Por favor, preencha este campo';
                        isValid = false;
                    } else {
                        input.parentElement.classList.remove('error');
                    }
                });

                // Validação específica para data e hora
                const horaInput = document.getElementById('hora');
                if (horaInput.value) {
                    const selectedDate = new Date(horaInput.value);
                    const now = new Date();
                    if (selectedDate < now) {
                        horaInput.parentElement.classList.add('error');
                        document.getElementById('horaError').textContent = 'A data e hora devem ser futuras';
                        isValid = false;
                    }
                }

                // Validação do ID do concedente
                if (!idConcedenteInput.value) {
                    nomeConcedenteInput.parentElement.classList.add('error');
                    document.getElementById('nome_concedenteError').textContent = 'Por favor, selecione uma empresa válida';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Limpar mensagens de erro ao digitar
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    this.parentElement.classList.remove('error');
                });
            });
        });
    </script>
</body>
</html>
