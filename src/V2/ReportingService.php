<?php

namespace EdgeBox\SyncCore\V2;

use EdgeBox\SyncCore\Interfaces\IApplicationInterface;
use EdgeBox\SyncCore\Interfaces\IReportingService;
use EdgeBox\SyncCore\V2\Raw\Model\SyndicationError;
use EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorList;
use EdgeBox\SyncCore\V2\Raw\Model\SyndicationErrorType;
use EdgeBox\SyncCore\V2\Raw\Model\UsageSummary;

class ReportingService implements IReportingService
{
    /**
     * @var SyncCore
     */
    protected $core;

    /**
     * SyndicationService constructor.
     */
    public function __construct(SyncCore $core)
    {
        $this->core = $core;
    }

    protected function getOperationErrorMessage(SyndicationError $error)
    {
        /**
         * @var string $type
         */
        $type = $error->getType();
        $timestamp = $error->getTimestamp();
        $date = date('Y-m-d--H-i-s', (int) $timestamp).': ';

        if (SyndicationErrorType::TIMEOUT === $type) {
            return $date.'The request timed out.';
        }

        if (SyndicationErrorType::BAD_RESPONSE_CODE === $type) {
            $status = $error->getStatusCode();
            $body = $error->getResponseBody();

            return $date."The site responded with status code $status and response body $body";
        }

        $message = $error->getErrorMessage();
        if (SyndicationErrorType::INVALID_DEPENDENCY === $type) {
            return $date."Invalid dependency: $message";
        }

        return $date."Unexpected error: $message";
    }

    /**
     * {@inheritdoc}
     */
    public function getLog($level = null)
    {
        $request = $this->core->getClient()->syndicationControllerGetErrorsRequest();

        /**
         * @var SyndicationErrorList $response
         */
        $response = $this->core->sendToSyncCoreAndExpect($request, SyndicationErrorList::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        $messages = [];
        foreach ($response->getItems() as $item) {
            /**
             * @var string $status
             */
            $status = $item->getStatus();
            $message = "Syndication error (now in status $status).";

            foreach ($item->getOperationErrors() as $operation) {
                /**
                 * @var string $status
                 */
                $status = $operation->getStatus();
                $index = $operation->getOperationIndex();
                $type = $operation->getEntityTypeNamespaceMachineName();
                $bundle = $operation->getEntityTypeMachineName();
                $name = $operation->getEntityName() ?? '(no name)';
                $uuid = $operation->getEntityRemoteUuid() ?? '(no UUID)';
                $uniqueId = $operation->getEntityRemoteUniqueId() ?? '(no ID)';
                $errors = $operation->getErrors();
                $mostRecent = $errors[0];
                $error = $this->getOperationErrorMessage($mostRecent);
                $message .= "\nOperation $index (now in status $status) failed to syndicate $type.$bundle $name $uuid $uniqueId. Most recent error: $error";
            }

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        $request = $this->core->getClient()->usageStatsControllerSummaryRequest();

        /**
         * @var UsageSummary $response
         */
        $response = $this->core->sendToSyncCoreAndExpect($request, UsageSummary::class, IApplicationInterface::SYNC_CORE_PERMISSIONS_CONFIGURATION);

        return [
            // TODO: Drupal: ".x" means don't compare minor versions.
            'version' => '2.x',
            // TODO: Drupal: Different values provided here.
            'usage' => [
                'site' => [
                    'monthly' => [
                        'updateCount' => $response->getSiteMonthly(),
                    ],
                    'daily' => [
                        'updateCount' => $response->getSiteDaily(),
                    ],
                    'hourly' => [
                        'updateCount' => $response->getSiteHourly(),
                    ],
                ],
                'contract' => [
                    'monthly' => [
                        'updateCount' => $response->getContractMonthly(),
                    ],
                ],
            ],
        ];
    }
}
