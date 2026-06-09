<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CatalogMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_tables_exist_after_migration(): void
    {
        $tables = [
            'users',
            'categories',
            'product_series',
            'products',
            'product_images',
            'colors',
            'storage_options',
            'product_variants',
            'orders',
            'order_items',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
        }
    }
}
