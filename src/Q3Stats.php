<?php

namespace Alxjzx100\Q3Stats;

use Exception;

class Q3Stats {
    protected string $server = '127.0.0.1';
    protected string $port = '27960';

    public function __construct( $server = '', $port = '' ) {
        if ( ! empty( $server ) ) {
            $this->server = $server;
        }

        if ( ! empty( $port ) ) {
            $this->port = $port;
        }
    }

    /**
     * @throws Exception
     */
    private function connect(): string {
        $socket     = socket_create( AF_INET, SOCK_DGRAM, SOL_UDP );
        $connection = @socket_connect( $socket, $this->server, $this->port );
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 3, 'usec' => 0));
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 3, 'usec' => 0));
        if ( ! $connection ) {
            throw new Exception( 'Connection error.' );
        }
        socket_write( $socket, "\xFF\xFF\xFF\xFFgetstatus" );
        $str = utf8_decode( @socket_read( $socket, 99999999 ) );
        if ( empty( $str ) ) {
            throw new Exception( 'No data received. ' . socket_strerror( socket_last_error() ) );
        }

        return $str;
    }

    public function getStats(): ?array {
        try {
            $str = $this->connect();
        } catch ( Exception $e ) {
            echo $e->getMessage();
            return null;
        }
        $str    = str_replace( "????statusResponse\n\\", '', $str );
        $str    = explode( '\\', $str );
        $result = [];
        $key    = '';
        foreach ( $str as $k => $text ) {
            if ( $k % 2 === 0 ) {
                $key = $text;
            } else {
                $result[ $key ] = $text;
            }
        }
        $this->getPlayersStats( $result );

        return $result;
    }

    private function getPlayersStats( &$result ) {
        $raw_players = explode( "\n", $result[array_key_last($result)] );
        if ( isset( $result[array_key_last($result)] ) && count( $raw_players ) > 1 ) {
            $result['time_to_end'] = $raw_players[0];
            $players               = [];
            foreach ( $raw_players as $k => $player ) {
                if ( $k > 0 && ! empty( $player ) ) {
                    $player    = explode( ' ', $player );
                    $players[] = [
                        'name'      => $player[2],
                        'score'     => $player[0],
                        'ping'      => $player[1],
                        'player_id' => $k
                    ];
                }
            }
            $result['players'] = $players;
        }
    }
}