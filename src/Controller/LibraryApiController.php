<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class LibraryApiController
{
    #[Route(
        '/api/library/books',
        name: 'api_library_books',
        methods: ['GET'],
        options: ['description' => 'Lista alla böcker i biblioteket som JSON']
    )]
    public function books(BookRepository $bookRepository): JsonResponse
    {
        $books = $bookRepository->findBy([], ['title' => 'ASC']);

        return new JsonResponse(array_map(
            fn (Book $book) => $this->serializeBook($book),
            $books
        ));
    }

    #[Route(
        '/api/library/book/{isbn}',
        name: 'api_library_book_show',
        methods: ['GET'],
        requirements: ['isbn' => '[0-9Xx\-]+'],
        options: ['description' => 'Visa en bok via dess ISBN. Exempel: <a href="/api/library/book/9780261103252">/api/library/book/9780261103252</a> (Sagan om ringen)']
    )]
    public function bookByIsbn(string $isbn, BookRepository $bookRepository): JsonResponse
    {
        $book = $bookRepository->findOneBy(['isbn' => $isbn]);

        if (!$book) {
            return new JsonResponse(
                ['error' => sprintf('Ingen bok med ISBN "%s" hittades.', $isbn)],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse($this->serializeBook($book));
    }

    private function serializeBook(Book $book): array
    {
        return [
            'id' => $book->getId(),
            'title' => $book->getTitle(),
            'author' => $book->getAuthor(),
            'isbn' => $book->getIsbn(),
            'description' => $book->getDescription(),
            'coverImageUrl' => $book->getCoverImageUrl(),
        ];
    }
}
