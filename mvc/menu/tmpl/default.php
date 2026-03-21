<div class="container-fluid px-3 px-lg-4 px-xl-5">
    <div class="nav-shell">
        <a class="navbar-brand nav-brand" href="#" data-href="home">
            <span class="nav-brand-mark">P</span>
            <span class="nav-brand-copy">
                <strong class="d-block lh-1">PehliONE</strong>
            </span>
        </a>
        <button class="navbar-toggler nav-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainmenu">
            <div class="nav-collapse-shell ms-auto">
                <ul class="navbar-nav nav-links">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-href="home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-href="logs">Logeintraege</a>
                    </li>
                    <?php if($this->model->isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-href="logout"
                           title="<?=$this->model->getUsername()?> abmelden"
                           aria-label="Abmelden">
                            <span class="bi bi-box-arrow-right me-1" aria-hidden="true"></span>Abmelden
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-href="login">Login</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="nav-utility">
                    <button id="change-theme" class="btn nav-theme-toggle bi bi-sun-fill" type="button" aria-label="Change theme"></button>
                </div>
            </div>
        </div>
    </div>
</div>
