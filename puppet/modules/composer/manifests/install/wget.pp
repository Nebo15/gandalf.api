#   Copyright 2015 Brainsware
#
#   Licensed under the Apache License, Version 2.0 (the "License");
#   you may not use this file except in compliance with the License.
#   You may obtain a copy of the License at
#
#       http://www.apache.org/licenses/LICENSE-2.0
#
#   Unless required by applicable law or agreed to in writing, software
#   distributed under the License is distributed on an "AS IS" BASIS,
#   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#   See the License for the specific language governing permissions and
#   limitations under the License.

# = Class: composer::install::wget
#
#  This private class helps install composer by downloading it directly from their website
#
# == Parameters:
#
#  none
#
class composer::install::wget {

  wget::fetch { 'composer-install':
    source      => $::composer::source,
    destination => "${::composer::target_dir}/${::composer::command_name}",
    execuser    => $::composer::user,
  }

  exec { 'composer-fix-permissions':
    command => "chmod a+x ${::composer::command_name}",
    path    => '/usr/bin:/bin:/usr/sbin:/sbin',
    cwd     => $::composer::target_dir,
    user    => $::composer::user,
    unless  => "test -x ${::composer::target_dir}/${::composer::command_name}",
    require => Wget::Fetch['composer-install'],
  }

  if $::composer::auto_update {
    exec { 'composer-update':
      command => "${::composer::command_name} self-update",
      path    => "${::composer::target_dir}:/usr/bin:/bin:/usr/sbin:/sbin",
      user    => $::composer::user,
      require => Exec['composer-fix-permissions'],
    }
  }
}
