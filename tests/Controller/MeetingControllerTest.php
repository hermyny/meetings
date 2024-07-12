<?php

namespace App\Test\Controller;

use App\Entity\Meeting;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MeetingControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/meeting/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Meeting::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Meeting index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'meeting[title]' => 'Testing',
            'meeting[agenda]' => 'Testing',
            'meeting[date]' => 'Testing',
            'meeting[time]' => 'Testing',
            'meeting[creator]' => 'Testing',
            'meeting[participant]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Meeting();
        $fixture->setTitle('My Title');
        $fixture->setAgenda('My Title');
        $fixture->setDate('My Title');
        $fixture->setTime('My Title');
        $fixture->setCreator('My Title');
        $fixture->setParticipant('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Meeting');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Meeting();
        $fixture->setTitle('Value');
        $fixture->setAgenda('Value');
        $fixture->setDate('Value');
        $fixture->setTime('Value');
        $fixture->setCreator('Value');
        $fixture->setParticipant('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'meeting[title]' => 'Something New',
            'meeting[agenda]' => 'Something New',
            'meeting[date]' => 'Something New',
            'meeting[time]' => 'Something New',
            'meeting[creator]' => 'Something New',
            'meeting[participant]' => 'Something New',
        ]);

        self::assertResponseRedirects('/meeting/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getAgenda());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getTime());
        self::assertSame('Something New', $fixture[0]->getCreator());
        self::assertSame('Something New', $fixture[0]->getParticipant());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Meeting();
        $fixture->setTitle('Value');
        $fixture->setAgenda('Value');
        $fixture->setDate('Value');
        $fixture->setTime('Value');
        $fixture->setCreator('Value');
        $fixture->setParticipant('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/meeting/');
        self::assertSame(0, $this->repository->count([]));
    }
}
