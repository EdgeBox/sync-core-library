<?php

namespace EdgeBox\SyncCore\Exception;

/**
 * The request never reached the Sync Core: on every attempt the host's name
 * did not resolve, the connection or its TLS handshake failed, or the timeout
 * ran out before any of the request was sent. Repeating the request can't
 * start anything twice.
 *
 * It extends TimeoutException, which the library threw for these failures
 * before, so a caller that catches TimeoutException still catches it. A
 * TimeoutException that is not a ConnectionFailedException may have reached
 * the Sync Core, which may then act on it without its answer arriving.
 */
class ConnectionFailedException extends TimeoutException {}
