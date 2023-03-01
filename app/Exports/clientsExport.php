<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class clientsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         $data = Client::get();
       

        foreach($data as $k => $users)
        {
            unset($users->address, $users->city,  $users->state, $users->email_verified_at,$users->remember_token,
                $users->zipcode,$users->country,
                $users->password,$users->telephone,$users->avatar,);
            
            $data[$k]["name"]             = $users->name;
            $data[$k]["email"]            = $users->email;
            $data[$k]["currant_workspace"] = $users->currant_workspace;
          
          
          



        }  

        return $data;
    }

        public function headings(): array
    {
        return [
            "ID",
            "Name",
            "email",   
            "currant_workspace",
            
            "Created At",
            "Updated At",
        ];
    }


}
  