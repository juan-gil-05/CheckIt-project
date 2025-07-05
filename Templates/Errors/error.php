<div class="d-flex-column-wrapper">

    <?php
    require_once BASE_PATH . "/Templates/header.php";
    ?>


    <main class="container-fluid">

        <!-- Error Text -->
        <div class="text-center">
            <div class="error mx-auto" data-text="Error">Error</div>
            <p class="lead text-gray-800 mb-5"><?= ($errorMsg) ? $errorMsg : "" ?></p>
            <a href="?controller=page&action=home">&larr; Retourner à l'accueil</a>
        </div>

    </main>


    <?php require_once BASE_PATH . "/Templates/footer.php" ?>
</div>