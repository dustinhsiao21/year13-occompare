<?php

namespace App\Http\Controllers;

use App\Contracts\OccupationParser;
use App\Http\Requests\OccupationCompareRequest;
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

    public function __construct(OccupationParser $parser)
    {
        $this->occparser = $parser;
    }

    public function index()
    {
        return $this->occparser->list();
    }

    public function compare(OccupationCompareRequest $request) : array
    {
        $this->occparser->setScope('skills');
        $occupation_1 = $this->occparser->get($request->get('occupation_1'));
        $occupation_2 = $this->occparser->get($request->get('occupation_2'));

        /** IMPLEMENT COMPARISON **/
        if(count($occupation_1) == 0 || count($occupation_2) == 0) {
            Log::error("Occupation One: " . json_encode($occupation_1).
            "Occupation Two:" . json_encode($occupation_2));
            throw new Exception("Ooops, We've got some errors");
        }
        
        $match = 0;
        $firstJob = [];
        
        foreach($occupation_1 as $skill_1) {
            $firstJob[$skill_1['label']] = $skill_1['value'];
        }

        foreach($occupation_2 as $skill_2) {
            if(isset($firstJob[$skill_2['label']])) {
                $value = (100 - abs(($firstJob[$skill_2['label']] - $skill_2['value']))) / 100;
                $match+=$value;
            }
        }

        $match = round(($match / count($occupation_1)) * 100, 2);
        /** IMPLEMENT COMPARISON **/

        return [
            'occupation_1' => $occupation_1,
            'occupation_2' => $occupation_2,
            'match' => $match
        ];
    }
}
