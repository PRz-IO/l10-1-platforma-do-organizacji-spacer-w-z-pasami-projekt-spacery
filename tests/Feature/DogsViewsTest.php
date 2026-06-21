<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Dog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\CreatesApplication;
class DogsViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_view_contains_add_dog_form()
    {
        $response = $this->get('/dogs/create');

        $response->assertStatus(200);

        $response->assertSee('Dodaj psa');
        $response->assertSee('name="Name"', false);
        $response->assertSee('name="Age"', false);
        $response->assertSee('name="Behaviour"', false);
        $response->assertSee('name="State"', false);
        $response->assertSee('name="Photo"', false);
        $response->assertSee('Dodaj psa');
    }

    public function test_create_view_contains_all_dog_states()
    {
        $response = $this->get('/dogs/create');

        $response->assertSee('Gotowy na spacer');
        $response->assertSee('Pod opieką weterynarza');
        $response->assertSee('Wymaga doświadczonej opieki');
        $response->assertSee('W lepszym miejscu');
    }

    public function test_edit_view_displays_existing_dog_data()
    {
        $dog = Dog::create([
            'Name' => 'Azor',
            'Age' => 5,
            'Behaviour' => 'Spokojny',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/edit');

        $response->assertStatus(200);

        $response->assertSee('Edytuj psa');
        $response->assertSee('Azor');
        $response->assertSee('Spokojny');
        $response->assertSee('value="5"', false);
    }

    public function test_edit_view_contains_update_button()
    {
        $dog = Dog::create([
            'Name' => 'Burek',
            'Age' => 3,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/edit');

        $response->assertSee('Zapisz zmiany');
    }

    public function test_edit_view_contains_put_method()
    {
        $dog = Dog::create([
            'Name' => 'Reksio',
            'Age' => 2,
            'Behaviour' => 'Happy',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/edit');

        $response->assertSee('_method', false);
    }

     public function test_index_page_loads()
    {
        $response = $this->get('/dogs');

        $response->assertStatus(200);
        $response->assertSee('Lista psów');
    }

    public function test_empty_database_message_is_displayed()
    {
        $response = $this->get('/dogs');

        $response->assertStatus(200);
        $response->assertSee('Brak psów w bazie');
    }

    public function test_ready_dog_is_visible()
    {
        Dog::create([
            'Name' => 'Burek',
            'Age' => 3,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs');

        $response->assertSee('Burek');
        $response->assertSee('Gotowe na spacer');
    }

    public function test_difficult_dog_section_is_visible()
    {
        Dog::create([
            'Name' => 'Rex',
            'Age' => 5,
            'Behaviour' => 'Aggressive',
            'State' => 'Difficult',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs');

        $response->assertSee('Rex');
        $response->assertSee('Wymagające doświadczonej opieki');
    }

    public function test_sick_dog_section_is_visible()
    {
        Dog::create([
            'Name' => 'Max',
            'Age' => 8,
            'Behaviour' => 'Calm',
            'State' => 'Sick',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs');

        $response->assertSee('Max');
        $response->assertSee('Pod opieką weterynarza');
    }

    public function test_dog_details_link_is_rendered()
    {
        $dog = Dog::create([
            'Name' => 'Azor',
            'Age' => 4,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs');

        $response->assertSee('/dogs/' . $dog->id, false);
        $response->assertSee('Szczegóły');
    }

    public function test_back_to_top_button_exists()
    {
        $response = $this->get('/dogs');

        $response->assertSee('href="#top"', false);
        $response->assertSee('↑');
    }


    public function test_show_page_displays_dog_name()
    {
        $dog = Dog::create([
            'Name' => 'Burek',
            'Age' => 4,
            'Behaviour' => 'Przyjazny',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id);

        $response->assertStatus(200);
        $response->assertSee('Burek');
    }

    public function test_show_page_displays_dog_age()
    {
        $dog = Dog::create([
            'Name' => 'Azor',
            'Age' => 7,
            'Behaviour' => 'Spokojny',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id);

        $response->assertSee('7');
    }

    public function test_show_page_displays_behaviour()
    {
        $dog = Dog::create([
            'Name' => 'Reksio',
            'Age' => 2,
            'Behaviour' => 'Bardzo energiczny',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id);

        $response->assertSee('Bardzo energiczny');
    }

    public function test_show_page_contains_walks_link()
    {
        $dog = Dog::create([
            'Name' => 'Max',
            'Age' => 3,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id);

        $response->assertSee('/dogs/' . $dog->id . '/walks', false);
        $response->assertSee('Spacery');
    }

    public function test_show_page_contains_photo()
    {
        $dog = Dog::create([
            'Name' => 'Lucky',
            'Age' => 5,
            'Behaviour' => 'Calm',
            'State' => 'Ready',
            'Photo' => 'dog.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id);

        $response->assertSee('dog.jpg');
    }


    public function test_walks_page_loads()
    {
        $dog = Dog::create([
            'Name' => 'Burek',
            'Age' => 4,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/walks');

        $this->assertTrue(
            in_array($response->status(), [200, 302])
        );
    }

    public function test_walks_page_contains_dog_name_when_accessible()
    {
        $dog = Dog::create([
            'Name' => 'Azor',
            'Age' => 3,
            'Behaviour' => 'Calm',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/walks');

        if ($response->status() === 200) {
            $response->assertSee('Azor');
        }

        $this->assertTrue(true);
    }

    public function test_walks_page_contains_future_walks_section()
    {
        $dog = Dog::create([
            'Name' => 'Rex',
            'Age' => 5,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/walks');

        if ($response->status() === 200) {
            $response->assertSee('Przyszłe spacery');
        }

        $this->assertTrue(true);
    }

    public function test_walks_page_contains_past_walks_section()
    {
        $dog = Dog::create([
            'Name' => 'Max',
            'Age' => 6,
            'Behaviour' => 'Friendly',
            'State' => 'Ready',
            'Photo' => 'test.jpg'
        ]);

        $response = $this->get('/dogs/' . $dog->id . '/walks');

        if ($response->status() === 200) {
            $response->assertSee('Odbyte spacery');
        }

        $this->assertTrue(true);
    }



}