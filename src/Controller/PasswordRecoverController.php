<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use App\Entity\User;
use App\Form\RecoveryPasswordType;
use App\Form\UpdatePasswordType;

class PasswordRecoverController extends AbstractController
{
    /**
     * @Route("/password/recover", name="password_recover")
     * @param Request $request
     * @param MailerInterface $
     * @return Response
     */
    public function new(Request $request, MailerInterface $mailer): Response
    {   
        $form = $this->createForm(RecoveryPasswordType::class, null);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            if($user = $em->getRepository(User::class)->findOneBy(['email' => $form->getData()['email']])) {

                $token = sha1(random_bytes(15));
                $userMail = $user->getEmail();

                $email = (new TemplatedEmail())
                    ->from('indystore.noreply@noreply.com')
                    ->to($userMail)
                    ->subject('Récupération de mot de passe')
                    ->htmlTemplate('emails/password_recovery.html.twig')
                    ->context([
                        'userMail' => $userMail,
                        'token' => $token
                    ])
                ;
                    
                $mailer->send($email);  
                
                $user->setPasswordToken($token);
                $em->flush();

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('password_recover/new.html.twig', [
            'recoveryForm' => $form->createView(),
        ]);

    }

    /**
     * @Route("/password/recover/{email}/{token}", name="update_password")
     * @param Request $request
     * @param string $email
     * @param string $token
     * @param UserPasswordEncoreInterface $passwordEncoder
     * @return Response
     */
    public function update(Request $request, string $email, string $token, UserPasswordEncoderInterface $passwordEncoder): ?Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

        if(!$user || $user->getPasswordToken() !== $token) {
            
            throw new NotFoundHttpException('Cette page n\'existe pas');
        }

        $form = $this->createForm(UpdatePasswordType::class, null);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $user->setPassword($passwordEncoder->encodePassword($user, $form->getData()['password']));
            $user->setPasswordToken('');

            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_recover/update.html.twig', [
            'updateForm' => $form->createView(),
            'email' => $email,
            'token' => $token
        ]);
    }
}
