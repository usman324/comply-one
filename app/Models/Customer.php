<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function getImage()
    {
        return $this->image ? Storage::url('customer/' . $this->image) : asset('dummy.jpeg');
    }
    public function getAvatar()
    {
        return $this->avatar ? Storage::url('customer/' . $this->avatar) : asset('dummy.jpeg');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id', 'id')->where('status', 'delivered');
    }
    public function paidPayments()
    {
        return $this->hasMany(Payment::class, 'customer_id', 'id')->where('payment_status_id', 3);
    }
    public function getTotalAmount()
    {
        $amount = $this->sales->sum('total_amount');
        return numberFormat($amount, 2);
    }
    public function getPaidAmount()
    {
        $amount = $this->paidPayments->sum('amount');
        return numberFormat($amount, 2);
    }
    public function getDueAmount()
    {
        $amount = $this->getTotalAmount() - $this->getPaidAmount();
        return numberFormat($amount, 2);
    }
     public function scopeByCustomer($query, $shops)
    {
        // if (isset($shops)) {
            return $query->whereIn('id', $shops??[]);
        // }
        return $query;
    }
}
