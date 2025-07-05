<div class="d-flex-column-wrapper">
    <?php require_once BASE_PATH . "/Templates/header.php"; ?>

    <main class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-flex align-items-center ps-5">
                        <img src="/Assets/logo-checkit.png" class="d-block mx-lg-auto img-fluid" alt="Logo CheckIt" width="500" loading="lazy">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Se registrer</h1>
                            </div>
                            <form class="user" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" id="exampleFirstName"
                                        placeholder="Nickname" name="nickname" value="<?= $nickname ?>">
                                    <?php if (isset($errors['nicknameEmpty'])) { ?>
                                        <div class="invalid-tooltip position-static small-text"><?= $errors['nicknameEmpty'] ?></div>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                                        placeholder="Email Address" name="email" value="<?= $email ?>">
                                    <?php if (isset($errors['emailEmpty'])) { ?>
                                        <div class="invalid-tooltip position-static small-text"><?= $errors['emailEmpty'] ?></div>
                                    <?php } elseif (isset($errors['emailUsed'])) { ?>
                                        <div class="invalid-tooltip position-static small-text"><?= $errors['emailUsed'] ?></div>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-user"
                                        id="exampleInputPassword" placeholder="Password" name="password" value="<?= $password ?>">
                                    <?php if (isset($errors['passwordEmpty'])) { ?>
                                        <div class="invalid-tooltip position-static invalid-tooltip-mdp small-text"><?= $errors['passwordEmpty'] ?></div>
                                    <?php } elseif (isset($errors['passwordLen'])) { ?>
                                        <div class="invalid-tooltip position-static invalid-tooltip-mdp small-text"><?= $errors['passwordLen'] ?></div>
                                    <?php } elseif (isset($errors['passwordInfo'])) { ?>
                                        <div class="invalid-tooltip position-static invalid-tooltip-mdp small-text"><?= $errors['passwordInfo'] ?></div>
                                    <?php } ?>
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block" name="signUp">
                                    Se registrer
                                </button>
                                <hr>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="?controller=user&action=logIn">Vous avez déjà un compte? Se connecter!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require_once BASE_PATH . "/Templates/footer.php" ?>
</div>