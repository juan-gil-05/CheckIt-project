<?php

use App\Security\Security;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CheckIt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/Styles/sb-admin-2.min.css">
    <link rel="stylesheet" href="/Styles/personalized.css">
</head>

<body>
    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class=" mb-2 mb-md-0">
                <a href="?controller=page&action=home" class="d-inline-flex link-body-emphasis text-decoration-none">
                    <img src="/Assets/logo-checkit.png" alt="Logo CheckIt" width="180">
                </a>
            </div>

            <ul class="nav  mb-2 justify-content-center mb-md-0">
                <li><a href="?controller=page&action=home" class="nav-link px-2 <?= ($_GET['controller'] === 'page') ? "menu-selected" : "" ?> ">Home</a></li>
                <li><a href="?controller=list&action=showLists" class="nav-link px-2 <?= ($_GET['controller'] === 'list') ? "menu-selected" : "" ?> ">Mes listes</a></li>
            </ul>

            <div class=" text-end">
                <?php if (Security::isLogged()) { ?>
                    <a href="?controller=user&action=logOut" class="btn btn-outline-primary me-2">Déconnexion</a>
                <?php } else { ?>
                    <a href="?controller=user&action=logIn" class="btn btn-outline-primary me-2">Connexion</a>
                    <a href="?controller=user&action=signUp" class="btn btn-outline-primary me-2">Insciption</a>
                <?php } ?>
            </div>
        </header>
    </div>