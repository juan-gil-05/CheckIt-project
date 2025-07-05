<div class="d-flex-column-wrapper">

    <?php

    use App\Security\Security;

    require_once BASE_PATH . "/Templates/header.php"; ?>

    <main class="container">

        <div class="d-flex justify-content-between align-items-center">

            <h1>Mes listes</h1>
            <?php if (Security::isLogged()) { ?>
                <a href="?controller=list&action=saveOrUpdateList" class="btn btn-primary">Ajouter une liste</a>
            <?php } ?>
            <form method="get">
                <!-- Inputs hidden to send the whole url with the id category parameter -->
                <input type="hidden" name="controller" value="list">
                <input type="hidden" name="action" value="showLists">
                <label for="category" class="form-label">Catégorie</label>
                <select name="category" id="category" onchange="this.form.submit()">
                    <option value="">Toutes</option>
                    <?php foreach ($categories as $category) { ?>
                        <option <?= ((int)$category['id'] === $categoryId ? 'selected="selected"' : '') ?>
                            value="<?= $category['id'] ?>">
                            <?= $category['name'] ?>
                        </option>
                    <?php } ?>
                </select>
            </form>
        </div>
        <!-- Show all the lists -->
        <div class="row">
            <?php if (Security::isLogged()) {
                if ($lists) {
                    foreach ($lists as $list) { ?>
                        <div class="col-md-4 my-2">
                            <div class="card w-100">
                                <div class="card-header d-flex align-items-center justify-content-evenly">
                                    <i class="bi bi-card-checklist"></i>
                                    <h3 class="card-title mb-0"><?= $list['title'] ?></h3>
                                </div>
                                <div class="card-body d-flex flex-column ">
                                    <?php if ($itemsByList[$list['id']]) { ?>
                                        <ul class="list-group">
                                            <!-- To show and edit all the items for each list -->
                                            <?php foreach ($itemsByList[$list['id']] as $item) { ?>
                                                <li class="list-group-item">
                                                    <a class="me-2"
                                                        href="<?= $_SERVER['REQUEST_URI'] ?>&listAction=updateStatusListItem&redirect=list&item_id=<?= $item['id'] ?>&status=<?= !$item['status'] ?>">
                                                        <i class="bi bi-<?= ($item['status'] ? 'check-' : '') ?>circle<?= ($item['status'] ? '-fill' : '') ?>"></i>
                                                    </a> <?= $item['name'] ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } else { ?>
                                        <h6>Votre liste est vide.</h6>
                                    <?php } ?>
                                    <!-- Button to see the list in detail -->
                                    <div class="d-flex justify-content-between align-items-end mt-2">
                                        <a href="?controller=list&action=saveOrUpdateList&id=<?= $encryptedListId[$list['id']] ?>" class="btn btn-primary">Voir la liste</a>
                                        <span class="badge rounded-pill text-bg-primary p-2 d-flex gap-2">
                                            <i class="bi <?= $list['category_icon'] ?>"></i>
                                            <p class="mb-0"><?= $list['category_name'] ?></p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <h3 class="d-flex justify-content-center pt-5">Aucune liste</h3>
                <?php } ?>

            <?php } else { ?>
                <p>Pour consulter vos listes, vous devez être connecté:</p>
                <a href="?controller=user&action=logIn" class="btn btn-outline-primary me-2">Login</a>
            <?php } ?>
        </div>

    </main>

    <?php require_once BASE_PATH . "/Templates/footer.php" ?>

</div>