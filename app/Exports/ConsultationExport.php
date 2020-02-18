<?php

namespace App\Exports;

use App\Consultation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class ConsultationExport implements FromQuery, WithMapping, WithHeadings,ShouldAutoSize,WithEvents
{
    use Exportable;
    private $to;
    private $from;
    private $religion;
    private $marital_status;
    private $gender;

    public function __construct(Request $request)
    {
        $this->gender = $request->gender;
        $this->marital_status = $request->marital_status;
        $this->religion = $request->religion;
        $this->from = $request->from;
        $this->to = $request->to;
    }

    public function query()
    {
        $consultQuery = Consultation::query();
        if ($this->from != '') {
            $from = \Carbon\Carbon::parse($this->from)->format('Y-m-d') . " 00:00:00";

            if (empty($this->to)){
                $to =\Carbon\Carbon::today()->format('Y-m-d'). " 00:00:00";;
            }else{
                $to = \Carbon\Carbon::parse($this->to)->format('Y-m-d') . " 23:59:59";

            }

            $consultQuery->whereBetween('created_at', [$from, $to]);
        }

        if ($this->gender  != '') {
            $gender = $this->gender;
            $consultQuery->whereHas('patient', function ($q) use ($gender) {
                $q->where('gender', $gender);
            });
        }

        if ($this->marital_status != '') {

            $marital_status = $this->marital_status;
            $consultQuery->whereHas('patient', function ($q) use ($marital_status) {
                $q->where('marital_status', $marital_status);
            });
        }

        if ($this->religion  != '') {

            $religion = $this->religion ;
            $consultQuery->whereHas('patient', function ($q) use ($religion) {
                $q->where('religion', $religion);
            });
        }
        return $consultQuery;
    }


    public function map($patient): array
    {
        $bigDiagnosis=[];
        foreach ($patient->patient->patientDiagnosis as $diagnosis){
            array_push($bigDiagnosis,$diagnosis->diagnoses['name']);
        }
        return [
            $patient->patient->first_name." ".$patient->patient->other_name." ".$patient->patient->last_name,
            $patient->patient->folder_number,
            $patient->patient->phone_number,
            implode($bigDiagnosis,','),


        ];
    }

    public function headings(): array
    {
        return [
            'Name','Folder Number',
            'Phone Number',
            'Diagnosis',

        ];
    }

    public function registerEvents(): array
    {
        return [

            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()
                    ->getStyle($cellRange)->getFont()->setSize(14);

                $spreadsheet=$event->sheet->getActiveCell()->getStyle('A1:D4')
                    ->getAlignment()->setWrapText(true);
            },
        ];
    }
}
