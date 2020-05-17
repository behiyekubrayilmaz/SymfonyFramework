<?php

namespace App\Controller;

use App\Entity\House;
use App\Entity\Admin\Messages;
use App\Form\Admin\MessagesType;
use App\Repository\Admin\CommentRepository;
use App\Repository\HouseRepository;
use App\Repository\SettingRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Bridge\Google\Smtp\GmailTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SettingRepository $settingRepository,HouseRepository $houseRepository)
    {
        $slider=$houseRepository->findBy(['status'=>'Allow'],['price'=>'ASC'],4);
        $house=$houseRepository->findBy(['status'=>'Allow'],['price'=>'DESC'],4);
        $newhouse=$houseRepository->findBy(['status'=>'Allow'],['title'=>'ASC'],4);
        $data=$settingRepository->findOneBy(['id'=>'1']);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data'=>$data,
            'house'=>$house,
            'newhouse'=>$newhouse,
            'slider'=>$slider,
        ]);
    }

    /**
     * @Route("/house/{id}", name="house_show", methods={"GET"})
     */
    public function show(House $house,$id,ImageRepository $imageRepository,CommentRepository $commentRepository): Response
    {
        
        $images=$imageRepository->findBy(['house'=>$id]);
        $comments=$commentRepository->findBy(['houseid'=>$id, 'status'=>'Read']);
        return $this->render('home/houseshow.html.twig', [
            'house' => $house,
            'images'=> $images,
            'comments'=>$comments,
        ]);
    }

    /**
     * @Route("/announcement", name="home_announcement", methods={"GET"})
     */
    public function announcement(HouseRepository $houseRepository): Response
    {
        $houses=$houseRepository->findBy(['status'=>'Allow'],['price'=>'DESC']);
        //dump($houses);
        //die();

        return $this->render('home/announcements.html.twig', [
            'houses' => $houses,
        ]);
    }

    /**
     * @Route("/about", name="home_about", methods={"GET"})
     */
    public function about(SettingRepository $settingRepository): Response
    {
        $data=$settingRepository->findOneBy(['id'=>'1']);
        return $this->render('home/aboutus.html.twig', [
            'data'=>$data,
        ]);
    }

    /**
     * @Route("/contact", name="home_contact", methods={"GET","POST"})
     */
    public function contact(SettingRepository $settingRepository,Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        $submittedToken =$request->request->get('token');
        $data=$settingRepository->findOneBy(['id'=>'1']);

        if ($form->isSubmitted()) {
            if($this->isCsrfTokenValid('form-message',$submittedToken)){

                $entityManager = $this->getDoctrine()->getManager();
                $message->setStatus('New');
                $message->setIp($_SERVER['REMOTE_ADDR']);
                $message->setCreatedAt( new \DateTime());
                $entityManager->persist($message);
                $entityManager->flush();
                $this->addFlash('success','Your message has been sent successfuly');

                $email=(new Email())
                    ->from($data->getSmtpemail())
                    ->to($form['email']->getData())
                    ->subject('Scaffold House Hires')
                    ->html("Dear ".$form['name']->getData()."<br>
                    <br>".$form['message']->getData()." <br>
                        <p> Thank you for your message</p>
                        <br>".$data->getCompany()." <br>
                        Adress: ".$data->getAddress()."<br>
                        Phone: ".$data->getPhone()."<br>"
                    );
                $transport= new GmailTransport($data->getSmtpemail(),$data->getSmtppassword());
                $mailer= new Mailer($transport);
                $mailer->send($email);
                
                return $this->redirectToRoute('home_contact');
            }
        }



        return $this->render('home/contact.html.twig', [
            'data'=>$data,
            'form' => $form->createView(),
            //'message' => $message,
        ]);
    }

}
