<?php
require_once '../autoload.php';
requireLogin();

$expense = new Expense();
$userId = $_SESSION['user_id'];

// Ambil parameter periode dari URL
$period = $_GET['period'] ?? 'this_month';
$dateFrom = $_GET['from'] ?? date('Y-m-01');
$dateTo = $_GET['to'] ?? date('Y-m-t');

// Ambil data pengeluaran
$expenses = $expense->getByDateRange($userId, $dateFrom, $dateTo);

// Set header untuk download CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="pengeluaran_' . date('Ymd_His') . '.csv"');

// Buat output stream
$output = fopen('php://output', 'w');

// Tulis BOM untuk UTF-8 (agar Excel bisa baca karakter Indonesia)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header CSV
fputcsv($output, ['No', 'Tanggal', 'Kategori', 'Deskripsi', 'Jumlah (Rp)'], ',', '"', '\\');

// Data rows
$no = 1;
$total = 0;
foreach ($expenses as $exp) {
    fputcsv($output, [
        $no++,
        date('d/m/Y', strtotime($exp['expense_date'])),
        $exp['category_name'],
        $exp['description'],
        number_format($exp['amount'], 0, ',', '.')
    ], ',', '"', '\\');
    $total += $exp['amount'];
}

// Total row
fputcsv($output, ['', '', '', 'TOTAL', number_format($total, 0, ',', '.')], ',', '"', '\\');

// Info periode
fputcsv($output, [], ',', '"', '\\');
fputcsv($output, ['Periode', date('d/m/Y', strtotime($dateFrom)) . ' - ' . date('d/m/Y', strtotime($dateTo))], ',', '"', '\\');
fputcsv($output, ['Jumlah Transaksi', count($expenses)], ',', '"', '\\');
fputcsv($output, ['Total Pengeluaran', 'Rp ' . number_format($total, 0, ',', '.')], ',', '"', '\\');
fputcsv($output, ['Diekspor pada', date('d/m/Y H:i:s')], ',', '"', '\\');

fclose($output);
exit();
?>