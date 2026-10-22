<?php
date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}

require_once __DIR__ . '/../../models/Aluno.php';
require_once __DIR__ . '/../partials/system_layout.php';

$alunoModel = new Aluno();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("location: ../relatorios/relatorio_de_saida.php?erro=dados_invalidos");
    exit();
}

$registro = $alunoModel->buscarSaidaPorId($id);
if (!$registro) {
    header("location: ../relatorios/relatorio_de_saida.php?erro=registro_nao_encontrado");
    exit();
}

$alunos = $alunoModel->listarAlunos();

$extraHead = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
renderHead('Editar Saída - Salaberga', $extraHead);
?>

<div class="lg:pl-72 flex flex-col min-h-screen">
    <?php renderSidebar('registros'); ?>

    <div class="flex-1 pt-12 pb-6 px-4 sm:px-6 lg:px-8">
        <main class="max-w-4xl mx-auto py-6 w-full">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100">
                <div class="p-8 border-b">
                    <h2 class="text-2xl font-bold">Editar Saída</h2>
                    <p class="text-sm text-gray-500 mt-1">Altere os dados e salve as mudanças.</p>
                </div>

                <div class="p-8">
                    <form action="../../controllers/controller_alunos.php?action=editar_saida" method="POST" class="space-y-6">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($registro['id'], ENT_QUOTES, 'UTF-8') ?>">

                        <div>
                            <label for="id_aluno" class="block text-xs font-medium text-slate-600">Aluno</label>
                            <select id="id_aluno" name="id_aluno" required class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm">
                                <?php foreach ($alunos as $a): ?>
                                    <option value="<?= $a['id'] ?>" <?= $a['id'] == $registro['id_aluno'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($a['nome'], ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($a['serie'] . ' ' . $a['turma'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="data_saida" class="block text-xs font-medium text-slate-600">Data da Saída</label>
                                <input id="data_saida" name="data_saida" type="date" required value="<?= date('Y-m-d', strtotime($registro['data_saida'])) ?>" class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm" />
                            </div>
                            <div>
                                <label for="hora_saida" class="block text-xs font-medium text-slate-600">Hora da Saída</label>
                                <input id="hora_saida" name="hora_saida" type="time" required value="<?= date('H:i', strtotime($registro['hora_saida'])) ?>" class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tipo_responsavel" class="block text-xs font-medium text-slate-600">Tipo Responsável</label>
                                <select id="tipo_responsavel" name="tipo_responsavel" required class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm bg-white">
                                    <?php $tipos = ['pai','mae','outros']; ?>
                                    <?php foreach ($tipos as $t): ?>
                                        <option value="<?= $t ?>" <?= ($registro['tipo_responsavel'] ?? '') === $t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="nome_responsavel" class="block text-xs font-medium text-slate-600">Nome do Responsável</label>
                                <input id="nome_responsavel" name="nome_responsavel" type="text" required value="<?= htmlspecialchars($registro['nome_responsavel'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm text-slate-700 bg-white shadow-sm" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="motivo" class="block text-xs font-medium text-slate-600">Motivo</label>
                                <select id="motivo" name="motivo" required class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm bg-white">
                                    <?php $opcoes = ['saúde','imprevisto','compromisso pessoal','outros']; ?>
                                    <?php foreach ($opcoes as $op): ?>
                                        <option value="<?= $op ?>" <?= ($registro['motivo'] ?? '') === $op ? 'selected' : '' ?>><?= ucfirst($op) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="tipo_conducente" class="block text-xs font-medium text-slate-600">Tipo Conducente</label>
                                <select id="tipo_conducente" name="tipo_conducente" required class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm bg-white">
                                    <?php $tcon = ['uber','responsavel','amigo(a)','outros']; ?>
                                    <?php foreach ($tcon as $c): ?>
                                        <option value="<?= $c ?>" <?= ($registro['tipo_conducente'] ?? '') === $c ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="descricao" class="block text-xs font-medium text-slate-600">Observação</label>
                            <textarea id="descricao" name="descricao" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-3 mt-1.5 text-sm bg-white"><?= htmlspecialchars($registro['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 px-6 py-3.5 text-sm font-semibold text-white shadow-md hover:bg-amber-700 transition focus:outline-none">
                                <i class="fas fa-save"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php renderFooter(); ?>
