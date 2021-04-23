<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GenreTest extends TestCase
{

    use DatabaseMigrations;

    public function testList()
    {

        factory(Genre::class, 1)->create();
        $genres = Genre::all();
        $this->assertCount(1, $genres);
        $categoryKeys = array_keys($genres->first()->getAttributes());
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'is_active', 'created_at', 'updated_at', 'deleted_at'
        ], $categoryKeys);
    }

    public function testCreate()
    {
        $genre = Genre::create([
            'name' => 'teste'
        ]);

        $genre->refresh();

        $this->assertNotEmpty($genre->id);
        $this->assertTrue((bool)preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $genre->id));
       
        $this->assertEquals('teste', $genre->name);
        $this->assertTrue($genre->is_active);
    
        $genre = Genre::create([
            'name' => 'teste'
        ]);

        $genre  = Genre::create([
            'name' => 'teste',
            'is_active' => false
        ]);
        $this->assertFalse($genre ->is_active);

        $genre  = Genre::create([
            'name' => 'teste',
            'is_active' => true
        ]);
        $this->assertTrue($genre ->is_active);
        
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'name' => 'name_genre',
            'is_active' => false
        ]);

        $data = [
            'name' => 'name_genre_update',
            'is_active' => true
        ];
        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }
    }

    public function testDelete()
    {
        $genteCreated = factory(Genre::class, 5)->create()->first();

        Genre::destroy($genteCreated->id);

        $allGenres = Genre::all();

        foreach ($allGenres as $genre) {
            $this->assertNotEquals($genre->id, $genteCreated->id);
        }
    }

    public function testeSoftDelete()
    {
        $genre = factory(Genre::class)->create();

        $genre->delete();
        $this->assertNull(Genre::find($genre->id));

        $genre->restore();
        $this->assertNotNull(Genre::find($genre->id));
    }
}
