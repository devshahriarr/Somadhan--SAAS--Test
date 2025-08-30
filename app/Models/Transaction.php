<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;

class Transaction extends Model
{
    use UsesTenantModel;
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    //
    public function supplier()
    {
        return $this->belongsTo(Customer::class, 'supplier_id', 'id');
    }

    //
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'payment_method', 'id');
    }

    //
    public function investor()
    {
        return $this->belongsTo(Investor::class, 'others_id', 'id');
    }

    //
    public function particularData()
    {
        $particulars = $this->particulars;

        // Check if particulars start with 'Sale#'
        if (strpos($particulars, 'Sale#') !== false) {
            preg_match('/\d+/', $particulars, $matches); // Extract the ID
            $saleId = $matches[0] ?? null;

            return $saleId ? Sale::find($saleId) : null; // Return sale data

            // Check if particulars start with 'Purchase#'
        } elseif (strpos($particulars, 'Purchase#') !== false) {
            preg_match('/\d+/', $particulars, $matches); // Extract the ID
            $purchaseId = $matches[0] ?? null;

            return $purchaseId ? Purchase::find($purchaseId) : null; // Return purchase data

            // Check for specific cases like 'Adjust Due Collection' or 'Return'
        } elseif (strpos($particulars, 'Adjust Due Collection') !== false || strpos($particulars, 'Return') !== false) {
            return Returns::find($this->others_id); // Match with others_id and return return data
        }

        // Default case: return null if no match
        return null;
    }

    public function getSaleIdAttribute()
    {
        // Extract the sale ID from 'particulars' (e.g., 'Sale#1')
        $particulars = $this->particulars;

        // If 'Sale#' exists, extract the numeric part
        if (strpos($particulars, 'Sale#') !== false) {
            preg_match('/\d+/', $particulars, $matches);

            return $matches[0] ?? null; // Return the sale ID
        }

        return null; // Return null if the format doesn't match
    }

    public function sale()
    {
        // Use the extracted sale_id to define the relationship

        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'reference_id');
    }

    public function getPurchaseIdAttribute()
    {
        if (strpos($this->particulars, 'Purchase#') === 0) {
            return (int) str_replace('Purchase#', '', $this->particulars);
        }

        return null;
    }
}
