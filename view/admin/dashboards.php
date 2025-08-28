<?php // view/admin/dashboards.php ?>
<section class="conteudo">
    
    <?php
        $isEditing = isset($dashboardParaEditar) && $dashboardParaEditar !== null;
        $formAction = $isEditing ? 'editar' : 'criar';
        $formTitle = $isEditing ? 'Editar Dashboard' : 'Adicionar Novo Dashboard';
        $buttonText = $isEditing ? 'Salvar Alterações' : 'Adicionar';
    ?>

    <h1>Gerenciamento de Dashboards</h1>

    <?php if (isset($feedback) && $feedback) : ?>
        <div class="feedback <?= htmlspecialchars($feedback['tipo']) ?>">
            <p><?= htmlspecialchars($feedback['mensagem']) ?></p>
        </div>
    <?php endif; ?>

    <div class="form active">
        <h3><?= $formTitle ?></h3>
        <form action="?p=admin_dashboards" method="POST" style="margin-top: 10px; display: block;">
            <input type="hidden" name="action" value="<?= $formAction ?>">
            <?php if ($isEditing) : ?>
                <input type="hidden" name="id" value="<?= $dashboardParaEditar->getId() ?>">
            <?php endif; ?>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" placeholder="Categoria - Nome" required value="<?= $isEditing ? htmlspecialchars($dashboardParaEditar->getTitulo()) : '' ?>">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="url_iframe">URL do Iframe</label>
                <input type="url" name="url_iframe" required value="<?= $isEditing ? htmlspecialchars($dashboardParaEditar->getUrlIframe()) : '' ?>">
            </div>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label>Permissões de Acesso</label>
                <div>
                    <?php
                        $allRoles = ['admin' => 'Admin', 'planejamento' => 'Planejamento', 'control' => 'Control'];
                        foreach ($allRoles as $roleValue => $roleName) {
                            // Verifica se o role está no array de roles do dashboard em edição
                            $checked = in_array($roleValue, $rolesDoDashboard) ? 'checked' : '';
                            echo "<label style='margin-right: 15px;'><input type='checkbox' name='roles[]' value='$roleValue' $checked> $roleName</label>";
                        }
                    ?>
                </div>
            </div>

            <button type="submit" class="button-form"><?= $buttonText ?></button>
            <?php if ($isEditing) : ?>
                <a href="?p=admin_dashboards" style="margin-left: 10px;">Cancelar Edição</a>
            <?php endif; ?>
        </form>
    </div>

    <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dashboards as $dashboard) : ?>
                <tr>
                    <td><?= $dashboard->getId() ?></td>
                    <td><?= htmlspecialchars($dashboard->getTitulo()) ?></td>
                    <td>
                        <a href="?p=admin_dashboards&a=editar&id=<?= $dashboard->getId() ?>" class="btn-editar">Editar</a>
                        <a href="?p=admin_dashboards&a=excluir&id=<?= $dashboard->getId() ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este dashboard?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</section>
<script src="./assets/js/script.js"></script>