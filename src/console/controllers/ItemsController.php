<?php
namespace verbb\wishlist\console\controllers;

use verbb\wishlist\Wishlist;
use verbb\wishlist\elements\Item;

use Craft;
use craft\console\Controller;
use craft\db\Query;
use craft\helpers\Console;
use craft\helpers\Db;

use yii\console\ExitCode;
use yii\web\Response;

class ItemsController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionCleanupOrphanedItems()
    {
        $itemElements = (new Query())
            ->select(['id'])
            ->from(['{{%elements}}'])
            ->where(['type' => Item::class])
            ->column();

        foreach ($itemElements as $itemElement) {
            $item = (new Query())
                ->from(['{{%wishlist_items}}'])
                ->where(['id' => $itemElement])
                ->exists();

            if (!$item) {
                $this->stderr('Removed item element ' . $itemElement . '.' . PHP_EOL, Console::FG_GREEN);

                Db::delete('{{%elements}}', ['id' => $itemElement]);
            }
        }

        return ExitCode::OK;
    }
}
