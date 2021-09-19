<?php

namespace App\Controller\Api;

use App\Entity\TransactionHistoryLogs;
use App\Entity\User;
use App\Model\TransactionListOut;
use App\Model\UserShortOut;
use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class ApiTransferController extends AbstractController
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
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $logsRepository;

    /**
     * @param EntityManagerInterface $em
     * @param AutoMapperInterface $autoMapper
     */
    public function __construct(EntityManagerInterface $em, AutoMapperInterface $autoMapper)
    {
        $this->em = $em;
        $this->userRepository = $this->em->getRepository(User::class);
        $this->logsRepository = $this->em->getRepository(TransactionHistoryLogs::class);
        $this->autoMapper = $autoMapper;
    }

    /**
     * @Route("/transfer/{login}/{amount}", name="api_auth_transfer",  methods={"POST"})
     * @param $login
     * @param $amount
     * @throws \HttpException
     */
    public function transfer($login, $amount)
    {
        /** @var User $userSender */
        $userSender = $this->getUser();

        if ($userSender->getBalance() >= $amount) {

            /** @var User $userGetter */
            $userGetter = $this->userRepository->findOneBy(['username' => $login]);

            if ($userGetter) {

                $balanceGetter = $userGetter->getBalance() + $amount;

                $userGetter->setBalance($balanceGetter);
                $this->em->persist($userGetter);

                $logGetter = new TransactionHistoryLogs();
                $logGetter->setReceive($amount);
                $logGetter->setUser($userGetter);
                $this->em->persist($logGetter);

                $balanceSender = $userSender->getBalance() - $amount;
                $userSender->setBalance($balanceSender);
                $this->em->persist($userSender);

                $logSender = new TransactionHistoryLogs();
                $logSender->setSend($amount);
                $logSender->setUser($userSender);
                $this->em->persist($logSender);

                $this->em->flush();

            } else {
                throw new NotFoundHttpException("user.not_found");
            }

        } else {
            return new JsonResponse(
                [
                    'message' => 'your balance less then amount',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse(
            [
                'message' => 'transaction was successful',
            ],
            Response::HTTP_OK
        );
    }

    /**
     * @Route("/transactions/by-user", name="get_transactions_by_user", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getTransactionsListByUser()
    {
        $transactions = $this->logsRepository->getTransactionsByUser($this->getUser());

        $transactionsOut = $this->autoMapper->mapMultiple($transactions, TransactionListOut::class);

        return $this->json($transactionsOut);
    }

}