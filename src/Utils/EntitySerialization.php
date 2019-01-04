<?php
/**
 * Created by PhpStorm.
 * User: diar
 * Date: 04.01.19
 * Time: 17:31
 */

namespace App\Utils;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EntitySerialization
{

    public static function serialize($entity, $type = 'json', $circularReferenceLimit = 1)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceLimit($circularReferenceLimit);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $normalizers = array($normalizer);

        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($entity,'json');
    }
}