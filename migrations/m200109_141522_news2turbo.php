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
        $this->addColumn('{{%news}}', 'in_turbo', $this->boolean()->defaultValue(true));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'in_turbo');
    }
}
