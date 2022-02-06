<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Jobs\SendBalanceTransferredEmailJob;
use App\Traits\CurrencyList;

class TransactionController extends Controller
{
    use CurrencyList;

    public function store(TransactionRequest $request)
    {
        $from_user = User::with('currency')->findOrFail(auth()->user()->id);
        if($from_user->id != $request->to_user_id) //Check Sender Can't Transfer Money To Himself
        {
            if($from_user->balance >= $request->balance) //Check Sender Balance Is Greater or Eqaul Than Transfer Request Amount
            {
                DB::beginTransaction();
                try {
                    $converted_amount = 0;
                    $to_user = User::with('currency')->findOrFail($request->to_user_id);
                    $currencies = $this->currency_list(); //Fetch Currency Amount List
                    //Convert Transfer Amount
                    if($from_user->currency->code == 'USD')
                    {
                        $converted_amount = $request->balance * ($currencies['rates'][$to_user->currency->code] ?? 0);
                    }else{
                        $converted_amount = $request->balance / ($currencies['rates'][$from_user->currency->code] ?? 0);
                    }
                    //Check converted amount > 0
                    if($converted_amount > 0)
                    {
                        //Update Sender Balance
                        $from_user->balance -= $request->balance;
                        $from_user->update();

                        //Update Receiver Balance
                        $to_user->balance += $converted_amount;
                        $to_user->update();

                        //Keep Balance Transfer Transaction Record
                        $transaction = Transaction::create([
                            'from_user_id'      => $from_user->id,
                            'to_user_id'        => $to_user->id,
                            'transfered_amount' => $request->balance,
                            'converted_amount'  => $converted_amount,
                            'from_currency_id'  => $from_user->currency_id,
                            'to_currency_id'    => $to_user->currency_id,
                        ]);

                        if($transaction)
                        {
                            $transaction_data = Transaction::with(['from_user','to_user','to_currency'])->findOrFail($transaction->id);
                            //Dispatch Balance Transferred Email Job If Transaction Successfull
                            SendBalanceTransferredEmailJob::dispatch($transaction_data)->delay(now()->addSeconds(10));
                            $output =[
                                'success' => true,
                                'message' => 'Money transfered successfully.',
                                'data'    => $transaction
                            ];
                        }else{
                            $output =[
                                'success' => false,
                                'message' => 'Failed to transfer money.',
                            ];
                        }
                    }else{
                        $output =[
                            'success' => false,
                            'message' => 'Failed to transfer money.',
                        ];
                    }
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    $output =[
                        'success' => false,
                        'message' => $th->getMessage(),
                    ];
                }
            }else{
                $output = [
                    'success' => false,
                    'message' => 'Insufficient account balance',
                ];
            }
        }else{
            $output = [
                'success' => false,
                'message' => 'You can\'t send money to yourself',
            ];
        }
        return response()->json($output);
    }
}
