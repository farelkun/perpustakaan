<?php

namespace App\Models\Master;

use App\Repository\ModelInterface;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class BookCategoryModel extends Model implements ModelInterface
{
    use SoftDeletes, HasRelationships, HasFactory;

     /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 'm_book_categories';

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
        'name',
        'description'
    ];

    public function isDeleted() {
        return ($this->is_deleted == null) ? 'Active' : 'Inactive';
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $category = $this->query();

        if (!empty($filter['name'])) {
            $category->where('name', 'LIKE', '%'.$filter['name'].'%');
        }


        $sort = $sort ?: 'id DESC';
        $category->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;

        return $category->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id): object
    {
        return $this->find($id);
    }

    public function store(array $payload) {
        return $this->create($payload);
    }

    public function edit(array $payload, int $id) {
        return $this->find($id)->update($payload);
    }

    public function drop($id) {
        return $this->find($id)->delete();
    }
}
