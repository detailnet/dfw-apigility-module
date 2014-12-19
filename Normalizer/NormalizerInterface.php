<?php

namespace Application\Core\Normalizer;

interface NormalizerInterface
{
    public function denormalize(array $data, $class);

    public function normalize($object);
}
