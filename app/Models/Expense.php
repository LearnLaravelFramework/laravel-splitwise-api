<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'expenses';


    /***
     * Get Shares for an Expense
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function expense_shares(){
        return $this->hasMany(ExpenseShare::class);
    }
}
