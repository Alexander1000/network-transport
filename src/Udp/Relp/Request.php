<?php declare(strict_types = 1);

namespace NetworkTransport\Udp\Relp;

class Request implements \NetworkTransport\Udp\RequestInterface
{
    public const FACILITY_KERNEL = 0;
    public const FACILITY_USER_LEVEL = 1;
    public const FACILITY_MAIL = 2;
    public const FACILITY_SYSTEM_DAEMON = 3;
    public const FACILITY_SECURITY_1 = 4;
    public const FACILITY_INTERNAL_SYSLOGD = 5;
    public const FACILITY_LINE_PRINTER = 6;
    public const FACILITY_NETWORK_NEWS = 7;
    public const FACILITY_UUCP = 8;
    public const FACILITY_CLOCK_DAEMON_1 = 9;
    public const FACILITY_SECURITY_2 = 10;
    public const FACILITY_FTP = 11;
    public const FACILITY_NTP = 12;
    public const FACILITY_AUDIT_1 = 13;
    public const FACILITY_ALERT_1 = 14;
    public const FACILITY_CLOCK_DAEMON_2 = 15;
    public const FACILITY_LOCAL0 = 16;
    public const FACILITY_LOCAL1 = 17;
    public const FACILITY_LOCAL2 = 18;
    public const FACILITY_LOCAL3 = 19;
    public const FACILITY_LOCAL4 = 20;
    public const FACILITY_LOCAL5 = 21;
    public const FACILITY_LOCAL6 = 22;
    public const FACILITY_LOCAL7 = 23;

    public const SEVERITY_EMERGENCY = 0;
    public const SEVERITY_ALERT = 1;
    public const SEVERITY_CRITICAL = 2;
    public const SEVERITY_ERROR = 3;
    public const SEVERITY_WARNING = 4;
    public const SEVERITY_NOTICE = 5;
    public const SEVERITY_INFORMATION = 6;
    public const SEVERITY_DEBUG = 7;

    public const RELP_MESSAGE_PATTERN = '<%d>%s %s %s: %s';

    protected $facility;
    protected $severity;
    protected $host;
    protected $process;
    protected $message;

    public function __construct(int $facility, int $severity, string $host, string $process, string $message)
    {
        $this->facility = $facility;
        $this->severity = $severity;
        $this->host = $host;
        $this->process = $process;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function serialize(): string
    {
        return sprintf(
            self::RELP_MESSAGE_PATTERN,
            $this->facility * 8 + $this->severity,
            date('M ') . sprintf('% 2d', intval(date('j'))) . ' ' . date('H:i:s'),
            $this->host,
            $this->process,
            $this->message
        );
    }
}
