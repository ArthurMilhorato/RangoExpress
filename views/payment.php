<?php
$title = 'Pagamento PIX - Rango Express';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>💳 Pagamento via PIX</h1>
        <p style="color: #FFD700; margin: 10px 0 0 0; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Finalize seu pedido com pagamento seguro! 💳💰
        </p>
    </div>

    <div class="payment-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin: 30px 0;">
        <!-- Resumo do Pedido -->
        <div class="order-summary" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; padding: 25px; border: 2px solid #DAA520; position: relative; overflow: hidden;">
            <!-- Decorative element -->
            <div style="position: absolute; top: 0; right: 0; width: 80px; height: 80px; background: linear-gradient(45deg, #8B0000, #DAA520); opacity: 0.1; border-radius: 0 15px 0 40px;"></div>

            <h3 style="color: #8B0000; margin-bottom: 25px; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; gap: 10px; position: relative; z-index: 1;">
                <span style="font-size: 1.8rem;">📋</span> Resumo do Pedido
            </h3>

            <div class="order-items" style="position: relative; z-index: 1;">
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="order-item" style="display: flex; justify-content: space-between; align-items: center; padding: 15px; margin-bottom: 10px; background: white; border-radius: 10px; border: 1px solid #DAA520; transition: all 0.3s ease;">
                        <div style="flex: 1;">
                            <strong style="color: #8B0000; font-size: 1.1rem; display: block; margin-bottom: 5px;"><?= htmlspecialchars($item['name']) ?></strong>
                            <small style="color: #666; background: #f8f9fa; padding: 4px 8px; border-radius: 15px; font-weight: 600;">Qtd: <?= $item['quantity'] ?></small>
                        </div>
                        <div style="text-align: right;">
                            <strong style="color: #DAA520; font-size: 1.2rem;">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-total" style="background: linear-gradient(135deg, #8B0000, #DAA520); color: white; padding: 20px; margin-top: 20px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(139,0,0,0.3); position: relative; z-index: 1;">
                <div style="font-size: 1.1rem; margin-bottom: 5px; opacity: 0.9;">Total do Pedido:</div>
                <div style="font-size: 2rem; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">R$ <?= number_format($total, 2, ',', '.') ?></div>
            </div>
        </div>

        <!-- QR Code PIX -->
        <div class="pix-section" style="text-align: center; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; padding: 25px; border: 2px solid #DAA520; position: relative; overflow: hidden;">
            <!-- Decorative element -->
            <div style="position: absolute; top: 0; left: 0; width: 80px; height: 80px; background: linear-gradient(45deg, #DAA520, #8B0000); opacity: 0.1; border-radius: 0 0 40px 0;"></div>

            <h3 style="color: #8B0000; margin-bottom: 25px; font-size: 1.5rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 10px; position: relative; z-index: 1;">
                <span style="font-size: 1.8rem;">📱</span> Pague com PIX
            </h3>

            <div id="pix-container" style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); border: 2px solid #DAA520; position: relative; z-index: 1;">
                <div id="loading" style="padding: 50px;">
                    <div style="color: #8B0000; font-size: 1.2rem; font-weight: 600; animation: pulse 2s infinite;">⏳ Gerando PIX Real...</div>
                    <div style="margin-top: 15px; color: #666;">Preparando seu código de pagamento</div>
                </div>

                <div id="pix-content" style="display: none;">
                    <!-- QR Code do Mercado Pago -->
                    <div id="qr-code-container" style="margin-bottom: 25px; padding: 15px; background: #f8f9fa; border-radius: 12px; border: 2px solid #DAA520;">
                        <img id="qr-code-image" style="max-width: 200px; border: 3px solid #8B0000; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    </div>

                    <p style="color: #666; margin-bottom: 25px; font-size: 1.1rem; line-height: 1.5;">
                        👑 <strong>Escaneie o QR Code</strong> com seu app do banco ou copie o código PIX abaixo
                    </p>

                    <!-- Código PIX -->
                    <div class="pix-key" style="margin-bottom: 20px;">
                        <strong style="color: #8B0000; font-size: 1.1rem; display: block; margin-bottom: 10px;">🔑 Código PIX:</strong>
                        <textarea id="pix-code" readonly style="width: 100%; height: 80px; font-size: 11px; resize: none; border: 2px solid #DAA520; padding: 10px; border-radius: 8px; background: #fafafa; font-family: monospace;"></textarea>
                        <button onclick="copyPixCode()" class="btn btn-primary" style="margin-top: 12px; padding: 10px 20px; font-size: 14px; font-weight: 600;">
                            📋 Copiar Código PIX
                        </button>
                    </div>

                    <!-- Status do pagamento -->
                    <div id="payment-status" style="margin: 25px 0; padding: 15px; border-radius: 10px; background: linear-gradient(135deg, #fff3cd, #ffeaa7); color: #856404; font-weight: 600; border: 2px solid #DAA520;">
                        ⏳ Aguardando pagamento...
                    </div>
                </div>

                <div id="error-container" style="display: none; color: #dc3545; padding: 25px; background: linear-gradient(135deg, #f8d7da, #f5c6cb); border-radius: 10px; border: 2px solid #dc3545;">
                    ❌ Erro ao gerar PIX. Tente novamente.
                </div>
            </div>
        </div>
    </div>

    <!-- Botões de ação -->
    <div class="payment-actions" style="text-align: center; padding: 30px; border-top: 2px solid #DAA520; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 0 0 15px 15px; margin-top: -15px;">
        <a href="/carrinho" class="btn btn-secondary" style="margin-right: 20px; padding: 15px 30px; font-size: 16px; font-weight: 600;">← Voltar ao Carrinho</a>
        <button onclick="cancelPayment()" class="btn btn-danger" style="padding: 15px 30px; font-size: 16px; font-weight: 600;">❌ Cancelar Pagamento</button>
    </div>

    <!-- Instruções -->
    <div class="payment-instructions" style="background: linear-gradient(135deg, #8B0000, #DAA520); color: white; padding: 30px; border-radius: 15px; margin-top: 30px; box-shadow: 0 8px 32px rgba(139,0,0,0.3);">
        <h4 style="margin-bottom: 20px; font-size: 1.4rem; text-align: center; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">📝 Como Realizar seu Pagamento:</h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 2rem; margin-bottom: 10px;">📱</div>
                <strong>1. Abra o app do seu banco</strong><br>
                <small>Selecione a opção PIX</small>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 2rem; margin-bottom: 10px;">📷</div>
                <strong>2. Escaneie ou cole o código</strong><br>
                <small>Use o QR Code ou chave PIX</small>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 2rem; margin-bottom: 10px;">💰</div>
                <strong>3. Confirme o valor</strong><br>
                <small><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></small>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 2rem; margin-bottom: 10px;">✅</div>
                <strong>4. Finalize e confirme</strong><br>
                <small>Seu pedido estará pronto!</small>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Payment Styles */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.order-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: relative;
    overflow: hidden;
}

