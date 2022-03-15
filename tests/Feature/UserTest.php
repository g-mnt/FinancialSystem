<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
  /** @test */
    public function it_should_register_user()
    {
        $faker = Factory::create("pt_BR");
        $userData = [
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'password' => $this->faker->password,
            'birth_date'=>$this->faker->date(),
            'cpf' => $faker->cpf()
        ];

        $this->post(route('user.store'), $userData)
            ->assertSuccessful();

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);
    }

    /** @test */
    public function password_should_be_encrypted()
    {
        $faker = Factory::create("pt_BR");
        $userData = [
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'password' => $this->faker->password,
            'birth_date'=>$this->faker->date(),
            'cpf' => $faker->cpf()
        ];

        $this->post(route('user.store'), $userData)
            ->assertSuccessful();

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);

        $savedPassword = User::where('email', $userData['email'])->get()->first()->password;

        $isHashed = Hash::check($userData['password'], $savedPassword);

        $this->assertTrue($isHashed);
    }

    /** @test */
    public function user_should_update()
    {
        $faker = Factory::create("pt_BR");
        $user = User::factory()->create();

        $newData = [
            'name' => $faker->name,
            'birth_date' => $faker->date(),
            'password' => $faker->password,
        ];

        $this->actingAs($user)->put(route('user.update', $user), $newData)->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'name' => $newData['name'],
            'birth_date' => $newData['birth_date']
        ]);

        $updatedUserPassword = User::where('email', $user->email)->get()->first()->password;

        $isHashed = Hash::check($newData['password'], $updatedUserPassword);

        $this->assertTrue($isHashed);

    }


}
