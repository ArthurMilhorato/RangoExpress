<?php
$title = 'Carrinho - Rango Express';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>🛒 Seu Carrinho</h1>
        <p style="color: #FFD700; margin: 10px 0 0 0; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Itens selecionados para seu pedido! 🍽️
        </p>
    </div>

    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
        <?php $total = 0; ?>
        <div class="cart-items-container" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; padding: 25px; margin-bottom: 30px; border: 2px solid #DAA520;">
            <?php foreach ($_SESSION['cart'] as $itemId => $item): ?>
                <div class="cart-item" id="cart-item-<?= $itemId ?>" style="display: flex; justify-content: space-between; align-items: center; padding: 15px; margin-bottom: 10px; background: white; border-radius: 10px; border: 1px solid #DAA520; transition: all 0.3s ease;">
                    <div style="flex: 1;">
                        <strong style="color: #8B0000; font-size: 1.1rem; display: block; margin-bottom: 10px;"><?= htmlspecialchars($item['name']) ?></strong>
                        <div class="quantity-controls" style="display: flex; align-items: center; gap: 8px; background: #f8f9fa; border-radius: 25px; padding: 5px; border: 2px solid #DAA520;">
                            <button onclick="updateQuantity(<?= $itemId ?>, -1)" class="btn-quantity" style="width: 35px; height: 35px; border-radius: 50%; border: none; background: linear-gradient(45deg, #8B0000, #B22222); color: white; font-weight: bold; font-size: 18px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center;">−</button>
                            <input type="number" id="quantity-<?= $itemId ?>" value="<?= $item['quantity'] ?>"
                                   min="1" max="10" style="width: 50px; text-align: center; padding: 8px; border: none; background: transparent; font-weight: bold; font-size: 16px; color: #8B0000;"
                                   onchange="changeQuantity(<?= $itemId ?>, this.value)">
                            <button onclick="updateQuantity(<?= $itemId ?>, 1)" class="btn-quantity" style="width: 35px; height: 35px; border-radius: 50%; border: none; background: linear-gradient(45deg, #28a745, #20c997); color: white; font-weight: bold; font-size: 18px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center;">+</button>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <strong id="item-total-<?= $itemId ?>" data-price="<?= $item['price'] ?>" style="color: #DAA520; font-size: 1.2rem;">R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></strong>
                        <button onclick="removeFromCart(<?= $itemId ?>)" class="btn-remove" title="Remover item" style="width: 35px; height: 35px; border-radius: 50%; border: none; background: linear-gradient(45deg, #dc3545, #c82333); color: white; font-size: 16px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(220,53,69,0.3);">
                            🗑️
                        </button>
                    </div>
                </div>
                <?php $total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary" style="background: linear-gradient(135deg, #8B0000, #DAA520); color: white; padding: 30px; border-radius: 15px; text-align: center; box-shadow: 0 8px 32px rgba(139,0,0,0.3); margin-bottom: 30px;">
            <h3 style="margin: 0 0 10px 0; font-size: 1.8rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">💰 Resumo do Pedido</h3>
            <div class="total-amount" style="font-size: 2.5rem; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
                Total: R$ <?= number_format($total, 2, ',', '.') ?>
            </div>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 1.1rem;">Pronto para finalizar seu pedido?</p>
        </div>

        <div class="cart-actions" style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="/cardapio" class="btn btn-secondary" style="padding: 15px 30px; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                <span>🍽️</span> Continuar Comprando
            </a>
            <a href="/pagamento" class="btn btn-success" style="padding: 15px 30px; font-size: 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;">
                <span>💳</span> Finalizar Pedido
            </a>
        </div>
    <?php else: ?>
        <div class="empty-cart" style="text-align: center; padding: 80px 40px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; margin: 30px 0; border: 2px dashed #DAA520; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; right: 0; width: 120px; height: 120px; background: linear-gradient(45deg, #8B0000, #DAA520); opacity: 0.05; border-radius: 0 15px 0 60px;"></div>
            <div style="font-size: 5rem; margin-bottom: 20px; position: relative; z-index: 1;">🛒</div>
            <h3 style="color: #8B0000; margin-bottom: 15px; font-weight: 700; font-size: 1.8rem; position: relative; z-index: 1;">Seu Carrinho Está Vazio</h3>
            <p style="color: #666; margin-bottom: 30px; font-size: 1.2rem; line-height: 1.6; position: relative; z-index: 1;">Adicione deliciosos pratos ao seu carrinho e prepare-se para um pedido incrível!</p>
            <a href="/cardapio" class="btn btn-primary" style="padding: 18px 35px; font-size: 18px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 10px; position: relative; z-index: 1;">
                <span>🍔</span> Explorar Cardápio
            </a>
        </div>
    <?php endif; ?>
</div>

<style>
/* Enhanced Cart Styles */
.cart-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-quantity:hover {
    transform: scale(1.1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.btn-remove:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(220,53,69,0.4);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
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

.btn-success {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
    box-shadow: 0 4px 15px rgba(40,167,69,0.3);
}

.btn-success:hover {
    background: linear-gradient(45deg, #20c997, #28a745);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40,167,69,0.4);
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

/* Enhanced Empty Cart Animation */
.empty-cart {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .cart-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .item-controls {
        width: 100%;
        justify-content: space-between;
    }

    .quantity-controls {
        order: 1;
    }

    .item-total {
        order: 2;
        text-align: left;
        min-width: unset;
    }

    .btn-remove {
        order: 3;
    }

    .cart-actions {
        flex-direction: column;
        align-items: center;
    }

    .cart-actions .btn {
        width: 100%;
        justify-content: center;
    }

    .cart-summary {
        padding: 20px;
    }

    .total-amount {
        font-size: 2rem;
    }

    .empty-cart {
        padding: 60px 20px;
    }

    .empty-cart h3 {
        font-size: 1.5rem;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
