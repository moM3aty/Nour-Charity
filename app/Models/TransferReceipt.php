<?php
// app/Models/TransferReceipt.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class TransferReceipt extends Model
{
    protected string $table = 'transfer_receipts';

    public function forDonation(int $donationId): ?array
    {
        return Database::fetch(
            "SELECT * FROM transfer_receipts WHERE donation_id = ? ORDER BY id DESC LIMIT 1",
            [$donationId]
        );
    }
}