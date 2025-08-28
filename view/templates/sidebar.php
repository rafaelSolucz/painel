<?php
// view/templates/sidebar.php
?>
<nav class="sidebar close">
    <header>
        <div class="image-text">
            <span class="image">
                <img src="./assets/img/secundaria.png" alt="logo">
            </span>
            <div class="header-text">
                <p class="title">Painel Solucz</p>
            </div>
        </div>

        <i class="bx bx-chevron-right toggle"></i>
    </header>
    <div class="menu-bar">
        <div class="menu">
            <ul class="nav-links">
                <?php
                // Agrupando dashboards por categoria
                $categorias = [];
                // A variável $dashboards virá da View que incluir este arquivo
                foreach ($dashboards as $dashboard) {
                    $partes = explode(' - ', $dashboard->getTitulo(), 2);
                    $categoria = count($partes) > 1 ? trim($partes[0]) : 'Dashboards';
                    $dashName = trim($partes[count($partes) - 1]);
                    if (!isset($categorias[$categoria])) {
                        $categorias[$categoria] = [];
                    }
                    $categorias[$categoria][] = [
                        'id' => $dashboard->getId(),
                        'name' => $dashName,
                        'url' => $dashboard->getUrlIframe()
                    ];
                }

                $activeCategory = $_GET['cat'] ?? null;
                $activeDashboard = $_GET['id'] ?? null;
                $i = 0;
                foreach ($categorias as $categoriaNome => $itens) :
                    $i++;
                    $isCategoryActive = ($activeCategory === str_replace(' ', '', $categoriaNome));
                ?>
                    <li>
                        <div class="submenu <?= $isCategoryActive ? 'active-submenu' : '' ?>">
                            <a href="#">
                                <i class='bx bx-pie-chart-alt-2 icon'></i>
                                <span class="link-name"><?= htmlspecialchars($categoriaNome) ?></span>
                            </a>
                            <i class="bx bxs-chevron-down arrow"></i>
                        </div>
                        <ul class="submenu-itens <?= $isCategoryActive ? 'open' : '' ?>">
                            <?php foreach ($itens as $item) : ?>
                                <li class="<?= ($activeDashboard == $item['id']) ? 'active' : '' ?>">
                                    <a href="?p=dashboards&cat=<?= urlencode(str_replace(' ', '', $categoriaNome)) ?>&id=<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="?p=logout">
                        <i class='bx bx-log-out icon'></i>
                        <span class="link-name">Sair</span>
                    </a>
                </li>
            </ul>
        </div>
        <hr>

        <!-- NOVO BLOCO PARA O MENU ADMIN -->
        <?php  ?>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') : ?>
            <div class="menu">
                <ul class="nav-links">
                    <li>
                        <div class="submenu">
                            <a href="#">
                                <i class='bx bx-cog icon'></i>
                                <span class="link-name">Configuração</span>
                            </a>
                            <i class="bx bxs-chevron-down arrow"></i>
                        </div>
                        <ul class="submenu-itens">
                            <li><a href="?p=admin_usuarios">Usuários</a></li>
                            <li><a href="?p=admin_dashboards">Dashboards</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bottom-content">
            <li class="mode">
                <div class="icon-moon">
                    <i class='bx bx-moon icon moon'></i>
                </div>
                <span class="mode-text link-name">Dark Mode</span>
                <div class="toggle-switch">
                    <span class="switch"></span>
                </div>
            </li>
        </div>
    </div>
</nav>