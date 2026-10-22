// Função para carregar alunos em um select
function carregarAlunos(selectElement) {
    console.log('Iniciando carregamento de alunos...');
    fetch('../control/avisosControl.php?action=listar_alunos')
        .then(response => {
            console.log('Resposta recebida:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            selectElement.innerHTML = '<option value="">Selecione o aluno</option>';
            if (Array.isArray(data)) {
                data.forEach(aluno => {
                    const option = document.createElement('option');
                    option.value = aluno.matricula;
                    option.textContent = `${aluno.matricula} - ${aluno.nome}`;
                    selectElement.appendChild(option);
                });
            } else {
                console.error('Dados recebidos não são um array:', data);
                alert('Erro ao carregar lista de alunos. Formato de dados inválido.');
            }
        })
        .catch(error => {
            console.error('Erro ao carregar alunos:', error);
            alert('Erro ao carregar lista de alunos. Por favor, tente novamente.');
        });
}

// Função para buscar alunos
function buscarAlunos(termo, resultadosDiv, selectElement) {
    console.log('Buscando alunos com termo:', termo);
    if (termo.length < 2) {
        resultadosDiv.classList.add('hidden');
        return;
    }

    fetch(`../control/avisosControl.php?action=buscar_alunos&termo=${encodeURIComponent(termo)}`)
        .then(response => {
            console.log('Resposta da busca recebida:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados da busca recebidos:', data);
            resultadosDiv.innerHTML = '';
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(aluno => {
                    const div = document.createElement('div');
                    div.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                    div.textContent = `${aluno.matricula} - ${aluno.nome}`;
                    div.onclick = () => {
                        selectElement.value = aluno.matricula;
                        resultadosDiv.classList.add('hidden');
                        buscaInput.value = '';
                    };
                    resultadosDiv.appendChild(div);
                });
                resultadosDiv.classList.remove('hidden');
            } else {
                resultadosDiv.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Erro ao buscar alunos:', error);
            alert('Erro ao buscar alunos. Por favor, tente novamente.');
        });
}

// Função para inicializar o campo de busca de alunos
function inicializarBuscaAlunos(buscaInput, resultadosDiv, selectElement) {
    // Event listener para o campo de busca
    buscaInput.addEventListener('input', (e) => {
        buscarAlunos(e.target.value, resultadosDiv, selectElement);
    });

    // Fechar resultados ao clicar fora
    document.addEventListener('click', (e) => {
        if (!buscaInput.contains(e.target) && !resultadosDiv.contains(e.target)) {
            resultadosDiv.classList.add('hidden');
        }
    });
} 