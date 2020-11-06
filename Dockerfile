FROM mlzxgzy/caddy-php-mariadb
ENV MYSQL_USER=web6 MYSQL_PASS=web6pass MYSQL_DATABASE=web6
COPY ./html/* /srv/
RUN apk add php7-session \
  && rm -rf /var/cache/apk/* \
  && echo 'if [ ! $FLAG ]; then export FLAG="{Flag_System_Was_Broken_Please_Contect_To_Administrator}"; fi' >> /n2r.sh \
  && echo 'sed -i "s/{IF_YOU_COULD_SEE_ME_PLEASE_CONTECT_TO_ADMINISTRATOR_THANKS}/$FLAG/g" /srv/*' >> /n2r.sh \
  && cat /srv/init.sql >> /init.sql \
  && rm /srv/init.sql
