<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait ManageDBAndRam
{
    /**
     * If true, setup has run at least once.
     *
     * @var bool
     */
    protected static $setUpHasRunOnce = false;

    /**
     * After the first run of setUp "migrate:fresh --seed"
     */
    public function setUp(): void
    {
        parent::setUp();
        if (! static::$setUpHasRunOnce) {
            Artisan::call('migrate:fresh');
            Artisan::call(
                'db:seed',
                ['--class' => 'DatabaseSeeder']
            );
            static::$setUpHasRunOnce = true;
        }
        DB::beginTransaction();
    }

    /**
     * After each test rollback the database
     */
    public function tearDown(): void
    {
        DB::rollBack();
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (
                ! $prop->isStatic()
                && strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_') !== 0
                && $prop->getType()?->allowsNull() !== false
            ) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
        parent::tearDown();
    }
}
