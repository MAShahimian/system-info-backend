<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

final class M20261108182300CreateSystemInfoLogs implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('system_info_logs', [
            'id' => $b->primaryKey(),
            'component' => $b->string(100)->notNull(),
            'version' => $b->string(50)->notNull(),
            'details' => $b->text(),
            'created_at' => $b->dateTime()->notNull(),
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('system_info_logs');
    }
}
