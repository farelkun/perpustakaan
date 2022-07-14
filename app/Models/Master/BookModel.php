<?php

namespace App\Models\Master;

use App\Http\Traits\RecordSignature;
use App\Repository\ModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class BookModel extends Model implements ModelInterface
{
    use SoftDeletes, HasRelationships, HasFactory;

    /**
    * Menentukan nama tabel yang terhubung dengan Class ini
    *
    * @var string
    */
    protected $table = 'm_books';

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
        'm_category_id',
        'title',
        'author',
        'publisher',
        'isbn',
        'cover',
        'stock',
        'description',
        'status',
    ];

    /**
     * Relasi ke ItemModelDet / tabel m_item_det
     *
     * @return void
     */
    public function coverUrl()
    {
        if (empty($this->cover)) {
            return asset('assets/img/no-image.png');
        }

        return asset('storage/' . $this->cover);
    }

    public function book_category()
    {
        return $this->belongsTo(BookCategoryModel::class, 'm_category_id', 'id');
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $dataBook = $this->query();

        $dataBook->with(['book_category']);

        if (!empty($filter['title'])) {
            $dataBook->where('title', 'LIKE', '%'.$filter['title'].'%');
        }

        $sort = $sort ?: 'id DESC';
        $dataBook->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;

        return $dataBook->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id): object
    {
        return $this->query()->with(['book_category'])->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }
}
