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

    private function getRepositories(): array
    {
        return [
            'listRepo' => new ListRepository,
            'categoryRepo' => new CategoryRepository,
            'tagRepo' => new TagRepository,
            'itemRepo' => new ItemRepository
        ];
    }

    // ?controller=list&action=showLists
    public function showLists()
    {
        // Repositories
        extract($this->getRepositories());

        $categories = $categoryRepo->getAllCategories();

        // To initialize the variables
        $lists = [];
        $itemsByList = [];
        $categoryId = null;
        $encryptedListId = [];

        // Filter the search by category
        if (isset($_SESSION['user'])) {
            if (isset($_GET['category'])) {
                $categoryId = (int)$_GET['category'];
            }
            // To show all the lists
            $lists = $listRepo->getListsByUserId($_SESSION['user']['id'], $categoryId);
        }

        // To show the list items
        if (!empty($lists)) {
            foreach ($lists as $list) {
                $itemsByList[$list['id']] = $listRepo->getListItems($list['id']);

                // To encrypt the list id
                $encryptedListId[$list['id']] = Security::encryptUrlParameter($list['id']);
            }
        }

        ItemController::updateOrDeleteItem($listRepo);

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

            // Repositories
            extract($this->getRepositories());

            // To initialize the variables
            $reminders = [];
            $list = [
                "title" => "",
                "category_id" => ""
            ];
            $items = [];
            $itemTag = [];
            $listId = null;
            $userId = $_SESSION['user']['id'];
            $editMode = false;

            $categories = $categoryRepo->getAllCategories();
            $tags = $tagRepo->getAllTags();

            // To edit the list and items, to add and show the reminders
            if (isset($_GET['id'])) {

                $listIdString = Security::decryptUrlParameter($_GET['id']);
                $listId = intval($listIdString);

                $list = $listRepo->getListById($listId);
                $editMode = true;

                $items = $listRepo->getListItems($listId);

                foreach ($items as $item) {
                    $itemTag[$item['id']] = $tagRepo->getItemTagByItemId($item['id']);
                }

                ReminderController::addReminder($userId, $listId);

                $reminders = ReminderController::showReminderForEachItem($userId, $listId);
            }

            $saveList = $this->saveList($listRepo, $listId);

            $this->deleteList($listRepo);

            ItemController::updateOrDeleteItem($listRepo);
            
            $saveTag = TagController::saveItemTag($tagRepo);
            
            TagController::deleteItemTag($tagRepo);
            
            ReminderController::deleteReminder($itemRepo);
            
            $saveListItem = ItemController::saveListItem($listRepo, $listId);
            $errorsListItem = array_merge($saveTag['errors'], $saveListItem);

            $this->render(
                "List/save-update-list",
                [
                    "editMode" => $editMode,
                    "list" => $list,
                    "items" => $items,
                    "itemTag" => $itemTag,
                    "categories" => $categories,
                    "tags" => $tags,
                    "errorsList" => $saveList['errors'],
                    "messagesList" => $saveList['messages'],
                    "errorsListItem" => $errorsListItem,
                    "messagesListItem" => $saveTag['messages'],
                    "itemReminders" => $reminders
                ]
            );
        } else {
            header('Location: ?controller=user&action=logIn');
            die;
        }
    }

    protected function saveList(ListRepository $listRepo, $listId)
    {
        $errorsList = [];
        $messagesList = [];

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

        return ['errors' => $errorsList, 'messages' => $messagesList];
    }


    protected function deleteList(ListRepository $listRepo)
    {
        if (isset($_GET['listAction']) && isset($_GET['list_id'])) {
            if ($_GET['listAction'] === 'deleteList') {
                $res = $listRepo->deleteListById((int)$_GET['list_id']);
                header('Location: ?controller=list&action=showLists');
                exit;
            }
        }
    }
}
