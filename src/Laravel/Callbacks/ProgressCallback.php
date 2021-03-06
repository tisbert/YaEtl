<?php

/*
 * This file is part of YaEtl.
 *     (c) Fabrice de Stefanis / https://github.com/fab2s/YaEtl
 * This source file is licensed under the MIT license which you will
 * find in the LICENSE file or at https://opensource.org/licenses/MIT
 */

namespace fab2s\YaEtl\Laravel\Callbacks;

use fab2s\NodalFlow\Callbacks\CallbackAbstract;
use fab2s\NodalFlow\Flows\FlowInterface;
use fab2s\NodalFlow\Nodes\NodeInterface;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProgressCallback
 */
class ProgressCallback extends CallbackAbstract
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var int
     */
    protected $numRecords;

    /**
     * @var int
     */
    protected $extractBatchSize;

    /**
     * @var int
     */
    protected $progressMod = 1;

    /**
     * @var int
     */
    protected $progressStep = 1;

    /**
     * @param int $progressMod
     *
     * @return $this
     */
    public function setProgressMod($progressMod)
    {
        $this->progressMod = max(1, (int) $progressMod);

        return $this;
    }

    /**
     * @param int $numRecords
     *
     * @return $this
     */
    public function setNumRecords($numRecords)
    {
        $this->numRecords = $numRecords;

        return $this;
    }

    /**
     * @param Command $command
     *
     * @return $this
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;

        $this->output = $this->command->getOutput();

        return $this;
    }

    /**
     * Triggered when a Flow starts
     *
     * @param FlowInterface $flow
     */
    public function start(FlowInterface $flow)
    {
        $this->command->info('[YaEtl] Start');
        $this->output->progressStart($this->numRecords);
    }

    /**
     * Triggered when a Flow progresses,
     * eg exec once or generates once
     *
     * @param FlowInterface $flow
     * @param NodeInterface $node
     */
    public function progress(FlowInterface $flow, NodeInterface $node)
    {
        $this->output->progressAdvance($this->progressMod);
    }

    /**
     * Triggered when a Flow succeeds
     *
     * @param FlowInterface $flow
     */
    public function success(FlowInterface $flow)
    {
        $this->output->progressFinish();

        $flowStatus = $flow->getFlowStatus();
        if ($flowStatus->isDirty()) {
            $this->command->warn('[YaEtl] Dirty Success');
        } else {
            $this->command->info('[YaEtl] Clean Success');
        }
    }

    /**
     * Triggered when a Flow fails
     *
     * @param FlowInterface $flow
     */
    public function fail(FlowInterface $flow)
    {
        $this->command->error('[YaEtl] Failed');
    }
}
