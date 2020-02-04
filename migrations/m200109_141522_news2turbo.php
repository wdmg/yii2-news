<?php

use yii\db\Migration;

/**
 * Class m200109_141522_news2turbo
 */
class m200109_141522_news2turbo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        if (is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('in_turbo')))
            $this->addColumn('{{%news}}', 'in_turbo', $this->boolean()->defaultValue(true)->after('source'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        if (!is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('in_turbo')))
            $this->dropColumn('{{%news}}', 'in_turbo');

    }
}
