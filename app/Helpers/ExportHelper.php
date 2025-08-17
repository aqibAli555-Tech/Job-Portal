<?php

namespace App\Helpers;

class ExportHelper
{
    public static function exportInExcel($data = [], $headers = [], $filename = 'export')
    {
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"{$filename}.xls\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        if (!empty($headers)) {
            fputcsv($output, $headers, "\t");
        }

        foreach ($data as $row) {
            fputcsv($output, $row, "\t");
        }

        fclose($output);
        exit;
    }
}
