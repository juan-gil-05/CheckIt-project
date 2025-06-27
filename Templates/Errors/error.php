<?php
require_once BASE_PATH . "/Templates/header.php";
?>


<?php if ($error) { ?>
    <div>
        <?= $error; ?>
    </div>
<?php } ?>

<?php require_once BASE_PATH . "/Templates/footer.php" ?>