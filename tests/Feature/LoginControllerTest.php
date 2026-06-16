<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Utilities\CurrUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase{
    use RefreshDatabase;

    public function test_logging_in_with_correct_credentials_works(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),
        "Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnyc","password"=>"Smalec"];

        $response = $this->post(route('login.login'),$payload);
        //$response->assertRedirect(route('home'));
        $response->assertSessionHas('Role',"Volunteer");
    }

    public function test_logging_rejects_wrong_login(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),
        "Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnycg","password"=>"Smalec"];

        $response = $this->post(route('login.login'),$payload);
        $response->assertViewIs('LogIn');
        $response->assertSee("Błędny Login");
    }
    public function test_logging_rejects_wrong_password(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),
        "Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnyc","password"=>"Sm"];

        $response = $this->post(route('login.login'),$payload);
        $response->assertViewIs('LogIn');
        $response->assertSee("Błędne Hasło");
    }

    public function test_logging_rejects_deleted_account(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),
        "Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Deleted"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnyc","password"=>"Smalec"];

        $response = $this->post(route('login.login'),$payload);
        $response->assertViewIs('LogIn');
        $response->assertSee("Błędny Login");
    }

    public function test_logging_rejects_banned_account(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),
        "Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Banned"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnyc","password"=>"Smalec"];

        $response = $this->post(route('login.login'),$payload);
        $response->assertViewIs('LogIn');
        $response->assertSee("To konto jest zablokowane");
    }

    public function test_logging_out_clears_session(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),
        "Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Active"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $payload= ["login"=>"MKnyc","password"=>"Smalec"];

        $response = $this->post(route('login.login'),$payload);
        $response->assertSessionHas('Role',"Volunteer");
        $this->assertEquals("Volunteer",CurrUser::getRole());

        $response = $this->get(route('login.logout'));
        $response->assertSessionMissing('Role');
    }
}
