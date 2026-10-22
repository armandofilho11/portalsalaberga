<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro Instituição</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonte -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Ícones -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>

        body{
            font-family:'Poppins',sans-serif;
        }

        .card-shadow{
            box-shadow:
            0 10px 25px rgba(0,0,0,.08),
            0 2px 10px rgba(0,0,0,.05);
        }

        .left-gradient{
            background:
            linear-gradient(
                135deg,
                #274f17 10%,
                #31591e 55%,
                #c97550 100%
            );
        }

        .input-style{
            @apply w-full h-12 px-4 border border-[#dbe3d0]
            rounded-xl bg-white
            focus:outline-none
            focus:ring-2
            focus:ring-[#8cb86e];
        }

    </style>

</head>




<body class="bg-slate-100 min-h-screen flex items-center justify-center p-12">

<div class="w-full max-w-7xl bg-[#faf7ef] rounded-3xl overflow-hidden card-shadow">

<div class="grid lg:grid-cols-12">
    

<!-- ========================================================= -->
<!-- ======================= CABEÇALHO ======================= -->
<!-- ========================================================= -->
        

<section class="lg:col-span-12 p-10 lg:p-14 overflow-y-auto">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-semibold text-[#31591e]">
                    Registro de Vagas
                </h2>
                <p class="text-gray-500 mt-2">
                    Informe sobre vagas, perfil, data e horários de seleção.
                </p>
            </div>
            
            
            
            
            
            
            

<form method="POST" action="../control/Controller.php">

    <input type="hidden" name="acao" value="cadastrar_vagas">

        
        
        
        
        
        
        
        
        
        

<div class="grid lg:grid-cols-2 gap-10">

<!-- ========================================================= -->
<!-- ==================== COLUNA ESQUERDA ==================== -->
<!-- ========================================================= -->
    <div>
        







<div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-8">

    <h4 class="font-semibold text-blue-900 mb-2">
        Orientações para preenchimento
    </h4>

    <ul class="text-sm text-blue-800 space-y-2">
        <li>• Selecione o nome de sua empresa ou organização.</li>
        <li>• Informe a quantidade de vagas para cada perfil desejado.</li>
        <li>• Selecione como pretende realizar a escolha dos candidatos.</li>
        <li><strong>• Utilize o campo "Observações" para: </strong>
            <ol class="mt-2 ml-6 list-decimal space-y-1">
                <li>Apresentar brevemente a instituição e sua área de atuação.</li>
                <li>Descrever as atividades que o estagiário desenvolverá.</li>
                <li>Informar a natureza dos serviços prestados pela organização.</li>
            </ol>
        </li>
        
        
    </ul>

</div>





        
        <div class="mb-8">
        
                <h3 class="text-xl font-semibold text-[#31591e] mb-5">
                    Empresa  /  Instituição
                </h3>
        
            <select
                name="concedente_id"
                required
                class="w-full h-12 px-4 border rounded-xl bg-white">
        
                <option value="">
                    Selecione aqui o nome de sua empresa
                </option>

                <?php 
                require_once("../model/model.php");
                $model = new model();
                $concedentes = $model->listar_concedentes();

                foreach ($concedentes as $concedente): ?>
                    <option value="<?= $concedente['id'] ?>">
                        <?= htmlspecialchars($concedente['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        

        </div>





        <h3 class="text-xl font-semibold text-[#31591e] mb-5">
            Modelo de Seleção
        </h3>

        <div class="space-y-3 mb-8">

            <label class="flex items-center gap-2">
                <input type="radio"
                    name="forma_selecao"
                    value="Selecao">
                Desejo realizar processo seletivo próprio.
            </label>

            <label class="flex items-center gap-2">
                <input type="radio"
                    name="forma_selecao"
                    value="Entrevista">
                Desejo entrevistar os candidatos encaminhados.
            </label>

            <label class="flex items-center gap-2">
                <input type="radio"
                    name="forma_selecao"
                    value="Indicacao">
                Desejo que a escola selecione e indique os estudantes conforme o perfil solicitado.
            </label>

        </div>
        
        
        
        
        
       
        
        
        
        
        

    </div>







<!-- ========================================================= -->
<!-- ===================== COLUNA DIREITA ==================== -->
<!-- ========================================================= -->    
    <div>




        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-5">
            <p class="text-sm text-gray-700 leading-relaxed">
                <strong>Importante:</strong> Não dispomos de alunos com perfil
                de saída voltado para atividades administrativas. Caso a
                instituição tenha interesse em estagiários com este perfil,
                recomendamos a busca junto ao curso técnico em Administração.
            </p>
        </div>
        
        
        
        
        
        
        <h3 class="mb-5 text-xl font-semibold text-[#31591e]">
            Perfil Desejado para os Estagiários
        </h3>


        <label class="block font-medium mb-6">
            Preencha a quantidade de vagas que pretende ofertar no(s) perfil/perfis escolhido(s).
        </label>

        <div class="grid grid-cols-4 gap-4 mb-8">

            <div>
            <h3 style="text-align:center;"  class="text-x2 font-semibold text-[#31591e] mb-0">
                Desenvolvedor
            </h3>
                <input type="number" min="0"
                    name="vagas_desenvolvedor"
                    class="w-full h-12 px-4 border rounded-xl">
            </div>

            <div>
            <h3 style="text-align:center;"  class="text-x2 font-semibold text-[#31591e] mb-0">
                    Design/Mídias
            </h3>
                <input type="number" min="0"
                    name="vagas_design"
                    class="w-full h-12 px-4 border rounded-xl">
            </div>

            <div>
                <h3 style="text-align:center;" class="text-x2 font-semibold text-[#31591e] mb-0">
                        Suporte/Redes
                </h3>
                <input type="number" min="0"
                    name="vagas_suporte"
                    class="w-full h-12 px-4 border rounded-xl">
            </div>

            <div>
                <h3 style="text-align:center;" class="text-x2 font-semibold text-[#31591e] mb-0">
                    Tutoria
                </h3>
                <input type="number" min="0"
                    name="vagas_tutoria"
                    class="w-full h-12 px-4 border rounded-xl">
            </div>

        </div>        





                
        <h3 class="mb-5 text-xl font-semibold text-[#31591e]">
            Sobre a seleção, responda:
        </h3>

        <div class="grid grid-cols-3 gap-4 mb-8">

            <div>
                <h3 style="text-align:center;" class="text-x2 font-semibold text-[#31591e] mb-0">
                    Data
                </h3>
                <input type="date" 
                    name="data"
                    class="w-full h-12 px-4 border rounded-xl">
            </div>


            <div>
                <h3 style="text-align:center;" class="text-x2 font-semibold text-[#31591e] mb-0">
                    Horário
                </h3>
                <input type="time" 
                    name="hora"
                    class="w-full h-12 px-4 border rounded-xl">
            </div>



            <div>
                <h3 style="text-align:center;" class="text-x2 font-semibold text-[#31591e] mb-0">
                    Procurar por
                </h3>
                <input type="text" 
                    name="procurar_por"
                    class="w-full h-12 px-4 border rounded-xl">
            </div>


        </div>      
        


    </div>
    
    
    
    
    

        


</div>




        <h3 class="mb-5 text-xl font-semibold text-[#31591e]">
            Observações
        </h3>

        <textarea
            name="observacoes"
            rows="3"
            class="w-full border rounded-xl p-1">
            
        </textarea>
        <!-- BOTÃO -->

<div class="mt-8">
    <button
        type="submit"
        class="w-full h-14 rounded-xl text-white font-medium
               bg-gradient-to-r from-[#7fae66] to-[#9cc18a]
               hover:opacity-90 transition">

        <i class="fa-solid fa-floppy-disk mr-2"></i>
        Registrar Vagas

    </button>
</div>

</form>

</section>

</div>

</div>

</body>
</html>