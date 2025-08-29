<?php // view/admin/usuarios.php ?>

<section class="conteudo">
    
    <?php // Define as variáveis para o formulário ser dinâmico ?>
    <?php
        $isEditing = isset($usuarioParaEditar) && $usuarioParaEditar !== null;
        $formAction = $isEditing ? 'editar' : 'criar';
        $formTitle = $isEditing ? 'Editar Usuário' : 'Adicionar Novo Usuário';
        $buttonText = $isEditing ? 'Salvar' : 'Adicionar';
    ?>

    <h1>Gerenciamento de Usuários</h1>

    <?php // Bloco para exibir feedback de sucesso ou erro ?>
    <?php if (isset($feedback) && $feedback) : ?>
        <div class="feedback <?= htmlspecialchars($feedback['tipo']) ?>">
            <p><?= htmlspecialchars($feedback['mensagem']) ?></p>
        </div>
    <?php endif; ?>

    <div class="form active">
        <h3><?= $formTitle ?></h3>
        <form action="?p=admin_usuarios" method="POST" style="margin-top: 10px;">
            <input type="hidden" name="action" value="<?= $formAction ?>">
            <?php if ($isEditing) : ?>
                <input type="hidden" name="id" value="<?= $usuarioParaEditar->getId() ?>">
            <?php endif; ?>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" required value="<?= $isEditing ? htmlspecialchars($usuarioParaEditar->getEmail()) : '' ?>">
            </div>

            <?php // O campo de senha só aparece no formulário de criação ?>
            <?php if (!$isEditing) : ?>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" required>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="role">Tipo</label>
                <select name="role">
                    <?php
                        $roles = ['control' => 'Control', 'planejamento' => 'Planejamento', 'admin' => 'Admin'];
                        foreach ($roles as $roleValue => $roleName) {
                            $selected = ($isEditing && $usuarioParaEditar->getRole() === $roleValue) ? 'selected' : '';
                            echo "<option value=\"$roleValue\" $selected>$roleName</option>";
                        }
                    ?>
                </select>
            </div>
            <button type="submit" class="button-form"><?= $buttonText ?></button>
            <?php if ($isEditing) : ?>
                <a href="?p=admin_usuarios" style="align-self: flex-end; margin-left: 10px;">Cancelar Edição</a>
            <?php endif; ?>
        </form>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?= $usuario->getId() ?></td>
                    <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                    <td><?= htmlspecialchars($usuario->getRole()) ?></td>
                    <td>
                        <a href="?p=admin_usuarios&a=editar&id=<?= $usuario->getId() ?>" class="btn-editar">Editar</a>
                        <a href="?p=admin_usuarios&a=excluir&id=<?= $usuario->getId() ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</section>
<script src="./assets/js/script.js"></script>