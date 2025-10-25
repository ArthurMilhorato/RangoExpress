<?php
$title = 'Meus Pedidos - Rango Express';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>📦 Meus Pedidos</h1>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-orders" style="text-align: center; padding: 60px 30px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; margin: 30px 0; border: 2px dashed #DAA520;">
            <div style="font-size: 4rem; margin-bottom: 20px;">🍽️</div>
            <h3 style="color: #8B0000; margin-bottom: 15px; font-weight: 700;">Nenhum pedido encontrado</h3>
            <p style="color: #666; margin-bottom: 25px; font-size: 1.1rem; line-height: 1.6;">Você ainda não fez nenhum pedido. Que tal explorar nosso delicioso cardápio?</p>
            <a href="/cardapio" class="btn btn-primary" style="padding: 15px 30px; font-size: 18px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">🍔 Ver Cardápio</a>
        </div>
    <?php else: ?>
        <div class="orders-grid" style="display: grid; gap: 25px;">
            <?php foreach ($orders as $order): ?>
                <div class="order-card" style="border: 2px solid #e9ecef; border-radius: 15px; padding: 25px; background: white; box-shadow: 0 8px 32px rgba(0,0,0,0.1); transition: all 0.3s ease; position: relative; overflow: hidden;">
                    <!-- Decorative element -->
                    <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(45deg, #8B0000, #DAA520); opacity: 0.05; border-radius: 0 15px 0 50px;"></div>

                    <div class="order-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #DAA520; position: relative; z-index: 1;">
                        <div>
                            <h3 style="margin: 0; color: #8B0000; font-size: 1.5rem; font-weight: 700;">Pedido #<?= $order->id ?></h3>
                            <small style="color: #666; font-size: 1rem; display: block; margin-top: 5px;">
                                📅 <?= date('d/m/Y \à\s H:i', strtotime($order->created_at)) ?>
                            </small>
                        </div>
                        <div class="order-status" style="text-align: right;">
                            <div style="font-weight: bold; font-size: 16px; margin-bottom: 8px;">
                                <?php
                                $statusConfig = [
                                    'pendente' => ['color' => '#856404', 'bg' => 'linear-gradient(45deg, #fff3cd, #ffeaa7)', 'icon' => '⏳', 'text' => 'Pendente'],
                                    'processado' => ['color' => '#004085', 'bg' => 'linear-gradient(45deg, #cce5ff, #99d6ff)', 'icon' => '👨‍🍳', 'text' => 'Processando'],
                                    'entregue' => ['color' => '#155724', 'bg' => 'linear-gradient(45deg, #d4edda, #a8dfbb)', 'icon' => '✅', 'text' => 'Entregue']
                                ];
                                $status = $order->status;
                                $config = $statusConfig[$status] ?? ['color' => '#383d41', 'bg' => 'linear-gradient(45deg, #e2e3e5, #d6d8db)', 'icon' => '❓', 'text' => ucfirst($status)];
                                ?>
                                <span class="status-badge" style="background: <?= $config['bg'] ?>; color: <?= $config['color'] ?>; padding: 8px 16px; border-radius: 25px; font-size: 14px; font-weight: 600; border: 2px solid <?= $config['color'] ?>; display: inline-flex; align-items: center; gap: 5px;">
                                    <span style="font-size: 16px;"><?= $config['icon'] ?></span>
                                    <?= $config['text'] ?>
                                </span>
                            </div>
                            <div style="font-size: 22px; font-weight: 700; color: #DAA520; text-shadow: 1px 1px 2px rgba(0,0,0,0.1);">
                                R$ <?= number_format($order->total, 2, ',', '.') ?>
                            </div>
                        </div>
                    </div>

                    <div class="order-items">
                        <h4 style="margin: 20px 0 15px 0; color: #8B0000; font-size: 1.3rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 20px;">🛒</span> Itens do Pedido
                        </h4>
                        <div class="items-container" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 20px; border-radius: 12px; border: 1px solid #DAA520;">
                            <?php
                            // Buscar itens do pedido
                            require_once 'repositories/OrderRepository.php';
                            $orderRepo = new OrderRepository();
                            $orderItems = $orderRepo->getOrderItems($order->id);

                            if (!empty($orderItems)): ?>
                                <?php foreach ($orderItems as $item): ?>
                                    <div class="order-item" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid rgba(218,165,32,0.3); transition: all 0.2s ease;">
                                        <div style="flex: 1;">
                                            <strong style="color: #8B0000; font-size: 1.1rem; display: block; margin-bottom: 4px;"><?= htmlspecialchars($item['name']) ?></strong>
                                            <small style="color: #666; font-size: 0.9rem;">
                                                R$ <?= number_format($item['price'], 2, ',', '.') ?> cada
                                            </small>
                                        </div>
                                        <div style="text-align: right;">
                                            <span class="quantity-badge" style="background: linear-gradient(45deg, #DAA520, #FFD700); color: #8B0000; padding: 4px 12px; border-radius: 15px; font-size: 14px; font-weight: 600; margin-bottom: 4px; display: inline-block;">
                                                <?= $item['quantity'] ?>x
                                            </span>
                                            <div style="font-weight: 700; color: #8B0000; font-size: 1.1rem;">
                                                R$ <?= number_format($item['quantity'] * $item['price'], 2, ',', '.') ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="margin: 0; color: #6c757d; font-style: italic; text-align: center; padding: 20px;">Itens não disponíveis</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="order-footer" style="margin-top: 20px; padding-top: 15px; border-top: 2px solid #DAA520; text-align: center; position: relative; z-index: 1;">
                        <?php if ($order->status === 'pendente'): ?>
                            <div class="status-message" style="background: linear-gradient(45deg, #fff3cd, #ffeaa7); color: #856404; padding: 15px; border-radius: 10px; border: 2px solid #856404; font-weight: 600; font-size: 1.1rem;">
                                ⏳ Aguardando processamento pela cantina
                            </div>
                        <?php elseif ($order->status === 'processado'): ?>
                            <div class="status-message" style="background: linear-gradient(45deg, #cce5ff, #99d6ff); color: #004085; padding: 15px; border-radius: 10px; border: 2px solid #004085; font-weight: 600; font-size: 1.1rem;">
                                👨‍🍳 Seu pedido está sendo preparado com todo carinho!
                            </div>
                        <?php elseif ($order->status === 'entregue'): ?>
                            <div class="status-message" style="background: linear-gradient(45deg, #d4edda, #a8dfbb); color: #155724; padding: 15px; border-radius: 10px; border: 2px solid #155724; font-weight: 600; font-size: 1.1rem;">
                                ✅ Pedido entregue com sucesso! Bom apetite! 🍽️
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="back-to-menu" style="text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; border: 2px solid #DAA520;">
        <a href="/cardapio" class="btn btn-secondary" style="padding: 15px 30px; font-size: 18px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">← Voltar ao Cardápio</a>
    </div>
</div>

<style>
/* Enhanced Order Cards */
.order-card:hover {
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    transform: translateY(-5px);
}

.order-item:hover {
    background: rgba(218,165,32,0.05);
    border-radius: 8px;
    padding-left: 8px;
    padding-right: 8px;
    margin: 0 -8px;
}

/* Enhanced Status Messages */
.status-message {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Enhanced Empty State */
.empty-orders {
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Enhanced Buttons */
.btn {
    display: inline-block;
    padding: 12px 24px;
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
    background: linear-gradient(45deg, #8B0000, #B22222);
    color: white;
    box-shadow: 0 4px 15px rgba(139,0,0,0.3);
}

.btn-primary:hover {
    background: linear-gradient(45deg, #B22222, #8B0000);
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

/* Responsive Design */
@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .order-status {
        text-align: left;
        width: 100%;
    }

    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .items-container {
        padding: 15px;
    }

    .status-message {
        font-size: 1rem;
        padding: 12px;
    }

    .empty-orders {
        padding: 40px 20px;
    }

    .empty-orders h3 {
        font-size: 1.5rem;
    }

    .back-to-menu {
        padding: 20px;
    }

    .btn {
        width: 100%;
        padding: 15px;
        font-size: 16px;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
