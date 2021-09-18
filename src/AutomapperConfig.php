<?php

namespace App;

use App\Entity\User;
use App\Model\UserInModel;
use App\Model\UserOutModel;
use App\Utils\EmailSecurityConvertor;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use FOS\UserBundle\Model;

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
    }
}