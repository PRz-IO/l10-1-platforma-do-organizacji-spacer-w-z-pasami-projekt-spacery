<?php

namespace App\DTOs;

use App\Models\Schedule;
use App\Models\Volunteer;
use App\Models\Worker;

class ProfileDTO
{
    protected string $Name;
    protected string $Last_Name;
    protected string $Login;
    protected string $Creation_Date;
    protected string $Email;
    protected string $Phone_Num;
    protected array $History;

    public function __construct(
        string $Name,string $Last_Name,
        string $Login,string $Creation_Date,
        string $Email,string $Phone_Num,
        array $History
        )
    {
        $this->Name = $Name;
        $this->Last_Name = $Last_Name;
        $this->Login = $Login;
        $this->Creation_Date = $Creation_Date;
        $this->Email = $Email;
        $this->Phone_Num = $Phone_Num;
        $this->History = $History;
    }

    public function getName():string{
        return $this->Name;
    }
    public function setName(string $Name){
        $this->Name = $Name;
    }

    public function getLast_Name():string{
        return $this->Last_Name;
    }
    public function setLast_Name(string $Last_Name){
        $this->Last_Name = $Last_Name;
    }

    public function getLogin():string{
        return $this->Login;
    }
    public function setLogin(string $Login){
        $this->Login = $Login;
    }

    public function getCreation_Date():string{
        return $this->Creation_Date;
    }
    public function setCreation_Date(string $Creation_Date){
        $this->Creation_Date = $Creation_Date;
    }

    public function getHistory():array{
        return $this->History;
    }
    public function setHistory(array $History){
        $this->History = $History;
    }

    public function getEmail():string{
        return $this->Email;
    }
    public function setEmail(string $Email){
        $this->Login = $Email;
    }

    public function getPhone_Num():string{
        return $this->Phone_Num;
    }
    public function setPhone_Num(string $Phone_Num){
        $this->Login = $Phone_Num;
    }

    public function toArray():array{
        return [
            'Name' => $this->Name,
            'Last_Name' => $this->Last_Name,
            'Login' => $this->Login,
            'Creation_Date'=> $this->Creation_Date,
            'History' => $this->History
        ];
    }

    public function isEmpty():bool{
        return empty($this->History);
    }

    public static function New(int $Id, string $Role): self{
        $history = [];
        if ($Role === 'Volunteer') {
            $profile = Volunteer::where('account_id', $Id)->first();
            $schedules = Schedule::where('volunteer_id',$profile->id)->orderByDesc('Date')->get();
            //error_log(print_r($walkdto,true));
            foreach($schedules as $walk){
                $walkdto = new ProfileWalkDTO(
                    $walk->id, $walk->dog->Name,
                    $walk->Date, $walk->Time,
                    $walk->Grade, $walk->worker->account->Name,
                    $walk->worker->account->Last_Name);
                $history[]=$walkdto;
            }
        }

        if ($Role === 'Worker') {
            $profile = Worker::where('account_id', $Id)->first();
            $schedules = Schedule::where('supervisor_id',$profile->id)->orderByDesc('Date')->get();
            foreach($schedules as $walk){
                $walkdto = new ProfileWalkDTO(
                    $walk->id, $walk->dog->Name,
                    $walk->Date, $walk->Time,
                    $walk->Grade, $walk->volunteer->account->Name,
                    $walk->volunteer->account->Last_Name);
                $history[]=$walkdto;
            }
        }
        return new self(
            $profile->account->Name, $profile->account->Last_Name,
            $profile->account->Login, $profile->account->Creation_Date,
            $profile->account->Email, $profile->account->Phone_Num,
            $history);
    }
}
