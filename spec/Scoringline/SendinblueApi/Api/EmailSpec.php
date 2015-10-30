<?php

namespace spec\Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Http\AbstractHttpClient;
use Nekland\BaseApi\Transformer\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\File\File;
use Scoringline\SendinblueApi\Model\Email;
use Scoringline\SendinblueApi\Exception\EmailSendFailureException;

class EmailSpec extends ObjectBehavior
{

    function let(AbstractHttpClient $client, TransformerInterface $transformer)
    {
        $this->beConstructedWith($client, $transformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Scoringline\SendinblueApi\Api\Email');
        $this->shouldHaveType('Nekland\BaseApi\Api\AbstractApi');
    }

    function it_should_send_simple_email(
        AbstractHttpClient $client,
        TransformerInterface $transformer
    ) {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];

        $resultString = json_encode($result);
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $this->sendSimpleEmail(
            ['from@example.com', 'from name!'],
            ['to@example.com' => 'to name!'],
            'Invitation',
            '<h1>HTML</h1> content'
        )->shouldReturn($result);
    }

    function it_should_send_advance_email_with_attachment_and_inline_image_when_exists_files(
        AbstractHttpClient $client,
        TransformerInterface $transformer,
        Email $email
    ) {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];

        $file = new File('fixtures/test.txt');
        $imageFile = new File('fixtures/logo_one.png');

        $resultString = json_encode($result);
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $email->setTo(['to@example.com' => 'to name!']);
        $email->setFrom(['from@example.com', 'from name!']);
        $email->setSubject('Invitation');
        $email->setText('You are invited for giving test');
        $email->setHtml('This is the <h1>HTML</h1>');
        $email->setAttachments([$file, 'fixtures/logo.png']);
        $email->setInlineImages(['fixtures/logo.png', $imageFile]);

        $this->sendEmail($email)->shouldReturn($result);
    }

    function it_should_throw_exception_when_email_send_failed_due_to_missing_params(
        AbstractHttpClient $client,
        TransformerInterface $transformer,
        Email $email
    ) {
        $result = [
            'code' => 'failure',
            'message' => 'Unable to send email. Exception message was: No recipient forward path has been supplied.',
            'data' => []
        ];

        $resultString = json_encode($result);
        $emailFailureException = new EmailSendFailureException($resultString);
        $client->send(Argument::any())->willThrow($emailFailureException);
        $transformer->transform($resultString)->willThrow($emailFailureException);

        $email->setFrom(['from@example.com', 'from name!']);
        $email->setSubject('Hello');
        $email->setHtml('Hi this is test <h1>email</h1>');

        $this
            ->shouldThrow($emailFailureException)
            ->duringSendEmail($email)
        ;

        $this->shouldThrow($emailFailureException)
            ->duringSendSimpleEmail(['joni@sendinblue.com', 'Joni'], [], 'Invite', 'Content')
        ;

        $this->shouldThrow('PhpSpec\Exception\Example\ErrorException')
            ->duringSendSimpleEmail()
        ;

        $this->shouldThrow($emailFailureException)
            ->duringSendEmailWithData([
                'from' => ['from@example.com', 'from name!'],
                'subject' => 'Hello',
                'html' => 'Content'
            ])
        ;
    }

    function it_should_throw_exception_when_email_send_failed_due_to_blank_data(
        AbstractHttpClient $client,
        TransformerInterface $transformer
    ) {

        $result = [
            'code' => 'failure',
            'message' => 'Unable to send email. Exception message was: The parameters you passed are not well formated. Please refer to https:\/\/github.com\/DTSL\/mailin-smtp-api or contact us at contact at sendinblue.com.',
            'data' => []
        ];

        $resultString = json_encode($result);
        $emailFailureException = new EmailSendFailureException($resultString);
        $client->send(Argument::any())->willThrow($emailFailureException);
        $transformer->transform($resultString)->willThrow($emailFailureException);

        $this->shouldThrow($emailFailureException)
            ->duringSendEmailWithData([])
        ;
    }

    function it_should_throw_error_with_advance_email_when_attachment_file_not_exist(Email $email)
    {
        $file = new File('fixtures/test.txt');
        $email
            ->setAttachments([$file, 'logo1.png'])
            ->willThrow('Scoringline\SendinblueApi\Exception\FileNotExistsException')
        ;
        $this
            ->shouldThrow('Prophecy\Exception\Call\UnexpectedCallException')
            ->duringSendEmail($email)
        ;
    }

    function it_should_throw_error_with_advance_email_when_inline_image_invalid(Email $email)
    {
        $email->setTo(['to@example.com' => 'to name!']);
        $email
            ->setInlineImages(['test.txt'])
            ->willThrow('Scoringline\SendinblueApi\Exception\InvalidFileException')
        ;

        $this
            ->shouldThrow('Prophecy\Exception\Call\UnexpectedCallException')
            ->duringSendEmail($email)
        ;
    }
}
