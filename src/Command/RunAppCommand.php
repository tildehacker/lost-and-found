<?php

namespace App\Command;

use Geokit\LatLng;
use Geokit\Math;
use NlpTools\Similarity\CosineSimilarity;
use NlpTools\Tokenizers\WhitespaceAndPunctuationTokenizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class RunAppCommand extends Command
{

    protected static $defaultName = 'app:run';
    private $container;


    public function __construct($name = null, ContainerInterface $container)
    {
        parent::__construct($name);
        $this->container = $container;
    }

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->container->get('doctrine')->getManager();
        $losts = $em->getRepository(\App\Entity\Lost::class)->findAll();
        $founds = $em->getRepository(\App\Entity\Found::class)->findAll();
        $mailer = $this->container->get('mailer');

        foreach ($losts as $lost) {
            foreach ($founds as $found) {
                $from = new LatLng(
                    $lost->getWhereLatitude(),
                    $lost->getWhereLongitude()
                );
                $to = new LatLng(
                    $found->getWhereLatitude(),
                    $found->getWhereLongitude()
                );
                $math = new Math();
                $distance = $math->distanceVincenty($from, $to)->meters();

                if ($this->validateSimilarTexts(
                        $lost->getDescription(),
                        $found->getDescription()
                    )
                    && $distance < 100
                    && $lost->getWhenLost() <= $found->getWhenFound()) {

                    $output->writeln($lost->getDescription()
                        . " "
                        . $found->getDescription());

                    $messageFound = (new \Swift_Message('Found email'))
                        ->setFrom('contact@lostandfound.com')
                        ->setTo($found->getEmail())
                        ->setBody(
                            $this->container->get('templating')->render(
                                'emails/found.html.twig',
                                ['description' => $found->getDescription(),
                                    'email' => $lost->getEmail()]
                            ),
                            'text/html'
                        );
                    $mailer->send($messageFound);
                    $messageLost = (new \Swift_Message('Lost email'))
                        ->setFrom('contact@lostandfound.com')
                        ->setTo($lost->getEmail())
                        ->setBody(
                            $this->container->get('templating')->render(
                                'emails/lost.html.twig',
                                ['description' => $lost->getDescription(),
                                    'email' => $found->getEmail()]
                            ),
                            'text/html'
                        );
                    $mailer->send($messageLost);
                }
            }
        }
    }

    public function validateSimilarTexts($text1, $text2)
    {
        $similarity = new CosineSimilarity();
        $tokenizer = new WhitespaceAndPunctuationTokenizer();

        $text1 = strip_tags($text1);
        $text2 = strip_tags($text2);

        $setA = $tokenizer->tokenize($text1);
        $setB = $tokenizer->tokenize($text2);

        $result = $similarity->similarity($setA, $setB);
        if ($result > 0.7) {
            return true;
        }

        return false;
    }

}