<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Prompts\Prompt;

class ProductImage extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_id',
        'image'
    ];


    public function product() : BelongsTo{
        return $this->belongsTo(Prompt::class);
    }
}
