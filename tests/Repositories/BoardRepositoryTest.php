<?php namespace Tests\Repositories;

use App\Models\Board;
use App\Repositories\BoardRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class BoardRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var BoardRepository
     */
    protected $boardRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->boardRepo = \App::make(BoardRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_board()
    {
        $board = Board::factory()->make()->toArray();

        $createdBoard = $this->boardRepo->create($board);

        $createdBoard = $createdBoard->toArray();
        $this->assertArrayHasKey('id', $createdBoard);
        $this->assertNotNull($createdBoard['id'], 'Created Board must have id specified');
        $this->assertNotNull(Board::find($createdBoard['id']), 'Board with given id must be in DB');
        $this->assertModelData($board, $createdBoard);
    }

    /**
     * @test read
     */
    public function test_read_board()
    {
        $board = Board::factory()->create();

        $dbBoard = $this->boardRepo->find($board->id);

        $dbBoard = $dbBoard->toArray();
        $this->assertModelData($board->toArray(), $dbBoard);
    }

    /**
     * @test update
     */
    public function test_update_board()
    {
        $board = Board::factory()->create();
        $fakeBoard = Board::factory()->make()->toArray();

        $updatedBoard = $this->boardRepo->update($fakeBoard, $board->id);

        $this->assertModelData($fakeBoard, $updatedBoard->toArray());
        $dbBoard = $this->boardRepo->find($board->id);
        $this->assertModelData($fakeBoard, $dbBoard->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_board()
    {
        $board = Board::factory()->create();

        $resp = $this->boardRepo->delete($board->id);

        $this->assertTrue($resp);
        $this->assertNull(Board::find($board->id), 'Board should not exist in DB');
    }
}
