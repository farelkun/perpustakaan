<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Membuat database default untuk tabel m_user dan m_roles
 * Jalankan perintah "php artisan db:seed --class=UserSeeder" pada terminal
 */
class UserSeeder extends Seeder
{
    public function run()
    {
        // Input data default untuk tabel m_roles
        DB::table('user_roles')->insert([
            'id' => 1,
            'nama' => 'Super Admin',
            'akses' => '{
                "user":{"create":true,"update":true,"delete":true,"view":true},
                "roles":{"create":true,"update":true,"delete":true,"view":true},
                "book_category":{"create":true,"update":true,"delete":true,"view":true},
                "book":{"create":true,"update":true,"delete":true,"view":true},
                "transaction":{"create":true,"update":true,"delete":true,"view":true},
                "item":{"create":false,"update":false,"delete":false,"view":false}
            }',
        ]);

        DB::table('user_roles')->insert([
            'id' => 2,
            'nama' => 'Customer',
            'akses' => '{
                "user":{"create":false,"update":false,"delete":false,"view":false},
                "roles":{"create":false,"update":false,"delete":false,"view":false},
                "book_category":{"create":false,"update":false,"delete":false,"view":false},
                "book":{"create":false,"update":false,"delete":false,"view":true},
                "transaction":{"create":false,"update":false,"delete":false,"view":true}
            }',
        ]);

        // Input data default untuk tabel m_user
        DB::table('user_auth')->insert([
            'id' => 1,
            'user_roles_id' => 1,
            'nama' => 'Farel Putra Hidayat',
            'email' => 'admin@landa.co.id',
            'password' => Hash::make('password'),
            'updated_security' => date('Y-m-d H:i:s')
        ]);
    }
}






