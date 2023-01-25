<?php

namespace App\Http\Controllers\Admin\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tripteki\Log\Contracts\Repository\Admin\ILogRepository;
use App\Http\Requests\Admin\Logs\LogShowValidation;
use Tripteki\Helpers\Http\Controllers\Controller;

class LogAdminController extends Controller
{
    /**
     * @var \Tripteki\Log\Contracts\Repository\Admin\ILogRepository
     */
    protected $logAdminRepository;

    /**
     * @param \Tripteki\Log\Contracts\Repository\Admin\ILogRepository $logAdminRepository
     * @return void
     */
    public function __construct(ILogRepository $logAdminRepository)
    {
        $this->logAdminRepository = $logAdminRepository;
    }

    /**
     * @OA\Get(
     *      path="/admin/logs",
     *      tags={"Admin Log"},
     *      summary="Index",
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="limit",
     *          description="Log's Pagination Limit."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="current_page",
     *          description="Log's Pagination Current Page."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="order",
     *          description="Log's Pagination Order."
     *      ),
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="filter[]",
     *          description="Log's Pagination Filter."
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      )
     * )
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $data = [];
        $statecode = 200;

        $data = $this->logAdminRepository->all();

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Get(
     *      path="/admin/logs/{log}",
     *      tags={"Admin Log"},
     *      summary="Show",
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="log",
     *          description="Log's Log."
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found."
     *      )
     * )
     *
     * @param \App\Http\Requests\Admin\Logs\LogShowValidation $request
     * @param string $log
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(LogShowValidation $request, $log)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        $data = $this->logAdminRepository->get($log);

        return iresponse($data, $statecode);
    }
};
