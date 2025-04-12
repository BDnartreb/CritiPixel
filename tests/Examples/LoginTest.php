<?php
namespace App\Tests\Functional;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LoginTest extends WebTestCase
{
    public function testLoginIsSuccessful(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $client->getContainer()->get("router");
        $crawler = $client->request('GET', $urlGenerator->generate('route.name'));
        $form = $crawler->filter("form[name=formname]")->form([
            "_username" => "email@home.fr",
            "_password" => "password"
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertRouteSame('route.name');
    }
}