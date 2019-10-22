<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageColorExtractor extends Model
{
    // DB columns to fill
    protected $fillable = ['img_name', 'img_colors', 'img_predominant_color', 'img_color_count'];
}
