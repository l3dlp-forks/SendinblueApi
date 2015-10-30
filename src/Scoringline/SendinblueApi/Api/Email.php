<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <m.veber@scoringline.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace Scoringline\SendinblueApi\Api;

use GuzzleHttp\Exception\RequestException;
use Nekland\BaseApi\Api\AbstractApi;
use Scoringline\SendinblueApi\Exception\EmailSendFailureException;
use Scoringline\SendinblueApi\Model\Email as EmailModel;

/**
 * Class Email
 * @package Scoringline\SendinblueApi\Api
 * @author Joni Rajput <joni@sendinblue.com>
 */
class Email extends AbstractApi
{
    const API_URL = '/email';

    /**
     * @param array $from
     * @param array $to
     * @param string $subject
     * @param string $content
     * @param array $attachment Ie: ['YourFileName.Extension' => 'Base64EncodedChunkData'] to send attachment/s generated on the fly
     * @param array $extraParams Ie: cc, bcc, replyTo, headers, text
     * @return array
     * @throws EmailSendFailureException
     */
    public function sendSimpleEmail($from, $to, $subject, $content, $attachment = [], $extraParams = [])
    {
        try {
            return $this->post(self::API_URL, json_encode(array_merge([
                'to' => $to,
                'from' => $from,
                'subject' => $subject,
                'html' => $content,
                'attachment' => $attachment
            ], $extraParams)));
        } catch(RequestException $e) {
           throw new EmailSendFailureException($e->getResponse()->getBody());
        }
    }

    /**
     * @param EmailModel $emailModel
     * @return array
     * @throws EmailSendFailureException
     */
    public function sendEmail(EmailModel $emailModel)
    {
        try {
            return $this->post(self::API_URL, json_encode($this->getParameters($emailModel)));
        } catch(RequestException $e) {
            throw new EmailSendFailureException($e->getResponse()->getBody());
        }
    }

    /**
     * @param array $param Ie. Associative array for to, from, subject, html etc
     * @return array
     * @throws EmailSendFailureException
     */
    public function sendEmailWithData($param = [])
    {
        try {
           return $this->post(self::API_URL, json_encode($param));
        } catch(RequestException $e) {
            throw new EmailSendFailureException($e->getResponse()->getBody());
        }
    }

    /**
     * @param EmailModel $emailModel
     * @return array
     */
    private function getParameters(EmailModel $emailModel)
    {
        $params = [];

        // Set to
        if (count($emailModel->getTo())) {
            $params['to'] = $emailModel->getTo();
        }
        // Set from
        if (count($emailModel->getFrom())) {
            $params['from'] = $emailModel->getFrom();
        }
        // Set subject
        if ($emailModel->getSubject()) {
            $params['subject'] = $emailModel->getSubject();
        }
        // Set text
        if ($emailModel->getText()) {
            $params['subject'] = $emailModel->getText();
        }
        // Set html
        if ($emailModel->getHtml()) {
            $params['html'] = $emailModel->getHtml();
        }

        if ($emailModel->getHeaders()) {
            $params['headers'] = $emailModel->getHeaders();
        }
        // SET replyTo
        if ($emailModel->getReplyTo()) {
            $params['replyto'] = $emailModel->getReplyTo();
        }
        // SET cc
        if ($emailModel->getCc()) {
            $params['cc'] = $emailModel->getCc();
        }
        // SET bcc
        if ($emailModel->getBcc()) {
            $params['bcc'] = $emailModel->getBcc();
        }
        // SET attachments
        if (count($emailModel->getAttachments())) {
            $params['attachment'] = $emailModel->getAttachments();
        }
        // SET inline_image
        if (count($emailModel->getInlineImages())) {
            $params['inline_image'] = $emailModel->getInlineImages();
        }

        return $params;
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
