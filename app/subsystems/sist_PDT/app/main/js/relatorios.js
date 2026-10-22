// Função para gerar relatório
function gerarRelatorio(tipo) {
    // Abre a página de geração de relatório em uma nova aba/janela com o tipo especificado
    window.open(`gerar_relatorio.php?tipo=${tipo}`, '_blank');
}

// Função para fechar o modal de relatório
function fecharModalRelatorio() {
    const modal = document.getElementById('relatorioModal');
    if (modal) {
        modal.remove();
    }
}

// Função para adicionar botão de relatório
function adicionarBotaoRelatorio(tipo) {
    console.log('Adicionando botão para:', tipo);
    const container = document.querySelector('.container');
    if (!container) {
        console.error('Container não encontrado');
        return;
    }

    const botao = document.createElement('button');
    botao.type = 'button';
    botao.className = 'flex items-center px-4 py-2 bg-[#007A33] text-white rounded-md hover:bg-[#007A33]/90 transition-colors duration-200 shadow-md';
    botao.innerHTML = `
        <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
            <polyline points="14 2 14 8 20 8"></polyline>
            <line x1="16" y1="13" x2="8" y2="13"></line>
            <line x1="16" y1="17" x2="8" y2="17"></line>
        </svg>
        Gerar Relatório
    `;
    botao.onclick = () => {
        console.log('Botão clicado para:', tipo);
        gerarRelatorio(tipo);
    };

    container.appendChild(botao);
    console.log('Botão adicionado com sucesso');
}

// Adicionar botões de relatório nas páginas específicas
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM carregado, verificando página atual');
    const path = window.location.pathname;
    console.log('Caminho atual:', path);
    
    if (path.includes('avisos.php')) {
        console.log('Página de avisos detectada');
        adicionarBotaoRelatorio('avisos');
    } else if (path.includes('ocorrencias.php')) {
        console.log('Página de ocorrências detectada');
        adicionarBotaoRelatorio('ocorrencias');
    } else if (path.includes('mapeamento.php')) {
        console.log('Página de mapeamento detectada');
        adicionarBotaoRelatorio('mapeamento');
    } else if (path.includes('lideranca.php')) {
        console.log('Página de liderança detectada');
        adicionarBotaoRelatorio('lideranca');
        adicionarBotaoRelatorio('vice_lideranca');
        adicionarBotaoRelatorio('secretaria');
    }
}); 