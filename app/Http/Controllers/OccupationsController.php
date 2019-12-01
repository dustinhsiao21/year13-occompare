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
        try{
            $this->occparser->setScope('skills');
            $occupation_1 = $this->occparser->get($request->get('occupation_1'));
            $occupation_2 = $this->occparser->get($request->get('occupation_2'));
        } catch(Exception $e) {
            //save the error message in log file.
            Log::error($e->getMessage());
            throw new Exception("Ooops, We've got some errors");
        }

        /** IMPLEMENT COMPARISON **/
        $match = 0;

        foreach($occupation_2 as $label => $value) {
            // if label did match, calculate the matching value
            if(isset($occupation_1[$label])) {
                $value = (100 - abs(($occupation_1[$label] - $value))) / 100;
                $match += $value;
            }
        }

        // calculate matching percentage
        $match = round(($match / count($occupation_1)) * 100, 2);
        /** IMPLEMENT COMPARISON **/

        return [
            // Not used in front-end
            // 'occupation_1' => $occupation_1,
            // 'occupation_2' => $occupation_2,
            'match' => $match,
        ];
    }
}
