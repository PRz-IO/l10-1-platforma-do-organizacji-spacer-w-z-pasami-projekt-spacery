<?php

namespace Tests\Feature;

use App\DTOs\CurrentUserDTO;
use App\Models\Account;
use App\Utilities\CurrUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ProfilesControllerTest extends TestCase{
        use RefreshDatabase;

    public function test_entering_profile_as_volunteer(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Experienced"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);
        $response = $this->get(route('profile.index'));
        $response->assertStatus(200);
        $response->assertViewIs("Profile");
        $response->assertSessionHas('Role',"Volunteer");
        $response->assertSee("Doświadczenie");

    }

    public function test_entering_profile_as_worker(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->worker()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Worker",false,"Pending");
        CurrUser::set($dto);
        $response = $this->get(route('profile.index'));
        $response->assertStatus(200);
        $response->assertViewIs("Profile");
        $response->assertSessionHas('Role',"Worker");
        $response->assertSee("Administrator");

    }

    public function test_entering_password_check_for_password_change(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["type"=>"ChPass"];
        
        $response = $this->post(route('profile.check'),$payload);
        $response->assertSessionHas("Role","Volunteer");
        $response->assertSee("Zmiana Hasła");

    }

    public function test_entering_password_check_for_profile_deletion(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["type"=>"DelAcc"];
        
        $response = $this->post(route('profile.check'),$payload);
        $response->assertSessionHas("Role","Volunteer");
        $response->assertSee("Usunięcie Konta");

    }

    public function test_deleting_profile_deletes_it(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["password"=>"Smalec"];
        
        $response = $this->delete(route('profile.delete'),$payload);
        $response->assertRedirect(route('login.login'));
        $response->assertSessionMissing("Role");

    }

    public function test_if_account_deletion_rejects_wrong_current_password(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["password"=>"Smar"];
        
        $response = $this->delete(route('profile.delete'),$payload);
        $response->assertViewIs("ProfileCheck");
        $response->assertSessionHas("Role","Volunteer");
        $response->assertSee("Błędne hasło");
    }

    public function test_if_password_changing_changes_the_password(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["password"=>"Smalec","newpassword"=>"bassword","rnewpassword"=>"bassword"];
        
        $response = $this->put(route('profile.change'),$payload);
        $response->assertRedirect(route('profile.index'));
        $response->assertSessionHas("Role","Volunteer");
        $Acc->refresh();
        $this->assertTrue(Hash::check("bassword",$Acc->Password));
    }

    public function test_if_password_change_rejects_wrong_current_password(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["password"=>"Sm","newpassword"=>"bassword","rnewpassword"=>"bassword"];
        
        $response = $this->put(route('profile.change'),$payload);
        $response->assertSessionHas("Role","Volunteer");
        $response->assertSee("Błędne aktualne hasło");

    }

    public function test_if_password_change_rejects_different_new_passwords(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["password"=>"Smalec","newpassword"=>"word","rnewpassword"=>"bassword"];
        
        $response = $this->put(route('profile.change'),$payload);
        $response->assertSessionHas("Role","Volunteer");
        $response->assertSee("Hasła nie są takie same");

    }
    public function test_if_password_change_rejects_too_short_new_password(){
        $Acc=Account::create(["Name"=>"Marian","Last_Name"=>"Knyc","Login"=>"MKnyc","Password"=>Hash::make("Smalec"),"Email"=>"email@email.email","Phone_Num"=>"123123123","Acc_State"=>"Pending"]);
        $Acc->volunteer()->create(["Is_Admin"=>false]);
        $dto= new CurrentUserDTO($Acc->id,"Volunteer",false,"Pending");
        CurrUser::set($dto);

        $payload= ["password"=>"Smalec","newpassword"=>"bass","rnewpassword"=>"bass"];
        
        $response = $this->put(route('profile.change'),$payload);
        $response->assertSessionHas("Role","Volunteer");
        $response->assertSee("Nowe hasło jest za krótkie(min 6)");

    }
}
