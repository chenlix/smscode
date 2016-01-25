#!/bin/bash

logFileName='/var/www/logs/sendSmsNew.id'

while true;do
    read key < $logFileName
    php /vagrant/sms/index.php $key >> /var/www/logs/sendSmsNew.log
done
sleep(1)
#!/bin/bash
#logFileName='/var/www/logs/sendSmsNew.id'
#read key < $logFileName
#php /vagrant/sms/index.php $key >> /var/www/logs/sendSmsNew.log