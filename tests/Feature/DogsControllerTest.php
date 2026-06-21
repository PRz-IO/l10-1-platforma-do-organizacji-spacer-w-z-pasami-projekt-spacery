<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\CreatesApplication;
class DogsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_opens()
    {
        $response = $this->get('/dogs');

        $response->assertStatus(200);
    }

    public function test_create_page_opens()
    {
        $response = $this->get('/dogs/create');

        $response->assertStatus(200);
    }

    public function test_show_displays_existing_dog()
    {
        $dog = Dog::create([
            'Name' => 'Burek',
            'Age' => 3,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id);

        $response->assertStatus(200);
        $response->assertViewHas('dog');
    }

    public function test_edit_displays_existing_dog()
    {
        $dog = Dog::create([
            'Name' => 'Azor',
            'Age' => 5,
            'Behaviour' => 'Calm',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/edit');

        $response->assertStatus(200);
        $response->assertViewHas('dog');
    }

    public function test_store_creates_new_dog()
    {
        $response = $this->post('/dogs', [
            'Name' => 'Reksio',
            'Age' => 2,
            'Behaviour' => 'Friendly',
            'State' => 'Ready'
        ]);

        $response->assertRedirect('/dogs');

        $this->assertDatabaseHas('dogs', [
            'Name' => 'Reksio'
        ]);
    }

    public function test_update_modifies_existing_dog()
    {
        $dog = Dog::create([
            'Name' => 'OldName',
            'Age' => 4,
            'Behaviour' => 'Calm',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->put('/dogs/' . $dog->id, [
            'Name' => 'NewName',
            'Age' => 4,
            'Behaviour' => 'Very Calm',
            'State' => 'Ready'
        ]);

        $response->assertRedirect('/dogs/' . $dog->id);

        $this->assertDatabaseHas('dogs', [
            'id' => $dog->id,
            'Name' => 'NewName'
        ]);
    }

    public function test_show_returns_404_for_missing_dog()
    {
        $response = $this->get('/dogs/999999');

        $response->assertStatus(404);
    }

    public function test_edit_returns_404_for_missing_dog()
    {
        $response = $this->get('/dogs/999999/edit');

        $response->assertStatus(404);
    }
}



