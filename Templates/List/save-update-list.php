<div class="d-flex-column-wrapper">

    <?php require_once BASE_PATH . "/Templates/header.php"; ?>

    <main class="container col-xxl-8">
        <h1>Liste</h1>
        <!-- To show the error messages -->
        <?php foreach ($errorsList as $error) { ?>
            <div class="alert alert-danger">
                <?= $error; ?>
            </div>
        <?php } ?>
        <!-- To show the succes messages -->
        <?php foreach ($messagesList as $message) { ?>
            <div class="alert alert-success">
                <?= $message; ?>
            </div>
        <?php } ?>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button <?= ($editMode) ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="<?= ($editMode) ? 'false' : 'true' ?>" aria-controls="collapseOne">
                        <?= ($editMode) ? $list['title'] : 'Ajouter une liste' ?>
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse <?= ($editMode) ? '' : 'show' ?>" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="title" class="form-label">Titre</label>
                                <input type="text" value="<?= $list['title']; ?>" name="title" id="title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie</label>
                                <!-- Section to filter by category -->
                                <select name="category_id" id="category_id" class="form-control">
                                    <?php foreach ($categories as $category) { ?>
                                        <option <?= ($category['id'] === $list['category_id']) ? 'selected="selected"' : '' ?>
                                            value="<?= $category['id'] ?>"><?= $category['name'] ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="submit" value="Enregistrer" name="saveList" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <?php if (!$editMode) { ?>
                <div class="alert alert-warning">
                    Après avoir enregistré, vous pourrez ajouter les items.
                </div>
            <?php } else { ?>
                <h2 class="border-top pt-3">Items</h2>
                <!-- To show the error messages -->
                <?php foreach ($errorsListItem as $error) { ?>
                    <div class="alert alert-danger">
                        <?= $error; ?>
                    </div>
                <?php } ?>
                <!-- To show the succes messages -->
                <?php foreach ($messagesListItem as $message) { ?>
                    <div class="alert alert-success">
                        <?= $message; ?>
                    </div>
                <?php } ?>
                <!-- Form to save a new item -->
                <form method="post" class="d-flex ">
                    <input type="checkbox" name="status" id="status">
                    <input type="text" name="name" id="name" placeholder="Ajouter un item" class="form-control mx-2">
                    <input type="submit" name="saveListItem" class="btn btn-primary" value="Enregistrer">
                </form>
                <!-- To update or delete an item  -->
                <div class="row m-4 border rounded p-2">
                    <?php foreach ($items as $item) { ?>
                        <div class="accordion mb-2">
                            <div class="accordion-item" id="accordion-parent-<?= $item['id'] ?>">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-item-<?= $item['id'] ?>" aria-expanded="false" aria-controls="collapseOne">
                                        <!-- To change the item status -->
                                        <a class="me-2" href="<?= $_SERVER['REQUEST_URI'] ?>&listAction=updateStatusListItem&item_id=<?= $item['id'] ?>&status=<?= !$item['status'] ?>">
                                            <i class="bi bi-<?= ($item['status'] ? 'check-' : '') ?>circle<?= ($item['status'] ? '-fill' : '') ?>"></i>
                                        </a>
                                        <?= $item['name'] ?>
                                    </button>
                                </h2>
                                <div id="collapse-item-<?= $item['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#accordion-parent-<?= $item['id'] ?>">
                                    <div class="accordion-body">
                                        <!-- To update the item -->
                                        <form action="" method="post">
                                            <div class="mb-3 d-flex gap-2">
                                                <input type="text" value="<?= $item['name']; ?>" name="name" class="form-control">
                                                <input type="hidden" name="item_id" value="<?= $item['id']; ?>">
                                                <input type="submit" value="Enregistrer" name="saveListItem" class="btn btn-primary">
                                            </div>
                                        </form>
                                        <!-- To delete the item and add a tag-->
                                        <div class="d-flex align-items-center justify-content-between">
                                            <!-- Delete item -->
                                            <a class="btn btn-outline-primary" href="<?= $_SERVER['REQUEST_URI'] ?>&listAction=deleteListItem&item_id=<?= $item['id'] ?>"
                                                onclick="return confirm('Etes-vous sûr de vouloir supprimer cet item ?')">
                                                <i class="bi bi-trash3-fill"></i>
                                                Supprimer
                                            </a>
                                            <!-- Add tag -->
                                            <form method="post" class="d-flex align-items-center gap-2">
                                                <label for="tagId" class="mb-0 text-secondary-emphasis">Tag:</label>
                                                <select name="tag_id" id="tagId" class="form-control">
                                                    <option value="0">Aucun</option>
                                                    <?php foreach ($tags as $tag) { ?>
                                                        <option
                                                            value="<?= $tag['id'] ?>"><?= $tag['name'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                                                <input type="submit" value="Ajouter" name="saveItemTag" class="btn btn-primary">
                                            </form>
                                        </div>
                                        <!-- To show the tags -->
                                        <?php if (!empty($itemTag[$item['id']])) { ?>
                                            <div>
                                                <ul class="item-tag-list">
                                                    <?php foreach ($itemTag[$item['id']] as $itemTag) { ?>
                                                        <li class="">
                                                            <?= $itemTag['tag_name'] ?>
                                                            <!-- To delete the tag -->
                                                            <a class="btn btn-light ms-3" href="<?= $_SERVER['REQUEST_URI'] ?>&itemTagAction=deleteItemTag&item_id=<?= $itemTag['item_id'] ?>&tag_id=<?= $itemTag['tag_id'] ?>"
                                                                onclick="return confirm('Etes-vous sûr de vouloir supprimer ce tag ?')">
                                                                <i class="bi bi-trash3-fill"></i>
                                                            </a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

    </main>

    <?php require_once BASE_PATH . "/Templates/footer.php" ?>

</div>