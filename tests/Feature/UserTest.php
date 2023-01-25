<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\user;
use App\Models\Vehiculos;

class UserTest extends TestCase
{
    /**
     * Creando un nuevo usuario.
     *
     */
    public function testCreateComment()
    {
        $user = User::factory()->create();
        $user_new = [
            '_token' => csrf_token(),
            'name' => 'Oscar Mauricio',
            'email' => 'userprueba@prueba.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];
        $response = $this->actingAs($user)->post('/registro-de-usuario', $user_new);
        $response->assertSessionHas('status', 'El usuario fue creado satisfactoriamente');

        $usertest = User::where('email', $user_new['email'])->first();

        
        $test_veh = [
            '_token' => csrf_token(),
            'marca' => 'honda',
            'modelo' => 'Subaru',
            'patente' => 'TY9823',
            'annio' => 1973,
            'precio' => 2345678,
            'users_id_veh' => $usertest->id,
        ];
        $response = $this->actingAs($user)->post('/agregar-vehiculo', $test_veh);
        $response->assertSessionHas('status', 'El vehículo fue ingresado correctamente');
    }
}
