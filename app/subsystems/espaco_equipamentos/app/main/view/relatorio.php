<?php
require_once __DIR__ . '/../controllers/RelatorioController.php';
require_once __DIR__ . '/../../../config/database.php';

// Estilo CSS para o relatório
$css = '
<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap");
    
    body {
        font-family: "Poppins", sans-serif;
        color: #333333;
        line-height: 1.6;
        margin: 0;
        padding: 20px;
    }
    
    .header {
        background-color: #007A33;
        color: white;
        padding: 20px;
        text-align: center;
        border-radius: 10px 10px 0 0;
        margin-bottom: 20px;
    }
    
    .header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }
    
    .header p {
        margin: 5px 0 0;
        font-size: 14px;
        opacity: 0.9;
    }
    
    .content {
        background-color: #FFFFFF;
        padding: 20px;
        border-radius: 0 0 10px 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .section {
        margin-bottom: 20px;
    }
    
    .section-title {
        color: #007A33;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #FFA500;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
    }
    
    th {
        background-color: #007A33;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 600;
    }
    
    td {
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    .status {
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-pendente {
        background-color: #ffc107;
        color: #856404;
    }
    
    .status-aprovado {
        background-color: #28a745;
        color: white;
    }
    
    .status-rejeitado {
        background-color: #dc3545;
        color: white;
    }
    
    .footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
        font-size: 12px;
        color: #666;
    }
    
    .highlight {
        color: #FFA500;
        font-weight: 600;
    }
    
    .info-box {
        background-color: #f8f9fa;
        border-left: 4px solid #007A33;
        padding: 15px;
        margin: 15px 0;
        border-radius: 0 5px 5px 0;
    }
    
    .info-box h3 {
        margin: 0 0 10px;
        color: #007A33;
        font-size: 16px;
    }
    
    .info-box p {
        margin: 0;
        font-size: 14px;
    }
</style>
';

// Conteúdo HTML do relatório
$html = $css . '
<div class="header">
    <h1>Relatório de Agendamentos</h1>
    <p>EEEP Salaberga - Sistema Gerenciador de Espaços e Equipamentos</p>
</div>

<div class="content">
    <div class="section">
        <div class="section-title">Informações Gerais</div>
        <div class="info-box">
            <h3>Período do Relatório</h3>
            <p>Data de geração: ' . date('d/m/Y H:i') . '</p>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Agendamentos de Equipamentos</div>
        <table>
            <thead>
                <tr>
                    <th>Equipamento</th>
                    <th>Aluno</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dados dos equipamentos serão inseridos aqui via PHP -->
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">Agendamentos de Espaços</div>
        <table>
            <thead>
                <tr>
                    <th>Espaço</th>
                    <th>Aluno</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dados dos espaços serão inseridos aqui via PHP -->
            </tbody>
        </table>
    </div>
</div>
';

echo $html;
?> 