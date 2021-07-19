<?php

namespace Tests\Unit\Models;

use App\Models\CastMember;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

class CastMemberUnitTest extends TestCase
{
    private $castMember;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->castMember = new CastMember();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
    }

    public function testFillable()
    {
        $fillable = ['name', 'type'];
        $this->assertEquals($fillable,  $this->castMember->getFillable());
    }

    public function testeIfUseTraits() {
        $traits = [Uuid::class, SoftDeletes::class];
        $casMemberTraits = array_keys(class_uses(CastMember::class));
        $this->assertEquals($traits, $casMemberTraits);
    }

    public function testCastsAttribute()
    {
        $casts = ['id' => 'string', 'type' => 'smallInteger'];
        $this->assertEquals($casts,  $this->castMember->getCasts());
    }

    public function testIncrementingAttribute()
    {
        $this->assertFalse( $this->castMember->getIncrementing());
    }

    public function testDatesAttribute()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date,  $this->castMember->getDates());
        }
        $this->assertCount(count($dates),  $this->castMember->getDates());
    }
}