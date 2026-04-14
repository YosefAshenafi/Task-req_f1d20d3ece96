<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\service\OrderService;

class AutoCancelOrders extends Command
{
    protected function configure()
    {
        $this->setName('orders:auto-cancel')
            ->setDescription('Cancel orders pending payment for more than 30 minutes');
    }

    protected function execute(Input $input, Output $output)
    {
        $orders = \app\model\Order::where('state', 'pending_payment')
            ->where('auto_cancel_at', '<=', date('Y-m-d H:i:s'))
            ->select();

        $count = 0;
        foreach ($orders as $order) {
            $order->state = 'canceled';
            $order->save();

            $history = new \app\model\OrderStateHistory();
            $history->order_id = $order->id;
            $history->from_state = 'pending_payment';
            $history->to_state = 'canceled';
            $history->notes = 'Auto-cancelled: payment not received within 30 minutes';
            $history->save();

            $count++;
        }

        $output->writeln("Cancelled {$count} orders.");
    }
}