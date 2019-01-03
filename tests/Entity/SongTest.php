<?php
/**
 * Created by PhpStorm.
 * User: diar
 * Date: 02.01.19
 * Time: 18:02
 */

namespace App\Tests\Entity;

use App\Entity\Song;
use PHPUnit\Framework\TestCase;

class SongTest extends TestCase
{

    public function testGetLength()
    {
        $this->expectException(new Song("Test", "123"));

    }

}
