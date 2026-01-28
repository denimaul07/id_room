<?php

namespace App\Imports;

use App\Models\ExtLd;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithUpserts;

class DataImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        $idpameran = $this->id;

        $data = [
            'idpameran' => $idpameran, 
            'noseri' => $row['noseri'],
            'nominal' => $row['nominal'],
            'probability' => $row['probability']
        ];

        return ExtLd::updateOrCreate(['noseri' => $row['noseri']], $data);
    }

    public function uniqueBy()
    {
        return 'noseri';
    }

    public function updateExistingChunkBy()
    {
        return 'noseri';
    }



}
