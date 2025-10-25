<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Rango Express' ?></title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚀</text></svg>">
    <meta name="description" content="Rango Express - Comida rápida e deliciosa com entrega eficiente">
    <meta name="keywords" content="restaurante, comida, delivery, cardápio, pedidos">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <a href="/" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 2rem;">🚀</span>
                    <span style="font-weight: 700; font-size: 1.5rem;">Rango Express</span>
                </a>
            </div>
            <nav class="nav">
                <?php if ($_SESSION['is_admin']): ?>
                    <a href="/admin" class="nav-link">⚙️ Admin</a>
                    <a href="/admin/sales-report" class="nav-link">📊 Vendas</a>
                <?php else: ?>
                    <a href="/cardapio" class="nav-link">🍽️ Cardápio</a>
                    <a href="/carrinho" class="nav-link">🛒 Carrinho</a>
                    <a href="/meus-pedidos" class="nav-link">📦 Meus Pedidos</a>
                <?php endif; ?>
                <a href="/logout" class="nav-link logout">🚪 Sair (<?= $_SESSION['user_name'] ?>)</a>
            </nav>
        </div>
    </header>
    <?php endif; ?>

    <main class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="animation: slideInFromTop 0.5s ease-out;">
                <span style="font-size: 1.2rem; margin-right: 10px;">✅</span>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error" style="animation: slideInFromTop 0.5s ease-out;">
                <span style="font-size: 1.2rem; margin-right: 10px;">❌</span>
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <footer class="footer" style="background: linear-gradient(135deg, #8B0000, #DAA520); color: white; padding: 30px 0; margin-top: 50px; text-align: center; box-shadow: 0 -4px 20px rgba(0,0,0,0.2);">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 20px;">
                <div>
                    <h3 style="margin-bottom: 15px; font-size: 1.4rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <span style="font-size: 1.8rem;">🚀</span> Rango Express
                    </h3>
                    <p style="line-height: 1.6; opacity: 0.9;">Comida rápida e deliciosa com entrega eficiente. Qualidade e velocidade para o seu dia a dia.</p>
                </div>
                <div>
                    <h4 style="margin-bottom: 15px; font-size: 1.2rem;">🍽️ Links Úteis</h4>
                    <ul style="list-style: none; padding: 0; line-height: 2;">
                        <li><a href="/cardapio" style="color: white; text-decoration: none; opacity: 0.8; transition: opacity 0.3s;">Cardápio</a></li>
                        <li><a href="/meus-pedidos" style="color: white; text-decoration: none; opacity: 0.8; transition: opacity 0.3s;">Meus Pedidos</a></li>
                        <li><a href="/carrinho" style="color: white; text-decoration: none; opacity: 0.8; transition: opacity 0.3s;">Carrinho</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: 15px; font-size: 1.2rem;">📞 Contato</h4>
                    <p style="margin: 5px 0; opacity: 0.9;">📧 contato@rangodorei.com</p>
                    <p style="margin: 5px 0; opacity: 0.9;">📱 (11) 9999-9999</p>
                    <p style="margin: 5px 0; opacity: 0.9;">🏠 Rua Imperial, 123 - São Paulo</p>
                </div>
            </div>
            <div style="border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px; opacity: 0.8;">
                <p>&copy; 2024 Rango Express. Todos os direitos reservados. | Feito com ❤️ para nossos clientes.</p>
            </div>
        </div>
    </footer>

    <style>
    /* Enhanced Layout Styles */
    .header {
        background: linear-gradient(135deg, #8B0000 0%, #DAA520 100%);
        box-shadow: 0 4px 20px rgba(139,0,0,0.3);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo a {
        transition: transform 0.3s ease;
    }

    .logo a:hover {
        transform: scale(1.05);
    }

    .nav {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        font-weight: 600;
        padding: 10px 15px;
        border-radius: 25px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .nav-link:hover::before {
        width: 200px;
        height: 200px;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.1);
        transform: translateY(-2px);
    }

    .logout {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.3);
    }

    .logout:hover {
        background: rgba(255,255,255,0.2);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #a8dfbb);
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .footer a:hover {
        opacity: 1 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 15px;
            padding: 10px 20px;
        }

        .nav {
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .nav-link {
            padding: 8px 12px;
            font-size: 14px;
        }

        .footer div[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
            text-align: center;
        }

        .alert {
            padding: 12px 15px;
            font-size: 14px;
        }
    }
    </style>

    <script src="/public/js/app.js"></script>
</body>
</html>
