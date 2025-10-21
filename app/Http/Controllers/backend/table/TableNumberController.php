<?php

namespace App\Http\Controllers\backend\table;

use App\Dtos\TableNumberDto;
use App\Exceptions\TableNumberNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\backend\tables\CreateTableNumberRequest;
use App\Http\Requests\backend\tables\UpdateTableNumberRequest;
use App\Http\Resources\Backend\tables\TableNumberResource;
use App\Services\implementation\TableNumberServiceImplementation;
use Illuminate\Http\JsonResponse;

class TableNumberController extends Controller
{
    protected TableNumberServiceImplementation $tableNumberService;

    public function __construct(TableNumberServiceImplementation $tableNumberService)
    {
        $this->tableNumberService = $tableNumberService;
    }

    public function index(): JsonResponse
    {
        $table = $this->tableNumberService->getAllTableNumbers();
        return response()->json([
            'message' => 'List all Table Numbers',
            'data' => TableNumberResource::collection($table),
        ]);
    }

    public function store(CreateTableNumberRequest $request): JsonResponse
    {
        $dto = new TableNumberDto(...$request->validated());
        $table = $this->tableNumberService->createTableNumber($dto);
        return response()->json([
            'message' => 'Table number created',
            'data' => new TableNumberResource($table),
        ], 201);
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        $table = $this->tableNumberService->getTableNumberById($id);
        return response()->json([
            'message' => 'Table number retrieved',
            'data' => new TableNumberResource($table),
        ]);
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function update(UpdateTableNumberRequest $request, int $id): JsonResponse
    {
        $dto = new TableNumberDto(...$request->validated());
        $table = $this->tableNumberService->updateTableNumber($dto, $id);
        return response()->json([
            'message' => 'Table number updated',
            'data' => new TableNumberResource($table),
        ]);
    }

    /**
     * @throws TableNumberNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        $this->tableNumberService->deleteTableNumber($id);
        return response()->json([
            'message' => 'Table number deleted',
        ]);
    }
}
