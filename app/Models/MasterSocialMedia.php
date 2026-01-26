<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSocialMedia extends Model
{
    protected $table = 'master_social_medias';
    
    // Hapus icon_path, tambah platform
    protected $fillable = ['name', 'platform', 'link_url', 'is_active'];
}
