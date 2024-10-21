
<h1 align="center"># Aligent Chat Module</h1> 

<div align="center">
  <p>The Aligent Chat Module introduces system configuration settings for managing LiveChat configurations. This module also provides admin capabilities for updating default configurations of these data, log updated data with the admin user name and the time stamp required for tracking.
</p>
  <img src="https://img.shields.io/badge/magento-2-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

## Features
- System Configuration Fields:

    The module adds the following system configuration fields.

   - livechat_license_number
   - livechat_groups
   - livechat_params

    These fields can be managed both through the store configurations and admin form interface.

- Admin Form:

    The module provides an admin page with a form to input and update the default values for above LiveChat settings.

    Once the form is submitted:

  - The system configuration fields are updated.
  - A log file will be saved at root/var/log/livechat.log with the values for livechat settings, time stamp and the admin user name who updated data.


## Installation

1. To install the Aligent Chat Module, follow below steps:
  - Install the module via Composer
    ```
    composer require adheesha23/module-live-chat
    ```
  - Or, clone from the repository to directory app/code/Aligent   
    ```
    git clone git@github.com:adheesha23/module-live-chat.git Chat
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




