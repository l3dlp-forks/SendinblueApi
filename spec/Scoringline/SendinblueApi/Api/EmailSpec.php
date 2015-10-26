<?php

namespace spec\Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Http\AbstractHttpClient;
use Nekland\BaseApi\Transformer\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\File\File;
use Scoringline\SendinblueApi\Model\Email;

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

    function it_should_send_basic_email(
        AbstractHttpClient $client,
        TransformerInterface $transformer,
        Email $email)
    {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];

        $resultString = json_encode($result);
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $email->setTo(['to@example.com' => 'to name!']);
        $email->setFrom(['from@example.com', 'from name!']);
        $email->setSubject('Invitation');
        $email->setText('You are invited for giving test');
        $email->setHtml('This is the <h1>HTML</h1>');

        $this->sendEmail($email)->shouldReturn($result);
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

        $this->sendEmail($email, 'advance')->shouldReturn($result);
    }

    function it_should_throw_exception_when_basic_email_send_failed_due_to_missing_params(
        AbstractHttpClient $client,
        TransformerInterface $transformer,
        Email $email
    ) {
        $result = [
            'code' => 'failure',
            'message' => 'Required parameters missing'
        ];

        $resultString = json_encode($result);
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $email->setFrom(['from@example.com', 'from name!']);

        $this
            ->shouldThrow('Scoringline\SendinblueApi\Exception\EmailSendFailureException')
            ->duringSendEmail($email)
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
            ->duringSendEmail($email, 'advance')
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
            ->duringSendEmail($email, 'advance')
        ;
    }
}
