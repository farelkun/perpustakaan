<?php

namespace App\Helpers\Transaction;
use App\Models\Transaction\TransactionModel;
use App\Repository\CrudInterface;

/**
 * Helper untuk manajemen transaction
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_transaction
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class TransactionHelper implements CrudInterface
{
    private $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    /**
     * Mengambil data transaction dari tabel m_transaction
     *
     * @author Wahyu Agung <wahyuagung26@gmail.com>
     *
     * @param  array $filter
     * $filter['nama'] = string
     * $filter['email'] = string
     * @param integer $TransactionPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->transactionModel->getAll($filter, $itemPerPage, $sort);
    }

    /**
     * Mengambil 1 data transaction dari tabel m_transaction
     *
     * @param  integer $id id dari tabel m_transaction
     *
     * @return void
     */
    public function getById(int $id): object
    {
        return $this->transactionModel->getById($id);
    }

    /**
     * method untuk menginput data baru ke tabel m_transaction
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     * $payload['nama'] = string
     * $payload['email] = string
     * $payload['is_verified] = string
     *
     * @return array
     */
    public function create(array $payload): array
    {
        try {
            $payload['admin_id'] = $payload['admin']['id'];
            $payload['user_id'] = $payload['user']['id'];
            $detailTransaction = $payload['detail'] ?? [];
            unset($payload['detail']);

            $newTransaction = $this->transactionModel->store($payload);

            // Simpan detail item
            if (!empty($detailTransaction)) {
                $detail = new TransactionDetailHelper($newTransaction);
                $detail->create($detailTransaction);
            }

            return [
                'status' => true,
                'data' => $newTransaction
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * method untuk mengubah transaction pada tabel m_transaction
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     * $payload['nama'] = string
     * $payload['email] = string
     * $payload['is_verified] = boolean
     *
     * @return void
     */
    public function update(array $payload, int $id): array
    {
        try {
            $detailTransaction = $payload['detail'] ?? [];
            unset($payload['detail']);

            $updateTransaction = $this->transactionModel->edit($payload, $id);
            $dataTransaction = $this->getById($updateTransaction);

            // Simpan detail Transaction
            if (!empty($detailTransaction)) {
                $detail = new TransactionDetailHelper($dataTransaction);
                $detail->update($detailTransaction);
            }

            return [
                'status' => true,
                'data' => $dataTransaction
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Menghapus data transaction dengan sistem "Soft Delete"
     * yaitu mengisi kolom deleted_at agar data tsb tidak
     * keselect waktu menggunakan Query
     *
     * @param  integer $id id dari tabel m_transaction
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $this->transactionModel->drop($id);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
