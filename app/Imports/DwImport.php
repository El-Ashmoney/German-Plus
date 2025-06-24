<?php

namespace App\Imports;

use App\Models\Word;
use Maatwebsite\Excel\Concerns\ToModel;

class DwImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Word|null
     */
    public function model(array $row)
    {
        return new Word([
            'german'     => $row[0],
            'arabic'    => $row[1],
            'note' => $row[2],
            'is_valid' => true,
        ]);
    }
}
