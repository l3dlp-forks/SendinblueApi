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

class Email extends AbstractApi
{
    const API_URL = '/email';

    /**
     * @param array  $to         I.e: ['to@example.com'=> 'to name'] associative array with commas to separate multiple recipients
     * @param array  $from       I.e: ['from@yahoo.com', 'from email']
     * @param string $subject    I.e: "Invitation"
     * @param string $text       I.e: "You are invited for giving test!"
     * @param string $html       I.e: "This is the <h1>HTML</h1>"
     * @param array $headers     I.e ["Content-Type" => "text/html; charset=utf-8"]
     * @param array  $replyTo    I.e: ['replyto@yahoo.com', 'reply to']
     * @param array  $cc         I.e: ['cc@example.com' => 'cc name']
     * @param array  $bcc        I.e: ['bcc@example.com' => 'bcc name']
     * @param array $attachment

     * @param array $inlineImage I.e ['YourFileName.Extension' => 'Base64EncodedChunkData'). associative array
     * @return array
     * @throws \RuntimeException
     */
    public function sendEmail(
        $to,
        $from,
        $subject,
        $text,
        $html,
        $headers = ["Content-Type" => "text/html; charset=utf-8"],
        $replyTo = [],
        $cc = [],
        $bcc = [],
        $attachment = [],
        $inlineImage = []
    ) {
        $result = $this->post(self::API_URL, json_encode([
            'to' => $to,
            'from'=> $from,
            'subject' => $subject,
            'text' => $text,
            'html' => $html,
            'replyto' => $replyTo,
            'cc' => $cc,
            'bcc' => $bcc,
            'attachment' => $attachment,
            'headers'=> $headers,
            'inline_image' => $inlineImage
        ]));

        if ($result['code'] === 'failure') {
            throw new \RuntimeException($result['message']);
        }

        return $result;
    }
}
