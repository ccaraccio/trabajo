#/bin/bash
nmap --script ssl-enum-ciphers -p 8443 $1 | grep -E "TLSv|SSLv"
