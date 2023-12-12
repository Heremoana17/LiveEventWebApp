<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Entity\ImageEvent;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\PictureService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/events', name: 'app_'), IsGranted('ROLE_ADMIN')]
class EventController extends AbstractController
{
    #[Route('/', name:'allEvents')]
    public function all(EventRepository $eventRepository, PaginatorInterface $paginatorInterface, Request $request):Response
    {
        $events = $paginatorInterface->paginate(
            $eventRepository->findBy([],['created_at'=>'DESC']),
            $request->query->get('page', 1),
            5
        );
        return $this->render('admin/event/allEvents.html.twig',[
            'events' => $events
        ]);
    }

    #[Route('/edit/{id?}', name:'editEvent')]
    public function editEvent(Request $request, EntityManagerInterface $em, PictureService $pictureService, Event $event=null, SluggerInterface $sluggerInterface, UserRepository $userRepository, SendMailService $mailer): Response
    {
        $new = false;
        if (!$event) {
            $event = new Event();
            $new = true;
        }
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()) {
            //on definit le dossier de destination
            $folder = 'evenements';
            //on recupère les images
            $images = $form->get('images')->getData();
            foreach($images as $image){
                //on appelle le service d'ajout
                $fichier = $pictureService->addImages($image, $folder, 1024, 576);
                $img = new ImageEvent();
                $img->setName($fichier);
                $event->addImageEvent($img);
            }
            $featuredImage = $form->get('featuredImage')->getData();
            if($featuredImage){
                $newFileName = $pictureService->addFeaturedImage($featuredImage, $folder);
                $event->setFeaturedImage($newFileName);
            };
            $event = $form->getData();
            //creation du slug
            $slug = $sluggerInterface->slug($event->getName());
            $event->setSlug($slug);
            //on persist
            $em->persist($event);
            $em->flush();
            //on envoie le mail newsletter aux abonnées
            $valider = $form->get('valider')->getData();
            if ($valider) {
                //on recupère les données du formulaire
                $subject = $form->get('subject')->getData();
                $content = $form->get('Content')->getData();
                //on récupère les abonnées
                $abonnées = $userRepository->findBy(['isSubscriber' => true]);
                //on envoie le mail
                foreach($abonnées as $abonnée){
                    $mailer->send(
                        'admin@pixelevent.site',
                        $abonnée->getEmail(),
                        $subject,
                        'newletter',
                        [
                            'content' => $content,
                            'abonnée' => $abonnée,
                            'event' => $event
                        ]
                    );
                }
            }
            //creation des message flash
            if ($new === true) {
                $this->addFlash('success', 'Nouvel evenement crée');
            } else {
                $this->addFlash('success', 'Evenement mis à jour');
            }
            return $this->redirectToRoute('app_allEvents');
        }
        return $this->render('admin/event/eventForm.html.twig',[
            'form' => $form->createView(),
            'new' => $new,
            'event' => $event
        ]);
    }

    #[Route('/delete/event/{id?}', name:'deleteEvent')]
    public function deleteEvent(Event $event, EntityManagerInterface $em, PictureService $pictureService):RedirectResponse
    {
        if ($event) {
            $images = $event->getImageEvents();
            $featuredImage = $event->getFeaturedImage();
            $pictureService->deleteAllsImages($images, $featuredImage, 'evenements');
            $em->remove($event);
            $em->flush();
            //edition des messages flash
            $this->addFlash('success','Evenement supprimer');
            return $this->redirectToRoute('app_allEvents');
        }
        $this->addFlash('error','Une erreur est survenue');
        return $this->redirectToRoute('app_allEvents');
    }

    #[Route('/delete/image/{id}', name:'delete_event_image', methods:['DELETE'])]
    public function deleteImage(ImageEvent $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'evenements')){
                // On supprime l'image de la base de données
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
    
}