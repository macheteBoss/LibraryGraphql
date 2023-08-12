<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\Error;

class MutationService
{
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function createAuthor(array $authorDetails): Author
    {
        $author = new Author(
            $authorDetails['name']
        );

        $this->manager->persist($author);
        $this->manager->flush();

        return $author;
    }

    public function updateAuthor(int $authorId, array $newDetails): Author
    {
        $author = $this->manager->getRepository(Author::class)->find($authorId);

        if (is_null($author)) {
            throw new Error("error Author id");
        }

        $author->setName($newDetails['name']);

        $this->manager->flush();

        return $author;
    }

    public function deleteAuthor(int $authorId)
    {
        $author = $this->manager->getRepository(Author::class)->find($authorId);

        if (is_null($author)) {
            throw new Error("error Author id");
        }

        $this->manager->remove($author);
        $this->manager->flush();

        return true;
    }

    public function createBook(array $bookDetails): Book
    {
        $book = new Book(
            $bookDetails['title'],
            $bookDetails['year']
        );

        $authorRepository = $this->manager->getRepository(Author::class);

        foreach ($bookDetails['authors'] as $authorId) {
            $author = $authorRepository->find($authorId);

            if (!is_null($author)) {
                $book->addAuthor($author);
            }
        }

        $this->manager->persist($book);
        $this->manager->flush();

        return $book;
    }

    public function updateBook(int $bookId, array $newDetails): Book
    {
        $book = $this->manager->getRepository(Book::class)->find($bookId);

        if (is_null($book)) {
            throw new Error("error Book id");
        }

        foreach ($newDetails as $field => $value) {
            $method = 'set' . ucfirst($field);
            if (method_exists(Book::class, $method)) {
                $book->$method($value);
            }
        }

        if (array_key_exists('authors', $newDetails) !== false) {
            $authorRepository = $this->manager->getRepository(Author::class);

            $authorIds = [];
            foreach ($book->getAuthors() as $item) {
                $authorIds[] = $item->getId();
            }

            $buf = $authorIds;

            foreach ($newDetails['authors'] as $key => $authorId) {
                if (!in_array($authorId, $authorIds)) {
                    $book->addAuthor($authorRepository->find($authorId));
                } else {
                    unset($buf[$key]);
                }
            }

            if (!empty($buf)) {
                foreach ($buf as $bufItem) {
                    $author = $authorRepository->find($bufItem);
                    $book->removeAuthor($author);
                }
            }
        }

        $this->manager->flush();

        return $book;
    }

    public function deleteBook(int $bookId)
    {
        $book = $this->manager->getRepository(Book::class)->find($bookId);

        if (is_null($book)) {
            throw new Error("error Book id");
        }

        $this->manager->remove($book);
        $this->manager->flush();

        return true;
    }
}