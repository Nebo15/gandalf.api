#!/usr/bin/env bash
modules_dir=$(dirname $0)/../modules
cd ${modules_dir}/../..
project_dir=$(pwd)
daemon_user='deploybot'
newrelic_key="1234567890123456789012345678901234567890"
newrelic_app_name="test-new-relic-app-name"

show_help()
{
cat << EOF
This script download files from remote server and download tar.gz to local machine or remote host.
Usage: $0 options
OPTIONS:
    -u  daemon user
    -k  newrelic licence key
    -n  newrelic app name
    -h  show this message
EOF
}

while getopts "u:h:n:k:" OPTION
do
     case ${OPTION} in
         u)
             daemon_user=$OPTARG
             ;;
         k)
              newrelic_key=$OPTARG
             ;;
         n)
              newrelic_app_name=$OPTARG
             ;;
         h)
             show_help
             ;;
         ?)
             show_help
             exit
             ;;
     esac
done

if [ ! -e ${modules_dir}/nginx ]; then
    sudo puppet module install --force jfryman-nginx --target-dir ${modules_dir}
fi;
if [ ! -e ${modules_dir}/newrelic ]; then
    sudo puppet module install --force fsalum-newrelic --target-dir ${modules_dir}
fi;

sudo FACTER_project_dir="${project_dir}" FACTER_newrelic_app_name="${newrelic_app_name}"  FACTER_newrelic_key="${newrelic_key}" FACTER_daemon_user="${daemon_user}" FACTER_error_reporting="0" puppet apply --modulepath ${modules_dir} ${modules_dir}/../general/manifests/general.pp