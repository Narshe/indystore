<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use App\Entity\Product;

class ProductNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = array()): array
    {
       
       $context = [
           'attributes' => [
               'id', 
               'name', 
               'description',
               'category' => ['name'],
               'productDetail' => [
                   'discount' => ['amount'], 
                   'developer', 
                   'publisher', 
                   'releaseDate'
                ]
            ]
       ];

       $data = $this->normalizer->normalize($object, $format, $context);

       $data['isDiscounted'] = $data['productDetail']['discount'] !== null;

       return $data;
      
    }


    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Product;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
