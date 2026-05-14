## Install Instructions for Sprint #5

### Vagrant/Packer

This requires you to have both vagrant, packer, and virtualbox downloaded and working.

* To install and run the current vagrant/packer scripts, first download the vagrant-packer folder under the code folder in the main repository
* Once downloaded, navigate to the packer-scripts directory in Powershell
* Once in that directory, type, without the quotes "packer build --var-file=.\variables-sample.json ./ubuntu18043-vanilla-multi-drives.json"
* This will start the packer script and may take several minutes to build
* Once the script finishes and the vagrant box is built, it will appear in the build directory. Move the box file from the build directory to the Ubuntu directory that is under the build directory
* Here you will type, without the quotes, "vagrant box add .\(name of the .box file) --name base"
* After this, the vagrant box will be added. So, the next step is to type, without quotes, "vagrant up"
* This will launch the vagrant box and after it is loaded, you can give the "vagrant ssh" command to enter the box. The username is vagrant and the password is vagrant.
* If you don't want to enter the machine, you can put the IP address, 192.168.33.10, into your browser and it should show server test PHP scripts that show that the Apache webserver is up and the database is connected to it and working.
* Once you are done, you can exit the vagrant box with the "exit" command and type "vagrant halt" to stop the machine.

### Website Instructions

* To view our the current build of our website, first download our repository.
* Then navigate to the code directory
* In the code directory, launch any of the .html files in your browser to see how it would look like in on the browser

### Estimote Cloud API & LTE Beacon Micro App Instructions. Please note this is NOT required to build the application. This is just a reference to how we build and deploy LTE beacon firmware

LTE / Bluetooth beacons can all be seen on the Estimote Cloud account. Here you can test and deploy
firmware to certain LTE beacons remotely. Currently we have this username and password restricted to our team and you will not be able to currently view this cloud account.

* Visit the cloud account here: https://cloud.estimote.com/
* Use login Email: XXXXXXXXXXX : Password: XXXXXXXXXXX
* Navigate to "IoT Apps" on the left hand side to deploy micro application code to LTE beacons
* Current micro application code is seen in Code > Javascript > beaconFirmware-v1.0.1.js. Copy and paste this code into the Estimote IDE to create and deploy the application.
    * See documentation here on how micro application Javascript syntax is written https://developer.estimote.com/lte-beacon/micro-app-api-reference/
    * These changes will reflect on what data is sent through the Estimote API seen below.
 * Each REST endpoint URL is seen on the Estimote IDE Platform (IoT Apps > Open Web IDE > REST endpoint URL).
    * These endpoints are used to fetch data projected from the LTE Beacon.
    * Note: Each IoT application uses a different REST endpoint URL.
