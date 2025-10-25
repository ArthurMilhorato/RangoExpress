<?php
require_once 'repositories/UserRepository.php';

class AuthController {
    private static $instance = null;
    private $userRepository;

    private function __construct() {
        $this->userRepository = new UserRepository();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new AuthController();
        }
        return self::$instance;
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Email e senha são obrigatórios';
                header('Location: /login');
                return;
            }

            $user = $this->userRepository->findByEmail($email);
            if ($user && $this->userRepository->verifyPassword($password, $user->password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['is_admin'] = $user->is_admin;
                
                if ($user->is_admin) {
                    header('Location: /admin');
                } else {
                    header('Location: /cardapio');
                }
                return;
            }

            $_SESSION['error'] = 'Email ou senha inválidos';
            header('Location: /login');
        } else {
            include 'views/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($name) || empty($email) || empty($password)) {
                $_SESSION['error'] = 'Todos os campos são obrigatórios';
                header('Location: /register');
                return;
            }

            $user = new User($name, $email, $password);
            if ($this->userRepository->create($user)) {
                $_SESSION['success'] = 'Cadastro realizado com sucesso! Faça login.';
                header('Location: /login');
            } else {
                $_SESSION['error'] = 'Erro ao cadastrar usuário';
                header('Location: /register');
            }
        } else {
            include 'views/register.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /login');
    }
}
?>