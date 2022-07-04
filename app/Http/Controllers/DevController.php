<?php

namespace App\Http\Controllers;

use App\Repositories\AuthorRepository;
use App\Repositories\BookAuthorRepository;
use App\Repositories\BookRepository;
use App\Repositories\BookTagRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FileRepository;
use App\Repositories\TagRepository;

class DevController extends AppBaseController
{
    /** @var CategoryRepository */
    private $categoryRepository;
    /** @var AuthorRepository */
    private $authorRepository;
    /** @var BookAuthorRepository */
    private $bookAuthorRepository;
    /** @var TagRepository */
    private $tagRepository;
    /** @var BookTagRepository */
    private $bookTagRepository;
    /** @var BookRepository */
    private $bookRepository;
    /** @var FileRepository */
    private $fileRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        AuthorRepository $authorRepository,
        BookAuthorRepository $bookAuthorRepository,
        TagRepository $tagRepository,
        BookTagRepository $bookTagRepository,
        BookRepository $bookRepository,
        FileRepository $fileRepository
    )
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
        $this->bookAuthorRepository = $bookAuthorRepository;
        $this->tagRepository = $tagRepository;
        $this->bookTagRepository = $bookTagRepository;
        $this->fileRepository = $fileRepository;
        $this->categoryRepository = $categoryRepository;
    }
}
