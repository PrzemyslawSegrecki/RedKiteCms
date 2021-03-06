#How to create a new theme for RedKite CMS

This document covers the process of creating a new theme for RedKite CMS.

Put simply, a theme is a just a collection of templates and assets to define a website design.

## The theme structure
When you build a custom theme it will be saved in the **app/plugins/RedKiteCms/Theme** folder, so to start creating a new theme, add a **MyCustomTheme** directory under that folder. A theme is a plugin in RedKite CMS and requires a **plugin.json** file placed in the root of the theme itself, so add that file and paste this code inside:

    // app/plugins/RedKiteCms/Theme/MyCustomTheme/plugin.json
    {
        "author": "[ YOUR NAME ]",
        "website": "[ YOUR WEBSITE ]"
    }

Obviously change the [ YOUR NAME ] and [ YOUR WEBSITE ] information to reflect yur own details.

The themes templates and assets lives under the **Resources** folder placed inside the themes root folder, so add that directory under the **MyCustomTheme** folder.

Here you need a **views** folder to handle the theme templates and a **public** folder to handle assets. This last folder should contain a **css** folder for stylesheets and a **js** folder for javascripts. RedKite CMS will symlink or copy everything placed under the **public** folder into the **/web/plugins/mycustomtheme** folder, the theme name has to be lowercase in order to make assets available on the web site. Create these folders as well.

To add a template just create a twig file under the **views** folder. Each file inside this directory is its own theme template.

Add a new **home.html.twig** file into the views folder and paste this code inside:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/home.html.twig #}
    {% extends base_template %}

    {% block body %}
        <div id="header">
        </div>

        <div id="menu">
        </div>

        <div id="content">
        </div>

        <div id="footer">
        </div>
    {% endblock %}

A template must always extend the template defined by the **base_template** variable, always available in theme's templates.

This template lives under the **lib/plugins/RedKiteCms/Core/RedKiteCms/Resources/views/Frontend** directory and it is named **base.html.twig**. The [redkite-cms-base-layout.md](https://github.com/redkite-labs/RedKiteCms/blob/master/docs/cookbook/redkite-cms-base-layout.md) explains that file in detail.

## Template slots
The code we pasted to the home template added four divs to the template itself, one to handle the website header, one to handle the website navigation menu, one for the main contents and one for the footer. At this stage the template does nothing, so we must add one or more **slots** to it.

A slot is just a virtual container for blocks and each block handles a content displayed on the page. Change the header div code as follows:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/home.html.twig #}
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                {{ slots.logo|default('')|raw }}
                </div>
            </div>
        </div>
    </div>

Ok we have introduced a lot of things here. Looking at that code from the template perspective, we added some Bootstrap instructions to implement the Bootstrap grid system, but looking at the code from a RedKite CMS perspective, the most important instruction is the twig one reported below:

    {{ slots.logo|default('')|raw }}

This is a simple declaration to understand. All it does is print the slot name value identified by the "logo" key of the "slots" variable.

The **slots** variable is always passed to the template and contains the rendered slots for that page.

### Repeat slots through website pages
The slot, as it is configured, will show different content on each page, which means that each page shows a different logo. In other words, you would have to add the logo on each page but this is not how you would expect a log to behave. It is usually the same one, repeated on all the pages of the website. Obviously this can be easily fixed, adding an extra instruction on the declaration.

Simply add a comment just before the slot declaration with the **repeat** instruction to make this work as it should.

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/home.html.twig #}
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                {# repeat: site #}{{ slots.logo|default('')|raw }}
                </div>
            </div>
        </div>
    </div>

In this case we want to repeat our logo on each page for each language, so we configured the site to repeat the slot at **site** level.

Behind the scenes, a slot is defined by an array, so when you print the logo slot, RedKite CMS prints all the blocks added to that slot.

## Define the pages created by RedKite CMS when a web site is started from a theme
When a user uses the theme for the first time they would expect to have at least a page defined, so you must configure the theme to achieve this first page. This is pretty straightforward because you just need to add a **pages** key to your **plugin.json** file. Go back to that file and add the following pages key:

    // app/plugins/RedKiteCms/Theme/MyCustomTheme/plugin.json
    {
        [...]
        "pages": {
            "homepage": "home"
        }
    }

This tells RedKite CMS to create a page called **homepage** based on the **home** template.

### Add a virtual host to handle the theme
When you use this theme, you will probably want to have some default content added to the slot.To add some blocks to slots you must configure a special virtual host to handle the theme as if it were a regular website. This means you handle the theme contents using the RedKite CMS interface. A virtual host could look like the following:

    # /etc/apache2/sites-available/000-default.conf
    <VirtualHost *>
      ServerName mycustom.theme
      DocumentRoot "/your/webserver/path/RedKiteCms/web"
      DirectoryIndex index.php

      <Directory "/your/webserver/path/RedKiteCms/web">
        AllowOverride All
      </Directory>
    </VirtualHost>

To define a valid virtual host to handle a theme, you must suffix it using the **.theme** token. This obviously supposes you are building your theme on a local computer. You still need to declare the **mycustom.theme** host in your **hosts** file, as follows:

    # /etch/hosts
    127.0.0.1	mycustom.theme

Open the http://mycustom.theme/login host in your browser and, if everything worked, you will see the login page. Sign in using the default credentials and open the **Control Panel**, click the **Dashboard** button and then go to **Themes** panel.

You should see the **MyCustomTheme** theme available in the right column: click the **Handle this theme** button and confirm the popup to start the website from scratch, using your custom theme.

### Add blocks to custom theme
What you should see is an empty page with an empty slot on the top. Now you must add a new block to that slot. Just click the **Control Panel** and enter in **Edit mode** then click over the slot and add a **Text** block from the panel just opened.

Now you can edit that block by clicking into it, change the default content to **My company** and format it as Heading 2.

Awesome! Now close the block by double clicking on the blocks area, click over the **Save as theme** button and confirm the popup when it opens.

Now every time you use this theme for a website the logo will be filled with the content you just added!

### Add external stylesheets
Let's give the logo some style. Add a new **style.css** file under the **public/css** folder, open it and paste this code inside:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/public/css/style.css #}
    #header {
      min-height: 60px;
      text-align: center;
    }
    #header h1 {
      margin: 20px 0;
    }

