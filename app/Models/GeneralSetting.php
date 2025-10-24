<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GeneralSetting extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function getLogo()
    {
        return $this->logo ? Storage::url('general/' . $this->logo) : null;
    }
    public function getFavicon()
    {
        return $this->favicon ? Storage::url('general/' . $this->favicon) : null;
    }
    public function getLogoHeight()
    {
        return $this->logo_height ?  $this->logo_height : '60';
    }
}
