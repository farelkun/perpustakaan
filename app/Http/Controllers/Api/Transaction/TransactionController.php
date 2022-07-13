<?php

namespace App\Http\Controllers\Api\Transaction;

use App\Helpers\Transaction\TransactionHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\TransactionRequest;
use App\Http\Resources\Transaction\TransactionCollection;
use App\Http\Resources\Transaction\TransactionResource;

class TransactionController extends Controller
{
    private $transaction;

    public function __construct()
    {
        $this->transaction = new TransactionHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'borrow_date' => $request->borrow_date ?? '',
            'return_date' => $request->return_date ?? ''
        ];
        $listCategories = $this->transaction->getAll($filter, 5, $request->sort ?? '');

        return response()->success(new TransactionCollection($listCategories));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/transaction/transactionRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $dataInput = $request->all();
        $datatransaction = $this->transaction->create($dataInput);

        if (!$datatransaction['status']) {
            return response()->failed($datatransaction['error'], 422);
        }

        return response()->success([], 'Data transaction berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $datatransaction = $this->transaction->getById($id);

        if (empty($datatransaction)) {
            return response()->failed(['Data transaction tidak ditemukan']);
        }

        return response()->success(new TransactionResource($datatransaction));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(TransactionRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/transaction/transactionRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $dataInput = $request->all();
        $dataTransaction = $this->transaction->update($dataInput, $dataInput['id']);

        if (!$dataTransaction['status']) {
            return response()->failed($dataTransaction['error']);
        }

        return response()->success(new TransactionResource($dataTransaction['data']), 'Data transaction berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataTransaction = $this->transaction->delete($id);

        if (!$dataTransaction) {
            return response()->failed(['Mohon maaf data transaction tidak ditemukan']);
        }

        return response()->success($dataTransaction);
    }
}
