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
        if (is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('in_rss')))
            $this->addColumn('{{%news}}', 'in_rss', $this->boolean()->defaultValue(true)->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('in_rss'))
            $this->dropColumn('{{%news}}', 'in_rss');
    }
}
