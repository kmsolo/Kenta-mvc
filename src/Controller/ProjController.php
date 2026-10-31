<?php

namespace App\Controller;

use App\Entity\Gadget;
use App\Form\GadgetType;
use App\Repository\GadgetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/proj')]
class ProjController extends AbstractController
{
    /**
     * Landningssida för projektet Prylkatalogen.
     */
    #[Route('', name: 'proj_home', methods: ['GET'])]
    public function home(GadgetRepository $gadgetRepository): Response
    {
        $gadgets = $gadgetRepository->findAll();

        return $this->render('proj/home.html.twig', [
            'gadgetCount' => count($gadgets),
            'highlighted' => array_slice($gadgets, 0, 3),
        ]);
    }

    /**
     * Kort introduktion till projektet.
     */
    #[Route('/about', name: 'proj_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('proj/about.html.twig');
    }

    /**
     * [READ MANY] Visar alla prylar i katalogen.
     */
    #[Route('/gadgets', name: 'proj_gadget_index', methods: ['GET'])]
    public function index(GadgetRepository $gadgetRepository): Response
    {
        return $this->render('proj/index.html.twig', [
            'gadgets' => $gadgetRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    /**
     * [CREATE] Formulär för att lägga till en ny pryl.
     */
    #[Route('/gadgets/new', name: 'proj_gadget_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GadgetRepository $gadgetRepository): Response
    {
        $gadget = new Gadget();
        $form = $this->createForm(GadgetType::class, $gadget);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gadgetRepository->save($gadget, true);
            $this->addFlash('success', sprintf('Prylen "%s" har lagts till.', $gadget->getName()));

            return $this->redirectToRoute('proj_gadget_show', ['id' => $gadget->getId()]);
        }

        return $this->render('proj/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * [READ ONE] Visar detaljer om en specifik pryl.
     */
    #[Route('/gadgets/{id}', name: 'proj_gadget_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Gadget $gadget): Response
    {
        return $this->render('proj/show.html.twig', [
            'gadget' => $gadget,
        ]);
    }

    /**
     * [UPDATE] Formulär för att uppdatera en befintlig pryl.
     */
    #[Route('/gadgets/{id}/edit', name: 'proj_gadget_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, Gadget $gadget, GadgetRepository $gadgetRepository): Response
    {
        $form = $this->createForm(GadgetType::class, $gadget, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gadgetRepository->save($gadget, true);
            $this->addFlash('success', sprintf('Prylen "%s" har uppdaterats.', $gadget->getName()));

            return $this->redirectToRoute('proj_gadget_show', ['id' => $gadget->getId()]);
        }

        return $this->render('proj/edit.html.twig', [
            'form' => $form,
            'gadget' => $gadget,
        ]);
    }

    /**
     * [DELETE] Raderar en pryl. Kräver POST + giltig CSRF-token.
     */
    #[Route('/gadgets/{id}/delete', name: 'proj_gadget_delete', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function delete(Request $request, Gadget $gadget, GadgetRepository $gadgetRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gadget->getId(), $request->request->get('_token'))) {
            $name = $gadget->getName();
            $gadgetRepository->remove($gadget, true);
            $this->addFlash('success', sprintf('Prylen "%s" har raderats.', $name));
        }

        return $this->redirectToRoute('proj_gadget_index');
    }

    /**
     * [OPTIONELLT] Återställer databasen till ursprungligt exempeldata.
     */
    #[Route('/reset', name: 'proj_reset', methods: ['GET'])]
    public function reset(EntityManagerInterface $em, GadgetRepository $gadgetRepository): Response
    {
        foreach ($gadgetRepository->findAll() as $gadget) {
            $em->remove($gadget);
        }
        $em->flush();

        foreach (\App\DataFixtures\GadgetFixtures::seedData() as $data) {
            $gadget = new Gadget();
            $gadget->setName($data['name']);
            $gadget->setBrand($data['brand']);
            $gadget->setCategory($data['category']);
            $gadget->setReleaseYear($data['releaseYear']);
            $gadget->setPrice($data['price']);
            $gadget->setRating($data['rating']);
            $gadget->setDescription($data['description']);
            $em->persist($gadget);
        }
        $em->flush();

        $this->addFlash('success', 'Prylkatalogen har återställts till ursprungligt innehåll.');

        return $this->redirectToRoute('proj_gadget_index');
    }
}
