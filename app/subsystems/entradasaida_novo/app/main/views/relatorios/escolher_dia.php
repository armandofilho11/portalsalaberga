<?php
// Define o fuso horário de Fortaleza/Brasília
date_default_timezone_set('America/Fortaleza');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("location: ../../index.php");
    exit();
}

// Importações do ecossistema e dos Models necessários
require_once __DIR__ . '/../../models/Aluno.php';
require_once __DIR__ . '/../partials/system_layout.php';

// Captura a data selecionada. Se não houver, assume o dia atual (Hoje)
$dataSelecionada = $_POST['data'] ?? ($_GET['data'] ?? date('Y-m-d'));

$alunoModel = new Aluno();

$entradas = method_exists($alunoModel, 'listarEntradasPorData') ? $alunoModel->listarEntradasPorData($dataSelecionada) : [];
$saidas = method_exists($alunoModel, 'listarSaidasPorData') ? $alunoModel->listarSaidasPorData($dataSelecionada) : [];
$saidasEstagio = method_exists($alunoModel, 'listarSaidasEstagioPorData') ? $alunoModel->listarSaidasEstagioPorData($dataSelecionada) : [];

// --- SEPARAÇÃO DOS ALUNOS DE ESTÁGIO POR CURSO ---
$enfermagem = [];
$informatica = [];
$administracao = [];
$edificacoes = [];

foreach ($saidasEstagio as $reg) {
    $curso = mb_strtolower($reg['curso'] ?? '', 'UTF-8');

    if ($curso === 'enfermagem') {
        $enfermagem[] = $reg;
    } elseif ($curso === 'informática' || $curso === 'informatica') {
        $informatica[] = $reg;
    } elseif ($curso === 'administração' || $curso === 'administracao' || $curso === 'meio ambiente') {
        $administracao[] = $reg; // Turma C agrupa Adm e Meio Ambiente
    } elseif ($curso === 'edificações' || $curso === 'edificacoes') {
        $edificacoes[] = $reg;
    } else {
        // Fallback baseado na Letra da Turma
        $turmaLetra = mb_strtoupper(trim($reg['turma'] ?? ''), 'UTF-8');
        if ($turmaLetra === 'B') {
            $informatica[] = $reg;
        } elseif ($turmaLetra === 'C') {
            $administracao[] = $reg;
        } elseif ($turmaLetra === 'D') {
            $edificacoes[] = $reg;
        } else {
            $enfermagem[] = $reg;
        }
    }
}