To add the stylesheet to your template add this code under the **extends** instruction to the **home.html.twig**:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/home.html.twig #}
    {% block post_external_stylesheets %}
        {{ parent() }}
        <link href="/plugins/mycustomtheme/css/style.css" rel="stylesheet" type="text/css" media="all" />
    {% endblock %}

As you can see the css link refers to the **/plugins/mycustomtheme/css/style.css** stylesheet. Reload the page and you should see the logo block aligned at the center of the page.

### More on repeating slots through website pages
Return back to the **home** template and change the menu div as follows:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/home.html.twig #}
    <div id="menu">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {# repeat: language #}{{ slots.menu|default('')|raw }}
                </div>
            </div>
        </div>
    </div>

Nothing has changed with the logo slot except for the **repeat** instruction which is now set to **language**. This switch tells RedKite CMS to repeat the menu for each website language.

Now change the content div as follows:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/home.html.twig #}
    <div id="content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">{{ slots.left_column|default('')|raw }}</div>
                <div class="col-md-6">{{ slots.content|default('')|raw }}</div>
                <div class="col-md-3">{{ slots.right_column|default('')|raw }}</div>
            </div>
        </div>
    </div>

Here there are three columns declared and each of them contains a slot. Every slot has a **repeat** switch specified, this tells RedKite CMS to repeat the slot at page level, so blocks change on each page of the site.

Lets try adding some styles to our **style.css** file:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/public/css/style.css #}
    #menu {
      min-height: 40px;
      text-align: center;
    }
    #menu ul {
      list-style-type: none;
      font-size: 16px;
    }
    #menu ul, #menu ul li {
      margin: 0px;
      padding: 0px;
      display: inline;
    }
    #menu ul li {
      padding: 0 10px;
    }
    #menu ul a {
      color: #c7254e;
    }

    #content {
      min-height: 400px;
    }

Now reload the page to display the slots.

Add a **Menu** block to the **menu** slot and three **Text** blocks on other slots then fill them with any content you like.

## Add a new template
Next we want to add a new template, so you need to create another twig file under the Theme's views folder: add the **internal.html.twig** file under the **views** folder. This template must share the header, the menu and the footer divs with the home template, so we have to do some refactoring on templates. Add a folder called **base** under the views folder and add a **base.html.twig** file under that folder.

