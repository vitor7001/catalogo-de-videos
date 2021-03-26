<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Traits\Uuid;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTest extends TestCase
{

    public function testFillableAttribute()
    {
        $fillable = ['name', 'description', 'is_active'];
        $category = new Category();
        $this->assertEquals($fillable, $category->getFillable());
    }

    public function testIfUseTraitsAttribute()
    {
        $traits = [
            SoftDeletes::class, Uuid::class
        ];

        $categoryTraits = array_keys(class_uses(Category::class));

        $this->assertEquals($traits, $categoryTraits);
    }


    public function testKeyTypeAttribute()
    {
        $keyType = 'string';
        $category = new Category();
        $this->assertEquals($keyType, $category->getKeyType());
    }


    public function testIncrementsAttribute()
    {
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        $category = new Category();

        foreach($dates as $date){
            $this->assertContains($date, $category->getDates());
        }

        $this->assertCount(count($dates), $category->getDates());
    }
}
