<?php
/**
 * Script untuk membuat struktur folder proyek
 * Jalankan file ini di browser: http://localhost/tugas%20akhir/setup_folders.php
 */

$base_dir = __DIR__;
$folders = [
    'src/config',
    'src/core', 
    'src/pages',
    'src/process',
    'docs',
    'uploads'
];

echo "<h2>Membuat Struktur Folder Proyek</h2>";
echo "<pre>";

foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "✅ Folder dibuat: $folder\n";
        } else {
            echo "❌ Gagal membuat folder: $folder\n";
        }
    } else {
        echo "ℹ️  Folder sudah ada: $folder\n";
    }
}

echo "</pre>";
echo "<p><strong>Langkah selanjutnya:</strong> Pindahkan file-file ke folder yang sesuai atau buka halaman ini.</p>";
?>
