#!/bin/bash

################################################################################
user="the_anarchist";
password="Aa,asdf;lkjasdf;lkj";
database="public_drop";
################################################################################

get_max_undocumented_time () {
	undocumentedTime=$(grep 'undocumentedTime' config.txt | cut -d '=' -f2)
	echo $undocumentedTime
}

################################################################################

get_max_unconfirmed_time () {
	unconfirmedTime=$(grep 'unconfirmedTime' config.txt | cut -d '=' -f2)
	echo $unconfirmedTime
}

################################################################################

virgins_friend () {
	statement="SELECT id FROM listings WHERE status = 'no_payment' AND client IS NOT NULL;";
	echo "listening for virgin offers";
	echo $(mysql -s --user="$user" --password="$password" --database="$database" --execute="$statement"); 
} 
################################################################################

escrow_autorelease_to_client_listener () {
	declare -a id;
	statement="SELECT id FROM listings;";
	id=($(mysql -s --user="$user" --password="$password" --database="$database" --execute="$statement"));

	declare -a timestamp_escrow_to_client;
	statement="SELECT timestamp_escrow_to_client FROM listings;";
	timestamp_escrow_to_client=($(mysql -s --user="$user" --password="$password" --database="$database" --execute="$statement"));

	declare -A row;
	size=${#id[@]};
	for i in $(seq 0 $(($size-1)) );
	do
		row[${id[$i]}]="${timestamp_escrow_to_client[$i]}";
	done

	for id in ${id[@]}
	do
		if [ $(( ${row[$id]} + $(get_max_undocumented_time) )) -lt $(date +%s) ] && [ ${row[$id]} != 'NULL' ];
		then
			echo "releasing escrowid $id to client, because time ($(get_max_undocumented_time)) without documentationlink is up"
			echo "vendor (name) is getting a comment by this system with a rating of -1 in his reputation";
		fi
	done
}

################################################################################

escrow_autorelease_to_vendor_listener () {
	declare -a id;
	statement="SELECT id FROM listings;";
	id=($(mysql -s --user="$user" --password="$password" --database="$database" --execute="$statement"));

	declare -a timestamp_escrow_to_vendor;
	statement="SELECT timestamp_escrow_to_vendor FROM listings;";
	timestamp_escrow_to_vendor=($(mysql -s --user="$user" --password="$password" --database="$database" --execute="$statement"));

	declare -A row;
	size=${#id[@]};
	for i in $(seq 0 $(($size-1)) );
	do
		row[${id[$i]}]="${timestamp_escrow_to_vendor[$i]}";
	done

	for id in ${id[@]}
	do
		if [ $(( ${row[$id]} + $(get_max_unconfirmed_time) )) -lt $(date +%s) ] && [ ${row[$id]} != 'NULL' ];
		then
			echo "releasing escrowid $id to vendor, because time ($(get_max_unconfirmed_time)) without confirment of reciving or claim is up"
		fi
	done
}

################################################################################

escrow_autorelease_to_vendor_starter () {
	statement="SELECT id FROM listings WHERE status = 'no_payment' AND client IS NOT NULL;";
	echo "listening for paid, undocumented trades to start ";
	echo $(mysql -s --user="$user" --password="$password" --database="$database" --execute="$statement");
}

################################################################################

print_points () {
	for (( c=0; c<=$points; c++ ));
	do
		printf ".";
	done
}

################################################################################
################################################################################

points=0;
direction_count=0
while(true)
do
	clear;
	if [ 0 -eq $(expr $points % 10) ]
	then
		let direction_count++;
	fi;
	if [ 0 -eq $(expr $direction_count % 2) ]
	then
		let points--;
	else
		let points++;
	fi;
	escrow_autorelease_to_client_listener
	escrow_autorelease_to_vendor_listener
	print_points;
	sleep 1;
done



