<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Form\SignupFormType;
use App\Entity\PostfixUser;
use App\DovecotAuthenticator\DoveadmAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
class SignupController extends AbstractController
{
	private function createSignupForm() {
		return $this->createForm(SignupFormType::class,null,[
            'action' => $this->generateUrl('default'),
            'method' => 'POST'
        ]);
	}
	private function validateRegistration($validator,$data) {
		$email = $data["name"] . '@' . $_ENV['DOMAIN'];
		$emailConstraint = new EmailConstraint();

		$errors = $validator->validate(
			$email,
			$emailConstraint 
		);
		
		if (0 === count($errors) && !$this->getDoctrine()
				->getRepository(PostfixUser::class)
				->findOneBy(["username"=>$email])) {
			return true;
		} else {
			return false;
		}
	}
    /**
     * @Route("/", name="default")
     */
    public function index(DoveadmAuth $auth,Request $request,ValidatorInterface $validator)
    {
		$form = $this->createSignupForm();
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$data = $form->getData(); 
			$username = $data["name"];
			$email = $data["name"] . '@' . $_ENV['DOMAIN'];
			$maildir = $_ENV['DOMAIN'] . '/'.$username;
			$password = $data["password"];
			$_passwordEncoded = $auth->encodepw($_ENV['CRYPT'],$password);
			$passwordEncoded=substr(trim ($_passwordEncoded[1]),11);
			if (true===$this->validateRegistration($validator,$data)) {
				//TODO: capsulate this into own formtype and use Doctrine?
				$sql = "
					INSERT INTO `mailbox` 	(`username`, 	`password`, 		`name`, 	`maildir`, 	`quota`, 	`local_part`,	`domain`,		`created`, `modified`, `active`, `phone`, `email_other`, `token`, `token_validity`) 
					VALUES 					(:email, 		:passwordEncoded, 	:username, 	:maildir, 	'0', 		:username, 		'appstack.de',	current_timestamp(), current_timestamp(), '1', '', '', '', current_timestamp()); 
				";
				$conn = $this->getDoctrine()->getManager()->getConnection();
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':email', $email);
				$stmt->bindParam(':maildir', $maildir);
				$stmt->bindParam(':passwordEncoded', $passwordEncoded);
				$stmt->execute();
				return $this->redirect($_ENV['REDIRECT']);
			}
			
			return $this->render('signup/index.html.twig', [
				'form' => $form->createView(),
				'error' => 'Es ist ein Fehler bei der Registrierung aufgetreten, eventuell war der Name bereits vergeben'
			]);
		}
        return $this->render('signup/index.html.twig', [
			'form' => $form->createView(),
        ]);
    }
}
