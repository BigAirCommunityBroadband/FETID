#!/usr/bin/perl
#
# routerdb.pl - Exports switch/router info. to the RANCID router.db files
#

use DBI;
use DBD::mysql;


#*** MySQL user and password - edit these ***#
$db="rancid";
$dbuser="rancid";
$dbpass="rancid";

my $dbh = DBI->connect("DBI:mysql:database=$db", $dbuser, $dbpass);

# edit this to suit your RANCID groups setup
my @section = (agriculture,athletics,au_net,auwan1,auwan2,border,core,engineering,firewalls,haley,library,oit,resnet,vetmed,wireless);


### Get device router.db entries from db and write to router.db files

foreach $name (@section) {

open(OUT, ">/home/rancid/var/$name/router.db");

### The statement handle
my $sth = $dbh->prepare( "SELECT ip, type FROM devices where section='$name'" );
$sth->execute(  );

while (@row = $sth->fetchrow_array(  )) {
        print OUT "$row[0]:$row[1]:up\n";

	}

close(OUT);

}
