<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Rango Express</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚀</text></svg>">
    <meta name="description" content="Cadastro - Rango Express - Junte-se ao serviço de entrega rápida">
</head>
<body>
    <div class="auth-container" style="min-height: 100vh; background: linear-gradient(135deg, #DAA520 0%, #8B0000 50%, #FFD700 100%); display: flex; align-items: center; justify-content: center; padding: 20px; position: relative; overflow: hidden;">
        <!-- Decorative background elements -->
        <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 6s ease-in-out infinite;"></div>
        <div style="position: absolute; bottom: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%; animation: float 8s ease-in-out infinite reverse;"></div>
        <div style="position: absolute; top: 50%; left: -30px; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%; animation: float 10s ease-in-out infinite;"></div>

        <div class="auth-card" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-radius: 20px; padding: 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); border: 2px solid rgba(218,165,32,0.3); max-width: 450px; width: 100%; position: relative; z-index: 1;">
            <div style="text-align: center; margin-bottom: 30px;">
                <div style="font-size: 4rem; margin-bottom: 15px; animation: bounce 2s ease-in-out;">👑</div>
                <h1 class="auth-title" style="color: #8B0000; font-size: 2.5rem; font-weight: 700; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">Junte-se ao Reino</h1>
                <p style="color: #666; margin: 10px 0 0 0; font-size: 1.1rem;">Cadastre-se e descubra delícias imperiais!</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error" style="animation: slideInFromTop 0.5s ease-out; background: linear-gradient(135deg, #f8d7da, #f5c6cb); color: #721c24; padding: 15px 20px; border-radius: 10px; border: 1px solid #f5c6cb; margin-bottom: 20px; font-weight: 600;">
                    <span style="font-size: 1.2rem; margin-right: 10px;">❌</span>
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" style="animation: fadeInUp 0.6s ease-out;">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="name" style="display: block; margin-bottom: 8px; font-weight: 600; color: #8B0000; font-size: 1.1rem;">👤 Nome Completo:</label>
                    <input type="text" id="name" name="name" class="form-control" required style="width: 100%; padding: 15px; border: 2px solid #DAA520; border-radius: 10px; font-size: 16px; background: #fafafa; transition: all 0.3s ease; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #8B0000; font-size: 1.1rem;">📧 Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required style="width: 100%; padding: 15px; border: 2px solid #DAA520; border-radius: 10px; font-size: 16px; background: #fafafa; transition: all 0.3s ease; box-sizing: border-box;">
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #8B0000; font-size: 1.1rem;">🔒 Senha:</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6" style="width: 100%; padding: 15px; border: 2px solid #DAA520; border-radius: 10px; font-size: 16px; background: #fafafa; transition: all 0.3s ease; box-sizing: border-box;">
                    <small style="color: #666; font-size: 0.9rem; margin-top: 5px; display: block;">Mínimo 6 caracteres</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 18px; font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; border: none; border-radius: 10px; background: linear-gradient(45deg, #DAA520, #8B0000); color: white; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(218,165,32,0.3); position: relative; overflow: hidden;">
                    <span style="position: relative; z-index: 1;">👑 Entrar no Reino</span>
                </button>
            </form>

            <div class="auth-link" style="text-align: center; margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(218,165,32,0.3);">
                <p style="margin: 0; color: #666; font-size: 1rem;">
                    Já tem conta?
                    <a href="/login" style="color: #8B0000; text-decoration: none; font-weight: 600; transition: all 0.3s ease; position: relative;">
                        <span style="position: relative; z-index: 1;">Faça login aqui</span>
                        <span style="position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: linear-gradient(45deg, #DAA520, #8B0000); transition: width 0.3s ease;"></span>
                    </a>
                </p>
            </div>
        </div>
    </div>

    <style>
    /* Enhanced Auth Styles */
    .auth-container {
        position: relative;
    }

    .auth-card {
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
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

    .form-control {
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #8B0000;
        box-shadow: 0 0 0 3px rgba(139,0,0,0.1);
        transform: translateY(-2px);
        background: white;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #8B0000, #DAA520);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(218,165,32,0.4);
    }

    .btn-primary::before {
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

    .btn-primary:hover::before {
        width: 300px;
        height: 300px;
    }

    .auth-link a:hover span:last-child {
        width: 100%;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .auth-container {
            padding: 10px;
        }

        .auth-card {
            padding: 30px 20px;
            margin: 20px;
        }

        .auth-title {
            font-size: 2rem !important;
        }

        .form-control {
            padding: 12px !important;
        }

        .btn-primary {
            padding: 15px !important;
            font-size: 16px !important;
        }
    }
    </style>

    <script src="/public/js/app.js"></script>
</body>
</html>
