<?php

/**
 * Recupera o caminho de URL base para a pasta de visualizações (/views).
 * Varre o caminho atual do script de execução para encontrar onde a pasta 
 * de views começa e reconstrói o prefixo dinamicamente.
 * * @return string Caminho relativo até a pasta de views (ex: /projeto/views)
 */
function getViewBaseUrl(): string
{
    // Padroniza as barras invertidas (comuns no Windows) para barras normais
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    
    // Procura a posição da pasta '/views' no caminho do arquivo atual
    $pos = strpos($script, '/views');
    if ($pos !== false) {
        // Corta e retorna a string desde o início até a palavra '/views' inclusive
        return substr($script, 0, $pos + 6);
    }
    
    // Fallback caso não ache a pasta views: retorna o diretório do script atual
    return dirname($script);
}

/**
 * Constrói uma URL absoluta interna para arquivos localizados na pasta de views.
 * Evita o uso manual de caminhos relativos complexos como "../../views/arquivo.php".
 * * @param string $path Caminho interno a partir da pasta views (ex: 'registros/entrada.php')
 * @return string URL amigável e higienizada completa para o link
 */
function viewUrl(string $path): string
{
    // Remove barras finais da base para evitar duplicidade de barras na concatenação
    $base = rtrim(getViewBaseUrl(), '/');
    
    // Remove barras iniciais do caminho informado e concatena tudo limpamente
    return $base . '/' . ltrim($path, '/');
}

/**
 * Recupera o caminho de URL base da raiz da aplicação inteira (antes da pasta views).
 * Útil para alcançar pastas irmãs da views, como a pasta de controllers.
 * * @return string Raiz da aplicação no servidor (ex: /projeto)
 */
function getApplicationBaseUrl(): string
{
    // Padroniza separadores de diretório para o formato web (/)
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    
    // Encontra onde a pasta '/views' inicia
    $pos = strpos($script, '/views');
    if ($pos !== false) {
        // Retorna tudo o que vem ANTES da pasta '/views' (raiz do projeto)
        return substr($script, 0, $pos);
    }
    
    // Fallback caso não seja executado dentro de views
    return dirname($script);
}

/**
 * Gera o link completo direcionando para uma ação específica dentro do controlador de usuários.
 * Codifica o parâmetro da ação para garantir o envio correto via protocolo HTTP GET.
 * * @param string $action Nome da operação a ser executada no switch/case do controller
 * @return string URL montada com a query string pronta (ex: /projeto/controllers/...php?action=logout)
 */
function controllerUrl(string $action): string
{
    // Limpa a raiz da aplicação e anexa a rota estática para o controller_usuario.php
    return rtrim(getApplicationBaseUrl(), '/') . '/controllers/controller_usuario.php?action=' . urlencode($action);
}

/**
 * Renderiza o cabeçalho padrão HTML5 (Tag <head>).
 * Inclui as dependências externas do projeto (TailwindCSS, FontAwesome), configurações
 * customizadas do tema institucional do Ceará e folhas de estilo CSS personalizadas.
 * * @param string $title Título dinâmico da aba do navegador
 * @param string $additionalHead Código HTML/CSS ou JS extra que precise ser injetado sob demanda
 */
