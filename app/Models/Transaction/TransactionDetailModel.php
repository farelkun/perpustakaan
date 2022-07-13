<?php

namespace App\Models\Transaction;

use App\Http\Traits\RecordSignature;
use App\Models\Master\BookModel;
use App\Repository\ModelDetInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class TransactionDetailModel extends Model implements ModelDetInterface
{
    use HasRelationships, HasFactory;

    /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 't_transaction_details';

    /**
     * Menentukan primary key, jika nama kolom primary key adalah "id",
     * langkah deklarasi ini bisa dilewati
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;

    protected $attributes = [

    ];

    protected $fillable = [
        'transaction_id',
        'book_id'
    ];

    /**
     * Relasi ke ItemModel / tabel m_item sebagai item parentnya
     *
     * @return void
     */
    public function transaction()
    {
        return $this->hasOne(TransactionModel::class, 'id', 'transaction_id');
    }

    public function book()
    {
        return $this->hasOne(BookModel::class, 'id', 'book_id');
    }

    public function getAll(int $id): object
    {
        return $this->where('transaction_id', $id)->get();
    }

    public function getById(int $id): object
    {
        return $this->find($id);
    }

    public function store(array $payload) {
        return $this->insert($payload);
    }

    public function edit(array $payload, int $id) {
        return $this->find($id)->update($payload);
    }

    public function drop(int $id) {
        return $this->find($id)->delete();
    }

    public function deleteUnUsed(array $unUsedId, int $parentId) {
        return $this->whereIn('id', $unUsedId)->where('transaction_id', '=', $parentId)->delete();
    }
}
