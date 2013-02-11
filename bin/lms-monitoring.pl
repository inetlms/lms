#!/usr/bin/perl -Tw

use Time::Local;
use Net::Ping;
use Getopt::Long;
use Time::HiRes qw(tv_interval gettimeofday);
use vars qw($ipaddr $packetsize $typetest $port);
use POSIX qw(strftime);
use MIME::QuotedPrint;

my $_version = '1.0.2';

my %options = (
    "--ipaddr|ip=s" => \$ipaddr,
    "--port|p=s" => \$port,
    "--packetsize|ps=s" => \$packetsize,
    "--type|t=s" => \$typetest
);

Getopt::Long::config("no_ignore_case");
GetOptions(%options);

if (!$ipaddr)
{
    print STDERR <<EOF;

lms-monitoring.pl $_version
(c)2011 by Sylwester Kondracki
www.lmsdodatki.pl

Wymagany jest adres IP

EOF
exit 0;

}

if (!$packetsize) { $packetsize=32; }
if ($packetsize > 1024) {$packetsize = 1024;}
if (!$typetest) { $typetest="icmp"; }
if (!$port) { $port=80; }

my($timeStart) = [gettimeofday()];
my $czas;
my $p;

if ($typetest eq "icmp")	{ $p = Net::Ping->new("icmp",2,$packetsize); }
if ($typetest eq "http")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(80); }
if ($typetest eq "https")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(443); }
if ($typetest eq "ssh")		{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(22); }
if ($typetest eq "ftp")		{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(21); }
if ($typetest eq "telnet")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(23); }
if ($typetest eq "callbook")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(2000); }
if ($typetest eq "rpcbind")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(111); }
if ($typetest eq "samba")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(901); }
if ($typetest eq "pptp")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(1723); }
if ($typetest eq "mysql")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(3306); }
if ($typetest eq "smtp")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(25); }
if ($typetest eq "dns")		{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(53); }
if ($typetest eq "nfs")		{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(2049); }
if ($typetest eq "postgresql")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(5432); }
if ($typetest eq "winbox")	{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number(8291); }
if ($typetest eq "tcp")		{ $p = Net::Ping->new("tcp",2,$packetsize); $p->port_number($port); }

if ($p->ping($ipaddr)) {
    my($timeElapsed) = tv_interval($timeStart, [gettimeofday()]);
    $czas = ($timeElapsed * 1000);
} else { 
    $czas = -1; 
}

$p->close();
print STDOUT "$czas\n";
exit 0;
