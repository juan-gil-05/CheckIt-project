<div class="d-flex-column-wrapper">
    <?php

    use App\Security\Security;

    require_once BASE_PATH . "/Templates/header.php";
    ?>

    <main>
        <div class="container col-xxl-8 px-4 py-5">
            <div class="row flex-lg-row-reverse align-items-center g-5 py-3">
                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="/Assets/logo-checkit.png" class="d-block mx-lg-auto img-fluid" alt="Logo CheckIt" width="500" loading="lazy">
                </div>
                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Gardez vos listes avec vous !</h1>
                    <p class="lead">Bienvenue sur CheckIt, votre nouvelle plateforme de création de listes de tâches en ligne. Avec CheckIt, vous pouvez facilement créer des listes de choses à faire pour tous les aspects de votre vie. Que vous planifiez votre prochain voyage, que vous organisiez votre travail ou que vous fassiez des courses, CheckIt vous aide à rester organisé et à suivre vos tâches en toute simplicité.</p>
                </div>
            </div>
        </div>

        <div class="container col-xxl-8 px-4 py-5">
            <div class="row text-center">
                <h2 class="mb-5">Découvrez les fonctionnalités principales :</h2>
                <div class="col-md-4 my-2">
                    <div class="card w-100">
                        <div class="card-header">
                            <i class="bi text-primary bi-card-checklist"></i>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Créer un nombre illimité de listes</h3>
                            <?php if (Security::isLogged()) { ?>
                                <a href="?controller=list&action=saveOrUpdateList" class="btn btn-primary">Créer une liste</a>
                            <?php } else { ?>
                                <a href="?controller=user&action=signUp" class="btn btn-primary">S'inscrire</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 my-2">
                    <div class="card w-100">
                        <div class="card-header">
                            <i class="bi text-primary bi-tags-fill"></i>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Classer les listes par catégories</h3>
                            <?php if (Security::isLogged()) { ?>
                                <a href="?controller=list&action=saveOrUpdateList" class="btn btn-primary">Créer une liste</a>
                            <?php } else { ?>
                                <a href="?controller=user&action=signUp" class="btn btn-primary">S'inscrire</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 my-2">
                    <div class="card w-100">
                        <div class="card-header">
                            <i class="bi text-primary bi-search"></i>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Ajouter des tags aux items de vos listes</h3>
                            <?php if (Security::isLogged()) { ?>
                                <a href="?controller=list&action=saveOrUpdateList" class="btn btn-primary">Créer une liste</a>
                            <?php } else { ?>
                                <a href="?controller=user&action=signUp" class="btn btn-primary">S'inscrire</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>


    <?php require_once BASE_PATH . "/Templates/footer.php" ?>
</div>