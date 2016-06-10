#!/usr/bin/env bash
modules_dir=$(dirname $0)/../../modules
cd ${modules_dir}/../..
project_dir=$(pwd)

show_help()
{
cat << EOF
This script download files from remote server and download tar.gz to local machine or remote host.
Usage: $0 options
OPTIONS:
    -k  newrelic licence key
    -n  newrelic app name
    -h  show this message
EOF
}

while getopts "h:n:k:" OPTION
do
     case ${OPTION} in
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

sudo FACTER_newrelic_app_name="${newrelic_app_name}"  FACTER_newrelic_key="${newrelic_key}" puppet apply --modulepath ${modules_dir} ${modules_dir}/../manifests/general.pp