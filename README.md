Iggy
====

## A Rapid Templating/Theming Framework

[![Build Status](https://travis-ci.org/caseyamcl/iggy.png)](https://travis-ci.org/caseyamcl/iggy.png)

This application is a small PHP app that allows designers to get to work on designing templates.  It is better 
than straight HTML in the following ways:

* Built-in support for SCSS, LESS, and JS preprocessors with no external dependencies
* Uses the powerful and simple [Twig](http://twig.sensiolabs.org/) templating tool
* Allows development and testing of custom error pages for any HTTP error type (404, 500, etc)
* Create new pages by creating new files and directories, just as with HTML
* Generates friendly URLs for your pages
* Unit-tested, PSR 0 thru 2-compliant modern PHP application
* No configuration needed.  Works out-of-the-box

## Different ways to use Iggy

### Run as command-line application

First, install the Iggy executable in your system path:

```bash
$ sudo wget -o /usr/local/bin/iggy https://github.com/caseyamcl/iggy/releases/iggy.phar \
   && chmod +x /usr/local/bin/iggy
```

Then, you can run Iggy in any directory:

```bash
cd my-project/
$ iggy
```

Or, you can specify the path explicitly:

```bash
iggy path/to/my-project
```

Iggy will log to the console.  You can browse to your site in your favorite browser: <http://localhost:8000>

Iggy includes several command line options:

| Option           | Explanation                                                                       |
| ---------------- | --------------------------------------------------------------------------------- |
| `-l`, `--listen` | Specify a network interface to listen on; default is all `0.0.0.0` 
| `-p`, `--port`   | Specify a network port to listen on; default is `8000`
| `-v`, `-vv`      | Increase verbosity of output; will add timestamps and memory usage

Iggy also includes a simple skeleton site generator to get you started, if you wish to use it:

```bash
# Initialize a new Iggy site skeleton
iggy init [-f force] [path]

# Run the server
iggy [path]
```

To exit the server, use `^C`.

### Run on shared-host

Iggy can run in a shared hosting environment or under NGINX or Apache:

1. Move to the directory that you wish to serve the site from.
2. Download the latest Iggy: `wget -o /usr/local/bin/iggy https://github.com/caseyamcl/iggy/releases/iggy.phar`
3. Initialize a blank Iggy site: `php iggy.phar init ./content` (or if you have existing content, move it into `content`)
4. Create a file in the directory that to you wish serve the site from named `index.php`:

```php
<?php
require_once(__DIR__ . '/iggy.phar');
```

You'll also need to configure your shared hosting environment to route all requests through the `index.php` script.

If your host uses Apache (most do), you can add this to your `.htaccess` or VHOST configuration:

```apacheconfig
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]
```

If your host uses NGINX, the configuration should include the following:

```
location / {
    try_files $uri $uri/ index.php;
}
```

### Run with PHP built-in server

You can also use the built-in PHP server.

1. Move to the directory that you wish to serve the site from.
2. Download the latest Iggy: `wget -o /usr/local/bin/iggy https://github.com/caseyamcl/iggy/releases/iggy.phar`
3. Initialize a blank Iggy site: `php iggy.phar init ./content` (or if you have existing content, move it into `content`)
4. Run the following:

```bash
$ php -S localhost:8000 iggy.phar
```

### Run Docker container

You can use the official Iggy Docker container:

```bash
docker run -v /PATH/TO/YOUR/CONTENT:/srv --rm -p 8000:8000 caseyamcl/iggy
```

**Note:** The official Docker Iggy does not allow you to specify the listen interface, port, or path, since Docker
handles all of this for you.

```bash
```

The Iggy Docker container will also initialize a blank Iggy site if you wish:

```bash
docker run -v /PATH/TO/YOUR/CONTENT:/srv --rm -p 8000:8000 caseyamcl/iggy [-p path] 
```

### Include as part of another application

You can use Iggy libraries as part of another application.  Iggy uses PSR-7 requests, so you can integrate it with any
PSR-7 compliant HTTP library.

```bash
composer require caseyamcl/iggy
```

```php
$iggy = (new Iggy\RequestHandlerFactory())->build('path/to/content');
$response = $iggy->handle('/some/url');
```

## Usage

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
        index.twig
        about.twig
        contact.twig
        store/
            index.twig
            products.twig
            singleproduct.twig
            checkout.twig
            singleimage.jpeg
        admin/
            index.twig
            products.twig
        
This would create the following URLs in your Iggy application:

| File                     | URL                                         |
| ------------------------ | ------------------------------------------- |
| index.twig               | http://your-iggy-url/                       |
| about.twig               | http://your-iggy-url/about                  |
| contact.twig             | http://your-iggy-url/contact                |
| store/index.twig         | http://your-iggy-url/store                  |
| store/products.twig      | http://your-iggy-url/store/products         |
| store/singleproduct.twig | http://your-iggy-url/store/singleproduct    |
| store/singleimage.jpeg   | http://your-iggy-url/store/singleimage.jpeg |
| admin/index.twig         | http://your-iggy-url/admin                  |
| admin/products.twig      | http://your-iggy-url/admin/products         |

### Link to other Pages

You can link to other pages inside of your site dynamically by using the `{{ site_url() }}`
syntax.  For example, if you want to link to your *admin/products* page, you can create
a link in your template as follows:

    <a href="{{ site_url('admin/products') }}" title="Products Admin Page">Products<a>
    
If you do not specify an argument for `{{ site_url() }}`, it will return the base URL for your
Iggy site:

    <a href="{{ site_url }}" title="Homepage">Go Home</a>
    
### Create Error Pages

If you want custom error pages, you can create them by first creating an `_errors`
directory inside of your content directory.  Iggy includes some sample content by
default.  You can replace it with your own templates.

Create a default error page template to catch all errors:
 
    _errors/default.twig
    
In addition, you can create custom error pages for different HTTP status code (400, 404,
500, etc) simply by creating templates with the code numbers in your errors directory:

    _errors/
        500.twig
        404.twig
        default.twig
        
If you do not create any error templates, or if a template cannot be resolved for the type
of error generated, Iggy will produce a bare-bones default HTML error page.

#### Preview Errors Pages

Iggy will show you your error pages whenever something bad happens (404, 500), but you can also
invoke an error page whenever you want by browsing to the appropriate `_errors` page manually:

Show default error page:

    http://your-iggy-url/_errors/
  
Show specific error pages:

    http://your-iggy-url/_errors/404
    http://your-iggy-url/_errors/500
    
### Create Non-Page Templates

You will probably want to create templates which should be included in pages via Twig,
but should not be .

To do this, simply create another directory inside of the content directory and name it anything
besides `errors` or `pages`.  For example, Iggy includes a `_templates` directory by default:

    _templates/
        some-menu.twig
        jumbotron.twig
        footer.twig
        twocol-page.twig

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

Assets include any static, non-HTML content: CSS, JS, images, videos, audio, text files, PDF files, etc.

Include these files as per normal, and Iggy will render them normally.  Iggy includes a comprehensive list of
known mime-types.  If you wish to use 

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

## Leveraging Twig

The [Twig](http://twig.sensiolabs.org) Templating Engine includes a number of features to make your life easier,
and I heartily recommend you read the [Twig for Designers](http://twig.sensiolabs.org/doc/templates.html) documentation to make use of
them in Iggy.

One common task you may wish to do right off the bat is extend templates:

### Extending Templates

Most pages have common elements on them (header, footer, nav).  You can create a base template in
Twig and have all pages extend that:

**content/templates/base.twig**

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

**content/pages/index.twig**

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

## Known Issues

Due to a [fundamental design decision in Twig](https://github.com/twigphp/Twig/issues/2392), Iggy has a small memory 
leak.  If you load too many page requests (thousands upon thousands), Iggy may crash due to memory allocation failure.
In this case, simply restart Iggy.  This is just another reason not to run Iggy in a production environment.  

If you absolutely need to keep Iggy running, you can use a tool like [supervisor](http://supervisord.org/) to auto-restart
Iggy when it crashes.