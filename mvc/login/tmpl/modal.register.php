<div id="modal-<?=$this->modalID?>" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="form-register" class="modal-content" data-action="user.setRegister">
            <div class="modal-header">
                <h5 class="modal-title">Registrieren</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-floating mb-3">
                    <input id="username" type="email" class="form-control" placeholder="Benutzername" tabindex="0">
                    <label for="username">Benutzername</label>
                </div>
                <div class="input-group flex-nowrap password">
                    <div class="form-floating mb-0">
                        <input id="pass" type="password" class="form-control" placeholder="Passwort" tabindex="0">
                        <label for="pass">Passwort</label>
                    </div>
                    <button type="button" class="input-group-text bi bi-tools" data-href="getpassword" tabindex="-1"></button>
                </div>
                <!-- 
                -->
                <div class="progress mb-1" style="height:.5rem;" role="progressbar" aria-label="Passwortstärke" aria-valuenow="0" aria-valuemin="0" aria-valuemax="1">
                    <div class="progress-bar"></div>
                </div>

                <div class="form-floating">
                    <input id="pass_repeat" type="password" class="form-control" placeholder="Passwort wiederholen" tabindex="0">
                    <label for="pass_repeat">Passwort wiederholen</label>
                </div>
            </div>
            <div class="modal-footer d-grid gap-2" style="grid-template-columns:1fr;">
                <button type="submit" class="btn btn-success"  tabindex="3">Registieren</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-href="login" tabindex="0">Zurück zur Anmeldung</button>
                <button type="reset" class="btn btn-sm btn-link" data-bs-dismiss="modal" tabindex="0">Abbrechen</button>
            </div>
        </form>
    </div>
    <script>
        $(document).ready((e) => {
            loadedForm('form-register');
        });
    </script>
</div>