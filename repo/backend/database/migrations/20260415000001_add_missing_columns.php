<?php

use think\migration\Migrator;

class AddMissingColumns extends Migrator
{
    public function up()
    {
        $table = $this->table('users');
        if (!$table->hasColumn('violation_points')) {
            $table->addColumn('violation_points', 'integer', ['default' => 0]);
        }
        $table->save();

        $table = $this->table('user_preferences');
        if (!$table->hasColumn('violation_alerts')) {
            $table->addColumn('violation_alerts', 'boolean', ['default' => true]);
        }
        $table->save();

        $table = $this->table('orders');
        if (!$table->hasColumn('invoice_address')) {
            $table->addColumn('invoice_address', 'string', ['limit' => 500, 'null' => true]);
        }
        if (!$table->hasColumn('pending_address_correction')) {
            $table->addColumn('pending_address_correction', 'text', ['null' => true]);
        }
        $table->save();
    }
}
