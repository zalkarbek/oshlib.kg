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
        'reader_id' => 'integer',
        'book_id' => 'integer',
        'bail_received' => 'boolean',
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
            $now = Carbon::now();
            $returnDate = new Carbon($this->return_date);
            $days = $now->diff($returnDate)->format("%r%a");
            return $days > 0 ? $days : 0;
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
    public function reader()
    {
        return $this->belongsTo(Reader::class, 'reader_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
