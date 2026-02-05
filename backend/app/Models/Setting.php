<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Socials;
use App\Models\Faq;
use App\Models\Mitra;
use App\Models\Services;
use App\Models\Portofolio;
use App\Models\ProcessWork;
use App\Models\Testimoni;
use App\Models\Membership;

class Setting extends Model
{
    use HasFactory;
    protected $table   = 'site_settings';
    protected $hidden  = ['id'];
    protected $guarded = [];  

    public function socials()
    {
        return $this->hasMany(Socials::class, 'odata_setting', 'odata')->where('isActive', 0);
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class, 'odata_setting', 'odata')->where('isActive', 0);
    }

    public function mitras()
    {
        return $this->hasMany(Mitra::class, 'odata_setting', 'odata')->where('isActive', 0);
    }

    public function services()
    {
        return $this->hasMany(Services::class, 'odata_setting', 'odata')->where('type', 'Renovasi')->where('isActive', 0);
    }

    public function servicesHome()
    {
        return $this->hasMany(Services::class, 'odata_setting', 'odata')->where('type', 'ID Room')->where('isActive', 0);
    }

    public function servicesHomeAll()
    {
        return $this->hasMany(Services::class, 'odata_setting', 'odata')->whereIn('type', ['Home'])->where('isActive', 0);
    }

    public function portofolio()
    {
        return $this->hasMany(Portofolio::class, 'odata_setting', 'odata')->where('isActive', 0);
    }

    public function processwork()
    {
        return $this->hasMany(ProcessWork::class, 'odata_setting', 'odata')->where('isActive', 0);
    }

    public function testimoni()
    {
        return $this->hasMany(Testimoni::class, 'odata_setting', 'odata')->whereJsonContains('type', 'Renovasi')->where('isActive', 0);
    }

    public function testimoniHome()
    {
        return $this->hasMany(Testimoni::class, 'odata_setting', 'odata')->whereJsonContains('type', 'ID Room')->where('isActive', 0);
    }

    public function membership()
    {
        return $this->hasMany(Membership::class, 'odata_setting', 'odata')->where('isActive', 0);
    }

}
