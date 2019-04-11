<?php

namespace SomethingDigital\AdminNotify\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Area;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Psr\Log\LoggerInterface;

class Notification
{
    const CONFIG_PATH_SD_ADMINNOTIFY_CC = 'sd_adminnotify/emails';
    
    const TEMPLATE_SUCCESS = 'admin_sd_adminnotify_success_template';
    const TEMPLATE_FAILURE = 'admin_sd_adminnotify_failure_template';

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var DeploymentConfig
     */
    private $config;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Notification constructor.
     * @param TransportBuilder $transportBuilder
     * @param DeploymentConfig $config
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        DeploymentConfig $config,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->config = $config;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\User\Model\User $user
     * @param bool $result
     * @param string $ip
     */
    public function sendActivityEmail($user, $result, $ip)
    {
        $transport = $this->transportBuilder->addTo($user->getEmail())
            ->addCc($this->getCcAddresses())
            ->setFrom($this->scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE))
            ->setTemplateIdentifier($result ? self::TEMPLATE_SUCCESS : self::TEMPLATE_FAILURE)
            ->setTemplateOptions(['area' => Area::AREA_ADMINHTML, 'store' => Store::DEFAULT_STORE_ID])
            ->setTemplateVars(['name' => $user->getName(), 'ip' => $ip])
            ->getTransport();

        try {
            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }

    private function getCcAddresses()
    {
        $result = [];
        $emails = explode(';', $this->config->get(self::CONFIG_PATH_SD_ADMINNOTIFY_CC));

        foreach ($emails as $email) {
            if ($email) {
                $result[] = trim($email);
            }
        }

        return $result;
    }
}
