<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\Utility;
// use App\Models\Projects;
// use App\Models\Timesheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class invoiceExport implements FromCollection,WithHeadings
{
     public function collection()
    {
        $data = Invoice::get();

        foreach($data as $k => $Invoice)
        {
            unset($Invoice->created_by,$Invoice->terms);


            $invoice_name = Utility::invoiceNumberFormat($Invoice->invoice_id);
            $pro_nm       = Utility::project_nm($Invoice->project_id);           
            $tax_name     = !empty($Invoice->tax_id)?Utility::tax_nm($Invoice->tax_id):"";
            $client_name  =  Utility::taxRate($Invoice->client_id);

            
            $data[$k]["invoice_id"]               =  $invoice_name;
            $data[$k]["project_id"]               =  $pro_nm;
            $data[$k]["status"]                   =  $Invoice->status;
            $data[$k]["issue_date"]               =  $Invoice->issue_date;
            $data[$k]["due_date"]                 =  $Invoice->due_date;
            $data[$k]["discount"]                 =  $Invoice->discount;
            $data[$k]["tax_id"]                   =  $tax_name;
            $data[$k]["client_id"]                =  $client_name;
            $data[$k]["workspace_id"]             =  $Invoice->workspace_id;      
        
        }  

        return $data;
    }

    public function headings(): array
    {
        return [
            "ID",
            "invoice_id",
            "project_id",
            "status",
            "issue_date",
            "due_date",   
           "discount",
            "tax_id",
            "client_id",
            "workspace_id",
            "Created At",
            "Updated At",
        ];
    }
}

        