function renderHead(string $title = 'Sistema Escolar', string $additionalHead = ''): void
{
    echo '<!DOCTYPE html>';
    echo '<html lang="pt-BR">';
    echo '<head>';
    echo '    <meta charset="UTF-8">';
    echo '    <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '    <meta http-equiv="X-UA-Compatible" content="IE=edge">';
    // Protege contra possíveis vulnerabilidades de XSS ao renderizar o título da página
    echo '    <title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title>';
    
    // Importação de frameworks utilitários visuais (Tailwind e biblioteca de ícones)
    echo '    <script src="https://cdn.tailwindcss.com"></script>';
    echo '    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">';
    
    // Configurações personalizadas de extensão de paleta de cores e tipografia do Tailwind
    echo '    <script>';
    echo '        tailwind.config = {';
    echo '            theme: {';
    echo '                extend: {';
    echo '                    colors: {';
    echo '                        ceara: { green: "#008C45", light: "#3CB371", olive: "#8CA03E", orange: "#FFA500" },';
    echo '                        slate: { 950: "#020617" }';
    echo '                    },';
    echo '                    fontFamily: { inter: ["Inter", "sans-serif"] }';
    echo '                }';
    echo '            }';
    echo '        };';
    echo '    </script>';
    
    // Estilos customizados fixos para fontes e elementos persistentes da interface (como a Sidebar)
    echo '    <style>';
    echo '        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");';
    echo '        * { font-family: "Inter", sans-serif; }';
    echo '        html { min-height: 100%; }';
    echo '        body { background: #f8fafc; min-height: 100vh; }';
    echo '        .sidebar { background: linear-gradient(180deg, #0f4d2f 0%, #075c2c 100%); }';
    echo '        .sidebar-link { transition: all 0.2s ease; }';
    echo '        .sidebar-link:hover { background: rgba(255,255,255,0.08); transform: translateX(3px); }';
    echo '        .sidebar-link.active { background: rgba(255,255,255,0.15); color: #ffffff; font-weight: 600; }';
    echo '        .sidebar { transform: translateX(-100%); transition: transform 0.25s ease-in-out; width: min(18rem, 100%); }';
    echo '        body.sidebar-open { overflow: hidden; }';
    echo '        body.sidebar-open { overflow: hidden; }';
    echo '        body.sidebar-open .sidebar { transform: translateX(0); display: flex !important; visibility: visible !important; }';
    echo '        .mobile-sidebar-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.45); opacity: 0; visibility: hidden; transition: opacity 0.25s ease-in-out, visibility 0.25s ease-in-out; }';
    echo '        body.sidebar-open .mobile-sidebar-overlay { opacity: 1; visibility: visible; }';
    echo '        .sidebar { min-height: 100vh; }';
    echo '        .mobile-sidebar-header { display: flex; }';
    echo '        @media (max-width: 1023px) {';
    echo '            body { padding-top: 4rem; }';
    echo '        }';
    echo '        @media (min-width: 1024px) {';
    echo '            .sidebar { transform: none !important; visibility: visible !important; }';
    echo '            .mobile-sidebar-overlay { display: none !important; }';
    echo '            .mobile-sidebar-header { display: none !important; }';
    echo '        }';
    echo '    </style>';
    
    echo '    <script>';
    echo '        function toggleSidebar(open) {';
    echo '            if (open) {';
    echo '                document.body.classList.add("sidebar-open");';
    echo '            } else {';
    echo '                document.body.classList.remove("sidebar-open");';
    echo '            }';
    echo '        }';
    echo '        document.addEventListener("DOMContentLoaded", function () {';
    echo '            var toggleButton = document.getElementById("sidebarToggle");';
    echo '            if (toggleButton) {';
    echo '                toggleButton.addEventListener("click", function () {';
    echo '                    document.body.classList.toggle("sidebar-open");';
    echo '                });';
    echo '            }';
    echo '        });';
    echo '    </script>';
    
    // Injeção de scripts/estilos específicos passados por parâmetro no escopo da view
    echo $additionalHead;
    echo '</head>';
}

/**
 * Renderiza o menu lateral de navegação fixa (Sidebar).
 * Mapeia os links disponíveis e adiciona estados visuais dinâmicos ("active") de acordo
 * com a página que está sendo acessada no momento.
 * * @param string $active O ID do item que deve ser realçado como ativo (ex: 'dashboard', 'entrada')
 */
