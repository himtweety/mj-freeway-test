<?php

namespace App\Http\Controllers\API\Beverages;

use App\Http\Controllers\API\ApiBaseController;
use App\Models\Beverage;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class BeverageController extends ApiBaseController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
        //
        $beverages = \App\Models\Beverage::all();
        return $this->api_response_success(
                        trans('list of avaialable beverages.'),
                        compact('beverages'),
                        $this->getHTTPResponse()::HTTP_OK
        );
    }

    public function check(Request $request) {

        $beverages = $request->get('beverages');
        $collection = collect($beverages);



        $filtered = $collection->reject(function ($value, $key) {
            if (!is_numeric($value['id']) || $value['id'] < 1) {
                return true;
            }
            return ($value['drink_is_taken'] != 1 || (!is_numeric($value['qty']) || (is_numeric($value['qty']) && $value['qty'] < 1)));
        });

        if (empty($filtered)) {
            return $this->api_response_error(
                            "please select one beverage and qty greater than 0",
                            "please select one beverage and qty greater than 0",
                            422
            );
        } else if (count($filtered->toArray()) != 1) {
            return $this->api_response_error(
                            "please select only one beverage and qty greater than 0",
                            "please select only one beverage and qty greater than 0",
                            422
            );
        };
        $itemArray = [];
        $multiplied = $filtered->map(function ($item, $key) {
            $beverageData = \App\Models\Beverage::query()
                            ->where('id', $item['id'])
                            ->select('*')->first()->toArray();
            return array_merge($item, $beverageData);
        });
        $itemData = $multiplied->first();
        if (empty($itemData['grams_per_serving'])) {
            return $this->api_response_error(
                            "selected beverage not found",
                            "selected beverage not found",
                            422
            );
        }
        $caloriesConsumed = $itemData['qty'] * $itemData['grams_per_serving'] * $itemData['servings_per_bottle'];

        $safelimit = \App\Models\Beverage::SafeLimit;
        $message = 'you have already consumed the beverage to match the max limit, you should not drink more.';
        $allowed = [
            'allowedsafelimit' => 0,
            'message' => $message
        ];
        if ($safelimit > $caloriesConsumed) {
            $safeIntakeCaloriesRemaining = $safelimit - $caloriesConsumed;
            $perUnitCalorie = $itemData['servings_per_bottle'] * $itemData['grams_per_serving'];
            $safeconsumption = floor($safeIntakeCaloriesRemaining / $perUnitCalorie);
            $remaining = $safeIntakeCaloriesRemaining % $perUnitCalorie;
            if ($safeconsumption > 0) {
                $message = 'you can have ' . $safeconsumption . ' more of ' . $itemData['name'] . ' which will server  you remaining ' . ($safeconsumption * $perUnitCalorie) . ' gm of the caffiene safely.';
                if ($remaining > 0) {
                    $message .= "<br> you will still have " . $remaining . " remaining calories";
                }
                $allowed = [
                    'allowedlimit' => $safeconsumption,
                    'message' => $message
                ];
            }
        }

        return $this->api_response_success(
                        trans('allowed limit.'),
                        $allowed,
                        $this->getHTTPResponse()::HTTP_OK
        );
    }

}
