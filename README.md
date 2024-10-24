
<h1 align="center">Aligent Chat Module</h1> 

<div align="center">
  <p>The Aligent Chat Module introduces store configuration settings for managing LiveChat configurations. This module also provides admin capabilities for updating default configurations of these data, log updated data with the admin user name and the time stamp required for tracking.
</p>
  <img src="https://img.shields.io/badge/magento-2-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

## Features
- Store Configuration Fields:

    The module adds the following store configuration fields.

   - livechat_license_number
   - livechat_groups
   - livechat_params

    These fields can be managed both through the store configurations and admin form interface.

- Admin Form:

    The module provides an admin page with a form to input the default scope values for above LiveChat settings.

    Once the form is submitted:

  - The relevant store configuration fields are updated.
  - A log file will be saved at project root/var/log/live_chat.log with the values for livechat settings, time stamp and the admin user name who updated data.


## Installation

1. To install the Aligent Chat Module, follow below steps:
  - Install the module via composer
    ```
    composer require adheesha23/module-live-chat
    ```
  - Or, clone from the repository. Run below command from project root directory.   
    ```
    git clone git@github.com:adheesha23/module-live-chat.git app/code/Aligent/Chat
    ```
2. Enable the module:
    ```
    bin/magento module:enable Aligent_Chat
    ```
3. Then run below commands:
    ```
    bin/magento setup:upgrade
    bin/magento setup:di:compile
    bin/magento cache:clean
    bin/magento cache:flush
   ```
## Module Usage

### Magento Admin
- Once the module is installed go to Magento admin dashboard.
- Go to Stores menu tab and there 'Live Chat Configuration' section will appear. Under that,
  - Manage Default Chat Configurations - This will go to admin form that manage default chat configurations.
  - Chat Configuration - This will go to ALIGENT > Chat Configuration tab in store configurations page.

### Logging
- To check the updated settings log, go to project root/var/log folder and check live_chat.log file.
    ```
    cat live_chat.log 
    ```

## Test Cases
### Unit Test
- Run one of the below command from project root directory based on how the module was installed.
  - If installed via composer
    ```
    $(pwd)/vendor/bin/phpunit --bootstrap $(pwd)/dev/tests/unit/framework/bootstrap.php --configuration $(pwd)/dev/tests/unit/phpunit.xml.dist $(pwd)/vendor/adheesha23/module-live-chat/Test/Unit/    
    ```
  - Or if installed by cloning the repository
    ```
    $(pwd)/vendor/bin/phpunit --bootstrap $(pwd)/dev/tests/unit/framework/bootstrap.php --configuration $(pwd)/dev/tests/unit/phpunit.xml.dist $(pwd)/app/code/Aligent/Chat/Test/Unit/    
    ```



