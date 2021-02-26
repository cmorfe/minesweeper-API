<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Board;

class BoardApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_board()
    {
        $board = Board::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/boards', $board
        );

        $this->assertApiResponse($board);
    }

    /**
     * @test
     */
    public function test_read_board()
    {
        $board = Board::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/boards/'.$board->id
        );

        $this->assertApiResponse($board->toArray());
    }

    /**
     * @test
     */
    public function test_update_board()
    {
        $board = Board::factory()->create();
        $editedBoard = Board::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/boards/'.$board->id,
            $editedBoard
        );

        $this->assertApiResponse($editedBoard);
    }

    /**
     * @test
     */
    public function test_delete_board()
    {
        $board = Board::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/boards/'.$board->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/boards/'.$board->id
        );

        $this->response->assertStatus(404);
    }
}
