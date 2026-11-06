<?php
// app/Models/GiftDonation.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class GiftDonation extends Model
{
    protected string $table = 'gift_donations';

    public function create(int $donationId, array $data): int
    {
        $token = bin2hex(random_bytes(16));
        Database::execute(
            "INSERT INTO gift_donations (donation_id, recipient_name, recipient_email, message, card_design, share_token, created_at)
             VALUES (?,?,?,?,?,?,NOW())",
            [
                $donationId,
                $data['recipient_name'] ?? null,
                $data['recipient_email'] ?? null,
                $data['message'] ?? null,
                $data['card_design'] ?? 'elegant',
                $token,
            ]
        );
        return (int) Database::conn()->lastInsertId();
    }

    public function byToken(string $token): ?array
    {
        return Database::fetch(
            "SELECT g.*, d.amount, d.currency, d.status, d.donation_number
             FROM gift_donations g
             JOIN donations d ON d.id = g.donation_id
             WHERE g.share_token = ? AND d.status = 'paid' LIMIT 1",
            [$token]
        );
    }
}