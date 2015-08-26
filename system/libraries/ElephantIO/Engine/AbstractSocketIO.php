<?php
/**
 * This file is part of the Elephant.io package
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 *
 * @copyright Wisembly
 * @license   http://www.opensource.org/licenses/MIT-License MIT License
 */

namespace ElephantIO\Engine;

use DomainException;

use Psr\Log\LoggerInterface;

use ElephantIO\EngineInterface,
    ElephantIO\Payload\Decoder,
    ElephantIO\Exception\UnsupportedActionException;

abstract class AbstractSocketIO implements EngineInterface
{
    const CONNECT      = 0;
    const DISCONNECT   = 1;
    const EVENT        = 2;
    const ACK          = 3;
    const ERROR        = 4;
    const BINARY_EVENT = 5;
    const BINARY_ACK   = 6;

    /** @var string[] Parse url result */
    protected $url;

    /** @var string[] Session information */
    protected $session;

    /** @var mixed[] Array of options for the engine */
    protected $options;

    /** @var resource Resource to the connected stream */
    protected $stream;

    public function __construct($url, array $options = [])
    {
        $this->url     = $this->parseUrl($url);
        $this->options = array_replace($this->getDefaultOptions(), $options);
    }

    /** {@inheritDoc} */
    public function connect()
    {
        throw new UnsupportedActionException($this, 'connect');
    }

    /** {@inheritDoc} */
    public function keepAlive()
    {
        throw new UnsupportedActionException($this, 'keepAlive');
    }

    /** {@inheritDoc} */
    public function close()
    {
        throw new UnsupportedActionException($this, 'close');
    }

    /**
     * Write the message to the socket
     *
     * @param integer $code    type of message (one of EngineInterface constants)
     * @param string  $message Message to send, correctly formatted
     */
    abstract public function write($code, $message = null);

    /** {@inheritDoc} */
    public function emit($event, array $args)
    {
        throw new UnsupportedActionException($this, 'emit');
    }

    /**
     * {@inheritDoc}
     *
     * Be careful, this method may hang your script, as we're not in a non
     * blocking mode.
     */
    public function read()
    {
        // Ignore first byte, I hope Socket.io does not send fragmented frames, so we don't have to deal with FIN bit.
		// There are also reserved bit's which are 0 in socket.io, and opcode, which is always "text frame" in Socket.io
		$header = fread($this->stream, 1);
		// There is also masking bit, as MSB, but it's 0 in current Socket.io
		$payload_len = ord(fread($this->stream, 1));

		switch ($payload_len) {
			case 126:
				$payload_len = unpack("n", fread($this->stream, 2));
				$payload_len = $payload_len[1];
				break;
			case 127:
				break;
		}
		// Reads the socket until full data is read
		//$payload = fread($this->fd, $payload_len); // Does not works if packet size > 16Kb
		$payload = '';
		$read = 0;
		while ($read < $payload_len && ( $buf = fread($this->stream, $payload_len - $read) )) {
			$read += strlen($buf);
			$payload .= $buf;
		}
		// decode the payload
		return (string) new Decoder($payload);
    }

    /** {@inheritDoc} */
    public function getName()
    {
        return 'SocketIO';
    }

    /**
     * Parse an url into parts we may expect
     *
     * @return string[] information on the given URL
     */
    protected function parseUrl($url)
    {
        $parsed = parse_url($url);

        if (false === $parsed) {
            throw new MalformedUrlException($url);
        }

        $server = array_replace(['scheme' => 'http',
                                 'host'   => 'localhost',
                                 'query'  => [],
                                 'path'   => 'socket.io'], $parsed);

        if (!isset($server['port'])) {
            $server['port'] = 'https' === $server['scheme'] ? 443 : 80;
        }

        if (!is_array($server['query'])) {
            parse_str($server['query'], $query);
            $server['query'] = $query;
        }

        $server['secured'] = 'https' === $server['scheme'];

        return $server;
    }

    /**
     * Get the defaults options
     *
     * @return array mixed[] Defaults options for this engine
     */
    protected function getDefaultOptions()
    {
        return ['context' => [],
                'debug'   => false,
                'wait'    => 100*1000,
                'timeout' => ini_get("default_socket_timeout")];
    }
}

