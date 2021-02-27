<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateBoardAPIRequest;
use App\Http\Requests\API\UpdateBoardAPIRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Repositories\BoardRepository;
use App\Rules\MaxMinesRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Response;
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
     * @param  Request  $request
     * @return Response
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
    public function index(Request $request)
    {
        $boards = $this->boardRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(BoardResource::collection($boards), 'Boards retrieved successfully');
    }

    /**
     * @param  CreateBoardAPIRequest  $request
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

        return $this->sendResponse(new BoardResource($board), 'Board saved successfully');
    }

    /**
     * @param  int  $id
     * @return Response
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
    public function show($id)
    {
        /** @var Board $board */
        $board = $this->boardRepository->find($id);

        if (empty($board)) {
            return $this->sendError('Board not found');
        }

        return $this->sendResponse(new BoardResource($board), 'Board retrieved successfully');
    }

    /**
     * @param  int  $id
     * @param  UpdateBoardAPIRequest  $request
     * @return Response
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
    public function update($id, UpdateBoardAPIRequest $request)
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
     * @param  int  $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/boards/{id}",
     *      summary="Remove the specified Board from storage",
     *      tags={"Board"},
     *      description="Delete Board",
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
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Board $board */
        $board = $this->boardRepository->find($id);

        if (empty($board)) {
            return $this->sendError('Board not found');
        }

        $board->delete();

        return $this->sendSuccess('Board deleted successfully');
    }

    /**
     * @param  array  $input
     * @return array
     * @throws ValidationException
     *
     */
    private function validateCreate(array $input): array
    {
        Validator::make($input, Board::$rules)->validate();

        return Validator::make($input, [
            'mines' => [
                'required', 'integer', 'min:1', new MaxMinesRule($input['width'], $input['height'])
            ]
        ])->validate();
    }
}
