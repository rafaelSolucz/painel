<?php
// view/UsuarioView.php

namespace App\View;

use App\Model\Usuario;

class UsuarioView
{
    /**
     * @param Usuario[] $usuarios
     * @param ?array $feedback
     * @param ?Usuario $usuarioParaEditar O usuário a ser editado (opcional)
     */
    public static function displayManagementPage(array $usuarios, ?array $feedback, ?Usuario $usuarioParaEditar = null): void
    {
        // Inclui a sidebar, mantendo o layout consistente
        $dashboards = []; // A sidebar precisa desta variável, mesmo que vazia
        require_once(__DIR__ . '/templates/sidebar.php');

        // Inclui o conteúdo específico da página de gerenciamento de usuários
        require_once(__DIR__ . '/admin/usuarios.php');
    }
}