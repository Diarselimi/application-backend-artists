<?php
/**
 * Created by PhpStorm.
 * User: diar
 * Date: 03.01.19
 * Time: 21:31
 */

namespace App\Tests\EventListener;


use App\Entity\Artist;
use App\EventListener\TokenGeneratorListener;
use App\Tests\MockedClasses\MockedEntityManager;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class TokenGeneratorListenerTest extends TestCase
{
    public function testPostPersist()
    {
        $key = 'test_generated_codes';
        $cache = new FilesystemAdapter();

        $codes = $cache->getItem($key);
        $codes->set(";SD123");
        $cache->save($codes);


        $artist = new Artist("Diar");
        $artist->setToken("SD123");
        $em = new MockedEntityManager();



        $args = new LifecycleEventArgs($artist, $em);
        $tokenListener = new TokenGeneratorListener($key);
        $tokenListener->postPersist($args);

        $codes = $cache->getItem($key);

        $this->assertNotEquals("SD123", $args->getObject()->getToken());
        $this->assertContains($args->getObject()->getToken(), $codes->get());

        $token = $args->getObject()->getToken();

        $tokenListener->postPersist($args);
        $codes = $cache->getItem($key);
        $this->assertNotEquals($token, $args->getObject()->getToken());


    }
}