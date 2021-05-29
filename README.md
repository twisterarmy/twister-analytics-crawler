# twister-analytics-crawler
Crawler toolkit for the twisterverse analytics (alpha)

### Requirements
`php-fpm`
`php-curl`
`php-mysql`
`mysql-server`

### Crontab
`* * * * * /usr/bin/php /path-to/twister-analytics-crawler/peer.php &> /dev/null`  
`0 0 * * * /usr/bin/php /path-to/twister-analytics-crawler/tor.php &> /dev/null`
