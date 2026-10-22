<?php
require_once('../models/select_model.php');
require_once('../models/sessions.php');
$select_model = new select_model();
$session = new sessions;
$session->autenticar_session();

$qtd = null;
if (isset($_POST['aprovar']) && !empty($_POST['selecionados'])) {
    $selecionados = [];
    foreach ($_POST['selecionados'] as $valor) {
        list($id_aluno, $id_vaga) = explode('|', $valor);
        $selecionados[] = ['id_aluno' => $id_aluno, 'id_vaga' => $id_vaga];
    }
    $qtd = $select_model->aprovar_selecionados($selecionados);
}
if (!function_exists('dashboard_format_decimal')) {
    function dashboard_format_decimal($value, int $max_decimals = 3): string
    {
        if ($value === null || $value === '') return '-';
        if (!is_numeric($value)) return (string) $value;
        $formatted = number_format((float) $value, $max_decimals, ',', '');
        return rtrim(rtrim($formatted, '0'), ',');
    }
}
if (!function_exists('dashboard_normalize_area')) {
    function dashboard_normalize_area($area): string
    {
        $area = trim((string) $area);
        $lower = function_exists('mb_strtolower') ? mb_strtolower($area, 'UTF-8') : strtolower($area);
        if (strpos($lower, 'desenvolv') !== false) return 'desenvolvimento';
        if (strpos($lower, 'design') !== false || strpos($lower, 'midia') !== false || strpos($lower, 'mídia') !== false) return 'design';
        if (strpos($lower, 'suporte') !== false || strpos($lower, 'rede') !== false) return 'suporte';
        if (strpos($lower, 'tutoria') !== false) return 'tutoria';
        return $lower;
    }
}
if (!function_exists('dashboard_area_label')) {
    function dashboard_area_label($area): string
    {
        switch (dashboard_normalize_area($area)) {
            case 'desenvolvimento': return 'Desenvolvimento';
            case 'design': return 'Design';
            case 'suporte': return 'Suporte';
            case 'tutoria': return 'Tutoria';
            default:
                $area = trim((string) $area);
                return $area !== '' ? $area : '-';
        }
    }
}
if (!function_exists('dashboard_area_badges')) {
    function dashboard_area_badges(array $aluno, int $perfil_id): array
    {
        $perfil_map = [1 => 'desenvolvimento', 2 => 'design', 3 => 'suporte', 4 => 'tutoria'];
        $perfil_atual = $perfil_map[$perfil_id] ?? '';
        $area1 = dashboard_normalize_area($aluno['perfil_opc1'] ?? '');
        $area2 = dashboard_normalize_area($aluno['perfil_opc2'] ?? '');
        $badge1 = ['label' => dashboard_area_label($aluno['perfil_opc1'] ?? ''), 'area' => $area1];
        $badge2 = ['label' => dashboard_area_label($aluno['perfil_opc2'] ?? ''), 'area' => $area2];
        if ($area1 === '' && $area2 === '') return [];
        if ($area1 === $area2 || $area2 === '') return [$badge1];
        if ($perfil_atual === $area1) return [$badge1];
        return [$badge1, $badge2];
    }
}
if (!function_exists('area_tw_classes')) {
    function area_tw_classes($area): string
    {
        switch (dashboard_normalize_area($area)) {
            case 'desenvolvimento':
                return 'bg-blue-600/20 text-blue-300 border border-blue-500/35';
            case 'design':
                return 'bg-purple-600/20 text-purple-300 border border-purple-500/35';
            case 'suporte':
                return 'bg-amber-600/20 text-amber-300 border border-amber-500/35';
            case 'tutoria':
                return 'bg-emerald-600/20 text-emerald-300 border border-emerald-500/35';
            default:
                return 'bg-slate-500/15 text-slate-300 border border-slate-500/25';
        }
    }
}
if (!function_exists('area_icon')) {
    function area_icon($area): string
    {
        switch (dashboard_normalize_area($area)) {
            case 'desenvolvimento': return 'fa-code';
            case 'design': return 'fa-paint-brush';
            case 'suporte': return 'fa-network-wired';
            case 'tutoria': return 'fa-chalkboard-teacher';
            default: return 'fa-question';
        }
    }
}

$perfis_ranking = $select_model->perfis_dashboard_ranking();
$ranking_por_perfil = [];
foreach ($perfis_ranking as $perfil_row) {
    $ranking_por_perfil[] = [
        'id' => (int) $perfil_row['id'],
        'nome_perfil' => $perfil_row['nome_perfil'],
        'alunos' => $select_model->alunos_ranking_dashboard_por_perfil_id((int) $perfil_row['id']),
    ];
}

$empresas_lista = $select_model->concedentes();
$fichas_concedentes_lista = $select_model->fichas_concedentes();

$vagas_lista = $select_model->vagas('', '', '');
$perfis_vaga_opcoes = [
    '1' => 'Desenvolvimento',
    '2' => 'Design/Mídia',
    '4' => 'Tutoria',
    '3' => 'Suporte/Redes',
];

$vagas_com_alunos_sel = $select_model->vagas_com_alunos();

$dados_alunos_todos = $select_model->alunos_aptos_curso();

$localidades_opcoes = [
    'Maranguape' => 'Maranguape',
    'Maracanau' => 'Maracanaú',
    'Maranguape/Maracanau' => 'Maranguape/Maracanaú',
];

if (!function_exists('dashboard_normalize_localidade')) {
    function dashboard_normalize_localidade($localidade): string
    {
        $localidade = trim((string) $localidade);
        $normalizada = function_exists('mb_strtolower') ? mb_strtolower($localidade, 'UTF-8') : strtolower($localidade);
        if ($normalizada === 'mp' || $normalizada === 'maranguape') return 'Maranguape';
        if ($normalizada === 'mc' || $normalizada === 'maracanaú' || $normalizada === 'maracanau' || $normalizada === 'maracana·') return 'Maracanau';
        if ($normalizada === 'mp/mc' || $normalizada === 'ambos' || strpos($normalizada, 'maranguape/') === 0) return 'Maranguape/Maracanau';
        return $localidade;
    }
}

if (!function_exists('dashboard_localidade_label')) {
    function dashboard_localidade_label($localidade): string
    {
        return [
            'Maranguape' => 'MP',
            'Maracanau' => 'MA',
            'Maranguape/Maracanau' => 'MP/MA',
        ][dashboard_normalize_localidade($localidade)] ?? '-';
    }
}

if (!function_exists('dashboard_localidade_display')) {
    function dashboard_localidade_display($localidade): string
    {
        return [
            'Maranguape' => 'MP',
            'Maracanau' => 'MA',
            'Maranguape/Maracanau' => 'MP/MA',
        ][dashboard_normalize_localidade($localidade)] ?? trim((string) $localidade) ?: '-';
    }
}

if (!function_exists('dashboard_localidade_badge_classes')) {
    function dashboard_localidade_badge_classes($localidade): string
    {
        switch (dashboard_normalize_localidade($localidade)) {
            case 'Maranguape':
                return 'bg-emerald-500/25 text-emerald-900 border border-emerald-500/60';
            case 'Maracanau':
                return 'bg-cyan-500/25 text-cyan-900 border border-cyan-500/60';
            case 'Maranguape/Maracanau':
                return 'bg-violet-500/25 text-violet-900 border border-violet-500/60';
            default:
                return 'bg-slate-500/25 text-slate-800 border border-slate-400/60';
        }
    }
}

foreach ($dados_alunos_todos as &$dado_aluno) {
    $dado_aluno['localidade'] = dashboard_normalize_localidade($dado_aluno['localidade'] ?? '');
}
unset($dado_aluno);

