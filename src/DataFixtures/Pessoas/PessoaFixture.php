<?php

namespace App\DataFixtures\Pessoas;

use App\Entity\Pessoa;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class PessoaFixture
 *
 * @package \App\DataFixtures\Pessoas
 */
class PessoaFixture extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @return \App\Entity\Pessoa
     */
    public function load(ObjectManager $manager)
    {
        $pessoa = new Pessoa();
        $pessoa->setNome(uniqid('nome'));
        $pessoa->setDataNascimento(new \DateTime('-20 years'));

        $manager->persist($pessoa);
        $manager->flush();

        return $pessoa;
    }
}
