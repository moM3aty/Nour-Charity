<?php
// app/Models/Donation.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class Donation extends Model
{
    protected string $table = 'donations';

    public function createDonation(array $data): int
    {
        $donationNumber = generate_ref('DON');
        $sql = "INSERT INTO donations
                (donation_number, donor_id, project_id, campaign_id, amount, currency,
                 donation_type, message, is_anonymous, status, payment_method, created_at)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW())";
        Database::execute($sql, [
            $donationNumber,
            $data['donor_id']      ?? null,
            $data['project_id']    ?? null,
            $data['campaign_id']   ?? null,
            $data['amount'],
            $data['currency']      ?? 'SAR',
            $data['donation_type'] ?? 'one_time',
            $data['message']       ?? null,
            $data['is_anonymous']  ?? 0,
            'pending',
            $data['payment_method'] ?? 'bank_transfer',
        ]);
        $id = (int) Database::conn()->lastInsertId();

        if (!empty($data['project_id'])) {
            Database::execute(
                "INSERT INTO donation_items (donation_id, project_id, amount, quantity) VALUES (?,?,?,1)",
                [$id, $data['project_id'], $data['amount']]
            );
        }
        return $id;
    }

    public function createFromCart(array $donor, array $items, string $paymentMethod = 'bank_transfer'): int
    {
        $total = 0.0;
        foreach ($items as $it) {
            $total += (float) ($it['amount'] ?? 0);
        }
        if ($total <= 0) {
            throw new \RuntimeException('قيمة التبرع غير صحيحة');
        }

        $donationNumber = generate_ref('DON');
        Database::execute(
            "INSERT INTO donations
             (donation_number, donor_id, amount, currency, donation_type, message, is_anonymous, status, payment_method, created_at)
             VALUES (?,?,?,?,?,?,?,?,?,NOW())",
            [
                $donationNumber,
                $donor['donor_id'] ?? null,
                $total,
                'SAR',
                'general',
                $donor['message']    ?? null,
                $donor['is_anonymous'] ?? 0,
                'pending',
                $paymentMethod,
            ]
        );
        $donationId = (int) Database::conn()->lastInsertId();

        foreach ($items as $it) {
            $amount = (float) ($it['amount'] ?? 0);
            if ($amount <= 0) continue;
            Database::execute(
                "INSERT INTO donation_items (donation_id, project_id, amount, quantity) VALUES (?,?,?,1)",
                [$donationId, !empty($it['project_id']) ? (int)$it['project_id'] : null, $amount]
            );
        }
        return $donationId;
    }

    public function byNumber(string $number): ?array
    {
        return Database::fetch(
            "SELECT d.*, p.title AS project_title, p.slug AS project_slug,
                    c.title AS campaign_title, c.slug AS campaign_slug
             FROM donations d
             LEFT JOIN projects p ON p.id = d.project_id
             LEFT JOIN campaigns c ON c.id = d.campaign_id
             WHERE d.donation_number = ? LIMIT 1",
            [$number]
        );
    }

    public function items(int $donationId): array
    {
        return Database::fetchAll(
            "SELECT di.*, p.title AS project_title, p.slug AS project_slug
             FROM donation_items di
             LEFT JOIN projects p ON p.id = di.project_id
             WHERE di.donation_id = ?",
            [$donationId]
        );
    }

    /**
     * ✅ إصلاح حرج: تحديث raised_amount للحملات أيضاً (كانت مفقودة)
     */
    public function updateStatus(int $id, string $status, ?int $userId = null, ?string $reason = null): bool
    {
        $allowed = ['pending','awaiting_verification','paid','failed','cancelled','refunded'];
        if (!in_array($status, $allowed, true)) return false;

        $old = Database::fetch(
            "SELECT status, amount, project_id, campaign_id FROM donations WHERE id = ?",
            [$id]
        );
        if (!$old) return false;

        $verifiedAt = in_array($status, ['paid'], true) ? 'NOW()' : 'NULL';
        $verifiedBy = $userId ?: 'NULL';

        Database::execute(
            "UPDATE donations
             SET status = ?, verified_at = $verifiedAt, verified_by = $verifiedBy
             WHERE id = ?",
            [$status, $id]
        );

        $wasPaid = ($old['status'] === 'paid');
        $isPaid  = ($status === 'paid');
        $delta   = (float)$old['amount'];

        // ---- المشروع ----
        if ($isPaid && !$wasPaid && $old['project_id']) {
            Database::execute(
                "UPDATE projects SET raised_amount = raised_amount + ? WHERE id = ?",
                [$delta, $old['project_id']]
            );
        }
        if ($wasPaid && !$isPaid && $old['project_id']) {
            Database::execute(
                "UPDATE projects SET raised_amount = GREATEST(0, raised_amount - ?) WHERE id = ?",
                [$delta, $old['project_id']]
            );
        }

        // ---- ✅ الحملة (الإصلاح الجديد) ----
        if ($isPaid && !$wasPaid && !empty($old['campaign_id'])) {
            Database::execute(
                "UPDATE campaigns SET raised_amount = raised_amount + ? WHERE id = ?",
                [$delta, $old['campaign_id']]
            );
        }
        if ($wasPaid && !$isPaid && !empty($old['campaign_id'])) {
            Database::execute(
                "UPDATE campaigns SET raised_amount = GREATEST(0, raised_amount - ?) WHERE id = ?",
                [$delta, $old['campaign_id']]
            );
        }

        \App\Core\AuditLog::record(
            'donation.status_change',
            'donation',
            $id,
            "Status: {$old['status']} → $status" . ($reason ? " | $reason" : '')
        );
        return true;
    }

    public function paginate(array $filters = [], int $page = 1, int $per = 20): array
    {
        $where = ['1=1'];
        $params = [];
        if (!empty($filters['status'])) { $where[] = "d.status = ?"; $params[] = $filters['status']; }
        if (!empty($filters['project_id'])) { $where[] = "d.project_id = ?"; $params[] = $filters['project_id']; }
        if (!empty($filters['from'])) { $where[] = "d.created_at >= ?"; $params[] = $filters['from']; }
        if (!empty($filters['to'])) { $where[] = "d.created_at <= ?"; $params[] = $filters['to'] . ' 23:59:59'; }
        if (!empty($filters['q'])) {
            $where[] = "(d.donation_number LIKE ? OR dn.name LIKE ? OR dn.email LIKE ?)";
            $q = '%' . $filters['q'] . '%';
            $params[] = $q; $params[] = $q; $params[] = $q;
        }
        $w = implode(' AND ', $where);
        $offset = max(0, ($page - 1) * $per);

        $items = Database::fetchAll(
            "SELECT d.*, p.title AS project_title, dn.name AS donor_name, dn.email AS donor_email
             FROM donations d
             LEFT JOIN projects p ON p.id = d.project_id
             LEFT JOIN donors dn ON dn.id = d.donor_id
             WHERE $w
             ORDER BY d.created_at DESC
             LIMIT $per OFFSET $offset",
            $params
        );
        $total = (int) (Database::fetch(
            "SELECT COUNT(*) AS c FROM donations d LEFT JOIN donors dn ON dn.id = d.donor_id WHERE $w",
            $params
        )['c'] ?? 0);

        return ['items' => $items, 'total' => $total, 'pages' => (int)ceil($total / $per), 'page' => $page];
    }

    public function summary(): array
    {
        return [
            'paid_total'      => (float) (Database::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM donations WHERE status='paid'")['s'] ?? 0),
            'pending_total'   => (float) (Database::fetch("SELECT COALESCE(SUM(amount),0) AS s FROM donations WHERE status IN('pending','awaiting_verification')")['s'] ?? 0),
            'paid_count'      => (int) (Database::fetch("SELECT COUNT(*) AS c FROM donations WHERE status='paid'")['c'] ?? 0),
            'pending_count'   => (int) (Database::fetch("SELECT COUNT(*) AS c FROM donations WHERE status IN('pending','awaiting_verification')")['c'] ?? 0),
        ];
    }
}