$CLS = [
    'btn_primary'   => 'inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-br from-green-700 to-green-600 shadow-lg shadow-green-700/25 hover:from-green-600 hover:to-green-500 hover:-translate-y-0.5 hover:shadow-green-600/40 transition-all duration-300',
    'btn_secondary' => 'inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-white/80 bg-white/5 border border-white/10 hover:bg-white/10 hover:-translate-y-0.5 hover:border-white/20 transition-all duration-300',
    'btn_danger'    => 'inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-white bg-gradient-to-br from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 transition-all duration-300',
    'input'         => 'w-full bg-neutral-800/80 border-2 border-neutral-700/80 text-white rounded-xl px-4 py-3 placeholder-white/40 focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/20 transition-all',
    'select'        => 'w-full bg-neutral-800/80 border-2 border-neutral-700/80 text-white rounded-xl pl-4 pr-10 py-3 appearance-none focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-600/20 transition-all',
    'label'         => 'block text-sm font-medium text-gray-300 mb-1',
    'card'          => 'bg-neutral-800/95 rounded-2xl p-6 shadow-xl border border-green-700/20 hover:border-green-500/40 hover:shadow-green-700/10 transition-all duration-300',
    'sidebar_link'  => 'nav-link flex items-center px-4 py-3 rounded-lg mb-1 text-white transition-all duration-200 hover:bg-green-700/20 hover:text-green-400 hover:translate-x-1',
    'sidebar_active'=> 'bg-green-700/30 text-green-400 font-semibold shadow-md shadow-green-700/10',
    'modal_overlay' => 'modal_overlay fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-3 md:p-5 overflow-hidden',
    'modal_box'     => 'modal_box bg-gradient-to-br from-neutral-800/95 to-neutral-900/95 border border-white/5 shadow-2xl rounded-2xl p-6 sm:p-8 w-[92vw] max-w-[42rem] min-w-0',
    'checkbox'      => 'w-5 h-5 rounded accent-green-600 cursor-pointer',
];
?>
<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a1a1a">
    <meta name="description" content="Sistema de Estágio - Painel">
    <script>
        try {
            if (localStorage.getItem('estagio-theme') === 'light') {
                document.documentElement.classList.add('light-mode');
                document.documentElement.classList.remove('dark');
            }
        } catch (error) {
            console.warn('Não foi possível carregar a preferência de tema do Estágio:', error);
        }
    </script>
    <title>Painel - Sistema de Estágio</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#007A33', 50: '#00FF6B', 400: '#00C250', 500: '#007A33', 700: '#00521A' },
                        secondary: { DEFAULT: '#FFA500', 500: '#FFA500' },
                        dark: { DEFAULT: '#1a1a1a', 50: '#2d2d2d', 400: '#1a1a1a', 700: '#111111' }
                    },
                    keyframes: {
                        fadeIn: { from: { opacity: 0 }, to: { opacity: 1 } },
                        slideUp: { from: { opacity: 0, transform: 'translateY(16px)' }, to: { opacity: 1, transform: 'translateY(0)' } }
                    },
                    animation: {
                        fadeIn: 'fadeIn .3s ease-out both',
                        slideUp: 'slideUp .35s ease-out both'
                    }
                }
            }
        }
    </script>
    <style>
        html.light-mode {
            background: #eaf3e8;
        }

        html.light-mode body,
        html.light-mode .bg-\[\#1a1a1a\] {
            background-color: #eaf3e8 !important;
            color: #173b28;
        }

        html.light-mode body > div > div.flex-1 {
            background-image: none !important;
            background-color: #eaf3e8 !important;
        }

        html.light-mode aside,
        html.light-mode #mobileSidebar {
            background: #064d2b !important;
            border-color: #064d2b !important;
            box-shadow: 4px 0 18px rgba(15, 61, 36, 0.08);
        }

        html.light-mode .bg-gradient-to-b,
        html.light-mode .bg-gradient-to-br {
            background-image: none !important;
            background-color: #ffffff !important;
        }

        html.light-mode aside.bg-gradient-to-b,
        html.light-mode #mobileSidebar.bg-gradient-to-b {
            background-color: #07552f !important;
        }

        html.light-mode aside .text-gray-400,
        html.light-mode #mobileSidebar .text-gray-400 {
            color: #d7efe1 !important;
        }

        html.light-mode aside .nav-link,
        html.light-mode #mobileSidebar .nav-link {
            color: #f5fff8 !important;
        }

        html.light-mode aside .nav-link:hover,
        html.light-mode #mobileSidebar .nav-link:hover,
        html.light-mode aside .sidebar_active {
            background-color: rgba(255, 255, 255, 0.17) !important;
            color: #ffbd17 !important;
        }

        html.light-mode aside .sidebar-brand-title,
        html.light-mode #mobileSidebar .sidebar-brand-title {
            color: #ffffff !important;
        }

        html.light-mode .bg-neutral-800,
        html.light-mode .bg-neutral-800\/95,
        html.light-mode .bg-neutral-900\/95 {
            background-color: #ffffff !important;
        }

        html.light-mode .bg-neutral-900\/25,
        html.light-mode .bg-neutral-900\/30,
        html.light-mode .bg-white\/5,
        html.light-mode .bg-white\/10 {
            background-color: #eef3f0 !important;
        }

        html.light-mode .text-white,
        html.light-mode .text-gray-200,
        html.light-mode .text-gray-300,
        html.light-mode .text-gray-100 {
            color: #24332c !important;
        }

        html.light-mode .text-gray-400,
        html.light-mode .text-gray-500 {
            color: #607268 !important;
        }

        html.light-mode .border-white\/5,
        html.light-mode .border-white\/10,
        html.light-mode .border-gray-700 {
            border-color: #d7e3dc !important;
        }

        html.light-mode .hover\:bg-white\/10:hover {
            background-color: #e2ebe6 !important;
        }

        html.light-mode .vaga-card {
            background: #ffffff !important;
            border-color: #d8e5dc !important;
            box-shadow: 0 8px 24px rgba(36, 51, 44, 0.08);
        }

        html.light-mode .vaga-card-toggle {
            background: #e6f0e9;
            color: #155c37;
        }

        html.light-mode .vaga-card-toggle:hover,
        html.light-mode .vaga-card-toggle:focus-visible {
            background: #cfe5d6;
            color: #0b4628;
        }

        html.light-mode .kanban-column-sem .vaga-card { border-left-color: #dc2626 !important; }
        html.light-mode .kanban-column-em .vaga-card { border-left-color: #d97706 !important; }
        html.light-mode .kanban-column-fin .vaga-card { border-left-color: #059669 !important; }

        html.light-mode .dashboard-card,
        html.light-mode .ranking-col-card,
        html.light-mode .dash-area-card {
            background: #ffffff !important;
            border-color: #e2ebe5 !important;
            box-shadow: 0 5px 16px rgba(30, 74, 49, 0.06);
        }

        html.light-mode .ranking-head {
            background: #edf6ef !important;
            color: #39704f !important;
            border-color: #d8e8dc !important;
        }

        html.light-mode .ranking-col-card.accent-dev .ranking-head {
            background: #dbeafe !important;
            color: #2563c7 !important;
            border-color: #a5c7ff !important;
        }

        html.light-mode .ranking-col-card.accent-design .ranking-head {
            background: #f0ddff !important;
            color: #8a3fc2 !important;
            border-color: #d6a8ff !important;
        }

        html.light-mode .ranking-col-card.accent-sup .ranking-head {
            background: #fff0c7 !important;
            color: #9a6800 !important;
            border-color: #f2c45b !important;
        }

        html.light-mode .ranking-col-card.accent-tut .ranking-head {
            background: #d8f5e7 !important;
            color: #087443 !important;
            border-color: #91d8b1 !important;
        }

        html.light-mode .ranking-aluno-card,
        html.light-mode .aluno-row-mobile {
            background: #ffffff !important;
            border-color: #d8e5dc !important;
            color: #24332c !important;
        }

        html.light-mode .ranking-col-card.accent-dev .ranking-aluno-card {
            border-left-color: #3b82f6 !important;
        }

        html.light-mode .ranking-col-card.accent-design .ranking-aluno-card {
            border-left-color: #a855f7 !important;
        }

        html.light-mode .ranking-col-card.accent-sup .ranking-aluno-card {
            border-left-color: #f59e0b !important;
        }

        html.light-mode .ranking-col-card.accent-tut .ranking-aluno-card {
            border-left-color: #10b981 !important;
        }

        html.light-mode .ranking-col-card.accent-dev {
            border-top-color: #3b82f6 !important;
        }

        html.light-mode .ranking-col-card.accent-design {
            border-top-color: #a855f7 !important;
        }

        html.light-mode .ranking-col-card.accent-sup {
            border-top-color: #f59e0b !important;
        }

        html.light-mode .ranking-col-card.accent-tut {
            border-top-color: #10b981 !important;
        }

        html.light-mode .ranking-aluno-details {
            background: #f7fbf8 !important;
            margin-top: 0.5rem;
            padding: 0.65rem !important;
            border-top: 1px solid #e2ebe5;
            border-radius: 0.4rem;
            color: #203b2b !important;
            font-size: 0.75rem;
        }

        html.light-mode .ranking-detail-label {
            color: #4f6758 !important;
            font-weight: 600;
        }

        html.light-mode .ranking-detail-value,
        html.light-mode .ranking-detail-stats,
        html.light-mode .ranking-detail-stats strong {
            color: #203b2b !important;
            font-weight: 700;
        }

        html.light-mode .ranking-detail-stats span {
            color: #4f6758 !important;
        }

        html.light-mode .ranking-detail-stats span strong {
            color: #173b28 !important;
            font-size: 0.8rem;
        }

        html.light-mode .area-badge {
            background: #edf8f3 !important;
            border-color: #bfe0c7 !important;
            color: #165d3a !important;
            box-shadow: 0 4px 10px rgba(22, 93, 58, 0.08) !important;
        }

        html.light-mode .bg-blue-600\/20 {
            background: #dfeeff !important;
            border-color: #9ec5ff !important;
            color: #1f4fb3 !important;
        }

        html.light-mode .bg-purple-600\/20 {
            background: #f2e7ff !important;
            border-color: #d3a9ff !important;
            color: #7c3ac2 !important;
        }

        html.light-mode .bg-amber-600\/20 {
            background: #fff0c8 !important;
            border-color: #f2c65a !important;
            color: #9a6700 !important;
        }

        html.light-mode .bg-emerald-600\/20 {
            background: #d9f8eb !important;
            border-color: #8cddb5 !important;
            color: #0b6a42 !important;
        }

        html.light-mode .text-blue-300,
        html.light-mode .text-purple-300,
        html.light-mode .text-amber-300,
        html.light-mode .text-emerald-300 {
            color: inherit !important;
        }

        html.light-mode .vaga-card-summary .bg-blue-600\/20,
        html.light-mode .vaga-card-summary .bg-purple-600\/20,
        html.light-mode .vaga-card-summary .bg-amber-600\/20,
        html.light-mode .vaga-card-summary .bg-emerald-600\/20 {
            border-width: 1px !important;
            font-weight: 700 !important;
        }

        html.light-mode .vaga-card-summary .bg-blue-600\/20 {
            background: #dceaff !important;
            border-color: #9fc2ff !important;
            color: #2563c7 !important;
        }

        html.light-mode .vaga-card-summary .bg-purple-600\/20 {
            background: #f0ddff !important;
            border-color: #d6a8ff !important;
            color: #8a3fc2 !important;
        }

        html.light-mode .vaga-card-summary .bg-amber-600\/20 {
            background: #fff0c7 !important;
            border-color: #f2c45b !important;
            color: #9a6800 !important;
        }

        html.light-mode .vaga-card-summary .bg-emerald-600\/20 {
            background: #d8f5e7 !important;
            border-color: #91d8b1 !important;
            color: #087443 !important;
        }

        .vaga-toolbar-main,
        .aluno-toolbar-main {
            background: linear-gradient(135deg, #007A33 0%, #00C250 100%) !important;
            border-color: rgba(34, 197, 94, 0.55) !important;
            color: #ffffff !important;
            box-shadow: 0 10px 22px rgba(0, 122, 51, 0.25) !important;
            transform: translateY(0);
        }

        .vaga-toolbar-main:hover,
        .aluno-toolbar-main:hover {
            background: linear-gradient(135deg, #00521A 0%, #00A642 100%) !important;
            box-shadow: 0 14px 28px rgba(0, 122, 51, 0.3) !important;
            transform: translateY(-1px);
        }

        html.light-mode .vaga-toolbar-main,
        html.light-mode .aluno-toolbar-main {
            background: #087443 !important;
            border-color: #075c35 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(8, 116, 67, 0.2) !important;
        }

        html.light-mode .vaga-toolbar-main:hover,
        html.light-mode .aluno-toolbar-main:hover {
            background: #075c35 !important;
        }

        .localidade-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            line-height: 1;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.04), 0 4px 10px rgba(15, 23, 42, 0.08);
            white-space: nowrap;
        }

        .localidade-badge i {
            font-size: 0.68rem;
            opacity: 0.9;
        }

        html:not(.light-mode) .localidade-badge.bg-emerald-500\/25 {
            background: rgba(16, 185, 129, 0.18) !important;
            border-color: rgba(52, 211, 153, 0.45) !important;
            color: #d1fae5 !important;
        }

        html:not(.light-mode) .localidade-badge.bg-cyan-500\/25 {
            background: rgba(34, 211, 238, 0.16) !important;
            border-color: rgba(103, 232, 249, 0.45) !important;
            color: #cffafe !important;
        }

        html:not(.light-mode) .localidade-badge.bg-violet-500\/25 {
            background: rgba(168, 85, 247, 0.18) !important;
            border-color: rgba(196, 181, 253, 0.45) !important;
            color: #ede9fe !important;
        }

        html:not(.light-mode) .localidade-badge.bg-slate-500\/25 {
            background: rgba(148, 163, 184, 0.14) !important;
            border-color: rgba(148, 163, 184, 0.35) !important;
            color: #e2e8f0 !important;
        }

        html.light-mode .localidade-badge {
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.04), 0 4px 10px rgba(22, 101, 52, 0.08);
        }

        html.light-mode .ranking-aluno-meta {
            opacity: 1 !important;
        }

        html.light-mode button.bg-white\/5,
        html.light-mode a.bg-white\/5 {
            background: #e6f0e9 !important;
            border-color: #bdd5c5 !important;
            color: #155c37 !important;
        }

        html.light-mode button.bg-white\/5:hover,
        html.light-mode a.bg-white\/5:hover {
            background: #cfe5d6 !important;
            color: #0b4628 !important;
        }

        html.light-mode .empresa-card .empresa-delete-btn {
            background: #fee2e2 !important;
            border-color: #fca5a5 !important;
            color: #991b1b !important;
        }

        html.light-mode .empresa-card .empresa-delete-btn:hover {
            background: #fecaca !important;
            border-color: #f87171 !important;
            color: #7f1d1d !important;
        }

        .empresa-delete-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(239, 68, 68, 0.14) !important;
            border: 1px solid rgba(248, 113, 113, 0.3) !important;
            color: #f87171 !important;
            box-shadow: 0 4px 10px rgba(127, 29, 29, 0.12);
        }

        .empresa-delete-btn:hover {
            background: rgba(239, 68, 68, 0.25) !important;
            border-color: rgba(248, 113, 113, 0.55) !important;
            color: #fca5a5 !important;
            box-shadow: 0 6px 14px rgba(127, 29, 29, 0.2);
            transform: translateY(-1px);
        }

        html.light-mode .empresa-modal-shell {
            background: #ffffff !important;
            border-color: #d5e5da !important;
            box-shadow: 0 24px 60px rgba(18, 61, 35, 0.2) !important;
        }

        html.light-mode .empresa-modal-icon {
            background: #dff5e7 !important;
            color: #087443 !important;
            border-color: #a9dbba !important;
        }

        html.light-mode .empresa-modal-divider {
            border-color: #e0ebe3 !important;
        }

        html.light-mode .empresa-delete-modal-shell {
            background: #ffffff !important;
            border-color: #ead4d4 !important;
            box-shadow: 0 24px 60px rgba(71, 35, 35, 0.2) !important;
        }

        html.light-mode .empresa-delete-modal-icon {
            background: #fee2e2 !important;
            border-color: #fecaca !important;
            color: #dc2626 !important;
        }

        html.light-mode .empresa-delete-modal-message {
            color: #5f4a4a !important;
        }

        html.light-mode .empresa-delete-modal-footer .empresa-delete-cancel {
            color: #155c37 !important;
            background: #e6f0e9 !important;
            border-color: #bdd5c5 !important;
        }

        html.light-mode .empresa-delete-modal-footer .empresa-delete-cancel:hover {
            color: #0b4628 !important;
            background: #cfe5d6 !important;
            border-color: #9fc5aa !important;
        }

        html.light-mode .empresa-delete-modal-footer .empresa-delete-confirm-btn {
            color: #ffffff !important;
            background: #dc2626 !important;
            background-image: none !important;
            border-color: #b91c1c !important;
            box-shadow: 0 6px 14px rgba(185, 28, 28, 0.22) !important;
        }

        html.light-mode .empresa-delete-modal-footer .empresa-delete-confirm-btn:hover {
            color: #ffffff !important;
            background: #b91c1c !important;
            background-image: none !important;
            border-color: #991b1b !important;
            box-shadow: 0 8px 18px rgba(153, 27, 27, 0.28) !important;
        }

        html.light-mode button.bg-amber-500\/20 {
            background: #fff0c2 !important;
            border-color: #e6bd45 !important;
            color: #765400 !important;
        }

        html.light-mode button.bg-green-600\/20 {
            background: #d8f1df !important;
            border-color: #79c58f !important;
            color: #086b35 !important;
        }

        html.light-mode button.bg-red-500\/20 {
            background: #ffe1e1 !important;
            border-color: #ef9a9a !important;
            color: #a32626 !important;
        }

        html.light-mode .aluno-row button[title="Detalhes"],
        html.light-mode .aluno-row button[title="Editar"],
        html.light-mode .aluno-row button[title="Deletar"],
        html.light-mode .aluno-row-mobile button[title="Detalhes"],
        html.light-mode .aluno-row-mobile button[title="Editar"],
        html.light-mode .aluno-row-mobile button[title="Deletar"] {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08) !important;
            border-width: 1px !important;
            font-weight: 700 !important;
        }

        html.light-mode .aluno-row button[title="Detalhes"],
        html.light-mode .aluno-row-mobile button[title="Detalhes"] {
            background: #dbeafe !important;
            border-color: #93c5fd !important;
            color: #1d4ed8 !important;
        }

        html.light-mode .aluno-row button[title="Editar"],
        html.light-mode .aluno-row-mobile button[title="Editar"] {
            background: #dcfce7 !important;
            border-color: #86efac !important;
            color: #166534 !important;
        }

        html.light-mode .aluno-row button[title="Deletar"],
        html.light-mode .aluno-row-mobile button[title="Deletar"] {
            background: #fee2e2 !important;
            border-color: #fca5a5 !important;
            color: #991b1b !important;
        }

        html.light-mode .dash-area-card p {
            color: #365342 !important;
            font-weight: 800;
        }

        html.light-mode .dash-area-card > h4 {
            font-weight: 700;
        }

        html.light-mode .dash-area-dev {
            background-color: #dbeafe !important;
            background-image: none !important;
            border-color: #93c5fd !important;
        }

        html.light-mode .dash-area-design {
            background: #f7efff !important;
            border-color: #d7b3ff !important;
        }

        html.light-mode .dash-area-support {
            background-color: #fef3c7 !important;
            background-image: none !important;
            border-color: #f2c14e !important;
        }

        html.light-mode .dash-area-tutoria {
            background: #e9f9f0 !important;
            border-color: #9bd7b5 !important;
        }

        html.light-mode .dash-area-dev h4,
        html.light-mode .dash-area-dev p { color: #3275d1 !important; }
        html.light-mode .dash-area-design h4,
        html.light-mode .dash-area-design p { color: #9148c9 !important; }
        html.light-mode .dash-area-support h4,
        html.light-mode .dash-area-support p { color: #b27a00 !important; }
        html.light-mode .dash-area-tutoria h4,
        html.light-mode .dash-area-tutoria p { color: #168447 !important; }

        html.light-mode .dash-area-dev span,
        html.light-mode .dash-area-design span,
        html.light-mode .dash-area-support span,
        html.light-mode .dash-area-tutoria span { color: #52675a !important; }

        html.light-mode .ranking-expand-btn,
        html.light-mode .rank-position {
            background: #e2eee5 !important;
            color: #245c3c !important;
        }

        html.light-mode .nome-cell,
        html.light-mode .score-cell {
            color: #24332c !important;
        }

        html.light-mode .status-badge-mini.approved {
            color: #087443 !important;
            background: #d9f3e4 !important;
        }

        html.light-mode .status-badge-mini.waiting {
            color: #866000 !important;
            background: #fff0bf !important;
        }

        html.light-mode .dash-empresa-pill {
            color: #086b7a !important;
            background: #d9f4f7 !important;
        }

        html.light-mode .kanban-column-sem,
        html.light-mode .kanban-column-em,
        html.light-mode .kanban-column-fin {
            background: #ffffff !important;
            border: 1px solid #e0ebe3;
        }

        html.light-mode .border-t,
        html.light-mode .border-b {
            border-color: #d8e5dc !important;
        }

        html.light-mode table,
        html.light-mode tbody,
        html.light-mode tr {
            background-color: #ffffff !important;
        }

        html.light-mode .dashboard-card h3,
        html.light-mode .dash-area-card h3 {
            color: #365342 !important;
        }

        html.light-mode .dash-grid-top .dashboard-card,
        html.light-mode .ranking-col-card {
            border-radius: 0.6rem;
        }

        html.light-mode .modal_overlay {
            background: rgba(18, 48, 29, 0.35) !important;
        }

        .modal_overlay {
            overscroll-behavior: contain;
        }

        .modal_box {
            max-height: calc(100vh - 1.5rem);
            max-height: calc(100dvh - 1.5rem);
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-gutter: stable;
        }

        .modal_box--compact {
            width: min(92vw, 32rem);
            max-width: 32rem;
            padding: 1.5rem;
        }

        .modal-selection-shell {
            width: min(92vw, 65rem);
            max-width: 65rem;
            padding: 1.25rem;
        }

        .modal_box .modal-danger-action {
            border: 1px solid #991b1b !important;
            color: #ffffff !important;
            background: #b91c1c !important;
            background-image: none !important;
            box-shadow: 0 5px 14px rgba(127, 29, 29, 0.22);
        }

        .modal_box .modal-danger-action:hover {
            border-color: #7f1d1d !important;
            color: #ffffff !important;
            background: #991b1b !important;
            background-image: none !important;
        }

        body.modal-open {
            overflow: hidden;
        }

        .estagio-sidebar {
            background: #064c2b !important;
            border-color: rgba(209, 231, 219, 0.18) !important;
            color: #e8f2ed;
            scrollbar-color: rgba(209, 231, 219, 0.35) transparent;
            scrollbar-width: thin;
        }

        html:not(.light-mode) .estagio-sidebar {
            background: linear-gradient(180deg, #1a1a1a 0%, #121212 100%) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #e5e7eb;
            scrollbar-color: rgba(255, 255, 255, 0.22) transparent;
        }

        .estagio-sidebar .sidebar-brand-title {
            color: #ffffff !important;
            letter-spacing: -0.02em;
        }

        .estagio-sidebar .sidebar-brand-title span {
            color: #ffc04d !important;
        }

        .estagio-sidebar .sidebar-tagline {
            color: #bdd9cb !important;
            font-size: 0.65rem;
            line-height: 1.35;
        }

        .estagio-sidebar .sidebar-section-heading {
            display: flex;
            align-items: center;
            height: 34px;
            margin: 0.25rem 0 0.55rem;
            padding: 0 0.65rem;
            border: 1px solid rgba(183, 215, 200, 0.2);
            border-radius: 6px;
            color: #bdd9cb;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .estagio-sidebar .nav-link {
            position: relative;
            min-height: 40px;
            margin-bottom: 0.25rem;
            padding: 0 0.65rem 0 2.9rem;
            border-radius: 7px;
            color: #e8f2ed !important;
            font-size: 0.82rem;
            font-weight: 600;
            transition: background-color 180ms ease, color 180ms ease, padding-left 200ms ease;
        }

        .estagio-sidebar .nav-link i {
            position: absolute;
            left: 0.7rem;
            width: 1.25rem;
            margin: 0;
            color: #c5dfce;
            text-align: center;
            transition: color 180ms ease;
        }

        .estagio-sidebar .nav-link:hover:not(.sidebar_active) {
            padding-left: 3.15rem;
            background: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            transform: none;
        }

        .estagio-sidebar .nav-link.sidebar_active {
            padding-left: 3.15rem;
            background: #276448 !important;
            color: #ffc04d !important;
            box-shadow: none !important;
        }

        .estagio-sidebar .nav-link.sidebar_active::before {
            position: absolute;
            inset: 8px auto 8px 0;
            width: 3px;
            border-radius: 0 3px 3px 0;
            background: #f3a11a;
            content: "";
        }

        .estagio-sidebar .nav-link.sidebar_active i,
        .estagio-sidebar .nav-link:hover i {
            color: #ffc04d;
        }

        html.light-mode .estagio-sidebar .nav-link.sidebar_active {
            background: #276448 !important;
            color: #ffc04d !important;
        }

        html.light-mode .estagio-sidebar .nav-link.sidebar_active i {
            color: #ffc04d !important;
        }

        html:not(.light-mode) .estagio-sidebar .nav-link.sidebar_active {
            background: #263c30 !important;
            color: #86efac !important;
        }

        html:not(.light-mode) .estagio-sidebar .nav-link.sidebar_active::before {
            background: #22c55e;
        }

        html:not(.light-mode) .estagio-sidebar .nav-link.sidebar_active i {
            color: #86efac !important;
        }

        .estagio-sidebar .sidebar-section-divider {
            border-color: rgba(183, 215, 200, 0.2) !important;
        }

        .estagio-sidebar .sidebar-exit {
            color: #f3c4bf !important;
        }

        .estagio-sidebar .sidebar-exit:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }

        #hamburgerMenu {
            border-color: rgba(183, 215, 200, 0.28);
            background: #064c2b;
            color: #ffffff;
        }

        #hamburgerMenu:hover {
            background: #0b7042;
        }

        html:not(.light-mode) #hamburgerMenu {
            border-color: rgba(255, 255, 255, 0.14);
            background: #1a1a1a;
            color: #f3f4f6;
        }

        html:not(.light-mode) #hamburgerMenu:hover {
            background: #282828;
        }

        .floating-theme-toggle {
            position: fixed;
            right: 1.5rem;
            bottom: 4.25rem;
            z-index: 60;
            display: inline-flex;
            width: 3.25rem;
            height: 3.25rem;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 9999px;
            background: linear-gradient(135deg, #242424, #151515);
            color: #f3f4f6;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.28);
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .floating-theme-toggle:hover {
            transform: translateY(-2px) scale(1.02);
        }

        .floating-theme-toggle:active {
            transform: scale(0.95);
        }

        .floating-theme-toggle__icon {
            font-size: 1.1rem;
            line-height: 1;
        }

        html.light-mode .floating-theme-toggle {
            border-color: rgba(255, 255, 255, 0.18);
            background: linear-gradient(135deg, #1ea55f, #0c9a45);
            color: #ffffff;
        }

        html.light-mode .floating-theme-toggle:hover {
            background: linear-gradient(135deg, #22b96a, #0d9b48);
        }

        @media (max-width: 767px) {
            .floating-theme-toggle {
                right: 1rem;
                bottom: 5rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .estagio-sidebar .nav-link,
            .estagio-sidebar .nav-link i,
            .floating-theme-toggle {
                transition-duration: 0.01ms;
            }
        }

        html.light-mode .bg-cyan-950\/30,
        html.light-mode .bg-violet-950\/35 {
            background-color: #ffffff !important;
        }

        html.light-mode .kanban-column {
            background: rgba(255, 255, 255, 0.6) !important;
        }

        html.light-mode .kanban-empty-state {
            color: #607268;
        }

        html.light-mode input,
        html.light-mode select,
        html.light-mode textarea {
            background-color: #ffffff !important;
            color: #24332c !important;
            border-color: #c7d8ce !important;
        }

        html.light-mode input::placeholder,
        html.light-mode textarea::placeholder {
            color: #809087 !important;
        }

        .empresa-modal-shell {
            width: min(92vw, 560px) !important;
            max-width: 560px !important;
            padding: 1.5rem !important;
            background: linear-gradient(145deg, #202820 0%, #171d19 100%) !important;
            border: 1px solid rgba(110, 231, 183, 0.18) !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.42) !important;
        }

        .empresa-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .empresa-modal-heading {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .empresa-modal-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.75rem;
            height: 2.75rem;
            flex: 0 0 auto;
            color: #86efac;
            background: rgba(34, 197, 94, 0.14);
            border: 1px solid rgba(74, 222, 128, 0.25);
            border-radius: 0.85rem;
        }

        .empresa-modal-kicker {
            margin-bottom: 0.2rem;
            color: #86efac;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .empresa-modal-close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            flex: 0 0 auto;
            color: #9ca3af;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 0.65rem;
            transition: all 0.2s ease;
        }

        .empresa-modal-close:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
        }

        .empresa-modal-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1rem;
        }

        .empresa-modal-form > div > label {
            color: #cbd5d0;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .empresa-modal-form > div > input {
            margin-top: 0.4rem;
        }

        .empresa-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 520px) {
            .empresa-modal-shell {
                padding: 1.15rem !important;
            }

            .empresa-modal-footer {
                flex-direction: column-reverse;
            }

            .empresa-modal-footer > button {
                width: 100%;
            }
        }

        .empresa-delete-modal-shell {
            width: min(92vw, 500px) !important;
            max-width: 500px !important;
            padding: 1.75rem !important;
            background: linear-gradient(145deg, #202020 0%, #171717 100%) !important;
            border: 1px solid rgba(248, 113, 113, 0.16) !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45) !important;
        }

        .empresa-delete-modal-content {
            text-align: center;
        }

        .empresa-delete-modal-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4rem;
            height: 4rem;
            margin-bottom: 1rem;
            color: #f87171;
            background: rgba(239, 68, 68, 0.14);
            border: 1px solid rgba(248, 113, 113, 0.2);
            border-radius: 9999px;
        }

        .empresa-delete-modal-message {
            max-width: 34rem;
            margin: 0.5rem auto 0;
            color: #a1a1aa;
            line-height: 1.6;
        }

        .empresa-delete-modal-footer {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .empresa-delete-modal-footer .empresa-delete-cancel {
            color: #d4d4d8;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .empresa-delete-modal-footer .empresa-delete-cancel:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        @media (max-width: 520px) {
            .empresa-delete-modal-shell {
                padding: 1.25rem !important;
            }

            .empresa-delete-modal-footer {
                flex-direction: column-reverse;
            }

            .empresa-delete-modal-footer > button {
                width: 100%;
            }
        }

        .dash-area-dev h4 { color: #93c5fd !important; }
        .dash-area-design h4 { color: #d8b4fe !important; }
        .dash-area-support h4 { color: #fcd34d !important; }
        .dash-area-tutoria h4 { color: #6ee7b7 !important; }

        .dash-area-dev {
            background: #152a3a !important;
            border-color: #24577a !important;
        }

        .dash-area-design {
            background: #241a3b !important;
            border-color: #5a3290 !important;
        }

        .dash-area-support {
            background: #302411 !important;
            border-color: #85520f !important;
        }

        .dash-area-tutoria {
            background: #123526 !important;
            border-color: #26744d !important;
        }

        html.light-mode .ranking-col-card.accent-dev {
            border-top: 4px solid #3b82f6 !important;
        }

        html.light-mode .ranking-col-card.accent-design {
            border-top: 4px solid #a855f7 !important;
        }

        html.light-mode .ranking-col-card.accent-sup {
            border-top: 4px solid #f59e0b !important;
        }

        html.light-mode .ranking-col-card.accent-tut {
            border-top: 4px solid #10b981 !important;
        }

        html,
        body {
            overflow-x: hidden;
        }

        @media (max-width: 767px) {
            header > div {
                align-items: flex-start;
                padding-left: 4rem !important;
            }

            header #pageTitle {
                text-align: left;
                line-height: 1.25;
            }

            main {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
                padding-top: 4.5rem !important;
            }

            #view-dashboard > .grid.grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.5rem;
            }

            #view-dashboard > .grid.grid-cols-2 > div {
                padding: 0.75rem;
            }

            #view-dashboard > .grid.grid-cols-2 > div > div {
                align-items: flex-start;
            }

            #view-dashboard > .grid.grid-cols-2 > div > div > div:last-child {
                width: 2.5rem;
                height: 2.5rem;
                flex-shrink: 0;
            }

            .vaga-card {
                min-height: 0;
                padding: 0.875rem;
            }

            .vaga-card h3,
            .empresa-card h3 {
                min-width: 0;
                overflow-wrap: anywhere;
                font-size: 0.95rem;
            }

            .vaga-card .flex.items-center.gap-2 span,
            .empresa-card .flex.items-center.gap-3 span {
                min-width: 0;
                overflow-wrap: anywhere;
            }

            .modal_box {
                max-width: calc(100vw - 1.5rem);
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            main {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            #view-dashboard > .grid.grid-cols-2 {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.4rem;
            }

            #view-dashboard > .grid.grid-cols-2 > div {
                padding: 0.65rem;
            }

            #view-dashboard > .grid.grid-cols-2 > div > div > div:last-child {
                width: 2.75rem;
                height: 2.75rem;
                flex-shrink: 0;
            }

            .dashboard-card {
                padding: 1rem;
            }

            .dashboard-card h3 {
                font-size: 0.75rem;
            }

            .dashboard-card p {
                font-size: 1.5rem;
            }

            .modal_box form + div,
            .modal_box form > div:last-child,
            .modal_box form .justify-end,
            .modal_box .justify-center {
                flex-direction: column;
            }

            .modal_box form + div > button,
            .modal_box form > div:last-child > button,
            .modal_box form .justify-end > button,
            .modal_box .justify-center > button {
                width: 100%;
            }

            .hamburgerMenu {
                top: 1rem !important;
                left: 1rem !important;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            main {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            #view-dashboard > .grid.grid-cols-2 {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.75rem;
            }

            #view-dashboard > .grid.grid-cols-2 > div {
                padding: 0.9rem;
            }
        }

        .dash-stats-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 1rem;
            width: 100%;
        }

        @media (max-width: 1024px) {
            .dash-stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 0.9rem;
            }
        }

        @media (max-width: 640px) {
            .dash-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.6rem;
            }
        }

        @media (max-width: 480px) {
            .dash-stats-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.5rem;
            }
        }

        .dashboard-card {
            background: rgba(38, 38, 38, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 0.75rem;
            padding: 1.25rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
            min-height: auto;
        }

        @media (max-width: 640px) {
            .dashboard-card {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .dashboard-card {
                padding: 0.875rem;
            }

            .dashboard-card h3 {
                font-size: 0.7rem;
            }

            .dashboard-card p.text-3xl {
                font-size: 1.875rem;
            }

            .dashboard-card i {
                font-size: 1.125rem;
            }
        }

        .dash-grid-top {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 0.6rem;
            width: 100%;
            align-items: stretch;
        }

        .dash-grid-top .dashboard-card,
        .dash-area-card {
            min-height: 120px;
            height: 120px;
            padding: 0.8rem 0.8rem;
            box-sizing: border-box;
        }

        .dash-area-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 0.75rem 0.8rem;
        }

        .dash-area-cards {
            display: contents;
        }

        .dash-cell-actions > div {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.75rem;
            width: 100%;
        }

        .dash-cell-actions a {
            min-width: 0;
        }

        .dash-cell-actions h4,
        .dash-cell-actions p {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dash-area-card h4 {
            margin-bottom: 0.3rem;
            font-size: 0.7rem;
        }

        .dash-area-card .text-xl,
        .dash-area-card .text-2xl {
            font-size: 1.5rem !important;
            line-height: 1.1;
        }

        .dash-cell-actions {
            grid-column: span 2 / span 2;
            min-height: 120px;
            height: 120px;
        }

        .dash-cell-2 {
            grid-column: span 1 / span 1;
        }

        .dash-grid-top > .dash-cell-actions,
        .dash-grid-top > .dash-cell-2 {
            height: 100%;
            align-self: stretch;
        }

        @media (max-width: 1024px) {
            .dash-grid-top {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }
            .dash-cell-actions { grid-column: span 2 / span 2; }
            .dash-grid-top > .dash-cell-actions { grid-row: auto; }
            .dash-cell-2 { grid-column: span 1 / span 1; }
        }

        @media (max-width: 640px) {
            .dash-grid-top {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 0.75rem;
            }
            .dash-cell-actions { grid-column: span 2 / span 2; }
            .dash-grid-top > .dash-cell-actions { grid-row: auto; }
            .dash-cell-2 { grid-column: span 1 / span 1; }
        }

        @media (max-width: 480px) {
            .dash-grid-top {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.5rem;
            }
            .dash-cell-actions { grid-column: span 1 / span 1; }
            .dash-grid-top > .dash-cell-actions { grid-row: auto; }
            .dash-cell-2 { grid-column: span 1 / span 1; }
        }

        .ranking-four-col {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.25rem;
            width: 100%;
        }

        @media (max-width: 1200px) {
            .ranking-four-col {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 640px) {
            .ranking-four-col {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.75rem;
            }
        }

        .ranking-col-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: 33rem;
            max-height: 33rem;
            padding: 1rem;
            background: rgba(38, 38, 38, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-top: 4px solid rgba(255, 255, 255, 0.25);
            border-radius: 0.75rem;
        }

        .accent-dev { border-top-color: #3b82f6; }
        .accent-design { border-top-color: #a855f7; }
        .accent-sup { border-top-color: #f59e0b; }
        .accent-tut { border-top-color: #10b981; }

        .accent-dev .ranking-head { color: #93c5fd; background: rgba(59, 130, 246, 0.12); }
        .accent-design .ranking-head { color: #d8b4fe; background: rgba(168, 85, 247, 0.12); }
        .accent-sup .ranking-head { color: #fcd34d; background: rgba(245, 158, 11, 0.12); }
        .accent-tut .ranking-head { color: #6ee7b7; background: rgba(16, 185, 129, 0.12); }

        .ranking-col-card.accent-dev .ranking-aluno-card { border-color: rgba(59, 130, 246, 0.45); border-left-color: #3b82f6; }
        .ranking-col-card.accent-design .ranking-aluno-card { border-color: rgba(168, 85, 247, 0.45); border-left-color: #a855f7; }
        .ranking-col-card.accent-sup .ranking-aluno-card { border-color: rgba(245, 158, 11, 0.45); border-left-color: #f59e0b; }
        .ranking-col-card.accent-tut .ranking-aluno-card { border-color: rgba(16, 185, 129, 0.45); border-left-color: #10b981; }

        .ranking-col-scroll {
            min-height: 0;
            overflow-y: auto;
            padding-right: 0.25rem;
        }

        .ranking-head,
        .ranking-aluno-grid {
            display: grid;
            grid-template-columns: 1.75rem minmax(0, 1fr) 3.25rem;
            gap: 0.25rem;
            align-items: center;
        }

        .ranking-head {
            padding: 0 0.25rem 0.5rem;
            margin-bottom: 0.25rem;
            color: rgba(255, 255, 255, 0.45);
            font-size: 0.62rem;
            font-weight: 600;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ranking-aluno-card {
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background: rgba(17, 17, 17, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-left: 4px solid #ef4444;
            border-radius: 0.5rem;
        }

        .ranking-aluno-card.approved { border-left-color: #10b981; }
        .ranking-aluno-card.waiting { border-left-color: #f59e0b; }
        .ranking-aluno-card.no_interview { border-left-color: #ef4444; }

        .ranking-expand-btn {
            float: right;
            width: 1.75rem;
            height: 1.75rem;
            border: 0;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.75);
            cursor: pointer;
        }

        .ranking-expand-btn i { transition: transform 0.2s ease; }
        .ranking-expand-btn[aria-expanded="true"] i { transform: rotate(180deg); }

        .ranking-aluno-grid {
            min-height: 1.75rem;
            font-size: 0.75rem;
        }

        .rank-position {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.65rem;
            font-weight: 700;
        }

        .nome-cell {
            min-width: 0;
            overflow: hidden;
            color: #f3f4f6;
            font-weight: 600;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .score-cell {
            color: #fff;
            font-weight: 700;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .ranking-aluno-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
            margin-top: 0.35rem;
        }

        .area-badge,
        .status-badge-mini,
        .dash-empresa-pill {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
            padding: 0.2rem 0.5rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 999px;
            font-size: 0.62rem;
            font-weight: 600;
            line-height: 1.1;
        }

        .status-badge-mini.approved { color: #34d399; background: rgba(16, 185, 129, 0.15); }
        .status-badge-mini.waiting { color: #fbbf24; background: rgba(245, 158, 11, 0.15); }
        .dash-empresa-pill { color: #67e8f9; background: rgba(6, 182, 212, 0.15); }

        .aluno-row-mobile {
            padding: 1.25rem !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            background: rgba(38, 38, 38, 0.92) !important;
            border-radius: 0.75rem !important;
        }

        .aluno-row-mobile h3 {
            letter-spacing: 0.025em;
            word-break: break-word;
        }

        @media (max-width: 640px) {
            .aluno-row-mobile {
                padding: 1rem !important;
            }
            .aluno-row-mobile h3 {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .aluno-row-mobile {
                padding: 0.875rem !important;
            }
            .aluno-row-mobile h3 {
                font-size: 0.8125rem;
            }
        }

        /* Tabela de alunos - Desktop */
        #alunosTableBody tr.aluno-row {
            transition: all 0.2s ease;
        }

        #alunosTableBody tr.aluno-row:hover {
            background-color: rgba(16, 185, 129, 0.05);
        }

        #alunosTableBody td {
            vertical-align: middle;
        }

        #alunosTableBody button {
            transition: all 0.2s ease;
        }

        .ranking-aluno-details {
            margin-top: 0.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.68rem;
        }

        .ranking-detail-line {
            display: flex;
            justify-content: space-between;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .ranking-detail-label { color: rgba(255, 255, 255, 0.55); }
        .ranking-detail-value { text-align: right; }
        .ranking-detail-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.25rem 0.5rem;
            margin-top: 0.5rem;
        }

        .vagas-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1rem;
        }

        .vagas-actions,
        .vagas-filters {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }

        .vagas-actions {
            flex: 1 1 34rem;
        }

        .vagas-filters {
            flex: 1 1 34rem;
            justify-content: flex-end;
        }

        .vagas-actions > a,
        .vagas-actions > button {
            flex: 0 1 auto;
            min-height: 3.25rem;
            white-space: nowrap;
        }

        .vagas-filters > .relative {
            flex: 1 1 12rem;
            min-width: 12rem;
        }

        .vagas-filters input,
        .vagas-filters select {
            width: 100%;
        }

        @media (min-width: 1400px) {
            .vagas-toolbar {
                flex-wrap: nowrap;
                gap: 0.75rem;
            }

            .vagas-actions,
            .vagas-filters {
                flex: 1 1 0;
            }

            .vagas-actions {
                flex-wrap: nowrap;
                gap: 0.5rem;
            }

            .vagas-actions > a,
            .vagas-actions > button {
                flex: 1 1 auto;
                min-width: 0;
                padding-left: 1rem;
                padding-right: 1rem;
                font-size: 0.9rem;
            }

            .vagas-filters {
                gap: 0.5rem;
            }

            .vagas-filters > .relative {
                flex: 1 1 0;
                min-width: 0;
            }
        }

        @media (max-width: 1100px) {
            .dash-stats-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .ranking-four-col { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 767px) {
            .flex-1.flex.flex-col.overflow-y-auto,
            main,
            .view-section,
            .view-section > div,
            #vagasGrid > *,
            #empresasGrid > * {
                min-width: 0;
            }

            #view-vagas > .mb-6 > div,
            #view-empresas > .mb-6 > div,
            #view-alunos > .mb-6 > div {
                align-items: stretch;
            }

            #view-vagas > .mb-6 > div > div,
            #view-empresas > .mb-6 > div > div,
            #view-alunos > .mb-6 > div > div {
                width: 100%;
                min-width: 0;
            }

            #view-vagas > .mb-6 button,
            #view-vagas > .mb-6 a,
            #view-empresas > .mb-6 button,
            #view-empresas > .mb-6 a,
            #view-alunos > .mb-6 button,
            #view-alunos > .mb-6 a,
            #view-vagas > .mb-6 input,
            #view-vagas > .mb-6 select,
            #view-empresas > .mb-6 input,
            #view-alunos > .mb-6 input,
            #view-alunos > .mb-6 select {
                max-width: 100%;
                white-space: normal;
            }

            #view-selecionados .overflow-x-auto {
                max-width: 100%;
            }

            .vagas-toolbar,
            .vagas-actions,
            .vagas-filters {
                align-items: stretch;
            }

            .vagas-actions,
            .vagas-filters {
                width: 100%;
            }

            .vagas-actions > a,
            .vagas-actions > button,
            .vagas-filters > .relative {
                flex: 1 1 12rem;
                min-width: 0;
            }

            .dash-stats-grid,
            .dash-grid-top,
            .ranking-four-col {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dash-cell-actions {
                grid-column: span 2 / span 2;
            }

            .dash-cell-2 {
                grid-column: span 1 / span 1;
            }

            .ranking-col-card {
                height: auto;
                max-height: none;
                min-height: 22rem;
            }

            .ranking-col-scroll {
                max-height: 24rem;
            }
        }

        @media (max-width: 480px) {
            .dash-stats-grid,
            .dash-grid-top,
            .ranking-four-col { grid-template-columns: minmax(0, 1fr); }
            .dash-cell-actions,
            .dash-cell-2 { grid-column: span 1 / span 1; }

            .vagas-actions,
            .vagas-filters {
                flex-direction: column;
            }

            .vagas-actions > a,
            .vagas-actions > button,
            .vagas-filters > .relative {
                width: 100%;
                flex-basis: auto;
            }
        }

        .kanban-wrapper {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.5rem;
            align-items: start;
        }

        .kanban-column {
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: calc(100vh - 15rem);
            min-height: 0;
            max-height: none;
            padding: 1rem;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.025);
        }

        .kanban-column-content {
            min-height: 0;
            overflow-y: auto;
            padding-right: 0.25rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .kanban-column.is-empty {
            height: auto;
            min-height: 0;
        }

        .kanban-column.is-empty .kanban-column-content {
            overflow: visible;
        }

        .kanban-empty-state {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 8rem;
            padding: 1rem;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            text-align: center;
        }

        .kanban-empty-state i {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .kanban-column h3 {
            margin-bottom: 0.75rem;
            font-size: 0.8rem;
            letter-spacing: 0.04em;
        }

        .kanban-column-sem { background: rgba(239, 68, 68, 0.04); }
        .kanban-column-sem h3 { color: #f87171; }
        .kanban-column-em { background: rgba(245, 158, 11, 0.04); }
        .kanban-column-em h3 { color: #fbbf24; }
        .kanban-column-fin { background: rgba(16, 185, 129, 0.04); }
        .kanban-column-fin h3 { color: #34d399; }

        .vaga-card {
            min-height: 0;
            height: auto;
            padding: 1.25rem;
            flex: 0 0 auto;
            background: rgba(38, 38, 38, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-left: 4px solid rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .vaga-card:hover { box-shadow: 0 10px 26px rgba(0, 0, 0, 0.28); transform: translateY(-2px); }

        .vaga-card .vaga-card-header { margin-bottom: 0.75rem; }
        .vaga-card .vaga-card-title { font-size: 1.1rem; }
        .vaga-card .vaga-card-title-wrap { min-width: 0; }
        .vaga-card .vaga-card-title-wrap h3 { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .vaga-card-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            margin-right: 0.25rem;
            border: 0;
            border-radius: 0.5rem;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.75);
            cursor: pointer;
        }
        .vaga-card-toggle i { transition: transform 0.2s ease; }
        .vaga-card.is-expanded .vaga-card-toggle i { transform: rotate(180deg); }
        .vaga-card-details[hidden] { display: none; }
        .vaga-card .vaga-card-info { gap: 0.35rem; margin-bottom: 0.75rem; }
        .vaga-card .vaga-card-info-item { padding: 0.4rem 0.5rem; font-size: 0.8rem; min-width: 0; flex-wrap: wrap; }
        .vaga-card .vaga-card-info-item span { min-width: 0; overflow-wrap: anywhere; }
        .vaga-card .vaga-card-actions { gap: 0.25rem; }
        .vaga-card .vaga-card-action { padding: 0.4rem; }
        .vaga-card .ver-detalhes-link { padding: 0.4rem 0.75rem; }

        .kanban-column-sem .vaga-card { border: 1px solid rgba(239, 68, 68, 0.35); border-left: 4px solid #ef4444; }
        .kanban-column-em .vaga-card { border: 1px solid rgba(245, 158, 11, 0.35); border-left: 4px solid #f59e0b; }
        .kanban-column-fin .vaga-card { border: 1px solid rgba(16, 185, 129, 0.35); border-left: 4px solid #10b981; }

        .kanban-column-sem .vaga-card { border-left-color: #ef4444 !important; }
        .kanban-column-em .vaga-card { border-left-color: #f59e0b !important; }
        .kanban-column-fin .vaga-card { border-left-color: #10b981 !important; }

        .vaga-card .custom-btn {
            min-width: 0;
            white-space: normal;
        }

        @media (min-width: 1536px) {
            main {
                max-width: 2200px !important;
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }

            .ranking-four-col {
                gap: 1.5rem;
            }
        }

        @media (min-width: 2200px) {
            main {
                max-width: 2600px !important;
                padding-left: 3rem !important;
                padding-right: 3rem !important;
            }

            .dash-stats-grid,
            .dash-grid-top {
                gap: 1.25rem;
            }

            .ranking-four-col {
                gap: 1.75rem;
            }
        }

        .estagio-main {
            padding-bottom: 5rem !important;
        }

        .estagio-footer {
            position: fixed;
            z-index: 30;
            right: 0;
            bottom: 0;
            left: 16rem;
            display: flex;
            min-height: 3.5rem;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
            background: #ffffff;
            color: #607268;
            box-shadow: 0 -4px 18px rgba(0, 0, 0, 0.1);
            font-size: 0.75rem;
        }

        .estagio-footer::before {
            position: absolute;
            inset: 0 0 auto;
            height: 4px;
            background: linear-gradient(90deg, #006b35, #f6c400 48%, #ed8500 70%, #006b35);
            content: "";
        }

        .estagio-footer__developers {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.4rem 1rem;
        }

        .estagio-footer a {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            color: #24332c;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .estagio-footer a:hover,
        .estagio-footer a:focus-visible {
            color: #087443;
            transform: translateY(-3px);
        }

        html:not(.light-mode) .estagio-footer {
            background: #171717;
            color: #d1d5db;
            box-shadow: 0 -4px 18px rgba(0, 0, 0, 0.35);
        }

        html:not(.light-mode) .estagio-footer a {
            color: #f3f4f6;
        }

        html:not(.light-mode) .estagio-footer a:hover,
        html:not(.light-mode) .estagio-footer a:focus-visible,
        html:not(.light-mode) .estagio-footer a i {
            color: #76d89e;
        }

        @media (max-width: 767px) {
            .estagio-footer {
                left: 0;
                min-height: 4.25rem;
                flex-direction: column;
                gap: 0.2rem;
                padding: 0.55rem 0.75rem;
                font-size: 0.68rem;
            }

            .estagio-footer__developers {
                gap: 0.25rem 0.85rem;
            }
        }

        @media (max-width: 1023px) {
            .kanban-wrapper { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 639px) {
            main { min-height: 0; overflow-x: hidden; }
            #view-vagas { min-width: 0; }
            .vagas-toolbar { gap: 0.75rem; }
            .vagas-actions,
            .vagas-filters { flex-direction: column; width: 100%; align-items: stretch; }
            .vagas-actions > a,
            .vagas-actions > button,
            .vagas-filters > .relative { width: 100%; min-width: 0; flex-basis: auto; }
            .vagas-actions > a,
            .vagas-actions > button { white-space: normal; }
            .kanban-wrapper {
                grid-template-columns: minmax(0, 1fr);
                gap: 0.75rem;
                max-width: 100%;
                overflow-x: hidden;
            }
            .kanban-column { height: min(34rem, calc(100vh - 17rem)); min-height: 0; }
            .kanban-column.is-empty { height: auto; }
            .vaga-card { width: 100%; padding: 1rem; }
            .vaga-card .vaga-card-header { align-items: flex-start; }
            .vaga-card .vaga-card-title { font-size: 1rem; }
            .vaga-card .vaga-card-actions { flex-wrap: wrap; justify-content: flex-end; }
            .vaga-card .custom-btn { width: 100%; }
        }
    </style>
</head>

<body class="select-none font-sans bg-[#1a1a1a] text-white min-h-screen overflow-hidden">
<div class="flex h-screen overflow-hidden">

    <aside class="estagio-sidebar hidden md:flex md:flex-col w-64 h-full border-r shadow-lg">
        <div class="p-5 flex flex-col h-full">
            <div class="flex items-center gap-3 mb-7">
                <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                <div>
                    <h1 class="sidebar-brand-title text-lg font-bold">Sistema <span>STGM</span></h1>
                    <p class="sidebar-tagline mt-1">Gerenciando oportunidades de estágio.</p>
                </div>
            </div>
            <nav class="flex-1">
                <div class="sidebar-section-heading">Menu principal</div>
                <a href="#dashboard" data-view="dashboard" onclick="showView('dashboard')" class="<?= $CLS['sidebar_link'] ?>">
                    <i class="fas fa-home w-5 mr-3"></i> Dashboard
                </a>
                <a href="#empresas" data-view="empresas" onclick="showView('empresas')" class="<?= $CLS['sidebar_link'] ?>">
                    <i class="fas fa-building w-5 mr-3"></i> Gerenciar Empresas
                </a>
                <a href="#vagas" data-view="vagas" onclick="showView('vagas')" class="<?= $CLS['sidebar_link'] ?>">
                    <i class="fas fa-briefcase w-5 mr-3"></i> Gerenciar Vagas
                </a>
                
                <a href="#alunos" data-view="alunos" onclick="showView('alunos')" class="<?= $CLS['sidebar_link'] ?>">
                    <i class="fas fa-user-graduate w-5 mr-3"></i> Gerenciar Alunos
                </a>
            </nav>
            <div class="mt-auto pt-4 border-t sidebar-section-divider">
                <a href="../../../main/views/subsystems.php" class="<?= $CLS['sidebar_link'] ?> sidebar-exit w-full" aria-label="Sair do sistema de estágio">
                    <i class="fas fa-sign-out-alt w-5 mr-3"></i> Sair
                </a>
            </div>
        </div>
    </aside>

    <div class="md:hidden fixed top-3 left-3 z-50">
        <button id="hamburgerMenu" class="w-12 h-12 flex items-center justify-center rounded-xl bg-green-700 border border-green-600 shadow-lg hover:bg-green-600 active:scale-95 transition-all">
            <i class="fas fa-bars text-green-100 text-lg"></i>
        </button>
    </div>
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/70 z-40 hidden md:hidden transition-opacity duration-300"></div>
    <div id="mobileSidebar" class="estagio-sidebar fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 ease-in-out md:hidden border-r overflow-y-auto shadow-2xl">
        <div class="p-5 flex flex-col h-full">
            <div class="flex items-center justify-between mb-7">
                <div class="flex items-center gap-2">
                    <img src="https://i.postimg.cc/Dy40VtFL/Design-sem-nome-13-removebg-preview.png" alt="Logo" class="h-10 w-auto">
                    <div>
                        <h1 class="sidebar-brand-title text-lg font-bold">Sistema <span>STGM</span></h1>
                        <p class="sidebar-tagline mt-1">Gerenciando oportunidades de estágio.</p>
                    </div>
                </div>
                <button id="closeSidebar" aria-label="Fechar menu" class="p-2 text-gray-300 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
            </div>
            <nav class="flex-1">
                <div class="sidebar-section-heading">Menu principal</div>
                <a href="#dashboard" data-view="dashboard" onclick="showView('dashboard')" class="<?= $CLS['sidebar_link'] ?> mobile-nav-link">
                    <i class="fas fa-home w-5 mr-3"></i> Dashboard
                </a>
                <a href="#empresas" data-view="empresas" onclick="showView('empresas')" class="<?= $CLS['sidebar_link'] ?> mobile-nav-link">
                    <i class="fas fa-building w-5 mr-3"></i> Gerenciar Empresas
                </a>
                <a href="#vagas" data-view="vagas" onclick="showView('vagas')" class="<?= $CLS['sidebar_link'] ?> mobile-nav-link">
                    <i class="fas fa-briefcase w-5 mr-3"></i> Vagas
                </a>
                <a href="#alunos" data-view="alunos" onclick="showView('alunos')" class="<?= $CLS['sidebar_link'] ?> mobile-nav-link">
                    <i class="fas fa-user-graduate w-5 mr-3"></i> Gerenciar Alunos
                </a>
            </nav>
            <div class="mt-auto pt-4 border-t sidebar-section-divider">
                <a href="../../../main/views/subsystems.php" class="<?= $CLS['sidebar_link'] ?> sidebar-exit w-full" aria-label="Sair do sistema de estágio">
                    <i class="fas fa-sign-out-alt w-5 mr-3"></i> Sair
                </a>
            </div>
        </div>
    </div>

    <div class="flex-1 flex flex-col min-h-0 overflow-y-auto bg-[#1a1a1a] bg-[radial-gradient(circle_at_10%_20%,rgba(0,122,51,0.05)_0%,rgba(0,122,51,0)_20%),radial-gradient(circle_at_90%_80%,rgba(255,165,0,0.05)_0%,rgba(255,165,0,0)_20%)]">


        <main class="estagio-main max-w-[2200px] mx-auto px-2 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8 w-full md:pt-6 pt-16">
            <section id="view-dashboard" class="view-section">
                <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-2 sm:gap-3">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold leading-tight">Dashboard</h2>
                        <p class="text-xs sm:text-sm text-gray-400 mt-1">Bem-vindo ao Sistema de Gerenciamento de Estágio</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <div class="h-10 w-10 rounded-full bg-green-700 text-white flex items-center justify-center font-semibold text-sm" style="color: #ffffff !important;">A</div>
                        <div class="hidden sm:block">
                            <p class="text-sm font-medium">Admin</p>
                            <p class="text-xs text-gray-400">Administrador</p>
                        </div>
                    </div>
                </div>

                <div class="dash-stats-grid">
                    <div class="dashboard-card">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold truncate text-gray-300">Empresas</h3>
                                <?php foreach ($select_model->total_empresa() as $dado) { ?>
                                    <p class="text-3xl font-bold mt-2 text-blue-400"><?= $dado ?></p>
                                <?php } ?>
                            </div>
                            <div class="h-12 w-12 shrink-0 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400"><i class="fas fa-building text-xl"></i></div>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold truncate text-gray-300">Vagas captadas</h3>
                                <?php foreach ($select_model->total_vagas() as $dado) { ?>
                                    <p class="text-3xl font-bold mt-2 text-emerald-400"><?= $dado ?? 0 ?></p>
                                <?php } ?>
                            </div>
                            <div class="h-12 w-12 shrink-0 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400"><i class="fas fa-briefcase text-xl"></i></div>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold truncate text-gray-300">Déficit vagas</h3>
                                <?php $dados2 = $select_model->total_alunos(); foreach ($select_model->total_vagas() as $dado) { ?>
                                    <p class="text-3xl font-bold mt-2 text-red-400"><?= $dados2 - $dado ?? 0 ?></p>
                                <?php } ?>
                            </div>
                            <div class="h-12 w-12 shrink-0 rounded-lg bg-red-500/10 flex items-center justify-center text-red-400"><i class="fas fa-chart-line text-xl"></i></div>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold truncate text-gray-300">Alunos</h3>
                                <p class="text-3xl font-bold mt-2 text-blue-400"><?= (int) $select_model->total_alunos() ?></p>
                            </div>
                            <div class="h-12 w-12 shrink-0 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400"><i class="fas fa-user-graduate text-xl"></i></div>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold truncate text-gray-300">Selecionados</h3>
                                <?php foreach ($select_model->estagios_ativas() as $dado) { ?>
                                    <p class="text-3xl font-bold mt-2 text-emerald-400"><?= (int) ($dado ?? 0) ?></p>
                                <?php } ?>
                            </div>
                            <div class="h-12 w-12 shrink-0 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400"><i class="fas fa-check-circle text-xl"></i></div>
                        </div>
                    </div>
                    <div class="dashboard-card">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold truncate text-gray-300">Não Selecionados</h3>
                                <?php foreach ($select_model->total_nao_selecionados() as $dado) { ?>
                                    <p class="text-3xl font-bold mt-2 text-red-400"><?= (int) ($dado ?? 0) ?></p>
                                <?php } ?>
                            </div>
                            <div class="h-12 w-12 shrink-0 rounded-lg bg-red-500/10 flex items-center justify-center text-red-400"><i class="fas fa-times-circle text-xl"></i></div>
                        </div>
                    </div>
                </div>

                <div class="dash-grid-top mt-5">
                    <div class="dashboard-card dash-cell-actions">
                        <h3 class="text-base sm:text-lg font-semibold mb-3">Ações rápidas</h3>
                        <div class="flex flex-row gap-3">
                            <a href="#empresas" onclick="showView('empresas')" class="flex flex-1 items-center p-2 rounded-lg hover:bg-blue-500/10 transition-colors gap-2 min-w-0">
                                <div class="h-9 w-9 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400 shrink-0"><i class="fas fa-building text-sm"></i></div>
                                <div class="min-w-0">
                                    <h4 class="font-medium text-xs sm:text-sm leading-tight">Cadastrar empresa</h4>
                                    <p class="text-xs text-gray-400 truncate">Nova empresa parceira</p>
                                </div>
                            </a>
                            <a href="#vagas" onclick="showView('vagas')" class="flex flex-1 items-center p-2 rounded-lg hover:bg-amber-500/10 transition-colors gap-2 min-w-0">
                                <div class="h-9 w-9 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-400 shrink-0"><i class="fas fa-briefcase text-sm"></i></div>
                                <div class="min-w-0">
                                    <h4 class="font-medium text-xs sm:text-sm leading-tight">Cadastrar vaga</h4>
                                    <p class="text-xs text-gray-400 truncate">Nova oportunidade</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="dash-area-cards">
                    <div class="dashboard-card dash-cell-2 dash-area-card dash-area-dev bg-blue-950/40 border border-blue-800/30">
                        <h4 class="font-medium text-blue-300 text-xs sm:text-sm mb-2">Desenvolvimento</h4>
                        <div class="flex justify-between items-end gap-2">
                            <?php foreach ($select_model->total_vagas_dev() as $dado) { ?><p class="text-xl sm:text-2xl font-bold text-gray-100"><?= $dado ?? 0 ?></p><?php } ?>
                            <span class="text-xs text-gray-400">Vagas</span>
                        </div>
                    </div>
                    <div class="dashboard-card dash-cell-2 dash-area-card dash-area-design bg-purple-950/35 border border-purple-700/35">
                        <h4 class="font-medium text-purple-300 text-xs sm:text-sm mb-2">Design</h4>
                        <div class="flex justify-between items-end gap-2">
                            <?php $valor_des = 0; $dados = $select_model->total_vagas_des(); if (is_array($dados)) { foreach ($dados as $dado) { $valor_des = (int) ($dado ?? 0); } } ?>
                            <p class="text-xl sm:text-2xl font-bold text-gray-100"><?= $valor_des ?></p>
                            <span class="text-xs text-gray-400">Vagas</span>
                        </div>
                    </div>
                    <div class="dashboard-card dash-cell-2 dash-area-card dash-area-support bg-amber-950/30 border border-amber-700/35">
                        <h4 class="font-medium text-amber-300 text-xs sm:text-sm mb-2">Suporte</h4>
                        <div class="flex justify-between items-end gap-2">
                            <?php foreach ($select_model->total_vagas_sup() as $dado) { ?><p class="text-xl sm:text-2xl font-bold text-gray-100"><?= $dado ?? 0 ?></p><?php } ?>
                            <span class="text-xs text-gray-400">Vagas</span>
                        </div>
                    </div>
                    <div class="dashboard-card dash-cell-2 dash-area-card dash-area-tutoria bg-emerald-950/30 border border-emerald-700/35">
                        <h4 class="font-medium text-emerald-300 text-xs sm:text-sm mb-2">Tutoria</h4>
                        <div class="flex justify-between items-end gap-2">
                            <?php foreach ($select_model->total_vagas_tut() as $dado) { ?><p class="text-xl sm:text-2xl font-bold text-gray-100"><?= $dado ?? 0 ?></p><?php } ?>
                            <span class="text-xs text-gray-400">Vagas</span>
                        </div>
                    </div>
                    </div>
                </div>

                <div class="mt-8 sm:mt-10">
                    <h2 class="text-lg sm:text-xl font-bold mb-3">Ranking de alunos por perfil</h2>
                    <div class="ranking-four-col">
                        <?php foreach ($ranking_por_perfil as $col):
                            $alunos_col = $col['alunos'];
                            $pid = (int) $col['id'];
                            $accentTop = ['1'=>'border-blue-500','2'=>'border-purple-500','3'=>'border-amber-500','4'=>'border-emerald-500'][(string)$pid] ?? 'border-gray-500';
                            $accentTxt = ['1'=>'text-blue-300','2'=>'text-purple-300','3'=>'text-amber-300','4'=>'text-emerald-300'][(string)$pid] ?? 'text-gray-300';
                            $titulo = ['1'=>'Desenvolvimento','2'=>'Design','3'=>'Suporte','4'=>'Tutoria'][(string)$pid] ?? $col['nome_perfil'];
                        ?>
                            <div class="ranking-col-card <?= ['1' => 'accent-dev', '2' => 'accent-design', '3' => 'accent-sup', '4' => 'accent-tut'][(string) $pid] ?? '' ?>">
                                <h3 class="text-xs sm:text-sm font-bold mb-1 <?= $accentTxt ?>"><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="text-[0.6rem] sm:text-[0.65rem] text-gray-500 mb-2"><?= htmlspecialchars($titulo, ENT_QUOTES, 'UTF-8') ?></p>
                                <div class="ranking-col-scroll flex-1 min-h-0">
                                    <div class="ranking-head"><span style="position: relative; top: 4px;">#</span><span style="position: relative; top: 4px; text-align: left;">Nome</span><span class="text-right" style="position: relative; top: 4px;">Escore</span>
                            </div>
                                    <?php if (count($alunos_col) === 0): ?>
                                        <p class="text-sm text-gray-500 py-4 text-center">Nenhum aluno neste perfil.</p>
                                    <?php else: foreach ($alunos_col as $idx => $aluno):
                                        $st = $aluno['ranking_ui_status'] ?? 'no_interview';
                                        if (!in_array($st, ['approved','waiting','no_interview'], true)) $st = 'no_interview';
                                        $stBadge = $st === 'approved' ? 'bg-emerald-500/15 text-emerald-300' : ($st === 'waiting' ? 'bg-amber-500/15 text-amber-300' : '');
                                        $stLabel = $st === 'approved' ? 'Aprovado' : ($st === 'waiting' ? 'Aguardando' : '');
                                        $nome_full = htmlspecialchars((string) ($aluno['nome'] ?? ''), ENT_QUOTES, 'UTF-8');
                                        $area_badges = dashboard_area_badges($aluno, $pid);
                                        $score_show = dashboard_format_decimal($aluno['score'] ?? null, 3);
                                        $medias_show = dashboard_format_decimal($aluno['medias'] ?? null, 3);
                                        $empresa_nome = isset($aluno['empresa']) && (string) $aluno['empresa'] !== '' ? $aluno['empresa'] : null;
                                    ?>
                                        <div class="ranking-aluno-card <?= $st ?>">
                                            <button type="button" class="ranking-expand-btn" aria-expanded="false" aria-label="Expandir detalhes do aluno">
                                                <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                            </button>
                                            <div class="ranking-aluno-grid">
                                                <span class="rank-position <?= $idx < 3 ? 'top-rank' : '' ?>"><?= $idx + 1 ?></span>
                                                <span class="nome-cell" title="<?= $nome_full ?>"><?= $nome_full ?></span>
                                                <span class="score-cell"><?= htmlspecialchars($score_show, ENT_QUOTES, 'UTF-8') ?></span>
                                            </div>
                                            <div class="ranking-aluno-details" aria-hidden="true" hidden>
                                                <div class="ranking-detail-list">
                                                    <div class="ranking-detail-line"><span class="ranking-detail-label">Nome</span><span class="ranking-detail-value"><?= $nome_full ?></span></div>
                                                    <div class="ranking-detail-line"><span class="ranking-detail-label">Atuação</span><span class="ranking-detail-value"><?php foreach ($area_badges as $b): ?><span class="area-badge <?= area_tw_classes($b['area']) ?>"><?= htmlspecialchars($b['label'], ENT_QUOTES, 'UTF-8') ?></span> <?php endforeach; ?></span></div>
                                                </div>
                                                <div class="ranking-detail-stats">
                                                    <span>Médias: <strong class="text-gray-300"><?= htmlspecialchars($medias_show, ENT_QUOTES, 'UTF-8') ?></strong></span>
                                                    <span>Ocorrências: <strong class="text-gray-300"><?= htmlspecialchars((string)($aluno['ocorrencia'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></strong></span>
                                                    <span>Projetos: <strong class="text-gray-300"><?= htmlspecialchars((string)($aluno['projetos'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></strong></span>
                                                    <span>Custeio: <strong class="text-gray-300"><?= (isset($aluno['custeio']) && (string)$aluno['custeio'] !== '0') ? 'Sim' : 'Não' ?></strong></span>
                                                    <span>Entregas indiv.: <strong class="text-gray-300">0</strong></span>
                                                    <span>Entregas grupo: <strong class="text-gray-300">0</strong></span>
                                                </div>
                                            </div>
                                            <div class="ranking-aluno-meta">
                                                <?php foreach ($area_badges as $b): ?><span class="area-badge <?= area_tw_classes($b['area']) ?>" title="<?= htmlspecialchars($b['label'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($b['label'], ENT_QUOTES, 'UTF-8') ?></span><?php endforeach; ?>
                                                <?php if ($stLabel): ?><span class="status-badge-mini <?= $st ?>"><?= $stLabel ?></span><?php endif; ?>
                                                <?php if ($empresa_nome): ?><span class="dash-empresa-pill" title="<?= htmlspecialchars($empresa_nome, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-building text-[0.6rem] mr-1"></i><?= htmlspecialchars($empresa_nome, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <section id="view-empresas" class="view-section hidden">
                <div class="mb-6 <?= $CLS['card'] ?>">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full">
                            <button onclick="openModal('novaEmpresaModal')" class="vaga-toolbar-main <?= $CLS['btn_primary'] ?> w-full sm:w-auto"><i class="fas fa-plus"></i><span>Nova Empresa</span></button>
                            <button type="button" onclick="openModal('relatorioConcedenteModal')" class="<?= $CLS['btn_secondary'] ?> w-full sm:w-auto"><i class="fas fa-file-alt"></i><span>Relatório de Concedente</span></button>
                            <div class="relative w-full sm:w-64">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
                                <input type="text" id="searchEmpresa" placeholder="Buscar empresa..." class="<?= $CLS['input'] ?> pl-10" oninput="filtrarEmpresas()">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="empresasGrid">
                    <?php if (empty($empresas_lista)): ?>
                        <div class="col-span-3 text-center py-16 text-gray-400">
                            <i class="fas fa-building text-5xl mb-4 text-gray-600 opacity-30"></i>
                            <p class="text-xl">Nenhuma empresa cadastrada no momento.</p>
                        </div>
                    <?php else: foreach ($empresas_lista as $dado): ?>
                        <div class="empresa-card <?= $CLS['card'] ?> relative overflow-hidden animate-slideUp before:content-[''] before:absolute before:inset-y-0 before:left-0 before:w-1.5 before:bg-gradient-to-b before:from-green-400 before:to-green-700 before:opacity-60 hover:before:opacity-100 before:transition-opacity before:duration-300" data-nome="<?= htmlspecialchars(strtolower($dado['nome']), ENT_QUOTES, 'UTF-8') ?>" data-endereco="<?= htmlspecialchars(strtolower((string)$dado['endereco']), ENT_QUOTES, 'UTF-8') ?>" data-contato="<?= htmlspecialchars(strtolower((string)$dado['contato']), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-white"><?= htmlspecialchars($dado['nome']) ?></h3>
                                <div class="flex gap-1">
                                    <button onclick="openModal('editarEmpresaModal-<?= $dado['id'] ?>')" class="w-9 h-9 rounded-lg bg-white/5 text-gray-400 hover:text-green-400 hover:bg-white/10 transition-colors"><i class="fas fa-edit"></i></button>
                                    <button onclick="openModal('excluirEmpresaModal-<?= $dado['id'] ?>')" class="empresa-delete-btn w-9 h-9 rounded-lg bg-white/5 text-red-500 hover:text-red-400 hover:bg-white/10 transition-colors" title="Excluir empresa"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white/5 text-gray-300"><i class="fas fa-map-marker-alt w-5 text-center text-green-500"></i><span><?= htmlspecialchars($dado['endereco']) ?: 'Não informado' ?></span></div>
                                <?php if (!empty($dado['nome_contato'])): ?>
                                    <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white/5 text-gray-300"><i class="fas fa-user w-5 text-center text-green-500"></i><span><?= htmlspecialchars($dado['nome_contato']) ?></span></div>
                                <?php endif; ?>
                                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white/5 text-gray-300">
                                    <i class="fab fa-whatsapp w-5 text-center text-green-400"></i>
                                    <?php if (!empty($dado['contato'])): $tel = preg_replace('/\D/', '', $dado['contato']); ?>
                                        <a href="https://wa.me/55<?= $tel ?>" target="_blank" class="hover:underline text-green-400"><?= htmlspecialchars($dado['contato']) ?></a>
                                    <?php else: ?><span>Não informado</span><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div id="editarEmpresaModal-<?= $dado['id'] ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> empresa-modal-shell">
                                <div class="empresa-modal-header">
                                    <div class="empresa-modal-heading">
                                        <div class="empresa-modal-icon"><i class="fas fa-building" aria-hidden="true"></i></div>
                                        <div>
                                            <p class="empresa-modal-kicker">Gerenciamento</p>
                                            <h2 class="text-2xl font-bold text-white leading-tight">Editar Empresa</h2>
                                        </div>
                                    </div>
                                    <button type="button" onclick="closeModal('editarEmpresaModal-<?= $dado['id'] ?>')" class="empresa-modal-close" aria-label="Fechar edição de empresa" title="Fechar">
                                        <i class="fas fa-times" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <form id="editarEmpresaForm-<?= $dado['id'] ?>" action="../controllers/controller_editar_excluir.php" method="post" class="empresa-modal-form">
                                    <input type="hidden" name="id_editar_empresa" value="<?= htmlspecialchars($dado['id']) ?>">
                                    <div><label class="<?= $CLS['label'] ?>">Nome da Empresa</label><input type="text" name="nome_editar_empresa" value="<?= htmlspecialchars($dado['nome']) ?>" class="<?= $CLS['input'] ?>" required></div>
                                    <div><label class="<?= $CLS['label'] ?>">Nome do Contato</label><input type="text" name="nome_contato_editar_empresa" value="<?= htmlspecialchars($dado['nome_contato']) ?: '' ?>" class="<?= $CLS['input'] ?>"></div>
                                    <div><label class="<?= $CLS['label'] ?>">Endereço Completo</label><input type="text" name="endereco_editar_empresa" value="<?= htmlspecialchars($dado['endereco']) ?: '' ?>" class="<?= $CLS['input'] ?>" required></div>
                                    <div><label class="<?= $CLS['label'] ?>">Telefone de Contato</label><input type="tel" name="contato_editar_empresa" value="<?= htmlspecialchars($dado['contato']) ?: '' ?>" placeholder="(XX) XXXXX-XXXX" class="<?= $CLS['input'] ?> telefone-input" required></div>
                                </form>
                                <div class="empresa-modal-footer">
                                    <button type="button" onclick="closeModal('editarEmpresaModal-<?= $dado['id'] ?>')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                    <button type="submit" form="editarEmpresaForm-<?= $dado['id'] ?>" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-save"></i><span>Salvar alterações</span></button>
                                </div>
                            </div>
                        </div>

                        <div id="excluirEmpresaModal-<?= $dado['id'] ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> empresa-delete-modal-shell">
                                <div class="empresa-delete-modal-content">
                                    <div class="empresa-delete-modal-icon"><i class="fas fa-triangle-exclamation text-2xl" aria-hidden="true"></i></div>
                                    <p class="empresa-modal-kicker text-red-400">Ação permanente</p>
                                    <h3 class="text-2xl font-bold text-white mt-1">Excluir empresa?</h3>
                                    <p class="empresa-delete-modal-message">Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita.</p>
                                </div>
                                <form action="../controllers/controller_editar_excluir.php" method="post">
                                    <input type="hidden" name="id_excluir_empresa" value="<?= htmlspecialchars($dado['id']) ?>">
                                    <div class="empresa-delete-modal-footer">
                                        <button type="button" onclick="closeModal('excluirEmpresaModal-<?= $dado['id'] ?>')" class="empresa-delete-cancel inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-semibold transition-all"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                        <button type="submit" class="empresa-delete-confirm-btn <?= $CLS['btn_danger'] ?>"><i class="fas fa-trash-alt"></i><span>Excluir empresa</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <p id="empresasNoResults" class="hidden text-center py-12 text-gray-400"><i class="fas fa-search text-4xl mb-4 text-gray-600 opacity-30 block"></i>Nenhuma empresa encontrada.</p>
            </section>

            <div id="novaEmpresaModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?> empresa-modal-shell">
                    <div class="empresa-modal-header">
                        <div class="empresa-modal-heading">
                            <div class="empresa-modal-icon"><i class="fas fa-building" aria-hidden="true"></i></div>
                            <div>
                                <p class="empresa-modal-kicker">Cadastro</p>
                                <h2 class="text-2xl font-bold text-white leading-tight">Nova Empresa</h2>
                            </div>
                        </div>
                        <button type="button" onclick="closeModal('novaEmpresaModal')" class="empresa-modal-close" aria-label="Fechar cadastro de empresa" title="Fechar">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                    <form id="novaEmpresaForm" action="../controllers/controller.php" method="post" class="empresa-modal-form">
                        <div><label class="<?= $CLS['label'] ?>">Nome da Empresa</label><input type="text" name="nome" class="<?= $CLS['input'] ?>" required></div>
                        <div><label class="<?= $CLS['label'] ?>">Nome do Contato</label><input type="text" name="nome_contato" class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Endereço Completo</label><input type="text" name="endereco" class="<?= $CLS['input'] ?>" required></div>
                        <div><label class="<?= $CLS['label'] ?>">Telefone de Contato</label><input type="tel" name="telefone" placeholder="(XX) XXXXX-XXXX" class="<?= $CLS['input'] ?> telefone-input"></div>
                    </form>
                    <div class="empresa-modal-footer">
                        <button type="button" onclick="closeModal('novaEmpresaModal')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                        <button type="submit" form="novaEmpresaForm" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-save"></i><span>Salvar empresa</span></button>
                    </div>
                </div>
            </div>

            <div id="relatorioConcedenteModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?>">
                    <h2 class="text-2xl font-bold mb-1 text-white">Relatório de Concedente</h2>
                    <p class="text-sm text-gray-400 mb-6">Selecione a empresa para gerar o PDF.</p>
                    <form action="../sub_fichas_cadastrais/view/relatorio_pdf_ficha_cadastral.php" method="get" target="_blank" class="space-y-4">
                        <div>
                            <label class="<?= $CLS['label'] ?>">Concedente</label>
                            <div class="relative">
                                <select name="id" required class="<?= $CLS['select'] ?>">
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php foreach ($fichas_concedentes_lista as $concedente): ?>
                                        <option value="<?= htmlspecialchars($concedente['id']) ?>">
                                            <?= htmlspecialchars($concedente['nome_fantasia'], ENT_QUOTES, 'UTF-8') ?>
                                            - <?= htmlspecialchars($concedente['razao_social'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" onclick="closeModal('relatorioConcedenteModal')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                            <button type="button" onclick="excluirFichaConcedente()" class="<?= $CLS['btn_danger'] ?>"><i class="fas fa-trash-alt"></i><span>Excluir cadastro</span></button>
                            <button type="submit" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-file-pdf"></i><span>Gerar PDF</span></button>
                        </div>
                    </form>
                </div>
            </div>

            <section id="view-vagas" class="view-section hidden">
                <div class="mb-6 <?= $CLS['card'] ?>">
                    <div class="vagas-toolbar">
                        <div class="vagas-actions">
                                                        <button onclick="openModal('novaVagaModal')" class="vaga-toolbar-main <?= $CLS['btn_primary'] ?> w-full sm:w-auto"><i class="fas fa-plus"></i><span>Vaga</span></button>
                                                            <button type="button" onclick="openModal('registroVagaModal')" class="vaga-toolbar-main <?= $CLS['btn_primary'] ?> w-full sm:w-auto"><i class="fas fa-clipboard-list"></i><span>Registro de vagas</span></button>
                            <button onclick="window.open('./relatorio/gerar_relatorio_vagas.php','_blank')" class="<?= $CLS['btn_secondary'] ?> w-full sm:w-auto"><i class="fas fa-file-pdf"></i><span>Relatório de seleção</span></button>
                            <button onclick="window.open('./relatorio/relatorio_resumo_vagas.php','_blank')" class="<?= $CLS['btn_secondary'] ?> w-full sm:w-auto"><i class="fas fa-file-pdf"></i><span>Resumo de vagas</span></button>
                        </div>
                        <div class="vagas-filters">
                            <div class="relative w-full sm:w-64">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
                                <input type="search" id="searchVaga" placeholder="Buscar vaga..." class="<?= $CLS['input'] ?> pl-10" oninput="filtrarVagas()">
                            </div>
                            <div class="relative w-full sm:w-56">
                                <select id="filterAreaVaga" class="<?= $CLS['select'] ?>" onchange="filtrarVagas()">
                                    <option value="">Todas as áreas</option>
                                    <?php foreach ($perfis_vaga_opcoes as $pid => $pnome): ?>
                                        <option value="<?= dashboard_normalize_area($pnome) ?>"><?= htmlspecialchars($pnome, ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                            <div class="relative w-full sm:w-56">
                                <select id="filterEmpresaVaga" class="<?= $CLS['select'] ?>" onchange="filtrarVagas()">
                                    <option value="">Todas as empresas</option>
                                    <?php foreach ($empresas_lista as $empresa): ?>
                                        <option value="<?= htmlspecialchars($empresa['id']) ?>"><?= htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kanban-wrapper" id="vagasGrid">
                    <div class="kanban-column kanban-column-sem">
                        <h3>VAGAS EM ABERTO</h3>
                        <div id="vagasSemSelecao" class="kanban-column-content"><div class="kanban-empty-state"><div><i class="fas fa-building-slash"></i>Nenhuma empresa nesta sessão.</div></div></div>
                    </div>
                    <div class="kanban-column kanban-column-em">
                        <h3>EM SELEÇÃO</h3>
                        <div id="vagasEmSelecao" class="kanban-column-content"><div class="kanban-empty-state"><div><i class="fas fa-building-slash"></i>Nenhuma empresa nesta sessão.</div></div></div>
                    </div>
                    <div class="kanban-column kanban-column-fin">
                        <h3>FINALIZADAS</h3>
                        <div id="vagasFinalizadas" class="kanban-column-content"><div class="kanban-empty-state"><div><i class="fas fa-building-slash"></i>Nenhuma empresa nesta sessão.</div></div></div>
                    </div>
                    <div id="vagasCardsPool" class="hidden">
                    <?php if (empty($vagas_lista)): ?>
                        <div id="vagasEmptyState" class="text-center py-16 text-gray-400"><i class="fas fa-briefcase text-5xl mb-4 text-gray-600 opacity-30"></i><p class="text-xl">Nenhuma vaga cadastrada no momento.</p></div>
                    <?php else: foreach ($vagas_lista as $dado):
                        $nomePerfil = isset($dado['nome_perfil']) ? htmlspecialchars($dado['nome_perfil'], ENT_QUOTES, 'UTF-8') : 'Área não informada';
                        $area = dashboard_normalize_area($nomePerfil);
                        $vagaId = htmlspecialchars($dado['id'], ENT_QUOTES, 'UTF-8');
                        $empresaName = htmlspecialchars($dado['nome_empresa'] ?? 'Não informado', ENT_QUOTES, 'UTF-8');
                        $empresaId = htmlspecialchars($dado['id_empresa'] ?? '', ENT_QUOTES, 'UTF-8');
                        $vagaStatus = $select_model->status_vaga_por_id((int) $dado['id']);
                        $alunosEmSelecao = $select_model->alunos_selecao_por_vaga((int) $dado['id']);
                        $alunosFinalizados = $select_model->alunos_finalizados_por_vaga((int) $dado['id']);
                        $dataVaga = !empty($dado['data']) ? date('Y-m-d', strtotime($dado['data'])) : '';
                        $horaVaga = !empty($dado['hora']) ? substr((string) $dado['hora'], 0, 5) : '';
                    ?>
                        <div class="vaga-card <?= $CLS['card'] ?> relative overflow-hidden flex flex-col animate-slideUp" data-status="<?= htmlspecialchars($vagaStatus, ENT_QUOTES, 'UTF-8') ?>" data-area="<?= htmlspecialchars($area, ENT_QUOTES, 'UTF-8') ?>" data-empresa-id="<?= $empresaId ?>">
                            <div class="flex items-center justify-between mb-3">
                                <div class="vaga-card-title-wrap">
                                    <h3 class="text-lg font-bold text-white" title="<?= $empresaName ?>"><?= $empresaName ?></h3>
                                </div>
                                <div class="flex gap-1.5 shrink-0">
                                    <button type="button" class="vaga-card-toggle" aria-expanded="false" aria-label="Expandir informações da vaga"><i class="fas fa-chevron-down"></i></button>
                                    <?php if ($vagaStatus !== 'finalizada'): ?>
                                        <button type="button" title="Editar vaga" onclick="openModal('editarVagaModal-<?= $vagaId ?>')" class="w-9 h-9 rounded-lg border border-white/10 bg-white/5 text-gray-300 hover:text-green-400 hover:bg-green-500/10 hover:border-green-500/30 transition-all duration-200"><i class="fas fa-edit"></i></button>
                                    <?php endif; ?>
                                    <button type="button" title="Excluir vaga" onclick="openModal('excluirVagaModal-<?= $vagaId ?>')" class="w-9 h-9 rounded-lg border border-red-500/20 bg-red-500/10 text-red-400 hover:text-red-300 hover:bg-red-500/20 hover:border-red-400/40 transition-all duration-200"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <div class="vaga-card-summary mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= area_tw_classes($area) ?>"><i class="fas <?= area_icon($area) ?> mr-1 text-xs"></i><?= $nomePerfil === 'Design/Social mídia' ? 'Design/Mídia' : $nomePerfil ?></span>
                            </div>
                            <div class="vaga-card-details" hidden>
                              <div class="space-y-1.5 text-sm text-gray-300 mb-3">
                                <div class="flex items-center gap-2"><i class="fas fa-building w-5 text-green-500"></i><span><?= $empresaName ?></span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-users w-5 text-green-500"></i><span><?= htmlspecialchars($dado['quant_vaga'] ?? '0') ?> vaga(s) disponível(is)</span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-users w-5 text-green-500"></i><span><?= htmlspecialchars($dado['quant_cand'] ?? '0') ?> candidatos</span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-calendar w-5 text-green-500"></i><span><?= htmlspecialchars($dado['data'] ?? '') ?></span><span class="mx-1">|</span><span><?= htmlspecialchars($dado['hora'] ?? '') ?></span></div>
                                <div class="flex items-center gap-2"><i class="fas fa-briefcase w-5 text-green-500"></i><span><?= htmlspecialchars($dado['tipo_vaga'] ?? '') ?></span></div>
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-user-graduate w-5 text-green-500 mt-0.5"></i>
                                    <div class="flex flex-col gap-1">
                                        <?php if ($vagaStatus === 'sem-selecao'): ?>
                                            <span class="text-gray-500">Nenhum aluno em seleção</span>
                                        <?php elseif ($vagaStatus === 'em-selecao'): ?>
                                            <?php foreach ($alunosEmSelecao as $aluno): ?>
                                                <span class="text-sm text-gray-300"><?= htmlspecialchars($aluno['nome']) ?> <span class="text-xs text-yellow-400">(em seleção)</span></span>
                                            <?php endforeach; ?>
                                        <?php elseif ($vagaStatus === 'finalizada'): ?>
                                            <?php foreach ($alunosFinalizados as $aluno): ?>
                                                <span class="text-sm text-gray-300"><?= htmlspecialchars($aluno['nome']) ?> <span class="text-xs text-green-400">(finalizado)</span></span>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="mt-auto pt-2 space-y-2 border-t border-white/5">
                                <?php if ($vagaStatus === 'sem-selecao'): ?>
                                    <button type="button" onclick="openModal('modalSelecionarAluno-<?= $vagaId ?>')" class="w-full rounded-lg bg-green-600/20 text-green-300 border border-green-500/30 px-3 py-2 text-xs font-semibold hover:bg-green-600/30 transition-colors">
                                        <span>Selecionar alunos</span>
                                    </button>
                                <?php elseif ($vagaStatus === 'em-selecao'): ?>
                                    <form action="../controllers/controller.php" method="post" class="space-y-2">
                                        <input type="hidden" name="id_vaga" value="<?= $dado['id'] ?>">
                                        <?php foreach ($alunosEmSelecao as $aluno): ?>
                                            <label class="flex items-center gap-2 text-sm text-gray-200">
                                                <input type="checkbox" name="aprovados[]" value="<?= (int) $aluno['id'] ?>" <?= ((int) ($aluno['Status'] ?? 0) === 1) ? 'checked' : '' ?> class="rounded accent-green-600">
                                                <?= htmlspecialchars($aluno['nome']) ?>
                                            </label>
                                        <?php endforeach; ?>
                                        <div class="flex gap-2">
                                            <button type="button" onclick="openModal('modalAtualizarSelecao-<?= $vagaId ?>')" class="flex-1 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/30 px-3 py-2 text-xs font-semibold hover:bg-amber-500/30">Atualizar seleção</button>
                                            <button type="submit" name="acao" value="finalizar_selecao" class="flex-1 rounded-lg bg-green-600/20 text-green-300 border border-green-500/30 px-3 py-2 text-xs font-semibold hover:bg-green-600/30" data-finalizar-selecao="true">Finalizar seleção</button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <div class="space-y-2">
                                        <?php foreach ($alunosFinalizados as $aluno): ?>
                                            <form action="../controllers/controller.php" method="post" class="flex items-center justify-between gap-2">
                                                <input type="hidden" name="id_vaga" value="<?= $dado['id'] ?>">
                                                <input type="hidden" name="id_aluno" value="<?= (int) $aluno['id'] ?>">
                                                <span class="text-sm text-gray-200"><?= htmlspecialchars($aluno['nome']) ?></span>
                                                <button type="button" onclick="confirmarRemocaoAluno(<?= $dado['id'] ?>, <?= (int) $aluno['id'] ?>, '<?= htmlspecialchars($aluno['nome'], ENT_QUOTES) ?>')" class="px-2 py-1 rounded bg-red-500/20 text-red-300 text-xs font-semibold hover:bg-red-500/30">Remover</button>
                                            </form>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div id="modalSelecionarAluno-<?= $vagaId ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> modal-selection-shell min-w-0">
                                <div class="mb-4 rounded-2xl border border-green-500/15 bg-gradient-to-r from-green-500/10 via-white/5 to-transparent p-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-500/15 text-green-300 ring-1 ring-green-500/20">
                                                <i class="fas fa-user-check text-lg"></i>
                                            </div>
                                            <div class="space-y-2">
                                                <div class="inline-flex items-center gap-2 rounded-full border border-green-500/20 bg-green-500/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-green-300">
                                                    Seleção de alunos
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-white tracking-tight"><?= $empresaName ?></h3>
                                                    <p class="mt-1 text-sm text-gray-400">Perfil: <span class="font-medium text-gray-200"><?= htmlspecialchars($dado['nome_perfil'] ?? 'Não informado') ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="closeModal('modalSelecionarAluno-<?= $vagaId ?>')" class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/5 text-gray-300 transition hover:bg-white/10 hover:text-white"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>

                                <div class="mb-4 grid gap-3 md:grid-cols-4">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Alunos aptos</p>
                                        <p class="mt-2 text-xl font-bold text-white"><?= count($alunosDisponiveis ?? []) ?></p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Perfil</p>
                                        <p class="mt-2 text-xl font-bold text-white"><?= htmlspecialchars($dado['nome_perfil'] ?? 'N/A') ?></p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Empresa</p>
                                        <p class="mt-2 text-sm font-bold text-white"><?= htmlspecialchars($empresaName) ?></p>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Status</p>
                                        <p class="mt-2 text-lg font-bold text-green-300">Disponível</p>
                                    </div>
                                </div>

                                <form action="../controllers/controller.php" method="post" class="space-y-5" data-selecao-alunos="true">
                                    <input type="hidden" name="id_vaga" value="<?= (int) $dado['id'] ?>">
                                    <?php $alunosDisponiveis = $select_model->alunos($dado['nome_perfil'] ?? '', '', ''); ?>
                                    <?php if (empty($alunosDisponiveis)): ?>
                                        <div class="rounded-2xl border border-dashed border-white/10 bg-white/5 p-8 text-center text-gray-400">
                                            Nenhum aluno disponível para este perfil no momento.
                                        </div>
                                    <?php else: ?>
                                        <div class="overflow-hidden rounded-2xl border border-white/10 bg-neutral-950/40 shadow-inner shadow-black/30">
                                            <table class="w-full min-w-0 text-sm text-left">
                                                <thead class="bg-gradient-to-r from-green-700/25 to-emerald-500/10 text-gray-200">
                                                    <tr>
                                                        <th class="px-4 py-3 w-12 text-center"><input type="checkbox" id="selectAll-<?= $vagaId ?>" class="rounded accent-green-600" onclick="toggleSelectAllByVaga(<?= $vagaId ?>)"></th>
                                                        <th class="px-4 py-3 font-semibold">Aluno</th>
                                                        <th class="px-4 py-3 font-semibold">Média</th>
                                                        <th class="px-4 py-3 font-semibold">Perfil</th>
                                                        <th class="px-4 py-3 font-semibold">Projetos</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-white/10 bg-neutral-900/25">
                                                    <?php foreach ($alunosDisponiveis as $alunoDisponivel): ?>
                                                        <tr class="transition-colors hover:bg-green-500/5">
                                                            <td class="px-4 py-3 text-center"><input type="checkbox" name="alunos[]" value="<?= (int) $alunoDisponivel['id'] ?>" class="aluno-vaga-checkbox-<?= $vagaId ?> rounded accent-green-600"></td>
                                                            <td class="px-4 py-3 font-medium text-white"><?= htmlspecialchars($alunoDisponivel['nome']) ?></td>
                                                            <td class="px-4 py-3 text-gray-300"><?= htmlspecialchars((string) ($alunoDisponivel['medias'] ?? '-')) ?></td>
                                                            <td class="px-4 py-3">
                                                                <?php $perfil1 = $alunoDisponivel['perfil_opc1'] ?? ''; $perfil2 = $alunoDisponivel['perfil_opc2'] ?? ''; ?>
                                                                <div class="flex flex-wrap gap-2">
                                                                    <?php if ($perfil1): ?><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold <?= area_tw_classes($perfil1) ?>"><i class="fas <?= area_icon($perfil1) ?> text-[9px]"></i><?= htmlspecialchars($perfil1) ?></span><?php endif; ?>
                                                                    <?php if ($perfil2): ?><span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold <?= area_tw_classes($perfil2) ?>"><i class="fas <?= area_icon($perfil2) ?> text-[9px]"></i><?= htmlspecialchars($perfil2) ?></span><?php endif; ?>
                                                                </div>
                                                            </td>
                                                            <td class="px-4 py-3 text-gray-300"><?= htmlspecialchars((string) ($alunoDisponivel['projetos'] ?? '-')) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>

                                    <div class="flex justify-end gap-3 pt-2 border-t border-white/10">
                                        <button type="button" onclick="closeModal('modalSelecionarAluno-<?= $vagaId ?>')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                        <button type="submit" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-check"></i><span>Salvar seleção</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="modalAtualizarSelecao-<?= $vagaId ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> w-[92vw] max-w-[1100px] min-w-0" style="max-width: min(92vw, 1100px); width: min(92vw, 1100px);">
                                <div class="mb-6 rounded-2xl border border-amber-500/15 bg-gradient-to-r from-amber-500/10 via-white/5 to-transparent p-4 sm:p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/15 text-amber-300 ring-1 ring-amber-500/20">
                                                <i class="fas fa-pen-to-square text-lg"></i>
                                            </div>
                                            <div class="space-y-2">
                                                <div class="inline-flex items-center gap-2 rounded-full border border-amber-500/20 bg-amber-500/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-amber-300">
                                                    Atualizar seleção
                                                </div>
                                                <div>
                                                    <h3 class="text-2xl font-bold text-white tracking-tight"><?= $empresaName ?></h3>
                                                    <p class="mt-1 text-sm text-gray-400">Perfil: <span class="font-medium text-gray-200"><?= htmlspecialchars($dado['nome_perfil'] ?? 'Não informado') ?></span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" onclick="closeModal('modalAtualizarSelecao-<?= $vagaId ?>')" class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/5 text-gray-300 transition hover:bg-white/10 hover:text-white"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>

                                <form action="../controllers/controller.php" method="post" class="space-y-5" data-atualizar-selecao="true">
                                    <input type="hidden" name="id_vaga" value="<?= (int) $dado['id'] ?>">
                                    <input type="hidden" name="acao" value="atualizar_selecao">

                                    <?php $alunosAtuais = $alunosEmSelecao ?? []; $idsAtuais = array_map(fn($a) => (int) ($a['id'] ?? 0), $alunosAtuais); $alunosDisponiveis = $select_model->alunos($dado['nome_perfil'] ?? '', '', ''); $adicionarLista = []; foreach ($alunosDisponiveis as $aluno) { $idAluno = (int) ($aluno['id'] ?? 0); if ($idAluno > 0 && !in_array($idAluno, $idsAtuais, true)) { $adicionarLista[] = $aluno; } } ?>

                                    <div class="grid gap-5 lg:grid-cols-2">
                                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                            <div class="mb-4 flex items-center justify-between">
                                                <h4 class="text-lg font-bold text-white">Remover da seleção</h4>
                                                <span class="rounded-full bg-red-500/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-red-300"><?= count($alunosAtuais) ?></span>
                                            </div>
                                            <?php if (empty($alunosAtuais)): ?>
                                                <div class="rounded-xl border border-dashed border-white/10 bg-neutral-950/30 p-4 text-sm text-gray-400 text-center">
                                                    Nenhum aluno está na seleção desta vaga.
                                                </div>
                                            <?php else: ?>
                                                <div class="space-y-2">
                                                    <?php foreach ($alunosAtuais as $aluno): ?>
                                                        <label class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-neutral-900/30 px-3 py-2 text-sm text-gray-200">
                                                            <span class="flex items-center gap-3">
                                                                <input type="checkbox" name="remover[]" value="<?= (int) ($aluno['id'] ?? 0) ?>" class="rounded accent-red-500">
                                                                <span><?= htmlspecialchars($aluno['nome'] ?? 'Aluno') ?></span>
                                                            </span>
                                                            <span class="text-xs text-red-300">Remover</span>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                            <div class="mb-4 flex items-center justify-between">
                                                <h4 class="text-lg font-bold text-white">Adicionar alunos</h4>
                                                <span class="rounded-full bg-green-500/10 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-[0.2em] text-green-300"><?= count($adicionarLista) ?></span>
                                            </div>
                                            <?php if (empty($adicionarLista)): ?>
                                                <div class="rounded-xl border border-dashed border-white/10 bg-neutral-950/30 p-4 text-sm text-gray-400 text-center">
                                                    Não há alunos disponíveis para adicionar.
                                                </div>
                                            <?php else: ?>
                                                <div class="space-y-2">
                                                    <?php foreach ($adicionarLista as $aluno): ?>
                                                        <label class="flex items-center justify-between gap-3 rounded-xl border border-white/10 bg-neutral-900/30 px-3 py-2 text-sm text-gray-200">
                                                            <span class="flex items-center gap-3">
                                                                <input type="checkbox" name="adicionar[]" value="<?= (int) ($aluno['id'] ?? 0) ?>" class="rounded accent-green-500">
                                                                <span><?= htmlspecialchars($aluno['nome'] ?? 'Aluno') ?></span>
                                                            </span>
                                                            <span class="text-xs text-green-300">Adicionar</span>
                                                        </label>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3 pt-2 border-t border-white/10">
                                        <button type="button" onclick="closeModal('modalAtualizarSelecao-<?= $vagaId ?>')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                        <button type="submit" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-save"></i><span>Salvar alterações</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="modalConfirmarRemocaoAluno-<?= $vagaId ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> modal_box--compact text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/10 text-red-400 mb-4"><i class="fas fa-triangle-exclamation text-2xl"></i></div>
                                <h3 class="text-xl font-bold text-white">Confirmar remoção</h3>
                                <p class="text-gray-400 mt-2 mb-4">Deseja realmente remover o aluno <span id="nomeAlunoRemover-<?= $vagaId ?>" class="font-semibold text-white"></span> desta vaga?</p>
                                <form action="../controllers/controller.php" method="post" class="space-y-4">
                                    <input type="hidden" name="id_vaga" value="<?= $dado['id'] ?>">
                                    <input type="hidden" name="id_aluno" id="idAlunoRemover-<?= $vagaId ?>" value="">
                                    <input type="hidden" name="acao" value="remover_aluno_vaga">
                                    <div class="flex justify-center gap-3">
                                        <button type="button" onclick="closeModal('modalConfirmarRemocaoAluno-<?= $vagaId ?>')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                        <button type="submit" class="<?= $CLS['btn_danger'] ?> modal-danger-action"><i class="fas fa-trash-alt"></i><span>Continuar a remoção</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="modalCarta-<?= $vagaId ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> modal_box--compact text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-700/10 text-green-400 mb-4"><i class="fas fa-file-alt text-2xl"></i></div>
                                <h3 class="text-xl font-bold text-white">Gerar Carta de Encaminhamento</h3>
                                <p class="text-gray-400 mt-2 mb-4">Preencha os dados para gerar a carta.</p>
                                <form onsubmit="gerarCarta(event, <?= $vagaId ?>)" class="space-y-4 text-left">
                                    <div><label class="<?= $CLS['label'] ?>">Nome do Responsável</label><input type="text" id="responsavel-<?= $vagaId ?>" class="<?= $CLS['input'] ?>" required></div>
                                    <div class="flex justify-end gap-3">
                                        <button type="button" onclick="closeModal('modalCarta-<?= $vagaId ?>')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                        <button type="submit" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-file-download"></i><span>Gerar</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="editarVagaModal-<?= $vagaId ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> max-w-3xl w-[92vw] p-5 sm:p-7" style="max-width: min(92vw, 768px); width: min(92vw, 768px);">
                                <div class="flex items-start justify-between gap-4 mb-6">
                                    <div>
                                        <div class="inline-flex items-center gap-2 text-green-400 text-xs font-semibold uppercase tracking-wider mb-2"><i class="fas fa-briefcase"></i><span>Gerenciar vaga</span></div>
                                        <h2 class="text-2xl font-bold text-white">Editar vaga</h2>
                                        <p class="text-sm text-gray-400 mt-1">Atualize os dados da oportunidade.</p>
                                    </div>
                                    <button type="button" onclick="closeModal('editarVagaModal-<?= $vagaId ?>')" aria-label="Fechar edição da vaga" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white transition-colors"><i class="fas fa-times"></i></button>
                                </div>
                                <form action="../controllers/controller_editar_excluir.php" method="post" class="space-y-5">
                                    <input type="hidden" name="id_editar_vaga" value="<?= $vagaId ?>">
                                    <div>
                                        <label class="<?= $CLS['label'] ?>">Nome da Empresa</label>
                                        <select name="empresa_editar_vaga" class="<?= $CLS['select'] ?>" required>
                                            <?php foreach ($empresas_lista as $empresa): $sel = $empresa['id'] == $empresaId ? 'selected' : ''; ?>
                                                <option value="<?= $empresa['id'] ?>" <?= $sel ?>><?= htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8') ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="<?= $CLS['label'] ?>">Área de Atuação</label>
                                        <div class="grid grid-cols-2 gap-2 mt-1">
                                            <?php foreach ($perfis_vaga_opcoes as $pid => $pnome):
                                                $checked = (dashboard_normalize_area($nomePerfil) === dashboard_normalize_area($pnome)) ? 'checked' : ''; ?>
                                                <label class="flex items-center gap-2 text-sm text-gray-300"><input type="radio" name="perfil_editar_vaga" value="<?= $pid ?>" <?= $checked ?> class="<?= $CLS['checkbox'] ?>"><?= $pnome ?></label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div><label class="<?= $CLS['label'] ?>">Vagas Disponíveis</label><input type="number" name="quantidade_editar_vaga" min="1" value="<?= htmlspecialchars($dado['quant_vaga'] ?? '') ?>" class="<?= $CLS['input'] ?>" required></div>
                                    <div><label class="<?= $CLS['label'] ?>">Quantidade de Candidatos</label><input type="number" name="quant_candidatos_editar_vaga" min="0" value="<?= htmlspecialchars($dado['quant_cand'] ?? '') ?>" class="<?= $CLS['input'] ?>" required></div>
                                    <div>
                                        <label class="<?= $CLS['label'] ?>">Tipo de Vaga</label>
                                        <select name="tipo_editar_vaga" class="<?= $CLS['select'] ?>" required>
                                            <?php foreach (['Hibrido'=>'Híbrido','Presencial'=>'Presencial','HomeOffice'=>'Home Office'] as $val=>$lbl):
                                                $sel = ($dado['tipo_vaga'] ?? '') === $val ? 'selected' : ''; ?>
                                                <option value="<?= $val ?>" <?= $sel ?>><?= $lbl ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div><label class="<?= $CLS['label'] ?>">Data</label><input type="date" name="data_editar_vaga" value="<?= htmlspecialchars($dataVaga) ?>" class="<?= $CLS['input'] ?>"></div>
                                        <div><label class="<?= $CLS['label'] ?>">Hora</label><input type="time" name="hora_editar_vaga" value="<?= htmlspecialchars($horaVaga) ?>" class="<?= $CLS['input'] ?>"></div>
                                    </div>
                                    <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3 border-t border-white/10 pt-5">
                                        <button type="button" onclick="closeModal('editarVagaModal-<?= $vagaId ?>')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                        <button type="submit" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-save"></i><span>Salvar</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="excluirVagaModal-<?= $vagaId ?>" class="<?= $CLS['modal_overlay'] ?>">
                            <div class="<?= $CLS['modal_box'] ?> modal_box--compact text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/10 text-red-500 mb-4"><i class="fas fa-exclamation-triangle text-2xl"></i></div>
                                <h3 class="text-xl font-bold text-white">Confirmar Exclusão</h3>
                                <p class="text-gray-400 mt-2">Tem certeza que deseja excluir esta vaga?</p>
                                <form action="../controllers/controller_editar_excluir.php" method="post" class="mt-6">
                                    <input type="hidden" name="id_excluir_vaga" value="<?= $vagaId ?>">
                                    <div class="flex justify-center gap-3">
                                        <button type="button" onclick="closeModal('excluirVagaModal-<?= $vagaId ?>')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                                        <button type="submit" class="<?= $CLS['btn_danger'] ?> modal-danger-action"><i class="fas fa-trash-alt"></i><span>Excluir</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; endif; ?>
                    </div>
                </div>
                <p id="vagasNoResults" class="hidden text-center py-12 text-gray-400"><i class="fas fa-search text-4xl mb-4 text-gray-600 opacity-30 block"></i>Nenhuma vaga encontrada com os filtros atuais.</p>
            </section>

            <div id="novaVagaModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?> max-w-[420px] w-[92vw] p-5 sm:p-5 bg-[#1d1d1d] border border-white/10 shadow-xl rounded-2xl" style="max-width: 420px; width: min(92vw, 420px);">
                    <h2 class="text-[2rem] font-bold mb-5 text-white leading-tight">Nova Vaga</h2>
                    <form action="../controllers/controller.php" method="post" class="space-y-4">
                        <div>
                            <label class="<?= $CLS['label'] ?>">Nome da Empresa</label>
                            <select name="empresa" class="<?= $CLS['select'] ?>" required>
                                <option value="" selected disabled>Selecione uma empresa</option>
                                <?php foreach ($empresas_lista as $empresa): ?><option value="<?= $empresa['id'] ?>"><?= htmlspecialchars($empresa['nome']) ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Área de Atuação</label>
                            <div class="grid grid-cols-2 gap-2 mt-1">
                                <?php foreach ($perfis_vaga_opcoes as $pid => $pnome): ?>
                                    <label class="flex items-center gap-2 text-sm text-gray-300"><input type="radio" name="areas" value="<?= $pid ?>" class="<?= $CLS['checkbox'] ?>"><?= $pnome ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div><label class="<?= $CLS['label'] ?>">Quantidade de Vagas</label><input type="number" name="quant_vagas" min="1" class="<?= $CLS['input'] ?>" required></div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Tipo de Vaga</label>
                            <select name="tipo_vaga" class="<?= $CLS['select'] ?>" required>
                                <option value="" selected disabled>Selecione o tipo</option>
                                <option value="Hibrido">Híbrido</option><option value="Presencial">Presencial</option><option value="HomeOffice">Home Office</option>
                            </select>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" onclick="closeModal('novaVagaModal')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                            <button type="submit" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-save"></i><span>Salvar</span></button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="registroVagaModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?> max-w-3xl">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-white">Registro de Vagas</h2>
                        <button onclick="closeModal('registroVagaModal')" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
                    </div>

                    <div class="bg-[#1c2a21] border border-[#2f5233] rounded-xl p-4 mb-6">
                        <h4 class="font-semibold text-[#86efac] mb-2 text-sm">Orientações para preenchimento</h4>
                        <ul class="text-xs text-gray-300 space-y-1">
                            <li>• Selecione o nome de sua empresa ou organização.</li>
                            <li>• Informe a quantidade de vagas para cada perfil desejado.</li>
                            <li>• Selecione como pretende realizar a escolha dos candidatos.</li>
                            <li><strong class="text-gray-200">• Utilize o campo "Observações" para: </strong>
                                <ol class="mt-2 ml-6 list-decimal space-y-1">
                                    <li>Apresentar brevemente a instituição e sua área de atuação.</li>
                                    <li>Descrever as atividades que o estagiário desenvolverá.</li>
                                    <li>Informar a natureza dos serviços prestados pela organização.</li>
                                </ol>
                            </li>
                        </ul>
                    </div>

                    <form method="POST" action="../control/Controller.php" class="space-y-6">
                        <input type="hidden" name="acao" value="cadastrar_vagas">

                        <div>
                            <label class="<?= $CLS['label'] ?>">Empresa / Instituição</label>
                            <select name="concedente_id" required class="<?= $CLS['select'] ?>">
                                <option value="">Selecione aqui o nome de sua empresa</option>
                                <?php foreach ($empresas_lista as $empresa): ?>
                                    <option value="<?= $empresa['id'] ?>"><?= htmlspecialchars($empresa['nome'], ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="<?= $CLS['label'] ?> mb-2">Modelo de Seleção</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-gray-200 text-sm">
                                    <input type="radio" name="forma_selecao" value="Selecao" class="<?= $CLS['checkbox'] ?>">
                                    Desejo realizar processo seletivo próprio.
                                </label>
                                <label class="flex items-center gap-2 text-gray-200 text-sm">
                                    <input type="radio" name="forma_selecao" value="Entrevista" class="<?= $CLS['checkbox'] ?>">
                                    Desejo entrevistar os candidatos encaminhados.
                                </label>
                                <label class="flex items-center gap-2 text-gray-200 text-sm">
                                    <input type="radio" name="forma_selecao" value="Indicacao" class="<?= $CLS['checkbox'] ?>">
                                    Desejo que a escola selecione e indique os estudantes conforme o perfil solicitado.
                                </label>
                            </div>
                        </div>

                        <div class="bg-[#2a2417] border border-[#7c5e1b] rounded-xl p-4">
                            <p class="text-sm text-gray-300 leading-relaxed">
                                <strong class="text-[#facc15]">Importante:</strong> Não dispomos de alunos com perfil de saída voltado para atividades administrativas. Caso a instituição tenha interesse em estagiários com este perfil, recomendamos a busca junto ao curso técnico em Administração.
                            </p>
                        </div>

                        <div>
                            <label class="<?= $CLS['label'] ?> mb-3">Perfil Desejado para os Estagiários</label>
                            <p class="text-xs text-gray-400 mb-3">Preencha a quantidade de vagas que pretende ofertar no(s) perfil/perfis escolhido(s).</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-center text-xs font-semibold text-green-400 mb-1">Desenvolvedor</label>
                                    <input type="number" min="0" name="vagas_desenvolvedor" class="<?= $CLS['input'] ?>">
                                </div>
                                <div>
                                    <label class="block text-center text-xs font-semibold text-green-400 mb-1">Design/Mídias</label>
                                    <input type="number" min="0" name="vagas_design" class="<?= $CLS['input'] ?>">
                                </div>
                                <div>
                                    <label class="block text-center text-xs font-semibold text-green-400 mb-1">Suporte/Redes</label>
                                    <input type="number" min="0" name="vagas_suporte" class="<?= $CLS['input'] ?>">
                                </div>
                                <div>
                                    <label class="block text-center text-xs font-semibold text-green-400 mb-1">Tutoria</label>
                                    <input type="number" min="0" name="vagas_tutoria" class="<?= $CLS['input'] ?>">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="<?= $CLS['label'] ?> mb-3">Sobre a seleção, responda:</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-center text-xs font-semibold text-green-400 mb-1">Data</label>
                                    <input type="date" name="data" class="<?= $CLS['input'] ?>">
                                </div>
                                <div>
                                    <label class="block text-center text-xs font-semibold text-green-400 mb-1">Horário</label>
                                    <input type="time" name="hora" class="<?= $CLS['input'] ?>">
                                </div>
                                <div>
                                    <label class="block text-center text-xs font-semibold text-green-400 mb-1">Procurar por</label>
                                    <input type="text" name="procurar_por" class="<?= $CLS['input'] ?>">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="<?= $CLS['label'] ?>">Observações</label>
                            <textarea name="observacoes" rows="3" class="<?= $CLS['input'] ?> h-auto"></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" onclick="closeModal('registroVagaModal')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                            <button type="submit" class="<?= $CLS['btn_primary'] ?>"><i class="fa-solid fa-floppy-disk"></i><span>Registrar Vagas</span></button>
                        </div>
                    </form>
                </div>
            </div>

            <section id="view-selecionados" class="view-section hidden">
                <?php if (isset($qtd)) { ?>
                    <div class="fixed top-8 left-1/2 -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-[60] flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> <?= $qtd ?> aluno(s) aprovado(s) com sucesso!
                    </div>
                <?php } ?>
                <form method="post" action="">
                    <?php foreach ($vagas_com_alunos_sel as $vaga):
                        $id_vaga = $vaga['id_vaga'];
                        $alunos = $select_model->alunos_selecionados_estagio($id_vaga);
                        $nome_empresa = $select_model->nome_empresa_por_vaga($id_vaga);
                    ?>
                        <div class="<?= $CLS['card'] ?> mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2"><i class="fas fa-building text-green-400 text-xl"></i><h3 class="text-lg font-bold text-green-400"><?= htmlspecialchars($nome_empresa) ?></h3></div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/10 text-blue-400"><i class="fas fa-users text-xs mr-1"></i>Alunos Selecionados</span>
                            </div>
                            <div class="overflow-x-auto rounded-lg">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-gray-400 border-b border-white/10">
                                            <th class="w-10 py-2"></th><th class="py-2">Aluno</th><th class="w-20 py-2">ID</th><th class="w-28 py-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($alunos as $aluno):
                                            $isApproved = isset($aluno['status']) && $aluno['status'] === 'approved'; ?>
                                            <tr class="border-b border-white/5 hover:bg-green-700/5 transition-colors">
                                                <td class="py-2">
                                                    <?php if ($isApproved): ?><i class="fas fa-check-circle text-green-500"></i>
                                                    <?php else: ?><input type="checkbox" name="selecionados[]" value="<?= $aluno['id'] . '|' . $id_vaga ?>" class="<?= $CLS['checkbox'] ?>"><?php endif; ?>
                                                </td>
                                                <td class="py-2 font-semibold text-white">
                                                    <?= htmlspecialchars($aluno['nome']) ?>
                                                    <?php if (!empty($aluno['contato'])):
                                                        $num = preg_replace('/\D/', '', $aluno['contato']);
                                                        $msg = $isApproved ? "Olá " . urlencode($aluno['nome']) . "! Parabéns! Você foi aprovado na entrevista da " . urlencode($nome_empresa) : "Olá " . urlencode($aluno['nome']) . ", infelizmente você não foi aprovado na entrevista da " . urlencode($nome_empresa); ?>
                                                        <a href="https://wa.me/55<?= $num ?>?text=<?= $msg ?>" target="_blank" class="ml-2"><i class="fab fa-whatsapp <?= $isApproved ? 'text-green-500' : 'text-gray-500' ?>"></i></a>
                                                    <?php else: ?><span class="ml-2"><i class="fab fa-whatsapp text-gray-500"></i></span><?php endif; ?>
                                                </td>
                                                <td class="py-2 text-xs text-gray-400"><?= htmlspecialchars($aluno['id']) ?></td>
                                                <td class="py-2"><span class="inline-block px-2 py-1 text-xs rounded-full <?= $isApproved ? 'bg-green-600' : 'bg-gray-600' ?> text-white"><?= $isApproved ? 'Aprovado' : 'Pendente' ?></span></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" name="aprovar" class="<?= $CLS['btn_primary'] ?> mt-4 w-full md:w-auto"><i class="fas fa-check-circle"></i> Aprovar Selecionados</button>
                </form>
            </section>

            <section id="view-alunos" class="view-section hidden">
                <div class="mb-4 <?= $CLS['card'] ?>">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <div class="relative flex-1">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
                                <input type="text" id="searchAluno" placeholder="Buscar aluno..." class="<?= $CLS['input'] ?> pl-10 w-full" oninput="filtrarAlunos()">
                            </div>
                            <div class="relative sm:w-56 w-full">
                                <select id="filterPerfilAluno" class="<?= $CLS['select'] ?> w-full" onchange="filtrarAlunos()">
                                    <option value="">Todos os perfis</option>
                                    <option value="desenvolvimento">Desenvolvimento</option>
                                    <option value="design">Design/Mídias</option>
                                    <option value="tutoria">Tutoria</option>
                                    <option value="suporte">Suporte/Redes</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                            <div class="relative sm:w-56 w-full">
                                <select id="filterLocalidadeAluno" class="<?= $CLS['select'] ?> w-full" onchange="filtrarAlunos()">
                                    <option value="">Todas as localidades</option>
                                    <?php foreach ($localidades_opcoes as $valor_localidade => $rotulo_localidade): ?>
                                        <option value="<?= htmlspecialchars(strtolower($valor_localidade), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($rotulo_localidade, ENT_QUOTES, 'UTF-8') ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-6 <?= $CLS['card'] ?>">
                    <div class="flex flex-col lg:flex-row lg:items-center gap-2 sm:gap-3">
                        <button onclick="abrirAdicionarAluno()" class="aluno-toolbar-main <?= $CLS['btn_primary'] ?> w-full sm:w-auto flex-shrink-0"><i class="fas fa-plus"></i><span>Adicionar Aluno</span></button>
                        <button onclick="gerarRelatorioAlunosCompleto()" class="<?= $CLS['btn_secondary'] ?> w-full sm:w-auto flex-shrink-0"><i class="fas fa-file-pdf"></i><span>Alunos Encaminhados
                            
                        </span></button>
                        <button onclick="gerarRelatoriTcesCompleto()" class="<?= $CLS['btn_secondary'] ?> w-full sm:w-auto flex-shrink-0"><i class="fas fa-file-contract"></i><span>Relatório de TCEs</span></button>
                    </div>

                <div class="hidden md:block <?= $CLS['card'] ?> overflow-x-auto mt-2">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-gray-400 uppercase border-b border-white/10">
                                <th class="px-4 py-4 font-semibold">Nome</th><th class="px-4 py-4 font-semibold text-center">Área 1</th><th class="px-4 py-4 font-semibold text-center">Área 2</th><th class="px-4 py-4 font-semibold text-center">Localidade</th><th class="px-4 py-4 font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="alunosTableBody">
                            <?php foreach ($dados_alunos_todos as $dado):
                                $a1 = $dado['perfil_opc1'] ?? ''; $a2 = $dado['perfil_opc2'] ?? ''; $loc = $dado['localidade'] ?? ''; ?>
                                <tr class="aluno-row border-b border-white/5 hover:bg-green-700/8 transition-colors" data-nome="<?= htmlspecialchars(strtolower($dado['nome']), ENT_QUOTES, 'UTF-8') ?>" data-perfil="<?= htmlspecialchars(dashboard_normalize_area($a1) . ' ' . dashboard_normalize_area($a2), ENT_QUOTES, 'UTF-8') ?>" data-localidade="<?= htmlspecialchars(strtolower($loc), ENT_QUOTES, 'UTF-8') ?>">
                                    <td class="px-4 py-4 font-medium text-white"><?= htmlspecialchars($dado['nome']) ?></td>
                                    <td class="px-4 py-4 text-center"><?php if ($a1): ?><span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold gap-1 <?= area_tw_classes($a1) ?>"><i class="fas <?= area_icon($a1) ?> text-xs"></i><?= htmlspecialchars($a1) ?></span><?php else: ?><span class="text-gray-500">-</span><?php endif; ?></td>
                                    <td class="px-4 py-4 text-center"><?php if ($a2): ?><span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold gap-1 <?= area_tw_classes($a2) ?>"><i class="fas <?= area_icon($a2) ?> text-xs"></i><?= htmlspecialchars($a2) ?></span><?php else: ?><span class="text-gray-500">-</span><?php endif; ?></td>
                                    <td class="px-4 py-4 text-center"><?php if ($loc): ?><span class="localidade-badge <?= htmlspecialchars(dashboard_localidade_badge_classes($loc), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(dashboard_localidade_display($loc)) ?></span><?php else: ?><span class="text-gray-500">-</span><?php endif; ?></td>
                                    <td class="px-4 py-4 text-right whitespace-nowrap">
                                        <button onclick='verDetalhesAluno(<?= (int)$dado["id"] ?>)' class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 hover:text-blue-300 transition-all border border-blue-400/30" title="Detalhes"><i class="fas fa-info-circle"></i></button>
                                        <button onclick='editarAlunoModal(<?= (int)$dado["id"] ?>)' class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 hover:text-green-300 transition-all ml-2 border border-green-400/30" title="Editar"><i class="fas fa-edit"></i></button>
                                        <button onclick='confirmarExclusaoAluno(<?= (int)$dado["id"] ?>)' class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 hover:text-red-300 transition-all ml-2 border border-red-400/30" title="Deletar"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p id="alunosNoResultsDesktop" class="hidden text-center py-8 text-gray-400">Nenhum aluno encontrado.</p>
                </div>

                <div id="alunosMobileCards" class="md:hidden space-y-3 sm:space-y-4">
                    <?php foreach ($dados_alunos_todos as $dado):
                        $a1 = $dado['perfil_opc1'] ?? ''; $a2 = $dado['perfil_opc2'] ?? ''; $loc = $dado['localidade'] ?? ''; ?>
                        <div class="aluno-row-mobile <?= $CLS['card'] ?> transition-all hover:shadow-lg hover:shadow-green-700/20" data-nome="<?= htmlspecialchars(strtolower($dado['nome']), ENT_QUOTES, 'UTF-8') ?>" data-perfil="<?= htmlspecialchars(dashboard_normalize_area($a1) . ' ' . dashboard_normalize_area($a2), ENT_QUOTES, 'UTF-8') ?>" data-localidade="<?= htmlspecialchars(strtolower($loc), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm sm:text-base font-bold text-white leading-snug uppercase tracking-tight"><?= htmlspecialchars($dado['nome']) ?></h3>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <?php if ($a1): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold gap-1 <?= area_tw_classes($a1) ?> transition-colors">
                                                <i class="fas <?= area_icon($a1) ?> text-xs"></i>
                                                <span><?= htmlspecialchars($a1) ?></span>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($a2): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold gap-1 <?= area_tw_classes($a2) ?> transition-colors">
                                                <i class="fas <?= area_icon($a2) ?> text-xs"></i>
                                                <span><?= htmlspecialchars($a2) ?></span>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($loc): ?>
                                            <span class="localidade-badge <?= htmlspecialchars(dashboard_localidade_badge_classes($loc), ENT_QUOTES, 'UTF-8') ?> transition-colors">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span><?= htmlspecialchars(dashboard_localidade_display($loc)) ?></span>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!$a1 && !$a2): ?>
                                            <span class="text-xs text-gray-500 italic">Sem perfis definidos</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                                    <button onclick='verDetalhesAluno(<?= (int)$dado["id"] ?>)' class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 hover:text-blue-300 transition-all border border-blue-400/30" title="Detalhes"><i class="fas fa-info-circle"></i></button>
                                    <button onclick='editarAlunoModal(<?= (int)$dado["id"] ?>)' class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 hover:text-green-300 transition-all border border-green-400/30" title="Editar"><i class="fas fa-edit"></i></button>
                                    <button onclick='confirmarExclusaoAluno(<?= (int)$dado["id"] ?>)' class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 hover:text-red-300 transition-all border border-red-400/30" title="Deletar"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <p id="alunosNoResultsMobile" class="hidden text-center py-8 text-gray-400">Nenhum aluno encontrado.</p>
                </div>
            </section>

            <script>window.__ALUNOS__ = <?= json_encode($dados_alunos_todos) ?>;</script>

            <div id="detalhesAlunoModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?> max-w-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-white">Detalhes do Aluno</h2>
                        <button type="button" onclick="closeModal('detalhesAlunoModal')" aria-label="Fechar detalhes do aluno" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white"><i class="fas fa-times"></i></button>
                    </div>
                    <div id="detalhesAlunoContent" class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm"></div>
                </div>
            </div>

            <div id="editarAlunoModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?> max-w-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-white">Editar Aluno</h2>
                        <button onclick="closeModal('editarAlunoModal')" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
                    </div>
                    <form id="editAlunoForm" action="../controllers/controller.php" method="post" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="hidden" name="acao" value="editar">
                        <input type="hidden" id="editAlunoId" name="id" value="">
                        <div><label class="<?= $CLS['label'] ?>">Nome</label><input type="text" id="editNome" name="nome" required class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Contato</label><input type="text" id="editContato" name="contato" class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Médias</label><input type="number" id="editMedias" name="medias" min="0" max="10" step="0.1" required class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Email</label><input type="email" id="editEmail" name="email" required class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Projetos</label><input type="text" id="editProjetos" name="projetos" class="<?= $CLS['input'] ?>"></div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Localidade</label>
                            <select id="editLocalidade" name="localidade" required class="<?= $CLS['select'] ?>">
                                <?php foreach ($localidades_opcoes as $valor_localidade => $rotulo_localidade): ?>
                                    <option value="<?= htmlspecialchars($valor_localidade, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($rotulo_localidade, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Opção 1</label>
                            <select id="editPerfilOpc1" name="perfil_opc1" required class="<?= $CLS['select'] ?>">
                                <option value="desenvolvimento">Desenvolvimento</option><option value="design">Design/Mídias</option><option value="tutoria">Tutoria</option><option value="suporte/redes">Suporte/Redes</option>
                            </select>
                        </div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Opção 2</label>
                            <select id="editPerfilOpc2" name="perfil_opc2" required class="<?= $CLS['select'] ?>">
                                <option value="desenvolvimento">Desenvolvimento</option><option value="design">Design/Mídias</option><option value="tutoria">Tutoria</option><option value="suporte/redes">Suporte/Redes</option>
                            </select>
                        </div>
                        <div><label class="<?= $CLS['label'] ?>">Ocorrência</label><input type="text" id="editOcorrencia" name="ocorrencia" class="<?= $CLS['input'] ?>"></div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Custeio</label>
                            <select id="editCusteio" name="custeio" required class="<?= $CLS['select'] ?>"><option value="1">Sim</option><option value="0">Não</option></select>
                        </div>
                        <div><label class="<?= $CLS['label'] ?>">Entregas Individuais</label><input type="text" id="editEntregasIndividuais" name="entregas_individuais" class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Entregas do Grupo</label><input type="text" id="editEntregasGrupo" name="entregas_grupo" class="<?= $CLS['input'] ?>"></div>
                    </form>
                    <div class="mt-6 flex justify-end gap-3">
                        <button onclick="closeModal('editarAlunoModal')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                        <button type="submit" form="editAlunoForm" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-save"></i><span>Salvar</span></button>
                    </div>
                </div>
            </div>

            <div id="adicionarAlunoModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?> max-w-2xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-white">Adicionar Aluno</h2>
                        <button onclick="closeModal('adicionarAlunoModal')" class="text-gray-400 hover:text-white"><i class="fas fa-times"></i></button>
                    </div>
                    <form id="addAlunoForm" action="../controllers/controller_adicionar.php" method="post" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="hidden" name="acao" value="adicionar">
                        <div><label class="<?= $CLS['label'] ?>">Nome</label><input type="text" name="nome" required class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Contato</label><input type="text" name="contato" class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Médias</label><input type="number" name="medias" min="0" max="10" step="0.1" required class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Email</label><input type="email" name="email" required class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Projetos</label><input type="text" name="projetos" class="<?= $CLS['input'] ?>"></div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Localidade</label>
                            <select name="localidade" required class="<?= $CLS['select'] ?>">
                                <option value="" selected disabled>Selecione...</option>
                                <?php foreach ($localidades_opcoes as $valor_localidade => $rotulo_localidade): ?>
                                    <option value="<?= htmlspecialchars($valor_localidade, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($rotulo_localidade, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Opção 1</label>
                            <select name="perfil_opc1" required class="<?= $CLS['select'] ?>"><option value="desenvolvimento">Desenvolvimento</option><option value="design">Design/Mídias</option><option value="tutoria">Tutoria</option><option value="suporte/redes">Suporte/Redes</option></select>
                        </div>
                        <div>
                            <label class="<?= $CLS['label'] ?>">Opção 2</label>
                            <select name="perfil_opc2" required class="<?= $CLS['select'] ?>"><option value="desenvolvimento">Desenvolvimento</option><option value="design">Design/Mídias</option><option value="tutoria">Tutoria</option><option value="suporte/redes">Suporte/Redes</option></select>
                        </div>
                        <div><label class="<?= $CLS['label'] ?>">Ocorrência</label><input type="text" name="ocorrencia" class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Custeio</label><select name="custeio" required class="<?= $CLS['select'] ?>"><option value="1">Sim</option><option value="0">Não</option></select></div>
                        <div><label class="<?= $CLS['label'] ?>">Entregas Individuais</label><input type="text" name="entregas_individuais" class="<?= $CLS['input'] ?>"></div>
                        <div><label class="<?= $CLS['label'] ?>">Entregas do Grupo</label><input type="text" name="entregas_grupo" class="<?= $CLS['input'] ?>"></div>
                    </form>
                    <div class="mt-6 flex justify-end gap-3">
                        <button onclick="closeModal('adicionarAlunoModal')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                        <button type="submit" form="addAlunoForm" class="<?= $CLS['btn_primary'] ?>"><i class="fas fa-plus"></i><span>Adicionar</span></button>
                    </div>
                </div>
            </div>

            <div id="confirmarExclusaoAlunoModal" class="<?= $CLS['modal_overlay'] ?>">
                <div class="<?= $CLS['modal_box'] ?> modal_box--compact text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/10 text-red-500 mb-4"><i class="fas fa-exclamation-triangle text-2xl"></i></div>
                    <h3 class="text-xl font-bold text-white">Confirmar Exclusão</h3>
                    <p class="text-gray-400 mt-2">Tem certeza que deseja excluir este aluno?</p>
                    <div class="mt-6 flex justify-center gap-3">
                        <button onclick="closeModal('confirmarExclusaoAlunoModal')" class="<?= $CLS['btn_secondary'] ?>"><i class="fas fa-times"></i><span>Cancelar</span></button>
                        <button type="button" onclick="efetivarExclusaoAluno()" class="<?= $CLS['btn_danger'] ?> modal-danger-action"><i class="fas fa-trash"></i><span>Excluir</span></button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<button type="button" id="themeToggle" class="floating-theme-toggle" aria-label="Alternar para modo claro" title="Alternar para modo claro">
    <i class="floating-theme-toggle__icon fa-solid fa-sun" aria-hidden="true"></i>
</button>

<footer class="estagio-footer" aria-label="Desenvolvedores do sistema">
    <span>Desenvolvido por</span>
    <div class="estagio-footer__developers">
        <a href="https://www.instagram.com/abraaolyncoln_/" target="_blank" rel="noopener noreferrer" aria-label="Instagram de Abraão Lyncoln">
            <i class="fab fa-instagram" aria-hidden="true"></i>
            <span>Abraão Lyncoln</span>
        </a>
        <a href="https://www.instagram.com/arma_ndofilho/" target="_blank" rel="noopener noreferrer" aria-label="Instagram de Armando Filho">
            <i class="fab fa-instagram" aria-hidden="true"></i>
            <span>Armando Filho</span>
        </a>
    </div>
</footer>

<script>
function applyTheme(theme) {
    const lightMode = theme === 'light';
    document.documentElement.classList.toggle('light-mode', lightMode);
    document.documentElement.classList.toggle('dark', !lightMode);
    const themeColor = document.querySelector('meta[name="theme-color"]');
    if (themeColor) themeColor.content = lightMode ? '#eaf3e8' : '#1a1a1a';
    const toggle = document.getElementById('themeToggle');
    const icon = toggle?.querySelector('i');
    if (icon) icon.className = lightMode ? 'floating-theme-toggle__icon fa-solid fa-moon' : 'floating-theme-toggle__icon fa-solid fa-sun';
    toggle?.setAttribute('aria-label', lightMode ? 'Alternar para modo escuro' : 'Alternar para modo claro');
    toggle?.setAttribute('title', lightMode ? 'Alternar para modo escuro' : 'Alternar para modo claro');
}

function toggleTheme() {
    const nextTheme = document.documentElement.classList.contains('light-mode') ? 'dark' : 'light';
    applyTheme(nextTheme);
    try {
        localStorage.setItem('estagio-theme', nextTheme);
    } catch (error) {
        console.warn('Não foi possível salvar a preferência de tema do Estágio:', error);
    }
}

let savedTheme = 'dark';
try {
    savedTheme = localStorage.getItem('estagio-theme') === 'light' ? 'light' : 'dark';
} catch (error) {
    console.warn('Não foi possível carregar a preferência de tema do Estágio:', error);
}
applyTheme(savedTheme);

const PAGE_TITLES = {
    dashboard: 'Dashboard',
    empresas: 'Gerenciamento de Empresas',
    vagas: 'Vagas Disponíveis',
    selecionados: 'Alunos Selecionados',
    alunos: 'Gerenciamento de Alunos'
};

function showView(view) {
    if (!PAGE_TITLES[view]) view = 'dashboard';
    document.querySelectorAll('.view-section').forEach(s => s.classList.add('hidden'));
    const el = document.getElementById('view-' + view);
    if (el) el.classList.remove('hidden');

    document.querySelectorAll('.nav-link').forEach(a => {
        const active = a.dataset.view === view;
        a.classList.toggle('sidebar_active', active);
        if (active) {
            a.setAttribute('aria-current', 'page');
        } else {
            a.removeAttribute('aria-current');
        }
    });

    const titleEl = document.getElementById('pageTitle');
    if (titleEl) titleEl.textContent = PAGE_TITLES[view];

    if (location.hash !== '#' + view) history.replaceState(null, '', '#' + view);
    closeMobileSidebar();
    window.scrollTo({ top: 0, behavior: 'instant' });
}

function closeMobileSidebar() {
    document.getElementById('mobileSidebar')?.classList.add('-translate-x-full');
    document.getElementById('sidebarOverlay')?.classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);

    const initial = (location.hash || '#dashboard').replace('#', '');
    showView(initial);

    const vagaColumns = {
        'sem-selecao': document.getElementById('vagasSemSelecao'),
        'em-selecao': document.getElementById('vagasEmSelecao'),
        finalizada: document.getElementById('vagasFinalizadas')
    };
    document.querySelectorAll('#vagasCardsPool .vaga-card').forEach(card => {
        const target = vagaColumns[card.dataset.status] || vagaColumns['sem-selecao'];
        target?.appendChild(card);
    });
    atualizarEstadosVagas();

    document.querySelectorAll('.vaga-card-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.vaga-card');
            const details = card?.querySelector('.vaga-card-details');
            if (!card || !details) return;
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            button.setAttribute('aria-label', expanded ? 'Expandir informações da vaga' : 'Recolher informações da vaga');
            card.classList.toggle('is-expanded', !expanded);
            details.hidden = expanded;
        });
    });

    const hamburger = document.getElementById('hamburgerMenu');
    const closeSidebarBtn = document.getElementById('closeSidebar');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const overlay = document.getElementById('sidebarOverlay');

    hamburger?.addEventListener('click', () => {
        mobileSidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
    closeSidebarBtn?.addEventListener('click', closeMobileSidebar);
    overlay?.addEventListener('click', closeMobileSidebar);

    document.querySelectorAll('.ranking-expand-btn').forEach(button => {
        button.addEventListener('click', () => {
            const card = button.closest('.ranking-aluno-card');
            const details = card?.querySelector('.ranking-aluno-details');
            if (!details) return;
            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            button.setAttribute('aria-label', expanded ? 'Expandir detalhes do aluno' : 'Recolher detalhes do aluno');
            details.hidden = expanded;
            details.setAttribute('aria-hidden', expanded ? 'true' : 'false');
        });
    });

    document.querySelectorAll('.telefone-input').forEach(input => {
        input.addEventListener('input', e => {
            let value = e.target.value.replace(/\D/g, '').slice(0, 11);
            if (value.length <= 10) {
                value = value.replace(/(\d{2})(\d{0,4})(\d{0,4})/, '($1) $2-$3');
            } else {
                value = value.replace(/(\d{2})(\d{0,5})(\d{0,4})/, '($1) $2-$3');
            }
            e.target.value = value.trim();
        });
    });
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            const tel = form.querySelector('input[name="telefone"], input[name^="contato_editar"]');
            if (tel) tel.value = tel.value; // mantém formatado; back-end já trata via preg_replace na exibição
        });
    });

    document.querySelectorAll('form[data-selecao-alunos="true"]').forEach(form => {
        form.addEventListener('submit', function (event) {
            const checkedBoxes = form.querySelectorAll('input[name="alunos[]"]:checked');
            if (checkedBoxes.length === 0) {
                event.preventDefault();
                alert('Selecione pelo menos um aluno antes de salvar a seleção.');
            }
        });
    });

    document.querySelectorAll('form[data-atualizar-selecao="true"]').forEach(form => {
        form.addEventListener('submit', function (event) {
            const adicionar = form.querySelectorAll('input[name="adicionar[]"]:checked');
            const remover = form.querySelectorAll('input[name="remover[]"]:checked');
            if (adicionar.length === 0 && remover.length === 0) {
                event.preventDefault();
                alert('Selecione ao menos um aluno para adicionar ou remover antes de atualizar a seleção.');
            }
        });
    });

    document.querySelectorAll('button[data-finalizar-selecao="true"]').forEach(button => {
        button.addEventListener('click', function (event) {
            const form = button.closest('form');
            if (!form) return;

            const checkedBoxes = form.querySelectorAll('input[name="aprovados[]"]:checked');
            if (checkedBoxes.length === 0) {
                event.preventDefault();
                alert('Para finalizar a seleção, escolha pelo menos um aluno.');
            }
        });
    });

    filtrarVagas();
});

window.addEventListener('hashchange', () => showView(location.hash.replace('#', '')));

function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;

    const hiddenPool = modal.closest('#vagasCardsPool');
    if (hiddenPool && hiddenPool.classList.contains('hidden')) {
        document.body.appendChild(modal);
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('modal-open');
}
function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    if (!document.querySelector('.modal_overlay.flex:not(.hidden)')) {
        document.body.classList.remove('modal-open');
    }
}
function excluirFichaConcedente() {
    const select = document.querySelector('#relatorioConcedenteModal select[name="id"]');
    if (!select || !select.value) {
        alert('Selecione um cadastro para excluir.');
        return;
    }

    const nome = select.options[select.selectedIndex].text.trim();
    if (!confirm('Excluir o cadastro institucional de ' + nome + '? As vagas vinculadas também serão removidas.')) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'post';
    form.action = '../controllers/controller_editar_excluir.php';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'id_excluir_ficha';
    input.value = select.value;
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
document.addEventListener('click', e => {
    if (e.target instanceof HTMLElement && e.target.classList.contains('modal_overlay') && !e.target.classList.contains('hidden')) {
        closeModal(e.target.id);
    }
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal_overlay.flex:not(.hidden)');
        const topModal = openModals[openModals.length - 1];
        if (topModal) closeModal(topModal.id);
    }
});

function filtrarEmpresas() {
    const term = document.getElementById('searchEmpresa').value.toLowerCase().trim();
    const cards = document.querySelectorAll('#empresasGrid .empresa-card');
    let visible = 0;
    cards.forEach(card => {
        const match = !term || card.dataset.nome.includes(term) || card.dataset.endereco.includes(term) || card.dataset.contato.includes(term);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('empresasNoResults').classList.toggle('hidden', visible !== 0);
}

function filtrarVagas() {
    const search = document.getElementById('searchVaga')?.value.toLowerCase().trim() || '';
    const area = document.getElementById('filterAreaVaga').value.toLowerCase();
    const empresaId = document.getElementById('filterEmpresaVaga').value;
    const cards = document.querySelectorAll('#vagasGrid .vaga-card');
    let visible = 0;
    cards.forEach(card => {
        const matchSearch = !search || card.textContent.toLowerCase().includes(search);
        const matchArea = !area || card.dataset.area.toLowerCase() === area.toLowerCase();
        const matchEmpresa = !empresaId || card.dataset.empresaId === empresaId;
        const match = matchSearch && matchArea && matchEmpresa;
        card.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    atualizarEstadosVagas();
    const noResults = document.getElementById('vagasNoResults');
    if (noResults) noResults.classList.toggle('hidden', visible !== 0);
}

function atualizarEstadosVagas() {
    document.querySelectorAll('.kanban-column-content').forEach(column => {
        const cards = Array.from(column.querySelectorAll('.vaga-card'));
        const visibleCards = cards.filter(card => card.style.display !== 'none');
        const kanbanColumn = column.closest('.kanban-column');
        let emptyState = column.querySelector('.kanban-empty-state');
        if (!emptyState) {
            emptyState = document.createElement('div');
            emptyState.className = 'kanban-empty-state';
            emptyState.innerHTML = '<div><i class="fas fa-building-slash"></i>Nenhuma empresa nesta sessão.</div>';
            column.appendChild(emptyState);
        }
        emptyState.hidden = visibleCards.length !== 0;
        kanbanColumn?.classList.toggle('is-empty', visibleCards.length === 0);
    });
}

function filtrarAlunos() {
    const term = document.getElementById('searchAluno').value.toLowerCase().trim();
    const perfil = document.getElementById('filterPerfilAluno').value;
    const localidade = document.getElementById('filterLocalidadeAluno')?.value || '';
    let visibleDesktop = 0, visibleMobile = 0;

    document.querySelectorAll('#alunosTableBody .aluno-row').forEach(row => {
        const match = (!term || row.dataset.nome.includes(term)) && (!perfil || row.dataset.perfil.includes(perfil)) && (!localidade || row.dataset.localidade === localidade);
        row.style.display = match ? '' : 'none';
        if (match) visibleDesktop++;
    });
    document.getElementById('alunosNoResultsDesktop').classList.toggle('hidden', visibleDesktop !== 0);

    document.querySelectorAll('#alunosMobileCards .aluno-row-mobile').forEach(card => {
        const match = (!term || card.dataset.nome.includes(term)) && (!perfil || card.dataset.perfil.includes(perfil)) && (!localidade || card.dataset.localidade === localidade);
        card.style.display = match ? '' : 'none';
        if (match) visibleMobile++;
    });
    document.getElementById('alunosNoResultsMobile').classList.toggle('hidden', visibleMobile !== 0);
}

let alunoIdParaExcluir = null;

function abrirAdicionarAluno() {
    document.getElementById('addAlunoForm')?.reset();
    openModal('adicionarAlunoModal');
}

function getAreaBadgeHtml(area) {
    if (!area || area === '-') return '<span class="text-gray-500">-</span>';
    const lower = area.toLowerCase();
    let cls = 'bg-slate-500/15 text-slate-300 border border-slate-500/25', icon = 'fa-question';
    if (lower.includes('desenvolvimento')) { cls = 'bg-blue-600/20 text-blue-300 border border-blue-500/35'; icon = 'fa-code'; }
    else if (lower.includes('design') || lower.includes('midia')) { cls = 'bg-purple-600/20 text-purple-300 border border-purple-500/35'; icon = 'fa-paint-brush'; }
    else if (lower.includes('tutoria')) { cls = 'bg-emerald-600/20 text-emerald-300 border border-emerald-500/35'; icon = 'fa-chalkboard-teacher'; }
    else if (lower.includes('suporte') || lower.includes('redes')) { cls = 'bg-amber-600/20 text-amber-300 border border-amber-500/35'; icon = 'fa-network-wired'; }
    return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${cls}"><i class="fas ${icon} text-xs mr-1"></i>${area}</span>`;
}

function normalizarLocalidade(localidade) {
    const valor = String(localidade || '').trim().toLowerCase();
    if (valor === 'mp' || valor === 'maranguape') return 'Maranguape';
    if (valor === 'mc' || valor === 'maracanau' || valor === 'maracanaú') return 'Maracanau';
    if (valor === 'mp/mc' || valor === 'ambos' || valor.startsWith('maranguape/')) return 'Maranguape/Maracanau';
    return localidade || '';
}

function verDetalhesAluno(id) {
    const aluno = window.__ALUNOS__.find(a => parseInt(a.id) === parseInt(id));
    if (!aluno) return;
    const content = document.getElementById('detalhesAlunoContent');
    const fields = [
        ['ID', aluno.id], ['Nome', aluno.nome], ['Score', aluno.score || '-'], ['Contato', aluno.contato || '-'],
        ['Médias', aluno.medias || '-'], ['Email', aluno.email || '-'], ['Projetos', aluno.projetos || '-'],
        ['Localidade', aluno.localidade || '-'],
    ];
    let html = fields.map(([label, value]) => `
        <div class="flex flex-col gap-1"><span class="text-xs uppercase tracking-wide text-gray-400 font-semibold">${label}</span><span class="text-white break-words">${value}</span></div>
    `).join('');
    html += `<div class="flex flex-col gap-1"><span class="text-xs uppercase tracking-wide text-gray-400 font-semibold">Opção 1</span>${getAreaBadgeHtml(aluno.perfil_opc1)}</div>`;
    html += `<div class="flex flex-col gap-1"><span class="text-xs uppercase tracking-wide text-gray-400 font-semibold">Opção 2</span>${getAreaBadgeHtml(aluno.perfil_opc2)}</div>`;
    html += `<div class="flex flex-col gap-1"><span class="text-xs uppercase tracking-wide text-gray-400 font-semibold">Ocorrência</span><span class="text-white">${aluno.ocorrencia || '-'}</span></div>`;
    html += `<div class="flex flex-col gap-1"><span class="text-xs uppercase tracking-wide text-gray-400 font-semibold">Custeio</span><span class="text-white">${aluno.custeio == 1 ? 'Sim' : 'Não'}</span></div>`;
    html += `<div class="flex flex-col gap-1"><span class="text-xs uppercase tracking-wide text-gray-400 font-semibold">Entregas Individuais</span><span class="text-white">${aluno.entregas_individuais || '-'}</span></div>`;
    html += `<div class="flex flex-col gap-1"><span class="text-xs uppercase tracking-wide text-gray-400 font-semibold">Entregas do Grupo</span><span class="text-white">${aluno.entregas_grupo || '-'}</span></div>`;
    content.innerHTML = html;
    openModal('detalhesAlunoModal');
}

function editarAlunoModal(id) {
    const aluno = window.__ALUNOS__.find(a => parseInt(a.id) === parseInt(id));
    if (!aluno) return;
    document.getElementById('editAlunoId').value = aluno.id;
    document.getElementById('editNome').value = aluno.nome || '';
    document.getElementById('editContato').value = aluno.contato === '-' ? '' : (aluno.contato || '');
    document.getElementById('editMedias').value = aluno.medias === '-' ? '' : (aluno.medias || '');
    document.getElementById('editEmail').value = aluno.email === '-' ? '' : (aluno.email || '');
    document.getElementById('editProjetos').value = aluno.projetos === '-' ? '' : (aluno.projetos || '');
    document.getElementById('editLocalidade').value = normalizarLocalidade(aluno.localidade) || 'Maranguape';
    document.getElementById('editPerfilOpc1').value = aluno.perfil_opc1 || 'desenvolvimento';
    document.getElementById('editPerfilOpc2').value = aluno.perfil_opc2 || 'desenvolvimento';
    document.getElementById('editOcorrencia').value = aluno.ocorrencia === '-' ? '' : (aluno.ocorrencia || '');
    document.getElementById('editCusteio').value = String(aluno.custeio ?? '1');
    document.getElementById('editEntregasIndividuais').value = aluno.entregas_individuais === '-' ? '' : (aluno.entregas_individuais || '');
    document.getElementById('editEntregasGrupo').value = aluno.entregas_grupo === '-' ? '' : (aluno.entregas_grupo || '');
    openModal('editarAlunoModal');
}

function confirmarExclusaoAluno(id) {
    alunoIdParaExcluir = id;
    openModal('confirmarExclusaoAlunoModal');
}
function efetivarExclusaoAluno() {
    if (alunoIdParaExcluir) {
        window.location.href = `../controllers/controller_editar_excluir.php?id_aluno=${alunoIdParaExcluir}`;
    }
}

function confirmarRemocaoAluno(idVaga, idAluno, nomeAluno) {
    const nomeEl = document.getElementById('nomeAlunoRemover-' + idVaga);
    const idEl = document.getElementById('idAlunoRemover-' + idVaga);
    if (nomeEl) nomeEl.textContent = nomeAluno;
    if (idEl) idEl.value = idAluno;
    openModal('modalConfirmarRemocaoAluno-' + idVaga);
}

function toggleSelectAllByVaga(vagaId) {
    const master = document.getElementById('selectAll-' + vagaId);
    const boxes = document.querySelectorAll('.aluno-vaga-checkbox-' + vagaId);
    boxes.forEach(box => {
        box.checked = master.checked;
    });
}

function gerarCarta(event, idVaga) {
    event.preventDefault();
    const responsavel = document.getElementById('responsavel-' + idVaga).value;
    window.location.href = `relatorio/gerar_carta.php?id_vaga=${idVaga}&responsavel=${encodeURIComponent(responsavel)}`;
}

// Funções para Relatório de Alunos
function gerarRelatorioAlunos() {
    const empresaId = document.getElementById('filterEmpresaRelAlunos').value || '';
    const perfilId = document.getElementById('filterPerfilRelAlunos').value || '';
    let url = './relatorio/situacao_alunos.php';
    const params = new URLSearchParams();
    if (empresaId) params.append('empresa', empresaId);
    if (perfilId) params.append('perfil', perfilId);
    if (params.toString()) url += '?' + params.toString();
    window.open(url, '_blank');
}

function visualizarPreviewAlunos() {
    const empresaId = document.getElementById('filterEmpresaRelAlunos').value || '';
    const perfilId = document.getElementById('filterPerfilRelAlunos').value || '';
    
    let alunosAprovados = [];
    
    // Extrai dados da tabela de selecionados
    const formElement = document.querySelector('form[method="post"]');
    if (formElement) {
        formElement.querySelectorAll('table tbody tr').forEach(row => {
            // Procura pelo ícone de check (alunos aprovados)
            const checkIcon = row.querySelector('i.fa-check-circle');
            if (checkIcon) {
                const nomeCell = row.cells[1];
                const nomeAluno = nomeCell ? nomeCell.textContent.trim() : '-';
                const cardPrincipal = row.closest('.card');
                const empresaSection = cardPrincipal ? cardPrincipal.querySelector('h3') : null;
                const nomeEmpresa = empresaSection ? empresaSection.textContent.trim() : '-';
                
                alunosAprovados.push({
                    nome: nomeAluno,
                    perfil: '-',
                    origem: '-',
                    empresa: nomeEmpresa
                });
            }
        });
    }
    
    alunosAprovados.sort((a, b) => a.nome.localeCompare(b.nome));
    
    const tbody = document.getElementById('previewAlunosTableBody');
    if (alunosAprovados.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">Nenhum aluno encaminhado. Adicione alunos na seção "Alunos Selecionados" primeiro.</td></tr>';
    } else {
        tbody.innerHTML = alunosAprovados.map(a => `
            <tr class="border-b border-white/5 hover:bg-green-700/8">
                <td class="px-4 py-3">${a.nome}</td>
                <td class="px-4 py-3">${a.perfil}</td>
                <td class="px-4 py-3">${a.origem}</td>
                <td class="px-4 py-3">${a.empresa}</td>
            </tr>
        `).join('');
    }
    document.getElementById('previewRelatorioAlunos').classList.remove('hidden');
}

// Funções para Relatório de TCEs
function gerarRelatoriTces() {
    const empresaId = document.getElementById('filterEmpresaRelTces').value || '';
    const perfilId = document.getElementById('filterPerfilRelTces').value || '';
    let url = './relatorio/situacao_tces.php';
    const params = new URLSearchParams();
    if (empresaId) params.append('empresa', empresaId);
    if (perfilId) params.append('perfil', perfilId);
    if (params.toString()) url += '?' + params.toString();
    window.open(url, '_blank');
}

function visualizarPreviewTces() {
    const empresaId = document.getElementById('filterEmpresaRelTces').value || '';
    const perfilId = document.getElementById('filterPerfilRelTces').value || '';
    
    let alunosTces = [];
    
    // Extrai dados da tabela de selecionados
    const formElement = document.querySelector('form[method="post"]');
    if (formElement) {
        formElement.querySelectorAll('table tbody tr').forEach(row => {
            const checkIcon = row.querySelector('i.fa-check-circle');
            if (checkIcon) {
                const nomeCell = row.cells[1];
                const nomeAluno = nomeCell ? nomeCell.textContent.trim() : '-';
                const cardPrincipal = row.closest('.card');
                const empresaSection = cardPrincipal ? cardPrincipal.querySelector('h3') : null;
                const nomeEmpresa = empresaSection ? empresaSection.textContent.trim() : '-';
                
                alunosTces.push({
                    empresa: nomeEmpresa,
                    perfil: '-',
                    origem: '-',
                    nome: nomeAluno
                });
            }
        });
    }
    
    alunosTces.sort((a, b) => {
        let cmp = a.empresa.localeCompare(b.empresa);
        if (cmp !== 0) return cmp;
        return a.nome.localeCompare(b.nome);
    });
    
    const tbody = document.getElementById('previewTcesTableBody');
    if (alunosTces.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-500">Nenhum aluno encaminhado. Adicione alunos na seção "Alunos Selecionados" primeiro.</td></tr>';
    } else {
        tbody.innerHTML = alunosTces.map(a => `
            <tr class="border-b border-white/5 hover:bg-green-700/8">
                <td class="px-4 py-3">${a.empresa}</td>
                <td class="px-4 py-3">${a.perfil}</td>
                <td class="px-4 py-3">${a.origem}</td>
                <td class="px-4 py-3 font-semibold">${a.nome}</td>
                <td class="px-4 py-3 text-center">
                    <input type="checkbox" class="w-4 h-4 rounded accent-green-600 cursor-pointer">
                </td>
            </tr>
        `).join('');
    }
    document.getElementById('previewRelatoriTces').classList.remove('hidden');
}

// FUNÇÕES DOS MODAIS DE RELATÓRIOS EM GERENCIAR ALUNOS
function gerarRelatorioAlunosCompleto() {
    window.open('./relatorio/situacao_alunos.php', '_blank');
}

function gerarRelatoriTcesCompleto() {
    window.open('./relatorio/situacao_tces.php', '_blank');
}
</script>
</body>
</html>