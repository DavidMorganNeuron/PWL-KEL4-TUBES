<?php

namespace App\Http\Controllers\Concerns;

// trait ini mengeliminasi duplikasi resolveStockTable antara OrderFlowController dan PaymentController
trait ResolvesStockTable
{
    // menghasilkan nama tabel stok cabang dari nama cabang. contoh: "Dr. Mansyur" → "stock_branch_dr_mansyur"
    private function resolveStockTable(string $branchName): string
    {
        $normalized = strtolower(preg_replace('/[\s.]+/', '_', trim($branchName)));
        $normalized = preg_replace('/[^a-z0-9_]/', '', $normalized);

        return 'stock_branch_' . $normalized;
    }
}