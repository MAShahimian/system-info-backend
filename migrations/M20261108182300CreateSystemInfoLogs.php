<?php

declare(strict_types=1);

namespace RadinTech\SystemInfo\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

final class M20261108182300CreateSystemInfoLogs implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable(
            'system_info_logs',
            [
                'id' => ColumnBuilder::primaryKey(),
                'component' => ColumnBuilder::string(100)->notNull(),
                'version' => ColumnBuilder::string(50)->notNull(),
                'details' => ColumnBuilder::text(),
                'created_at' => ColumnBuilder::datetime()->notNull(),
            ],
        );
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('system_info_logs');
    }
}
