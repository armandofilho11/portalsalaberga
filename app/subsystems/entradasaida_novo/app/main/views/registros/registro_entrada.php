<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../partials/system_layout.php';
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
} elseif ($erro === 'registro') {
    $mensagem = 'Houve um erro ao registrar a entrada. Tente novamente.';
    $tipoMensagem = 'error';
} elseif ($sucesso === '1') {
    $mensagem = 'Entrada registrada com sucesso.';
    $tipoMensagem = 'success';
}

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
    </style>
';

renderHead('Registrar Entrada', $extraHead);
?>
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col">

    <?php renderSidebar('entrada'); ?>
    
    <div class="lg:pl-72 flex-1 flex flex-col justify-between">
        
        <main class="pt-6 px-4 sm:px-6 lg:px-8 flex-1 flex flex-col justify-center">
            <div class="max-w-4xl w-full mx-auto py-4">
                <div class="bg-white rounded-[2rem] border border-gray-200 shadow-sm overflow-hidden">

                    <div class="bg-gradient-to-r from-green-800 to-green-600 p-6 text-white flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold">Registrar Entrada</h2>
                            <p class="opacity-90 text-xs hidden sm:block">Controle de fluxo e acesso de alunos ao estabelecimento</p>
                        </div>
                        <i class="fas fa-door-open text-2xl opacity-50"></i>
                    </div>

                    <?php if (!empty($mensagem)): ?>
                        <div class="px-8 pt-4">
                            <div class="rounded-xl p-3 text-sm font-medium <?= $tipoMensagem === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="../../controllers/controller_usuario.php?action=registrar_entrada" method="POST" class="p-8 space-y-6 min-h-[350px] flex flex-col justify-between" id="formEntrada">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="space-y-5">
                                <section>
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                        <i class="fas fa-user-graduate text-green-700 text-xs"></i>
                                        Dados do Aluno
                                    </h3>
                                    <div>
                                        <select name="id_aluno" id="id_aluno" class="select-aluno w-full" required>
                                            <option value="">Selecione um aluno</option>
                                            <?php foreach ($dadosAlunos as $aluno): ?>
                                                <option value="<?= $aluno['id'] ?>">
                                                    <?= htmlspecialchars($aluno['nome'] . " - " . $aluno['serie'] . " " . $aluno['turma'], ENT_QUOTES, 'UTF-8') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </section>

                                <section>
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                        <i class="fas fa-question-circle text-orange-600 text-xs"></i>
                                        Motivo da Entrada
                                    </h3>
                                    <div>
                                        <select name="motivo" id="motivo" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-600 outline-none bg-white transition text-sm" required>
                                            <option value="">Selecione um motivo</option>
                                            <option value="atraso">Atraso</option>
                                            <option value="saúde">Saúde</option>
                                            <option value="compromisso pessoal">Compromisso Pessoal</option>
                                            <option value="outros">Outros</option>
                                        </select>
                                    </div>
                                </section>
                            </div>

                            <div class="space-y-5">
                                <section>
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                        <i class="fas fa-calendar-check text-green-700 text-xs"></i>
                                        Data e Hora
                                    </h3>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <input type="date" name="data_entrada" required value="<?= date('Y-m-d') ?>" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-600 outline-none transition text-sm">
                                        </div>
                                        <div>
                                            <input type="time" name="hora_entrada" required value="<?= date('H:i') ?>" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-green-600 outline-none transition text-sm">
                                        </div>
                                    </div>
                                </section>

                                <section>
                                    <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                        <i class="fas fa-comment-dots text-slate-600 text-xs"></i>
                                        Observações
                                    </h3>
                                    <textarea name="descricao" rows="3" placeholder="Digite observações importantes..." class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-600 outline-none resize-none transition text-sm"></textarea>
                                </section>
                            </div>

                        </div>

                        <div class="pt-4">
                            <button type="submit" name="entrada" id="btnSubmit" class="w-full bg-gradient-to-r from-green-800 to-green-600 hover:from-green-900 hover:to-green-700 text-white font-bold py-3.5 rounded-xl transition duration-300 shadow-sm flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-check-circle"></i>
                                Registrar Entrada
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>

        <?php renderFooter(); ?>
    </div>

    <script>
        $(document).ready(function () {
            $('.select-aluno').select2({
                placeholder: "Selecione um aluno",
                allowClear: true,
                width: '100%'
            });
        });

        const form = document.getElementById('formEntrada');
        form.addEventListener('submit', function () {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Registrando...`;
        });
    </script>
</body>
</html>