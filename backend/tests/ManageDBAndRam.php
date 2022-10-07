<?php
namespace Tests;


use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait ManageDBAndRam
{
    /**
    * If true, setup has run at least once.
    * @var boolean
    */
    protected static $setUpHasRunOnce = false;
    /**
    * After the first run of setUp "migrate:fresh --seed"
    * @return void
    */
    public function setUp(): void
    {
        parent::setUp();
        if (!static::$setUpHasRunOnce) {
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
     * @return void
     */
    public function tearDown(): void
    {
        DB::rollBack();
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic()
                && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')
                && $prop->getType()?->allowsNull() !== false
            ) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
        parent::tearDown();
    }
}
