<?php

namespace App\Http\Controllers\Log;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tripteki\Log\Contracts\Repository\ILogRepository;
use App\Http\Requests\Logs\LogShowValidation;
use App\Http\Requests\Logs\LogUpdateValidation;
use Tripteki\Helpers\Http\Controllers\Controller;

class LogController extends Controller
{
    /**
     * @var \Tripteki\Log\Contracts\Repository\ILogRepository
     */
    protected $logRepository;

    /**
     * @param \Tripteki\Log\Contracts\Repository\ILogRepository $logRepository
     * @return void
     */
    public function __construct(ILogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    /**
     * @OA\Get(
     *      path="/logs",
     *      tags={"Logs"},
     *      summary="Index",
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *          required=false,
     *          in="query",
     *          name="type",
     *          schema={"type": "string", "enum": {"archived", "unarchived"}},
     *          description="Log's Type."
     *      ),
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

        $this->logRepository->setUser($request->user());

        $data = $this->logRepository->all();

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Get(
     *      path="/logs/{log}",
     *      tags={"Logs"},
     *      summary="Show",
     *      security={{ "bearerAuth": {} }},
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
     * @param \App\Http\Requests\Logs\LogShowValidation $request
     * @param string $log
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(LogShowValidation $request, $log)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 200;

        $this->logRepository->setUser($request->user());

        $data = $this->logRepository->get($log);

        return iresponse($data, $statecode);
    }

    /**
     * @OA\Put(
     *      path="/logs/{context}",
     *      tags={"Logs"},
     *      summary="Update",
     *      security={{ "bearerAuth": {} }},
     *      @OA\Parameter(
     *          required=true,
     *          in="path",
     *          name="context",
     *          schema={"type": "string", "enum": {"archive", "unarchive"}},
     *          description="Log's Context."
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="logs",
     *                      type="array",
     *                      @OA\Items(type="string"),
     *                      description="Log's Logs."
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created."
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity."
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found."
     *      )
     * )
     *
     * @param \App\Http\Requests\Logs\LogUpdateValidation $request
     * @param string $context
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(LogUpdateValidation $request, $context)
    {
        $form = $request->validated();
        $data = [];
        $statecode = 202;

        $this->logRepository->setUser($request->user());

        if ($this->logRepository->getUser()) {

            foreach ($form["logs"] as $log) {

                if ($context == LogUpdateValidation::ARCHIVE) $data[] = $this->logRepository->archive($log);
                else if ($context == LogUpdateValidation::UNARCHIVE) $data[] = $this->logRepository->unarchive($log);
            }

            if ($data) {

                $statecode = 201;
            }
        }

        return iresponse($data, $statecode);
    }
};
