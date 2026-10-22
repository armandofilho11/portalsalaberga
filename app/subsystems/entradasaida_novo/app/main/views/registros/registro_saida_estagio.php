<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}

require_once __DIR__ . "/../../models/Aluno.php";
require_once __DIR__ . '/../partials/system_layout.php';

$alunoModel = new Aluno();
$dadosAlunos = $alunoModel->listarAlunosEstagio();

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
    $mensagem = 'Houve um erro ao registrar a saída de estágio. Tente novamente.';
    $tipoMensagem = 'error';
} elseif ($sucesso === '1') {
    $mensagem = 'Saída de estágio registrada com sucesso.';
    $tipoMensagem = 'success';
}

$extraHead = '
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        ceara: { green: "#008C45", light: "#3CB371", olive: "#8CA03E", orange: "#FFA500" },
                    }
                }
            }
        };
    </script>
    <style>
        body { font-family: "Inter", sans-serif; background: #f8fafc; }
        .gradient-text { background: linear-gradient(45deg, #d97706, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .header-gradient { background: linear-gradient(90deg, #d97706, #f59e0b); }
        .select2-container .select2-selection--single {
            height: 52px !important;
            border-radius: 12px !important;
            border: 1px solid #cbd5e1 !important;
            padding-top: 10px !important;
        }
        .form-input:focus, .form-select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
            border-color: #f59e0b;
        }
        .required-label::after {
            content: " *";
            color: #d97706;
            font-weight: 700;
        }
    </style>
';

renderHead('Registrar Saída de Estágio - Salaberga', $extraHead);
?>

<div class="lg:pl-72 flex flex-col min-h-screen">
    <?php 
        renderSidebar('saida_estagio');
    ?>

    <div class="flex-1 pt-12 pb-6 px-4 sm:px-6 lg:px-8">
        <main class="max-w-3xl mx-auto py-6 w-full">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100">
                
                <div class="header-gradient p-8 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold mb-1">Registrar Saída de Estágio</h2>
                        <p class="opacity-90 text-sm">Use este formulário para registrar saídas de estágio institucional.</p>
                    </div>
                </div>

                <div class="p-8">
                    <?php if (!empty($mensagem)): ?>
                        <div class="mb-6 rounded-xl px-5 py-4 text-sm font-medium <?= $tipoMensagem === 'success' ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : 'bg-amber-50 text-amber-900 border border-amber-200' ?>">
                            <i class="fas fa-<?= $tipoMensagem === 'success' ? 'check-circle text-emerald-600' : 'exclamation-circle text-amber-600'; ?> mr-2"></i>
                            <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="../../controllers/controller_usuario.php?action=registrar_saida_estagio" method="POST" class="space-y-6" id="formEstagio">

                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-slate-800">
                                <span class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-md">
                                    <i class="fas fa-user-graduate"></i>
                                </span>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Identificação do Aluno</h3>
                                    <p class="text-xs text-slate-500">Busque e selecione o estudante liberado.</p>
                                </div>
                            </div>

                            <div>
                                <label for="id_aluno" class="block text-xs font-medium text-slate-600 required-label">Nome do Aluno</label>
                                <div class="mt-1.5">
                                    <select id="id_aluno" name="id_aluno" required class="select-aluno w-full text-sm text-slate-700 bg-white">
                                        <option value="" selected disabled>Digite o nome do estudante...</option>
                                        <?php foreach ($dadosAlunos as $aluno): ?>
                                            <option value="<?= $aluno['id'] ?>">
                                                <?= htmlspecialchars($aluno['nome'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($aluno['serie'], ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars($aluno['turma'], ENT_QUOTES, 'UTF-8') ? ' ' . htmlspecialchars($aluno['turma'], ENT_QUOTES, 'UTF-8') : '' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="data_saida" class="block text-xs font-medium text-slate-600 required-label">Data da Saída</label>
                                <input id="data_saida" name="data_saida" type="date" required value="<?= date('Y-m-d') ?>" class="form-input w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm" />
                            </div>
                            <div>
                                <label for="hora_saida" class="block text-xs font-medium text-slate-600 required-label">Hora da Saída</label>
                                <input id="hora_saida" name="hora_saida" type="time" required value="<?= date('H:i') ?>" class="form-input w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm" />
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="btnSubmit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-6 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-amber-700 transition focus:outline-none focus:ring-4 focus:ring-amber-100">
                                <i class="fas fa-save"></i>
                                Salvar Registro de Estágio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php 
    // Rodapé
    renderFooter(); 
    ?>
</div>

<script>
    $(document).ready(function () {
        $('.select-aluno').select2({
            placeholder: 'Digite o nome do estudante...',
            allowClear: true,
            width: '100%'
        });
    });

    // Prevenção de envio duplo e feedback visual
    const form = document.getElementById('formEstagio');
    form.addEventListener('submit', function () {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Salvando registro...`;
    });
</script>