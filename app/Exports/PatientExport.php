<?php

namespace App\Exports;

use App\Patient;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PatientExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $type;
    private $gender;
    private $marital_status;
    private $religion;
    private $from;
    private $to;

    public function __construct(Request $request)
    {
        $this->type = $request->type;
        $this->gender = $request->gender;
        $this->marital_status = $request->marital_status;
        $this->religion = $request->religion;
        $this->from = $request->from;
        $this->to = $request->to;
    }

    public function query()
    {
        $patientQuery = Patient::query();
        if($this->type != '' )
        {
            $type = $this->type;
            $from =  \Carbon\Carbon::parse($this->from)->format('Y-m-d')." 00:00:00";
            $to = \Carbon\Carbon::parse( $this->to)->format('Y-m-d')." 23:59:59";
            $patientQuery->whereHas('registration', function ($q) use($type,$from,$to){
                $q->where('detain', $type)->whereBetween('created_at',[$from,$to]);
            });
        }
        if($this->gender != '' )
        {
            $patientQuery->where('gender', $this->gender);
        }
        if($this->marital_status != '' )
        {
            $patientQuery->where('marital_status', $this->marital_status);
        }
        if($this->religion != '' )
        {
            $patientQuery->where('religion', $this->religion);
        }
        return $patientQuery;
    }

    public function map($patient): array
    {
        return [
            $patient->title,
            $patient->first_name." ".$patient->other_name." ".$patient->last_name,
            $patient->folder_number,
            $patient->date_of_birth,
            $patient->gender,
            $patient->marital_status,
            $patient->other_information,
            $patient->postal_address,
            $patient->house_number,
            $patient->locality,
            $patient->phone_number,
            $patient->occupation,
            $patient->religion,
            $patient->name_of_nearest_relative,
            $patient->number_of_nearest_relative,

        ];
    }

    public function headings(): array
    {
        return [
            'Title',
            'Name','Folder Number',
            'Date of Birth',
            'Gender',
            'Marital Status',
            'Other Information',
            'Postal Address',
            'House Number',
            'Locality',
            'Phone Number',
            'Occupation',
            'Religion',
            'Nearest Relative','Number of Nearest Relative',
        ];
    }
}
