<?php

namespace App\Exports;

use App\Drug;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DrugExport implements  FromQuery, WithMapping, WithHeadings
{
    private $type;
    private $unit_of_pricing;
    private $supplier_id;

    public function __construct(Request $request)
    {
        $this->type = $request->type_id;
        $this->unit_of_pricing = $request->unit_of_pricing;
        $this->supplier_id = $request->supplier_id;
    }

    public function query()
    {
        $drugQuery = Drug::query();
        if($this->type != '' )
        {
            $drugQuery->where('drug_type_id', $this->type);
        }
        if($this->unit_of_pricing != '' )
        {
            $drugQuery->where('unit_of_pricing',$this->unit_of_pricing);
        }
        if($this->supplier_id != '' )
        {
            $drugQuery->where('supplier_id', $this->supplier_id);
        }
        return $drugQuery;
    }

    public function map($drug): array
    {
        return [
            $drug->name,
            $drug->drug_type->name,
            $drug->qty_in_stock,
            $drug->unit_of_pricing,
            $drug->no_of_tablet,
            $drug->qty_in_tablet,
            $drug->supplier->name,
            $drug->cost_price,
            $drug->retail_price,
            $drug->nhis_amount,
            $drug->expiry_date,

        ];
    }

    public function headings(): array
    {
        return [
            'Drug Name',
            'Drug Type',
            'Qty in Stock',
            'Unit of Pricing',
            'No of Tablet',
            'Qty in Tablet',
            'Supplier',
            'Cost Price',
            'Retail Price',
            'NHIS',
            'Expiry Date',
        ];
    }
}
