#!/usr/bin/bash
#
# Clone the SD Card to attached USB drive:
#
# cd /tmp
# wget https://raw.githubusercontent.com/billw2/rpi-clone/master/rpi-clone
# lsblk
# sudo bash rpi-clone -f -U sda
#

sudo mkdir /logs
sudo chown -R pi:pi /logs
sudo chmod 777 /logs
logfile=/logs/install.log
echo "" >$logfile

# Error management
set -o errexit
set -o pipefail
#set -o nounset

usage() {
	cat 1>&2 <<EOF

This script configures a base Pi with OS updates, miner software and supporting tools.
It provides scripts to also install ExpressVPN as well as the Rasbian desktop. An 
option also exists to install a WiFi access point on a second WiFi adapter.

USAGE:
	$(basename $0) [parameters]

PARAMETERS:
	-ac <CODE>  Setup the WiFI country code. Default: "GB"
	-ad <DNS>   Setup the WiFI DNS redirection. Default: "8.8.8.8"
	-as <SSID>  Setup the WiFI access point SSID. Default: "MnrTOR"
	-ap <SSID>  Setup the WiFI access point Passphrase. Default: "Welcome123"
	-cs <SSID>  Connect the remote side to this SSID access point. Default: ""
	-cp <PASS>  The passphrase for the remote side SSID access point. Default: ""
	-gn <NAME>  Your pretty name for git checking in. Default: "Nigel Johnson"
	-ge <EMAIL> Your git registered email address. Default: nigel@nigeljohnson.net
	-gp <PASS>  The Personal Access Token you made on github 
	-ld         install LCD display drivers
	-ssd        Boot order: SSD -> SD Card
	-xa <PASS>  The ExpressVPN activation code
	-xl <PASS>  The ExpressVPN location for connection. Default: UKLO

	-h | --help Show this help and exit
	
	NOTE: If you want to configure the wifi, you will need to supply the remote side
	      SSID and passphrase. You will also need to have a wifi dongle plugged in
	      and presenting itself as 'wlan1' in your ifconfig

EOF
}
die() {
	[ -n "$1" ] && echo -e "\nError: $1\n" >&2
	usage
	[ -z "$1" ]
	exit
}

GIT_USERNAME="Nigel Johnson"
GIT_USERMAIL="nigel@nigeljohnson.net"
GIT_PAT=""
CLIENT_SSID=""
CLIENT_PASSPHRASE=""
AP_SSID="MnrTOR"
AP_PASSPHRASE="Welcome123"
AP_IP="10.10.1.1"
AP_CHANNEL=6
AP_WLAN=1
CCODE="GB"
OPENFLAG=""
DNS_IP="8.8.8.8"
XVPN_ACT=""
XVPN_LOC="uklo"
LCD=""
BOOT="0xf41"
BOOT_ORDER="SD Card -> SSD"

# You can now supply no paramters
#if [ $# -eq 0 ]; then
#	die
#fi

while [[ $# -gt 0 ]]; do
	case $1 in
	-ac)
		CCODE="$2"
		echo "WiFi country code: '$2'"
		shift
		;;
	-ad)
		DNS_IP="$2"
		echo "WiFi IP address: '$2'"
		shift
		;;
	-as)
		AP_SSID="$2"
		echo "AP SSID: '$2'"
		shift
		;;
	-ap)
		AP_PASSPHRASE="$2"
		echo "AP passphrase: '$2'"
		shift
		;;
	-cs)
		CLIENT_SSID="$2"
		echo "Remote side SSID: '$2'"
		shift
		;;
	-cp)
		CLIENT_PASSPHRASE="$2"
		echo "Remote side passphrase: '$2'"
		shift
		;;
	-gp)
		GIT_PAT="$2"
		echo "GIT PAT: '$2'"
		shift
		;;
	-gn)
		GIT_USERNAME="$2"
		echo "GIT name: '$2'"
		shift
		;;
	-ge)
		GIT_USERMAIL="$2"
		echo "GIT email address: '$2'"
		shift
		;;
	-ld)
		LCD="YES"
		echo "LCD Attached"
		;;
	-ld)
		BOOT="0xf14"
		BOOT_ORDER="SSD -> SD Card"
		echo "boot order '($BOOT) $BOOT_ORDER'"
		;;
	-xa)
		XVPN_ACT="$2"
		echo "ExpressVPN activation: '$2'"
		shift
		;;
	-xl)
		XVPN_LOC="$2"
		echo "ExpressVPN location: '$2'"
		shift
		;;
	-h | --help)
		usage
		exit 0
		;;
	*)
		die "Unknown option '$1'"
		;;
	esac
	shift
