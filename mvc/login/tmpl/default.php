<form id="form-login" data-action="login.checkCreds">
    <fieldset>
        <div class="form-floating mb-3">
            <input id="username" type="email" class="form-control" placeholder="name@beispiel.de" autocomplete="email">
            <label for="username">E-Mail-Adresse</label>
        </div>
        <div class="form-floating">
            <input id="pass" type="password" class="form-control" placeholder="Passwort" autocomplete="current-password">
            <label for="pass">Passwort</label>
        </div>
    </fieldset>
    <fieldset>
        <button type="submit" class="btn btn-success">Anmelden</button>
        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Konto erstellen</button>
        <button type="reset" class="btn btn-link" data-bs-dismiss="modal">Abbrechen</button>
    </fieldset>
</form>
