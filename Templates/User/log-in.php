<div class="d-flex-column-wrapper">

    <?php require_once BASE_PATH . "/Templates/header.php"; ?>

    <main class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-flex align-items-center ps-5">
                                <img src="/Assets/logo-checkit.png" class="d-block mx-lg-auto img-fluid" alt="Logo CheckIt" width="500" loading="lazy">
                            </div>
                            <div class="col-lg-7">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Se connecter</h1>
                                    </div>
                                    <form class="user" method="POST">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="exampleInputEmail" aria-describedby="emailHelp"
                                                placeholder="Email" name="email" value="<?=$email?>">
                                            <!-- Si il y a des erreurs on affiche le message d'erreur -->
                                            <?php if (isset($errors['emailEmpty'])) { ?>
                                                <div class="invalid-tooltip position-static small-text"><?= $errors['emailEmpty'] ?></div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" name="password">
                                        </div>
                                        <?php if (isset($errors['invalidUser'])) { ?>
                                            <div class="if-form-error d-flex justify-content-center content-text">
                                                <div class="alert alert-danger mt-3 content-text text-center"><?= $errors['invalidUser'] ?></div>
                                            </div>
                                        <?php } ?>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" name="logIn">
                                            Se connecter
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="?controller=user&action=signUp">Créer un nouveau compte !</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <?php require_once BASE_PATH . "/Templates/footer.php" ?>

</div>