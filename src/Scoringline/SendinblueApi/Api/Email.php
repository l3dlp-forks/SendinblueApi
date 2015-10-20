<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <joni@sendinblue.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Api\AbstractApi;
use Scoringline\SendinblueApi\Model\EmailModel;

class Email extends AbstractApi
{
    const API_URL = '/email';

    /**
     * @param EmailModel $emailModel
     * @return array
     * @throws \Exception
     */
    public function sendEmail(EmailModel $emailModel)
    {
        $result = $this->post(self::API_URL, json_encode([
            'to' => $emailModel->getTo(),
            'from'=> $emailModel->getFrom(),
            'subject' => $emailModel->getSubject(),
            'text' => $emailModel->getText(),
            'html' => $emailModel->getHtml(),
            'headers'=> $emailModel->getHeaders(),
            'replyto' => $emailModel->getReplyTo(),
            'cc' => $emailModel->getCc(),
            'bcc' => $emailModel->getBcc(),
            'attachment' => $emailModel->getAttachment(),
            'inline_image' => $emailModel->getInlineImage()
        ]));

        if ($result['code'] === 'failure') {
            throw new \Exception('Email can not send by Sendinblue');
        }

        return $result;
    }
}
