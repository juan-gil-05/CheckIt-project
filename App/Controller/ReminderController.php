<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Exception;
use MongoDB\BSON\UTCDateTime;

class ReminderController extends Controller
{
    public static function addReminder(int $userId, int $listId)
    {
        try {

            $itemRepo = new ItemRepository;

            if (isset($_POST['saveReminder'])) {

                $itemId = (int)$_POST['item_id'];
                $remindAt = new UTCDateTime(strtotime($_POST['remind_at']) * 1000);
                $message = $_POST['message'] ?? '';

                $itemRepo->saveReminder($userId, $itemId, $listId, $remindAt, $message);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
                exit;
            }
        } catch (Exception $e) {
            echo '$e';
            exit;
        }
    }

    public static function showReminderForEachItem(int $userId, int $listId) : array
    {
        try {
            $itemRepo = new ItemRepository;

            $itemReminders = [];

            $reminders = $itemRepo->findReminderByUserId($userId, $listId);

            foreach ($reminders as $reminder) {
                $itemReminders[$reminder['item_id']][] = $reminder;
            }

            return $itemReminders;
        } catch (Exception $e) {
            echo '$e';
            exit;
        }
        return $itemReminders;
    }

    public static function deleteReminder(ItemRepository $itemRepo)
    {
        if (isset($_GET['reminderAction']) && isset($_GET['reminder_id'])) {
            if ($_GET['reminderAction'] === 'deleteReminder') {
                $itemRepo->deleteReminderById((string)$_GET['reminder_id']);
                header('Location: ?controller=list&action=saveOrUpdateList&id=' . $_GET['id']);
                exit;
            }
        }
    }
}
