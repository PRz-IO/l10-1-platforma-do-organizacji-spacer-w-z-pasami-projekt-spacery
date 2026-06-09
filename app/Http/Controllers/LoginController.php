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
        
        try{
            $request->validate([
                'login' => 'required|string|max:80',
                'password' => 'required|string|max:80',
            ]);
        }
        catch(Exception $e){
            return view('LogIn')->with('L',$request->input('login'))->with('Err','Nie podano loginu lub hasła');
        }

        $L = $request->input('login');
        $acc = Account::where('Login',$L)->first();
        if(is_null($acc)){
            return view('LogIn')->with('L',$L)->with('Err','Błędny Login');
        }

        //$acc = Account::where('Login',$L)->where('Password',$request->input('password'))->first();
        if(! Hash::check($request->input('password'),$acc->Password)){
            return view('LogIn')->with('L',$L)->with('Err','Błędne hasło');
        }
        if($acc->Acc_State == "Banned" || $acc->Acc_State == "Deleted"){
            return view('LogIn')->with('Err','To konto jest zablokowane');
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
        return redirect('/');
    }

    public function Logout(){
        CurrUser::LogOut();
        return redirect('/login');
    }
}
