<?php

use yii\db\Migration;

/**
 * Class m200106_161822_news2rss
 */
class m200106_161822_news2rss extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%news}}', 'in_rss', $this->boolean()->defaultValue(true)->after('source'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'in_rss');
    }
}
