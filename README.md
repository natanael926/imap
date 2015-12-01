# Imap
##Trabaja con las funciones más comunes de imap.

Opera con los protocolo imap, nttp, pop3 y los métodos de accesos a buzón local, en el solo hago uso de funciones comunes como imap_open, imap_listmailbox, imap_msgno, imap_fetchheader.

#Objetivos
Disponer de un paquete que me facilite, la creación  de un cliente de correo, obtener mis últimos correos y su estatus, enviar correos, etc. 


# Requisitos
php >= 5.4.0 y ext-imap

# Instalación

> git clone https://github.com/natanael926/get-mail-imap.git <br />
> curl -s https://getcomposer.org/installer | php <br />
> php composer.phar install 

## Configuración laravel
En el archivo .env craer los parametros 
>HOST_IMAP=imap.gmail.com
>USER_EMAIL_IMAP=youremail@gmail.com
>PASS_EMAIL_IMAP=yourpasswork
>POST_IMAP=993
>POST_SMTP=465

## Algunos y metodos clase Imap
* getInstance()
* listMailBoxes()
* headerMsg($numMsg, $uid = false) $uid indica si es el id o el numero
* bodyMsg($numMsg, $uid = false) $uid indica si es el id o el numero 
* fetchOverview($numMsgStart, $numMsgEnd)
* numMsg() cantidad de mensages
* getUID($numMsg)
* getUIDBySearch($patten = 'all')
* getPart($msgNumber, $mimeType, $structure = false, $partNumber = false) 

## Ejemplo obtener cuerpo.
* Imap::getInstance()->getPart(17662, "TEXT/PLAIN");
* Imap::getInstance()->getPart(17681, "TEXT/HTML"); 
<<<<<<< HEAD
=======

## Ejemplo obtener cuerpo.
* Imap::getInstance()->getPart(17662, "TEXT/PLAIN");
* Imap::getInstance()->getPart(17681, "TEXT/HTML"); 
>>>>>>> develop

