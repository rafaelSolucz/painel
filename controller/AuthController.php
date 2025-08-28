<?php
namespace App\Controller;

use App\Util\Functions as Util;
use App\Model\Usuario;
use App\Dal\UsuarioDao;
use App\View\AuthView;

abstract class AuthController
{
    public static ?string $msg = null;

    private static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token_auth'])) {
            $_SESSION['csrf_token_auth'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token_auth'];
    }

    private static function validateCsrfToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token_auth']) || $token !== $_SESSION['csrf_token_auth']) {
            return false;
        }

        unset($_SESSION['csrf_token_auth']);
        return true;
    }

    public static function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["email"])) {
            $email = Util::preparaTexto($_POST["email"]);
            $senha = $_POST["senha"];
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                AuthView::login(self::$msg, self::generateCsrfToken());
                return;
            }

            try {
                $usuario = UsuarioDao::buscarPorEmail($email);
                if ($usuario && password_verify($senha, $usuario->getSenha())) {
                    $_SESSION['user_id'] = $usuario->getId();
                    $_SESSION['user_email'] = $usuario->getEmail();
                    $_SESSION['user_role'] = $usuario->getRole();
                    header("Location: ?p=dashboards");
                    exit;
                }

                self::$msg = "E-mail ou senha incorretos.";
            } catch (\Exception $e) {
                self::$msg = "Erro no login: " . $e->getMessage();
            }
        }
        AuthView::login(self::$msg, self::generateCsrfToken());
        // require_once(__DIR__ . '/../view/auth/login.php'); usado para teste com o autoload-teste
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
        header("Location: ?p=login");
        exit;
    }

    public static function requireLogin(): void
    {
        if (!self::isAuthenticated()) {
            header("Location: ?p=login");
            exit;
        }
    }

    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}