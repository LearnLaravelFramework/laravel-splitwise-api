<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        //
    }

    /**
     * API Add Expense
     *
     * @param Request $request
     */
    public function add_expense(Request $request){
        if($request->isJson()){
            $data = json_decode($request->getContent(),1);
            $share_type = $data['share_type'];
            switch ($share_type){
                case "EQUAL":
                    $return = $this->__add_equal_share($data,$request);
                    break;
                case "EXACT":
                    $return = $this->__add_exact_share($data,$request);
                    break;
                case "PERCENT":
                    $return = $this->__add_percent_share($data,$request);
                    break;
            }
            if($return['success']){
                return array('success'=>true,'msg'=>$return['msg']);
            }
            else{
                return array('success'=>false,'msg'=>$return['msg']);
            }
        }
        return response()->json(['data' => 'bad_request'], 400);
    }
    private function __add_equal_share($data,Request $request){
        DB::beginTransaction();
        $friend_ids = $data['shares'][0]['friend_ids'];
        $userId = Auth::id();
        $user = Auth::user();
        try{
            $share_basis = "EQUAL"."|".$data['amount']."|".implode(",",$friend_ids);
            $Shares = [];
            $equal_amount =  round($data['amount'] / count($friend_ids),2);
            foreach ($friend_ids as $friend_id){
                $ExpenseShare = new ExpenseShare();
                $ExpenseShare->user_id = $userId;
                $ExpenseShare->friend_id = $friend_id;
                $ExpenseShare->share_amount = $equal_amount;
                $ExpenseShare->share_type = $data['share_type'];
                $Shares[] = $ExpenseShare;
            }

            $Expense = new Expense();
            $Expense->user_id = $userId;
            $Expense->name = $data['name'];
            $Expense->amount = $data['amount'];
            $Expense->share_type = 'EQUAL';
            $Expense->share_basis = $share_basis;
            $Expense->save();
            $Expense->expense_shares()->saveMany($Shares);
            DB::commit();
            return ['success'=>true,'msg'=>'Expense Has been Stored'];
        }
        catch (\Exception $e){
            //dd($e);
            DB::rollBack(); // something went wrong
        }
        //dd( $data);
        return ['success'=>false,'msg'=>'Some Error Occurred'];
    }

    private function __add_exact_share($data,Request $request){
        DB::beginTransaction();
        //dd($data);
        $friend_shares = $data['shares'];
        $userId = Auth::id();
        try{
            //dd($friend_shares);
            $Shares = [];
            $amounts = [];
            $friend_ids = [];
            foreach ($friend_shares as $friend_share){
                $friend_ids[] = $friend_share['friend_id'];
                $ExpenseShare = new ExpenseShare();
                $ExpenseShare->user_id = $userId;
                $ExpenseShare->friend_id = $friend_share['friend_id'];
                $amounts[] = $friend_share['share_amount'];
                $ExpenseShare->share_amount = $friend_share['share_amount'];
                $ExpenseShare->share_type = 'EXACT';
                $Shares[] = $ExpenseShare;
            }

            if(array_sum($amounts) != $data['amount']){
                return ['success'=>false,'msg'=>'Shares Sum Should Match Base Amount'];
            }

            $share_basis = "EXACT"."|".implode(",",$amounts)."|".implode(",",$friend_ids);

            $Expense = new Expense();
            $Expense->user_id = $userId;
            $Expense->name = $data['name'];
            $Expense->amount = $data['amount'];
            $Expense->share_type = 'EXACT';
            $Expense->share_basis = $share_basis;

            $Expense->save();
            $Expense->expense_shares()->saveMany($Shares);
            DB::commit();
            return ['success'=>true,'msg'=>'Expense Has been Stored'];
        }
        catch (\Exception $e){
            //dd($e);
            DB::rollBack(); // something went wrong
        }
        return ['success'=>false,'msg'=>'Some Error Occurred'];
    }

    private function __add_percent_share($data,Request $request){
        DB::beginTransaction();
        //dd($data);
        $friend_shares = $data['shares'];
        $userId = Auth::id();
        try{

            $Shares = [];
            $amounts = [];
            $percentages = [];
            $friend_ids = [];
            foreach ($friend_shares as $friend_share){
                $friend_ids[] = $friend_share['friend_id'];
                $ExpenseShare = new ExpenseShare();
                $ExpenseShare->user_id = $userId;
                $ExpenseShare->friend_id = $friend_share['friend_id'];
                $ExpenseShare->share_amount = round(($data['amount'] * $friend_share['share_percent'] / 100),2);

                $percentages[] = $friend_share['share_percent'];
                $amounts[] = $ExpenseShare->share_amount;

                $ExpenseShare->share_type = 'PERCENT';
                $Shares[] = $ExpenseShare;
            }

            if(array_sum($percentages) != 100){
                return ['success'=>false,'msg'=>'Percentage Should be equal to 100'];
            }
            if(array_sum($amounts) != $data['amount']){
                return ['success'=>false,'msg'=>'Shares Sum Should Match Base Amount'];
            }

            $share_basis = "PERCENT"."|".implode(",",$percentages)."|".implode(",",$amounts)."|".implode(",",$friend_ids);

            $Expense = new Expense();
            $Expense->user_id = $userId;
            $Expense->name = $data['name'];
            $Expense->amount = $data['amount'];
            $Expense->share_type = 'PERCENT';
            $Expense->share_basis = $share_basis;

            //dd($Expense);
            //dd($Shares);
            $Expense->save();
            $Expense->expense_shares()->saveMany($Shares);
            DB::commit();
            return ['success'=>true,'msg'=>'Expense Has been Stored'];
        }
        catch (\Exception $e){
            DB::rollBack(); // something went wrong
        }
        return ['success'=>false,'msg'=>'Some Error Occurred'];
    }

    public function get_expenses(Request $request){
        $userId = Auth::id();
        $user = Auth::user();

        $expenses = Expense::with("expense_shares")->get();
        $total_expenses = DB::table("expenses")->where("user_id",Auth::id())->sum("amount");
        $friends = DB::table("expense_shares")->select("friend_id","share_amount")->where("user_id",Auth::id())->get();
        $balances = [];
        foreach ($friends as $friend){
            if(isset($balances[$friend->friend_id])){
                $balances[$friend->friend_id]+= round($friend->share_amount,2);
            }
            else{
                $balances[$friend->friend_id] = round($friend->share_amount,2);
            }
        }
        $allBalances = [];
        $details = [];
        foreach ($balances as $friend_id => $balance){
            $friend = User::find($friend_id);
            $balanceRounded =  round($balance,2);
            $allBalances[] = $friend->name." Owes ". $user->name. " a Total of ".$balanceRounded;
            $details[] = ['friend'=>$friend->name,'friend_id'=>$friend->id,'email'=>$friend->email,'balance'=>$balanceRounded];
        }
        $data = [
            'total_expenses' => $total_expenses,
            'balances' => $allBalances,
            'details' => $details
        ];
        return response()->json($data, 400);
    }
}