done

#[ -z "$GIT_PAT" ] && die "PAT for git access not configured"
#[ -z "$GIT_USERNAME" ] && die "git check-in name not configured"
#[ -z "$GIT_USERMAIL" ] && die "git check-in email address not configured"

echo ""
echo "####################################################################" | tee -a $logfile
echo "##" | tee -a $logfile
echo "## The configuration we will be using today:" | tee -a $logfile
echo "##" | tee -a $logfile
echo "##        Boot order : '${BOOT_ORDER}'" | tee -a $logfile
if [[ -n "$GIT_PAT" ]]; then
	echo "##  GIT checkin name : '${GIT_USERNAME}'" | tee -a $logfile
	echo "## GIT email address : '${GIT_USERMAIL}'" | tee -a $logfile
	echo "##  GIT access token : '${GIT_PAT}'"
else
	echo "##        GIT access : 'READ-ONLY"
fi

#echo "##" | tee -a $logfile

if [[ -n "$LCD" ]]; then
	echo "##       LCD drivers : 320x480 - Touch enabled" | tee -a $logfile
else
	echo "##       LCD drivers : Not configured" | tee -a $logfile
fi

#echo "##" | tee -a $logfile

if [[ -n "$CLIENT_SSID" && -n "$CLIENT_PASSPHRASE" ]]; then
	echo "##      WiFi Country : '${CCODE}'" | tee -a $logfile
	echo "##       Client SSID : '${CLIENT_SSID}'" | tee -a $logfile
	echo "## Client passphrase : '${CLIENT_PASSPHRASE}'"
	echo "##           AP SSID : '${AP_SSID}'" | tee -a $logfile
	echo "##     AP passphrase : '${AP_PASSPHRASE}'"
	echo "##     AP IP address : '${AP_IP}'" | tee -a $logfile
	echo "## AP DNS IP address : '${DNS_IP}'" | tee -a $logfile
else
	echo "##      Access Point : Not configured" | tee -a $logfile
fi

#echo "##" | tee -a $logfile

if [[ -n "$XVPN_ACT" ]]; then
	echo "##    VPN activation : '${XVPN_ACT}'"
	echo "##      VPN location : '${XVPN_LOC}'" | tee -a $logfile
else
	echo "##       Express VPN : Not configured" | tee -a $logfile
fi

echo "##" | tee -a $logfile
echo "####################################################################" | tee -a $logfile
echo "" | tee -a $logfile
echo "Shall we get started? Press return to continue"
echo ""
read ok

echo "## Update BIOS and core OS" | tee -a $logfile
echo "" | tee -a $logfile

# Ensure the base packages are up to date
echo "## Update core OS" | tee -a $logfile
sudo apt update -y
echo "## Ensure we have latest firmware available" | tee -a $logfile
sudo apt full-upgrade -y
echo "## Cleanup loose packages" | tee -a $logfile
sudo apt autoremove -y
echo "## Ensure we have latest firmware installed" | tee -a $logfile
sudo rpi-eeprom-update -a -d

echo "## Update the bootloader order SD -> USB card" | tee -a $logfile
cat >/tmp/boot.conf <<EOF
[all]
BOOT_UART=0
WAKE_ON_GPIO=1
ENABLE_SELF_UPDATE=1
BOOT_ORDER=$BOOT
EOF
sudo rpi-eeprom-config --apply /tmp/boot.conf

# Install core packages we need to do the core stuff later
echo "## Install core pacakges" | tee -a $logfile
sudo apt install -y lsb-release apt-transport-https ca-certificates git python3-dev python3-pip python3-pil automake autoconf pkg-config libcurl4-openssl-dev libjansson-dev libssl-dev libgmp-dev make g++ tor screen xserver-xorg x11-xserver-utils xinit openbox chromium-browser xserver-xorg-input-evdev

