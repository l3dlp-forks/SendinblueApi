<?php

namespace spec\Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Http\AbstractHttpClient;
use Nekland\BaseApi\Transformer\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
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

    function it_should_send_email_with_attachment_and_inline_image(
        AbstractHttpClient $client,
        TransformerInterface $transformer,
        Email $email
    ) {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];

        $resultString = (string) (json_encode($result));
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $email
            ->setTo(['to@example.com' => 'to name!'])
            ->setFrom(['to@example.com', 'to name!'])
            ->setSubject('Invitation')
            ->setText('You are invited for giving test')
            ->setHtml('This is the <h1>HTML</h1>')
            ->setAttachment(['myfilename.pdf', '/docs/mydocument.doc', 'images/image.gif'])
            ->setInlineImage(['logo.png', 'images/image.gif'])
        ;

        $this->sendEmail($email)->shouldReturn($result);
    }

    function it_should_send_email_blank_carbon_copy(
        AbstractHttpClient $client,
        TransformerInterface $transformer,
        Email $email)
    {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];
        $resultString = (string) (json_encode($result));
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);
        $email
            ->setTo(['to@example.com' => 'to name!'])
            ->setFrom(['from@example.com', 'from name!'])
            ->setSubject('Invitation')
            ->setText('You are invited for giving test')
            ->setHtml('This is the <h1>HTML</h1>')
            ->setBcc(['bcc@example.com' => 'Bcc name']);
        ;

        $this->sendEmail($email)->shouldReturn($result);
    }

    function it_should_throw_exception_when_email_send_failed(
        AbstractHttpClient $client,
        TransformerInterface $transformer,
        Email $email
    ) {
        $result = [
            'code' => 'failure'
        ];

        $resultString = (string) (json_encode($result));
        $client->send(Argument::any())->willReturn($resultString);
        $transformer->transform($resultString)->willReturn($result);

        $email->setFrom(['from@example.com', 'from name!']);

        $this->shouldThrow('\Exception')->duringSendEmail($email);
    }
}
