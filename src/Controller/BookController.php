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

#[Route('/book')]
final class BookController extends AbstractController
{
    // 🟡 Afficher la liste de tous les livres
    #[Route('/', name: 'book_list')]
    public function index(BookRepository $repo): Response
    {
        $books = $repo->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    // 🟢 Ajouter un nouveau livre
    #[Route('/new', name: 'book_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // 🟠 Modifier un livre existant
    #[Route('/edit/{id}', name: 'book_edit')]
    public function edit(Request $request, Book $book, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // 🔴 Supprimer un livre
    #[Route('/delete/{id}', name: 'book_delete')]
    public function delete(Book $book, EntityManagerInterface $em): Response
    {
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('book_list');
    }

    // 📊 Statistiques (getNbrBooks + getBooksByAuthor)
    #[Route('/stats', name: 'book_stats')]
    public function stats(BookRepository $repo): Response
    {
        $nbrBooks = $repo->getNbrBooksQB(); // ou getNbrBooksDQL()
        $booksByAuthor = $repo->getBooksByAuthorQB('Victor Hugo'); // exemple

        return $this->render('book/stats.html.twig', [
            'nbrBooks' => $nbrBooks,
            'booksByAuthor' => $booksByAuthor,
        ]);
    }
    // 📖 Afficher les détails d’un livre
#[Route('/{id}', name: 'book_show')]
public function show(Book $book): Response
{
    // Symfony injecte automatiquement le Book correspondant à l'id
    return $this->render('book/show.html.twig', [
        'book' => $book,
    ]);
}

}
