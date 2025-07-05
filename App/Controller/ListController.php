<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ItemRepository;
use App\Repository\ListRepository;
use App\Repository\TagRepository;
use App\Security\Security;
use Exception;
use MongoDB\BSON\UTCDateTime;

class ListController extends Controller
{

    public function route(): void
    {
        $urlAction = $_GET['action'] ?? null;
        try {

            if (isset($urlAction)) {
                switch ($urlAction) {
                    // ?controller=list&action=showLists
                    case 'showLists':
                        $this->showLists();
                        break;
                    // ?controller=list&action=saveOrUpdateList
                    case 'saveOrUpdateList':
                        $this->saveOrUpdateList();
                        break;
                    default:
                        throw new Exception("L'action n'existe pas: " . $_GET['action']);
                        break;
                }
            } else {
                throw new Exception("Aucune action détectée");
            }
        } catch (Exception $e) {
            $this->render("Errors/error", ["errorMsg" => $e->getMessage()]);
        }
    }


    // ?controller=list&action=showLists
    public function showLists()
    {
        $listRepo = new ListRepository;
        $categoryRepo = new CategoryRepository;

        $categories = $categoryRepo->getAllCategories();

        $lists = [];
        $itemsByList = [];
        $categoryId = null;
        $encryptedListId = [];

        if (isset($_SESSION['user'])) {
            if (isset($_GET['category'])) {
                $categoryId = (int)$_GET['category'];
            }
            $lists = $listRepo->getListsByUserId($_SESSION['user']['id']);
        }

        if (!empty($lists)) {
            foreach ($lists as $list) {
                $itemsByList[$list['id']] = $listRepo->getListItems($list['id']);

                $encryptedListId[$list['id']] = Security::encryptUrlParameter($list['id']);
            }
        }

        $this->updateOrDeleteItem($listRepo);

        // To filter the search by category
        if (isset($_SESSION['user'])) {
            if (isset($_GET['category'])) {
                $categoryId = (int)$_GET['category'];
            }
            $lists = $listRepo->getListsByUserId($_SESSION['user']['id'], $categoryId);
        }


        $this->render(
            "List/show-lists",
            [
                "categories" => $categories,
                "itemsByList" => $itemsByList,
                "lists" => $lists,
                "categoryId" => $categoryId,
                "encryptedListId" => $encryptedListId
            ]
        );
    }

