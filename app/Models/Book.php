<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Book extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        // 'category_id' => 'required',
        'author_id' => 'required',
        'publisher_id' => 'required',
        'file_id' => 'required',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'release_date',
        'category_id',
        'publisher_id',
        // 'author_id',
        'file_id',
        'page_count',
        'writing_date',
        'has_variants',
        'available_for_rent',
        'copies_num'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'release_date' => 'datetime',
        'views' => 'integer',
        'category_id' => 'integer',
        'publisher_id' => 'integer',
        'author_id' => 'integer',
        'file_id' => 'integer',
        'page_count' => 'integer',
        'available_for_rent' => 'boolean',
        'copies_num' => 'integer'
    ];

    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'is_favorite',
        'cover',
        'read_status',
        'rating',
        'is_available_for_rent',
        'is_rented',
        'author',
    ];

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        if ($url) {
            $array = explode('.', $url);
            $extension = strtolower(end($array));
            try {
                return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
            } catch (\Exception $e) {
                return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
            }
        } else {
            $fileId = $this->fileDetails->id;
            $path = Storage::disk('diskD')->path("elkitep/books/$fileId/cover.1.jpg");
            if (file_exists($path)) {
                return asset("storage/cover/$fileId");
            }
        }

        return asset('images/image_default.png');
    }

    /**
     * @return bool
     **/
    public function getIsFavoriteAttribute()
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if(Auth::guard($guard)->check()) {
                return Favorite::where('user_id', Auth::guard($guard)->id())->where('book_id', $this->id)->exists();
            }
        }

        return null;
    }

    /**
     * @return string
     **/
    public function getCoverAttribute()
    {
        return $this->getFirstMediaUrl();
    }

    /**
     * @return string
     **/
    public function getReadStatusAttribute()
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if(Auth::guard($guard)->check()) {
                $readingStatus = UserReading::where('user_id', Auth::guard($guard)->id())->where('book_id', $this->id)->first();
                if ($readingStatus) return $readingStatus->status;
                else return "none";
            }
        }

        return null;
    }

    /**
     * @return string
     **/
    public function getRatingAttribute()
    {
        return $this->reviews()->average('rating') ?? 0.0;
    }

    /**
     * @return string
     **/
    public function getIsAvailableForRentAttribute()
    {
        if (!$this->available_for_rent || $this->has_variants == 'electronic') return false;

        return true;
    }

    public function getIsRentedAttribute()
    {
        $guards = array_keys(config('auth.guards'));
        foreach ($guards as $guard) {
            if(Auth::guard($guard)->check()) {
                $reader = User::find(Auth::guard($guard)->id())->reader;
                if ($reader) {
                    $rBook = RentedBooks::where('book_id', '=', $this->id)->where('reader_id', '=', $reader->id)->first();
                    if ($rBook && $rBook->daysLeft() !== 0) {
                        return true;
                    }

                }
            }
        }

        return false;
    }

    public function getAuthorAttribute()
    {
        $id = $this->firstAuthor()->id ?? 0;
        $image = $this->firstAuthor()->image ?? '';
        $description = $this->firstAuthor()->description ?? '';
        $booksCount = $this->firstAuthor()->books_count ?? 0;

        return [
            'id' => $id,
            'image' => $image,
            'description' => $description,
            'name' => $this->authorsName(),
            'books_count' => $booksCount,
        ];
    }

    public function firstAuthor()
    {
        return $this->authors->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function authorsName()
    {
        $authors = [];
        foreach ($this->authors as $author) {
            $authors[] = $author->name;
        }

        return implode(', ', $authors);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function fileDetails()
    {
        return $this->belongsTo(File::class,'file_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function attributes()
    {
        return $this->hasMany(BookAttribute::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function bookTags()
    {
        return $this->hasMany(BookTag::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'book_tags', 'book_id', 'tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function authors()
    {
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function bookSelection()
    {
        return $this->hasMany(BookSelection::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function userReadings()
    {
        return $this->hasMany(UserReading::class);
    }

    public function deleteWithFiles()
    {
        if ($this->fileDetails) {
            // unlink(storage_path('app/' . $this->file->path));
            deleteDirWithFiles(storage_path('app/books/' . $this->fileDetails->id));
        }

        $this->delete();
    }
}
