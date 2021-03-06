<?php

use yii\db\Migration;

/**
 * Class m200109_141741_news2amp
 */
class m200109_141741_news2amp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('in_amp')))
            $this->addColumn('{{%news}}', 'in_amp', $this->boolean()->defaultValue(true)->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('in_amp'))
            $this->dropColumn('{{%news}}', 'in_amp');
    }
}
