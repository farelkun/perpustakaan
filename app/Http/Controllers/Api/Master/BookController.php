<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\Master\BookHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\CreateRequest;
use App\Http\Requests\Book\UpdateRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Book\DetailResource;

class BookController extends Controller
{
    protected $book;

    public function __construct()
    {
        $this->book = new BookHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ['name' => $request->name ?? ''];
        $books = $this->book->getAll($filter, 5, $request->sort ?? '');

        return response()->success(new BookCollection($books));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        /**
        * Menampilkan pesan error ketika validasi gagal
        * pengaturan validasi bisa dilihat pada class app/Http/request/User/CreateRequest
        */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $dataInput = $request->all();
        $dataBook = $this->book->create($dataInput);

        if (!$dataBook['status']) {
            return response()->failed($dataBook['error'], 422);
        }

        return response()->success([], 'Data book berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataBook = $this->book->getById($id);

        if (empty($dataBook)) {
            return response()->failed(['Data book tidak ditemukan']);
        }

        return response()->success(new DetailResource($dataBook));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/User/UpdateRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $dataInput = $request->all();
        $dataBook = $this->book->update($dataInput, $dataInput['id']);

        if (!$dataBook['status']) {
            return response()->failed($dataBook['error']);
        }

        return response()->success(new BookResource($dataBook['data']), 'Data book berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataBook = $this->book->delete($id);

        if (!$dataBook) {
            return response()->failed(['Mohon maaf data book tidak ditemukan']);
        }

        return response()->success($dataBook);
    }
}
