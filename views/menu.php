<?php
$title = 'Cardápio - Rango Express';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>🍽️ Cardápio</h1>
        <p style="color: #FFD700; margin: 10px 0 0 0; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Delícias deliciosas para seu paladar! 🍽️✨
        </p>
    </div>

    <div class="grid">
        <?php foreach ($items as $item): ?>
            <div class="item-card">
                <div class="item-image">
                    <?php if ($item->image): ?>
                        <?php if (filter_var($item->image, FILTER_VALIDATE_URL)): ?>
                            <img src="<?= $item->image ?>" alt="<?= $item->name ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px 15px 0 0;">
                        <?php else: ?>
                            <img src="/public/images/<?= $item->image ?>" alt="<?= $item->name ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px 15px 0 0;">
                        <?php endif; ?>
                    <?php else: ?>
                        <div style="font-size: 4rem; display: flex; align-items: center; justify-content: center; height: 100%;">🍽️</div>
                    <?php endif; ?>
                </div>

                <div class="item-content">
                    <h3 class="item-title" style="font-size: 1.4rem; margin-bottom: 8px;"><?= htmlspecialchars($item->name) ?></h3>
                    <p class="item-description" style="margin-bottom: 15px; line-height: 1.5;"><?= htmlspecialchars($item->description) ?></p>
                    <div class="item-price" style="font-size: 1.6rem; margin-bottom: 15px;">R$ <?= number_format($item->price, 2, ',', '.') ?></div>

                    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <label for="quantity-<?= $item->id ?>" style="font-weight: 600; color: #8B0000; font-size: 0.9rem;">Qtd:</label>
                            <input type="number" id="quantity-<?= $item->id ?>" value="1" min="1" max="10"
                                   style="width: 65px; padding: 8px; border: 2px solid #DAA520; border-radius: 8px; font-size: 14px; text-align: center; background: #fafafa; transition: all 0.3s ease;">
                        </div>
                        <button onclick="addToCart(<?= $item->id ?>)" class="btn btn-success" style="flex: 1; min-width: 140px; padding: 12px 20px; font-size: 14px; font-weight: 600;">
                            🛒 Adicionar
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($items)): ?>
        <div class="empty-menu" style="text-align: center; padding: 60px 30px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; margin: 30px 0; border: 2px dashed #DAA520;">
            <div style="font-size: 4rem; margin-bottom: 20px;">🍽️</div>
            <h3 style="color: #8B0000; margin-bottom: 15px; font-weight: 700;">Cardápio Indisponível</h3>
            <p style="color: #666; margin-bottom: 0; font-size: 1.1rem; line-height: 1.6;">Nenhum item disponível no momento. Volte mais tarde para delícias imperiais!</p>
        </div>
    <?php endif; ?>
</div>

<style>
/* Enhanced Menu Styles */
.item-card {
    position: relative;
    overflow: hidden;
}

.item-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(218,165,32,0.05), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.item-card:hover::before {
    opacity: 1;
}

.item-content {
    position: relative;
    z-index: 2;
}

.item-title {
    position: relative;
}

.item-title::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 3px;
    background: linear-gradient(45deg, #8B0000, #DAA520);
    transition: width 0.3s ease;
}

.item-card:hover .item-title::after {
    width: 100%;
}

input[type="number"] {
    transition: all 0.3s ease;
}

input[type="number"]:focus {
    border-color: #8B0000;
    box-shadow: 0 0 0 3px rgba(139,0,0,0.1);
    transform: scale(1.05);
}

.btn-success {
    position: relative;
    overflow: hidden;
}

.btn-success::before {
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

.btn-success:hover::before {
    width: 300px;
    height: 300px;
}

/* Enhanced Empty State */
.empty-menu {
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

/* Responsive Enhancements */
@media (max-width: 768px) {
    .item-content div:last-child {
        flex-direction: column;
        gap: 15px;
    }

    .item-content div:last-child > div {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .btn-success {
        width: 100% !important;
        min-width: unset !important;
    }

    input[type="number"] {
        width: 80px !important;
    }
}
</style>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
