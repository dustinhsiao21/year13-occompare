<?php
namespace App\Services;

class MatchingService 
{
    public function getMatch(array $occupation_1, array $occupation_2) : float
    {
        // use occupation_1 to create new array structure: [['label' => 'value'], []...];
        $occupationOneArray = [];
        foreach($occupation_1 as $skill){
            $occupationOneArray[$skill['label']] = $skill['value'];
        }

        $matchingArray = [];
        foreach($occupation_2 as $skill_2) {
            // if label did match, calculate the matching value  = 1 - ( abs(value1 - value2) / max(value1, value2));
            if(isset($occupationOneArray[$skill_2['label']])) {
                $matchingArray[$skill_2['label']] = 
                1 - ((abs($occupationOneArray[$skill_2['label']] - $skill_2['value']))/ max($occupationOneArray[$skill_2['label']], $skill_2['value']));
            }
        }
        // sum of matching value / the number of occupation_1 + the number of occupation_2 - the number of matcning skills
        return round((array_sum($matchingArray) / (count($occupation_1) + count($occupation_2) - count($matchingArray))) * 100, 2);

    }
}