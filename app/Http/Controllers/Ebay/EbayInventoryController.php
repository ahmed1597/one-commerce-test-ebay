<?php

namespace App\Http\Controllers\Ebay;

use Illuminate\Http\Request;
use App\Models\EbayInventoryItem;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\EbayInventoryItemResource;
use App\Application\Ebay\Services\EbayTokenService;
use App\Application\Ebay\Actions\SyncEbayInventoryAction;
use App\Domain\Ebay\Exceptions\EbayNotConnectedException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Domain\Ebay\Exceptions\EbayTokenRefreshFailedException;
use App\Infrastructure\Ebay\Repositories\EbayInventoryItemRepository;

final class EbayInventoryController extends Controller
{
    public function __construct(
        private EbayTokenService $tokens,
        private EbayInventoryItemRepository $repo,
    ) {}

    /**
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $conn = $this->tokens->getValidConnectionOrFail();
        } catch (EbayNotConnectedException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        } catch (EbayTokenRefreshFailedException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

        $perPage = (int) $request->query('per_page', 25);

        $paginator = $this->repo->paginateForConnection($conn, $perPage);

        return response()->json(
            EbayInventoryItemResource::collection($paginator)->response()->getData(true)
        );
    }

    public function sync(SyncEbayInventoryAction $action): JsonResponse
    {
        try {
            $maxPages = (int) request()->integer('maxPages', 2);
            $pageSize = (int) request()->integer('limit', 50);

            $maxPages = max(1, min($maxPages, 20));
            $pageSize = max(1, min($pageSize, 200));

            return response()->json($action->execute($maxPages, $pageSize));
        } catch (EbayNotConnectedException|EbayTokenRefreshFailedException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
}
