<?php

namespace App\Tests\Controller;

use App\Entity\Energy;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class EnergyControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/energy/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Energy::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Energy index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'energy[type]' => 'Testing',
            'energy[code]' => 'Testing',
            'energy[provider]' => 'Testing',
            'energy[contractEnd]' => 'Testing',
            'energy[power]' => 'Testing',
            'energy[basePrice]' => 'Testing',
            'energy[segment]' => 'Testing',
            'energy[peakHour]' => 'Testing',
            'energy[offPeakHour]' => 'Testing',
            'energy[horoSeason]' => 'Testing',
            'energy[peakHourWinter]' => 'Testing',
            'energy[peakHourSummer]' => 'Testing',
            'energy[offPeakHourWinter]' => 'Testing',
            'energy[offPeakHourSummer]' => 'Testing',
            'energy[total]' => 'Testing',
            'energy[customer]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Energy();
        $fixture->setType('My Title');
        $fixture->setCode('My Title');
        $fixture->setProvider('My Title');
        $fixture->setContractEnd('My Title');
        $fixture->setPower('My Title');
        $fixture->setBasePrice('My Title');
        $fixture->setSegment('My Title');
        $fixture->setPeakHour('My Title');
        $fixture->setOffPeakHour('My Title');
        $fixture->setHoroSeason('My Title');
        $fixture->setPeakHourWinter('My Title');
        $fixture->setPeakHourSummer('My Title');
        $fixture->setOffPeakHourWinter('My Title');
        $fixture->setOffPeakHourSummer('My Title');
        $fixture->setTotal('My Title');
        $fixture->setCustomer('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Energy');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Energy();
        $fixture->setType('Value');
        $fixture->setCode('Value');
        $fixture->setProvider('Value');
        $fixture->setContractEnd('Value');
        $fixture->setPower('Value');
        $fixture->setBasePrice('Value');
        $fixture->setSegment('Value');
        $fixture->setPeakHour('Value');
        $fixture->setOffPeakHour('Value');
        $fixture->setHoroSeason('Value');
        $fixture->setPeakHourWinter('Value');
        $fixture->setPeakHourSummer('Value');
        $fixture->setOffPeakHourWinter('Value');
        $fixture->setOffPeakHourSummer('Value');
        $fixture->setTotal('Value');
        $fixture->setCustomer('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'energy[type]' => 'Something New',
            'energy[code]' => 'Something New',
            'energy[provider]' => 'Something New',
            'energy[contractEnd]' => 'Something New',
            'energy[power]' => 'Something New',
            'energy[basePrice]' => 'Something New',
            'energy[segment]' => 'Something New',
            'energy[peakHour]' => 'Something New',
            'energy[offPeakHour]' => 'Something New',
            'energy[horoSeason]' => 'Something New',
            'energy[peakHourWinter]' => 'Something New',
            'energy[peakHourSummer]' => 'Something New',
            'energy[offPeakHourWinter]' => 'Something New',
            'energy[offPeakHourSummer]' => 'Something New',
            'energy[total]' => 'Something New',
            'energy[customer]' => 'Something New',
        ]);

        self::assertResponseRedirects('/energy/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getCode());
        self::assertSame('Something New', $fixture[0]->getProvider());
        self::assertSame('Something New', $fixture[0]->getContractEnd());
        self::assertSame('Something New', $fixture[0]->getPower());
        self::assertSame('Something New', $fixture[0]->getBasePrice());
        self::assertSame('Something New', $fixture[0]->getSegment());
        self::assertSame('Something New', $fixture[0]->getPeakHour());
        self::assertSame('Something New', $fixture[0]->getOffPeakHour());
        self::assertSame('Something New', $fixture[0]->getHoroSeason());
        self::assertSame('Something New', $fixture[0]->getPeakHourWinter());
        self::assertSame('Something New', $fixture[0]->getPeakHourSummer());
        self::assertSame('Something New', $fixture[0]->getOffPeakHourWinter());
        self::assertSame('Something New', $fixture[0]->getOffPeakHourSummer());
        self::assertSame('Something New', $fixture[0]->getTotal());
        self::assertSame('Something New', $fixture[0]->getCustomer());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Energy();
        $fixture->setType('Value');
        $fixture->setCode('Value');
        $fixture->setProvider('Value');
        $fixture->setContractEnd('Value');
        $fixture->setPower('Value');
        $fixture->setBasePrice('Value');
        $fixture->setSegment('Value');
        $fixture->setPeakHour('Value');
        $fixture->setOffPeakHour('Value');
        $fixture->setHoroSeason('Value');
        $fixture->setPeakHourWinter('Value');
        $fixture->setPeakHourSummer('Value');
        $fixture->setOffPeakHourWinter('Value');
        $fixture->setOffPeakHourSummer('Value');
        $fixture->setTotal('Value');
        $fixture->setCustomer('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/energy/');
        self::assertSame(0, $this->repository->count([]));
    }
}
