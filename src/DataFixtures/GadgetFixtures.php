<?php

namespace App\DataFixtures;

use App\Entity\Gadget;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GadgetFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach (self::seedData() as $data) {
            $gadget = new Gadget();
            $gadget->setName($data['name']);
            $gadget->setBrand($data['brand']);
            $gadget->setCategory($data['category']);
            $gadget->setReleaseYear($data['releaseYear']);
            $gadget->setPrice($data['price']);
            $gadget->setRating($data['rating']);
            $gadget->setDescription($data['description']);
            $manager->persist($gadget);
        }

        $manager->flush();
    }

    public static function seedData(): array
    {
        return [
            [
                'name' => 'UltraPod Pro',
                'brand' => 'Sonix',
                'category' => 'Ljud',
                'releaseYear' => 2025,
                'price' => 1299,
                'rating' => 5,
                'description' => 'Trådlösa hörlurar med adaptiv brusreducering och 30 timmars batteritid.',
            ],
            [
                'name' => 'NovaBook Air 14',
                'brand' => 'Novatech',
                'category' => 'Dator',
                'releaseYear' => 2026,
                'price' => 14990,
                'rating' => 4,
                'description' => 'Superlätt bärbar dator med 18 timmars batteritid och OLED-skärm.',
            ],
            [
                'name' => 'PulseWatch 3',
                'brand' => 'Sonix',
                'category' => 'Wearable',
                'releaseYear' => 2025,
                'price' => 2495,
                'rating' => 4,
                'description' => 'Smart klocka med pulsmätning, GPS och två veckors batteritid.',
            ],
            [
                'name' => 'GridPad Mechanical',
                'brand' => 'KeyForge',
                'category' => 'Tillbehör',
                'releaseYear' => 2024,
                'price' => 899,
                'rating' => 5,
                'description' => 'Mekaniskt tangentbord med hot-swappbara switchar och RGB-belysning.',
            ],
            [
                'name' => 'HaloLens X',
                'brand' => 'Optiq',
                'category' => 'Kamera',
                'releaseYear' => 2026,
                'price' => 8990,
                'rating' => 4,
                'description' => 'Kompakt systemkamera med 4K-video och stabilisering i sensorn.',
            ],
            [
                'name' => 'NexPhone 15',
                'brand' => 'Nexora',
                'category' => 'Mobil',
                'releaseYear' => 2026,
                'price' => 11990,
                'rating' => 5,
                'description' => 'Flaggskeppsmobil med tre kameror och batteri som räcker hela dagen.',
            ],
            [
                'name' => 'HomeHub Mini',
                'brand' => 'Domus',
                'category' => 'Smart hem',
                'releaseYear' => 2025,
                'price' => 599,
                'rating' => 3,
                'description' => 'Röststyrd smart hub som samlar belysning, larm och termostat i en app.',
            ],
            [
                'name' => 'DriftPad Elite',
                'brand' => 'KeyForge',
                'category' => 'Gaming',
                'releaseYear' => 2024,
                'price' => 649,
                'rating' => 4,
                'description' => 'Trådlös handkontroll med programmerbara knappar och låg inputlatens.',
            ],
        ];
    }
}
