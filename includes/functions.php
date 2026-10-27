<?php
declare(strict_types=1);

function hitungNilaiAkhir(float $tugas, float $uts, float $uas): float
{
    return ($tugas * 0.30) + ($uts * 0.30) + ($uas * 0.40);
}

function tentukanGrade(float $nilaiAkhir): string
{
    if ($nilaiAkhir >= 85) {
        return "A";
    } elseif ($nilaiAkhir >= 75) {
        return "B";
    } elseif ($nilaiAkhir >= 65) {
        return "C";
    } elseif ($nilaiAkhir >= 50) {
        return "D";
    }

    return "E";
}

function tentukanStatus(float $nilaiAkhir): string
{
    return $nilaiAkhir >= 65 ? "Lulus" : "Tidak Lulus";
}

function tentukanPredikat(float $nilaiAkhir): string
{
    if ($nilaiAkhir >= 85) {
        return "Sangat Baik";
    } elseif ($nilaiAkhir >= 75) {
        return "Baik";
    } elseif ($nilaiAkhir >= 65) {
        return "Cukup";
    } elseif ($nilaiAkhir >= 50) {
        return "Kurang";
    }

    return "Sangat Kurang";
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function formatNilai(float $nilai): string
{
    return number_format($nilai, 2, ",", ".");
}
