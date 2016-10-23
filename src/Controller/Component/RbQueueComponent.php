<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPProtocolException;
use App\Lib\Constants;
use PhpAmqpLib\Message\AMQPMessage;
use Cake\Log\Log;

class RbQueueComponent extends Component
{
	/**
	 * 
	 * @param String $msg
	 * @param String $queueName
	 * @param String $routeKey
	 * @throws AMQPProtocolException
	 */
	public function sendMessage($msg, $queueName, $routeKey)
	{
		try {
			$amqpConn = new AMQPStreamConnection(Constants::RABBITMQ_SERVER_NAME, Constants::RABBITMQ_SERVER_PORT, Constants::RABBITMQ_USER, Constants::RABBITMQ_PASSWORD);
			$channel = $amqpConn->channel();
			
			$msg = new AMQPMessage($msg, ['delivery_mode' => 2]);
			$channel->exchange_declare(Constants::RABBITMQ_EXCHANGE_NAME, 'direct',FALSE, TRUE, FALSE);
			$channel->queue_declare($queueName, FALSE, TRUE, FALSE, FALSE);
			$channel->queue_bind($queueName, Constants::RABBITMQ_EXCHANGE_NAME, $routeKey);
			$channel->basic_publish($msg, Constants::RABBITMQ_EXCHANGE_NAME, $routeKey);
			$channel->close();
			$amqpConn->close();
		} catch (AMQPProtocolException $e) {
			throw new AMQPProtocolException(500, 'Error when connecting with RQ Server', '');
			//Log::error($e->getMessage());
		}
	}
}