<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("location: ../index.php");
    exit();
}

require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../partials/system_layout.php';

$usuarioModel = new Usuario();
$registros = $usuarioModel->listarPreEstagioCompleto();

$extraHead = '
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        body { background: #f8fafc; }
        .gradient-text { background: linear-gradient(45deg, #0f766e, #14b8a6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
';

renderHead('Relatório de Pré-Estágio - Salaberga', $extraHead);
?>

<div class="lg:pl-72 flex flex-col min-h-screen">
    <?php renderSidebar('relatorios'); ?>

    <div class="flex-1 pt-12 px-4 sm:px-6 lg:px-8">
        <main class="max-w-6xl mx-auto py-6 w-full space-y-6">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-teal-600 to-cyan-500 p-8 text-white">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h1 class="text-2xl font-bold">Relatório de Pré-Estágio</h1>
                            <p class="text-sm opacity-90 mt-1">Histórico dos 4 momentos pós-almoço registrados por aluno</p>
                        </div>
                        <a href="../relatorio.php" class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/20 px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200 bg-white text-left text-sm text-gray-600">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 font-semibold text-gray-700">Aluno</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">Turma</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">Data</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">Hora</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">Momento</th>
                                    <th class="px-6 py-4 font-semibold text-gray-700">Observação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (empty($registros)): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                                            Nenhum registro de pré-estágio encontrado.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($registros as $registro): ?>
                                        <tr class="hover:bg-teal-50/30 transition duration-150">
                                            <td class="px-6 py-4 font-semibold text-gray-900">
                                                <?= htmlspecialchars($registro['aluno'], ENT_QUOTES, 'UTF-8') ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-teal-50 text-teal-800 border border-teal-200">
                                                    <?= htmlspecialchars($registro['serie'] . ' ' . $registro['turma'], ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 font-medium">
                                                <?= date('d/m/Y', strtotime($registro['data_registro'])) ?>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600 font-medium">
                                                <?= date('H:i', strtotime($registro['hora_registro'])) ?>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 border border-slate-200">
                                                    <?= htmlspecialchars(str_replace(['ida_almoco','retorno_almoco','ida_lanche','retorno_lanche'], ['Ida para o almoço','Retorno do almoço','Ida para o lanche','Retorno do lanche'], $registro['momento']), ENT_QUOTES, 'UTF-8') ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-500 max-w-md whitespace-normal break-words text-xs leading-relaxed">
                                                <?= $registro['descricao'] ? nl2br(htmlspecialchars($registro['descricao'], ENT_QUOTES, 'UTF-8')) : '<span class="text-gray-300 italic">Sem observações</span>' ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <?php renderFooter(); ?>
    </div>
</div>
