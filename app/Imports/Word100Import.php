<?php

namespace App\Imports;

use App\Models\Word;
use Maatwebsite\Excel\Concerns\ToModel;

class Word100Import implements ToModel
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
            'english'    => $row[1],
            'sound'    => $row[2],
            'is_valid' => false,
        ]);
    }
}
