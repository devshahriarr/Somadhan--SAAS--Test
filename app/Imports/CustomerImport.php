<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['name'])) {

                $customer = Customer::where('name', $row['name'])->first();
                if ($customer) {

                    $customer->update([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'branch_id' => $row['branch_id'] ?? 1,
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'address' => $row['address'],
                        'opening_payable' => $row['opening_payable'] ?? 0,
                        'opening_receivable' => $row['opening_receivable'] ?? 0,
                        'wallet_balance' => $row['wallet_balance'] ?? 0,
                        'total_receivable' => $row['total_receivable'] ?? 0,
                        'total_payable' => $row['total_payable'] ?? 0,
                        'party_type' => $row['party_type'],
                    ]);
                } else {
                    Customer::create([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'branch_id' => $row['branch_id'] ?? 1,
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'address' => $row['address'],
                        'opening_payable' => $row['opening_payable'] ?? 0,
                        'opening_receivable' => $row['opening_receivable'] ?? 0,
                        'wallet_balance' => $row['wallet_balance'] ?? 0,
                        'total_receivable' => $row['total_receivable'] ?? 0,
                        'total_payable' => $row['total_payable'] ?? 0,
                        'party_type' => $row['party_type'],
                    ]);
                }
            } else {
                $notification = [
                    'warning' => 'Not Inserted',
                    'alert-type' => 'info',
                ];

                return redirect()->back()->with($notification);
            }
        }
    }
}
