<?php
$title = 'Relatório de Vendas - Rango Express';
ob_start();
?>

<div class="card">
    <div class="card-header">
        <h1>📊 Relatório de Vendas</h1>
        <p style="color: #FFD700; margin: 10px 0 0 0; font-size: 1.1rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">
            Insights para o seu negócio! 📈
        </p>
    </div>

    <!-- Filtros de Período -->
    <div class="filters-section" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 25px; border-radius: 15px; margin-bottom: 30px; border: 2px solid #DAA520; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: linear-gradient(45deg, #8B0000, #DAA520); opacity: 0.05; border-radius: 0 15px 0 50px;"></div>
        <h3 style="color: #8B0000; margin-bottom: 20px; font-size: 1.4rem; position: relative; z-index: 1;">🔍 Filtrar por Período</h3>
        <form method="GET" action="/admin/sales-report" style="display: flex; gap: 20px; align-items: end; margin-top: 15px; flex-wrap: wrap;">
            <div class="form-group" style="min-width: 150px;">
                <label for="filter_type" style="display: block; margin-bottom: 8px; font-weight: 600; color: #8B0000;">Tipo de Filtro:</label>
                <select id="filter_type" name="filter_type" onchange="toggleFilters()" style="width: 100%; padding: 10px; border: 2px solid #DAA520; border-radius: 8px; background: white; font-weight: 600; color: #8B0000;">
                    <option value="today" <?= (!isset($_GET['filter_type']) || $_GET['filter_type'] === 'today') ? 'selected' : '' ?>>📅 Hoje</option>
                    <option value="day" <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'day') ? 'selected' : '' ?>>📆 Dia Específico</option>
                    <option value="month" <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'month') ? 'selected' : '' ?>>📊 Mês Específico</option>
                    <option value="all" <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'all') ? 'selected' : '' ?>>🌍 Todo o Período</option>
                </select>
            </div>

            <div id="day_filter" style="display: <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'day') ? 'block' : 'none' ?>; min-width: 100px;">
                <label for="day" style="display: block; margin-bottom: 8px; font-weight: 600; color: #8B0000;">Dia:</label>
                <select id="day" name="day" style="width: 100%; padding: 10px; border: 2px solid #DAA520; border-radius: 8px; background: white;">
                    <?php
                    $selectedDay = $_GET['day'] ?? date('d');
                    $selectedMonth = $_GET['month'] ?? date('m');
                    $selectedYear = $_GET['year'] ?? date('Y');
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
                    for ($i = 1; $i <= $daysInMonth; $i++): ?>
                        <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $selectedDay == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                            <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div id="month_filter" style="display: <?= (isset($_GET['filter_type']) && $_GET['filter_type'] === 'month') ? 'block' : 'none' ?>; min-width: 150px;">
                <label for="month_select" style="display: block; margin-bottom: 8px; font-weight: 600; color: #8B0000;">Mês:</label>
                <select id="month_select" name="month" style="width: 100%; padding: 10px; border: 2px solid #DAA520; border-radius: 8px; background: white;">
                    <?php
                    $months = [
                        '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
                        '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
                        '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
                    ];
                    foreach ($months as $num => $name): ?>
                        <option value="<?= $num ?>" <?= $selectedMonth == $num ? 'selected' : '' ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="year_filter" style="display: <?= (isset($_GET['filter_type']) && in_array($_GET['filter_type'], ['day', 'month'])) ? 'block' : 'none' ?>; min-width: 100px;">
                <label for="year" style="display: block; margin-bottom: 8px; font-weight: 600; color: #8B0000;">Ano:</label>
                <select id="year" name="year" style="width: 100%; padding: 10px; border: 2px solid #DAA520; border-radius: 8px; background: white;">
                    <?php
                    $currentYear = date('Y');
                    for ($i = $currentYear; $i >= $currentYear - 5; $i--): ?>
                        <option value="<?= $i ?>" <?= $selectedYear == $i ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 10px 20px; font-weight: 600;">🔍 Filtrar</button>
        </form>

        <div class="current-period" style="margin-top: 15px; padding: 15px; background: rgba(255,255,255,0.8); border-radius: 10px; border: 1px solid #DAA520;">
            <p style="margin: 0; color: #8B0000; font-weight: 600;">
                📅 <strong>Período atual:</strong>
                <?php
                $filterType = $_GET['filter_type'] ?? 'today';
                switch ($filterType) {
                    case 'today':
                        echo 'Hoje (' . date('d/m/Y') . ')';
                        break;
                    case 'day':
                        echo 'Dia ' . $_GET['day'] . '/' . $_GET['month'] . '/' . $_GET['year'];
                        break;
                    case 'month':
                        echo 'Mês de ' . $months[$_GET['month']] . ' de ' . $_GET['year'];
                        break;
                    case 'all':
                        echo 'Todo o período disponível';
                        break;
                }
                ?>
            </p>
        </div>
    </div>

    <!-- Resumo de Vendas -->
    <?php if ($salesReport): ?>
        <div class="metrics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 40px;">
            <div class="metric-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 8px 32px rgba(102,126,234,0.3); transition: all 0.3s ease;">
                <div style="font-size: 3rem; margin-bottom: 10px;">📦</div>
                <h3 style="margin: 0; font-size: 2.5em; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><?= $salesReport['total_orders'] ?? 0 ?></h3>
                <p style="margin: 10px 0 0 0; font-size: 1.1rem; font-weight: 600;">Total de Pedidos</p>
            </div>
            <div class="metric-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 8px 32px rgba(240,147,251,0.3); transition: all 0.3s ease;">
                <div style="font-size: 3rem; margin-bottom: 10px;">💰</div>
                <h3 style="margin: 0; font-size: 2.5em; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">R$ <?= number_format($salesReport['total_revenue'] ?? 0, 2, ',', '.') ?></h3>
                <p style="margin: 10px 0 0 0; font-size: 1.1rem; font-weight: 600;">Receita Total</p>
            </div>
            <div class="metric-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 8px 32px rgba(79,172,254,0.3); transition: all 0.3s ease;">
                <div style="font-size: 3rem; margin-bottom: 10px;">👨‍🍳</div>
                <h3 style="margin: 0; font-size: 2.5em; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><?php
                    require_once 'repositories/OrderRepository.php';
                    $orderRepo = new OrderRepository();
                    echo $orderRepo->getOrdersByStatus('processado');
                ?></h3>
                <p style="margin: 10px 0 0 0; font-size: 1.1rem; font-weight: 600;">Em Processamento</p>
            </div>
            <div class="metric-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 8px 32px rgba(67,233,123,0.3); transition: all 0.3s ease;">
                <div style="font-size: 3rem; margin-bottom: 10px;">✅</div>
                <h3 style="margin: 0; font-size: 2.5em; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);"><?php
                    require_once 'repositories/OrderRepository.php';
                    $orderRepo = new OrderRepository();
                    echo $orderRepo->getOrdersByStatus('entregue');
                ?></h3>
                <p style="margin: 10px 0 0 0; font-size: 1.1rem; font-weight: 600;">Entregues</p>
            </div>
            <div class="metric-card" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: white; padding: 25px; border-radius: 15px; text-align: center; box-shadow: 0 8px 32px rgba(255,154,158,0.3); transition: all 0.3s ease;">
                <div style="font-size: 3rem; margin-bottom: 10px;">🏆</div>
                <h3 style="margin: 0; font-size: 2.5em; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">R$ <?php
                    require_once 'repositories/OrderRepository.php';
                    $orderRepo = new OrderRepository();
                    $totalRevenue = $orderRepo->getTotalRevenue();
                    echo number_format($totalRevenue, 2, ',', '.');
                ?></h3>
                <p style="margin: 10px 0 0 0; font-size: 1.1rem; font-weight: 600;">Valor Total do Programa</p>
            </div>
        </div>
    <?php else: ?>
        <div class="no-data-alert" style="background: linear-gradient(135deg, #fff3cd, #ffeaa7); color: #856404; padding: 25px; border-radius: 15px; margin-bottom: 30px; border: 2px solid #856404; text-align: center;">
            <div style="font-size: 3rem; margin-bottom: 15px;">⚠️</div>
            <strong style="font-size: 1.2rem;">Nenhum dado encontrado</strong> para o período selecionado.
            <p style="margin: 10px 0 0 0;">Tente ajustar os filtros para ver mais informações.</p>
        </div>
    <?php endif; ?>

    <!-- Produtos Mais Vendidos -->
    <div class="top-products-section">
        <h2 style="color: #8B0000; margin: 30px 0 20px 0; font-size: 1.8rem; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 2rem;">🏆</span> Produtos Mais Vendidos
        </h2>

        <?php if (!empty($topItems)): ?>
            <div class="top-products-table" style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 8px 32px rgba(0,0,0,0.1); border: 2px solid #DAA520;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Produto</th>
                            <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Preço Unitário</th>
                            <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Quantidade Vendida</th>
                            <th style="background: linear-gradient(45deg, #8B0000, #DAA520); color: white; font-weight: 700; font-size: 1.1rem;">Receita Gerada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topItems as $item): ?>
                            <tr style="transition: all 0.2s ease;">
                                <td style="font-weight: 600; color: #8B0000; font-size: 1.1rem;"><?= htmlspecialchars($item['name']) ?></td>
                                <td style="font-weight: 600; color: #DAA520;">R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                                <td><span class="quantity-badge" style="background: linear-gradient(45deg, #28a745, #20c997); color: white; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 1rem;"><?= $item['total_quantity'] ?></span></td>
                                <td><strong style="color: #DAA520; font-size: 1.2rem;">R$ <?= number_format($item['total_revenue'], 2, ',', '.') ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-products-message" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); padding: 30px; border-radius: 15px; text-align: center; border: 2px dashed #DAA520;">
                <div style="font-size: 3rem; margin-bottom: 15px;">🍽️</div>
                <p style="margin: 0; color: #666; font-size: 1.1rem; line-height: 1.6;">Nenhum produto vendido neste período.<br>Que tal promover alguns itens do cardápio?</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Botão Voltar -->
    <div class="back-to-admin" style="text-align: center; margin-top: 40px; padding: 30px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 15px; border: 2px solid #DAA520;">
        <a href="/admin" class="btn btn-secondary" style="padding: 15px 30px; font-size: 18px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">← Voltar ao Painel Admin</a>
    </div>