.btn::before {
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

.btn:hover::before {
    width: 300px;
    height: 300px;
}

.btn-primary {
    background: linear-gradient(45deg, #8B0000, #DAA520);
    color: white;
    box-shadow: 0 4px 15px rgba(139,0,0,0.3);
}

.btn-primary:hover {
    background: linear-gradient(45deg, #DAA520, #8B0000);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139,0,0,0.4);
    color: white;
}

.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #5a6268);
    color: white;
    box-shadow: 0 4px 15px rgba(108,117,125,0.3);
}

.btn-secondary:hover {
    background: linear-gradient(45deg, #5a6268, #6c757d);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108,117,125,0.4);
    color: white;
}

.btn-danger {
    background: linear-gradient(45deg, #dc3545, #c82333);
    color: white;
    box-shadow: 0 4px 15px rgba(220,53,69,0.3);
}

.btn-danger:hover {
    background: linear-gradient(45deg, #c82333, #dc3545);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220,53,69,0.4);
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .payment-grid {
        grid-template-columns: 1fr !important;
        gap: 30px;
    }

    .payment-instructions div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
    }

    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .payment-actions {
        flex-direction: column;
        gap: 15px;
    }

    .payment-actions .btn {
        width: 100%;
    }

    .pix-section #pix-container {
        padding: 20px !important;
    }

    .qr-code-container img {
        max-width: 150px !important;
    }
}
</style>

<script>
let paymentId = null;
let checkInterval = null;

// Gerar PIX ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de pagamento carregada');
    generatePix();
});

// Gerar PIX via API do Mercado Pago
function generatePix() {
    console.log('Iniciando geração de PIX...');

    fetch('/api/create-pix', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta do servidor');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            paymentId = data.payment_id;
            showPixData(data);
            startPaymentCheck();
        } else {
            console.error('Erro do servidor:', data.error || data.details);
            showError(data.error || 'Erro desconhecido');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showError('Erro de conexão: ' + error.message);
    });
}

