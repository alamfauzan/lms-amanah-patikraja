<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tugas;
use Illuminate\Support\Facades\Storage;

// 1. Create a dummy PDF content and save it in the public disk
$attachmentsDir = 'tugas/attachments';
$fileName = 'sample_instruksi.pdf';
$filePath = $attachmentsDir . '/' . $fileName;

$dummyPdfContent = "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << >> /Contents 4 0 R >>\nendobj\n4 0 obj\n<< /Length 43 >>\nstream\nBT /F1 24 Tf 100 700 Td (Instruksi Tugas Latihan) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000056 00000 n \n0000000111 00000 n \n0000000212 00000 n \ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n306\n%%EOF";

Storage::disk('public')->put($filePath, $dummyPdfContent);

// 2. Update all assignments in the database to use this attachment
$updated = Tugas::query()->update([
    'file_path' => $filePath
]);

echo "Successfully created sample attachment and updated {$updated} tugas records!\n";
