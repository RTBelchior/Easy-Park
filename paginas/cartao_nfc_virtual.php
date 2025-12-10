<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>EasyPark - Cartão NFC Virtual</title>
    <link rel="icon" href="../imagens/barreira.png" type="image/x-icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 420px;
            width: 100%;
        }

        .card-virtual {
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 25px;
            text-align: center;
            color: white;
            position: relative;
        }

        .card-logo {
            font-size: 70px;
            margin-bottom: 15px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .card-title {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .card-subtitle {
            font-size: 15px;
            opacity: 0.95;
        }

        .card-body {
            padding: 35px 25px;
        }

        .user-info {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 2px solid #f1f5f9;
        }

        .user-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 36px;
            margin: 0 auto 15px;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .user-name {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .user-number {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 12px;
        }

        .user-type {
            display: inline-block;
            padding: 8px 20px;
            background: #e0e7ff;
            color: #4338ca;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .card-number-section {
            background: #f8fafc;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            text-align: center;
        }

        .card-label {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-number {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
            margin-bottom: 15px;
        }

        .card-type {
            display: inline-block;
            padding: 6px 16px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
        }

        .nfc-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 35px;
            text-align: center;
            margin-bottom: 25px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .nfc-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .nfc-icon {
            font-size: 100px;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: nfcPulse 2s ease-in-out infinite;
        }

        @keyframes nfcPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .nfc-status {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .nfc-instruction {
            font-size: 15px;
            opacity: 0.95;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .nfc-section.active {
            animation: activePulse 1.5s ease-in-out infinite;
        }

        @keyframes activePulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
            50% { box-shadow: 0 0 0 20px rgba(102, 126, 234, 0); }
        }

        .activate-button {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: 15px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            transition: all 0.3s;
        }

        .activate-button:active {
            transform: scale(0.95);
        }

        .activate-button:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            box-shadow: none;
        }

        .info-box {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .info-title {
            font-size: 16px;
            font-weight: 700;
            color: #854d0e;
            margin-bottom: 8px;
        }

        .info-text {
            font-size: 14px;
            color: #854d0e;
            line-height: 1.6;
        }

        .info-list {
            margin-top: 12px;
            padding-left: 20px;
        }

        .info-list li {
            margin-bottom: 6px;
        }

        .compatibility-warning {
            background: #fee2e2;
            border: 2px solid #ef4444;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            display: none;
        }

        .compatibility-warning.show {
            display: block;
        }

        .warning-icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .warning-title {
            font-size: 16px;
            font-weight: 700;
            color: #991b1b;
            margin-bottom: 8px;
        }

        .warning-text {
            font-size: 14px;
            color: #991b1b;
            line-height: 1.6;
        }

        .back-button {
            width: 100%;
            padding: 15px;
            background: #f1f5f9;
            color: #475569;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.9);
        }

        .last-access {
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .last-access-title {
            font-size: 15px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 12px;
        }

        .access-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .access-item:last-child {
            border-bottom: none;
        }

        .access-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .access-icon {
            font-size: 20px;
        }

        .access-details {
            display: flex;
            flex-direction: column;
        }

        .access-type {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .access-park {
            font-size: 12px;
            color: #64748b;
        }

        .access-time {
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-virtual">
            <div class="card-header">
                <div class="card-logo">💳</div>
                <div class="card-title">Cartão NFC Virtual</div>
                <div class="card-subtitle">Aproxime do leitor para acesso</div>
            </div>

            <div class="card-body">
                <div class="user-info">
                    <div class="user-avatar" id="userAvatar">?</div>
                    <div class="user-name" id="userName">Carregando...</div>
                    <div class="user-number" id="userNumber">Nº ------</div>
                    <div class="user-type" id="userType">---</div>
                </div>

                <div class="card-number-section">
                    <div class="card-label">ID do Cartão</div>
                    <div class="card-number" id="cardNumber">-------</div>
                    <div class="card-type" id="cardType">---</div>
                </div>

                <div class="compatibility-warning" id="compatibilityWarning">
                    <div class="warning-icon">⚠️</div>
                    <div class="warning-title">NFC não disponível</div>
                    <div class="warning-text">
                        O seu dispositivo não suporta emulação de cartão NFC (HCE) ou o NFC está desativado. 
                        Esta funcionalidade está disponível apenas em dispositivos Android com NFC ativo.
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-icon">💡</div>
                    <div class="info-title">Como usar:</div>
                    <div class="info-text">
                        <ol class="info-list">
                            <li><strong>Ative o NFC</strong> nas configurações do telemóvel</li>
                            <li>Clique no botão <strong>"Ativar Cartão NFC"</strong></li>
                            <li><strong>Aproxime o telemóvel</strong> do leitor da cancela</li>
                            <li>Aguarde a confirmação de acesso</li>
                        </ol>
                    </div>
                </div>

                <div class="nfc-section" id="nfcSection">
                    <div class="status-badge" id="statusBadge">Inativo</div>
                    <div class="nfc-icon" id="nfcIcon">📡</div>
                    <div class="nfc-status" id="nfcStatus">NFC Desativado</div>
                    <div class="nfc-instruction" id="nfcInstruction">
                        Clique no botão abaixo para ativar o cartão virtual
                    </div>
                </div>

                <button class="activate-button" id="activateButton" onclick="toggleNFC()">
                    🔓 Ativar Cartão NFC
                </button>

                <div class="last-access" id="lastAccessSection" style="display: none;">
                    <div class="last-access-title">📅 Últimos Acessos</div>
                    <div id="lastAccessList">Carregando...</div>
                </div>

                <button class="back-button" onclick="window.history.back()">
                    ← Voltar
                </button>
            </div>
        </div>
    </div>

    <script>
        let nfcActive = false;
        let nfcWriter = null;
        let cardData = null;

        // Load User Data
        async function loadUserData() {
            try {
                // Caminho correto para paginas/cartao_nfc_virtual.php
                const response = await fetch('../api/get_meu_cartao.php');
                const text = await response.text();
                console.log('Resposta completa da API:', text);
                console.log('URL chamada:', response.url);
                
                const parts = text.trim().split('|');
                console.log('Partes separadas:', parts);

                if (parts[0] === 'SUCCESS') {
                    cardData = {
                        nome: parts[1],
                        numero: parts[2],
                        tipo_utilizador: parts[3],
                        iniciais: parts[4],
                        numero_cartao: parts[5],
                        tipo_cartao: parts[6],
                        ativo: parts[7] === '1'
                    };

                    console.log('Dados do cartão:', cardData);

                    // Update UI
                    document.getElementById('userAvatar').textContent = cardData.iniciais;
                    document.getElementById('userName').textContent = cardData.nome;
                    document.getElementById('userNumber').textContent = `Nº ${cardData.numero}`;
                    document.getElementById('userType').textContent = cardData.tipo_utilizador;
                    document.getElementById('cardNumber').textContent = cardData.numero_cartao;
                    document.getElementById('cardType').textContent = cardData.tipo_cartao === '1' ? 'Tag NFC' : 'Cartão';

                    if (!cardData.ativo) {
                        showWarning('Cartão desativado', 'Seu cartão está desativado. Contacte o administrador.');
                        document.getElementById('activateButton').disabled = true;
                    }

                    loadLastAccess();
                } else {
                    console.error('Erro na resposta:', text);
                    showWarning('Erro', text || 'Não foi possível carregar os dados do cartão.');
                }
            } catch (error) {
                console.error('Erro ao carregar dados:', error);
                showWarning('Erro', 'Erro ao carregar dados: ' + error.message);
            }
        }

        // Check NFC Support
        function checkNFCSupport() {
            if ('NDEFReader' in window) {
                return true;
            } else {
                document.getElementById('compatibilityWarning').classList.add('show');
                document.getElementById('activateButton').disabled = true;
                return false;
            }
        }

        // Toggle NFC
        async function toggleNFC() {
            if (!cardData) {
                alert('Carregue os dados do cartão primeiro');
                return;
            }

            if (!nfcActive) {
                await activateNFC();
            } else {
                deactivateNFC();
            }
        }

        // Activate NFC
        async function activateNFC() {
            try {
                if (!nfcWriter) {
                    nfcWriter = new NDEFReader();
                }

                // Solicitar permissão e escrever no cartão virtual
                await nfcWriter.write({
                    records: [{
                        recordType: "text",
                        data: cardData.numero_cartao
                    }]
                });

                nfcActive = true;
                updateNFCUI(true);

                // Vibrar
                if ('vibrate' in navigator) {
                    navigator.vibrate([200, 100, 200]);
                }

            } catch (error) {
                console.error('Erro ao ativar NFC:', error);
                alert('Erro: ' + error.message + '\n\nPara usar esta funcionalidade:\n1. Ative o NFC nas configurações\n2. Use um navegador compatível (Chrome/Edge)\n3. Aproxime de um leitor NFC');
            }
        }

        // Deactivate NFC
        function deactivateNFC() {
            nfcActive = false;
            updateNFCUI(false);

            if ('vibrate' in navigator) {
                navigator.vibrate(100);
            }
        }

        // Update NFC UI
        function updateNFCUI(active) {
            const section = document.getElementById('nfcSection');
            const icon = document.getElementById('nfcIcon');
            const status = document.getElementById('nfcStatus');
            const instruction = document.getElementById('nfcInstruction');
            const button = document.getElementById('activateButton');
            const badge = document.getElementById('statusBadge');

            if (active) {
                section.classList.add('active');
                icon.textContent = '📶';
                status.textContent = 'NFC Ativo!';
                instruction.textContent = 'Aproxime o telemóvel do leitor da cancela agora';
                button.textContent = '🔒 Desativar Cartão NFC';
                button.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                badge.textContent = 'Ativo';
                badge.classList.add('active');
            } else {
                section.classList.remove('active');
                icon.textContent = '📡';
                status.textContent = 'NFC Desativado';
                instruction.textContent = 'Clique no botão abaixo para ativar o cartão virtual';
                button.textContent = '🔓 Ativar Cartão NFC';
                button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                badge.textContent = 'Inativo';
                badge.classList.remove('active');
            }
        }

        // Load Last Access
        async function loadLastAccess() {
            try {
                const response = await fetch('../api/get_meus_acessos.php');
                const text = await response.text();
                const parts = text.trim().split('|');

                if (parts[0] === 'SUCCESS') {
                    const acessos = JSON.parse(parts[1]);
                    
                    if (acessos.length > 0) {
                        document.getElementById('lastAccessSection').style.display = 'block';
                        
                        const html = acessos.slice(0, 5).map(a => {
                            const icon = a.tipo_acesso === 'entrada' ? '🚗' : '🚀';
                            const tipo = a.tipo_acesso === 'entrada' ? 'Entrada' : 'Saída';
                            const date = new Date(a.data_hora_acesso);
                            const time = date.toLocaleString('pt-PT', {
                                day: '2-digit',
                                month: '2-digit',
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                            return `
                                <div class="access-item">
                                    <div class="access-info">
                                        <div class="access-icon">${icon}</div>
                                        <div class="access-details">
                                            <div class="access-type">${tipo}</div>
                                            <div class="access-park">Parque ${a.id_parque}</div>
                                        </div>
                                    </div>
                                    <div class="access-time">${time}</div>
                                </div>
                            `;
                        }).join('');

                        document.getElementById('lastAccessList').innerHTML = html;
                    }
                }
            } catch (error) {
                console.error('Erro ao carregar acessos:', error);
            }
        }

        // Show Warning
        function showWarning(title, message) {
            const warning = document.getElementById('compatibilityWarning');
            warning.querySelector('.warning-title').textContent = title;
            warning.querySelector('.warning-text').textContent = message;
            warning.classList.add('show');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            checkNFCSupport();
            loadUserData();
        });
    </script>
</body>
</html>