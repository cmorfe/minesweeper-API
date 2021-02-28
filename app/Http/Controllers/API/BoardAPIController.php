<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Repositories\BoardRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

/**
 * Class BoardController
 * @package App\Http\Controllers\API
 */
class BoardAPIController extends AppBaseController
{
    /** @var  BoardRepository */
    private $boardRepository;

    public function __construct(BoardRepository $boardRepo)
    {
        $this->middleware('auth:sanctum');

        $this->boardRepository = $boardRepo;
    }

    /**
     * @return JsonResponse
     *
     * @SWG\Get(
     *      path="/boards",
     *      summary="Get a listing of the Boards.",
     *      tags={"Board"},
     *      description="Get all Boards",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Board")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(): JsonResponse
    {
        $boards = $this->boardRepository->all();

        return $this->sendResponse(BoardResource::collection($boards), 'Boards retrieved successfully');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     *
     * @SWG\Post(
     *      path="/boards",
     *      summary="Store a newly created Board in storage",
     *      tags={"Board"},
     *      description="Store Board",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Board that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Board")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Board"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        try {
            $this->validateCreate($input);
        } catch (ValidationException $e) {
            return $this->sendError($e->getMessage(), $e->status, $e->errors());
        }
        $board = $this->boardRepository->create($input);

        return $this->sendResponse(new BoardResource($board->append('game_squares')), 'Board saved successfully');
    }

    /**
     * @param  int  $id
     * @return JsonResponse
     *
     * @SWG\Get(
     *      path="/boards/{id}",
     *      summary="Display the specified Board",
     *      tags={"Board"},
     *      description="Get Board",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Board",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Board"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var Board $board */
        $board = $this->boardRepository->find($id);

        if (empty($board)) {
            return $this->sendError('Board not found');
        }

        return $this->sendResponse(new BoardResource($board->append('game_squares')), 'Board retrieved successfully');
    }

    /**
     * @param  int  $id
     * @param  Request  $request
     * @return JsonResponse
     *
     * @SWG\Put(
     *      path="/boards/{id}",
     *      summary="Update the specified Board in storage",
     *      tags={"Board"},
     *      description="Update Board",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Board",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Board that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Board")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Board"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, Request $request): JsonResponse
    {
        $input = $request->all();

        /** @var Board $board */
        $board = $this->boardRepository->find($id);

        if (empty($board)) {
            return $this->sendError('Board not found');
        }

        $board = $this->boardRepository->update($input, $id);

        return $this->sendResponse(new BoardResource($board), 'Board updated successfully');
    }

    /**
     * @param  array  $input
     * @return array
     * @throws ValidationException
     *
     */
    private function validateCreate(array $input): array
    {
        return Validator::make($input, Board::$rules)
            ->after(function ($validator) use ($input) {
                if (is_int($input['width']) && is_int($input['height'])) {
                    $maxMines = $input['width'] * $input['height'];

                    if ($input['mines'] > $maxMines) {
                        $validator->errors()->add('mines',
                            "The number of mines must be less than or equal {$maxMines}.");
                    }
                }
            })->validate();
    }
}
