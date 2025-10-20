<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    // 🔹 Version DQL : compter le nombre total de livres
    public function getNbrBooksDQL(): int
    {
        $dql = "SELECT COUNT(b) FROM App\Entity\Book b";
        return (int) $this->getEntityManager()
            ->createQuery($dql)
            ->getSingleScalarResult();
    }

    // 🔹 Version QueryBuilder : compter le nombre total de livres
    public function getNbrBooksQB(): int
    {
        return (int) $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // 🔹 Version DQL : récupérer les livres d’un auteur
    public function getBooksByAuthorDQL(Author $author): array
    {
        $dql = "SELECT b FROM App\Entity\Book b WHERE b.author = :author";
        return $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('author', $author)
            ->getResult();
    }

    // 🔹 Version QueryBuilder : récupérer les livres d’un auteur
    public function getBooksByAuthorQB(Author $author): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.author = :author')
            ->setParameter('author', $author)
            ->getQuery()
            ->getResult();
    }
}