// Estilos customizados locais mantendo a identidade visual
$extraHead = '
    <style>
        .gradient-text { background: linear-gradient(45deg, #008C45, #3CB371); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .form-card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; }
    </style>
';

// Inicializa a estrutura nativa da página
renderPageFrame('relatorios', 'Relatório por Dia - Salaberga', '', $extraHead);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight"><span class="gradient-text">Relatório Diário</span></h1>
        <p class="text-sm text-gray-500 mt-1">Inspeção detalhada de fluxos para o dia: <strong class="text-gray-700"><?= date('d/m/Y', strtotime($dataSelecionada)) ?></strong></p>
    </div>
    <a href="../relatorio.php" class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition border border-gray-200 shadow-sm w-full sm:w-auto justify-center">
        <i class="fas fa-arrow-left text-xs"></i> Voltar
    </a>
</div>

<div class="form-card p-6 mb-8 shadow-sm">
    <form method="POST" action="" id="formFiltroData" class="flex flex-col sm:flex-row gap-4 items-center justify-center">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <i class="fas fa-calendar-alt text-xl text-gray-400"></i>
            <input 
                type="date" 
                name="data" 
                id="data" 
                value="<?= htmlspecialchars($dataSelecionada, ENT_QUOTES, 'UTF-8') ?>" 
                required
                class="w-full sm:w-48 px-3 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm shadow-sm cursor-pointer"
            >
        </div>
        <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-semibold px-6 py-2.5 rounded-xl text-sm shadow-sm transition duration-150 transform hover:-translate-y-0.5">
            Filtrar Data
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
        <div class="border-b border-gray-100 pb-3 mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-sign-in-alt text-green-600"></i> Entradas do Dia
            </h2>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-xs text-gray-400"></i>
                <input type="text" id="buscaEntrada" placeholder="Buscar entrada..." class="pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg w-full sm:w-44 focus:outline-none focus:ring-1 focus:ring-green-500">
            </div>
        </div>
        <div class="overflow-x-auto rounded-xl border border-gray-200 flex-1">
            <table class="w-full border-collapse text-left text-sm text-gray-600" id="tabelaEntrada">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                    <tr class="border-b border-gray-200">
                        <th class="px-4 py-3">Aluno / Turma</th>
                        <th class="px-4 py-3">Motivo / Descrição</th>
                        <th class="px-4 py-3 text-center">Horário</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($entradas)): ?>
                        <tr class="sem-resultado">
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400 italic">Nenhuma entrada registrada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($entradas as $reg): 
                            $textoMotivo = $reg['motivo'] ?? ($reg['motivo_entrada'] ?? ($reg['tipo'] ?? 'Entrada Regular'));
                        ?>
                            <tr class="hover:bg-gray-50/50 transition linha-registro">
                                <td class="px-4 py-3 nome-aluno">
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($reg['aluno'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <span class="text-xs text-gray-500"><?= htmlspecialchars(($reg['serie'] ?? '') . ' ' . ($reg['turma'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    <div class="mb-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <?= htmlspecialchars($textoMotivo, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($reg['descricao']) && $reg['descricao'] !== $textoMotivo): ?>
                                        <p class="text-gray-500 italic leading-tight mt-1"><?= htmlspecialchars($reg['descricao'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 font-medium">
                                    <?= isset($reg['hora_entrada']) ? date('H:i', strtotime($reg['hora_entrada'])) : '--:--' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
        <div class="border-b border-gray-100 pb-3 mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-sign-out-alt text-red-600"></i> Saídas Regulares
            </h2>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-xs text-gray-400"></i>
                <input type="text" id="buscaSaida" placeholder="Buscar saída..." class="pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg w-full sm:w-44 focus:outline-none focus:ring-1 focus:ring-red-500">
            </div>
        </div>
        <div class="overflow-x-auto rounded-xl border border-gray-200 flex-1">
            <table class="w-full border-collapse text-left text-sm text-gray-600" id="tabelaSaida">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                    <tr class="border-b border-gray-200">
                        <th class="px-4 py-3">Aluno / Turma</th>
                        <th class="px-4 py-3">Motivo</th>
                        <th class="px-4 py-3 text-center">Horário</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($saidas)): ?>
                        <tr class="sem-resultado">
                            <td colspan="3" class="px-4 py-8 text-center text-gray-400 italic">Nenhuma saída registrada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($saidas as $reg): ?>
                            <tr class="hover:bg-gray-50/50 transition linha-registro">
                                <td class="px-4 py-3 nome-aluno">
                                    <div class="font-semibold text-gray-800"><?= htmlspecialchars($reg['aluno'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
                                    <span class="text-xs text-gray-500"><?= htmlspecialchars(($reg['serie'] ?? '') . ' ' . ($reg['turma'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-800 border border-orange-200">
                                        <?= htmlspecialchars($reg['motivo'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 font-medium">
                                    <?= isset($reg['hora_saida']) ? date('H:i', strtotime($reg['hora_saida'])) : '--:--' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
    <div class="border-b border-gray-100 pb-3 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-user-graduate text-purple-600"></i> Painel de Saídas para Estágio Técnico
        </h2>
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-2.5 text-xs text-gray-400"></i>
            <input type="text" id="buscaEstagio" placeholder="Filtrar aluno geral..." class="pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg w-full sm:w-44 focus:outline-none focus:ring-1 focus:ring-purple-500">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6" id="containerCursosEstagio">
        
        <div class="bg-gray-50/50 p-4 rounded-xl border border-red-100 flex flex-col">
            <h3 class="text-xs font-bold uppercase text-red-700 bg-red-50 px-3 py-1.5 rounded-lg mb-3 flex justify-between items-center border border-red-200">
                <span>Enfermagem (A)</span>
                <span class="bg-red-200 text-red-900 px-2 py-0.5 rounded-full text-[10px]"><?= count($enfermagem) ?></span>
            </h3>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left text-gray-600 tabela-curso">
                    <tbody>
                        <?php if(empty($enfermagem)): ?>
                            <tr><td class="text-center py-4 text-gray-400 italic">Nenhum registro</td></tr>
                        <?php else: foreach($enfermagem as $reg): ?>
                            <tr class="border-b border-gray-200 hover:bg-white transition linha-registro">
                                <td class="py-2 pr-2">
                                    <div class="font-semibold text-gray-800 nome-aluno"><?= htmlspecialchars($reg['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <span class="text-[10px] text-gray-400"><?= htmlspecialchars($reg['matricula'], ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="py-2 text-right font-medium text-red-600 whitespace-nowrap">
                                    <i class="far fa-clock mr-0.5"></i><?= date('H:i', strtotime($reg['hora_saida'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-50/50 p-4 rounded-xl border border-blue-100 flex flex-col">
            <h3 class="text-xs font-bold uppercase text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg mb-3 flex justify-between items-center border border-blue-200">
                <span>Informática (B)</span>
                <span class="bg-blue-200 text-blue-900 px-2 py-0.5 rounded-full text-[10px]"><?= count($informatica) ?></span>
            </h3>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left text-gray-600 tabela-curso">
                    <tbody>
                        <?php if(empty($informatica)): ?>
                            <tr><td class="text-center py-4 text-gray-400 italic">Nenhum registro</td></tr>
                        <?php else: foreach($informatica as $reg): ?>
                            <tr class="border-b border-gray-200 hover:bg-white transition linha-registro">
                                <td class="py-2 pr-2">
                                    <div class="font-semibold text-gray-800 nome-aluno"><?= htmlspecialchars($reg['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <span class="text-[10px] text-gray-400"><?= htmlspecialchars($reg['matricula'], ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="py-2 text-right font-medium text-blue-600 whitespace-nowrap">
                                    <i class="far fa-clock mr-0.5"></i><?= date('H:i', strtotime($reg['hora_saida'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-50/50 p-4 rounded-xl border border-emerald-100 flex flex-col">
            <h3 class="text-xs font-bold uppercase text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg mb-3 flex justify-between items-center border border-emerald-200">
                <span>Adm / M. Ambiente (C)</span>
                <span class="bg-emerald-200 text-emerald-900 px-2 py-0.5 rounded-full text-[10px]"><?= count($administracao) ?></span>
            </h3>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left text-gray-600 tabela-curso">
                    <tbody>
                        <?php if(empty($administracao)): ?>
                            <tr><td class="text-center py-4 text-gray-400 italic">Nenhum registro</td></tr>
                        <?php else: foreach($administracao as $reg): ?>
                            <tr class="border-b border-gray-200 hover:bg-white transition linha-registro">
                                <td class="py-2 pr-2">
                                    <div class="font-semibold text-gray-800 nome-aluno"><?= htmlspecialchars($reg['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <span class="text-[10px] text-gray-400"><?= htmlspecialchars($reg['matricula'], ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="py-2 text-right font-medium text-emerald-600 whitespace-nowrap">
                                    <i class="far fa-clock mr-0.5"></i><?= date('H:i', strtotime($reg['hora_saida'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-200 flex flex-col">
            <h3 class="text-xs font-bold uppercase text-gray-700 bg-gray-100 px-3 py-1.5 rounded-lg mb-3 flex justify-between items-center border border-gray-300">
                <span>Edificações (D)</span>
                <span class="bg-gray-300 text-gray-900 px-2 py-0.5 rounded-full text-[10px]"><?= count($edificacoes) ?></span>
            </h3>
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-xs text-left text-gray-600 tabela-curso">
                    <tbody>
                        <?php if(empty($edificacoes)): ?>
                            <tr><td class="text-center py-4 text-gray-400 italic">Nenhum registro</td></tr>
                        <?php else: foreach($edificacoes as $reg): ?>
                            <tr class="border-b border-gray-200 hover:bg-white transition linha-registro">
                                <td class="py-2 pr-2">
                                    <div class="font-semibold text-gray-800 nome-aluno"><?= htmlspecialchars($reg['nome'], ENT_QUOTES, 'UTF-8') ?></div>
                                    <span class="text-[10px] text-gray-400"><?= htmlspecialchars($reg['matricula'], ENT_QUOTES, 'UTF-8') ?></span>
                                </td>
                                <td class="py-2 text-right font-medium text-gray-600 whitespace-nowrap">
                                    <i class="far fa-clock mr-0.5"></i><?= date('H:i', strtotime($reg['hora_saida'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // --- ENVIO AUTOMÁTICO INTELIGENTE AO MUDAR A DATA ---
    const inputData = document.getElementById("data");
    const formData = document.getElementById("formFiltroData");

    if (inputData && formData) {
        inputData.addEventListener("change", function () {
            const valor = inputData.value; 
            if (valor) {
                const partes = valor.split("-");
                if (partes[0] && partes[0].length === 4) {
                    formData.submit();
                }
            }
        });
    }

    // --- FILTROS DE BUSCA INTERNA NAS TABELAS ---
    function configurarFiltro(idInput, idContenedorPai) {
        const input = document.getElementById(idInput);
        const container = document.getElementById(idContenedorPai);
        if (!input || !container) return;

        input.addEventListener("input", function () {
            const termo = input.value.toLowerCase().trim();
            const linhas = container.querySelectorAll(".linha-registro");

            linhas.forEach(linha => {
                const textoLinha = linha.textContent.replace(/\s+/g, ' ').toLowerCase();
                
                if (textoLinha.includes(termo)) {
                    linha.style.display = "";
                } else {
                    linha.style.display = "none";
                }
            });
        });
    }

    configurarFiltro("buscaEntrada", "tabelaEntrada");
    configurarFiltro("buscaSaida", "tabelaSaida");
    configurarFiltro("buscaEstagio", "containerCursosEstagio"); 
});
</script>

<?php
// Fecha os containers estruturais e renderiza o rodapé padrão
closePageFrame();
?>