<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Workflow\Exception\TransitionException;
use Symfony\Component\Workflow\Registry;

class PostTransitionController
{
    /** @var Registry */
    private $workflows;

    public function __construct(Registry $workflows)
    {
        $this->workflows = $workflows;
    }

    public function __invoke(Post $data, $transition)
    {
        $workflow = $this->workflows->get($data);

        try {
            $workflow->apply($data, $transition);
        } catch (TransitionException $exception) {
            throw new HttpException(400, 'Can not apply transition.');
        }

        return $data;
    }
}
