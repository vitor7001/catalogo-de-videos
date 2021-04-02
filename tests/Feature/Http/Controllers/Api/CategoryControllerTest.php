<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class CategoryControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations;

    private $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = factory(Category::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('categories.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->category->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('categories.show', ['category' => $this->category->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->category->toArray());
    }

    public function testInvalidationData()
    {
        $data = [
            'name' => ''
        ];
        $this->assertValidationInStoreAction($data, 'required');
        $this->assertValidationInUpdateAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256)
        ];
        $this->assertValidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertValidationInUpdateAction($data, 'max.string', ['max' => 255]);

        $data = [
            'is_active' => 'a'
        ];
        $this->assertValidationInStoreAction($data, 'boolean');
        $this->assertValidationInUpdateAction($data, 'boolean');
    }

    protected function assertInvalidationRequired(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'required');
        $response
            ->assertJsonMissingValidationErrors(['is_active']);
    }

    protected function assertInvalidationMax(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['name'], 'max.string', ['max' => 255]);
    }

    protected function assertInvalidationBoolean(TestResponse $response)
    {
        $this->assertInvalidationFields($response, ['is_active'], 'boolean');
    }

    protected function routeStore()
    {
        return route('categories.store');
    }

    protected function routeUpdate()
    {
        return route('categories.update');
    }
}
