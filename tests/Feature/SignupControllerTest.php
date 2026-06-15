<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Utilities\CurrUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class SignupControllerTest extends TestCase{
        use RefreshDatabase;

    public function test_if_signing_up_works_with_correct_data(){
        // $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        // $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnyc",
        "email"=>"email@gemail.email",
        "password"=>"Smalec",
        "rpassword"=>"Smalec",
        "name"=>"Marian",
        "surname"=>"Knyc",
        "phone"=>"179179197",
        "role"=>"Worker"];

        $response = $this->post(route('signup.signup'),$payload);
        $response->assertSessionHas('Role',"Worker");
        $response->assertSee("Zarządzaj weryfikacją wolontariuszy");
    }

    public function test_if_signing_up_rejects_with_existing_login(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnyc",
        "email"=>"email@email.email",
        "password"=>"Smalec",
        "rpassword"=>"Smalec",
        "name"=>"Marian",
        "surname"=>"Knyc",
        "phone"=>"179179197",
        "role"=>"Worker"];

        $response = $this->post(route('signup.signup'),$payload);
        //$response->assertRedirect(route('home'));
        $response->assertSessionMissing("Role");
        $response->assertSee("Błędne dane");
    }

    public function test_if_signing_up_rejects_with_incorrect_email(){
        $payload= ["login"=>"MKnyc",
        "email"=>"email",
        "password"=>"Smalec",
        "rpassword"=>"Smalec",
        "name"=>"Marian",
        "surname"=>"Knyc",
        "phone"=>"179179197",
        "role"=>"Worker"];

        $response = $this->post(route('signup.signup'),$payload);
        $response->assertSessionMissing("Role");
        $response->assertSee("Błędne dane");
    }
    public function test_if_signing_up_rejects_too_short_password(){
        $payload= ["login"=>"MKnyc",
        "email"=>"email@email.email",
        "password"=>"S",
        "rpassword"=>"S",
        "name"=>"Marian",
        "surname"=>"Knyc",
        "phone"=>"179179197",
        "role"=>"Worker"];

        $response = $this->post(route('signup.signup'),$payload);
        $response->assertSessionMissing("Role");
        $response->assertSee("Błędne dane");
    }
    public function test_if_signing_up_rejects_different_passwords(){
        $payload= ["login"=>"MKnyc",
        "email"=>"email@email.email",
        "password"=>"Smalec",
        "rpassword"=>"2Smalce",
        "name"=>"Marian",
        "surname"=>"Knyc",
        "phone"=>"179179197",
        "role"=>"Worker"];

        $response = $this->post(route('signup.signup'),$payload);
        $response->assertSessionMissing("Role");
        $response->assertSee("Błędne dane");
    }

    public function test_if_signing_up_rejects_incorrect_name(){
        $payload= ["login"=>"MKnyc",
        "email"=>"email@email.email",
        "password"=>"Smalec",
        "rpassword"=>"Smalec",
        "name"=>"Marian5",
        "surname"=>"Knyc",
        "phone"=>"179179197",
        "role"=>"Worker"];

        $response = $this->post(route('signup.signup'),$payload);
        $response->assertSessionMissing("Role");
        $response->assertSee("Błędne dane");
    }

    public function test_if_signing_up_rejects_existing_phone_number(){
    $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);    
    $payload= ["login"=>"MKnyc",
        "email"=>"email@email.email",
        "password"=>"Smalec",
        "rpassword"=>"Smalec",
        "name"=>"Marian",
        "surname"=>"Knyc",
        "phone"=>"123123123",
        "role"=>"Worker"];

        $response = $this->post(route('signup.signup'),$payload);
        $response->assertSessionMissing("Role");
        $response->assertSee("Błędne dane");
    }
}