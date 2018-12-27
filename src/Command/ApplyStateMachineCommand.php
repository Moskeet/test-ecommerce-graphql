<?php

namespace App\Command;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Workflow\Registry;

class ApplyStateMachineCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:apply-state-machine';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param EntityManagerInterface $em
     * @param Registry $registry
     */
    public function __construct(
        EntityManagerInterface $em,
        Registry $registry
    ) {
        parent::__construct();
        $this->em = $em;
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this->setDescription('Apply state-machine (workflows) to Transactions.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $notFinal = $this->em->getRepository(Transaction::class)->getStateChangable();
        $transactionWorkflow = $this->registry->get(new Transaction());

        foreach ($notFinal as $transaction) {
            /** @var Transaction $transaction */
            if ($transactionWorkflow->can($transaction, 'accept')) {
                if ($transaction->getOwner()->getMoney() >= $transaction->getTotalPrice()) {
                    $transactionWorkflow->apply($transaction, 'accept');
//                    $user = $transaction->getOwner();
//                    $user->setMoney($user->getMoney() - $transaction->getTotalPrice());
//                    $this->em->flush();
                    $io->note(sprintf('%d: accepted', $transaction->getId()));

                    continue;
                }
            }

            if ($transactionWorkflow->can($transaction, 'decline')) {
                $transactionWorkflow->apply($transaction, 'decline');
                $this->em->flush();
                $io->note(sprintf('%d: declined', $transaction->getId()));

                continue;
            }

            if ($transactionWorkflow->can($transaction, 'process')) {
                $transactionWorkflow->apply($transaction, 'process');
                $this->em->flush();
                $io->note(sprintf('%d: processed', $transaction->getId()));
            }
        }

        $io->note('Finished.');
    }
}
