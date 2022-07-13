<?php

namespace App\Helpers\Transaction;

use App\Repository\DetailInterface;
use App\Models\Transaction\TransactionDetailModel;
use App\Models\Transaction\TransactionModel;

/**
 * Helper untuk manajemen item / menu / produk
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_item
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class TransactionDetailHelper implements DetailInterface
{
    private $model;
    private $parent;

    public function __construct(TransactionModel $transaction)
    {
        $this->parent = $transaction;
        $this->model = new TransactionDetailModel();
    }

    /**
     * Mempersiapkan data baru untuk diinput ke tabel m_item_det
     *
     * @param  array $detail
     * $detail['id']
     * $detail['m_item_id']
     * $detail['keterangan']
     * $detail['tipe']
     * $detail['harga']
     * @param  int $itemId id dari tabel m_item
     *
     * @return object
     */
    public function prepare(array $detail): array
    {
        $arrDetail = [];
        foreach ($detail as $key => $val) {
            $arrDetail[$key]['id'] = $val['id'] > 0 ? $val['id'] : null;
            $arrDetail[$key]['transaction_id'] = $this->parent->id;
            $arrDetail[$key]['book_id'] = $val['book_id'];
        }

        return $arrDetail;
    }

    /**
     * Fungsi untuk mengambil detail item berdasarkan item id
     *
     * @author Wahyu Agung <email@email.com>
     *
     * @param  int $itemId
     *
     * @return object
     */
    public function getAll(): object
    {
        return $this->model->getAll($this->parent->id);
    }

    /**
     * Fungsi untuk melakukan grouping detail item berdasarkan id tabel m_item_det
     *
     * @author Wahyu Agung <email@email.com>
     *
     * @return array
     */
    public function groupById(): array
    {
        $detailItems = $this->getAll();
        $arrDetail = [];
        foreach ($detailItems as $val) {
            $arrDetail[$val->id] = [
                'id' => $val['id'],
                'transaction_id' => $val['transaction_id'],
                'book_id' => $val['book_id']
            ];
        }

        return $arrDetail;
    }

    /**
     * Fungsi untuk mengecek jika ada perubahan data detail
     *
     * @author Wahyu Agung <email@email.com>
     *
     * @param  array $oldDetail
     * $oldDetail['keterangan']
     * $oldDetail['tipe']
     * $oldDetail['harga']
     * @param  array $newDetail
     * $newDetail['keterangan']
     * $newDetail['tipe']
     * $newDetail['harga']
     *
     * @return boolean
     */
    public function isChanged(array $oldDetail, array $newDetail): bool
    {
        // Siapkan array detail yang lama (di database)
        $old = collect([
            'book_id' => $oldDetail['book_id'] ?? ''
        ]);

        // Cari perbedaan detail lama dg detail baru (dari payload angular)
        $diff = $old->diffAssoc([
            'book_id' => $newDetail['book_id'] ?? '',
        ]);

        return (count($diff->all()) > 0) ? true : false;
    }

    /**
     * Proses mass input data detail ke tabel m_user_det
     *
     * @author Wahyu Agung <email@email.com>
     *
     * @param array $newDetail array multidimensi
     * $newDetail[x]['m_item_id']
     * $newDetail[x]['keterangan']
     * $newDetail[x]['tipe']
     * $newDetail[x]['harga']
     *
     * @return boolean
     */
    public function create(array $detail): bool
    {
        if (isset($detail[0]['transaction_id'])) {
            $newDetail = $this->prepare($detail);
            $this->model->store((array) $newDetail);
            return true;
        }

        return false;
    }

    /**
     * Fungsi untuk melakukan update detail item
     *
     * @author Wahyu Agung <email@email.com>
     *
     * @param array $newDetail
     * $newDetail[x]['id']
     * $newDetail[x]['m_item_id']
     * $newDetail[x]['keterangan']
     * $newDetail[x]['tipe']
     * $newDetail[x]['harga']
     *
     * @return boolean
     */
    public function update(array $newDetail): bool
    {
        try {
            $arrOldDet = $this->groupById();
            $tmpCreateDetail = [];
            foreach ($newDetail as $arrNewDet) {
                /**
                 * Jika tidak ada data atau id dari payload tidak terdaftar di db, maka tampung di array $tmpCreateDetail untuk selanjutnya diinput,
                 * Jika id sudah terdaftar di DB, maka Update jika ada perubahan
                 */
                if (!isset($arrOldDet[$arrNewDet['id']])) {
                    $tmpCreateDetail[] = $arrNewDet;
                } elseif ($this->isChanged($arrOldDet[$arrNewDet['id']], $arrNewDet)) {
                    $this->model->edit($arrNewDet, $arrNewDet['id']);
                }

                // Seleksi detail yang masih digunakan, sisakan detail lama yang akan dihapus
                if (isset($arrOldDet[$arrNewDet['id']])) {
                    unset($arrOldDet[$arrNewDet['id']]);
                }
            }

            // Hapus detail yang tidak digunakan
            if (!empty($arrOldDet)) {
                $this->deleteUnUsed($arrOldDet);
            }

            // Mass Insert jika ada detail baru
            if (!empty($tmpCreateDetail)) {
                $this->create($tmpCreateDetail);
            }

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
    * Hapus detail item yang tidak digunakan
    *
    * @author Wahyu Agung <agung@landa.co.id>
    *
    * @param  array $usedDetailId id detail yang tetap digunakan / disimpan
    * @return void
    */
    public function deleteUnUsed(array $arrOldDet): void
    {
        $unUsedId = [];
        foreach ($arrOldDet as $val) {
            $unUsedId[] = $val['id'];
        }

        if (!empty($unUsedId)) {
            $this->model->deleteUnUsed($unUsedId, $this->parent->id);
        }
    }
}
