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

        //VERIFICAÇÕES UUID
        //Lógica: pesquisei como validar e acabei caindo na regex que estou utilizando
        $this->assertNotEmpty($category->id);
        $this->assertTrue((bool)preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $category->id));
        $this->assertEquals(36, strlen($category->id));
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

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'description' => 'test_description',
            'is_active' => false
        ]);

        $data = [
            'name' => 'test_name_update',
            'description' => 'test_description_update',
            'is_active' => true
        ];
        $category->update($data);

        foreach($data as $key => $value){
            $this->assertEquals($value, $category->{$key});
        }
    }

    public function testDelete()
    {
        $categoryCreated = factory(Category::class, 5)->create()->first();

        Category::destroy($categoryCreated->id);

        $allCategories = Category::all();

        foreach($allCategories as $category){
            $this->assertNotEquals($category->id, $categoryCreated->id);
        }
    }

    public function testeSoftDelete()
    {
        $category = factory(Category::class)->create();

        $category->delete();
        $this->assertNull(Category::find($category->id));

        $category->restore();
        $this->assertNotNull(Category::find($category->id));
    }
}
