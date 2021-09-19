<?php

namespace App;

use App\Entity\TransactionHistoryLogs;
use App\Entity\User;
use App\Model\TransactionListOut;
use App\Model\UserOutModel;
use App\Model\UserShortOut;
use App\Utils\EmailSecurityConvertor;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\MappingOperation\Operation;

class AutomapperConfig implements AutoMapperConfiguratorInterface
{
    /**
     * @var EmailSecurityConvertor
     */
    private $emailConvertor;

    public function __construct(EmailSecurityConvertor $emailSecurityConvertor){
        $this->emailConvertor = $emailSecurityConvertor;
    }

    public function configure(AutoMapperConfigInterface $config): void
    {
        $this->configureUser($config);
        $this->configureTransaction($config);
    }

    /**
     * @param AutoMapperConfigInterface $config
     */
    public function configureUser(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(User::class, UserOutModel::class)
            ->forMember('email', function (User $user) {
                return $this->emailConvertor->convertMail($user->getEmail());
            });

        $config->registerMapping(User::class, UserShortOut::class);
    }

    public function configureTransaction(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(TransactionHistoryLogs::class, TransactionListOut::class)
            ->forMember('user', Operation::mapTo(UserShortOut::class));
    }
}