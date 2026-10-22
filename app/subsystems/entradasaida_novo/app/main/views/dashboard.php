<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

session_start();
if (!isset($_SESSION['usuario'])) {
    header('location: ../index.php');
    exit();
}

require_once __DIR__ . '/partials/system_layout.php';
require_once __DIR__ . '/../models/Aluno.php';

$alunoModel = new Aluno();
$totalAlunos = $alunoModel->contarAlunosAtivos();
$totalEntradasHoje = $alunoModel->contarEntradasHoje();
$totalSaidasHoje = $alunoModel->contarSaidasHoje();
$totalSaidasEstagioHoje = $alunoModel->contarSaidasEstagioHoje();
$userName = $_SESSION['usuario']['nome'] ?? 'Usuário';

renderHead('Dashboard');
?>
<body class="min-h-screen bg-slate-50 text-slate-900 flex flex-col">
    
    <?php renderSidebar('dashboard'); ?>
    
    <div class="lg:pl-72 flex-1 flex flex-col">
        
        <main class="flex-1 pt-8 pb-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <section class="grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
                    <div class="rounded-[2rem] border border-gray-200 bg-white p-8 shadow-sm">
                        <div class="flex flex-col gap-3">
                            <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Visão geral do sistema</p>
                            <h2 class="text-4xl font-semibold text-slate-900">Painel de controle</h2>
                            <p class="max-w-2xl text-slate-600">Acompanhe as entradas, saídas e saídas de estágio em tempo real. Use o menu lateral para acessar rapidamente os registros e relatórios.</p>
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                            <p class="text-sm text-slate-500">Alunos ativos</p>
                            <p class="mt-4 text-4xl font-semibold text-emerald-600"><?= number_format($totalAlunos) ?></p>
                        </article>

                        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                            <p class="text-sm text-slate-500">Entradas hoje</p>
                            <p class="mt-4 text-4xl font-semibold text-sky-600"><?= number_format($totalEntradasHoje) ?></p>
                        </article>

                        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                            <p class="text-sm text-slate-500">Saídas hoje</p>
                            <p class="mt-4 text-4xl font-semibold text-rose-600"><?= number_format($totalSaidasHoje) ?></p>
                        </article>

                        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                            <p class="text-sm text-slate-500">Saídas de estágio hoje</p>
                            <p class="mt-4 text-4xl font-semibold text-violet-600"><?= number_format($totalSaidasEstagioHoje) ?></p>
                        </article>
                    </div>
                </section>

                <section class="grid gap-6 lg:grid-cols-3">
                    <a href="<?= viewUrl('registros/registro_entrada.php') ?>" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <span class="rounded-2xl bg-sky-100 p-3 text-sky-700"><i class="fas fa-sign-in-alt"></i></span>
                            <span class="text-xs uppercase tracking-[0.35em] text-slate-400">Ação rápida</span>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-slate-900">Registrar Entrada</h3>
                        <p class="mt-3 text-slate-600">Acesse o formulário para registrar entradas de alunos.</p>
                    </a>

                    <a href="<?= viewUrl('registros/registro_saida.php') ?>" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <span class="rounded-2xl bg-rose-100 p-3 text-rose-700"><i class="fas fa-sign-out-alt"></i></span>
                            <span class="text-xs uppercase tracking-[0.35em] text-slate-400">Ação rápida</span>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-slate-900">Registrar Saída</h3>
                        <p class="mt-3 text-slate-600">Acesse o formulário de saída para o aluno.</p>
                    </a>

                    <a href="<?= viewUrl('registros/registro_saida_estagio.php') ?>" class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <span class="rounded-2xl bg-violet-100 p-3 text-violet-700"><i class="fas fa-briefcase"></i></span>
                            <span class="text-xs uppercase tracking-[0.35em] text-slate-400">Ação rápida</span>
                        </div>
                        <h3 class="mt-6 text-xl font-semibold text-slate-900">Saída Estágio</h3>
                        <p class="mt-3 text-slate-600">Registre saídas para estágio direto daqui.</p>
                    </a>
                </section>

            </div>
        </main>

        <?php
        // Rodapé
         renderFooter(); 
        ?>
    </div>
</body>
</html>