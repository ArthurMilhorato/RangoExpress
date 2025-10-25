<?php
require_once 'config/mercadopago.php';
require_once 'repositories/UserRepository.php';

class PaymentController {
    private static $instance = null;
    private $mercadoPago;
    private $userRepository;

    private function __construct() {
        $this->mercadoPago = MercadoPago::getInstance();
        $this->userRepository = new UserRepository();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new PaymentController();
        }
        return self::$instance;
    }
    
    public function createPix() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
            echo json_encode(['error' => 'Carrinho vazio']);
            return;
        }
        
        $total = 0;
        $description = 'Pedido Rango Express - ';
        $items = [];
        
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
            $items[] = $item['name'] . ' (x' . $item['quantity'] . ')';
        }
        
        $description .= implode(', ', $items);
        
        // Buscar email do usuário
        $user = $this->userRepository->findById($_SESSION['user_id']);
        $payer_email = $user ? $user->email : 'cliente@cotemig.com';
        
        // Criar pagamento PIX via API do Mercado Pago
        $result = $this->mercadoPago->createPixPayment($total, $description, $payer_email);
        
        if ($result['status_code'] === 201 && isset($result['response']['id'])) {
            $payment = $result['response'];
            $_SESSION['payment_id'] = $payment['id'];
            
            // Verificar se tem dados do PIX
            if (isset($payment['point_of_interaction']['transaction_data'])) {
                $pix_data = $payment['point_of_interaction']['transaction_data'];
                
                echo json_encode([
                    'success' => true,
                    'payment_id' => $payment['id'],
                    'qr_code' => $pix_data['qr_code'] ?? '',
                    'qr_code_base64' => $pix_data['qr_code_base64'] ?? '',
                    'amount' => $payment['transaction_amount']
                ]);
            } else {
                echo json_encode([
                    'error' => 'Dados do PIX não disponíveis',
                    'details' => $payment
                ]);
            }
        } else {
            echo json_encode([
                'error' => 'Erro ao criar pagamento PIX',
                'details' => $result['response'] ?? 'Erro desconhecido',
                'status_code' => $result['status_code']
            ]);
        }
    }
    

    
    public function checkPayment() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['payment_id'])) {
            echo json_encode(['error' => 'Pagamento não encontrado']);
            return;
        }
        
        $payment_id = $_SESSION['payment_id'];
        
        // Verificar via API do Mercado Pago
        $result = $this->mercadoPago->getPaymentStatus($payment_id);
        
        if ($result['status_code'] === 200 && isset($result['response']['status'])) {
            $payment = $result['response'];
            echo json_encode([
                'status' => $payment['status'],
                'status_detail' => $payment['status_detail'] ?? ''
            ]);
        } else {
            echo json_encode([
                'error' => 'Erro ao verificar pagamento',
                'details' => $result['response'] ?? 'Erro desconhecido'
            ]);
        }
    }
    

}
?>