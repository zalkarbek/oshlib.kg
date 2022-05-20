<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentedBooks extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inventory_number',
        'department',
        'issue_date',
        'return_date',
        'bail_received',
        'note',
        'book_name',
        'author_name',
        'book_id',
        'reader_id',
    ];

    protected $casts = [
        'bail_received' => 'boolean',
        'book_id' => 'integer',
        'reader_id' => 'integer'
    ];

    protected $appends = [
        'days_left',
    ];

    public function getDaysLeftAttribute()
    {
        return $this->daysLeft();
    }

    /**
     * Check if plan has trial.
     *
     * @return bool
     */
    public function daysLeft()
    {
        if ($this->issue_date && $this->return_date) {
            $issueDate = new Carbon($this->issue_date);
            $returnDate = new Carbon($this->return_date);

            return $issueDate->diff($returnDate)->days;
        }

        return null;
    }

    /**
     * Check if subscription period has ended.
     *
     * @return bool
     */
    public function ended(): bool
    {
        return $this->ends_at ? Carbon::now()->gte($this->ends_at) : false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'reader_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
