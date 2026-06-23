<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Whether the database has been prepared this process. Declared here (not on
     * a trait) and referenced via self:: so a single flag is shared across every
     * test class in the run.
     */
    private static bool $databasePrepared = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->prepareDatabaseOnce();

        // Isolate every test in a transaction that is rolled back in tearDown.
        // Previously this was attempted by the ManageDBAndRam trait, but its
        // setUp()/tearDown() were shadowed by this class and never ran, so tests
        // mutated a shared database with no rollback.
        DB::beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::rollBack();

        parent::tearDown();
    }

    /**
     * Migrate:fresh and seed once per test-runner process.
     *
     * $databasePrepared is false at process start (new run), so every `php
     * vendor/bin/phpunit` invocation gets a clean, freshly-seeded database.
     * Individual tests are still isolated by the beginTransaction/rollBack pair
     * above, so the seed data is never mutated between tests.
     */
    private function prepareDatabaseOnce(): void
    {
        if (self::$databasePrepared) {
            return;
        }
        self::$databasePrepared = true;

        Artisan::call('migrate:fresh');
        Artisan::call('db:seed', ['--class' => 'TestDatabaseSeeder']);
    }
}
