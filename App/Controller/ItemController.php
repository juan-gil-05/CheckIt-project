<?php

namespace App\Controller;

use App\Repository\ListRepository;

class ItemController extends Controller
{
    // To finish
    public static function saveListItem(ListRepository $listRepo, $listId)
    {
        $errorsListItem = [];
        // To save or update an item from a list
        if (isset($_POST['saveListItem'])) {
            if (!empty($_POST['name'])) {
                // save
                $item_id = (isset($_POST['item_id']) ? $_POST['item_id'] : null);
                $listRepo->saveListItem($_POST['name'], $listId, false, $item_id);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
                exit;
            } else {
                // error
                $errorsListItem[] = "Le nom de l'item est obligatoire";
            }
        }
        return $errorsListItem;
    }

    public static function updateOrDeleteItem(ListRepository $listRepo)
    {
        if (isset($_GET['listAction']) && isset($_GET['item_id'])) {
            if ($_GET['listAction'] === 'deleteListItem') {
                $res = $listRepo->deleteListItemById((int)$_GET['item_id']);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
            }
            if ($_GET['listAction'] === 'updateStatusListItem' && isset($_GET['status'])) {
                $res = $listRepo->updateListItemStatus((int)$_GET['item_id'], (bool)$_GET['status']);
                if (isset($_GET['redirect']) && $_GET['redirect'] === 'list') {
                    header('Location: ?controller=list&action=showLists');
                } else {
                    header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
                }
            }
        }
    }
}
