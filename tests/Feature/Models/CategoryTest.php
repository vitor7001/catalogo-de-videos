<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    use DatabaseMigrations;

    public function testList()
    {

        factory(Category::class, 1)->create();
        $categories = Category::all();
        $this->assertCount(1, $categories);
        $categoryKeys = array_keys($categories->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'description', 'is_active', 'created_at', 'updated_at', 'deleted_at'
        ], $categoryKeys);
    }

    public function testCreate()
    {
        $category = Category::create([
            'name' => 'teste'
        ]);

        $category->refresh();
        $this->assertEquals('teste', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);


        $category = Category::create([
            'name' => 'teste',
            'description' => null
        ]);
        $this->assertNull($category->description);


        $category = Category::create([
            'name' => 'teste',
            'description' => 'teste_description'
        ]);
        $this->assertEquals('teste_description',$category->description);

        $category = Category::create([
            'name' => 'teste',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);

        $category = Category::create([
            'name' => 'teste',
            'is_active' => true
        ]);
        $this->assertTrue($category->is_active);
    }
}
