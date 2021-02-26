<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSquareAPIRequest;
use App\Http\Requests\API\UpdateSquareAPIRequest;
use App\Models\Square;
use App\Repositories\SquareRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\SquareResource;
use Response;

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
        $this->squareRepository = $squareRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/squares",
     *      summary="Get a listing of the Squares.",
     *      tags={"Square"},
     *      description="Get all Squares",
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
     *                  @SWG\Items(ref="#/definitions/Square")
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
        $squares = $this->squareRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(SquareResource::collection($squares), 'Squares retrieved successfully');
    }

    /**
     * @param CreateSquareAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/squares",
     *      summary="Store a newly created Square in storage",
     *      tags={"Square"},
     *      description="Store Square",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Square that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Square")
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
    public function store(CreateSquareAPIRequest $request)
    {
        $input = $request->all();

        $square = $this->squareRepository->create($input);

        return $this->sendResponse(new SquareResource($square), 'Square saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/squares/{id}",
     *      summary="Display the specified Square",
     *      tags={"Square"},
     *      description="Get Square",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Square",
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
    public function show($id)
    {
        /** @var Square $square */
        $square = $this->squareRepository->find($id);

        if (empty($square)) {
            return $this->sendError('Square not found');
        }

        return $this->sendResponse(new SquareResource($square), 'Square retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSquareAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/squares/{id}",
     *      summary="Update the specified Square in storage",
     *      tags={"Square"},
     *      description="Update Square",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Square",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Square that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Square")
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
    public function update($id, UpdateSquareAPIRequest $request)
    {
        $input = $request->all();

        /** @var Square $square */
        $square = $this->squareRepository->find($id);

        if (empty($square)) {
            return $this->sendError('Square not found');
        }

        $square = $this->squareRepository->update($input, $id);

        return $this->sendResponse(new SquareResource($square), 'Square updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/squares/{id}",
     *      summary="Remove the specified Square from storage",
     *      tags={"Square"},
     *      description="Delete Square",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Square",
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
        /** @var Square $square */
        $square = $this->squareRepository->find($id);

        if (empty($square)) {
            return $this->sendError('Square not found');
        }

        $square->delete();

        return $this->sendSuccess('Square deleted successfully');
    }
}
