<?php namespace Tests\Repositories;

use App\Models\Square;
use App\Repositories\SquareRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SquareRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var SquareRepository
     */
    protected $squareRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->squareRepo = \App::make(SquareRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_square()
    {
        $square = Square::factory()->make()->toArray();

        $createdSquare = $this->squareRepo->create($square);

        $createdSquare = $createdSquare->toArray();
        $this->assertArrayHasKey('id', $createdSquare);
        $this->assertNotNull($createdSquare['id'], 'Created Square must have id specified');
        $this->assertNotNull(Square::find($createdSquare['id']), 'Square with given id must be in DB');
        $this->assertModelData($square, $createdSquare);
    }

    /**
     * @test read
     */
    public function test_read_square()
    {
        $square = Square::factory()->create();

        $dbSquare = $this->squareRepo->find($square->id);

        $dbSquare = $dbSquare->toArray();
        $this->assertModelData($square->toArray(), $dbSquare);
    }

    /**
     * @test update
     */
    public function test_update_square()
    {
        $square = Square::factory()->create();
        $fakeSquare = Square::factory()->make()->toArray();

        $updatedSquare = $this->squareRepo->update($fakeSquare, $square->id);

        $this->assertModelData($fakeSquare, $updatedSquare->toArray());
        $dbSquare = $this->squareRepo->find($square->id);
        $this->assertModelData($fakeSquare, $dbSquare->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_square()
    {
        $square = Square::factory()->create();

        $resp = $this->squareRepo->delete($square->id);

        $this->assertTrue($resp);
        $this->assertNull(Square::find($square->id), 'Square should not exist in DB');
    }
}
