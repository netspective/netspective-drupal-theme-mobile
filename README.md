#Netspective Mobile Theme Configuration

## REQUIREMENTS

- Netspective Mobile theme requires a Drupal 6.x or higher version.

## INSTALLATION

###1. DOWNLOAD THEME

- Download Netspective Mobile theme zip/tar file from github and copy it to the location.

    http://example.com/sites/all/themes/netspective-drupal-theme-mobile.tar.gz

-  Extract the tar file using the commands 
     
        $ tar -xvf  netspective-drupal-theme-mobile.tar.gz 

- Rename the folder to netspective_mobile.

        $ mv netspective-drupal-theme-mobile netspective_mobile 

- If theme directory already exists in the drupal installation directory then copy to the theme folder 

        $ cp netspective_mobile/ ../../drupa-6.x/sites/all/theme/

- Or create 'theme' folder and copy  the netspective theme to the directory
    
        $ mkdir /../../drupal1.x/sites/all/theme/

        $ cp netspective_mobile/ ../../drupa-6.x/sites/all/theme/

### INSTALL MODULE

- Login as Admin user and navigate to Administer > Site building > Themes. Check the 'Enabled' box next to the theme

- Select the Option button to make Netspective Mobile theme as Default theme and then click the 'Save Configuration' button at the bottom.

### UPDATE THE CONFIGURATION SETTINGS

- Navigate to below location:
    
             Administer > Site building > Themes

- Click configure link next to the Netspective Mobile theme to change the default settings, select the appropriate logo for the theme.

## AUTOMATIC REDIRECTION OF MOBILE SITE

- Download the Drupal Module 'Mobile tools' on [http://drupal.org/project/mobile_tools](http://drupal.org/project/mobile_tools)

- Extract the tar file using the commands
     
             $ tar -xvf  mobile_tools_6.x-2.x-dev.tar.gz

- Copy the extracted file to the modules folder in drupal installation directory

             $ cp mobile_tools/ ../../drupa-6.x/modules/

- Enable the mobile tools module in administer menu from the below location  

             Administer › Site building › Modules 

### MODULE SETTINGS

- To change the settings go to

             Administer › Site configuration › Mobile Tools

- For theme switching enter same URL for Mobile URL and Desktop URL values.
- On 'theme switching TAB' choose "Switch theme for a mobile device" option then select appropriate mobile theme in drop down list box.
- Assign theme for all device group(ipod, Android, iPhone).
- To manage the blocks layout go to 

             Administer › Site building › Blocks


##MORE INFORMATION

- For additional documentation, see the online Drupal handbook [reference](http://drupal.org/handbook).

- For Drupal theme Installation [reference](http://drupal.org/node/456)
