<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageTripdossier extends Model
{
    public $table = "package_tripdossier";
    protected $fillable = ['name','package_id','email','phone','comment'];


}
