<?php

namespace App\Http\Controllers;

use App\Contracts\OccupationParser;
use App\Http\Requests\OccupationCompareRequest;
use App\Services\MatchingService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Exception;

class OccupationsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $occparser;
    private $matchingService;

    public function __construct(OccupationParser $parser, MatchingService $matchingService)
    {
        $this->occparser = $parser;
        $this->matchingService = $matchingService;
    }

    public function index()
    {
        return $this->occparser->list();
    }

    public function compare(OccupationCompareRequest $request) : array
    {
        try{
            $this->occparser->setScope('skills');
            $occupation_1= $this->occparser->get($request->get('occupation_1'));
            $occupation_2= $this->occparser->get($request->get('occupation_2'));
        } catch(Exception $e) {
            //save the error message in log file.
            Log::error($e->getMessage());
            throw new Exception("Ooops, We've got some errors");
        }
        /** IMPLEMENT COMPARISON **/
        $match = $this->matchingService->getMatch($occupation_1, $occupation_2);
        /** IMPLEMENT COMPARISON **/

        return [
            // Not used in front-end
            'occupation_1' => $occupation_1,
            'occupation_2' => $occupation_2,
            'match' => $match,
        ];
    }
}
