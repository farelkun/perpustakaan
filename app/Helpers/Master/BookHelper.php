<?php

namespace App\Helpers\Master;

use App\Models\Master\BookModel;
use App\Repository\CrudInterface;

/**
 * Helper untuk manajemen item / menu / produk
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_item
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class BookHelper implements CrudInterface
{
    protected $bookModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
    }

    /**
     * Mengambil data item dari tabel m_item
     *
     * @author Wahyu Agung <wahyuagung26@gmail.com>
     *
     * @param  array $filter
     * $filter['nama'] = string
     * $filter['email'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->bookModel->getAll($filter, $itemPerPage, $sort);
    }

    /**
     * Mengambil 1 data item dari tabel m_item
     *
     * @param  integer $id id dari tabel m_item
     * @return object
     */
    public function getById(int $id): object
    {
        return $this->bookModel->getById(($id));
    }

    /**
     * method untuk menginput data baru ke tabel m_item
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     * $payload['nama'] = string
     * $payload['email] = string
     * $payload['is_verified] = string
     *
     * @return void
     */
    public function create(array $payload): array
    {
        try {
            $payload['m_category_id'] = $payload['book_category']['id'];

            if (!empty($payload['cover'])) {
                $cover = $payload['cover']->store('upload/cover', 'public');
                $payload['cover'] = $cover;
                // $folderPath = "upload/coverItem";

                // $image_parts = explode(";base64,", $payload['cover']);
                // $image_type_aux = explode("image/", $image_parts[0]);
                // $image_type = $image_type_aux[1];
                // $image_base64 = base64_decode($image_parts[1]);
                // $file = $folderPath . '/' . uniqid() . '.' . $image_type;

                // Storage::disk('public')->put($file, $image_base64);
                // $payload['cover'] = $file;
            }
            $newBook = $this->bookModel->store($payload);

            return [
                'status' => true,
                'data' => $newBook
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * method untuk mengubah item pada tabel m_item
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     * $payload['nama'] = string
     * $payload['email] = string
     * $payload['password] = string
     *
     * @return array
     */
    public function update(array $payload, int $id): array
    {
        try {
            $payload['m_category_id'] = $payload['book_category']['id'];
            if (!empty($payload['cover'])) {
                $cover = $payload['cover']->store('upload/cover', 'public');
                $payload['cover'] = $cover;
                // $folderPath = "upload/coverItem";

                // $image_parts = explode(";base64,", $payload['cover']);
                // $image_type_aux = explode("image/", $image_parts[0]);
                // $image_type = $image_type_aux[1];
                // $image_base64 = base64_decode($image_parts[1]);
                // $file = $folderPath . '/' . uniqid() . '.' . $image_type;

                // Storage::disk('public')->put($file, $image_base64);
                // $payload['cover'] = $file;
            } else {
                unset($payload['cover']); // Jika cover kosong, hapus dari array agar tidak diupdate
            }

            $updateBook = $this->bookModel->edit($payload, $id);
            $dataBook = $this->getById($updateBook);

            return [
                'status' => true,
                'data' => $dataBook
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Menghapus data item dengan sistem "Soft Delete"
     * yaitu mengisi kolom deleted_at agar data tsb tidak
     * keselect waktu menggunakan Query
     *
     * @param  integer $id id dari tabel m_item
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $this->bookModel->drop($id);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