Move the following code from the **home** template to the **base** template:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/base/base.html.twig #}
    {% extends base_template %}

    {% block post_external_stylesheets %}
        {{ parent() }}
        <link href="/plugins/mycustomtheme/css/style.css" rel="stylesheet" type="text/css" media="all" />
    {% endblock %}
    
    {% block body %}
        <div id="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        {# repeat: site #}{{ slots.logo|default('')|raw }}
                    </div>
                </div>
            </div>
        </div>
    
        <div id="menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        {# repeat: language #}{{ slots.menu|default('')|raw }}
                    </div>
                </div>
            </div>
        </div>

        <div id="content">
            {% block content %}{% endblock %}
        </div>

        <div id="footer">
        </div>
    {% endblock %}    
    
now change the **home** template as follows:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/home.html.twig #}
    {% extends 'MyCustomTheme/Resources/views/base/base.html.twig' %}

    {% block content %}
        <div class="container">
            <div class="row">
                <div class="col-md-3">{{ slots.left_column|default('')|raw }}</div>
                <div class="col-md-6">{{ slots.content|default('')|raw }}</div>
                <div class="col-md-3">{{ slots.right_column|default('')|raw }}</div>
            </div>
        </div>
    {% endblock %}
    
The **home** template now extends from the **base** template and **content** is the only div defined in this home template.

Now open the **internal.html.twig** template and paste this code inside:

    {# app/plugins/RedKiteCms/Theme/MyCustomTheme/Resources/view/internal.html.twig #}
    {% extends 'MyCustomTheme/Resources/views/base/base.html.twig' %}

    {% block content %}
        <div class="container">
            <div class="row">
                <div class="col-md-12">{{ slots.internal|default('')|raw }}</div>
            </div>
        </div>
    {% endblock %}

The **internal** template simply defines a slot name **internal**. To use this template you must add a new page, so open the **Control Panel**, click the **Dashboard** button and then go to the pages section.

Here click the **Add new** button, rename the page **internal** and choose the **internal** template from the **templates** combo box.

Please notice that the **base** theme is not listed with the available templates because it does not live under the views folder but inside a nested folder.

This means that RedKite CMS will only see available templates as being those inside the views folder root and skips over the ones placed inside the nested directories.

Click the **Plus icon**  to the left of the **internal** template name, change the permalink name to **en-gb-internal**, and then click the **Navigate to** button at the right of the **permalink** text field.

Now you are on the internal page. Add a new **Text** block into the **internal slot**, fill it with your content and you are done!

Edit the menu as follows:

    children:
      item1:
        value: 'Home'
        tags:
          href: 'en-gb-homepage'
        type: Link
      item2:
        value: 'Internal'
        tags:
          href: 'en-gb-internal'
        type: Link

We just linked the two pages in the menu. Just to try, open the **Control panel** click the **Stop editor** button then click the **Home** link to navigate to the homepage.

Just save again the theme by clicking the **Save as theme** button from the **Control panel**.

### Add a new page when the theme is used
Open the **plugin.json** file and add the new page as follows:

    // app/plugins/RedKiteCms/Theme/MyCustomTheme/plugin.json
    {
        [...]
        "pages": {
            [...]
            "internal": "internal"
        }
    }


## Use your custom theme
Now your theme is ready, so let's use it on a real website. Add a new redkite virtualhost as follows:

    # /etc/apache2/sites-available/000-default.conf
    <VirtualHost *>
      ServerName redkite
      DocumentRoot "/your/webserver/path/RedKiteCms/web"
      DirectoryIndex index.php

      <Directory "/your/webserver/path/RedKiteCms/web">
       AllowOverride All
      </Directory>
    </VirtualHost>

then add the host to available hosts:

    # /etch/hosts
    127.0.0.1	redkite

and restart your webserver

    sudo service apache2 restart

Now open the **http://redkite/login** website and sign in. Open the **Dashboard** and go to the **Themes** section, then click the **Start from this theme** button placed under the **MyCustomTheme** then confirm.

The page reloads and you will get the website using your custom theme!

Found a typo? Found something wrong in this documentation? [Just fork and edit it !](https://github.com/redkite-labs/RedKiteCms/edit/master/docs/book/redkite-cms-configuration.md)
