<?php

namespace App\Services;

use App\Exceptions\InvalidCodeException;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class E5CodeService
{
    /**
     * Validate and persist an E5 student code for $user.
     * Replicates the logic in AuthController::setE5code().
     *
     * @throws InvalidCodeException
     */
    public static function setCode(User $user, string $e5code): void
    {
        $validated = env('E5VOS_FAKE_API') || Http::post(env('E5VOS_API_URL'), [
            'email' => $user->email,
            'studentId' => $e5code,
            'api_token' => env('E5VOS_API_TOKEN'),
        ])->body() === 'true';

        if (! $validated) {
            throw new InvalidCodeException;
        }

        $ejgLetter = $e5code[4];
        $codeYear = intval($e5code);
        $ejgYear = date('Y') - $codeYear;
        $currmonth = date('m');

        if ($currmonth < 9) {
            $ejgYear--;
        }

        if ($ejgLetter === 'N') {
            $ejgYear += 8;
            if (($currmonth < 9 && $codeYear == date('Y') - 1) || ($currmonth > 8 && $codeYear == date('Y'))) {
                $ejgYear++;
                $ejgLetter = 'NY';
            } else {
                $ejgLetter = 'E';
            }
        } elseif ($ejgLetter === 'A' || $ejgLetter === 'B') {
            $ejgYear += 7;
        } else {
            $ejgYear += 9;
        }

        $user->e5code = $e5code;
        $user->ejg_class = strval($ejgYear).'.'.$ejgLetter;
        $user->save();

        Permission::firstOrCreate(['user_id' => $user->id, 'code' => 'STD']);
    }
}
