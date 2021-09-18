<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Model\UserOutModel;
use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validation;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Route("/user")
 */
class ApiUserController extends AbstractController
{
    /**
     * @var AutoMapperInterface $autoMapper
     */
    private $autoMapper;

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $userRepository;

    /**
     * @param EntityManagerInterface $em
     * @param AutoMapperInterface $autoMapper
     */
    public function __construct(EntityManagerInterface $em, AutoMapperInterface $autoMapper)
    {
        $this->em = $em;
        $this->userRepository = $this->em->getRepository(User::class);
        $this->autoMapper = $autoMapper;
    }

    /**
     * @Route("/register", name="api_auth_register",  methods={"POST"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function register(Request $request, UserManagerInterface $userManager)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        $validator = Validation::createValidator();

        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            'username' => [
                new Assert\Length(array('min' => 5, 'max' => 23)),
                new Assert\Regex(array('pattern' => '/[a-zA-Z]/',
                    'message' => 'username.should.contain.only.latin.letters'))
            ],
            'email' => new Assert\Email(array('message' => 'email.not.valid')),
            'password' => new Assert\Length(array('min' => 8))
        ));

        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        $user = new User();
        $user
            ->setUsername($data['username'])
            ->setPlainPassword($data['password'])
            ->setEmail($data['email'])
            ->setEnabled(true)
            ->setRoles(['ROLE_USER'])
            ->setSuperAdmin(false)
        ;

        try {
            $userManager->updateUser($user, true);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }

        # Code 307 preserves the request method, while redirectToRoute() is a shortcut method.
        return $this->redirectToRoute('api_auth_login', [
            'username' => $data['username'],
            'password' => $data['password']
        ], 307);
    }

    /**
     * @Route("/info", name="api_user_detail", methods={"GET"})
     * @return Response
     * @throws \AutoMapperPlus\Exception\UnregisteredMappingException
     */
    public function detail(): Response
    {
        $userOut = $this->autoMapper->map($this->getUser(), UserOutModel::class);

        return $this->json($userOut);
    }

    /**
     * @Route("/logout", name="logout", methods={"POST"})
     * @return JsonResponse
     */
    public function logout()
    {
        /** @var User[]|null $users */
        $users = $this->userRepository->findUsersWithConfirmationToken();

        if($users){
            foreach ($users as $user){
                $user->setConfirmationToken(null);
                $this->em->persist($user);
            }

            $this->em->flush();
        }

        return new JsonResponse(
            [
                'message' => 'user was logout',
            ],
            Response::HTTP_OK
        );
    }
}
