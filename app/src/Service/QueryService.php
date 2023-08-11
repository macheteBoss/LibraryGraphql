<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;

class QueryService
{
    private $authorRepository;
    private $bookRepository;

    public function __construct(AuthorRepository $authorRepository, BookRepository $bookRepository) {
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    public function getAuthor(int $id): Author
    {
        return $this->authorRepository->find($id);
    }

    public function getAllAuthors(): array
    {
        return $this->authorRepository->findAll();
    }

    public function getBook(int $id): Book
    {
        return $this->bookRepository->find($id);
    }

    public function getAllBooks(): array
    {
        return $this->bookRepository->findAll();
    }
}