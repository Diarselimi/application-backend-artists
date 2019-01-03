<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Song;
use App\Utils\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Artist extends Fixture
{
    private $artists = [];

    public function load(ObjectManager $manager)
    {

        $data = (object) json_decode(file_get_contents("https://gist.githubusercontent.com/fightbulc/9b8df4e22c2da963cf8ccf96422437fe/raw/8d61579f7d0b32ba128ffbf1481e03f4f6722e17/artist-albums.json"));
        $this->convertDataToObjects($data);

        foreach ($this->artists as $artist) {
            $manager->persist($artist);
        }
        $manager->flush();
    }

    private function convertDataToObjects($data) {

        foreach ($data as $key => $artist) {
            $newArtist = new \App\Entity\Artist($artist->name);
            $newArtist->setToken(TokenGenerator::generate(6));

            foreach ($artist->albums as $album) {
                $album = (object) $album;

                $newAlbum = new Album();
                $newAlbum->setToken(TokenGenerator::generate(6));
                $newAlbum->setTitle($album->title);
                $newAlbum->setCover($album->cover);
                $newAlbum->setDescription($album->description);

                foreach ($album->songs as $song) {
                    $song = (object) $song;
                    try {
                        $newAlbum->addSong(new Song($song->title, $song->length));
                    } catch (\Exception $e) {
                        //just pass the song
                    }
                }

                $newArtist->addAlbum($newAlbum);
                unset($newAlbum);
            }

            array_push($this->artists, $newArtist);
            unset($newArtist);
        }
    }

}
