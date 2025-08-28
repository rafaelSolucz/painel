<?php
// controller/UsuarioController.php

namespace App\Controller;

use App\Dal\UsuarioDao;
use App\View\UsuarioView;
use App\Model\Usuario;
use App\Util\Functions as Util;

class UsuarioController
{
    public static function manageUsers(?string $action = null): void
    {
        // 1. Segurança: Apenas admins podem acessar
        AuthController::requireLogin();
        if (!AuthController::isAdmin()) {
            header("Location: ?p=dashboards");
            exit;
        }

        $feedback = $_SESSION['feedback'] ?? null;
        unset($_SESSION['feedback']);
        $usuarioParaEditar = null;

        // 2. Lógica de Ações (POST para criar/editar/excluir)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'listar';

            try {
                switch ($action) {
                    case 'criar':
                        $usuario = Usuario::criarUsuario(null, $_POST['email'], $_POST['senha'], $_POST['role']);
                        UsuarioDao::cadastrar($usuario);
                        $_SESSION['feedback'] = ['tipo' => 'sucesso', 'mensagem' => 'Usuário criado com sucesso!'];
                        break;
                    
                    // NOVO CASE PARA EDITAR
                    case 'editar':
                        // O método editar do DAO não altera a senha.
                        // Criamos o objeto com uma senha vazia, pois ela não será usada.
                        $usuario = Usuario::criarUsuario((int)$_POST['id'], $_POST['email'], 'not_changed', $_POST['role']);
                        UsuarioDao::editar($usuario);
                        $_SESSION['feedback'] = ['tipo' => 'sucesso', 'mensagem' => 'Usuário alterado com sucesso!'];
                        break;
                }
            } catch (\Exception $e) {
                $_SESSION['feedback'] = ['tipo' => 'erro', 'mensagem' => $e->getMessage()];
            }

            header("Location: ?p=admin_usuarios");
            exit;
        }

        // Lógica de Ações via GET (excluir ou carregar para edição)
        if ($action === 'excluir' && isset($_GET['id'])) {
            try {
                UsuarioDao::excluir((int)$_GET['id']);
                $_SESSION['feedback'] = ['tipo' => 'sucesso', 'mensagem' => 'Usuário excluído com sucesso!'];
            } catch (\Exception $e) {
                $_SESSION['feedback'] = ['tipo' => 'erro', 'mensagem' => 'Erro ao excluir usuário.'];
            }
            header("Location: ?p=admin_usuarios");
            exit;
        }
        
        // NOVO BLOCO PARA CARREGAR DADOS PARA EDIÇÃO
        if ($action === 'editar' && isset($_GET['id'])) {
            $usuarioParaEditar = UsuarioDao::buscarPorId((int)$_GET['id']);
        }


        // 3. Carregar dados para a View
        $usuarios = UsuarioDao::listar();

        // 4. Chamar a View, passando o usuário a ser editado
        UsuarioView::displayManagementPage($usuarios, $feedback, $usuarioParaEditar);
    }
}