    // ?controller=list&action=saveOrUpdateList
    public function saveOrUpdateList()
    {
        if (Security::isLogged()) {
            $errorsList = [];
            $errorsListItem = [];
            $messagesList = [];
            $messagesListItem = [];

            $listRepo = new ListRepository;
            $categoryRepo = new CategoryRepository;
            $tagRepo = new TagRepository;
            $itemRepo = new ItemRepository;

            $categories = $categoryRepo->getAllCategories();
            $tags = $tagRepo->getAllTags();
            $list = [
                "title" => "",
                "category_id" => ""
            ];
            $items = [];
            $itemTag = [];
            $itemReminders = [0];
            $listId = null;

            $userId = $_SESSION['user']['id'];

            $editMode = false;
            // To edit the list and items
            if (isset($_GET['id'])) {

                $listIdString = Security::decryptUrlParameter($_GET['id']);
                $listId = intval($listIdString);

                $list = $listRepo->getListById($listId);
                $editMode = true;

                $items = $listRepo->getListItems($listId);

                foreach ($items as $item) {
                    $itemTag[$item['id']] = $tagRepo->getItemTagByItemId($item['id']);
                }


                $this->addReminder($userId, $listId);

                $itemReminders = $this->showReminderForEachItem($userId, $listId);
            }

            // To save or update a list
            if (isset($_POST['saveList'])) {
                if (!empty($_POST['title'])) {
                    $res = $listRepo->saveList($_POST['title'], (int)$_SESSION['user']['id'], $_POST['category_id'], $listId);
                    if ($res) {
                        if ($listId) {
                            $messagesList[] = 'La liste a bien été mise à jour';
                            header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
                        } else {
                            header('Location: ?controller=list&action=saveOrUpdateList&id=' . Security::encryptUrlParameter($res));
                        }
                    } else {
                        // erreur
                        $errorsList[] = "La liste n'a pas été enregistrée";
                    }
                } else {
                    // erreur
                    $errorsList[] = "Le titre est obligatoire";
                }
            }

            $this->deteleList($listRepo);
            // To save or update an item from a list
            if (isset($_POST['saveListItem'])) {
                if (!empty($_POST['name'])) {
                    // save
                    $item_id = (isset($_POST['item_id']) ? $_POST['item_id'] : null);
                    $res = $listRepo->saveListItem($_POST['name'], $listId, false, $item_id);
                    header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
                } else {
                    // error
                    $errorsListItem[] = "Le nom de l'item est obligatoire";
                }
            }
            $this->updateOrDeleteItem($listRepo);

            // To save an item from list
            if (isset($_POST['saveItemTag'])) {
                $itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
                $tagId = isset($_POST['tag_id']) ? (int)$_POST['tag_id'] : null;

                if ($tagId === 0) {
                    $errorsListItem[] = "Vous devez choisir un tag";
                    header("Refresh:3");
                } else {
                    // To verify if the tag is already in use to the item
                    $existingTags = $tagRepo->getItemTagByItemId($itemId);
                    $isDuplicate = false;

                    foreach ($existingTags as $tag) {
                        if ((int)$tag['tag_id'] === $tagId) {
                            $isDuplicate = true;
                            break;
                        }
                    }

                    if ($isDuplicate) {
                        $errorsListItem[] = "Ce tag est déjà choisi pour cet item";
                        header("Refresh:3");
                    } else {
                        $res = $tagRepo->saveItemTag($itemId, $tagId);
                        $messagesListItem[] = "Tag ajouté";
                        header("Refresh:3");
                        if (!$res) {
                            $errorsListItem[] = "Une erreur est survenue lors de l'ajout du tag";
                            header("Refresh:3");
                        }
                    }
                }
            }

            $this->deleteItemTag($tagRepo);

            $this->deleteReminder($itemRepo);


            $this->render(
                "List/save-update-list",
                [
                    "editMode" => $editMode,
                    "list" => $list,
                    "items" => $items,
                    "itemTag" => $itemTag,
                    "categories" => $categories,
                    "tags" => $tags,
                    "errorsList" => $errorsList,
                    "errorsListItem" => $errorsListItem,
                    "messagesList" => $messagesList,
                    "messagesListItem" => $messagesListItem,
                    "itemReminders" => $itemReminders[0]
                ]
            );
        } else {
            header('Location: ?controller=user&action=logIn');
            die;
        }
    }

    protected function updateOrDeleteItem(ListRepository $listRepo)
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

    protected function deleteItemTag(TagRepository $tagRepo)
    {
        if (isset($_GET['itemTagAction']) && isset($_GET['item_id'])) {
            if ($_GET['itemTagAction'] === 'deleteItemTag') {
                $res = $tagRepo->deleteItemTag((int)$_GET['item_id'], (int)$_GET['tag_id']);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
            }
        }
    }

    protected function addReminder(int $userId, int $listId)
    {
        try {

            $itemRepo = new ItemRepository;

            if (isset($_POST['saveReminder'])) {

                $itemId = (int)$_POST['item_id'];
                $remindAt = new UTCDateTime(strtotime($_POST['remind_at']) * 1000);
                $message = $_POST['message'] ?? '';

                $itemRepo->saveReminder($userId, $itemId, $listId, $remindAt, $message);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
            }
        } catch (Exception $e) {
            echo '$e';
            exit;
        }
    }

    protected function showReminderForEachItem(int $userId, int $listId)
    {
        try {
            $itemRepo = new ItemRepository;

            $itemReminders = [];

            $reminders = $itemRepo->findReminderByUserId($userId, $listId);

            foreach ($reminders as $reminder) {
                $itemReminders[$reminder['item_id']][] = $reminder;
            }

            return [$itemReminders];
        } catch (Exception $e) {
            echo '$e';
            exit;
        }
    }

    protected function deleteReminder(ItemRepository $itemRepo)
    {
        if (isset($_GET['reminderAction']) && isset($_GET['reminder_id'])) {
            if ($_GET['reminderAction'] === 'deleteReminder') {
                $res = $itemRepo->deleteReminderById((string)$_GET['reminder_id']);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
            }
        }
    }

    protected function deteleList(ListRepository $listRepo)
    {
        if (isset($_GET['listAction']) && isset($_GET['list_id'])) {
            if ($_GET['listAction'] === 'deleteList') {
                $res = $listRepo->deleteListById((int)$_GET['list_id']);
                header('Location: ?controller=list&action=showLists');
            }
        }
    }
}
