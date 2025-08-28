<?php
// view/dashboard/index.php
?>
<section class="conteudo">
    <?php
    $activeDashboard = $_GET['id'] ?? null;
    $activeCategory = $_GET['cat'] ?? null;

    if ($activeDashboard && $activeCategory) {
        $dashboardId = (int) $activeDashboard;
        $dashboardEncontrado = null;
        // A variável $dashboards virá da View que incluir este arquivo
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