if [[ -n "$LCD" ]]; then
	echo "## Install LCD libraries" | tee -a $logfile
	# Mostly taken from https://github.com/jwa-7/goodtft-kiosk
	cd /tmp
	git clone https://github.com/goodtft/LCD-show.git
	cd LCD-show

	echo "## Updating boot config" | tee -a $logfile
	# Copy the Overlay across
	sudo cp ./usr/tft35a-overlay.dtb /boot/overlays/tft35a.dtbo

	# Update the booot file to map the correct font
	sudo sed -i -e 's/$/ fbcon=map:10 fbcon=font:ProFont6x11/' /boot/cmdline.txt

	# Enable the SPI for the display, i2c for the touch, and UART for something
	sudo bash -c 'cat >> /boot/config.txt' <<EOF
dtparam=i2c_arm=on
dtparam=spi=on
enable_uart=1
dtoverlay=tft35a:rotate=0
EOF

	# Configure the display layout and hooks
	echo "## Setting up framebuffer driver" | tee -a $logfile
	sudo bash -c 'cat > /usr/share/X11/xorg.conf.d/99-fbturbo.conf' <<EOF
Section "Device"
  Identifier "Allwinner A10/A13/A20 FBDEV"
  Driver     "fbturbo"
  Option     "fbdev" "/dev/fb0"
  Option     "SwapbuffersWait" "true"
EndSection

Section "InputClass"
  Identifier   "calibration"
  MatchProduct "ADS7846 Touchscreen"
  Driver       "evdev"
  Option       "Calibration" "3936 227 268 3880"
  Option       "EmulateThirdButton" "1"
  Option       "EmulateThirdButtonTimeout" "750"
  Option       "EmulateThirdButtonMoveThreshold" "30"
  #Option "InvertY" "true"
  Option "InvertX" "true"
EndSection

Section "Device"
  Identifier "uga"
  driver     "fbdev"
  Option     "fbdev" "/dev/fb0"
  Option     "ShadowFB" "off"
EndSection

Section "Monitor"
  Identifier "WSSP"
EndSection

Section "Screen"
  Identifier "primary"
  Device     "uga"
  Monitor    "WSSP"
EndSection

Section "ServerLayout"
  Identifier "default"
  Screen   0 "primary"
EndSection
EOF

	echo "## Setting up bash_profile" | tee -a $logfile
	# On login, if we are not attached to a terminal, launch the X display system
	bash -c 'cat > ~/.bash_profile' <<EOF
if [ -f ~/.bashrc ]; then
	. ~/.bashrc
fi

[[ -z "\$DISPLAY" && "\$XDG_VTNR" -eq 1 ]] && startx -- -nocursor
EOF
	# When the Xorg display starts, this is called
	echo "## Setting up openbox autostart" | tee -a $logfile
	sudo bash -c 'cat >> /etc/xdg/openbox/autostart' <<EOF
# Set the screen to dim after 60 seconds of idle
xset +dpms 60 120 180
# Remove exit errors from the config files that could trigger a warning
sed -i 's/"exited_cleanly":false/"exited_cleanly":true/' ~/.config/chromium/'Local State'
sed -i 's/"exited_cleanly":false/"exited_cleanly":true/; s/"exit_type":"[^"]\+"/"exit_type":"Normal"/' ~/.config/chromium/Default/Preferences
# Launch the browser
chromium-browser --noerrdialogs --incognito --touch-events --kiosk http://localhost/
EOF

	echo "## Setting autologin as pi user" | tee -a $logfile
	sudo systemctl set-default multi-user.target
	sudo ln -fs /lib/systemd/system/getty@.service /etc/systemd/system/getty.target.wants/getty@tty1.service
	sudo bash -c 'cat > /etc/systemd/system/getty\@tty1.service.d/autologin.conf' <<EOF
[Service]
ExecStart=
ExecStart=-/sbin/agetty --autologin pi --noclear %I \$TERM
EOF

fi

echo "## Disabling IPv6" | tee -a $logfile
sudo bash -c 'cat > /etc/sysctl.d/disable-ipv6.conf' <<EOF
net.ipv6.conf.all.disable_ipv6 = 1
EOF

echo "## Install bashrc hooks" | tee -a $logfile
bash -c 'cat >> ~/.bashrc' <<EOF
source /webroot/minertor-miner/res/bashrc
EOF

echo "## Install rc.local hooks" | tee -a $logfile
sudo cat /etc/rc.local | grep -v 'exit 0' | sudo tee /etc/rc.local.tmp >/dev/null
sudo rm /etc/rc.local
sudo mv /etc/rc.local.tmp /etc/rc.local
sudo bash -c 'cat >> /etc/rc.local' <<EOF
. /webroot/minertor-miner/res/rc.local
exit 0
EOF
sudo chmod 755 /etc/rc.local

