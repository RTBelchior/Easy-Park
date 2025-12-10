<style>
    /* --- CORREÇÃO DO ALINHAMENTO GERAL --- */
    footer .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        width: 100%;
        box-sizing: border-box;
    }

    /* --- ESTILOS PRINCIPAIS --- */
    .site-footer {
        margin-top: 80px;
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        padding-top: 50px;
        padding-bottom: 20px;
        font-size: 15px;
        width: 100%;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        margin-bottom: 40px;
    }

    .footer-section h3 {
        font-size: 20px;
        margin-bottom: 20px;
        color: #fff;
        font-weight: bold;
        position: relative;
        display: inline-block;
    }

    .footer-section h3::after {
        content: '';
        display: block;
        width: 40px;
        height: 3px;
        background: #3b82f6;
        margin-top: 8px;
        border-radius: 2px;
    }

    /* --- ALINHAMENTO ESPECÍFICO DA NAVEGAÇÃO (CENTRO) --- */
    .footer-center {
        display: flex;
        flex-direction: column;
        align-items: center; /* Centra horizontalmente */
    }

    /* Centra a linha azul decorativa do título apenas nesta coluna */
    .footer-center h3::after {
        margin-left: auto;
        margin-right: auto;
    }

    /* Garante que a lista de links fica bonita e centrada como um bloco */
    .footer-center .footer-links {
        width: fit-content; /* Ocupa apenas o espaço necessário */
        min-width: 140px;   /* Tamanho mínimo para alinhar bem */
    }

    /* --- LINKS --- */
    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-links a:hover {
        color: white;
        transform: translateX(5px);
    }

    .footer-links a::before {
        content: '›';
        font-weight: bold;
        color: #3b82f6;
    }

    .footer-section p {
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.8);
    }

    /* --- FICHA TÉCNICA --- */
    .info-list {
        list-style: none;
        padding: 0;
    }

    .info-list li {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
        color: rgba(255, 255, 255, 0.8);
        align-items: flex-start;
    }

    .info-icon {
        font-size: 20px;
        min-width: 25px;
        margin-top: -2px;
    }

    .dev-names {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
        display: block;
        border-left: 2px solid #3b82f6;
        padding-left: 10px;
        line-height: 1.4;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
        text-align: center;
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            gap: 30px;
            text-align: center;
        }

        .footer-section h3::after {
            margin: 8px auto 0;
        }

        /* No telemóvel removemos o display flex específico para não conflitar */
        .footer-center {
            display: block;
        }
        
        .footer-center .footer-links {
            width: 100%; /* Volta a ocupar tudo */
        }
        
        .footer-links a, .info-list li {
            justify-content: center;
            text-align: center;
        }
        
        .info-list li {
            flex-direction: column;
            align-items: center;
        }

        .dev-names {
            border-left: none;
            border-top: 1px solid #3b82f6;
            padding-left: 0;
            padding-top: 5px;
        }
    }
</style>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            
            <!-- Coluna 1: Sobre o Projeto -->
            <div class="footer-section">
                <h3>EasyPark</h3>
                <p>
                    Um sistema inteligente de gestão de estacionamento escolar. 
                    Automação, segurança e eficiência para facilitar o dia-a-dia 
                    da nossa comunidade educativa.
                </p>
            </div>

            <!-- Coluna 2: Navegação (AGORA COM CLASSE EXTRA "footer-center") -->
            <div class="footer-section footer-center">
                <h3>Navegação</h3>
                <ul class="footer-links">
                    <li><a href="/Easy-Park/index.php">Página Inicial</a></li>
                    <li><a href="/Easy-Park/paginas/mapa.php">Mapa</a></li>
                    <li><a href="/Easy-Park/paginas/saibaMais.php">Sobre o Projeto</a></li>
                    <li><a href="/Easy-Park/paginas/formulario.php">Sugestões</a></li>
                </ul>
            </div>

            <!-- Coluna 3: Ficha Técnica -->
            <div class="footer-section">
                <h3>Ficha Técnica</h3>
                <ul class="info-list">
                    <li>
                        <span class="info-icon">📍</span>
                        <div>
                            <strong>Instituto Politécnico de Setúbal</strong>
                        </div>
                    </li>
                    <li>
                        <span class="info-icon">🎓</span>
                        <div>
                            <strong>CTeSP</strong><br>
                            Programação Web, Dispositivos e Aplicações Móveis
                        </div>
                    </li>
                    <li>
                        <span class="info-icon">👨‍💻</span>
                        <div>
                            <strong>Equipa de Desenvolvimento:</strong>
                            <span class="dev-names">
                                Matheus Santana • Ricardo Almeida<br>
                                Rodrigo Belchior • Rodrigo Firinca
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> EasyPark - Todos os direitos reservados.</p>
        </div>
    </div>
</footer>