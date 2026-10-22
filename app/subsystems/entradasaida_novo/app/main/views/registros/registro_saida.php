<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}

require_once __DIR__ . "/../../models/Aluno.php";

$alunoModel = new Aluno();
$dadosAlunos = $alunoModel->listarAlunos();

$erro = $_GET['erro'] ?? '';
$sucesso = $_GET['sucesso'] ?? '';
$mensagem = '';
$tipoMensagem = '';

if ($erro === 'aluno') {
    $mensagem = 'Selecione um aluno válido antes de continuar.';
    $tipoMensagem = 'error';
} elseif ($erro === 'campos') {
    $mensagem = 'Preencha todos os campos obrigatórios.';
    $tipoMensagem = 'error';
} elseif ($erro === 'registro') {
    $mensagem = 'Houve um erro ao registrar a saída. Tente novamente.';
    $tipoMensagem = 'error';
} elseif ($sucesso === '1') {
    $mensagem = 'Saída registrada com sucesso.';
    $tipoMensagem = 'success';
}

require_once __DIR__ . '/../partials/system_layout.php';

$extraHead = '
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .select2-container .select2-selection--single {
            height: 46px !important;
            border-radius: 12px !important;
            border: 1px solid #d1d5db !important;
            padding-top: 8px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #991b1b !important;
        }
    </style>
';

renderHead('Registrar Saída - Salaberga', $extraHead);
?>
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col">

    <?php renderSidebar('saida'); ?>
    
    <div class="lg:pl-72 flex-1 flex flex-col justify-between">

        <main class="pt-6 px-4 sm:px-6 lg:px-8 flex-1 flex flex-col justify-center">
            <div class="max-w-4xl w-full mx-auto py-4">
                <div class="bg-white rounded-[2rem] border border-gray-200 shadow-sm overflow-hidden">
                    
                    <div class="bg-gradient-to-r from-red-800 to-red-600 p-6 text-white flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold flex items-center gap-3">
                                <i class="fas fa-sign-out-alt"></i>
                                Registrar Saída de Aluno
                            </h2>
                            <p class="opacity-90 text-xs hidden sm:block">Preencha os dados abaixo para autorizar e registrar a saída</p>
                        </div>
                    </div>

                    <div class="p-8">
                        <?php if ($mensagem): ?>
                            <div class="mb-6 p-4 rounded-2xl text-sm font-medium <?php echo $tipoMensagem === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <i class="fas fa-<?php echo $tipoMensagem === 'success' ? 'check-circle' : 'exclamation-circle'; ?> mr-2"></i>
                                <?php echo htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo controllerUrl('registrar_saida'); ?>" method="POST" class="min-h-[380px] flex flex-col justify-between space-y-6" id="formSaida">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                                
                                <div class="space-y-5">
                                    <section>
                                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                            <i class="fas fa-user-graduate text-red-700 text-xs"></i>
                                            Dados do Aluno
                                        </h3>
                                        <div>
                                            <select id="id_aluno" name="id_aluno" class="select-aluno w-full" required>
                                                <option value="">Selecione um aluno</option>
                                                <?php foreach ($dadosAlunos as $aluno): ?>
                                                    <option value="<?php echo $aluno['id']; ?>">
                                                        <?php echo htmlspecialchars($aluno['nome'] . " (" . $aluno['turma'] . " - " . $aluno['serie'] . ")", ENT_QUOTES, 'UTF-8'); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </section>

                                    <section class="space-y-4">
                                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                            <i class="fas fa-user-shield text-red-700 text-xs"></i>
                                            Dados do Responsável
                                        </h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div class="sm:col-span-1">
                                                <select id="tipo_responsavel" name="tipo_responsavel" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 outline-none bg-white transition text-sm" required>
                                                    <option value="" disabled selected>Tipo</option>
                                                    <option value="pai">Pai</option>
                                                    <option value="mae">Mãe</option>
                                                    <option value="outros">Outros</option>
                                                </select>
                                            </div>
                                            <div class="sm:col-span-2">
                                                <input type="text" id="nome_responsavel" name="nome_responsavel" placeholder="Nome completo" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 outline-none transition text-sm" required>
                                            </div>
                                        </div>
                                    </section>
                                    
                                    <section>
                                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                            <i class="fas fa-car text-red-700 text-xs"></i>
                                            Tipo de Condução
                                        </h3>
                                        <div>
                                            <select id="tipo_conducente" name="tipo_conducente" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 outline-none bg-white transition text-sm" required>
                                                <option value="" disabled selected>Selecione o tipo</option>
                                                <option value="uber">Uber</option>
                                                <option value="responsavel">Responsável</option>
                                                <option value="amigo(a)">Amigo(a)</option>
                                                <option value="outros">Outros</option>
                                            </select>
                                        </div>
                                    </section>
                                </div>

                                <div class="space-y-5">
                                    <section class="space-y-4">
                                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                            <i class="fas fa-calendar-alt text-red-700 text-xs"></i>
                                            Detalhes da Saída
                                        </h3>
                                        <div>
                                            <select id="motivo" name="motivo" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 outline-none bg-white transition text-sm" required>
                                                <option value="" disabled selected>Selecione um motivo</option>
                                                <option value="saúde">Saúde</option>
                                                <option value="imprevisto">Imprevisto</option>
                                                <option value="compromisso pessoal">Compromisso Pessoal</option>
                                                <option value="outros">Outros</option>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <input type="date" id="data_saida" name="data_saida" value="<?php echo date('Y-m-d'); ?>" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 outline-none transition text-sm" required>
                                            </div>
                                            <div>
                                                <input type="time" id="hora_saida" name="hora_saida" value="<?php echo date('H:i'); ?>" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 outline-none transition text-sm" required>
                                            </div>
                                        </div>
                                    </section>

                                    <section>
                                        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                            <i class="fas fa-sticky-note text-slate-600 text-xs"></i>
                                            Observações
                                        </h3>
                                        <div>
                                            <textarea id="descricao" name="descricao" placeholder="Adicione observações relevantes..." rows="3" class="w-full px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-600 outline-none resize-none transition text-sm"></textarea>
                                        </div>
                                    </section>
                                </div>

                            </div>

                            <div class="pt-4">
                                <button type="submit" id="btnSubmit" class="w-full bg-gradient-to-r from-red-800 to-red-600 hover:from-red-900 hover:to-red-700 text-white font-bold py-3.5 px-6 rounded-xl transition duration-300 shadow-sm flex items-center justify-center gap-2 text-sm">
                                    <i class="fas fa-paper-plane"></i>
                                    Registrar Saída
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </main>

        <?php renderFooter(); ?>
    </div>

    <script>
        // Validação visual em tempo real com foco em vermelho
        const inputs = document.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.classList.add('border-red-500', 'focus:ring-red-600');
                    this.classList.remove('border-gray-300');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                }
            });
            input.addEventListener('input', function() {
                if (this.value) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-gray-300');
                }
            });
        });

        // Prevenção de envio duplo
        const form = document.getElementById('formSaida');
        form.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('btnSubmit');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Registrando saída...';
        });

        $(document).ready(function () {
            $('.select-aluno').select2({
                placeholder: 'Selecione um aluno',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>