# Magento2 Flysystem #

Magento2 Flysystem Module from [Flagbit](https://www.flagbit.de/) integrate [Flysystem](https://flysystem.thephpleague.com/)
an Abstraction for local and remote filesystems into Magento2 without overriding or breaking core media storage functions.

## Features ##

* Integrate Flysystem to configure different file storages like **sftp**, **local** or **cloud** (S3).
    *  Provide modularity to easily integrate more adapters in the projects which use it.
* Integrate image modal for Magento2 product and category image uploads.
    *  You can select from the same **file-pool** instead of uploading from local.
    *  So you can select an image like in the image selector for cms pages and blocks.
* ACL Configuration for insert, upload, delete files and for create, delete folders and more.
* Separate media page for fast access to flysystem media files. No need for WYSIWYG-Editor Button.

## Flysystem Adapters ##
* In core module integrated **local**, **ftp** and **sftp** adapters
* [**S3** Flysystem Adapter](https://github.com/flagbit/Magento2-Flysystem-S3) (install the additional magento2 module)
    
## Wiki Pages ##

* **User Guides**
    * [Installation & Configuration Guide](https://github.com/Flagbit/Magento2-Flysystem/wiki/Installation-&-Configuration-Guide)
    * [FAQ and Troubleshooting](https://github.com/Flagbit/Magento2-Flysystem/wiki/FAQ-and-Troubleshooting)
* **Developer Guides**
    * [Integration of Flysystem to Magento2](https://github.com/Flagbit/Magento2-Flysystem/wiki/Integration-of-Flysystem-to-Magento2)
    * [API Guide Magento2 Flysystem](https://github.com/Flagbit/Magento2-Flysystem/wiki/API-Guide-Magento2-Flysystem)
    * [Integrate a new Flysystem Adapter](https://github.com/Flagbit/Magento2-Flysystem/wiki/Integrate-a-new-Flysystem-Adapter)
    * [Use Flysystem in custom modules](https://github.com/Flagbit/Magento2-Flysystem/wiki/Use-Flysystem-in-custom-modules)

## Screenshots ##

**Backend Configuration**

![Magento2 Flysystem Backend Configuration](https://blog.flagbit.de/wp-content/uploads/2018/07/magento2_flysystem_backend_configuration.png "Magento2 Flysystem Backend Configuration")

**Select Product Image**

![Magento2 Flysystem Select Product Image](https://blog.flagbit.de/wp-content/uploads/2018/07/magento2_flysystem_select_product_image.png "Magento2 Flysystem Select Product Image")

**Select Category Image**

![Magento2 Flysystem Select Category Image](https://blog.flagbit.de/wp-content/uploads/2018/07/magento2_flysystem_select_category_image.png "Magento2 Flysystem Select Category Image")

**Modal File View**

![Magento2 Flysystem Modal File View](https://blog.flagbit.de/wp-content/uploads/2018/07/magento2_flysystem_file_view.png "Magento2 Flysystem Modal File View")

**ACL Configuration**

![Magento2 Flysystem ACL Configuration](https://blog.flagbit.de/wp-content/uploads/2018/07/magento2_flysystem_acl_configuration.png "Magento2 Flysystem ACL Configuration")

**Browse Media Content**

![Magento2 Flysystem Browse Media Content](https://blog.flagbit.de/wp-content/uploads/2018/07/magento2_flysystem_browse_media_content.png "Magento2 Flysystem Browse Media Content")