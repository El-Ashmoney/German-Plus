<?php

namespace App\Imports;

use App\Models\Word;
use Maatwebsite\Excel\Concerns\ToModel;

class WordsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Word|null
     */
    public function model(array $row)
    {
        return new Word([
            'german' => $row[0],
            'english' => $row[1],
            'note' => $row[2],
            'sound' => $row[3],
            'is_valid' => false,
        ]);
    }
}
