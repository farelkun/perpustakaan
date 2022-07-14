<?php

namespace App\Http\Controllers\Api\Master;

use App\Helpers\Master\BookCategoryHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Resources\BookCategory\BookCategoryCollection;
use App\Http\Resources\BookCategory\BookCategoryResource;

class BookCategoryController extends Controller
{
    private $category;

    public function __construct()
    {
        $this->category = new BookCategoryHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ['nama' => $request->nama ?? ''];
        $listCategories = $this->category->getAll($filter, 2, $request->sort ?? '');

        return response()->success(new BookCategoryCollection($listCategories));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Category/CategoryRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors(), 422);
        }

        $dataInput = $request->all();
        $dataCategory = $this->category->create($dataInput);

        if (!$dataCategory['status']) {
            return response()->failed($dataCategory['error'], 422);
        }

        return response()->success([], 'Data category berhasil disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dataCategory = $this->category->getById($id);

        if (empty($dataCategory)) {
            return response()->failed(['Data category tidak ditemukan']);
        }

        return response()->success(new BookCategoryResource($dataCategory));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Category/CategoryRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $dataInput = $request->all();
        $dataCategory = $this->category->update($dataInput, $dataInput['id']);

        if (!$dataCategory['status']) {
            return response()->failed($dataCategory['error']);
        }

        return response()->success(new BookCategoryResource($dataCategory['data']), 'Data category berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dataCategory = $this->category->delete($id);

        if (!$dataCategory) {
            return response()->failed(['Mohon maaf data category tidak ditemukan']);
        }

        return response()->success($dataCategory);
    }
}