echo ""
echo "####################################################################"
echo ""
echo " Install the correct version of PHP"
echo ""
echo "Press return to continue"
echo ""
read ok

# Update the package list with a repository that supports our needs and ensure we are up to date with that
echo "## Get repository signature" | tee -a $logfile
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "## Install ARM repository for latest PHP builds" | tee -a $logfile
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
echo "## Ensure we are up to date with that repository" | tee -a $logfile
sudo apt update -y
echo "## Install out-of-date packages" | tee -a $logfile
sudo apt upgrade -y
echo "## Remove the latest PHP (v8)" | tee -a $logfile
sudo apt remove -y --purge php8.0
echo "## Install the required version of PHP (v7.4)" | tee -a $logfile
sudo apt install -y nginx mariadb-server php7.4 php7.4-fpm php7.4-BCMath php7.4-bz2 php7.4-Calendar php7.4-cgi php7.4-ctype php7.4-cURL php7.4-dba php7.4-dom php7.4-enchant php7.4-Exif php7.4-fileinfo php7.4-FTP php7.4-GD php7.4-gettext php7.4-GMP php7.4-iconv php7.4-intl php7.4-json php7.4-LDAP php7.4-mbstring php7.4-mysql php7.4-OPcache php7.4-Phar php7.4-posix php7.4-Shmop php7.4-SimpleXML php7.4-SOAP php7.4-Sockets php7.4-tidy php7.4-tokenizer php7.4-XML php7.4-XMLreader php7.4-XMLrpc php7.4-XMLwriter php7.4-XSL
echo "## Cleanup loose packages" | tee -a $logfile
sudo apt autoremove -y

## Install composer
echo "## Install Composer for PHP" | tee -a $logfile
cd /tmp
wget -O composer-setup.php https://getcomposer.org/installer
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
sudo composer self-update

echo ""
echo "####################################################################"
echo ""
echo " Install the minertor-miner software"
echo ""
echo "Press return to continue"
echo ""
read ok

# The software is held in github so set that up and clone it to the right place
echo "## Cloning minertor-miner source code tree" | tee -a $logfile
sudo mkdir /webroot
cd /webroot
if [[ -n "$GIT_PAT" ]]; then
	git config --global credential.helper store
	git config --global user.email $GIT_USERMAIL
	git config --global user.name $GIT_USERNAME
	sudo git clone https://${GIT_PAT}:x-oauth-basic@github.com/nigeljohnson73/minertor-miner.git
else
	sudo git clone https://github.com/nigeljohnson73/minertor-miner.git
fi
sudo chown -R pi:pi minertor-miner
cd minertor-miner
sudo mysql --user=root <res/setup_root.sql
sudo mysql -uroot -pEarl1er2day <res/setup_db.sql
cp res/install/config_* .

## install the composer dependancies
echo "## Installing composer dependancies" | tee -a $logfile
composer install

## Install crontab entries to start the services
echo "## Installing service management startup in crontab" | tee -a $logfile
echo "# minertor-miner Miner configuration" | {
	cat
	sudo bash -c 'cat' <<EOF
1 0 * * * /usr/bin/php /webroot/minertor-miner/sh/service_update.php > /tmp/service_update.txt 2>&1
* * * * * /usr/bin/php /webroot/minertor-miner/sh/service_tick.php > /tmp/service_tick.txt 2>&1
EOF
} | crontab -

echo ""
echo "####################################################################"
echo ""
echo " Configure TOR, Nginx and sort WiFi/VPN"
echo ""
echo "Press return to continue"
echo ""
read ok

echo "## Configuring TOR" | tee -a $logfile
sudo cp /etc/tor/torrc /etc/tor/torrc.orig
sudo bash -c 'cat > /etc/tor/torrc' <<EOF
HiddenServiceDir /var/lib/tor/hidden_service/
HiddenServicePort 80 127.0.0.1:80
SocksPort 0.0.0.0:9050
SocksPolicy accept *
EOF
sudo service tor stop
sleep 1
echo "Starting TOR service"
sudo service tor start
sleep 1
sudo cat /var/lib/tor/hidden_service/hostname | tee /logs/darkweb_hostname.txt

