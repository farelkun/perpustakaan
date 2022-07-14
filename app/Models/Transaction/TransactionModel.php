<?php

namespace App\Models\Transaction;

use App\Repository\ModelInterface;
use App\Http\Traits\RecordSignature;
use App\Models\User\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class TransactionModel extends Model implements ModelInterface
{
    use HasRelationships, HasFactory;

     /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 't_transactions';

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
        'admin_id',
        'user_id',
        'start_date',
        'end_date',
        'return_date',
        'penalty',
        'status'
    ];

    public function is_status() {
        return ($this->status == 'borrowed') ? 'Borrowed' : 'Returned';
    }

    public function admin()
    {
        return $this->belongsTo(UserModel::class, 'admin_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function transaction_detail()
    {
        return $this->hasMany(TransactionDetailModel::class, 'transaction_id', 'id');
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $transaction = $this->query();
        $transaction->with([
            'admin',
            'user'
        ]);

        // dd($filter['user_id']);

        if (!empty($filter['user_id'])) {
            $transaction->where('user_id', 'LIKE', '%'.$filter['user_id'].'%');
        }

        $sort = $sort ?: 'id DESC';
        $transaction->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;

        return $transaction->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id): object
    {
        return $this->query()->with(['transaction_detail', 'user', 'admin'])->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function drop($id)
    {
        return $this->find($id)->delete();
    }
}
