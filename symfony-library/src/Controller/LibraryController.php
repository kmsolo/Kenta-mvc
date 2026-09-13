<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/library')]
class LibraryController extends AbstractController
{
    /**
     * Landningssida för Bibliotek-applikationen.
     */
    #[Route('', name: 'library_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('library/index.html.twig', [
            'bookCount' => count($bookRepository->findAll()),
        ]);
    }

    /**
     * [READ MANY] Visar alla böcker i en tabell.
     */
    #[Route('/books', name: 'library_book_list', methods: ['GET'])]
    public function list(BookRepository $bookRepository): Response
    {
        return $this->render('library/list.html.twig', [
            'books' => $bookRepository->findBy([], ['title' => 'ASC']),
        ]);
    }

    /**
     * [CREATE] Formulär för att lägga till en ny bok.
     */
    #[Route('/books/new', name: 'library_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookRepository $bookRepository): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->save($book, true);
            $this->addFlash('success', sprintf('Boken "%s" har lagts till.', $book->getTitle()));

            return $this->redirectToRoute('library_book_show', ['id' => $book->getId()]);
        }

        return $this->render('library/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * [READ ONE] Visar detaljer om en specifik bok.
     */
    #[Route('/books/{id}', name: 'library_book_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Book $book): Response
    {
        return $this->render('library/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * [UPDATE] Formulär för att uppdatera en befintlig bok.
     */
    #[Route('/books/{id}/edit', name: 'library_book_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookType::class, $book, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookRepository->save($book, true);
            $this->addFlash('success', sprintf('Boken "%s" har uppdaterats.', $book->getTitle()));

            return $this->redirectToRoute('library_book_show', ['id' => $book->getId()]);
        }

        return $this->render('library/edit.html.twig', [
            'form' => $form,
            'book' => $book,
        ]);
    }

    /**
     * [DELETE] Raderar en bok. Kräver POST + giltig CSRF-token.
     */
    #[Route('/books/{id}/delete', name: 'library_book_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $title = $book->getTitle();
            $bookRepository->remove($book, true);
            $this->addFlash('success', sprintf('Boken "%s" har raderats.', $title));
        }

        return $this->redirectToRoute('library_book_list');
    }

    /**
     * [OPTIONELLT] Återställer databasen till ursprungligt innehåll (3 böcker).
     */
    #[Route('/reset', name: 'library_reset', methods: ['GET'])]
    public function reset(EntityManagerInterface $em, BookRepository $bookRepository): Response
    {
        foreach ($bookRepository->findAll() as $book) {
            $em->remove($book);
        }
        $em->flush();

        $seedBooks = [
            [
                'title' => 'Sagan om ringen',
                'author' => 'J.R.R. Tolkien',
                'isbn' => '9780261103252',
                'description' => 'Fantasyeposet om ringens brödraskap och kampen mot Sauron.',
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9780451524935',
                'description' => 'En dystopisk roman om övervakning, propaganda och totalitärt styre.',
            ],
            [
                'title' => 'Harry Potter och De Vises Sten',
                'author' => 'J.K. Rowling',
                'isbn' => '9780747532699',
                'description' => 'Den första boken om trollkarlspojken Harry Potter och Hogwarts.',
            ],
        ];

        foreach ($seedBooks as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setIsbn($data['isbn']);
            $book->setDescription($data['description']);
            $em->persist($book);
        }
        $em->flush();

        $this->addFlash('success', 'Databasen har återställts till ursprungligt innehåll.');

        return $this->redirectToRoute('library_book_list');
    }
}