function renderSidebar(string $active = ''): void
{
    // Array associativo contendo o mapa de navegação principal do sistema
    $menu = [
        ['title' => 'Dashboard', 'icon' => 'fas fa-home', 'link' => viewUrl('dashboard.php'), 'id' => 'dashboard'],
        ['title' => 'Registrar Entrada', 'icon' => 'fas fa-sign-in-alt', 'link' => viewUrl('registros/registro_entrada.php'), 'id' => 'entrada'],
        ['title' => 'Registrar Saída', 'icon' => 'fas fa-sign-out-alt', 'link' => viewUrl('registros/registro_saida.php'), 'id' => 'saida'],
        ['title' => 'Saída Estágio', 'icon' => 'fas fa-briefcase', 'link' => viewUrl('registros/registro_saida_estagio.php'), 'id' => 'estagio'],
        ['title' => 'Pré-Estágio', 'icon' => 'fas fa-clock', 'link' => viewUrl('registros/registro_pre_estagio.php'), 'id' => 'pre_estagio'],
        ['title' => 'Relatórios', 'icon' => 'fas fa-chart-bar', 'link' => viewUrl('relatorio.php'), 'id' => 'relatorios'],
        ['title' => 'Últimas Saídas', 'icon' => 'fas fa-history', 'link' => viewUrl('relatorios/ultimas_saidas.php'), 'id' => 'ultimas'],
        ['title' => 'QRCodes', 'icon' => 'fas fa-qrcode', 'link' => viewUrl('QRCode/decisao.php'), 'id' => 'qrcode'],
    ];
    ?>
    <div class="mobile-sidebar-header fixed top-0 left-0 right-0 z-50 flex items-center justify-between bg-white/95 border-b border-gray-200 px-4 py-3 lg:hidden">
        <button id="sidebarToggle" type="button" class="inline-flex items-center justify-center rounded-2xl border border-gray-200 bg-white p-2 text-slate-700 shadow-sm">
            <i class="fas fa-bars"></i>
        </button>
        <span class="text-sm font-semibold text-slate-900">Menu</span>
        <a href="<?= viewUrl('dashboard.php') ?>" class="text-sm font-bold text-slate-700">Salaberga</a>
    </div>

    <div class="mobile-sidebar-overlay" onclick="toggleSidebar(false)"></div>

    <aside class="sidebar fixed inset-y-0 left-0 z-50 w-72 text-white lg:flex flex-col justify-between p-6 shadow-xl">
        
        <div class="space-y-6 w-full overflow-y-auto pr-1">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="rounded-2xl bg-white/10 p-2.5 text-white">
                    <i class="fas fa-school text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.25em] text-white/60 font-semibold">Bem-vindo ao</p>
                    <h2 class="text-xl font-bold text-white">Painel Salaberga</h2>
                </div>
            </div>

            <hr class="border-white/10">

            <nav class="space-y-1.5">
                <?php foreach ($menu as $item): 
                    // Verifica se o item atual do loop corresponde à página ativa solicitada
                    $isActive = ($item['id'] === $active);
                    $activeClass = $isActive ? 'active' : '';
                ?>
                    <a href="<?= $item['link'] ?>" class="sidebar-link group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-white/90 <?= $activeClass ?>">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl <?= $isActive ? 'bg-white/20 text-white' : 'bg-white/5 text-white/70 group-hover:bg-white/10 group-hover:text-white' ?> transition-colors">
                            <i class="<?= $item['icon'] ?> text-xs"></i>
                        </span>
                        <span><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <div class="w-full pt-4 border-t border-white/10">
            <a href="<?= controllerUrl('logout') ?>" 
               class="flex items-center gap-3 px-4 py-3 text-orange-400 hover:text-orange-300 hover:bg-white/5 rounded-2xl transition-all duration-200 font-medium w-full">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-orange-500/10 text-orange-400">
                    <i class="fas fa-sign-out-alt text-xs"></i>
                </span>
                <span>Sair da conta</span>
            </a>
        </div>

    </aside>
    <?php
}

/**
 * Renderiza o rodapé padrão unificado para todas as telas do sistema.
 * Exibe as informações de direitos autorais com o ano atual calculado em tempo real.
 */
function renderFooter(): void
{
    echo '<footer class="mt-12 bg-white border-t border-gray-200 py-6 text-center text-sm text-gray-500">';
    echo '    <p>© ' . date('Y') . ' Sistema Escolar Salaberga. Todos os direitos reservados.</p>';
    echo '</footer>';
}

/**
 * Facilita a abertura estrutural de novas páginas.
 * Executa as chamadas em sequência do cabeçalho HTML (<head>), da Sidebar e abre as 
 * tags de blocos necessárias do grid de layout interno (compensando o espaço lateral da sidebar).
 * * @param string $active ID do menu que deve ficar ativo
 * @param string $title Título da aba do navegador
 * @param string $userName Nome do usuário conectado (Pronto para implementações futuras)
 */
function renderPageFrame(string $active = '', string $title = 'Sistema Escolar', string $userName = ''): void
{
    renderHead($title);
    echo '<body class="min-h-screen bg-slate-50 text-slate-900">';
    renderSidebar($active);
    
    // Div de compensação: Afasta todo o conteúdo principal da tela para a direita para não ficar sob a Sidebar fixa
    echo '<div class="lg:pl-72">';
    echo '<main class="min-h-screen pt-16 lg:pt-8 px-4 sm:px-6 lg:px-8">';
}

/**
 * Conclui e fecha a estrutura da página aberta pela função renderPageFrame.
 * Fecha as tags principais pendentes (`</main>`, `</div>`), renderiza o Footer e fecha o escopo do arquivo HTML.
 */
function closePageFrame(): void
{
    echo '</main>';
    echo '</div>'; // Fecha o container de espaçamento .lg:pl-72
    renderFooter();
    echo '</body>';
    echo '</html>';
}