<?php

namespace App\Imports;

use App\Models\Barang;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class BarangImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithBatchInserts,
    WithChunkReading
{
    use SkipsFailures;

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $row = $this->normalizeHeaders($row->toArray());

            $row = collect($row)
                ->filter(fn($value, $key) => !empty(trim((string)$key)) && $value !== null)
                ->mapWithKeys(fn($value, $key) => [trim($key) => $value])
                ->toArray();

            if (empty($row) || collect($row)->filter()->isEmpty()) {
                Log::info('⛔ Melewati baris kosong sepenuhnya');
                continue;
            }

            $id   = $row['id'] ?? null;
            $nama = $row['名'] ?? $row['nama'] ?? null;

            if ($nama !== null && mb_detect_encoding($nama, 'UTF-8', true) === false) {
                $nama = mb_convert_encoding($nama, 'UTF-8');
            }

            if ($nama === null || trim((string) $nama) === '') {
                Log::warning("⚠️ Baris [$index] tanpa nama di-skip:", $row);
                continue;
            }

            $satuan           = $row['単位'] ?? $row['satuan'] ?? null;
            $stok_dipesan     = $this->parseNumericValue($row['在庫注文'] ?? $row['stok_dipesan'] ?? null);
            $stok_tersedia    = $this->parseNumericValue($row['利用可能な在庫'] ?? $row['stok_tersedia'] ?? null);
            $stok_dibutuhkan  = $this->parseNumericValue($row['在庫が必要です'] ?? $row['stok_dibutuhkan'] ?? null);
            $created_at_raw   = $row['日付'] ?? $row['created_at'] ?? null;

            $created_at = $this->parseDate($created_at_raw);

            $where = $id ? ['id' => $id] : ['nama' => $nama];

            Barang::updateOrInsert(
                $where,
                [
                    'nama'            => $nama,
                    'satuan'          => $satuan,
                    'stok_dipesan'    => $stok_dipesan,
                    'stok_tersedia'   => $stok_tersedia,
                    'stok_dibutuhkan' => $stok_dibutuhkan,
                    'created_at'      => $created_at,
                    'updated_at'      => now(),
                ]
            );

            Log::info("✅ Row [$index] berhasil diimpor:", [
                'id'              => $id,
                'nama'            => $nama,
                'satuan'          => $satuan,
                'stok_dipesan'    => $stok_dipesan,
                'stok_tersedia'   => $stok_tersedia,
                'stok_dibutuhkan' => $stok_dibutuhkan,
                'created_at'      => $created_at->toDateTimeString(),
            ]);
        }
    }

    private function parseNumericValue($value)
    {
        if ($value === null || $value === '') return 0;
        if (is_numeric($value)) return (int)$value;

        $cleaned = preg_replace('/[^0-9.-]/', '', $value);
        return is_numeric($cleaned) ? (int)$cleaned : 0;
    }

    private function parseDate($value)
    {
        try {
            if (empty($value)) return now();
            if (is_string($value)) return Carbon::parse($value);
            if (is_numeric($value)) return Carbon::createFromTimestamp(($value - 25569) * 86400);
            return now();
        } catch (\Exception $e) {
            Log::warning("❗ Gagal parsing tanggal: " . $e->getMessage());
            return now();
        }
    }

    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            '名' => ['required_without:nama', 'string', 'max:255'],
            'nama' => ['required_without:名', 'string', 'max:255'],
            '単位' => ['nullable', 'string'],
            'satuan' => ['nullable', 'string'],
            '在庫注文' => ['nullable', 'numeric', 'min:0'],
            'stok_dipesan' => ['nullable', 'numeric', 'min:0'],
            '利用可能な在庫' => ['nullable', 'numeric', 'min:0'],
            'stok_tersedia' => ['nullable', 'numeric', 'min:0'],
            '在庫が必要です' => ['nullable', 'numeric', 'min:0'],
            'stok_dibutuhkan' => ['nullable', 'numeric', 'min:0'],
            '日付' => ['nullable'],
            'created_at' => ['nullable'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        Log::info("🧾 Data mentah baris [$index]:", $data);

        $data = $this->normalizeHeaders($data);

        $filteredData = collect($data)
            ->filter(fn($value, $key) => !empty(trim((string) $key)) && $value !== null)
            ->toArray();

        Log::info("🧪 Validasi baris [$index]:", $filteredData);

        if (empty($filteredData)) return [];

        $hasValidName = isset($filteredData['名']) && trim((string) $filteredData['名']) !== '' ||
                        isset($filteredData['nama']) && trim((string) $filteredData['nama']) !== '';

        return $hasValidName ? $filteredData : [];
    }

    public function customValidationMessages()
    {
        return [
            '名.required_without' => 'Kolom nama (名) harus diisi',
            'nama.required_without' => 'Kolom nama harus diisi',
            '在庫注文.numeric' => 'Stok dipesan harus berupa angka',
            'stok_dipesan.numeric' => 'Stok dipesan harus berupa angka',
            '利用可能な在庫.numeric' => 'Stok tersedia harus berupa angka',
            'stok_tersedia.numeric' => 'Stok tersedia harus berupa angka',
            '在庫が必要です.numeric' => 'Stok dibutuhkan harus berupa angka',
            'stok_dibutuhkan.numeric' => 'Stok dibutuhkan harus berupa angka',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    private function normalizeHeaders(array $row): array
    {
        $aliases = [
            'id' => ['id'],
            '名' => ['名', 'nama'],
            '単位' => ['単位', 'satuan'],
            '在庫注文' => ['在庫注文', 'stok_dipesan'],
            '利用可能な在庫' => ['利用可能な在庫', 'stok_tersedia'],
            '在庫が必要です' => ['在庫が必要です', 'stok_dibutuhkan'],
            '日付' => ['日付', 'created_at'],
        ];

        $normalized = [];

        foreach ($aliases as $target => $candidates) {
            foreach ($candidates as $alias) {
                if (array_key_exists($alias, $row)) {
                    $normalized[$target] = $row[$alias];
                    break;
                }
            }
        }

        return array_merge($row, $normalized);
    }
}
