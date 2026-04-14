<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateDashboardFavoritesTable extends Migrator
{
    public function change()
    {
        $table = $this->table('dashboard_favorites', ['engine' => 'InnoDB', 'collation' => 'utf8mb4_unicode_ci']);
        $table
            ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('widget_id', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['user_id', 'widget_id'], ['unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
