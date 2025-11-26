<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado e se é administrador (segurança extra)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPark - Gestão de Acessos</title>
    <link rel="stylesheet" href="../../css/administracao.css">
    <style>
        /* Estilos adicionais específicos para esta página */
        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 16px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }

        .filter-input, .filter-select {
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #1e293b;
            transition: all 0.2s;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #1e3a8a;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .table-section {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .records-count {
            font-size: 14px;
            color: #64748b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8fafc;
        }

        th {
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #1e293b;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-entrada {
            background: rgba(34, 197, 94, 0.15);
            color: #16a34a;
        }

        .badge-saida {
            background: rgba(239, 68, 68, 0.15);
            color: #dc2626;
        }

        .parque-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: #dbeafe;
            color: #1e3a8a;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .loading-state {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .pagination button {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .pagination button:hover:not(:disabled) {
            background: #f8fafc;
            border-color: #3b82f6;
        }

        .pagination button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .pagination .page-info {
            font-size: 14px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- HEADER -->
        <div class="header">
            <div class="header-title">
                <h1>Gestão de Acessos</h1>
                <p class="header-subtitle">Entradas e saídas do parque</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div class="user-details">
                    <div class="user-name">Carregando...</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
        </div>

        <!-- FILTROS -->
        <div class="filters-section">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="filterNome">Nome</label>
                    <input type="text" id="filterNome" class="filter-input" placeholder="Buscar por nome...">
                </div>

                <div class="filter-group">
                    <label for="filterNumero">Número de Aluno</label>
                    <input type="text" id="filterNumero" class="filter-input" placeholder="Ex: 1234">
                </div>

                <div class="filter-group">
                    <label for="filterTipo">Tipo de Acesso</label>
                    <select id="filterTipo" class="filter-select">
                        <option value="">Todos</option>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterParque">Parque</label>
                    <select id="filterParque" class="filter-select">
                        <option value="">Todos</option>
                        <option value="1">Parque 1</option>
                        <option value="2">Parque 2</option>
                        <option value="3">Parque 3</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterDataInicio">Data Início</label>
                    <input type="date" id="filterDataInicio" class="filter-input">
                </div>

                <div class="filter-group">
                    <label for="filterDataFim">Data Fim</label>
                    <input type="date" id="filterDataFim" class="filter-input">
                </div>
            </div>

            <div class="filter-actions">
                <button class="btn btn-secondary" onclick="limparFiltros()">🔄 Limpar</button>
                <button class="btn btn-primary" onclick="buscarAcessos()">🔍 Buscar</button>
            </div>
        </div>

        <!-- TABELA -->
        <div class="table-section">
            <div class="table-header">
                <div>
                    <div class="table-title">Registros de Acesso</div>
                    <div class="records-count" id="recordsCount">0 registros encontrados</div>
                </div>
            </div>

            <div id="tableContent">
                <div class="loading-state">
                    ⏳ Carregando dados...
                </div>
            </div>

            <div class="pagination" id="pagination" style="display: none;">
                <button onclick="previousPage()">← Anterior</button>
                <span class="page-info" id="pageInfo">Página 1 de 1</span>
                <button onclick="nextPage()">Próximo →</button>
            </div>
        </div>
    </main>

    <script>
        let currentPage = 1;
        let totalPages = 1;
        const recordsPerPage = 20;

        // --- FETCH USER INFO ---
        async function fetchUserInfo() {
            try {
                // Ajusta o caminho da API conforme a tua estrutura de pastas (../ ou ../../)
                // ATENÇÃO: Confirma se o ficheiro está em ../../api ou ../api
                const response = await fetch('../../api/get_user_info.php');
                const text = await response.text();
                const parts = text.split('|');

                if (parts[0] === 'SUCCESS') {
                    const nome = parts[1];
                    const tipo = parts[2];
                    const iniciais = parts[3];

                    const avatar = document.querySelector('.user-avatar');
                    if (avatar) avatar.textContent = iniciais;

                    const userName = document.querySelector('.user-name');
                    if (userName) userName.textContent = nome;

                    const userRole = document.querySelector('.user-role');
                    if (userRole) userRole.textContent = tipo;
                }
            } catch (error) { console.log('Erro user info:', error); }
        }

        // Buscar acessos
        async function buscarAcessos(page = 1) {
            currentPage = page;
            
            const nome = document.getElementById('filterNome').value;
            const numero = document.getElementById('filterNumero').value;
            const tipo = document.getElementById('filterTipo').value;
            const parque = document.getElementById('filterParque').value;
            const dataInicio = document.getElementById('filterDataInicio').value;
            const dataFim = document.getElementById('filterDataFim').value;

            const params = new URLSearchParams({
                page: page,
                limit: recordsPerPage,
                nome: nome,
                numero: numero,
                tipo: tipo,
                parque: parque,
                data_inicio: dataInicio,
                data_fim: dataFim
            });

            try {
                document.getElementById('tableContent').innerHTML = '<div class="loading-state">⏳ Carregando dados...</div>';
                
                // ATENÇÃO: Confirma o caminho da tua API
                const response = await fetch(`../../api/get_historico_entradas.php?${params}`);
                const text = await response.text();
                
                console.log('📥 Resposta:', text);
                
                const parts = text.trim().split('|');
                
                if (parts[0] === 'SUCCESS') {
                    const totalRecords = parseInt(parts[1]);
                    const recordsData = parts[2];
                    
                    totalPages = Math.ceil(totalRecords / recordsPerPage);
                    
                    document.getElementById('recordsCount').textContent = 
                        `${totalRecords} registro${totalRecords !== 1 ? 's' : ''} encontrado${totalRecords !== 1 ? 's' : ''}`;
                    
                    if (totalRecords === 0) {
                        mostrarEstadoVazio();
                    } else {
                        renderizarTabela(recordsData);
                        atualizarPaginacao();
                    }
                } else {
                    throw new Error(parts[1] || 'Erro ao buscar dados');
                }
                
            } catch (error) {
                console.error('❌ Erro:', error);
                document.getElementById('tableContent').innerHTML = 
                    '<div class="empty-state"><div class="empty-state-icon">⚠️</div><p>Erro ao carregar dados: ' + error.message + '</p></div>';
            }
        }

        function renderizarTabela(recordsData) {
            if (!recordsData || recordsData === 'EMPTY') {
                mostrarEstadoVazio();
                return;
            }

            const records = recordsData.split(';');
            
            let html = `
                <table>
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Nome</th>
                            <th>Nº Aluno</th>
                            <th>Tipo</th>
                            <th>Parque</th>
                            <th>Cartão</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            records.forEach(record => {
                const fields = record.split(',');
                if (fields.length >= 6) {
                    const dataHora = fields[0];
                    const nome = fields[1];
                    const numero = fields[2];
                    const tipo = fields[3];
                    const parque = fields[4];
                    const cartao = fields[5];
                    
                    const badgeClass = tipo === 'entrada' ? 'badge-entrada' : 'badge-saida';
                    const tipoText = tipo === 'entrada' ? '🟢 Entrada' : '🔴 Saída';
                    
                    html += `
                        <tr>
                            <td>${formatarDataHora(dataHora)}</td>
                            <td><strong>${nome}</strong></td>
                            <td>${numero}</td>
                            <td><span class="badge ${badgeClass}">${tipoText}</span></td>
                            <td><span class="parque-badge">🅿️ Parque ${parque}</span></td>
                            <td><code>${cartao}</code></td>
                        </tr>
                    `;
                }
            });
            
            html += '</tbody></table>';
            document.getElementById('tableContent').innerHTML = html;
        }

        function mostrarEstadoVazio() {
            document.getElementById('tableContent').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <p>Nenhum registro encontrado com os filtros aplicados.</p>
                </div>
            `;
            document.getElementById('pagination').style.display = 'none';
        }

        function atualizarPaginacao() {
            const pagination = document.getElementById('pagination');
            const pageInfo = document.getElementById('pageInfo');
            
            if (totalPages > 1) {
                pagination.style.display = 'flex';
                pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;
                
                const prevBtn = pagination.querySelector('button:first-child');
                const nextBtn = pagination.querySelector('button:last-child');
                
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages;
            } else {
                pagination.style.display = 'none';
            }
        }

        function previousPage() {
            if (currentPage > 1) {
                buscarAcessos(currentPage - 1);
            }
        }

        function nextPage() {
            if (currentPage < totalPages) {
                buscarAcessos(currentPage + 1);
            }
        }

        function limparFiltros() {
            document.getElementById('filterNome').value = '';
            document.getElementById('filterNumero').value = '';
            document.getElementById('filterTipo').value = '';
            document.getElementById('filterParque').value = '';
            document.getElementById('filterDataInicio').value = '';
            document.getElementById('filterDataFim').value = '';
            buscarAcessos(1);
        }

        function formatarDataHora(dataHora) {
            const data = new Date(dataHora);
            const dia = String(data.getDate()).padStart(2, '0');
            const mes = String(data.getMonth() + 1).padStart(2, '0');
            const ano = data.getFullYear();
            const hora = String(data.getHours()).padStart(2, '0');
            const min = String(data.getMinutes()).padStart(2, '0');
            
            return `${dia}/${mes}/${ano} ${hora}:${min}`;
        }

        // --- CORREÇÃO AQUI ---
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Página carregada!');
            fetchUserInfo(); // Adicionada a chamada da função
            buscarAcessos(1);
        });

        // Buscar ao pressionar Enter nos campos
        document.querySelectorAll('.filter-input, .filter-select').forEach(element => {
            element.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    buscarAcessos(1);
                }
            });
        });
    </script>
</body>
</html>