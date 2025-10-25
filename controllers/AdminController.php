<?php
require_once 'repositories/ItemRepository.php';
require_once 'repositories/OrderRepository.php';
require_once 'helpers/ImageHelper.php';

class AdminController {
    private $itemRepository;
    private $orderRepository;

    public function __construct() {
        $this->itemRepository = ItemRepository::getInstance();
        $this->orderRepository = new OrderRepository();
    }

    private function checkAdmin() {
        // if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
        //     header('Location: /login');
        //     exit;
        // }
    }

    public function index() {
        $this->checkAdmin();
        $items = $this->itemRepository->findAll();
        $orders = $this->orderRepository->findAll();
        include 'views/admin.php';
    }

    public function createItem() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $imageUrl = $_POST['image_url'] ?? '';
            $image = '';

            if (empty($name) || empty($price)) {
                $_SESSION['error'] = 'Nome e preço são obrigatórios';
                header('Location: /admin');
                return;
            }

            // Processar upload de arquivo se existir
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadResult = ImageHelper::uploadImage($_FILES['image_file']);
                if ($uploadResult['success']) {
                    $image = $uploadResult['filename'];
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: /admin');
                    return;
                }
            }
            // Ou usar URL se fornecida
            elseif (!empty($imageUrl)) {
                if (ImageHelper::validateImageUrl($imageUrl)) {
                    $image = $imageUrl;
                } else {
                    $_SESSION['error'] = 'URL da imagem inválida';
                    header('Location: /admin');
                    return;
                }
            }

            $item = new Item($name, $description, $price, $image);
            if ($this->itemRepository->create($item)) {
                $_SESSION['success'] = 'Item criado com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao criar item';
            }
        }

        header('Location: /admin');
    }

    public function updateItem() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? 0;
            $imageUrl = $_POST['image_url'] ?? '';
            $image = '';

            $item = new Item($name, $description, $price, $image);
            $item->id = $id;

            // Buscar item atual para deletar imagem antiga se necessário
            $currentItem = $this->itemRepository->findById($id);
            $oldImage = $currentItem ? $currentItem->image : '';

            // Processar upload de arquivo se existir
            if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploadResult = ImageHelper::uploadImage($_FILES['image_file']);
                if ($uploadResult['success']) {
                    $image = $uploadResult['filename'];
                    // Deletar imagem antiga se existir e não for URL
                    if (!empty($oldImage) && !filter_var($oldImage, FILTER_VALIDATE_URL)) {
                        ImageHelper::deleteImage($oldImage);
                    }
                } else {
                    $_SESSION['error'] = $uploadResult['message'];
                    header('Location: /admin');
                    return;
                }
            }
            // Ou usar URL se fornecida
            elseif (!empty($imageUrl)) {
                if (ImageHelper::validateImageUrl($imageUrl)) {
                    $image = $imageUrl;
                    // Deletar imagem antiga se existir e não for URL
                    if (!empty($oldImage) && !filter_var($oldImage, FILTER_VALIDATE_URL)) {
                        ImageHelper::deleteImage($oldImage);
                    }
                } else {
                    $_SESSION['error'] = 'URL da imagem inválida';
                    header('Location: /admin');
                    return;
                }
            }
            // Se não foi fornecida nova imagem, manter a atual
            else {
                $image = $oldImage;
            }

            $item->image = $image;

            if ($this->itemRepository->update($item)) {
                $_SESSION['success'] = 'Item atualizado com sucesso!';
            } else {
                $_SESSION['error'] = 'Erro ao atualizar item';
            }
        }

        header('Location: /admin');
    }

    public function deleteItem() {
        $this->checkAdmin();

        $id = $_GET['id'] ?? 0;

        // Buscar item para deletar imagem associada
        $item = $this->itemRepository->findById($id);
        if ($item && !empty($item->image) && !filter_var($item->image, FILTER_VALIDATE_URL)) {
            ImageHelper::deleteImage($item->image);
        }

        if ($this->itemRepository->delete($id)) {
            $_SESSION['success'] = 'Item removido com sucesso!';
        } else {
            $_SESSION['error'] = 'Erro ao remover item';
        }

        header('Location: /admin');
    }

    public function updateOrderStatus() {
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['order_id'] ?? 0;
            $status = $_POST['status'] ?? '';

            if ($this->orderRepository->updateStatus($id, $status)) {
                $_SESSION['success'] = 'Status do pedido atualizado!';
            } else {
                $_SESSION['error'] = 'Erro ao atualizar status';
            }
        }

        header('Location: /admin');
    }

    public function salesReport() {
        $this->checkAdmin();

        $filterType = $_GET['filter_type'] ?? 'today';
        $day = $_GET['day'] ?? date('d');
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');

        $salesReport = $this->orderRepository->getSalesReportByFilter($filterType, $day, $month, $year);
        $topItems = $this->orderRepository->getTopSellingItemsByFilter($filterType, $day, $month, $year);

        include 'views/sales_report.php';
    }
}
?>
