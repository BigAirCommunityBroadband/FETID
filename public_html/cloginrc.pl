#!/usr/bin/perl 
#
# cloginrc.pl - Exports switch/router login info. to the RANCID .cloginrc file
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

# Get device access entries from db and write to file
open(OUT, ">/home/rancid/.cloginrc");

foreach $name (@section) {


# The statement handle
my $sql = "SELECT *  FROM devices where section='$name'";
my $sth = $dbh->prepare($sql);
$sth->execute(  );

	my $ucname=uc($name);
	print OUT "\n#### $ucname section ####\n";

# Print entries to file
while (@row = $sth->fetchrow_array(  )) {

	if ( $row[3] eq ssh ) {
		print OUT "add user $row[0] $row[4]\n";
		print OUT "add method $row[0] $row[3]\n";
		print OUT "add password $row[0] $row[5] $row[6]\n";
		print OUT "#---\n";
	}
	elsif ( $row[3] eq telnet && $row[4] ne none ) { 
		print OUT "add user $row[0] $row[4]\n";
		print OUT "add password $row[0] $row[5] $row[6]\n";
		print OUT "#---\n";
  	}
	elsif ( $row[3] eq telnet && $row[4] eq none ) {
		print OUT "add password $row[0] $row[5] $row[6]\n";
	}
    }
}

close(OUT);

exit;
