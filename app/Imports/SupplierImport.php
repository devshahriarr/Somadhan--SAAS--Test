<?php

namespace App\Imports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            if (isset($row['name'])) {
                $supplier = Customer::where('name', $row['name'])->first();
                if ($supplier) {
                    $supplier->update([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'branch_id' => $row['branch_id'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'address' => $row['address'],
                        'wallet_balance' => $row['wallet_balance'],
                        'party_type' => 'supplier',
                    ]);
                } else {
                    Customer::create([
                        // 'id' => $row['id'],
                        'name' => $row['name'],
                        'branch_id' => $row['branch_id'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                        'address' => $row['address'],
                        'wallet_balance' => $row['wallet_balance'],
                        'party_type' => 'supplier',
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
