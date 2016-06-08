class nebo15_users {

  accounts::user {'andrew':
    shell => '/bin/bash',
    name => 'andrew',
    groups  => ['andrew','sudo'],
    password => '$6$mKwbL5vG$xxpSMDdW53QDcwjeomSXwDh2bZ.kkSiyQxBQx05H3SBY8rYunQfUJExY0vXYVNafXfIkP3BZdbRmBNiMzPQC0/',
    sshkeys => [
      'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCqqp5dmekEgbpGz/yOg5cET5SZAao+nltZzyAsRs45E26TBoOkrFIoN9qWeCtGHBZayaD+pQ/ygzMO+j3Ednzpe1WYXD6VuWmr5Qyk4rQdzWN1VbsyMrr1sw5C4VUE7S5L6PRk+cZHH6n0XIRoNIlL7nrXCMwSWDEIkIwqCagMOdAbix/g6wkiijir09JnqYbyE+nNTulvjW/mkIS3QYGj61p3XdfEZsySz8gqbMfJ0nf1o3LTwwShp5JZg+C8rMaGlPGuKGTHxT3s+v2Uywum5Q/HyCJ3IcVEl2jU62RjytAKRWA7eo9AY9J7cKb9bvCivkgmiD7J6JSTG7UlNvUP andrew@dryga.com'
    ],
  }
  accounts::user {'samorai':
    shell => '/bin/bash',
    name => 'samorai',
    groups  => ['samorai','sudo'],
    password => '$6$wapOKA5F$GLjQ0BdfKzrd.O5wRhJkZ8gpYs8wfJ6eySoHr7tMCBLxr8uSZfAlNrw3IGejsn.Ztw39ugqqucLvoC6EveyWe1',
    sshkeys => [
      'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQDXkd6Moa0IT2NKA5mrgWPA8ZvHw8RgNH/05v7x7FCsYlFBOpsXmmdYP5pViWboorUZmQ2dR7Q+PYqrBPcbt0kh0gVa5p9mcu56xZDWUavilH9NDeIPq2jDDB6IWeLaOQVTLL3cK4aRnl91EpjZKQ7LoCLq1rSFKW1T8+/XZsR/pI8FMuVXmpfkNyeD0O0cV+zIStnD7HC53eTvNQYmDPusAT5AZxSTltCXUzQRaHJQ9CONM39Wc+BrCEzJimLrPvoDWpThz1CeiY6nwc3oBK1rd0YSFeXqk55buweOp37FHCfGZB8eOEco2/nZsaCej2dDpRQQR0AhwjkF0um1igjTQGRxwvN9HXG//9+QdNS0HQhOjrvqQSUIM3+axn4BhP0dLH4GRWOh0d5EpzP2dujIfCH85jpJIQE+VNyMb0+uqKFlXn+Y/+B8czP5i+m1n0I0Mse1A0OTZkUZFTf/C8ff8RDXwF0uvza8/EUP2M6rtLuz3eLJuLKI+zn+LxKxCvo/ietn8CWch/Y9G8dVjR29RwsTtvAb5yi1x/Y7SlDseBPx9w2uN5Sud08qMCl1rJIk+v3XZAGwxHhM1TL4GLzc+fPH7RXniuLvJn9hS0QkRw3NTdIoVpQxxZ1uvj7AsU82AOnI+aiCspLB3kcN1DhGD8a1zBvgCB8ef+103TosGQ== oleg.samorai@gmai.com'
    ],
  }
  accounts::user {'bardack':
    shell => '/bin/bash',
    name => 'bardack',
    groups  => ['bardack','sudo'],
    password => '$6$Ie3WO9bM$dON5FqowUBFvZFdWfZfCmrzGo.qGVTW1ZCt0i/enhJUUja1pnOzmPs7Rc5hX4uIKC6qer41u81L6RLKpvALjX.',
    sshkeys => [
      'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCiOiaRLfU7iHjq2cvPPX+1eK1QDoJ7Yu0IrUrHuEWPxG5Mqui840A320y2Eb0Alr5rSeryZiYlIKxnoCWw2DgYBa5+wIbtEb6gIMszp39VF9qjNgXaAnWVKKWqN5JTLNOASG/Dxsw/DvtQ3M52+v4HYiZkZzGubUra5QqZNGndG7N8upvJYvgEwLaeTx/axwP91SdFBq1VqtGUgmrFbxFpX+4yx1jMVCoa/AIAEdHZIzu8ZlKduwhyT2vEOfg1xlsxq5vKZlthpGYQKtE4NI1dtJ/M+aYd96kG0UNmOOdvFq3dCD0P0MvhJf64Z9BMJXfhxHPTz9mknW/yKCfXfZ0t',
      'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDfDZkwERE9ZbWZZ1/XOmRkKdQ6mps3C4+LD6THKgfcpRKUui1BAsKwJmXSbApFgHOL5BmHHPUSOCCnl0knJYdjOxLwFgPfoZtDn3FES64/zQKGf1RqNOU1Ynw+hx+LFGKw5Af+cL88MUehbEFWI2M5prwC0rGgsNnwiQTcygP6TeCZJAegRMg626d5QU5MaOot/SsfRxrl45ii68oWyoz0CLRUKh2EOduz/hBf4jj83Kv2HDaZB1+qe7ttZqydaFZJvl5Ht0H7qgMwY1d0FC2jy3zEYXy5KrX5OdNrTgGIKxalp8gcOUANIU1Jhmr3ik4PYzr5NyIVfVwHZTz/dbPz paul@nebo15.com',
      'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDPYaa4wPCTI4WRvIF1LhpProLCQ7hRxcViX18bZl5732tLftkzuac7jnVy7iDM0A8exC9pGGK5i4ffguC2sOA6nfRuUlQ5E53FtRVsFS3Ziiv7LSzYKxPW0IyqvUZ+ZAKqlZB0ZXxMiSk2H4MN/VJFc9nMujb0TSQNyWgQpW6EPS2O7gqx91kOfwbtINsWSiCEyd5RnodAAdF+pz8Jy6uWtznkNl3LuPi8O2a/jsEtvKLjkDCrKKn69Rn0yuF201AQtdNQ1LhkDAuH+MwSalVp48evWN30UiVOc0wptEDyI6gPNlYiyiFvjnazUH2naJUtpvPF6ttjAk+sHPfgGslD paul.bardack@gmail.com'
    ],
  }
  accounts::user {'deploybot':
    shell => '/bin/bash',
    name => 'deploybot',
    groups  => ['deploybot'],
    password => '$6$wapOKA5F$GLjQ0BdfKzrd.O5wRhJkZ8gpYs8wfJ6eySoHr7tMCBLxr8uSZfAlNrw3IGejsn.Ztw39ugqqucLvoC6EveyWe1',
    sshkeys => [
      'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCxH6gf60lPsCo8lgdnUQc1WB3JHOlRhCiUY1iBlXMpUZ5PQod6eYPu45YtVqIALQr6gKhkZnNbT8cxit2cwvTr4tTy6OQ/4B/C4R/duOsCbGuHcGfw59fVCugDEHCr1a8qC+KtgUu5fI7SDlj1UtEc3w7BPyHjKAKYaYwbu+CFtUmE4KTRVos4fSWSRWU+mB0BFn6x+ff2CJErqpQXloHkb2wMTMXr1tldysyPVLwjzUU3QfIdKXAOPWhuyJQb9oCHb39xlkkd3wi+gLwszNIVthiq0OB/5hpzAtGDsU2K6cI0qJWxG5zjrV+Rymccp+7jnmESw2/TWo0NJPzJXF23 nebo15-deployments@DeployBot',
    ],
  }
}