</div>

<style>
/* Enhanced Sales Report Styles */
.filters-section {
    position: relative;
}

.metrics-grid {
    margin-bottom: 40px;
}

.metric-card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.metric-card h3 {
    margin: 15px 0 5px 0;
}

.top-products-table {
    border: 2px solid #DAA520;
}

.table tr:hover {
    background: linear-gradient(135deg, #fff8dc, #f5f5dc) !important;
    transform: scale(1.01);
}

.quantity-badge {
    display: inline-block;
    box-shadow: 0 2px 8px rgba(40,167,69,0.3);
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
    .metrics-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .filters-section form {
        flex-direction: column;
        align-items: stretch;
    }

    .filters-section .form-group {
        min-width: unset;
    }

    .table {
        font-size: 14px;
    }

    .table th,
    .table td {
        padding: 10px 8px;
    }

    .metric-card {
        padding: 20px;
    }

    .metric-card h3 {
        font-size: 2rem;
    }

    .btn {
        width: 100%;
        padding: 15px;
        font-size: 16px;
    }
}
</style>

<script>
function toggleFilters() {
    const filterType = document.getElementById('filter_type').value;
    const dayFilter = document.getElementById('day_filter');
    const monthFilter = document.getElementById('month_filter');
    const yearFilter = document.getElementById('year_filter');

    // Esconde todos os filtros primeiro
    dayFilter.style.display = 'none';
    monthFilter.style.display = 'none';
    yearFilter.style.display = 'none';

    // Mostra apenas os filtros relevantes
    if (filterType === 'day') {
        dayFilter.style.display = 'block';
        yearFilter.style.display = 'block';
    } else if (filterType === 'month') {
        monthFilter.style.display = 'block';
        yearFilter.style.display = 'block';
    }
}
</script>

<?php
$content = ob_get_clean();
include 'layout.php';
?>
