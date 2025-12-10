document.addEventListener("DOMContentLoaded", () => {
    // 1. Carregar dados assim que a página abre
    fetchUserInfo();
    fetchCardInfo();
    fetchVehicles();

    // 2. Configurar o formulário de adicionar veículo (AJAX)
    const formAdd = document.getElementById('form-adicionar-veiculo');
    if (formAdd) {
        formAdd.addEventListener('submit', handleAddVehicle);
    }
});

/* -------------------------------------------------------------------------- */
/*                           1. INFORMAÇÕES PESSOAIS                          */
/* -------------------------------------------------------------------------- */

async function fetchUserInfo() {
    try {
        const response = await fetch('../api/get_user_info.php');
        const text = await response.text();

        console.log('User Info Response:', text);

        // Formato API: SUCCESS|nome|tipo|iniciais|email|ativo
        const parts = text.split('|');

        if (parts[0] === 'SUCCESS') {
            const elNome = document.getElementById('user-nome');
            const elTipo = document.getElementById('user-tipo');
            const elEmail = document.getElementById('user-email');
            const elEstado = document.getElementById('user-estado');

            if (elNome) elNome.textContent = parts[1];
            if (elTipo) elTipo.textContent = parts[2];
            if (elEmail) elEmail.textContent = parts[4];

            // Estado da Conta (Badge Colorido)
            if (elEstado) {
                const isAtivo = parts[5] == '1';
                if (isAtivo) {
                    elEstado.innerHTML = '<span class="badge badge-success" style="background:#dcfce7; color:#166534; padding:2px 8px; border-radius:4px;">Ativa</span>';
                } else {
                    elEstado.innerHTML = '<span class="badge badge-danger" style="background:#fee2e2; color:#991b1b; padding:2px 8px; border-radius:4px;">Inativa</span>';
                }
            }
        } else {
            console.error('Erro API User:', text);
        }

    } catch (error) {
        console.error('Erro fetch user:', error);
    }
}

/* -------------------------------------------------------------------------- */
/*                           2. INFORMAÇÕES DO CARTÃO                         */
/* -------------------------------------------------------------------------- */

