<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportCsv extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'month',
        'year'
    ];

    public function imports(){
        return $this->hasMany(ImportCsvDetail::class, 'import_csvs_id');
    }
}
