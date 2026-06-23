<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
     * Migrate and seed, but only when the testing database is empty.
     *
     * The DatabaseSeeder inserts ~1000 users plus events and attendances, which
     * is expensive. Seeding only when empty means that cost is paid at most once
     * per machine (the data persists between runs inside the committed schema),
     * while each individual test stays isolated via the transaction above. Drop
     * the testing database to force a reseed after schema or seeder changes.
     */
    private function prepareDatabaseOnce(): void
    {
        if (self::$databasePrepared) {
            return;
        }
        self::$databasePrepared = true;

        if (! Schema::hasTable('users') || DB::table('users')->count() === 0) {
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
        }
    }
}
