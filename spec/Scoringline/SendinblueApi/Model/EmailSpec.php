<?php

namespace spec\Scoringline\SendinblueApi\Model;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Scoringline\SendinblueApi\Model\Email');
    }

    function it_should_throw_exception_when_file_does_not_exists()
    {
        $this
            ->shouldThrow('\Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException')
            ->duringAddAttachment('none.png')
        ;
    }

    function it_should_build_an_array()
    {
        $this->setTo('hello@scoringline.com', 'scoringline');

        $this
            ->toArray()
            ->shouldHaveKeyWithValue('to', [['hello@scoringline.com' => 'scoringline']])
        ;
    }
}
