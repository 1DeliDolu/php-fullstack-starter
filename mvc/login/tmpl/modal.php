<div id="modal-<?=$this->modalID?>" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="form-login" class="modal-content" data-action="user.setLogin">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">Willkommen zurück</h5>
                    <div class="small text-body-secondary">Melde dich mit deiner E-Mail-Adresse und deinem Passwort an.</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input id="username" type="email" class="form-control" placeholder="name@beispiel.de" autocomplete="email">
                    <label for="username">E-Mail-Adresse</label>
                </div>
                <div class="form-floating">
                    <input id="pass" type="password" class="form-control" placeholder="Passwort" autocomplete="current-password">
                    <label for="pass">Passwort</label>
                </div>
            </div>
            <div class="modal-footer d-grid gap-2" style="grid-template-columns:1fr;">
                <button type="submit" class="btn btn-success">Anmelden</button>
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal" data-href="register">Konto erstellen</button>
                <button type="reset" class="btn btn-sm btn-link" data-bs-dismiss="modal">Abbrechen</button>
            </div>
        </form>
    </div>
    <script>
        $(document).ready((e) => {
            loadedForm('form-login');
        });
    </script>
</div>