async function fetchCardInfo() {
    const tbody = document.getElementById('lista-cartoes-body');
    const noCardMsg = document.getElementById('msg-no-card');
    const table = document.getElementById('tabela-cartoes');

    if (!tbody) return;

    try {
        const response = await fetch('../api/get_meu_cartao.php');
        const text = await response.text();

        console.log('Cartões Response:', text);

        // Separar Status do Conteúdo
        const primeiroPipe = text.indexOf('|');
        const status = text.substring(0, primeiroPipe);
        const dados = text.substring(primeiroPipe + 1);

        if (status === 'SUCCESS') {
            tbody.innerHTML = '';

            if (dados.trim() === "") {
                if (table) table.style.display = 'none';
                if (noCardMsg) noCardMsg.style.display = 'block';
                return;
            }

            const cartoes = dados.split(';');

            cartoes.forEach(cartaoStr => {
                if (cartaoStr.trim() === "") return;

                const parts = cartaoStr.split('|');
                // Formato: NUMERO|ATIVO|DATA|TIPO
                const numCartao = parts[0];
                const isAtivo = parts[1] == '1';
                const dataRaw = parts[2];
                const tipoRaw = parts[3];

                // 1. Formatar Data (YYYY-MM-DD -> DD/MM/YYYY)
                let dataFormatada = dataRaw;
                if (dataRaw && dataRaw !== 'N/A') {
                    const dateObj = new Date(dataRaw);
                    if (!isNaN(dateObj.getTime())) {
                        const dia = String(dateObj.getDate()).padStart(2, '0');
                        const mes = String(dateObj.getMonth() + 1).padStart(2, '0'); // Meses começam em 0
                        const ano = dateObj.getFullYear();
                        dataFormatada = `${dia}/${mes}/${ano}`;
                    }
                }

                // 2. Badge de Estado
                const estadoHtml = isAtivo
                    ? '<span class="badge badge-success" style="background:#dcfce7; color:#166534; padding:4px 8px; border-radius:4px;">Ativo</span>'
                    : '<span class="badge badge-danger" style="background:#fee2e2; color:#991b1b; padding:4px 8px; border-radius:4px;">Inativo</span>';

                // 3. Badge de Tipo (Capitalizar primeira letra)
                const tipoFormatado = tipoRaw.charAt(0).toUpperCase() + tipoRaw.slice(1);
                const tipoHtml = `<span class="badge badge-info" style="background:#e0f2fe; color:#075985; padding:4px 8px; border-radius:4px;">${tipoFormatado}</span>`;

                const row = `
                    <tr>
                        <td>${tipoHtml}</td>
                        <td>${numCartao}</td>
                        <td>${estadoHtml}</td>
                        <td>${dataFormatada}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            if (table) table.style.display = 'table';
            if (noCardMsg) noCardMsg.style.display = 'none';

        } else {
            // Erro ou sem dados
            if (table) table.style.display = 'none';
            if (noCardMsg) {
                noCardMsg.style.display = 'block';
                if (status === 'ERROR' && dados) noCardMsg.textContent = dados;
            }
        }

    } catch (error) {
        console.error('Erro fetch cards:', error);
        tbody.innerHTML = '<tr><td colspan="4" style="color:red; text-align:center;">Erro ao carregar dados.</td></tr>';
    }
}

/* -------------------------------------------------------------------------- */
/*                           3. GESTÃO DE VEÍCULOS                            */
/* -------------------------------------------------------------------------- */

// --- LISTAR VEÍCULOS ---
async function fetchVehicles() {
    const tbody = document.getElementById('lista-veiculos-body');
    const noVeiculosMsg = document.getElementById('msg-no-veiculos');
    const table = document.getElementById('tabela-veiculos');

    if (!tbody) return;

    try {
        const response = await fetch('../api/get_meus_veiculos.php');
        const text = await response.text();

        // Separar Status do Conteúdo
        const primeiroPipe = text.indexOf('|');
        const status = text.substring(0, primeiroPipe);
        const dados = text.substring(primeiroPipe + 1);

        if (status === 'SUCCESS') {
            tbody.innerHTML = '';

            // Se não houver dados
            if (dados.trim() === "") {
                if (table) table.style.display = 'none';
                if (noVeiculosMsg) noVeiculosMsg.style.display = 'block';
                return;
            }

            const veiculos = dados.split(';');

            veiculos.forEach(vStr => {
                if (vStr.trim() === "") return;

                const parts = vStr.split('|');
                // Formato: ID|MARCA|MODELO|MATRICULA|TIPO
                const id = parts[0];
                const marca = parts[1];
                const modelo = parts[2];
                const matricula = parts[3];
                const tipoRaw = parts[4];

                // 1. Lógica do Badge (Carro vs Mota) - Igual ao PHP original
                let tipoHtml = '';
                const nomeTipo = tipoRaw.toLowerCase();

                if (nomeTipo.includes('moto') || nomeTipo.includes('mota')) {
                    tipoHtml = '<span class="badge badge-warning">🛵 Mota</span>';
                } else {
                    tipoHtml = '<span class="badge badge-info">🚗 Carro</span>';
                }

                // 2. Construção da Linha HTML 
                const row = `
                    <tr>
                        <td>${tipoHtml}</td>
                        <td>${marca}</td>
                        <td>${modelo}</td>
                        <td><span class="matricula">${matricula}</span></td>
                        <td style="text-align: center;">
                            <button class="btn-remove" onclick="deleteVehicle(${id})" title="Remover Veículo">
                                🗑️
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            if (table) table.style.display = 'table';
            if (noVeiculosMsg) noVeiculosMsg.style.display = 'none';

        } else {
            if (table) table.style.display = 'none';
            if (noVeiculosMsg) noVeiculosMsg.style.display = 'block';
        }

    } catch (error) {
        console.error('Erro fetch vehicles:', error);
    }
}

// --- ADICIONAR VEÍCULO (AJAX) ---
async function handleAddVehicle(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const btnSubmit = form.querySelector('button[type="submit"]');
    const msgDiv = document.getElementById('msg-form-veiculo');

    try {
        // UI Loading
        btnSubmit.disabled = true;
        const textoOriginal = btnSubmit.textContent;
        btnSubmit.textContent = "A guardar...";

        // Limpar mensagens anteriores
        if (msgDiv) {
            msgDiv.style.display = 'none';
            msgDiv.textContent = '';
            msgDiv.className = '';
        }

        const response = await fetch('../api/adicionar_veiculo.php', {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        console.log('Resposta do Servidor:', text); 

        const parts = text.trim().split('|');

        // LÓGICA MELHORADA DE ERRO
        if (parts[0] === 'SUCCESS') {
            form.reset();
            toggleCarForm();
            fetchVehicles();
            alert("Veículo adicionado com sucesso!");
        } else {
            // Se parts[1] existir, é um erro formatado (ex: "Matrícula já existe").
            // Se NÃO existir, é um erro cru do PHP (ex: "Fatal error..."). Usamos o text todo.
            let mensagemErro = parts[1] ? parts[1] : text;

            // Se a mensagem for muito longa (erro de código), cortamos para não estragar o layout
            if (mensagemErro.length > 200) {
                console.error("Erro detalhado:", mensagemErro);
                mensagemErro = "Erro de sistema. Verifique a consola (F12) para detalhes.";
            }

            if (msgDiv) {
                msgDiv.textContent = mensagemErro;
                msgDiv.style.display = 'block';
                msgDiv.style.color = '#991b1b';
                msgDiv.style.backgroundColor = '#fee2e2';
                msgDiv.style.padding = '10px';
                msgDiv.style.borderRadius = '5px';
                msgDiv.style.marginBottom = '15px';
                msgDiv.style.border = '1px solid #fca5a5';
            } else {
                alert(mensagemErro);
            }
        }

        btnSubmit.textContent = textoOriginal;
        btnSubmit.disabled = false;

    } catch (error) {
        console.error('Erro JS:', error);
        if (msgDiv) {
            msgDiv.textContent = "Erro de conexão ou JavaScript.";
            msgDiv.style.display = 'block';
        }
        btnSubmit.textContent = "Guardar Veículo";
        btnSubmit.disabled = false;
    }
}

// --- REMOVER VEÍCULO ---
async function deleteVehicle(id) {
    if (!confirm('Tem a certeza que deseja remover este veículo?')) return;

    try {
        const formData = new FormData();
        formData.append('id_veiculo', id);

        const response = await fetch('../api/remove_veiculo.php', {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        const parts = text.split('|');

        if (parts[0] === 'SUCCESS') {
            fetchVehicles(); // Atualiza a lista
        } else {
            alert('Erro: ' + (parts[1] || 'Falha ao remover'));
        }

    } catch (error) {
        console.error('Erro delete:', error);
        alert('Erro de conexão ao tentar apagar.');
    }
}

/* -------------------------------------------------------------------------- */
/*                           4. FUNÇÕES UTILITÁRIAS                           */
/* -------------------------------------------------------------------------- */

function toggleCarForm() {
    var form = document.getElementById('form-Veiculos');
    if (form) {
        // Limpar mensagens de erro ao abrir/fechar
        const msgDiv = document.getElementById('msg-form-veiculo');
        if (msgDiv) msgDiv.style.display = 'none';

        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }
}

function formatarMatricula(input) {
    let valor = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

    if (valor.length > 2) {
        valor = valor.substring(0, 2) + '-' + valor.substring(2);
    }

    if (valor.length > 5) {
        valor = valor.substring(0, 5) + '-' + valor.substring(5, 7);
    }

    input.value = valor;
}