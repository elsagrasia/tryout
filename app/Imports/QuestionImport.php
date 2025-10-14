<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class QuestionImport implements ToModel, WithStartRow
{
    protected array $catCache = [];

    /**
     * Karena file Excel TIDAK punya header,
     * maka data dimulai dari baris pertama (1).
     */
    public function startRow(): int
    {
        return 1;
    }

    /**
     * Mapping kolom Excel ke field database Question
     */
    public function model(array $row)
    {
        // =============== 1️⃣ CATEGORY HANDLING ===============
        $name = trim((string) ($row[0] ?? ''));
        $slug = Str::slug(preg_replace('/\s+/', ' ', $name));

        if (!$name) {
            // Skip kalau kategori kosong
            return null;
        }

        if (!isset($this->catCache[$slug])) {
            // Jika category_slug tidak ada di tabelmu,
            // ubah key ini ke ['category_name' => $name]
            $category = Category::firstOrCreate(
                ['category_slug' => $slug],
                ['category_name' => $name]
            );
            $this->catCache[$slug] = $category->id;
        }

        // =============== 2️⃣ NORMALISASI CORRECT OPTION ===============
        $correct = strtoupper(trim((string) ($row[9] ?? '')));
        // Ambil hanya huruf A–E, maksimal 1 huruf
        $correct = substr(preg_replace('/[^A-E]/i', '', $correct), 0, 1);

        // Fallback aman jika kosong / tidak valid
        if (!in_array($correct, ['A', 'B', 'C', 'D', 'E'])) {
            $correct = 'A';
        }

        // =============== 3️⃣ BUILD QUESTION ===============
        return new Question([
            'category_id'    => $this->catCache[$slug],
            'disease'        => trim((string) ($row[1] ?? '')),
            'vignette'       => trim((string) ($row[2] ?? '')),
            'question_text'  => trim((string) ($row[3] ?? '')),
            'option_a'       => trim((string) ($row[4] ?? '')),
            'option_b'       => trim((string) ($row[5] ?? '')),
            'option_c'       => trim((string) ($row[6] ?? '')),
            'option_d'       => trim((string) ($row[7] ?? '')),
            'option_e'       => trim((string) ($row[8] ?? '')),
            'correct_option' => $correct,
            'explanation'    => isset($row[10]) ? trim((string) $row[10]) : null,
        ]);
    }
}
