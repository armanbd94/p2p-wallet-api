<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardSummaryController extends Controller
{
    public function most_conversion_user()
    {
        try {
            $data = DB::select('SELECT  t.from_user_id AS user_id,u.name,u.email,COUNT(t.from_user_id) AS total_convertion FROM transactions as t 
                    INNER JOIN users as u ON t.from_user_id=u.id GROUP BY t.from_user_id ORDER BY COUNT(from_user_id) DESC LIMIT 1');
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function user_total_converted_amount($user_id)
    {
        try {
            $data = DB::select('SELECT  t.from_user_id AS user_id,u.name,u.email,SUM(t.transfered_amount) AS total_converted_amount FROM transactions as t 
                    INNER JOIN users as u ON t.from_user_id=u.id WHERE t.from_user_id=? GROUP BY t.from_user_id',[$user_id]);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function user_third_highest_transaction_amount($user_id)
    {
        try {

            $number = 3;// For third highest
            $data = DB::select('SELECT t1.from_user_id AS user_id,t1.transfered_amount AS transaction_amount FROM
                transactions AS t1 WHERE ? = (SELECT COUNT(DISTINCT t2.transfered_amount) FROM transactions AS t2 
                WHERE t2.transfered_amount > t1.transfered_amount  AND t2.from_user_id=?) AND t1.from_user_id=?',[($number-1),$user_id,$user_id]);
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }



}
