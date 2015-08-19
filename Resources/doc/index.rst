Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require mailxpert/apibundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the ``app/AppKernel.php`` file of your project:

.. code-block:: php

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new Mailxpert\APIBundle\MailxpertAPIBundle(),
            );

            // ...
        }

        // ...
    }

.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md

Step 3: Create your entity class
--------------------------------

On your application, you have to create an AccessToken entity. You need to extend the one from the bundle. You can adapt the following code:

.. code-block:: php

    <?php
    // src/AppBundle/Entity/AccessToken.php

    namespace AppBundle\Entity;

    use Mailxpert\APIBundle\Entity\AccessToken as BaseAccessToken;
    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity
     * @ORM\Table(name="mx_access_token")
     */
    class AccessToken extends BaseAccessToken
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        public function __construct()
        {
            parent::__construct();
            // your own logic
        }
    }
..


Step 4: Modify your config
--------------------------

In order to have the translation, enable the following line:

.. code-block:: yml

    # app/config/config.yml
        translator:     ~
..

You also need to configure the bundle. Provide the necessary information (you can obtain a client ID on https://dev.mailxpert.ch/ ).

You can use the following structure:

.. code-block:: yml

    # app/config/config.yml
    mailxpert_api:
        access_token_class: AppBundle\Entity\AccessToken
        oauth:
            client_id: ""
            client_secret: ""
            redirect_url: "http://example.com/mx/oauth/code"
..

Step 5: Import the routes from the module
-----------------------------------------

To be able to use the Login with mailXpert, you can import the following routes. You can also create your own controller inspired on the one from the Bundle.

.. code-block:: yml

    # app/config/routing.yml
    mx_api:
        resource: "@MailxpertAPIBundle/Resources/config/routing.xml"
        type: xml
..


Annexes
=======

Create an access token from the console
---------------------------------------

If you already have your access token values (you can get them from the Developer console at https://dev.mailxpert.ch/ ), you can enter them interactively via the console:

.. code-block:: bash

    app/console mailxpert:api:access_token:create
..