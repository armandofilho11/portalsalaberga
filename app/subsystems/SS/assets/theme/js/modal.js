// ===== FUNÇÕES DE MODAL ADMINISTRATIVO =====

function showReportsModal() {
    Swal.fire({
      title: '<h2 class="text-2xl font-bold text-gray-800 mb-4">Relatórios</h2>',
      html: `
          <div class="bg-white rounded-lg p-6">
              <form action="../controllers/controller_relatorio.php" id="searchForm" method="post">
                  <div class="space-y-6">
                      <div class="relative">
                          <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Relatório</label>
                          <select required id="type" name="tipo" onchange="toggleCourseSelect()" class="form-select block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200">
                              <option value="">Selecione um tipo</option>
                              <option value="1">Publica Geral</option>
                              <option value="2">Publica Ampla Concorrência</option>
                              <option value="3">Publica Cotas</option>
                              <option value="4">Privada Geral</option>
                              <option value="5">Privada Ampla Concorrência</option>
                              <option value="6">Privada Cotas</option>
                              <option value="7">Usuario</option>
                          </select>
                      </div>
                      
                      <div class="relative">
                          <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Curso</label>
                          <select required id="course" name="curso" class="form-select block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200">
                              <option value="">Selecione um curso</option>
                              <option value="1">Enfermagem</option>
                              <option value="2">Informática</option>
                              <option value="3">Administração</option>
                              <option value="4">Edificações</option>
                          </select>
                      </div>
  
                      <div class="flex justify-center space-x-4 mt-8">
                          <button type="button" class="px-6 py-2.5 bg-gray-400 text-white rounded-lg font-medium hover:bg-gray-500 transition-all duration-200 focus:ring-2 focus:ring-gray-300" onclick="Swal.close()">
                              Cancelar
                          </button>
                          <button type="submit" class="px-6 py-2.5 bg-ceara-green text-white rounded-lg font-medium hover:bg-green-600 transition-all duration-200 focus:ring-2 focus:ring-ceara-green">
                              Gerar Resultados
                          </button>
                      </div>
                  </div>
              </form>
          </div>
          `,
      showConfirmButton: false,
      showCancelButton: false,
      background: "#ffffff",
      customClass: {
        popup: "rounded-xl shadow-xl border-0",
      },
      didOpen: () => {
        const script = document.createElement("script")
        script.textContent = `
                  function toggleCourseSelect() {
                      const typeSelect = document.getElementById('type');
                      const courseSelect = document.getElementById('course');
                      if (typeSelect.value === '7') {
                          courseSelect.disabled = true;
                          courseSelect.required = false;
                          courseSelect.value = '';
                      } else {
                          courseSelect.disabled = false;
                          courseSelect.required = true;
                      }
                  }
                  toggleCourseSelect();
              `
        document.body.appendChild(script)
      },
    })
  }
  
  function showatualizModal() {
    Swal.fire({
      title: "",
      html: `
          <div class="max-w-md mx-auto">
              <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                  <div class="bg-gradient-to-r from-ceara-green to-green-500 px-6 py-4">
                      <h2 class="text-2xl font-bold text-white">Atualizar Notas</h2>
                  </div>
                  <div class="p-6">
                      <form action="../controllers/atualizar.php" method="post" id="searchForm" class="space-y-6">
                          <div class="space-y-4">
                              <div class="flex flex-col">
                                  <label for="searchId" class="text-sm font-medium text-gray-700 mb-2">
                                      Digite o ID do Relatório
                                  </label>
                                  <input type="text" id="searchId" name="id"
                                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-ceara-green focus:border-transparent transition-all duration-200 ease-in-out"
                                         placeholder="Digite o ID" required>
                              </div>
                          </div>
                          <div class="mt-6 flex justify-end space-x-3">
                              <button type="button" onclick="Swal.close()" 
                                      class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transform hover:scale-105 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                  Cancelar
                              </button>
                              <button type="submit"   
                                  class="px-6 py-2 bg-ceara-green text-white rounded-lg hover:bg-green-600 transform hover:scale-105 transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-ceara-orange focus:ring-offset-2">  
                                  Buscar  
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
          `,
      showConfirmButton: false,
      showCancelButton: false,
      customClass: {
        popup: "rounded-xl shadow-2xl border-0",
        container: "backdrop-filter backdrop-blur-sm",
      },
      background: "#fff",
      width: "auto",
      padding: 0,
    })
  }
  
  function openInsertUserModal() {
    Swal.fire({
      title: '<h2 class="text-2xl font-bold text-gray-800 mb-4">Inserir Usuário</h2>',
      html: `
          <div class="bg-white rounded-lg p-6">
              <form id="insertUserForm" action="../controllers/controller_cadastrar.php" method="post">
                  <div class="space-y-6">
                      <div class="grid grid-cols-2 gap-6">
                          <div class="relative">
                              <label class="block text-sm font-medium text-gray-700 mb-2" for="fullName">Nome Completo</label>
                              <input type="text" id="fullName" name="nomeC"
                                  class="form-input block w-full max-w-lg px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200" required>
                          </div>
                          
                          <div class="relative">
                              <label class="block text-sm font-medium text-gray-700 mb-2" for="email">E-mail Institucional</label>
                              <input type="email" id="email" name="email"
                                  class="form-input block w-full max-w-lg px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200" required>
                          </div>
                          
                          <div class="relative">
                              <label class="block text-sm font-medium text-gray-700 mb-2" for="password">Senha</label>
                              <input type="password" id="password" name="senha"
                                  class="form-input block w-full max-w-lg px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200" required>
                          </div>
                          
                          <div class="relative">
                              <label class="block text-sm font-medium text-gray-700 mb-2" for="status">Status</label>
                              <select id="status" name="status"
                                  class="form-select block w-full max-w-lg px-4 py-2.5 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200">
                                  <option value="">Selecione um status</option>
                                  <option value="1">Admin</option>
                                  <option value="0">Não Admin</option>
                              </select>
                          </div>
                      </div>
                      
                      <div class="flex justify-center space-x-4 mt-8">
                          <button type="button" class="px-6 py-2.5 bg-gray-400 text-white rounded-lg font-medium hover:bg-gray-500 transition-all duration-200 focus:ring-2 focus:ring-gray-300" onclick="Swal.close()">
                              Cancelar
                          </button>
                          <button type="submit" class="px-6 py-2.5 bg-ceara-green text-white rounded-lg font-medium hover:bg-green-600 transition-all duration-200 focus:ring-2 focus:ring-ceara-green">
                              Inserir
                          </button>
                      </div>
                  </div>
              </form>
          </div>
          `,
      showConfirmButton: false,
      showCancelButton: false,
      background: "#ffffff",
      customClass: {
        popup: "rounded-xl shadow-xl border-0",
      },
    })
  }
  
  function showResultsModal() {
    Swal.fire({
      title: '<h2 class="text-2xl font-bold text-gray-800 mb-4">Resultados</h2>',
      html: `
          <div class="bg-white rounded-lg p-6">
              <form action="../controllers/controller_resultados.php" id="searchForm" method="post">
                  <div class="space-y-6">
                      <div class="relative">
                          <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Resultado</label>
                          <select required id="type" name="tipo" 
                              class="form-select block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200"
                              onchange="toggleCourseSelect()">
                              <option value="">Selecione um tipo</option>
                              <option value="1">Classificados</option>
                              <option value="2">Lista de espera</option>
                              <option value="4">Resultado Preliminar</option>
                              <option value="3">Resultado Final</option>
                          </select>
                      </div>
  
                      <div class="relative">
                          <label for="course" class="block text-sm font-medium text-gray-700 mb-2">Curso</label>
                          <select required id="course" name="curso" 
                              class="form-select block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200">
                              <option value="">Selecione um curso</option>
                              <option value="1">Enfermagem</option>
                              <option value="2">Informática</option>
                              <option value="3">Administração</option>
                              <option value="4">Edificações</option>
                          </select>
                      </div>
  
                      <div class="flex justify-center space-x-4 mt-8">
                          <button type="button" class="px-6 py-2.5 bg-gray-400 text-white rounded-lg font-medium hover:bg-gray-500 transition-all duration-200 focus:ring-2 focus:ring-gray-300" onclick="Swal.close()">
                              Cancelar
                          </button>
                          <button type="submit" onclick="submitForm()" class="px-6 py-2.5 bg-ceara-green text-white rounded-lg font-medium hover:bg-green-600 transition-all duration-200 focus:ring-2 focus:ring-ceara-green">
                              Gerar Resultados
                          </button>
                      </div>
                  </div>
              </form>
          </div>
          `,
      showConfirmButton: false,
      showCancelButton: false,
      background: "#ffffff",
      customClass: {
        popup: "rounded-xl shadow-xl border-0",
      },
    })
  }
  
  function showDeleteConfirmationModal() {
    Swal.fire({
      title: '<h2 class="text-2xl font-bold text-gray-800 mb-4">Confirmação de Exclusão</h2>',
      html: `
          <div class="bg-white rounded-lg p-6">
              <form id="deleteForm" action="../controllers/controller_delete.php" method="post">
                  <p class="text-gray-700 mb-4">Você tem certeza que quer apagar o banco?</p>
                  <div class="relative mt-4">
                      <input type="password" id="password" name="senha" required 
                          class="form-input block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200" 
                          placeholder="Digite sua senha">
                  </div>
                  <div class="flex justify-center space-x-4 mt-8">
                      <button type="button" class="px-6 py-2.5 bg-gray-400 text-white rounded-lg font-medium hover:bg-gray-500 transition-all duration-200" onclick="Swal.close()">
                          Cancelar
                      </button>
                      <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all duration-200 focus:ring-2 focus:ring-red-500">
                          Apagar Banco
                      </button>
                  </div>
              </form>
          </div>
          `,
      showConfirmButton: false,
      showCancelButton: false,
      background: "#ffffff",
      customClass: {
        popup: "rounded-xl shadow-xl border-0",
      },
    })
  }
  
  function showExcluirCandidatoModal() {
    Swal.fire({
      title: '<h2 class="text-2xl font-bold text-gray-800 mb-4">Excluir Candidato</h2>',
      html: `
          <div class="bg-white rounded-lg p-6">
              <form action="../controllers/controller_excluir/excluir_candidato.php" method="post" id="courseForm">
                  <div class="mb-4">
                      <input type="text" id="courseName" name="id_candidato" required 
                          class="form-input block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-ceara-green focus:border-ceara-green transition-all duration-200" 
                          placeholder="ID do candidato">
                  </div>
                  <div class="flex justify-center space-x-4 mt-8">
                      <button type="button" class="px-6 py-2.5 bg-gray-400 text-white rounded-lg font-medium hover:bg-gray-500 transition-all duration-200" onclick="Swal.close()">
                          Cancelar
                      </button>
                      <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-all duration-200 focus:ring-2 focus:ring-red-500">
                          Excluir
                      </button>
                  </div>
              </form>
          </div>
          `,
      showConfirmButton: false,
      showCancelButton: false,
      background: "#ffffff",
      customClass: {
        popup: "rounded-xl shadow-xl border-0",
      },
    })
  }
  
  // ===== FUNÇÕES AUXILIARES =====
  
  function toggleCourseSelect() {
    const typeSelect = document.getElementById("type")
    const courseSelect = document.getElementById("course")
  
    if (typeSelect && courseSelect) {
      if (typeSelect.value === "3" || typeSelect.value === "4") {
        courseSelect.disabled = true
        courseSelect.classList.add("bg-gray-200", "cursor-not-allowed")
      } else {
        courseSelect.disabled = false
        courseSelect.classList.remove("bg-gray-200", "cursor-not-allowed")
      }
    }
  }
  
  function submitForm() {
    const courseSelect = document.getElementById("course")
    const typeSelect = document.getElementById("type")
    const form = document.getElementById("searchForm")
  
    if (courseSelect && typeSelect && form) {
      if (courseSelect.value === "" || typeSelect.value === "") {
        Swal.fire({
          icon: "error",
          title: "Erro",
          text: "Por favor, selecione um curso e um tipo",
          customClass: {
            popup: "rounded-xl shadow-xl",
          },
        })
        return
      }
  
      Swal.fire({
        title: "Gerando Relatórios...",
        text: "Por favor, aguarde.",
        allowOutsideClick: false,
        customClass: {
          popup: "rounded-xl shadow-xl",
        },
        didOpen: () => {
          Swal.showLoading()
        },
      })
  
      setTimeout(() => {
        form.submit()
      }, 1500)
    }
  }
  
  // ===== FUNÇÕES DE FORMULÁRIOS DE CURSOS =====
  
  function enfermagemPub() {
    showModal("Enfermagem", "Escola Pública")
  }
  
  function enfermagemPriv() {
    showModal("Enfermagem", "Escola Privada")
  }
  
  function informaticaPub() {
    showModal("Informática", "Escola Pública")
  }
  
  function informaticaPriv() {
    showModal("Informática", "Escola Privada")
  }
  
  function administracaoPub() {
    showModal("Administração", "Escola Pública")
  }
  
  function administracaoPriv() {
    showModal("Administração", "Escola Privada")
  }
  
  function edificacoesPub() {
    showModal("Edificações", "Escola Pública")
  }
  
  function edificacoesPriv() {
    showModal("Edificações", "Escola Privada")
  }
  
  function showModal(courseName, schoolType) {
    Swal.fire({
      width: "80%",
      showConfirmButton: false,
      showCancelButton: false,
      background: "#ffffff",
      customClass: {
        popup: "rounded-xl shadow-xl border-0",
      },
      html: createModalContent(courseName, schoolType),
    })
  }
  
  function createModalContent(courseName, schoolType) {
    switch (courseName) {
      case "Enfermagem":
        return createEnfermagemForm(schoolType)
      case "Informática":
        return createInformaticaForm(schoolType)
      case "Administração":
        return createAdministracaoForm(schoolType)
      case "Edificações":
        return createEdificacoesForm(schoolType)
      default:
        return ""
    }
  }
  
  // ===== FORMULÁRIO DE ENFERMAGEM =====
  
  function createEnfermagemForm(schoolType) {
    return `
      <div class="bg-white rounded-xl shadow-md">
          <form id="EnfermagemForm" action="../controllers/controller.php" method="POST">
              <div class="bg-red-600 p-4 rounded-t-xl">
                  <h2 class="text-2xl font-bold text-white text-center">Formulário de Enfermagem</h2>
              </div>
  
              <div class="p-6 space-y-6">
                  <!-- Informações Pessoais -->
                  <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                      <div class="md:col-span-6">
                          <input type="text" name="nome" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500" oninput="removeAccents(this)" placeholder="Nome Completo" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" name="nasc" maxlength="10" placeholder="DD/MM/AAAA" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500" oninput="maskNascimento(this)" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="Enfermagem" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="curso" value="Enfermagem">
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="${schoolType}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="publica" value="${schoolType}">
                      </div>
                      <div class="md:col-span-2">
                          <select name="bairro" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                              <option value="">Selecione um bairro</option>
                              <option value="Cota">Cota</option>
                              <option value="Outros Bairros">Outros Bairros</option>
                          </select>
                      </div>
                      <div class="md:col-span-1 flex items-center">
                          <label for="pcd" class="text-sm text-gray-600 mr-2">PCD</label>
                          <input type="checkbox" id="pcd" name="pcd" value="1" class="w-4 h-4 text-red-600 border border-gray-300 rounded">
                      </div>
                  </div>
  
                  <!-- Notas dos Anos -->
                  <div class="space-y-4">
                      <!-- 6º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-red-600 mb-3 border-b pb-2">6º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp6" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a6" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m6" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h6" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g6" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c6" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i6" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef6" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r6" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 7º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-red-600 mb-3 border-b pb-2">7º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp7" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a7" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m7" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h7" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g7" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c7" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i7" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef7" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r7" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 8º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-red-600 mb-3 border-b pb-2">8º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp8" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a8" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m8" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h8" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g8" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c8" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i8" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef8" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r8" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-red-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
                  </div>
  
                  <!-- Botões -->
                  <div class="flex justify-center space-x-4 pt-4 border-t">
                      <button type="button" onclick="closeModalAndRedirect('EnfermagemForm', 'inicio.php');" 
                          class="px-6 py-2.5 border-2 border-red-600 rounded-md text-red-600 hover:bg-red-50 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                          Cancelar
                      </button>
                      <button type="button" onclick="handleAvancar()" 
                          class="px-6 py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                          </svg>
                          Avançar
                      </button>
                  </div>
              </div>
  
              <!-- Modal para 9º Ano -->
              <div id="bimestreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl w-[90%] h-auto" style="transform: scale(0.95);">
                      <div class="bg-red-600 p-4 rounded-t-xl">
                          <h2 class="text-2xl font-bold text-white text-center">Notas 9º ano</h2>
                      </div>
                      <div class="p-4 space-y-3">
                          <!-- 1º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-red-600 mb-3 border-b pb-2">1º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_1" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_1" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_1" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_1" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_1" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_1" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_1" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_1" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_1" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 2º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-red-600 mb-3 border-b pb-2">2º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_2" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_2" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_2" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_2" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_2" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_2" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_2" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_2" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_2" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 3º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-red-600 mb-3 border-b pb-2">3º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_3" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_3" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_3" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_3" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_3" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_3" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_3" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_3" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_3" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Média Geral -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-red-600 mb-3 border-b pb-2">Média Geral</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_4" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="a9_4" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_4" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="h9_4" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="g9_4" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="c9_4" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="i9_4" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="ef9_4" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_4" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-red-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Botões do Modal 9º Ano -->
                          <div class="flex justify-center space-x-4 pt-4 border-t">
                              <button type="button" onclick="hideBimestreModal()" 
                                  class="px-6 py-2.5 border-2 border-red-600 rounded-md text-red-600 hover:bg-red-50 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                  </svg>
                                  Voltar
                              </button>
                              <button type="submit" 
                                  class="px-6 py-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                  </svg>
                                  Cadastrar
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
      </div>
      `
  }
  
  // ===== FORMULÁRIO DE INFORMÁTICA =====
  
  function createInformaticaForm(schoolType) {
    return `
      <div class="bg-white rounded-xl shadow-md">
          <form id="InformaticaForm" action="../controllers/controller.php" method="POST">
              <div class="bg-blue-600 p-4 rounded-t-xl">
                  <h2 class="text-2xl font-bold text-white text-center">Formulário de Informática</h2>
              </div>
  
              <div class="p-6 space-y-6">
                  <!-- Informações Pessoais -->
                  <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                      <div class="md:col-span-6">
                          <input type="text" name="nome" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="removeAccents(this)" placeholder="Nome Completo" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" name="nasc" maxlength="10" placeholder="DD/MM/AAAA" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="maskNascimento(this)" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="Informática" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="curso" value="Informática">
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="${schoolType}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="publica" value="${schoolType}">
                      </div>
                      <div class="md:col-span-2">
                          <select name="bairro" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                              <option value="">Selecione um bairro</option>
                              <option value="Cota">Cota</option>
                              <option value="Outros Bairros">Outros Bairros</option>
                          </select>
                      </div>
                      <div class="md:col-span-1 flex items-center">
                          <label for="pcd" class="text-sm text-gray-600 mr-2">PCD</label>
                          <input type="checkbox" id="pcd" name="pcd" value="1" class="w-4 h-4 text-blue-600 border border-gray-300 rounded">
                      </div>
                  </div>
  
                  <!-- Notas dos Anos -->
                  <div class="space-y-4">
                      <!-- 6º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-blue-600 mb-3 border-b pb-2">6º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp6" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a6" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m6" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h6" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g6" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c6" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i6" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef6" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r6" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 7º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-blue-600 mb-3 border-b pb-2">7º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp7" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a7" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m7" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h7" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g7" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c7" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i7" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef7" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r7" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 8º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-blue-600 mb-3 border-b pb-2">8º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp8" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a8" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m8" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h8" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g8" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c8" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i8" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef8" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r8" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-blue-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
                  </div>
  
                  <!-- Botões -->
                  <div class="flex justify-center space-x-4 pt-4 border-t">
                      <button type="button" onclick="closeModalAndRedirect('InformaticaForm', 'inicio.php');" 
                          class="px-6 py-2.5 border-2 border-blue-600 rounded-md text-blue-600 hover:bg-blue-50 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                          Cancelar
                      </button>
                      <button type="button" onclick="handleAvancar()" 
                          class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                          </svg>
                          Avançar
                      </button>
                  </div>
              </div>
  
              <!-- Modal para 9º Ano -->
              <div id="bimestreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl w-[90%] h-auto" style="transform: scale(0.95);">
                      <div class="bg-blue-600 p-4 rounded-t-xl">
                          <h2 class="text-2xl font-bold text-white text-center">Notas 9º ano</h2>
                      </div>
                      <div class="p-4 space-y-3">
                          <!-- 1º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-blue-600 mb-3 border-b pb-2">1º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_1" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_1" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_1" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_1" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_1" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_1" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_1" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_1" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_1" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 2º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-blue-600 mb-3 border-b pb-2">2º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_2" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_2" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_2" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_2" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_2" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_2" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_2" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_2" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_2" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 3º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-blue-600 mb-3 border-b pb-2">3º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_3" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_3" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_3" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_3" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_3" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_3" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_3" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_3" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_3" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Média Geral -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-blue-600 mb-3 border-b pb-2">Média Geral</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_4" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="a9_4" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_4" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="h9_4" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="g9_4" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="c9_4" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="i9_4" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="ef9_4" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_4" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-blue-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Botões do Modal 9º Ano -->
                          <div class="flex justify-center space-x-4 pt-4 border-t">
                              <button type="button" onclick="hideBimestreModal()" 
                                  class="px-6 py-2.5 border-2 border-blue-600 rounded-md text-blue-600 hover:bg-blue-50 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                  </svg>
                                  Voltar
                              </button>
                              <button type="submit" 
                                  class="px-6 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                  </svg>
                                  Cadastrar
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
      </div>
      `
  }
  
  // ===== FORMULÁRIO DE ADMINISTRAÇÃO =====
  
  function createAdministracaoForm(schoolType) {
    return `
      <div class="bg-white rounded-xl shadow-md">
          <form id="AdministracaoForm" action="../controllers/controller.php" method="POST">
              <div class="bg-green-600 p-4 rounded-t-xl">
                  <h2 class="text-2xl font-bold text-white text-center">Formulário de Administração</h2>
              </div>
  
              <div class="p-6 space-y-6">
                  <!-- Informações Pessoais -->
                  <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                      <div class="md:col-span-6">
                          <input type="text" name="nome" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" oninput="removeAccents(this)" placeholder="Nome Completo" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" name="nasc" maxlength="10" placeholder="DD/MM/AAAA" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" oninput="maskNascimento(this)" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="Administração" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="curso" value="Administração">
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="${schoolType}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="publica" value="${schoolType}">
                      </div>
                      <div class="md:col-span-2">
                          <select name="bairro" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                              <option value="">Selecione um bairro</option>
                              <option value="Cota">Cota</option>
                              <option value="Outros Bairros">Outros Bairros</option>
                          </select>
                      </div>
                      <div class="md:col-span-1 flex items-center">
                          <label for="pcd" class="text-sm text-gray-600 mr-2">PCD</label>
                          <input type="checkbox" id="pcd" name="pcd" value="1" class="w-4 h-4 text-green-600 border border-gray-300 rounded">
                      </div>
                  </div>
  
                  <!-- Notas dos Anos -->
                  <div class="space-y-4">
                      <!-- 6º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-green-600 mb-3 border-b pb-2">6º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp6" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a6" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m6" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h6" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g6" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c6" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i6" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef6" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r6" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 7º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-green-600 mb-3 border-b pb-2">7º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp7" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a7" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m7" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h7" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g7" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c7" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i7" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef7" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r7" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 8º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-green-600 mb-3 border-b pb-2">8º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp8" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a8" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m8" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h8" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g8" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c8" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i8" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef8" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r8" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-green-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
                  </div>
  
                  <!-- Botões -->
                  <div class="flex justify-center space-x-4 pt-4 border-t">
                      <button type="button" onclick="closeModalAndRedirect('AdministracaoForm', 'inicio.php');" 
                          class="px-6 py-2.5 border-2 border-green-600 rounded-md text-green-600 hover:bg-green-50 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                          Cancelar
                      </button>
                      <button type="button" onclick="handleAvancar()" 
                          class="px-6 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                          </svg>
                          Avançar
                      </button>
                  </div>
              </div>
  
              <!-- Modal para 9º Ano -->
              <div id="bimestreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl w-[90%] h-auto" style="transform: scale(0.95);">
                      <div class="bg-green-600 p-4 rounded-t-xl">
                          <h2 class="text-2xl font-bold text-white text-center">Notas 9º ano</h2>
                      </div>
                      <div class="p-4 space-y-3">
                          <!-- 1º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-green-600 mb-3 border-b pb-2">1º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_1" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_1" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_1" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_1" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_1" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_1" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_1" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_1" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_1" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 2º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-green-600 mb-3 border-b pb-2">2º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_2" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_2" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_2" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_2" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_2" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_2" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_2" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_2" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_2" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 3º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-green-600 mb-3 border-b pb-2">3º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_3" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_3" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_3" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_3" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_3" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_3" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_3" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_3" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_3" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Média Geral -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-green-600 mb-3 border-b pb-2">Média Geral</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_4" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="a9_4" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_4" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="h9_4" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="g9_4" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="c9_4" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="i9_4" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="ef9_4" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_4" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-green-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Botões do Modal 9º Ano -->
                          <div class="flex justify-center space-x-4 pt-4 border-t">
                              <button type="button" onclick="hideBimestreModal()" 
                                  class="px-6 py-2.5 border-2 border-green-600 rounded-md text-green-600 hover:bg-green-50 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                  </svg>
                                  Voltar
                              </button>
                              <button type="submit" 
                                  class="px-6 py-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                  </svg>
                                  Cadastrar
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
      </div>
      `
  }
  
  // ===== FORMULÁRIO DE EDIFICAÇÕES =====
  
  function createEdificacoesForm(schoolType) {
    return `
      <div class="bg-white rounded-xl shadow-md">
          <form id="EdificacoesForm" action="../controllers/controller.php" method="POST">
              <div class="bg-gray-600 p-4 rounded-t-xl">
                  <h2 class="text-2xl font-bold text-white text-center">Formulário de Edificações</h2>
              </div>
  
              <div class="p-6 space-y-6">
                  <!-- Informações Pessoais -->
                  <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                      <div class="md:col-span-6">
                          <input type="text" name="nome" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500" oninput="removeAccents(this)" placeholder="Nome Completo" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" name="nasc" maxlength="10" placeholder="DD/MM/AAAA" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500" oninput="maskNascimento(this)" required>
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="Edificações" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="curso" value="Edificações">
                      </div>
                      <div class="md:col-span-1">
                          <input type="text" value="${schoolType}" class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md" disabled>
                          <input type="hidden" name="publica" value="${schoolType}">
                      </div>
                      <div class="md:col-span-2">
                          <select name="bairro" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500" required>
                              <option value="">Selecione um bairro</option>
                              <option value="Cota">Cota</option>
                              <option value="Outros Bairros">Outros Bairros</option>
                          </select>
                      </div>
                      <div class="md:col-span-1 flex items-center">
                          <label for="pcd" class="text-sm text-gray-600 mr-2">PCD</label>
                          <input type="checkbox" id="pcd" name="pcd" value="1" class="w-4 h-4 text-gray-600 border border-gray-300 rounded">
                      </div>
                  </div>
  
                  <!-- Notas dos Anos -->
                  <div class="space-y-4">
                      <!-- 6º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-gray-600 mb-3 border-b pb-2">6º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp6" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a6" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m6" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h6" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g6" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c6" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i6" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef6" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r6" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 7º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-gray-600 mb-3 border-b pb-2">7º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp7" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a7" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m7" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h7" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g7" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c7" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i7" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef7" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r7" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
  
                      <!-- 8º Ano -->
                      <div class="bg-gray-50 p-4 rounded-lg">
                          <h3 class="text-lg font-semibold text-gray-600 mb-3 border-b pb-2">8º Ano</h3>
                          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-9 gap-3">
                              <input type="text" name="lp8" placeholder="PORTUGUÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="a8" placeholder="ARTES" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="m8" placeholder="MATEMÁTICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="h8" placeholder="HISTÓRIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="g8" placeholder="GEOGRAFIA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="c8" placeholder="CIÊNCIAS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="i8" placeholder="INGLÊS" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" required oninput="maskNota(this)">
                              <input type="text" name="ef8" placeholder="ED. FÍSICA" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                              <input type="text" name="r8" placeholder="RELIGIÃO" class="w-full px-2 py-2 border border-gray-300 rounded-md text-center focus:ring-2 focus:ring-gray-500 text-sm" oninput="maskNota(this)">
                          </div>
                      </div>
                  </div>
  
                  <!-- Botões -->
                  <div class="flex justify-center space-x-4 pt-4 border-t">
                      <button type="button" onclick="closeModalAndRedirect('EdificacoesForm', 'inicio.php');" 
                          class="px-6 py-2.5 border-2 border-gray-600 rounded-md text-gray-600 hover:bg-gray-50 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                          </svg>
                          Cancelar
                      </button>
                      <button type="button" onclick="handleAvancar()" 
                          class="px-6 py-2.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-base flex items-center font-medium">
                          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                          </svg>
                          Avançar
                      </button>
                  </div>
              </div>
  
              <!-- Modal para 9º Ano -->
              <div id="bimestreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white rounded-xl w-[90%] h-auto" style="transform: scale(0.95);">
                      <div class="bg-gray-600 p-4 rounded-t-xl">
                          <h2 class="text-2xl font-bold text-white text-center">Notas 9º ano</h2>
                      </div>
                      <div class="p-4 space-y-3">
                          <!-- 1º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-gray-600 mb-3 border-b pb-2">1º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_1" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_1" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_1" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_1" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_1" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_1" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_1" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_1" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_1" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_1" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 2º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-gray-600 mb-3 border-b pb-2">2º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_2" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_2" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_2" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_2" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_2" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_2" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_2" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_2" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_2" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- 3º Bimestre -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-gray-600 mb-3 border-b pb-2">3º Bimestre</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_3" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="a9_3" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_3" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="h9_3" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="g9_3" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="c9_3" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="i9_3" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" required oninput="maskNota(this)">
                                  <input type="text" name="ef9_3" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_3" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Média Geral -->
                          <div class="bg-gray-50 p-3 rounded-lg">
                              <h3 class="text-lg font-semibold text-gray-600 mb-3 border-b pb-2">Média Geral</h3>
                              <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-9 gap-2">
                                  <input type="text" name="lp9_4" placeholder="PORTUGUÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="a9_4" placeholder="ARTES" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="m9_4" placeholder="MATEMÁTICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="h9_4" placeholder="HISTÓRIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="g9_4" placeholder="GEOGRAFIA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="c9_4" placeholder="CIÊNCIAS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="i9_4" placeholder="INGLÊS" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="ef9_4" placeholder="ED. FÍSICA" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                                  <input type="text" name="r9_4" placeholder="RELIGIÃO" class="w-full px-1 py-1.5 border border-gray-300 rounded-md text-center focus:ring-1 focus:ring-gray-500 text-xs" oninput="maskNota(this)">
                              </div>
                          </div>
  
                          <!-- Botões do Modal 9º Ano -->
                          <div class="flex justify-center space-x-4 pt-4 border-t">
                              <button type="button" onclick="hideBimestreModal()" 
                                  class="px-6 py-2.5 border-2 border-gray-600 rounded-md text-gray-600 hover:bg-gray-50 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                  </svg>
                                  Voltar
                              </button>
                              <button type="submit" 
                                  class="px-6 py-2.5 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-base flex items-center font-medium">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                  </svg>
                                  Cadastrar
                              </button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
      </div>
      `
  }
    
  function handleAvancar() {
    const personalFields = [
        document.querySelector('input[name="nome"]'),
        document.querySelector('input[name="nascimento"]'),
        document.querySelector('input[name="bairro"]'),
    ]

    const requiredFields = document.querySelectorAll(
        'input[name="lp6"], input[name="m6"], input[name="h6"], input[name="g6"], input[name="c6"], input[name="i6"],' +
        'input[name="lp7"], input[name="m7"], input[name="h7"], input[name="g7"], input[name="c7"], input[name="i7"],' +
        'input[name="lp8"], input[name="m8"], input[name="h8"], input[name="g8"], input[name="c8"], input[name="i8"]',
    )

    const personalInfoValid = personalFields.every((field) => field && field.value.trim() !== "")
    const gradeFieldsValid = Array.from(requiredFields).every((field) => field.value.trim() !== "")

    if (personalInfoValid && gradeFieldsValid) {
        const bimestreModal = document.getElementById("bimestreModal")
        bimestreModal.classList.remove("hidden")

        const modalContent = bimestreModal.querySelector("div")
        if (modalContent) {
            modalContent.style.position = "relative"
            document.body.style.overflow = "hidden"
        }

        Swal.fire({
            icon: "success",
            title: "Formulário válido!",
            text: "Você pode prosseguir para o próximo passo.",
            customClass: {
                popup: "rounded-xl shadow-xl",
            },
            timer: 2000,
            showConfirmButton: false
        })
    } else {
        if (!personalInfoValid) {
            Swal.fire({
                icon: "error",
                title: "Informações pessoais incompletas",
                text: "Por favor, preencha todas as informações pessoais (Nome, Data de Nascimento, Bairro).",
                customClass: {
                    popup: "rounded-xl shadow-xl",
                },
                willClose: () => {
                    const modal = document.querySelector('.swal2-container')
                    if (modal) {
                        modal.style.display = 'none'
                    }
                }
            })
        } else {
            Swal.fire({
                icon: "error",
                title: "Notas incompletas",
                text: "Por favor, preencha todas as notas dos anos 6º, 7º e 8º antes de avançar.",
                customClass: {
                    popup: "rounded-xl shadow-xl",
                },
                willClose: () => {
                    const modal = document.querySelector('.swal2-container')
                    if (modal) {
                        modal.style.display = 'none'
                    }
                }
            })
        }
    }
  }
  
  function hideBimestreModal() {
    document.getElementById("bimestreModal").classList.add("hidden")
    document.body.style.overflow = "auto"
  
    const courseName = document.querySelector('input[name="curso"]').value
    const schoolType = document.querySelector('input[name="publica"]').value
    showModal(courseName, schoolType)
  }
  
  function closeModalAndRedirect(formId, redirectUrl) {
    const form = document.getElementById(formId)
    if (form) {
      form.reset()
    }
    const modal = document.getElementById("bimestreModal")
    if (modal) {
      modal.classList.add("hidden")
    }
    Swal.close()
    if (redirectUrl) {
      window.location.href = redirectUrl
    }
  }
  
  
  function maskNascimento(input) {
    let value = input.value.replace(/\D/g, "")
    value = value.slice(0, 8)
  
    let formattedValue = ""
    if (value.length >= 6) {
      formattedValue = `${value.slice(0, 2)}/${value.slice(2, 4)}/${value.slice(4)}`
    } else if (value.length >= 4) {
      formattedValue = `${value.slice(0, 2)}/${value.slice(2)}`
    } else if (value.length >= 2) {
      formattedValue = `${value.slice(0, 2)}/${value.slice(2)}`
    } else {
      formattedValue = value
    }
  
    if (value.length === 0) {
      formattedValue = ""
    }
  
    input.value = formattedValue
  
    if (formattedValue.length === 10) {
      const [day, month, year] = formattedValue.split("/").map(Number)
      const date = new Date(year, month - 1, day)
      const today = new Date()
  
      if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day || date > today) {
        input.setCustomValidity("Data inválida")
      } else {
        input.setCustomValidity("")
      }
    } else {
      input.setCustomValidity("")
    }
  }
  
  function removeAccents(input) {
    const cursorPosition = input.selectionStart
    const accents = "àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ"
    const noAccents = "aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY"
  
    const value = input.value
    let result = ""
  
    for (let i = 0; i < value.length; i++) {
      const char = value[i]
      const index = accents.indexOf(char)
  
      if (index !== -1) {
        result += noAccents[index]
      } else {
        result += char
      }
    }
  
    input.value = result
    input.setSelectionRange(cursorPosition, cursorPosition)
  }
  
  function maskNota(input) {
    let value = input.value.replace(/[^0-9.]/g, "")
  
    const parts = value.split(".")
    if (parts.length > 2) {
      value = parts[0] + "." + parts.slice(1).join("")
    }
  
    if (["101", "102", "103", "104", "105", "106", "107", "108", "109", "110"].includes(value)) {
      value = "10"
      input.value = value
      return
    }
  
    if (!value.includes(".") && value.length >= 2) {
      value = value.slice(0, 1) + "." + value.slice(1)
    }
  
    if (value.includes(".")) {
      const decimals = value.split(".")[1]
      if (decimals.length > 2) {
        value = value.slice(0, value.indexOf(".") + 3)
      }
    }
  
    if (input.value === "10") {
      input.value = "10"
      return
    }
  
    const numericValue = Number.parseFloat(value)
    if (numericValue > 10) {
      value = value[0] + ".0"
    }
  
    input.value = value
  }
    
  window.enfermagemPub = enfermagemPub
  window.enfermagemPriv = enfermagemPriv
  window.informaticaPub = informaticaPub
  window.informaticaPriv = informaticaPriv
  window.administracaoPub = administracaoPub
  window.administracaoPriv = administracaoPriv
  window.edificacoesPub = edificacoesPub
  window.edificacoesPriv = edificacoesPriv
  window.showReportsModal = showReportsModal
  window.showatualizModal = showatualizModal
  window.openInsertUserModal = openInsertUserModal
  window.showResultsModal = showResultsModal
  window.showDeleteConfirmationModal = showDeleteConfirmationModal
  window.showExcluirCandidatoModal = showExcluirCandidatoModal
  window.handleAvancar = handleAvancar
  window.hideBimestreModal = hideBimestreModal
  window.closeModalAndRedirect = closeModalAndRedirect
  window.maskNascimento = maskNascimento
  window.removeAccents = removeAccents
  window.maskNota = maskNota
  window.toggleCourseSelect = toggleCourseSelect
  window.submitForm = submitForm
  