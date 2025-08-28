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
                foreach ($dashboards as $dashboard) {
                    // Exemplo: `Gerencial - Relatório de Vendas`
                    // O nome da categoria seria 'Gerencial'
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

<section class="conteudo">
    <?php
    if ($activeDashboard && $activeCategory) {
        $dashboardId = (int) $activeDashboard;
        $dashboardEncontrado = null;
        foreach ($dashboards as $d) {
            if ($d->getId() === $dashboardId) {
                $dashboardEncontrado = $d;
                break;
            }
        }
        if ($dashboardEncontrado) {
            $url = htmlspecialchars($dashboardEncontrado->getUrlIframe());
    ?>
            <iframe class="dashboard-iframe" src="<?= $url ?>" frameborder="0" allowFullScreen="true"></iframe>
    <?php
        } else {
    ?>
            <h1>Dashboard não encontrado</h1>
            <p>O dashboard selecionado não está disponível ou você não tem permissão para acessá-lo.</p>
    <?php
        }
    } else {
    ?>
        <h1>Bem-vindo ao Painel Solucz</h1>
        <p>Selecione um dashboard no menu lateral para visualizar.</p>
    <?php
    }
    ?>
</section>

<script src="./assets/js/script.js"></script>