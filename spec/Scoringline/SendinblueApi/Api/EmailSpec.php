<?php

namespace spec\Scoringline\SendinblueApi\Api;

use Nekland\BaseApi\Http\AbstractHttpClient;
use Nekland\BaseApi\Transformer\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmailSpec extends ObjectBehavior
{

    function let(AbstractHttpClient $client, TransformerInterface $transformer)
    {
        $this->beConstructedWith($client, $transformer);

        $client->send(Argument::any())->willReturn('res');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Scoringline\SendinblueApi\Api\Email');
        $this->shouldHaveType('Nekland\BaseApi\Api\AbstractApi');
    }

    function it_should_send_email(TransformerInterface $transformer)
    {
        $result = [
            'code' => 'success',
            'message' => 'Email sent successfully',
            'data' => []
        ];

        $transformer->transform('res')->willReturn($result);

        $this
            ->sendEmail(
                ['joni@sendinblue.com' => 'Joni Baba'],
                ['m.veber@scoringline.com', 'Maxime Veber'],
                'Invitaion for test',
                'You are invited for giving test!',
                '<b>You are invited for giving test!</b>'
            )
            ->shouldReturn($result)
        ;
    }

    function it_should_throw_exception_when_email_send_failed(TransformerInterface $transformer)
    {
        $result = [
            'code' => 'failure'
        ];

        $transformer->transform('res')->willReturn($result);

        $this
            ->shouldThrow('\RuntimeException')
            ->duringSendEmail(
                [],
                ['m.veber@scoringline.com', 'Maxime Veber'],
                'Invitaion for test',
                'You are invited for giving test!',
                '<b>You are invited for giving test!</b>'
            )
        ;
    }
}
