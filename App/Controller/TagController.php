<?php

namespace App\Controller;

use App\Repository\TagRepository;

class TagController extends Controller
{

    public static function saveItemTag(TagRepository $tagRepo)
    {
        $errorsListItem = [];
        $messagesListItem = [];

        // To save a tag from a item 
        if (isset($_POST['saveItemTag'])) {
            $itemId = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
            $tagId = isset($_POST['tag_id']) ? (int)$_POST['tag_id'] : null;

            if ($tagId === 0) {
                $errorsListItem[] = "Vous devez choisir un tag";
                header("Refresh:3");
            } else {
                // To verify if the tag is already in use by the item
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

        return ['errors' => $errorsListItem, 'messages' => $messagesListItem];
    }

    public static function deleteItemTag(TagRepository $tagRepo)
    {
        if (isset($_GET['itemTagAction']) && isset($_GET['item_id'])) {
            if ($_GET['itemTagAction'] === 'deleteItemTag') {
                $tagRepo->deleteItemTag((int)$_GET['item_id'], (int)$_GET['tag_id']);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
                exit;
            }
        }
    }

}
