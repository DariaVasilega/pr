<?php

namespace App\Serializer;

use App\Entity\Post;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PostNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($post, $format = null, array $context = [])
    {
        $post = [
            'id' => $post->getId(),
            'text' => $post->getText(),
            'user' => [
                'id' => $post->getUser()->getId(),
                'name' => $post->getUser()->getName(),
                'surname' => $post->getUser()->getSurname(),
                'avatar' => str_replace('/', '|', $post->getUser()->getAvatar()->getFileName()),
            ],
        ];

        return $post;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Post;
    }
}
