<?php
date_default_timezone_set("Australia/Sydney");
// Fill in data for the distinguished name to be used in the cert
// You must change the values of these keys to match your name and
// company, or more precisely, the name and company of the person/site
// that you are generating the certificate for.
// For SSL certificates, the commonName is usually the domain name of
// that will be using the certificate, but for S/MIME certificates,
// the commonName will be the name of the individual who will use the
// certificate.
$dn = array(
    "countryName" => "AU",
    "stateOrProvinceName" => "NSW",
    "localityName" => "Sydney",
    "organizationName" => "Big Air",
    "organizationalUnitName" => "Network Operations",
    "commonName" => "Router Admin DB",
    "emailAddress" => "dave@bacb.com.au"
);
$pass = md5(date("r").serialize($dn));

// Generate a new private (and public) key pair
$privkey = openssl_pkey_new();

// Generate a certificate signing request
$csr = openssl_csr_new($dn, $privkey);

// You will usually want to create a self-signed certificate at this
// point until your CA fulfills your request.
// This creates a self-signed cert that is valid for 10 years
$sscert = openssl_csr_sign($csr, null, $privkey, 3650);

// Now you will want to preserve your private key, CSR and self-signed
// cert so that they can be installed into your web server, mail server
// or mail client (depending on the intended use of the certificate).
// This example shows how to get those things into variables, but you
// can also store them directly into files.
// Typically, you will send the CSR on to your CA who will then issue
// you with the "real" certificate.
openssl_csr_export($csr, $csrout) and var_dump($csrout);
openssl_x509_export($sscert, $certout) and var_dump($certout);
openssl_pkey_export($privkey, $pkeyout, $pass) and var_dump($pkeyout);

$key_file = "keys.php";
if (file_exists($key_file)) echo "$key_file already exists, cowardly refusing to overwrite\n";
else if ($fp=fopen($key_file,"w")) {
	echo "creating $key_file\n";
	fwrite($fp,"<?php \n\n/*\n$csrout\n*/\n\n\$cert=\"$certout\";\n\$key=\"$pkeyout\";\n\n");
	fwrite($fp,"\$PublicKey = openssl_get_publickey(\$cert);\n\$PrivateKey = openssl_get_privatekey(\$key,\"$pass\");\n\n?>");
	fclose($fp);
}
// Show any errors that occurred here
while (($e = openssl_error_string()) !== false) {
    echo $e . "\n";
}
?>
