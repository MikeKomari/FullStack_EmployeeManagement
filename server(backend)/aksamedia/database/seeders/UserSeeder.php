<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Admin
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'phone' => '08123456789',
            'email' => 'admin@example.com',
            'password' => Hash::make('pastibisa'),
            'division_id' => '9999', //placeholder
            'position' => 'admin',
        ]);

        $division = Division::first();

        User::create([
            'name' => 'Mike',
            'username' => 'mike',
            'phone' => '0822334455',
            'email' => 'mike@gmail.com',
            'password' => Hash::make('mikepass'),
            'division_id' => $division->id,
            'position' => 'Staff',
            'image' => 'https://www.google.com/url?sa=i&url=https%3A%2F%2Fcommons.wikimedia.org%2Fwiki%2FFile%3APortrait_Placeholder.png&psig=AOvVaw0_kfNBkf_R89xFJ9DqDpF_&ust=1753926733465000&source=images&cd=vfe&opi=89978449&ved=0CBIQjRxqFwoTCLCbqsO8444DFQAAAAAdAAAAABAE'
        ]);

        User::create([
            'name' => 'Sarah Johnson',
            'username' => 'sarahj',
            'phone' => '0811223344',
            'email' => 'sarahj@example.com',
            'password' => Hash::make('sarahpass'),
            'division_id' => $division->id,
            'position' => 'Manager',
            'image' => 'https://www.w3schools.com/howto/img_avatar2.png'
        ]);

        User::create([
            'name' => 'David Lee',
            'username' => 'davidl',
            'phone' => '0813555777',
            'email' => 'davidl@example.com',
            'password' => Hash::make('davidpass'),
            'division_id' => $division->id,
            'position' => 'Staff',
            'image' => 'https://www.w3schools.com/howto/img_avatar.png'
        ]);

        User::create([
            'name' => 'Emily Clark',
            'username' => 'emilyc',
            'phone' => '0814666888',
            'email' => 'emilyc@example.com',
            'password' => Hash::make('emilypass'),
            'division_id' => $division->id,
            'position' => 'Supervisor',
            'image' => 'https://randomuser.me/api/portraits/women/44.jpg'
        ]);

        User::create([
            'name' => 'John Doe',
            'username' => 'johnd',
            'phone' => '0815777999',
            'email' => 'johnd@example.com',
            'password' => Hash::make('johnpass'),
            'division_id' => $division->id,
            'position' => 'Staff',
            'image' => 'https://randomuser.me/api/portraits/men/45.jpg'
        ]);

        User::create([
            'name' => 'Lisa Wong',
            'username' => 'lisaw',
            'phone' => '0816888000',
            'email' => 'lisaw@example.com',
            'password' => Hash::make('lisapass'),
            'division_id' => $division->id,
            'position' => 'Staff',
            'image' => 'https://randomuser.me/api/portraits/women/46.jpg'
        ]);
    }
}
