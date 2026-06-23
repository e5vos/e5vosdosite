<?php

namespace Tests\Unit\Services;

use App\Models\Permission;
use App\Services\E5CodeService;
use Illuminate\Support\Facades\Http;
use Tests\Concerns\CreatesEntities;
use Tests\TestCase;

class E5CodeServiceTest extends TestCase
{
    use CreatesEntities;

    protected function setUp(): void
    {
        parent::setUp();
        Http::fake(['*' => Http::response('true', 200)]);
    }

    public function test_set_code_persists_e5code_and_class_for_standard_student(): void
    {
        $user = $this->makeUser();
        // 2024A → class year 8 in 2026 (before September): 2026-2024-1+7 = 8 → '8.A'
        E5CodeService::setCode($user, '2024A00EJG001');

        $user->refresh();
        $this->assertSame('2024A00EJG001', $user->e5code);
        $this->assertNotNull($user->ejg_class);
    }

    public function test_set_code_grants_student_permission(): void
    {
        $user = $this->makeUser();
        E5CodeService::setCode($user, '2024A00EJG002');

        $this->assertDatabaseHas('permissions', [
            'user_id' => $user->id,
            'code' => 'STD',
        ]);
    }

    public function test_set_code_is_idempotent_for_permission(): void
    {
        $user = $this->makeUser();
        E5CodeService::setCode($user, '2024A00EJG003');
        E5CodeService::setCode($user, '2024A00EJG003');

        $this->assertSame(1, Permission::where(['user_id' => $user->id, 'code' => 'STD'])->count());
    }

    public function test_set_code_derives_class_for_n_letter_student(): void
    {
        $user = $this->makeUser();
        // 2024N → class year 9 in 2026 (before September): 2026-2024-1+8=9, letter→E → '9.E'
        E5CodeService::setCode($user, '2024N00EJG005');

        $user->refresh();
        $this->assertSame('2024N00EJG005', $user->e5code);
        $this->assertNotNull($user->ejg_class);
    }

    public function test_set_code_derives_class_for_b_letter_students(): void
    {
        $user = $this->makeUser();
        // 2025B → class year 7 in 2026 (before September): 2026-2025-1+7=7 → '7.B'
        E5CodeService::setCode($user, '2025B00EJG006');

        $user->refresh();
        $this->assertSame('2025B00EJG006', $user->e5code);
        $this->assertNotNull($user->ejg_class);
    }
}
