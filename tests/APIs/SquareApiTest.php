<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Square;

class SquareApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_square()
    {
        $square = Square::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/squares', $square
        );

        $this->assertApiResponse($square);
    }

    /**
     * @test
     */
    public function test_read_square()
    {
        $square = Square::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/squares/'.$square->id
        );

        $this->assertApiResponse($square->toArray());
    }

    /**
     * @test
     */
    public function test_update_square()
    {
        $square = Square::factory()->create();
        $editedSquare = Square::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/squares/'.$square->id,
            $editedSquare
        );

        $this->assertApiResponse($editedSquare);
    }

    /**
     * @test
     */
    public function test_delete_square()
    {
        $square = Square::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/squares/'.$square->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/squares/'.$square->id
        );

        $this->response->assertStatus(404);
    }
}
