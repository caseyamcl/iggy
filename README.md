Iggy
====

A Rapid Templating/Theming Framework
------------------------------------

[![Build Status](https://travis-ci.org/caseyamcl/iggy.png)](https://travis-ci.org/caseyamcl/iggy.png)

This application is a small PHP app to allow designers to get to work on designing
templates.  It is better than straight HTML in the following ways:

* Built-in support for SCSS, LESS, and JS CSS preprocessors
* Uses the powerful and simple [Twig](http://twig.sensiolabs.org/) templating tool
* Allows development and testing of custom error pages for any HTTP error type (404, 500, etc)
* Create new pages by creating new files and directories, just as with HTML
* Generates friendly URLs for your pages
* Unit-tested, PSR 0 thru 2-compliant modern PHP application
* No configuration needed.  Works out-of-the-box

Installation
------------

Download the and uncompress the latest release from Github:
 
* [Latest ZIP](https://raw.githubusercontent.com/caseyamcl/iggy/master/dist/iggy.zip)
* [Latset TAR](https://raw.githubusercontent.com/caseyamcl/iggy/master/dist/iggy.tgz)

Or, you can use Composer to create the project:

    composer create-project caseyamcl/iggy

Or, you can download and install it manually:

    git clone https://github.com/caseyamcl/iggy.git
    cp -r src/Resource/skel/* . 

Running It
----------

### Use the PHP Built-In Web server

If you wish to use PHP's built-in web server to work with Iggy, simply run the following
in your terminal:

     php -S localhost:8000 index.php
     
Be sure that you have a `content` and `assets` directory in whatever directory your `iggy.phar` file
is located.

### Use Apache or NGINX

To use Iggy on an Apache or NGINX server, rewrite all requests to run `iggy.phar`:

**Apache**

    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule . index.php [L]

**NGINX**

    location / {
        try_files $uri $uri/ index.php;
    }

Usage
-----

The Iggy file structure looks like this:

    /path/to/iggy
        iggy.phar     Application sourcecode
        index.php     Application runner
        content/      Put your pages and templates in here
        assets/       Put your CSS, JS, images, LESS, SASS, etc. in here
        
        LICENSE       Readme
        README.md     License
        CHANGELOG.md  Changelog

### Create Pages

To function correctly, Iggy requires that you create a `pages` directory
inside of the content directory.

All pages and templates you create should use the file extension *.html.twig*.

To create a **front page**, create a `pages/index.html.twig` file.  By default, Iggy
includes a sample index file.  You should delete it and create your own.

You can create **subpages** simply by creating other files inside of your `pages` directory. 
For example, your pages directory may look like this:

    pages/
        index.html.twig
        about.html.twig
        contact.html.twig
        store/
            index.html.twig
            products.html.twig
            singleproduct.html.twig
            checkout.html.twig
        admin/
            index.html.twig
            products.html.twig
        
This would create the following URLs in your Iggy application:

| File                          | URL                                      |
| ----------------------------- | ---------------------------------------- |
| index.html.twig               | http://your-iggy-url/                    |
| about.html.twig               | http://your-iggy-url/about               |
| contact.html.twig             | http://your-iggy-url/contact             |
| store/index.html.twig         | http://your-iggy-url/store               |
| store/products.html.twig      | http://your-iggy-url/store/products      |
| store/singleproduct.html.twig | http://your-iggy-url/store/singleproduct |
| admin/index.html.twig         | http://your-iggy-url/admin               |
| admin/products.html.twig      | http://your-iggy-url/admin/products      |

### Link to other Pages

You can link to other pages inside of your site dynamically by using the `{{ site_url() }}`
syntax.  For example, if you want to link to your *admin/products* page, you can create
a link in your template as follows:

    <a href="{{ site_url('admin/products') }}" title="Products Admin Page">Products<a>
    
If you do not specify an argument for `{{ site_url() }}`, it will return the base URL for your
Iggy site:

    <a href="{{ site_url() }}" title="Homepage">Go Home</a>
    
### Create Error Pages

If you want custom error pages, you can create them by first creating an `errors`
directory inside of your content directory.  Iggy includes some sample content by
default.  You can replace it with your own templates.

Create a default error page template to catch all errors:
 
    errors/default.html.twig
    
In addition, you can create custom error pages for different HTTP status code (400, 404,
500, etc) simply by creating templates with the code numbers in your errors directory:

    errors/
        500.html.twig
        404.html.twig
        default.html.twig
        
If you do not create any error templates, or if a template cannot be resolved for the type
of error generated, Iggy will produce a bare-bones default HTML error page.

#### Preview Errors Pages

Iggy will show you your error pages whenever something bad happens (404, 500), but you can also
invoke an error page whenever you want by using a special `_error` URL:

Show default error page:

    http://your-iggy-url/_error/
  
Show specific error pages:

    http://your-iggy-url/_error/404
    http://your-iggy-url/_error/500
    
### Create Non-Page Templates

You will probably want to create templates which should be included in pages via Twig,
but should not be browseable.

To do this, simply create another directory inside of the content directory and name it anything
besides `errors` or `pages`.  For example, Iggy includes a `templates` directory by default:

    templates/
        some-menu.html.twig
        jumbotron.html.twig
        footer.html.twig
        twocol-page.html.twig

Then, use Twig to `include` that template in your pages somewhere:

    ...
    {% include('templates/some-menu.html.twig') %}
    ...
    
Or, use Twig `extends` to extend a template:

    {% extends 'templates/twocol-page.html.twig' %}
    {% block col_one %}
       ...
    {% endblock %}
    ...
    
See Twig documentation for full syntax options for `include` and `extends`, as well as other tools.

You can create as many template directories as you like.

### Use Assets

Assets include any static, non-HTML content: CSS, JS, LESS, SASS, images, videos,
PDF files, etc.

Use the `assets` directory for these type of files.  Then, from within your pages, you 
can generate URLs for them by using the following syntax:

    {{ asset('path/to/asset') }}

For example, if you create *assets/img/whippet.jpg*, you would use the
following syntax in your template to generate a URL for it:

    {{ asset('img/whippet.jpg') }}
    
This can be used inside of an image tag like any other URL:

    <img src="{{ asset('img/whippet.jpg') }}" alt="Whippet" />
    
### Use CSS and JS Preprocessors

If you want to compile LESS, SCSS, or JS upon loading it, you can do so in
your templates:

    {{ less('path/to/less') }}
    {{ scss('path/to/scss') }}
    {{ js('path/to/js') }}
    
If the path you specify is a directory, then Iggy will combine all of the files it finds
inside of that directory (recursively) into a single file and then process it through
the LESS, SCSS, or JS preprocessor.

If the path you specify is a single file, Iggy will process only that file and return
its contents.

Leveraging Twig
---------------

The [Twig](http://twig.sensiolabs.org) Templating Engine includes a number of features to make your life easier,
and I heartily recommend you read the [Twig for Designers](http://twig.sensiolabs.org/doc/templates.html) documentation to make use of
them in Iggy.

One common task you may wish to do right off the bat is extend templates:

### Extending Templates

Most pages have common elements on them (header, footer, nav).  You can create a base template in
Twig and have all pages extend that:

**content/templates/base.html.twig**

    <!DOCTYPE html>
    <html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Your Site Title Here</title>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />

    <body>
    
        <h1>Hello Iggy!</h1>
        <hr />
       
        {% block page_content %}
            <p>Sub-templates should override this</p>
        {% endblock %}

    </body>
    </html>

**content/pages/index.html.twig**

    {% extends 'templates/base.html.twig' %}
    {% block page_content %}
    
    <p>
        This will override the stuff in the 'page_content' block
        in 'templates/base.html.twig'
    </p>
    
    {% endblock %}

You can have multiple blocks per template, and you can have multiple levels of inheritence
in Twig (e.g. a page may extend 'pages/index.html.twig').  You can also have multiple base templates.
There are very few limitations.
