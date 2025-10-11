<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class QuestionImport implements ToModel, WithStartRow
{
    // cache slug -> category_id (hemat query)
    protected array $catCache = [];

    // Lewati baris header
    public function startRow(): int { return 2; }

    public function model(array $row)
    {
        // 0) CATEGORY
        $name = trim((string) ($row[0] ?? ''));
        $slug = Str::slug(preg_replace('/\s+/', ' ', $name));

        if (!isset($this->catCache[$slug])) {
            // kalau tabelmu belum punya kolom category_slug, ganti key pertamaOrCreate ke ['category_name' => $name]
            $category = Category::firstOrCreate(
                ['category_slug' => $slug],
                ['category_name' => $name]
            );
            $this->catCache[$slug] = $category->id;
        }

        // 1) NORMALISASI correct_option (harus A/B/C/D/E satu huruf)
        $correct = strtoupper(trim((string) ($row[9] ?? '')));
        // buang karakter selain A-E, ambil 1 huruf pertama
        $correct = substr(preg_replace('/[^A-E]/i', '', $correct), 0, 1);

        return new Question([
            'category_id'    => $this->catCache[$slug],

            'disease'        => trim((string) ($row[1]  ?? '')),
            'vignette'       => trim((string) ($row[2]  ?? '')),
            'question_text'  => trim((string) ($row[3]  ?? '')),

            'option_a'       => trim((string) ($row[4]  ?? '')),
            'option_b'       => trim((string) ($row[5]  ?? '')),
            'option_c'       => trim((string) ($row[6]  ?? '')),
            'option_d'       => trim((string) ($row[7]  ?? '')),
            'option_e'       => trim((string) ($row[8]  ?? '')),

            'correct_option' => $correct, // pasti 1 char A-E
            'explanation'    => isset($row[10]) ? trim((string) $row[10]) : null,
        ]);
    }
}
