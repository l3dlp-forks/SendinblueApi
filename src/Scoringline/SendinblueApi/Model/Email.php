<?php

/**
 * This file is a part of scoringline sendinblue api package
 *
 * (c) Scoringline <m.veber@scoringline.com>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
namespace Scoringline\SendinblueApi\Model;

use Scoringline\SendinblueApi\Exception\FileNotExistsException;
use Scoringline\SendinblueApi\Exception\InvalidFileException;
use Symfony\Component\HttpFoundation\File\File;
use Scoringline\SendinblueApi\Api\Email as EmailApi;

/**
 * Class Email
 *
 * @author Joni Rajput <joni@sendinblue.com>
 * @author Maxime Veber <nek.dev@gmail.com>
 */
class Email
{
    /**
     * @var array
     */
    private $to;

    /**
     * @var array
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $html;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $replyTo;

    /**
     * @var array
     */
    private $cc;

    /**
     * @var array
     */
    private $bcc;

    /**
     * @var array
     */
    private $attachments;

    /**
     * @var array
     */
    private $inlineImages;

    /**
     * @var EmailApi
     */
    private $email;

    /**
     * CONSTRUCTOR
     * @param string $encoding
     * @param EmailApi $email
     */
    public function __construct(EmailApi $email, $encoding = 'utf-8')
    {
        $this->to = [];
        $this->from = [];
        $this->subject = '';
        $this->html = '';
        $this->text = '';
        $this->headers = ["Content-Type" => "text/html; charset=" . $encoding];
        $this->replyTo = [];
        $this->cc = [];
        $this->bcc = [];
        $this->attachments = [];
        $this->inlineImages = [];
        $this->email = $email;
    }

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
     * @param string $subject
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
        $this->text = $text;

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
        $this->html = $html;

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
     * @param array $attachments
     * @return Email
     */
    public function setAttachments(array $attachments)
    {
        // SOF attachment content encoding
        if (is_array($attachments) && count($attachments)) {
            for ($i = 0; $i < count($attachments); $i++) {
                $this->addAttachment($attachments[$i]);
            }
        }

        // EOF image content encoding

        return $this;
    }

    /**
     * @param string|File $attachment
     * @throws FileNotExistsException
     * @return array
     */
    public function addAttachment($attachment)
    {
        if (is_object($attachment) && $attachment instanceof File) {
            if ($attachment->isFile()) {
                $attachmentContent = $this->email->encodeFileContent($attachment);
                $this->attachments[$attachment->getFilename()] = $attachmentContent;
            }
        } else {
            if (file_exists($attachment)) {
                $attachmentInfo = pathinfo($attachment);
                $attachmentContent = $this->email->encodeFileContent($attachment);
                $this->attachments[$attachmentInfo['basename']] = $attachmentContent;
            } else {
                throw new FileNotExistsException('Attached file does not exist');
            }
        }

        return $this->attachments;
    }

    /**
     * @return array
     *
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $inlineImages
     * @return Email
     */
    public function setInlineImages(array $inlineImages)
    {
        // SOF image content encoding
        if (count($inlineImages)) {
            for ($i = 0; $i < count($inlineImages); $i++) {
                $this->addInlineImage($inlineImages[$i]);
            }
        }
        // EOF image content encoding

        return $this;
    }

    /**
     * @param string|File $inlineImage
     * @throws FileNotExistsException
     * @throws InvalidFileException
     * @return array
     */
    public function addInlineImage($inlineImage)
    {
        $extensions = ['gif', 'jpg', 'jpeg', 'png', 'bmp', 'tif'];
        if (is_object($inlineImage) && $inlineImage instanceof File) {
            if (in_array($inlineImage->getExtension(), $extensions) ) {
                $attachmentContent = $this->email->encodeFileContent($inlineImage);
                $this->inlineImages[$inlineImage->getFilename()] = $attachmentContent;
            } else {
                throw new InvalidFileException('Invalid image');
            }
        } else {
            if (file_exists($inlineImage)) {
                $imageInfo = pathinfo($inlineImage);
                $extension = $imageInfo['extension'];
                if ($extension && in_array($extension, $extensions)) {
                    $imageContent = $this->email->encodeFileContent($inlineImage);
                    $this->inlineImages[$imageInfo['basename']] = $imageContent;
                } else {
                    throw new InvalidFileException('Invalid image');
                }
            } else {
                throw new FileNotExistsException('Image does not exist');
            }
        }

        return $this->inlineImages;
    }

    public function toArray()
    {

    }

    /**
     * @return array
     */
    public function getInlineImages()
    {
        return $this->inlineImages;
    }
}

