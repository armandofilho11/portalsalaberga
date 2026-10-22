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
    $mensagem = 'Não foi possível registrar esse momento do pré-estágio. Verifique se o aluno já registrou esse mesmo intervalo hoje.';
    $tipoMensagem = 'error';
} elseif ($sucesso === '1') {
    $mensagem = 'Registro do pré-estágio salvo com sucesso.';
    $tipoMensagem = 'success';
}

$extraHead = '
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body { font-family: "Inter", sans-serif; background: #f8fafc; }
        .header-gradient { background: linear-gradient(90deg, #0f766e, #14b8a6); }
        .select2-container .select2-selection--single {
            height: 52px !important;
            border-radius: 12px !important;
            border: 1px solid #cbd5e1 !important;
            padding-top: 10px !important;
        }
        .form-input:focus, .form-select:focus, textarea:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.15);
            border-color: #14b8a6;
        }
        .required-label::after {
            content: " *";
            color: #0f766e;
            font-weight: 700;
        }
    </style>
';

renderHead('Pré-Estágio - Salaberga', $extraHead);
?>

<div class="lg:pl-72 flex flex-col min-h-screen">
    <?php renderSidebar('pre_estagio'); ?>

    <div class="flex-1 pt-12 pb-6 px-4 sm:px-6 lg:px-8">
        <main class="max-w-4xl mx-auto py-6 w-full">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100">
                <div class="header-gradient p-8 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-2xl font-bold mb-1">Registro de Pré-Estágio</h2>
                        <p class="opacity-90 text-sm">Registre os 4 momentos após o almoço: ida para o almoço, retorno, ida para o lanche e retorno do lanche.</p>
                    </div>
                </div>

                <div class="p-8">
                    <?php if (!empty($mensagem)): ?>
                        <div class="mb-6 rounded-xl px-5 py-4 text-sm font-medium <?= $tipoMensagem === 'success' ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : 'bg-amber-50 text-amber-900 border border-amber-200' ?>">
                            <i class="fas fa-<?= $tipoMensagem === 'success' ? 'check-circle text-emerald-600' : 'exclamation-circle text-amber-600'; ?> mr-2"></i>
                            <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>

                    <form action="../../controllers/controller_usuario.php?action=registrar_pre_estagio" method="POST" class="space-y-6" id="formPreEstagio">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="id_aluno" class="block text-xs font-medium text-slate-600 required-label">Aluno</label>
                                <select id="id_aluno" name="id_aluno" required class="select-aluno w-full text-sm text-slate-700 bg-white">
                                    <option value="" selected disabled>Digite o nome do estudante...</option>
                                    <?php foreach ($dadosAlunos as $aluno): ?>
                                        <option value="<?= $aluno['id'] ?>">
                                            <?= htmlspecialchars($aluno['nome'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($aluno['serie'], ENT_QUOTES, 'UTF-8') ?><?= htmlspecialchars($aluno['turma'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="momento" class="block text-xs font-medium text-slate-600 required-label">Momento</label>
                                <select id="momento" name="momento" required class="form-select w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm">
                                    <option value="" selected disabled>Selecione o intervalo...</option>
                                    <option value="ida_almoco">Ida para o almoço</option>
                                    <option value="retorno_almoco">Retorno do almoço</option>
                                    <option value="ida_lanche">Ida para o lanche</option>
                                    <option value="retorno_lanche">Retorno do lanche</option>
                                </select>
                            </div>

                            <div>
                                <label for="data_registro" class="block text-xs font-medium text-slate-600 required-label">Data</label>
                                <input id="data_registro" name="data_registro" type="date" required value="<?= date('Y-m-d') ?>" class="form-input w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm" />
                            </div>

                            <div>
                                <label for="hora_registro" class="block text-xs font-medium text-slate-600 required-label">Hora</label>
                                <input id="hora_registro" name="hora_registro" type="time" required value="<?= date('H:i') ?>" class="form-input w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm" />
                            </div>
                        </div>

                        <div>
                            <label for="descricao" class="block text-xs font-medium text-slate-600">Observação</label>
                            <textarea id="descricao" name="descricao" rows="4" placeholder="Descreva alguma observação opcional..." class="form-input w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" id="btnSubmit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-teal-600 px-6 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-teal-700 transition focus:outline-none focus:ring-4 focus:ring-teal-100">
                                <i class="fas fa-save"></i>
                                Salvar Registro do Pré-Estágio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php renderFooter(); ?>
</div>

<script>
    $(document).ready(function () {
        $('.select-aluno').select2({
            placeholder: 'Digite o nome do estudante...',
            allowClear: true,
            width: '100%'
        });
    });

    const form = document.getElementById('formPreEstagio');
    form.addEventListener('submit', function () {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Salvando registro...`;
    });
</script>