echo "## Configuring Nginx" | tee -a $logfile
cd /var/www/
sudo mv html html_orig
sudo ln -s /webroot/minertor-miner html
sudo bash -c 'cat > /etc/php/7.4/fpm/pool.d/www.conf' <<EOF
[www]
user = www-data
group = www-data
listen = /run/php/php7.4-fpm.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 10
pm.start_servers = 3
pm.min_spare_servers = 1
pm.max_spare_servers = 5
EOF
sudo bash -c 'cat > /etc/nginx/sites-enabled/default' <<EOF
server {
    listen       80;
    server_name  _;
    root         /var/www/html;

    #try_files \$uri \$uri/ /index.php\$is_args\$args;	

    location / {
        fastcgi_connect_timeout 3s;
        fastcgi_read_timeout 10s;
        include fastcgi_params;
        fastcgi_param  SCRIPT_FILENAME  \$document_root/index.php;
        fastcgi_pass unix:/run/php/php7.4-fpm.sock;
    }
}
EOF
sudo systemctl reload php7.4-fpm
sudo systemctl restart nginx

if [[ -n "$CLIENT_SSID" && -n "$CLIENT_PASSPHRASE" ]]; then
	echo "## Deploying WiFi setup" | tee -a $logfile
	bash -c 'cat > ~/setup_wifi.sh' <<EOF
#/bin/sh
echo
echo "This is a one-way thing. Are you sure?"
echo ""
echo "Press return to continue"
echo ""
read ok
bash /webroot/minertor-miner/sh/configure_wifi.sh -c '$CLIENT_SSID' '$CLIENT_PASSPHRASE' -a '$AP_SSID' '$AP_PASSPHRASE' -i '$AP_IP' -d '$DNS_IP' -x '$CCODE' -f '$AP_CHANNEL' -l '$AP_WLAN' $OPEN_FLAG
EOF
else
	echo "## Skipping WiFi configuration" | tee -a $logfile
	bash -c 'cat > ~/setup_wifi.sh' <<EOF
#/bin/sh

echo "This file does nothing, have a look inside to see what to do"

# Run the following commands to Configure the WiFi. There are a lot of options to run through
# /webroot/minertor-miner/sh/configure_wifi.sh
#
EOF
fi

echo "## Deploying ExpressVPN setup" | tee -a $logfile
cd /tmp
wget https://www.expressvpn.works/clients/linux/expressvpn_3.11.0.16-1_armhf.deb
sudo dpkg -i expressvpn_*.deb
if [[ -n "$XVPN_ACT" && -n "$XVPN_LOC" ]]; then
	bash -c 'cat > ~/setup_vpn.sh' <<EOF
#/bin/sh
echo
echo "When asked, the code you need is: '$XVPN_ACT'"
echo ""
expressvpn activate
bash -c "expressvpn connect $XVPN_LOC > /dev/null" &
bash -c "sleep 30 && expressvpn autoconnect true" &
echo ""
echo "This session will now probably hang while the VPN connects."
echo "Give it a minute or so, then reboot to complete the config."
echo ""
EOF
else
	echo "## Skipping VPN configuration" | tee -a $logfile
	bash -c 'cat > ~/setup_vpn.sh' <<EOF
#!/bin/sh

echo "This file does nothing, have a look inside to see what to do"

# Run the following commands to make the VPN work
#
# Activate the VPN with your activation code
#    expressvpn activate
#
# Get a list of access points
#    expressvpn list
#
# Connect to the default 'smart' location, or supply one from the list 
#    expressvpn connect
#
# The connection above may hang the session as traffic is routed for the 
# first time, if so, just kill the session and connect, then set up 
# the auto connection to the last connected location
#    expressvpn autoconnect true
#
EOF
fi

echo "" | tee -a $logfile
echo "####################################################################" | tee -a $logfile
echo "" | tee -a $logfile
echo "A summary of this install can be foung in $logfile" | tee -a $logfile
echo "" | tee -a $logfile
echo "We are all done. Thanks for flying with us today and we value your" | tee -a $logfile
echo "custom as we know you have choices. The next steps for you are:" | tee -a $logfile
echo "" | tee -a $logfile
echo " * Reboot this raspberry pi" | tee -a $logfile
echo " * Optionally, install ExpressVPN (~/setup_vpn.sh)" | tee -a $logfile
echo " * Optionally, install Access Point (~/setup_wifi.sh)" | tee -a $logfile
echo "" | tee -a $logfile
echo "####################################################################" | tee -a $logfile
