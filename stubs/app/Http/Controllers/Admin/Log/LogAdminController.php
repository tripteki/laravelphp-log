<?php

namespace App\Http\Controllers\Admin\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use Tripteki\Log\Contracts\Repository\Admin\ILogRepository;
use App\Exports\Logs\LogExport;
use App\Http\Requests\Admin\Logs\LogShowValidation;
use Tripteki\Helpers\Http\Requests\FileExportValidation;
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

    /**
     * @OA\Get(
     *      path="/admin/logs-export",
     *      tags={"Admin Log"},
     *      summary="Export",
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="file",
     *          schema={"type": "string", "enum": {"csv", "xls", "xlsx"}},
     *          description="Log's File."
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success."
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity."
     *      )
     * )
     *
     * @param \Tripteki\Helpers\Http\Requests\FileExportValidation $request
     * @return mixed
     */
    public function export(FileExportValidation $request)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        if ($form["file"] == "csv") {

            $data = Excel::download(new LogExport(), "Log.csv", \Maatwebsite\Excel\Excel::CSV);

        } else if ($form["file"] == "xls") {

            $data = Excel::download(new LogExport(), "Log.xls", \Maatwebsite\Excel\Excel::XLS);

        } else if ($form["file"] == "xlsx") {

            $data = Excel::download(new LogExport(), "Log.xlsx", \Maatwebsite\Excel\Excel::XLSX);
        }

        return $data;
    }
};
