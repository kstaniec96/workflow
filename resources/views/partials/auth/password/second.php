<div class="auth-title">
    <h3>Zresetuj hasło</h3>
    <span>Podaj nowe hasło do swojego konta</span>
</div>

<form class="form" method="POST" autocapitalize="off" autocomplete="off" id="form-second-password" action="/auth/password">
    <input type="hidden" value="<?= load('request')->query()->get('token')->value ?>" id="token" name="token">

    <div class="form-prepend-group form-group">
        <div class="form-prepend">
            <span class="material-icons-outlined">lock</span>
        </div>

        <input type="password" name="password" placeholder="Hasło" id="password" class="form-control required">
    </div>

    <div class="form-prepend-group form-group">
        <div class="form-prepend">
            <span class="material-icons-outlined">lock</span>
        </div>

        <input type="password" name="password-confirm" placeholder="Potwierdź hasło" id="password-confirm" class="form-control required">
    </div>

    <button type="submit" class="custom-button color-white bg-color-black">
        <span>Zresetuj hasło</span>
    </button>
</form>