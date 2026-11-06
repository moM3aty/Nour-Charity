<?php
// app/Models/BankAccount.php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

final class BankAccount extends Model
{
    protected string $table = 'bank_accounts';

    public function active(): array
    {
        return Database::fetchAll("SELECT * FROM bank_accounts WHERE is_active = 1 ORDER BY sort_order, id");
    }

    public function allOrdered(): array
    {
        return Database::fetchAll("SELECT * FROM bank_accounts ORDER BY sort_order, id");
    }
}