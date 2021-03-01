<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SquareResource;
use App\Models\Square;
use App\Repositories\SquareRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class SquareController
 * @package App\Http\Controllers\API
 */
class SquareAPIController extends AppBaseController
{
    /** @var  SquareRepository */
    private $squareRepository;

    public function __construct(SquareRepository $squareRepo)
    {
        $this->middleware('auth:sanctum');

        $this->squareRepository = $squareRepo;
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     *
     * @SWG\Post(
     *      path="/squares/{id}",
     *      summary="Open the specified Square in storage",
     *      tags={"Square"},
     *      description="Open Square",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="board",
     *          description="id of Board",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="square",
     *          description="id of Square",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=202,
     *          description="accepted operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Square"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function open(Request $request): JsonResponse
    {
        $input = $request->route()->parameters();

        $square = $this->squareRepository->open($input['board'], $input['square']);

        if (empty($square)) {
            return $this->sendError('Square not found');
        }

        return $this->sendResponse(new SquareResource($square), 'Square open successfully');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     *
     * @SWG\Post (
     *      path="/boards/{board}/squares/{square}/mark",
     *      summary="Toggle the mark of the specified Square in storage",
     *      tags={"Square"},
     *      description="Toggle mark of Square",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="board",
     *          description="id of Board",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="square",
     *          description="id of Square",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=202,
     *          description="accepted operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Square"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function mark(Request $request): JsonResponse
    {
        $input = $request->route()->parameters();

        $square = $this->squareRepository->mark($input['board'], $input['square']);

        if (empty($square)) {
            return $this->sendError('Square not found');
        }

        return $this->sendResponse(new SquareResource($square), 'Square marked successfully');
    }
}
