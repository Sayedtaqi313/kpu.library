<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use App\Models\Categroy;
use App\Models\Image;
class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publicationYear',
        'lang',
        'edition',
        'translator',
        'isbn',
        'description',
        'cat_id',
        'dep_id',
        'sec_id',
        'format',
        'barrow',
    ];

    public function section() {
        return $this->belongsTo(Section::class,'sec_id');
    }

    public function category() {
        return $this->belongsTo(Category::class,'cat_id');
    }
 
    public function image() {
        return $this->morphOne(Image::class,'imageable');
    }

}