// Mostrar dados do PIX
function showPixData(data) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('pix-content').style.display = 'block';

    // QR Code como imagem base64
    document.getElementById('qr-code-image').src = 'data:image/png;base64,' + data.qr_code_base64;

    // Código PIX
    document.getElementById('pix-code').value = data.qr_code;

    // Status inicial
    const statusDiv = document.getElementById('payment-status');
    statusDiv.innerHTML = '⏳ Aguardando pagamento...';
    statusDiv.style.background = 'linear-gradient(135deg, #fff3cd, #ffeaa7)';
    statusDiv.style.color = '#856404';
}

// Mostrar erro
function showError(message) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('error-container').style.display = 'block';

    if (message) {
        document.getElementById('error-container').innerHTML = '❌ ' + message + '<br><button onclick="generatePix()" class="btn btn-primary" style="margin-top: 15px; padding: 12px 25px;">🔄 Tentar Novamente</button>';
    }
}

// Verificar status do pagamento
function startPaymentCheck() {
    checkInterval = setInterval(checkPaymentStatus, 3000); // Verifica a cada 3 segundos
}

function checkPaymentStatus() {
    if (!paymentId) return;

    fetch('/api/check-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        const statusDiv = document.getElementById('payment-status');

        if (data.status === 'approved') {
            statusDiv.innerHTML = '✅ Pagamento aprovado! Preparando seu pedido...';
            statusDiv.style.background = 'linear-gradient(135deg, #d4edda, #a8dfbb)';
            statusDiv.style.color = '#155724';

            clearInterval(checkInterval);
            setTimeout(() => {
                window.location.href = '/checkout';
            }, 2000);
        } else if (data.status === 'rejected') {
            statusDiv.innerHTML = '❌ Pagamento rejeitado';
            statusDiv.style.background = 'linear-gradient(135deg, #f8d7da, #f5c6cb)';
            statusDiv.style.color = '#721c24';
            clearInterval(checkInterval);
        } else {
            statusDiv.innerHTML = '⏳ Aguardando pagamento...';
        }
    })
    .catch(error => {
        console.error('Erro ao verificar pagamento:', error);
    });
}

// Copiar código PIX
function copyPixCode() {
    const pixCode = document.getElementById('pix-code');
    pixCode.select();
    document.execCommand('copy');

    // Visual feedback
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '✅ Copiado!';
    button.style.background = 'linear-gradient(45deg, #28a745, #20c997)';

    setTimeout(() => {
        button.innerHTML = originalText;
        button.style.background = '';
    }, 2000);
}

function cancelPayment() {
    if (confirm('Deseja cancelar o pagamento?')) {
        if (checkInterval) {
            clearInterval(checkInterval);
        }
        window.location.href = '/carrinho';
    }
}

// Limpar interval ao sair da página
window.addEventListener('beforeunload', function() {
    if (checkInterval) {
        clearInterval(checkInterval);
    }
});
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
