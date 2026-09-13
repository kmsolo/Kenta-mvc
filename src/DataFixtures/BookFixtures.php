<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $books = [
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

        foreach ($books as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setAuthor($data['author']);
            $book->setIsbn($data['isbn']);
            $book->setDescription($data['description']);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
