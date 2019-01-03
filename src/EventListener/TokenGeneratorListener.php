<?php
/**
 * Created by PhpStorm.
 * User: diar
 * Date: 03.01.19
 * Time: 17:47
 */

namespace App\EventListener;


use App\Entity\Album;
use App\Entity\Artist;
use App\Utils\TokenGenerator;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class TokenGeneratorListener
{
    private $cacheKey;

    public function __construct($cacheKey)
    {
        $this->cacheKey = $cacheKey;
    }
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();

        // only act on some "Product" entity
        if ($entity instanceof Artist || $entity instanceof Album) {

            $cache = new FilesystemAdapter();

            $codes = $cache->getItem($this->cacheKey);


            if (strpos($codes->get(), $entity->getToken()) !== false) {

                $entity->setToken(TokenGenerator::generate(6));
                $entityManager->persist($entity);

                $this->postPersist($args);
            } else {
                $codes->set($codes->get().";".$entity->getToken());
                $cache->save($codes);

            }

        }
    }
}