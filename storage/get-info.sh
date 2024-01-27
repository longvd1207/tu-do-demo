#!/bin/bash

computer_name=$(hostname)
cpu_id=$(sudo dmidecode -t processor | grep ID | awk '{gsub(/^.*: /,""); gsub(/ /,""); print}')
motherboard=$(sudo dmidecode -t 2 | grep "Serial Number")
ip_addresses=$(hostname -I)
mac_addresses=$(ifconfig -a | grep -o -E '([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})' | awk '{printf "%s ", $0}')

output="Computer Name: $computer_name\nCPU ID: $cpu_id\nMotherboard ID: $motherboard_id\nIP Addresses: $ip_addresses\nMAC Addresses: $mac_addresses"

echo -e "$output" > output.txt