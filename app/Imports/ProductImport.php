<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;

class ProductImport implements ToCollection
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        // Skip header row (assuming first row is headers)
        $rows->shift();

        foreach ($rows as $row) {
            DB::table('products')->insert([
    'code'        => $row[0],
    'name'        => $row[1],
    'price'       => is_numeric($row[2]) ? $row[2] : 0, // safe numeric
    'category_id' => $this->getCategoryId($row[3]) ?? 1, // fallback to 1 if not found
    'unit_id'     => 1, // default unit
    'alert'       => 0, // default alert
    'active'      => 1,
    'description' => $row[4] ?? '', // optional
    'created_at'  => now(),
    'updated_at'  => now(),
]);

        }
    }

    /**
     * Helper to get category ID by category name
     */
    private function getCategoryId($categoryName)
    {
        return DB::table('categories')->where('name', $categoryName)->value('id') ?? null;
    }
}
