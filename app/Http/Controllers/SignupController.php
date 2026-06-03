<?php

namespace App\Http\Controllers;

use App\DTOs\CurrentUserDTO;
use App\Models\Account;
use App\Utilities\CurrUser;
use Exception;
use Hash;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function index(){
        return view('SignUp');
    }

    public function SignUp(Request $request){
        try{
            $val = $request->validate([
                'login' => 'required|string|min:3|max:80|unique:accounts,Login',
                'email'=> 'required|string|max:80|email:rfc,strict|unique:accounts,Email',
                'password' => 'required|string|min:6|max:80', 
                'rpassword'=>'required|string|min:6|max:80|same:password',
                'name'=>['required','string','min:3','max:80','regex:/^[A-Z][a-zęóąśłżźćń]{2,}$/'],
                'surname'=>['required','string','min:3','max:80','regex:/^[A-Z][a-zęóąśłżźćń]{2,}$/'],
                'phone'=>['required','string','regex:/^[0-9]{9}$/','unique:accounts,Phone_Num'],
                'role'=>'required|string|max:80'
            ]);
        }
        catch(Exception $e){
            return view('SignUp')
            ->with('L',$request->input('login'))->with('E',$request->input('email'))
            ->with('N',$request->input('name'))->with('S',$request->input('surname'))
            ->with('P',$request->input('phone'))->with('R',$request->input('role'))
            ->with('Err','Błędne dane');
        }
        
        $Acc = Account::create([
            'Name'=>$request->input('name'),
            'Last_Name'=>$request->input('surname'),
            'Login'=>$request->input('login'),
            'Password'=>Hash::make($request->input('password')),
            'Acc_State'=>'Pending',
            'Email'=>$request->input('email'),
            'Phone_Num'=>$request->input('phone')
        ]);
        if($request->input('role') == 'Volunteer'){
            $Acc->volunteer()->create(['Is_Experienced'=>False]);
        }
        else{
            $Acc->worker()->create(['Is_Admin'=>False]);
        }
        $Curr = new CurrentUserDTO($Acc->id,$request->input('role'),False,'Pending');
        CurrUser::set($Curr);
        return view('test')->with('Err','Pomyślnie stworzono konto');
    }
}
