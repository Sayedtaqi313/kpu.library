<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
use App\Models\Categroy;
use App\Models\Image;
use App\Models\Stock;
use App\Models\Reserve;
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

    public function stock() {
        return $this->hasOne(Stock::class,'book_id');
    }

    public function reserve() {
        return $this->hasOne(Reserve::class,'book_id');
    }

}