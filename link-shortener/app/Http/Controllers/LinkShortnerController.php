<?php

namespace App\Http\Controllers;

use App\Exceptions\UrlNotFoundException;
use App\Http\Requests\DecodeLinkRequest;
use App\Http\Requests\EncodeLinkRequest;
use App\Services\ShortnerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LinkShortnerController extends Controller
{
    /**
     * shortner
     *
     * @var ShortnerService
     */
    protected ShortnerService $shortner;

    /**
     * __construct
     *
     * @param ShortnerService $shortner
     */
    public function __construct(ShortnerService $shortner)
    {
        $this->shortner = $shortner;
    }

    /**
     * store
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(EncodeLinkRequest $request): JsonResponse
    {
        return $this->urlHandler($request, "encode");
    }

    /**
     * show
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function show(DecodeLinkRequest $request): JsonResponse
    {
        return $this->urlHandler($request, "decode");
    }

    /**
     * urlHandler
     *
     * @param Request $request
     * @param string $type
     *
     * @return JsonResponse
     */
    protected function urlHandler(Request $request, string $type): JsonResponse
    {
        // catch and issues associated with accessing redis
        try {
            $link = $this->shortner->$type($request->input('url'));
        } catch (UrlNotFoundException $e) {
            return $this->errorResponse($e->getMessage(), class_basename($e), 404);
        } catch (\RedisException $e) {
            return $this->errorResponse($e->getMessage(), class_basename($e), 500);
        }

        return response()->jsonac([
            "link" => $link
        ]);
    }

    /**
     * errorResponse
     *
     * @param string $message
     * @param string $exception
     * @param int $status
     *
     * @return JsonResponse
     */
    protected function errorResponse(string $message, string $exception, int $status): JsonResponse
    {
        return response()->json([
            'exception' => $exception,
            'msg' => $message,
            'status' => $status
        ], $status);
    }
}
