<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <m.veber@scoringline.com>
 *
 * @author Joni Rajput <joni@sendinblue.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Api\AbstractApi;
use Scoringline\SendinblueApi\Exception\EmailSendFailureException;
use Scoringline\SendinblueApi\Model\Email as EmailModel;

class Email extends AbstractApi
{
    const API_URL = '/email';

    /**
     * @param EmailModel $emailModel
     * @param string $type
     * @return array
     * @throws \Exception
     */
    public function sendEmail(EmailModel $emailModel, $type = 'basic')
    {
        if($type === 'advance') {
            $result = $this->post(self::API_URL, json_encode($this->getAdvanceParams($emailModel)));
        } else {
            $result = $this->post(self::API_URL, json_encode($this->getBasicParams($emailModel)));
        }

        if ($result['code'] === 'failure') {
            throw new EmailSendFailureException($result['message']);
        }

        return $result;
    }

    /**
     * @param EmailModel $emailModel
     * @return array
     */
    private function getBasicParams(EmailModel $emailModel)
    {
        $basicParams = [];

        // Set to
        if (count($emailModel->getTo())) {
            $basicParams['to'] = $emailModel->getTo();
        }
        // Set from
        if (count($emailModel->getFrom())) {
            $basicParams['from'] = $emailModel->getFrom();
        }
        // Set subject
        if ($emailModel->getSubject()) {
            $basicParams['subject'] = $emailModel->getSubject();
        }
        // Set text
        if ($emailModel->getText()) {
            $basicParams['subject'] = $emailModel->getText();
        }
        // Set html
        if ($emailModel->getHtml()) {
            $basicParams['html'] = $emailModel->getHtml();
        }

        return $basicParams;
    }

    /**
     * @param EmailModel $emailModel
     * @return array
     */
    private function getAdvanceParams(EmailModel $emailModel)
    {
        $advanceParams = [];
        // SET headers
        if ($emailModel->getHeaders()) {
            $advanceParams['headers'] = $emailModel->getHeaders();
        }
        // SET replyTo
        if ($emailModel->getReplyTo()) {
            $advanceParams['replyto'] = $emailModel->getReplyTo();
        }
        // SET cc
        if ($emailModel->getCc()) {
            $advanceParams['cc'] = $emailModel->getCc();
        }
        // SET bcc
        if ($emailModel->getBcc()) {
            $advanceParams['bcc'] = $emailModel->getBcc();
        }

        // SET attachments
        if (count($emailModel->getAttachments())) {
            $advanceParams['attachment'] = $emailModel->getAttachments();
        }
        // SET inline_image
        if (count($emailModel->getInlineImages())) {
            $advanceParams['inline_image'] = $emailModel->getBcc();
        }

        return array_merge($this->getBasicParams($emailModel), $advanceParams);
    }

    /**
     * ENCODE content to base_64 nad split into chunks
     * @param string $file
     * @return string
     */
    public function encodeFileContent($file)
    {
        return chunk_split(base64_encode(file_get_contents($file)));
    }
}
