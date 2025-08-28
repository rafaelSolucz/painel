<?php
namespace App\View;

class AuthView
{
    public static function displayMessage(?string $msg = null): void
    {
        if ($msg !== null): ?>
            <div class="alert">
                <?= htmlspecialchars($msg) ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif;
    }

    public static function login(?string $msg = null, string $csrfToken): void
    {
?>
        <div class="login-container">
            <div class="login-box">
                <div class="login-logo-container">
                    <img src="./assets/img/principal.png" alt="Logo da Empresa" class="login-logo">
                </div>
                <h1 class="login-title">Acesse sua conta</h1>
                <p class="login-subtitle">Bem-vindo ao Painel Solucz! Insira suas credenciais para continuar.</p>

                <?php self::displayMessage($msg); ?>
                
                <form class="login-form" action="?p=login" method="post">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>
                    <button type="submit" class="login-button">Entrar</button>
                </form>
            </div>
        </div>
<?php
    }
}