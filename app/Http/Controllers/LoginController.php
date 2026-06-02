<?php

namespace App\Http\Controllers;

use App\DTOs\CurrentUserDTO;
use App\Utilities\CurrUser;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Account;

class LoginController extends Controller
{
    public function index(){
        return view('LogIn');
    }

    public function Login(Request $request){
        if(CurrUser::IsLogged()){
            error_log("Zalogowany");
        }
        else{
            error_log("Nie Zalogowany");
        }
        $L = $request->input('login');
        try{
            $request->validate([
                'login' => 'required|string|max:80',
                'password' => 'required|string|max:80',
            ]);
        }
        catch(Exception $e){
            return view('LogIn')->with('L',$L)->with('Err','Nie podano loginu lub hasła');
        }

        //$acc = Account::where('Login',$L)->where('Password',Hash::make($request->input('password')))->first();
        $acc = Account::where('Login',$L)->where('Password',$request->input('password'))->first();
        if(is_null($acc)){
            return view('LogIn')->with('L',$L)->with('Err','Błędne dane logowania');
        }
        $id=$acc->id;
        $role="";
        $param=NULL;
        $state=$acc->Acc_State;

        if(is_null($acc->worker)){
            $role="Volunteer";
            $param=$acc->volunteer->Is_Experienced;
        }

        else{
            $role="Worker";
            $param=$acc->worker->Is_Admin;
        }
        $dto= new CurrentUserDTO($id,$role,$param,$state);
        error_log($dto->getId() . $dto->getRole() . "P: " . $dto->getParam() ." S: ". $dto->getAcc_State());
        CurrUser::set($dto);
        if(CurrUser::IsLogged()){
            error_log("Zalogowany");
        }
        else{
            error_log("Nie Zalogowany");
        }
        return redirect('/test');
    }

    public function Logout(){
        CurrUser::LogOut();
        return redirect('/login');
    }
}
