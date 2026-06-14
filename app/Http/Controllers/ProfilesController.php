<?php

namespace App\Http\Controllers;

use App\DTOs\ProfileDTO;
use App\Models\Account;
use App\Models\Schedule;
use Exception;
use Hash;
use Illuminate\Http\Request;
use App\Models\Volunteer;
use App\Models\Worker;
use App\Utilities\CurrUser;
use App\Models\Dog;
use App\Models\Fav_Dog;
use Session;


class ProfilesController extends Controller
{
    public function index(){
        if(!CurrUser::IsLogged()){
            return redirect('/login');
        }
        $ProfileDTO = ProfileDTO::New(CurrUser::getId(),CurrUser::getRole());
        //error_log(print_r($ProfileDTO->getHistory(),true));
        $WCount = Schedule::join('volunteers','schedules.volunteer_id','=','volunteers.id')
        ->where('account_id',CurrUser::getId())->count('*');
        return view('Profile',compact('ProfileDTO','WCount'))->with('Err',Session::get('Err'));
    }
    
    public function checkPass(Request $request){
        if(!CurrUser::IsLogged()){
            return redirect('/login');
        }
        return view('ProfileCheck')->with('Type',$request->input('type'));
    }

    public function Change(Request $request){   
        try{
            $request->validate([
                'password' => 'required|string', 
                'newpassword' => 'required|string',
                'rnewpassword'=> 'required|string',
            ]);
        }
        catch(Exception $e){
            return view('ProfileCheck')->with('Type','ChPass')->with('Err','Nie podano wszystkich haseł');
        }

        try{
            $request->validate([
                'newpassword' => 'min:6|max:80', 
                'rnewpassword'=> 'min:6|max:80',
            ]);
        }
        catch(Exception $e){
            return view('ProfileCheck')->with('Type','ChPass')->with('Err','Nowe hasło jest za krótkie(min 6)');
        }

        
        if($request->input('newpassword') != $request->input('rnewpassword')){
            return view('ProfileCheck')->with('Type','ChPass')->with('Err','Hasła nie są takie same');
        }



        $Acc = Account::findOrFail(CurrUser::getId());
        
        if(! Hash::check($request->input('password'),$Acc->Password)){
            return view('ProfileCheck')->with('Type','ChPass')->with('Err','Błędne aktualne hasło');
        }
        
        if ($Acc) {
            $Acc->update(['Password' => Hash::make($request->input('newpassword'))]);
        }
        return redirect()->route('profile.index')->with('Err', 'Pomyślnie Zmieniono Hasło');
    }

    public function Delete(Request $request){
        
        $Acc = Account::findOrFail(CurrUser::getId());
        
        if(! Hash::check($request->input('password'),$Acc->Password)){
            return view('ProfileCheck')->with('Type','DelAcc')->with('Err','Błędne hasło');
        }

        $Acc->update(['Acc_State' => 'Deleted']);
        CurrUser::LogOut();
        return redirect()->route('login.login')->with('Err', 'Pomyślnie usunięto konto.');
    }

}
