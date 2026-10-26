<?php

namespace Database\Seeders\ThinkMotion;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'email' => 'pilates@example.com',
                'name' => 'ピラティステスト',
                'is_pilates_user' => true,
                'is_medical' => false,
            ],
            [
                'email' => 'pilates-mania@example.com',
                'name' => 'ピラティスマニア',
                'is_pilates_user' => true,
                'is_medical' => false,
            ],
            [
                'email' => 'pilatespt@example.com',
                'name' => 'ピラティスPT',
                'is_pilates_user' => true,
                'is_medical' => true,
            ],
            [
                'email' => 'testpt@example.com',
                'name' => 'テストPT',
                'is_pilates_user' => false,
                'is_medical' => true,
            ],
        ];
        
        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'password' => Hash::make('password123456'),
                    'profile_completed' => false,
                    'bookshelf_public' => true,
                    'is_client' => false,
                    'is_pilates_user' => $data['is_pilates_user'],
                    'is_medical' => $data['is_medical'],
                ]
            );
        
            // 既存ユーザーにも配列の区分を反映
            $user->is_pilates_user = $data['is_pilates_user'];
            $user->is_medical = $data['is_medical'];
            $user->save();
        
            if ($user->is_pilates_user) {
                $user->client()->firstOrCreate(
                    [],
                    [
                        'id' => (string) Str::uuid(),
                        'name' => $data['name'],
                    ]
                );
            }
        
            if ($user->is_medical) {
                $user->profile()->firstOrCreate(
                    [],
                    [
                        'id' => (string) Str::uuid(),
                        'display_name' => $data['name'],
                    ]
                );
            }
        }
    }
}
