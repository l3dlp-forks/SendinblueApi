<?php

namespace Scoringline\SendinblueApi\Model;

use Symfony\Component\HttpFoundation\File\File;

class Email
{
    /**
     * @var array
     */
    private $to = [];

    /**
     * @var array
     */
    private $from = [];

    /**
     * @var string
     */
    private $subject = '';

    /**
     * @var string
     */
    private $text = '';

    /**
     * @var string
     */
    private $html = '';

    /**
     * @var array
     */
    private $headers = ["Content-Type" => "text/html; charset=utf-8"];

    /**
     * @var array
     */
    private $replyTo = [];

    /**
     * @var array
     */
    private $cc = [];

    /**
     * @var array
     */
    private $bcc = [];

    /**
     * @var array
     */
    private $attachment = [];

    /**
     * @var array
     */
    private $inlineImage = [];

    /**
     * @param array $to
     * @return Email
     */
    public function setTo(array $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param array $from
     * @return Email
     */
    public function setFrom(array $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param array $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     *
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param array $text
     * @return Email
     */
    public function setText($text)
    {
        $this->subject = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param array $html
     * @return Email
     */
    public function setHtml($html)
    {
        $this->subject = $html;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param array $headers
     * @return Email
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $replyTo
     * @return Email
     */
    public function setReplyTo(array $replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * @return array
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param array $cc
     * @return Email
     */
    public function setCc(array $cc)
    {
        $this->cc = $cc;

        return $this;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param array $bcc
     * @return Email
     */
    public function setBcc(array $bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param array|object $attachmentData
     * @return Email
     */
    public function setAttachment($attachmentData)
    {
        // SOF attachment content encoding
        if (is_array($attachmentData) && count($attachmentData)) {
            for ($i = 0; $i < count($attachmentData); $i++) {
                if (is_object($attachmentData[$i]) && $attachmentData[$i] instanceof File) {
                    if ($attachmentData[$i]->isFile()) {
                        $attachmentContent = chunk_split(base64_encode(file_get_contents($attachmentData[$i])));
                        $this->attachment[$attachmentData[$i]->getFilename()] = $attachmentContent;
                    }
                } else {
                    if (file_exists($attachmentData[$i])) {
                        $attachmentInfo = pathinfo($attachmentData[$i]);
                        $attachmentContent = chunk_split(base64_encode(file_get_contents($attachmentData[$i])));
                        $this->attachment[$attachmentInfo['basename']] = $attachmentContent;
                    }
                }

            }
        } elseif (is_object($attachmentData) && null === $attachmentData && $attachmentData->isValid()) {
            $attachmentContent = chunk_split(base64_encode(file_get_contents($attachmentData)));
            $this->attachment[$attachmentData->getClientOriginalName()] = $attachmentContent;
        }

        // EOF image content encoding

        return $this;
    }

    /**
     * @return array
     *
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param array $imageArray
     * @return Email
     */
    public function setInlineImage(array $imageArray)
    {
        // SOF image content encoding
        if (count($imageArray)) {
            for ($i = 0; $i < count($imageArray); $i++) {
                if (file_exists($imageArray[$i])) {
                    $imageInfo = pathinfo($imageArray[$i]);
                    $extension = $imageInfo['extension'];
                    $extensions = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'tif'];
                    if ($extension && in_array($extension, $extensions)) {
                        $imageContent = chunk_split(base64_encode(file_get_contents($imageArray[$i])));
                        $this->inlineImage[$imageInfo['basename']] = $imageContent;
                    }
                }
            }
        }
        // EOF image content encoding

        return $this;
    }

    /**
     * @return array
     */
    public function getInlineImage()
    {
        return $this->inlineImage;
    }
}

